<?php
    Warecorp::addTranslation('/modules/groups/xajax/action.resignHandleSendForm.php.xml');
    
    $objResponse = new xajaxResponse();
    $isValid        = true;
    //  проверить на существование текущей группы и текущего пользователя
    if ( $this->currentGroup->getId() === null || $this->_page->_user->getId() === null ) {
        $objResponse->addRedirect("/");
        $isValid = false;
    }
    if ( $isValid ) {
    	if ($confirm == 'true') {
    		$template = 'groups/resignhost.confirm.tpl';
            $_SESSION['resign']['subject'] = $subject;
            $_SESSION['resign']['body'] = $sbody;      
    	    $this->view->onclick = "xajax_privileges_resign_handle('','',false);";
    	    $Content = $this->view->getContents ( $template ) ;
    	    $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title('Resign Host');
            $popup_window->content($Content);
            $popup_window->width(500)->height(350)->open($objResponse);		
    	} else {
    	    //  Send email and pmb message to members
    	    $membersListObj = $this->currentGroup->getMembers();
    	    $membersListObj->setOrder('zua.login');
    	    $members = $membersListObj->getList();
    	    $subject = !empty($_SESSION['resign']['subject'])?$_SESSION['resign']['subject']:'';
            $sbody = !empty($_SESSION['resign']['body'])?$_SESSION['resign']['body']:'';
    	    
    	    if ( sizeof($members) > 0 ) {
                //--------------
                try { $client = Warecorp::getMailServerClient(); }
                catch ( Exception $e ) { $client = NULL; }
    
                if ( $client ) {
                    try {
                        $campaignUID = $client->createCampaign();
                        $client->setSender($campaignUID,  $this->_page->_user->getEmail(),  $this->_page->_user->getFirstname().' '.$this->_page->_user->getLastname() );
                        $client->setTemplate($campaignUID, 'RESIGN_MEMBERS_INFORMATION', HTTP_CONTEXT);
    
                        $params = new Warecorp_SOAP_Type_Params();
                        $params->loadDefaultCampaignParams();
                        $client->addParams($campaignUID, $params);
    
                        $recipients = new Warecorp_SOAP_Type_Recipients();
                        $pmbRecipients = array();
                        foreach ( $members as &$_member ) {
                            if ( $_member->getId() != $this->_page->_user->getId() ) {
                                $req = new Warecorp_Group_Resign_Requests();
                                $req->setGroupId($this->currentGroup->getId());
                                $req->setUserId($_member->getId());
                                $req->save();
    
                                $recipient = new Warecorp_SOAP_Type_Recipient();
                                $recipient->setEmail( $_member->getEmail() );
                                $recipient->setName($_member->getFirstname().' '.$_member->getLastname());
                                $recipient->setLocale( $_member->getLocale() );
                                $recipient->addParam('CCFID', Warecorp::getCCFID($_member));
                                $recipient->addParam('days_number', 60);
                                $recipient->addParam('group_host_login', $this->currentGroup->getHost()->getLogin());
                                $recipient->addParam('group_name', $this->currentGroup->getName());
                                $recipient->addParam('link_set_new_host', $this->currentGroup->getGroupPath('setnewhost').'access_code/'.md5($req->getId()).'/');
                                $recipient->addParam('message_body', $sbody);
                                $recipient->addParam('message_subject', $subject);
                                $recipient->addParam('recipient_full_name', $_member->getFirstname().' '.$_member->getLastname());
                                $recipient->addParam('SITE_LINK_UNSUBSCRIBE', $_member->getUserPath('settings'));
                                $recipients->addRecipient($recipient);
                                
                                $pmbRecipients[] = $_member->getId();
                            }
                        }
                        $client->addRecipients($campaignUID, $recipients);
                        
                        /* add callback to mailsrv campaign to sent PMB message */
                        $objCallback = new Warecorp_SOAP_Type_Callback();
                        $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                        $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                        $objCallback->setAction( 'callbackAddPMBMessage' );
                        $callbackUID = $client->addCallback($campaignUID, $objCallback);
            
                        $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                        $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                        $client->addCallbackParam($callbackUID, 'sender_id', $this->_page->_user->getId());
                        $client->addCallbackParam($callbackUID, 'sender_type', 'user');
                        $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                        unset( $pmbRecipients ); 
            
                        $client->startCampaign($campaignUID);
                    } catch ( Exception $e ) { throw $e; }
                }
    	    } 	
    	    $this->currentGroup->getMembers()->resignAsHost($this->_page->_user->getId());        
    	    $objResponse->addRedirect($this->currentGroup->getGroupPath('summary'));
    	}	
    } else {
    	    $this->view->visibility = true;
    	    $this->view->hostIsResidned = true;
    	
    	    if ($this->currentGroup->getGroupType() == "simple"){
    	        $Content = $this->view->getContents('groups/settings.resign.tpl');
    	    } elseif ($this->currentGroup->getGroupType() == "family"){
    	        $Content = $this->view->getContents('groups/settings.familyresign.tpl');
    	    }
    	    $Script = "document.getElementById('GroupSettingsResignAnchor').focus();";
    	
    	    $objResponse->addClear( "GroupSettingsMainContent_Content", "innerHTML" );
    	    $objResponse->addAssign( "GroupSettingsMainContent_Content", "innerHTML", $Content );
    	    $objResponse->addScript($Script);
    }
