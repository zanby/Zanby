<?php
    Warecorp::addTranslation("/modules/users/addressbook/action.import.php.xml");
    /**
     * Import stages:
     *   1. Select import source - file / webservice
     *       file - select google/yahoo/outlook, upload file form
     *       webservice - select provider (google, yahoo, msn), enter login and password
     *   2. preview contacts, change encoding, field separator
     *        skipped by webservice - encoding must be known by parser
     *
     *   3. contacts already registered in zanby. Checkboxes to make them friends
     *       (grayed that are already your friend)
     *       "add friends & go next" button
     *   4. contacts are not in zanby
     *       "add to addressbook" button
     *
     * original - facebook.com
     */
    
    /**
     * Processing
     *
     * Webservice:
     * Step1 - user types login, password, select service. Submitted form goes to step1.
     *  Validator of this form try log in to service, if success:
     *      generate random value
     *      stores login/pass/servicename in session by random value ($_SESSION['addressbook_import'][$rand] = array('type' = 'service', 'email' => '..', 'serviceName' = "..."))
     *      make redirect into step3, passing extra url parameters type=webservice, ind=$rand (/type/webservice/ind/$rand/)
     *
     * Step2.
     *
     *
     *
     * CSV/VCard/LDIF:
     * Step1 - user uploads file, check appropriate file format. Submitted form goes to step1
     *   Validator of this form checks if file exits and there are more than 0 bytes. If ok:
     *     Store file in temp directory.
     *     generate random value
     *     Save temp filename in session like ($_SESSION['addressbook_import'][$rand] = array('type' = 'file', 'filename' => '..')
     *      make redirect into step2, passing extra url parameters type=webservice, ind=$rand (/type/webservice/ind/$rand/)
     *
     *
     *
     * $_SESSION['addressbook'][$ind]]['contactsAdded'] values:
     *   false - there are now zanby users in your imported addresses
     *   integer - (integer) of zanby users was added into your friendslist
     *   unset - users was already notified about 2 previous events
     *
     *
     * Step3 - count contacts already in zanby users database
     *   count = 0  - set $_SESSION['addressbook'][$ind]]['contacts_added'] = false; redirect into step4
     *   count != 0 - $_SESSION['addressbook'][$ind]]['contacts_added'] will be integer
     *
     *
     */
    
    
    if ($this->_page->_user->getId() !== $this->currentUser->getId()){
        $this->_redirect("/");
    }
    
    $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
    $CfgFilesCacheLifeTime = 60*30; // half hour minute
    
    $this->params = $this->_getAllParams();
    $action = $this->_page->Template->getSmarty()->_tpl_vars['ACTION_NAME'];
    $this->view->action = $action;
    
    $this->_page->Xajax->registerUriFunction("addressbook_instruction", "/users/addressbookInstruction/");
    $step = ( isset($this->params['import']) && !empty($this->params['import']) ) ? (int)$this->params['import'] : 1;
    $step = ($step != 0) ? $step : 1;
    //var_dump($step);var_dump($this->params);exit;
    //print $step;
    if ($step == 1) {
        $formWsLogin = new Warecorp_Form('wsLogin', 'post', $this->currentUser->getUserPath($action). "import/1/");
        $formWsLogin->addRule('email', 'required', Warecorp::t('Fill email'));
        $formWsLogin->addRule('email', 'email', Warecorp::t('Enter correct Email Address'));
        $formWsLogin->addRule('password', 'required', Warecorp::t('Fill password'));
        $this->view->formWsLogin = $formWsLogin;
    
    
       if ($formWsLogin->isPostback() && $formWsLogin->validate($this->params)) {
            $webservice = new Warecorp_Import_Webservice($this->_page->_user->getId() );
             
            try {
                $res = $webservice->fetchContacts($this->params['email'], $this->params['password']);

                $rand = md5(mktime());

                $sessionCache = array(
                	'addressbook_import' => array(
                        $rand => array(
                        	'type' => 'webservice',
                			'contacts' => $res
                        )
                    )
                );
                $cache->save($sessionCache, 'addressbook_'.$rand, array(), $CfgFilesCacheLifeTime);
    
                $this->_redirect($this->_page->_user->getUserPath($action)."import/3/value/$rand");
                
            } catch (Warecorp_Import_ServiceDown_Exception $e) {
                $formWsLogin->addCustomErrorMessage( Warecorp::t('Webmail server error') );
            } catch (Warecorp_Import_WrongAccount_Exception $e) {
                $formWsLogin->addCustomErrorMessage( Warecorp::t('Bad email or password') );
            } catch (Warecorp_Import_NoPlugin_Exception $e) {
                $formWsLogin->addCustomErrorMessage( Warecorp::t('Unsupported webmail') );
            }
        }
    
        $formDirectly = new Warecorp_Form('emailDirectly', 'post', $this->currentUser->getUserPath($action). "import/1/");
        $formDirectly->addRule('email_list', 'required', Warecorp::t('Fill emails please'));
        $this->view->formDirectly = $formDirectly;
    
        if (isset($this->params['email_list'])) {
    
            if ($formDirectly->validate($this->params)) {
    
                $tmpEmailList = $this->params['email_list'];
                $emailList = explode("\n", $this->params['email_list']);
    
                if (count($emailList)>0 && $emailList[0]!='') {
                    $rand = md5(mktime());
    
                    $regex = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/';
                    foreach($emailList as $id => &$email) {
                        $email = trim($email);
                        if (!preg_match($regex, $email) ) {
                            $formDirectly->addCustomErrorMessage(Warecorp::t('%s is incorrect email', array($email)));
                            $errors[] = $email;
                            unset($emailList[$id]);
                        } else {
                            if (function_exists('checkdnsrr')) {
                                $tokens = explode('@', $email);
                                if (!(checkdnsrr($tokens[1], 'MX') || checkdnsrr($tokens[1], 'A'))) {
                                    $formDirectly->addCustomErrorMessage(Warecorp::t('%s is incorrect email', array($email)));
                                    $errors[] = $email;
                                    unset($emailList[$id]);
                                }
                            }
                        }
                    }
    
                    $sessionCache['addressbook_import'][$rand] = array('type' => 'emaillist',
                    'contacts' => $emailList
                    );
    
                    $cache->save($sessionCache, 'addressbook_'.$rand, array(), $CfgFilesCacheLifeTime);
    
                    if (count($emailList)==0 || isset($errors)) {
                        $formDirectly->addCustomErrorMessage(Warecorp::t('Please enter only correct emails.'));
                        $this->view->emailList = $tmpEmailList;
                    } else {
                        $this->_redirect($this->_page->_user->getUserPath($action)."import/3/value/$rand");
                    }
                }
            }
        }
    
        $formFile = new Warecorp_Form('file', 'post', $this->currentUser->getUserPath($action). "import/1/");
    
        $formFile->addRule('file_type', 'required', Warecorp::t('Enter correct file type'));
        //$formFile->addRule('copntacts_file', 'uploadedfile', 'Enter correct contacts file');  @ не работал валидатор класс Form.php
    
        $fileTypes = array(''                       => '',
        'Csv_Outlook'            => Warecorp::t('Outlook (csv)'),
        'Csv_OutlookExpress'     => Warecorp::t('Outlook Express (csv)'),
        'Csv_WindowsAddressBook' => Warecorp::t('Windows Address Book (csv)'),
        'Ldif_Thunderbird'       => Warecorp::t('Thunderbird (ldif)'),
        //                       'Csv_PalmDesktop'        => 'Palm Desktop (csv) @todo',
        'Vcf'                    => Warecorp::t('vCard'),
        //                       'Csv_Entourage'          => 'Entourage @todo',
        //                       'Vcf_MacOsAddressBook'   => 'Mac OS X Address Book @todo',
        );
    
        $fileExts = array(
        'Csv_Outlook'            => 'csv',
        'Csv_OutlookExpress'     => 'csv',
        'Csv_WindowsAddressBook' => 'csv',
        'Ldif_Thunderbird'       => 'ldif',
        'Vcf'                    => 'vcf'
        );
    
        $fileName = tempnam("/tmp", "import_file_");
        if ( isset($this->params['file_type']) && (!move_uploaded_file($_FILES['copntacts_file']['tmp_name'], $fileName)) ) {
            $formFile->addRule('_custom_error', 'required', Warecorp::t('Enter correct contacts file'));
        }
    
        if (isset($this->params['file_type']) && $formFile->validate($this->params)) {
    //*****
        $infilename = $_FILES['copntacts_file']['name'];
        $infile_ext=strrpos($infilename,'.')?strtolower(substr($infilename,strrpos($infilename,'.')+1)):"";
        if($infile_ext!==$fileExts[($this->params['file_type'])]) {
            /*$_SESSION['addressbook_import']['error'] = 1;*/
            $sessionCache['addressbook_import']['error'] = 1;
            $this->_redirect($this->_page->_user->getUserPath($action)."import/5/value/1");
        }
            $type = $this->params['file_type'];
            if (($type) && (isset($fileTypes[$type]))) {
                $rand = md5(mktime());
                /*
                $_SESSION['addressbook_import'][$rand] = array('type'        => 'file',
                'fileType'    => $type,
                'fileName'    => $fileName);
                */
                $sessionCache['addressbook_import'][$rand] = array('type'        => 'file',
                'fileType'    => $type,
                'fileName'    => $fileName);
                $cache->save($sessionCache, 'addressbook_'.$rand, array(), $CfgFilesCacheLifeTime);
                $this->_redirect($this->_page->_user->getUserPath($action)."import/2/value/$rand");
            }
        }
    
        $this->view->assign($this->params);
        $this->view->fileTypes = $fileTypes;
        $this->view->formFile = $formFile;
    
    // 22222222222222222222222222222222222222222
    } elseif ($step == 2) {
    
        $value = $this->params['value'];
    
        $sessionCache = $cache->load('addressbook_'.$value);
        $session = &$sessionCache['addressbook_import'][$value];
        /*$session = &$_SESSION['addressbook_import'][$value];*/
    
        switch ($session['type']) {
            case "webservice":
                break;
            case "file":
                $service = Warecorp_Import_File::factory($session['fileType'], $session['fileName']);
    
                $encodings = array("UTF-8"        => Warecorp::t("Unicode (UTF-8)"),
                "WINDOWS-1251" => Warecorp::t("Cyrillic (win)"),
                "KOI8-R"       => Warecorp::t("Cyrillic (koi)"),
                );
                if ((isset($this->params['items']['encoding'])) && (isset($encodings[$encoding = $this->params['items']['encoding']]))) {
    
                    $service->setEncoding($encoding);
                }
    
                $encodingSelected = $service->encoding;
                $session['encoding'] = $encodingSelected;
                $cache->save($sessionCache, 'addressbook_'.$value, array(), $CfgFilesCacheLifeTime);
                //ldif-files doesn't need encoding prompt
                @list($type, $class) = explode('_', $session['fileType']);
                if (($type == 'Ldif') || ($type == "Vcf")) {
                    $this->_redirect($this->_page->_user->getUserPath($action)."import/3/value/$value");
                }
                $contacts = $service->getContacts($this->_page->_user->getId());
                foreach ($contacts as &$contact){
                    $email = $contact->getEmails();
                    $user = new Warecorp_User('email', $email[0]);
                    if ($user->isExist) {
                        $contact = new Warecorp_User('id', $contact->getUserId());
                    }
                }
    
                $formViewContacts = new Warecorp_Form('viewContacts',
                'post',
                $this->currentUser->getUserPath($action). "import/2/value/$value/");
    
    
                if ($formViewContacts->validate($this->params)) {
    
                    if (isset($this->params['items']['continue'])) {
    
                        $this->_redirect($this->_page->_user->getUserPath($action)."import/3/value/$value");
                    } else {
                        //$this->_redirect($this->_page->_user->getUserPath("addressbook/import/4/value/$value");
                    }
                }
    
                $encodingSelected = $service->encoding;
                $cache->save($sessionCache, 'addressbook_'.$value, array(), $CfgFilesCacheLifeTime);
                break;
            default:
                $this->_redirectError(Warecorp::t('Incorrect import source type'));
        }
    
        $this->view->formViewContacts = $formViewContacts;
        $this->view->optionsEncodings = $encodings;
        $this->view->optionsEncodingSelected = $encodingSelected;
        $this->view->contacts = $contacts;
        $this->view->contactsCount = count($contacts);
    
    // 3333333333333333333333333333333333333333333
    } elseif ($step == 3) {
        $value = $this->params['value'];
    
        $sessionCache = $cache->load('addressbook_'.$value);
        $session = &$sessionCache['addressbook_import'][$value];

        /*$session = &$_SESSION['addressbook_import'][$value];*/
        switch ($session['type']) {
            case "webservice":
                $contacts = $session['contacts'];
                $_contacts = array();
                foreach ($contacts as &$contact) {
                    $user = new Warecorp_User('email', $contact->email);
                    if ($user->isExist && ($user->getId() != $this->_page->_user->getId())) {
                        $contactItem = new Warecorp_User_Addressbook_User();
                        $contactItem->setUserId($user->getId());
                        $contactItem->setContactOwnerId($this->_page->_user->getId());
                        $contact = $contactItem;
                        $_contacts[] = $contact;
                    }
                }
                $_contactsSession = $session['contacts'];
                break;
            case "emaillist":
                $contacts = $session['contacts'];
                $_contacts = array();
                foreach ($contacts as &$contact) {
                    $user = new Warecorp_User('email', $contact);
                    if ($user->isExist && ($user->getId() != $this->_page->_user->getId())) {
                        $contactItem = new Warecorp_User_Addressbook_User();
                        $contactItem->setUserId($user->getId());
                        $contactItem->setContactOwnerId($this->_page->_user->getId());
                        $contact = $contactItem;
                        $_contacts[] = $contact;
                    }
                }
                $_contactsSession = $session['contacts'];
                break;
            case "file":
                $service = Warecorp_Import_File::factory($session['fileType'], $session['fileName'], $session['encoding']);
                $contacts = $service->getContacts($this->_page->_user->getId());
                $_contacts = $contacts;
                $_contactsSession = $contacts;
                break;
            default:
                $this->_redirectError(Warecorp::t('Incorrect import source type'));
    
        }
        $users = array();
        $contactsEmails = array();
    
        
        $_alreadyPresent = array();
        foreach($_contacts as $contact) {
            if ($contact instanceof Warecorp_User_Addressbook_User) {
                $user = new Warecorp_User('email', $contact->getEmails());
                if ( $user->isExist && $user->getEmail() != $this->_page->_user->getEmail() && !in_array($user->getId(), $_alreadyPresent) ) {
                    $_alreadyPresent[] = $user->getId();
                    $users[] = $user;
                    $contactsEmails[] = $user->getEmail();
                }
            }
        }
        unset($_alreadyPresent);
    
        if (!count($users)) {
            /*if(count($_contacts)===0) {
                //$_SESSION['addressbook_import']['error'] = 2;
                $sessionCache['addressbook_import']['error'] = 2;
    
                $cache->save($sessionCache, 'addressbook_'.$value, array(), $CfgFilesCacheLifeTime);
                $this->_redirect($this->_page->_user->getUserPath($action)."import/5/value/$value");
            }*/
            $session['contactsAdded'] = false;
            $cache->save($sessionCache, 'addressbook_'.$value, array(), $CfgFilesCacheLifeTime);
            $this->_redirect($this->_page->_user->getUserPath($action)."import/4/value/$value");
        }
    
        $formAddFriends = new Warecorp_Form('addFriends',
        'post',
        $this->currentUser->getUserPath($action). "import/4/value/$value/");
    
    
        $oFriends = new Warecorp_User_Friend_List();
        $oFriends->setUserId($this->_page->_user->getId())->returnAsAssoc( false );
    
        $alreadyFriendsCount = 0;
        $friendsIds = array();
        foreach($oFriends->getList() as $friend) {
            $friendsIds[] = $friend->getFriend()->getId();
            if (in_array($friend->getFriend()->getEmail(), $contactsEmails)) $alreadyFriendsCount++;
        }
        $friendsIds[] = $this->_page->_user->getId();
    
        $addressbook = $this->_page->_user->getAddressbook();
        $addressbookContacts = $addressbook->getContacts()->getList();
        $addressbookEmails = array();
        $contactsEmails = array();
        $alreadyInAddressbook = 0;
        //    foreach ($_contacts as $id=>&$contact) {
        //        $contactEmail = $contact->getEmails();
        //        if ( (Warecorp_User::isUserExists('email', $contactEmail[0])) ) {
        //            unset ($_contacts[$id]);
        //        } else {
        //            $contactsEmails[] = $contactEmail[0];
        //        }
        //    }
        foreach($addressbookContacts as $item) {
            $itemEmail = $item->getEmails();
            if ($itemEmail[0] != "") {
                $addressbookEmails[] = $itemEmail[0];
                if (in_array($itemEmail[0], $contactsEmails)) {
                    $alreadyInAddressbook ++;
                }
            }
        }
    
        $this->view->addressbook = $addressbookEmails;
        $this->view->alreadyInAddressbookCount = $alreadyInAddressbook;
        $this->view->friends = $friendsIds;
        $this->view->formAddFriends = $formAddFriends;
        $this->view->users = $users;
        $this->view->usersCount = count($users);
        $this->view->alreadyFriendsCount = $alreadyFriendsCount;
    
    
     // 4444444444444444444444444444444444444444444444444444
    } elseif ($step == 4) {
        $value = $this->params['value'];
    
        $sessionCache = $cache->load('addressbook_'.$value);
        if ( false === $sessionCache ) {
            $this->_redirect($this->currentUser->getUserPath($action).'import/1');
        }
    
        /*$session = &$_SESSION['addressbook_import'][$value];*/
        $session = &$sessionCache['addressbook_import'][$value];
    
    
        switch ($session['type']) {
    
            case "webservice":
                $contacts = $session['contacts'];
                foreach ($contacts as $id=>&$contact) {
                    $user = new Warecorp_User('email', $contact->email);
                    if (!$user->isExist) {
                        $contactItem = new Warecorp_User_Addressbook_CustomUser();
                        $contactItem->setFirstName($contact->firstName);
                        $contactItem->setLastName($contact->lastName);
                        $contactItem->setEmail($contact->email);
                        $contactItem->setContactOwnerId($this->_page->_user->getId());
                        $contact = $contactItem;
                    }
                    else unset($contacts[$id]);
                }
                break;
    
            case "emaillist":
                $contacts = $session['contacts'];
                foreach ($contacts as $id=>&$contact) {
                    $user = new Warecorp_User('email', $contact);
                    if (!$user->isExist) {
                        $contactItem = new Warecorp_User_Addressbook_CustomUser();
                        $contactItem->setEmail($contact);
                        $contactItem->setContactOwnerId($this->_page->_user->getId());
                        $contact = $contactItem;
                    }
                    else unset($contacts[$id]);
                }
                break;
    
            case "file":
            //
                $service = Warecorp_Import_File::factory( $session['fileType'], $session['fileName'], $session['encoding'] );
                $contacts = $service->getContacts($this->_page->_user->getId());
                foreach ($contacts as $id=>&$contact){
                    if ($contact instanceof Warecorp_User_Addressbook_User) {
                        unset($contacts[$id]);
                    }
                }
                break;
            default:
                $this->_redirectError(Warecorp::t('Incorrect import source type'));
    
        }
    
        $formAddFriends = new Warecorp_Form('addFriends',
        'post',
        $this->currentUser->getUserPath($action). "import/4/value/$value/");
    
    
    //$mycont = $contacts;
    /*foreach($contacts as $id=>$cont1) {
    }
    */
    //unset($contact);
    //unset($id);
    
        $countFriended = 0;
        if (($formAddFriends->validate($this->params)) && (isset($this->params['items']['add'])) && isset($this->params['items']['contacts'])) {
    
            foreach($this->params['items']['contacts'] as $email=>$userId) {
                // add contact to addressbook
                $user = new Warecorp_User('email', $email);
                $addressbook = $this->_page->_user->getAddressbook();
    
                if (!$addressbook->isContactUserExist($this->_page->_user->getId(),$email)) {
                    $contact = new Warecorp_User_Addressbook_User();
                    $contact->setUserId($user->getId());
                    $contact->setContactOwnerId($this->_page->_user->getId());
                    $contact->save();
                }
    
                if (! Warecorp_User_Friend_Item::isUserFriend($this->_page->_user->getId(), $userId)
                && $this->_page->_user->getId() != $userId) {
                    $oFriends = new Warecorp_User_Friend_Request_Item();
                    $oFriends->setSenderId($this->_page->_user->getId());
                    $oFriends->setRecipientId($userId);
                    $oFriends->setRequestDate(time());
    
                    if ($oFriends->save()) {
                        $objUser = new Warecorp_User('id', $userId);
                        $objUser->sendFriendInvite( $this->_page->_user, $objUser, $oFriends, Warecorp::t("No message") );
                    }
                }
            }
            $countFriended ++;
        }
        $formAddContacts = new Warecorp_Form('addContacts',
        'post',
        $this->currentUser->getUserPath($action). "import/4/value/$value/");
    
    
        if ($formAddContacts->validate($this->params)) {
            $invited = 0;
            if (isset($this->params['items']['add']) && isset($this->params['items']['contacts'])) {
    
                $emails = array_keys($this->params['items']['contacts']);
                $tempContacts = $contacts;
                //-----------
                try { $client = Warecorp::getMailServerClient(); }
                catch ( Exception $e ) { $client = NULL; }
    
                if ( $client ) {
                    try {
                        /**
                         * don't need to send message to pmb system
                         * @author Artem Sukharev
                         */
                        $campaignUID = $client->createCampaign();
                        $client->setSender($campaignUID, $this->_page->_user->getEmail(), $this->_page->_user->getFirstname().' '.$this->_page->_user->getLastname() );
                        $client->setTemplate($campaignUID, 'USERS_INVITE_EXTERNAL', HTTP_CONTEXT);
    
                        $params = new Warecorp_SOAP_Type_Params();
                        $params->loadDefaultCampaignParams();
                        $client->addParams($campaignUID, $params);
    
                        $recipients = new Warecorp_SOAP_Type_Recipients();
                        $addedEmails = array();
                        foreach($tempContacts as $tempContact) {
                            $contactEmail = $tempContact->getEmails();
                            if ( in_array($contactEmail[0], $emails) && !in_array($contactEmail[0], $addedEmails) ) {
                                $tempContact->save();
                                $addedEmails[] = $contactEmail[0];
    
                                $recipient = new Warecorp_SOAP_Type_Recipient();
                                $recipient->setEmail( $contactEmail[0] );
                                $recipient->setName($tempContact->getFirstName().' '.$tempContact->getLastName());
                                $recipient->setLocale( null );
                                $recipient->addParam('CCFID', Warecorp::getCCFID($tempContact));
                                $recipient->addParam('recipient_full_name', $tempContact->getFirstName().' '.$tempContact->getLastName());
                                $recipient->addParam('sender_full_name', $this->_page->_user->getFirstname().' '.$this->_page->_user->getLastName());
                                $recipient->addParam('sender_login', $this->_page->_user->getLogin());
                                $recipients->addRecipient($recipient);
    
                                $oFriendsRequests = new Warecorp_User_Friend_Request_List();
                                if (!$oFriendsRequests->setSenderId($this->_page->_user->getId())->setEmail($contactEmail[0])->getCount()) {
                                    $request = new Warecorp_User_Friend_Request_Item();
                                    $request->setSenderId($this->_page->_user->getId());
                                    $request->setRequestDate(time());
                                    $request->setEmail($contactEmail[0]);
                                    $request->save();
                                    $sendRequest = false;
                                }
                                $invited++;
                            }
                        }
                        $client->addRecipients($campaignUID, $recipients);
                        $client->startCampaign($campaignUID);
                    } catch ( Exception $e ) { throw $e; }
                }
            }
            /*$_SESSION['addressbook_import']['invited'] = $invited;*/
            $sessionCache['addressbook_import']['invited'] = $invited;
            $cache->save($sessionCache, 'addressbook_'.$value, array(), $CfgFilesCacheLifeTime);
            $this->_redirect($this->_page->_user->getUserPath($action)."import/5/value/$value");
    
        } else {
            $addressbook = $this->_page->_user->getAddressbook();
            $addressbookContacts = $addressbook->getContacts()->getList();
            $addressbookEmails = array();
            $contactsEmails = array();
            $alreadyInAddressbook = 0;
    
            foreach ($contacts as $id=>$cont2) {
                $contactEmail = $cont2->getEmails();
                if ( (Warecorp_User::isUserExists('email', $contactEmail[0])) ) {
    //            if ( (Warecorp_User::isUserExists('email', $contact->getEmail())) ) {
                    unset ($contacts[$id]);
                } else {
                    $contactsEmails[] = $contactEmail[0];
                }
            }
    /*
            foreach ($contacts as $id=>&$contact) {
                $contactEmail = $contact->getEmails();
                if ( (Warecorp_User::isUserExists('email', $contactEmail[0])) ) {
                    unset ($contacts[$id]);
                } else {
                    $contactsEmails[] = $contactEmail[0];
                }
            }
    */
    
            foreach($addressbookContacts as $item) {
                $itemEmail = $item->getEmails();
                if ($itemEmail[0] != "") {
                    $addressbookEmails[] = $itemEmail[0];
                    if (in_array($itemEmail[0], $contactsEmails)) {
                        $alreadyInAddressbook ++;
                    }
                }
            }
        }
    
        $this->view->items = @$this->params['items'];
    
        $this->view->contacts = $contacts;
        $this->view->addressbook = $addressbookEmails;
    
        $this->view->contactsCount = count($contacts);
        $this->view->countFriended = $countFriended;
        $this->view->alreadyInAddressbookCount = $alreadyInAddressbook;
    
        $this->view->formAddContacts = $formAddContacts;
    } elseif ($step == 5) {
        $value = isset($this->params['value'])?$this->params['value']:'';
    
        $sessionCache = $cache->load('addressbook_'.$value);
    
        /*
        if (isset($_SESSION['addressbook_import']['invited']))
            $this->view->invited = $_SESSION['addressbook_import']['invited'];
        */
    
        if (isset($sessionCache['addressbook_import']['invited']))
            $this->view->invited = $sessionCache['addressbook_import']['invited'];
    
    /*
        if (isset($_SESSION['addressbook_import']['error'])){
            $this->view->error = $_SESSION['addressbook_import']['error'];
        } else {
            $this->view->error = 0;
        }
    */
        if (isset($sessionCache['addressbook_import']['error'])){
            $this->view->error = $sessionCache['addressbook_import']['error'];
        } else {
            $this->view->error = 0;
        }
        /*
        unset($_SESSION['addressbook_import']);
        */
    
        $cache->remove('addressbook_'.$value);
    }
    
    $this->view->bodyContent = "users/addressbook/import$step.tpl";
