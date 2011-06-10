<?php
    Warecorp::addTranslation('/modules/groups/action.setnewhost.php.xml');
    
    if ( $this->_page->_user->getId() === null ) { $this->_redirectToLogin();}
    if( !isset($this->params['access_code']) ) { $this->params['access_code'] = empty($_SESSION['access_code']) ? '' : $_SESSION['access_code']; }
    if (empty($this->params['access_code']) || !($req = Warecorp_Group_Resign_Requests::getRequestByHash($this->params['access_code']))) $this->_redirect($this->currentGroup->getGroupPath('summary'));    

    if ($req->getUserId() != $this->_page->_user->getId()) {
        $this->_page->_user->logout();
        $this->_redirectToLogin();
    }
    
    $_SESSION['access_code'] = $this->params['access_code'];
    $req_id = $req->getId();
    $condition = (boolean)$req_id || ($this->currentGroup->getHost()->getId() === null && $this->currentGroup->getMembers()->isMemberExists($this->_page->_user->getId()));
    if ( $condition ) {    	
    	$group_type = ($this->currentGroup->getGroupType() == 'simple') ? 1 : (($this->currentGroup->getGroupType() == 'family') ? 2 : 0);
   	
    	if ( isset($this->params['yes']) && $group_type != 0 ) {
            //unset($_SESSION['access_code']);                		
        	$req = new Warecorp_Group_Resign_Requests($req_id); 
        	$this->currentGroup->getMembers()->changeHost($this->_page->_user);
            /* send email to old host */
        	$this->currentGroup->sendResignThankNewHost( $this->currentGroup, $this->_page->_user );
                
            /**
             * send message to simple group members
             * NOTE: for family it hasn't be used
             * @author Artem Sukharev
             */
            if ( $this->currentGroup->getGroupType() != 'family' ) {
        	    $membersListObj = ($group_type == 1) 
        	       ? $this->currentGroup->getMembers()
        	       : $this->currentGroup->getMembers()->setMembersRole(array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST));
        	    $membersListObj->setOrder('zua.login');
        	    $members = $membersListObj->getList();
                    
                if ( sizeof($members) != 0 ) {
                    //-----------
                    try { $client = Warecorp::getMailServerClient(); }
                    catch ( Exception $e ) { $client = NULL; }
        
                    if ( $client ) {
                        try {
                            $campaignUID = $client->createCampaign();
                            $client->setSender($campaignUID, $this->_page->_user->getEmail(), $this->_page->_user->getFirstname().' '.$this->_page->_user->getLastname());
                            $request = $client->setTemplate($campaignUID, 'RESIGN_NEW_HOST_MEMBERS_INFORMATION', HTTP_CONTEXT);
        
                            $params = new Warecorp_SOAP_Type_Params();
                            $params->loadDefaultCampaignParams();
                            $client->addParams($campaignUID, $params);
        
                            $recipients = new Warecorp_SOAP_Type_Recipients();
                            $pmbRecipients = array();
                            foreach ( $members as &$_member ) {
                                if ( $_member->getId() != $this->_page->_user->getId() ) {
                                    $recipient = new Warecorp_SOAP_Type_Recipient();
                                    $recipient->setEmail( $_member->getEmail() );
                                    $recipient->setName($_member->getFirstname().' '.$_member->getLastname());
                                    $recipient->setLocale( $_member->getLocale() );
                                    $recipient->addParam('CCFID', Warecorp::getCCFID($_member));
                                    $recipient->addParam('group_host_login', $this->_page->_user->getLogin());
                                    $recipient->addParam('group_name', $this->currentGroup->getName());
                                    $recipient->addParam('recipient_full_name', $_member->getFirstname().' '.$_member->getLastname());
                                    $recipient->addParam('link_group_host_profile', $this->_page->_user->getUserPath('profile'));
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
            }

            if ( $this->currentGroup->getGroupType() == 'family' ) {
                $this->_page->_user->setMembershipPeriod( 'annualy' );
                $this->_page->_user->setMembershipPlan( 'premium' );
                $this->_page->_user->setMembershipDowngrade( new Zend_Db_Expr('NULL') );
                $this->_page->_user->save();
            }
            
    		$req->deleteAll();
    		$this->_redirect($this->currentGroup->getGroupPath('summary'));
    	} elseif (isset($this->params['no']) && $group_type != 0) {
            unset($_SESSION['access_code']);
    		$req = new Warecorp_Group_Resign_Requests($req_id);
    		$req->delete();
    		$this->_redirect($this->_page->_user->getUserPath('profile'));
    	} else {
    		$this->_page->breadcrumb = null;
    		$this->view->group = $this->currentGroup;
            $this->view->AccessCode = $this->currentGroup;
    		$this->view->bodyContent = 'groups/newhost.tpl';
    	}
    } else {
    	$this->_redirect($this->_page->_user->getUserPath('profile'));
    }
