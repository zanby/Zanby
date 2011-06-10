<?php
    Warecorp::addTranslation("/modules/users/messages/action.messagecompose.php.xml");
    
    /*function checkLogin($to) {
        return !Warecorp_User::isUserExists('login', $to);
    }*/

    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    $this->params = $this->_getAllParams();

    //$contactListsSelected = (isset($this->params['mailingList'])) ? array_values($this->params['mailingList']) : array();
    $contactsLists        = (isset($this->params['mail_lists']))  ? array_values($this->params['mail_lists'])  : array();
    $contactsGroups       = (isset($this->params['mail_groups'])) ? array_values($this->params['mail_groups']) : array();
    
    //array with selected groups
    $groupsSelected = array();
    //array with selected lists
    $listsSelected = array();

    $this->_page->Xajax->registerUriFunction ( "load_contact_list",      "/users/autocompleteLoadContactList/");
    $this->_page->Xajax->registerUriFunction ( "addFromAddressbook",     "/users/messagesAddAddressFromAddressbook/" );
    $this->_page->Xajax->registerUriFunction ( "addAddressToField",      "/users/messagesAddAddressToField/" );
    $this->_page->Xajax->registerUriFunction ( "deleteAddressFromField", "/users/messagesDeleteAddressFromField/" );
    $this->_page->Xajax->registerUriFunction ( "messageSend",            "/users/messageSend/" ) ;
    $this->_page->Xajax->registerUriFunction ( "deleteMessage",          "/users/deleteMessage/" ) ;
    $this->_page->Xajax->registerUriFunction ( "deleteMessageDo",        "/users/deleteMessageDo/" ) ;
    $this->_page->Xajax->registerUriFunction ( "closePopup",             "/ajax/closePopup/" ) ;
    // create <form>
    $form = new Warecorp_Form('messagecompose', 'post', $this->_page->_user->getUserPath('messagecompose'));
    
    if (isset($this->params['load'])) {
        $message = new Warecorp_Message_Standard($this->params['id']);
        if (!($message) || ($message->getOwnerId() != $this->_page->_user->getId())) {
            $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/');
            //?
        }
    	switch ($this->params['load'])
    	{
    	    case 'reply':
                $sender = $message->getSender()->getSenderDisplayName();
                $recipients = $sender;
                $subject = $message->getSubject();
                if (substr($subject,0,'4') != "Re: ") {
                    $subject = "Re: " . $subject;
                }
                //array for reply footer
                $replyFooter = array();
                $replyFooter['sender']      = $sender;
                $replyFooter['recipients']  = $message->getRecipientsStringName();
                $replyFooter['subject']     = wordwrap($message->getSubject(), 20, ' ', true);
                $replyFooter['date']        = $message->getCreateDate();
                $replyFooter['body']        = wordwrap($message->getBody(), 80);
                $this->view->replyFooter = $replyFooter;
                         // inserts $body at the every string's start
/*                         $_senderName = ($message->getSender()  instanceof Warecorp_User)?
                                                                $message->getSender()->getLogin()
                                                                :$message->getSender()->getName();
*/                       
                $body = $this->view->getContents('users/messages/reply.footer.tpl');
/*                         $body = "<br /><hr> <div class='znbFriendsTbCont znbFriendsTbContDate'>From:".$sender."</div>\n".
                                      "<div class='znbFriendsTbCont znbFriendsTbContDate'>To:".$message->getRecipientsStringName()."</div>\n".
                                      "<div class='znbFriendsTbCont znbFriendsTbContDate'>Subject:".wordwrap($message->getSubject(), 20,' ', true)."</div>\n".
                                      "<div class='znbFriendsTbCont znbFriendsTbContDate'>Date:".$message->getCreateDate()."</div>\n".
                                      "<div style='overflow-x:auto;'>".wordwrap($message->getBody(), 80)."</div>\n"; break;
*/
                break;
    	    case 'forward':
                $subject = $message->getSubject();
                if (substr($subject,0,'5') != "Fwd: ") {
                    $subject = "Fwd: " . $subject;
                }
    	        $body = $message->getBody(); 
                break;
    	    case 'draft':
                $recipients = $message->getRecipientsTargetEmails();
    	        $subject = $message->getSubject();
                $body = $message->getBody(); 
                break;
    	    default: 
                $this->_redirect($this->_page->_user->getUserPath('messagelist'));
    	}
    	//if (isset($sender) && ($message->getSender() instanceof Warecorp_User)) $this->view->recipient = $sender;
    	if (isset($recipients)) $this->view->target_emails = $recipients;
        if (isset($subject)) $this->view->subject = $subject;
        if (isset($body)) $this->view->body = $body;
    } else {
        //load message from db or create new
        if (isset($this->params['id']) && $this->params['id']) {
            $message = new Warecorp_Message_Standard($this->params['id']);
        } else {
            $message = new Warecorp_Message_Standard();
        }
        
        //array with registered recipients 
        $regRecipients = array();
        //array with not registered recipients
        $notRegRecipients = array();
        //getting recipients from 'To:' field
        if (!empty($this->params['target_emails'])){
            $allEmails = $message->parseRecipientString($this->params['target_emails']);
            foreach ($allEmails AS $itemEmail) {
                if (strpos($itemEmail, '@')){
                    $user = new Warecorp_User('email', trim($itemEmail));
                } else {
                    $user = new Warecorp_User('login', trim($itemEmail));
                }
                if ($user->getId()){
                    $regRecipients[] = $user;
                } else {
                    if (!in_array($itemEmail, $notRegRecipients) && strpos($itemEmail, '@')){
                        $notRegRecipients[] = trim($itemEmail);
                    } else {
                        $form->addCustomErrorMessage(Warecorp::t("Incorrect 'To:' field, user with login '%s' doesn't exist, please correct it", $itemEmail));
                    }

                }
            }
        }
        //getting recipients from groups
        if ($contactsGroups){
            foreach($contactsGroups AS $itemList){
                $itemGroup = Warecorp_Group_Factory::loadById($itemList);
                $regRecipients = array_merge($regRecipients, $itemGroup->getMembers()->getList());
                $groupsSelected[] = $itemGroup;
            }
        }
        //getting recipients from mailing list
        if ($contactsLists){
            foreach($contactsLists AS $itemList){
                $itemConcactList = new Warecorp_User_Addressbook_ContactList(false, 'id', $itemList);
                $listsSelected[] = $itemConcactList;
                foreach($itemConcactList->getContacts()->getList() AS $itemContact){
                    if ($itemContact instanceof Warecorp_User_Addressbook_User){
                        $regRecipients[] = $itemContact->getUser();
                    } else {
                        if (!in_array($itemContact->getEmail(), $notRegRecipients)){
                            $notRegRecipients[] = $itemContact->getEmail();
                        }
                    }
                }
            }
        }
        
        //array of unique user ids and array of temp unique recipients items 
        $uniqUsers      = array();
        $tempRecipients = array();
        //make array of registered users unique
        foreach ($regRecipients AS $user){
            if (!in_array($user->getId(), $uniqUsers)){
                $uniqUsers[] = $user->getId();
                $tempRecipients[] = $user;
            }
        }
        $regRecipients = $tempRecipients;
        // sending of message
        if ( isset($this->params['btnSend']) ) {
            /*if ($this->params['id']) {
                $message = new Warecorp_Message_Standard($this->params['id']);
            } else {
                $message = new Warecorp_Message_Standard();
            }*/
            //
            if (empty($this->params['mail_lists']) && empty($this->params['mail_groups']) && empty($this->params['target_emails'])) {
                $form->addRule('mail_lists[]', 'required', Warecorp::t('Please add contacts from Addressbook or enter field To'));
                $this->view->styleClass = 'znbFormErrorOuter';
            }
            
            /*$recipients = array_unique($message->stringToArray($this->params['recipient'], array(';',',', ' ')));
            if (!empty($this->params['mailingList'])) {
                foreach ($this->params['mailingList'] as $id) {
                	$pos = strpos($id, "group_");
                    if ($pos !== false) {
                    	$entity = explode("group_", $id);
	                    $entity = $entity[1];
	                    $recipientContactList = Warecorp_User_Addressbook_Factory::loadById(0,$entity);
	                    $recipientlist = $recipientContactList->getContacts()->getList();
                    } else {
                    	$recipientContactList = Warecorp_User_Addressbook_Factory::loadById($id);
                    	$recipientlist = $recipientContactList->getContacts()->getList( array(Warecorp_User_Addressbook_eType::USER) );
                    }
                    foreach ($recipientlist as $recipientContact) {
                        if ($recipientContact instanceof Warecorp_User) {
                            $recipient = $recipientContact;
                        } else {
                            $recipient = $recipientContact->getUser();
                        }
                       	$recipients[] = $recipient->getLogin(); 
                    }
                }
                $recipients = array_unique($recipients);
            }*/
            $form->addRule('subject',       'required',  Warecorp::t('Please enter Subject'));
            $form->addRule('subject',       'maxlength', Warecorp::t('Subject of your message is too long (maximum %s letters)', 100), array("max" => 100));
            //rule for correct form of taget_emails field
            //$form->addRule('target_emails', 'regexp',    Warecorp::t('Please enter valid field To'), array('regexp' => '/^((<((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))>|\S+)(,|,\s|$|\s$))*/'));
            $form->addRule('target_emails', 'regexp',    Warecorp::t('Please enter valid field To'), array('regexp' => '/^((\S+)(,|,\s|$|\s$))*$/'));
            /*foreach ($recipients as $recipient){
                 $form->addRule('recipient','callback', Warecorp::t("Incorrect 'To:' field, user with login '%s' doesn't exist, please correct it", $recipient), array('func' => 'checkLogin', 'params' => $recipient));
            }*/
            $form->addRule('body',          'required',  Warecorp::t('Please enter Message'));
            $form->addRule('body',          'maxlength', Warecorp::t('Body of your message is too long (maximum %s letters)', 65535), array("max" => 65535));
            if ( $form->validate($this->params) ) {
                //need to create new message if it is not in draft folder
                if ($message->getFolder() == Warecorp_Message_eFolders::DRAFT){
                    $notNewFlag = true;
                } else {
                    $notNewFlag = false;
                }
                $message->setRecipientsListFromArrayOfUsers( $regRecipients );
                $message->addRecipientsEmailsLike($notRegRecipients);
                $message->setSenderId( $this->_page->_user->getId() );
                $message->setOwnerId( $this->_page->_user->getId() );
                $message->setSubject($this->params['subject']);
                $message->setBody($this->params['body']);
                $message->setIsRead(1);
                $message->setFolder( Warecorp_Message_eFolders::SENT );
                $message->setFolderRecovery( null );
                //if message is in draft folder we need only to move it to SENT folder, not create new
                if ($message->getId() && $notNewFlag) {
                    $message->update();
                } else {
                    $message->save();
                }
                
                // save to inbox folder of recipients
                foreach ($regRecipients AS $recipient) {
                    $message = new Warecorp_Message_Standard();
                    $message->setRecipientsListFromArrayOfUsers( $regRecipients );
                    $message->addRecipientsEmailsLike($notRegRecipients);
                    $message->setSenderId( $this->_page->_user->getId() );
                    $message->setOwnerId( $recipient->getId() );
                    $message->setSubject($this->params['subject']);
                    $message->setBody($this->params['body']);
                    $message->setIsRead(0);
                    $message->setFolder( Warecorp_Message_eFolders::INBOX );
                    $message->setFolderRecovery( null );
                    $message->save();
                }

                $this->_page->_user->sendMessageToNotRegisteredUser( $this->_page->_user, $notRegRecipients, $this->params['subject'], $this->params['body'] );
               
                //reporting about sending
                $report = Warecorp::t("Sent");
                $complete = true;
            } else {
                //if ( !empty($this->params['recipient']) ) $this->view->recipient = $this->params['recipient'] ;
                if ( !empty($this->params['target_emails']) ) $this->view->target_emails = $this->params['target_emails'] ;
                if ( !empty($this->params['subject']) ) $this->view->subject = $this->params['subject'];
                if ( !empty($this->params['body'])  ) $this->view->body = $this->params['body'] ;
                //if ( !empty($this->params['mailingList'])  ) $this->view->contactId = $this->params['mailingList'] ;
            }
        } elseif ( isset($this->params['btnDraft'])) {
            $form->addRule('subject', 'required', Warecorp::t('Please enter Subject'));
            if ($form->validate($this->params)) {
                /*if ($this->params['id']) {
                    $message = new Warecorp_Message_Standard($this->params['id']);
                } else {
                    $message = new Warecorp_Message_Standard();
                }*/
            	//if ( isset($this->params['recipient'])) $message->setRecipientsListFromStringName($this->params['recipient']);
                //setting up recipients and body
                if ( !empty($this->params['body']) )
                    $message->setBody($this->params['body']);

                $message->cleanRecipientsList();
                if ( !empty($regRecipients) ) 
                    $message->setRecipientsListFromArrayOfUsers($regRecipients);
                if ( !empty($notRegRecipients) )
                    $message->addRecipientsEmailsLike($notRegRecipients);
                
                //saving draft message
                $message->setSenderId( $this->_page->_user->getId() );
                $message->setOwnerId( $this->_page->_user->getId() );
                $message->setSubject($this->params['subject']);
                $message->setIsRead(0);
                $message->setFolder( Warecorp_Message_eFolders::DRAFT);
                $message->setFolderRecovery( null );
                if ($message->getId()) {
                    $message->update();
                } else {
                    $message->save();
                }
                $report = Warecorp::t("Saved");
                $complete = true;
            } else {
                //if ( !empty($this->params['recipient']) ) $this->view->recipient = $this->params['recipient'] ;
                if ( !empty($this->params['target_emails']) ) $this->view->target_emails = $this->params['target_emails'] ;
                if ( !empty($this->params['subject']) ) $this->view->subject = $this->params['subject'];
                if ( !empty($this->params['body'])  ) $this->view->body = $this->params['body'] ;
            }
        }
    }
	// messages list
	$messageManager = new Warecorp_Message_List();
	$folderList = $messageManager->getMessagesFoldersList($this->_page->_user->getId());
    $this->view->folders = $folderList;

    // get friends
    /*$contactLists = array();
    foreach ( $this->currentUser->getAddressbook()->getContacts()->getList(array(Warecorp_User_Addressbook_eType::CONTACT_LIST, Warecorp_User_Addressbook_eType::GROUP)) as $contactList ) {
		if ($contactList instanceof Warecorp_User_Addressbook_Group)
        	$contactLists['group_'.$contactList->getGroupId()] = $contactList->getDisplayName();
        else $contactLists[$contactList->getContactId()] = $contactList->getDisplayName();
    }
    $this->view->contactLists = $contactLists;
    $this->view->contactListsSelected = $contactListsSelected;*/

    //assign selected groups and lests
    $formParams = array();
    $formParams['mail_lists'] = $listsSelected;
    $formParams['mail_groups'] = $groupsSelected;
    //$formParams['target_emails'] = $groupsSelected;
    $this->view->formParams = $formParams;

    $this->view->form = $form;
    
    $this->view->id = isset($this->params['id']) ? $this->params['id'] : '';

    if (isset($complete)) {
        $this->view->message = $report;
        $this->_page->showAjaxAlert($report);
        $this->_redirect($this->_page->_user->getUserPath('messagelist'));
    	//$this->view->bodyContent = 'users/messages/messagelist.tpl';
    } else {
        $this->view->bodyContent = 'users/messages/messagecompose.tpl';
    }
