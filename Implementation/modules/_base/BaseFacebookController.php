<?php
	Warecorp::addTranslation('/modules/facebook/facebook.controller.php.xml');

class BaseFacebookController extends Warecorp_Controller_Action
{
    private function checkConnection()  {
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( empty($facebookId) ) {
            if ( $this->isAjaxAction() ) {
                $objResponse = new xajaxResponse();
                exit($objResponse->printXml($this->_page->Xajax->sEncoding));
            } else {
                $this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/users/login/');
            }
        }
    }
    
    public function noRouteAction() { exit; }
    
    public function indexAction() {
	
		exit;
    }

    public function changeconnectionstateAction() {        
        $objResponse = new xajaxResponse();
        
        $_SESSION['FACEBOOK_SESSION_STATE'] = ( isset($_SESSION['FACEBOOK_SESSION_STATE']) ) ? $_SESSION['FACEBOOK_SESSION_STATE'] : 0;
        $_SESSION['FACEBOOK_SESSION_STATE'] = $this->getRequest()->getParam('state', 0);
        if ( $_SESSION['FACEBOOK_SESSION_STATE'] == 1 ) {
            $objResponse->addScript('document.location.reload();');
        }
        
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit; 
    }
    
    /**
     * Process user registration after using Facebook Connect login screen
     * @return unknown_type
     */
    public function processregistrationAction() {
        $this->checkConnection();
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( null === $user = Warecorp_Facebook_User::loadUserByFacebookId($facebookId) ) {
            $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/registration/index/mode/facebook/');
        } else {
            $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/facebook/confirmprofile/');
        }        
    }    
    /**
     * when user have z account with current FB id hi must confirm it
     * @return unknown_type
     */
    public function confirmprofileAction() {
		$this->checkConnection();
		Warecorp::addTranslation('/modules/facebook/confirmprofile.php.xml');
		
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( null === $user = Warecorp_Facebook_User::loadUserByFacebookId($facebookId) ) {
            $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/registration/index/mode/facebook/');
        }                
        $this->view->user = $user;
        //$this->view->form = $form;
        //$this->view->bodyContent = 'facebook/confirmfbprofile.tpl';
        $this->view->bodyContent = 'facebook/confirmprofile.tpl';
        $this->view->isRightBlockHidden = true;		
    }
    
    /**
     * when user have z account with current FB id hi must confirm it
     * @return unknown_type
     */
    public function confirmfbprofileAction() {
        exit;    
    }
    
    /**
     * Process user login after using Facebook Connect login screen
     * @return void
     */
    public function processloginAction() {
        $this->checkConnection();
        
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( null === $user = Warecorp_Facebook_User::loadUserByFacebookId($facebookId) ) {
            $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/registration/index/mode/facebook/');
        } else {
            $user->authenticate();     

            $url = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/';
            if ( isset($_SESSION['login_return_page']) ) {
                $url = $_SESSION['login_return_page'];
                unset($_SESSION['login_return_page']);
                $parsed_url = parse_url($url);
                $condition=(strstr($parsed_url['host'], BASE_HTTP_HOST) === BASE_HTTP_HOST);
                if (!$condition) $url = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/';//$url = $user->getUserPath('profile');            
            }
            
            if ( WP_SSO_ENABLED && Warecorp_Wordpress_SSO::isWordpressSiteEnabled() ) {
                $code = md5(uniqid(mt_rand(), true));
                $cache = Warecorp_Cache::getFileCache();
                $cache->save($user->getId(), 'SSO_'.$code, array(), Warecorp_Wordpress_SSO::LIFETIME);
                
                $this->_redirect(WP_SSO_URL.'?zssodoaction=signin&key='.$code.'&ret='.urlencode($url));
            } else {
                $this->_redirect($url);
            }
        }
    }
    
    /**
     * Process user link account after using Facebook Connect login screen
     * @return unknown_type
     */
	public function processlinkAction() {
	    /* TODO: anon check */
	    
		$this->checkConnection();
		Warecorp::addTranslation('/modules/facebook/processlink.php.xml');		
		$objResponse = new xajaxResponse();
		
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( $facebookId ) {
    		/* if z account isn't connected yet to FB account */
    		if ( !Warecorp_Facebook_User::isZAccountConnected($this->_page->_user->getId()) ) {
    			/* and if FB account isn't connected to any z account */
    			if ( null === $user = Warecorp_Facebook_User::loadUserByFacebookId($facebookId) ) {
    				$objFUser = new Warecorp_Facebook_User();
    				$objFUser->setFacebookId($facebookId);
    				$objFUser->setUserId($this->_page->_user->getId());
    				$objFUser->save();				
    				   				    				
    				$objResponse->addRedirect($this->_page->_user->getUserPath('networks'));
    				/* link Z account with FB account */
    				/*
    				$popup_window = Warecorp_View_PopupWindow::getInstance();        
    				$popup_window->title(Warecorp::t('Congratulation'));
    				$popup_window->content('<p>' . Warecorp::t('Congratulation! Your account has been connected to Facebook account. Just now you can use your Facebook account to log in our system.') . '</p>');
    				$popup_window->width(350)->height(100)->reload($objResponse);
    				*/        
    			}
    			/* if FB account is already connected to any Z account offer to confirm this association  */
    			/* TODO: now it just show information must be dialog window to switch to another account */
    			else {
                    $objFUser = new Warecorp_Facebook_User($facebookId);
                    $objFUser->setUserId($this->_page->_user->getId());
                    $objFUser->save();              
    			    
    				$objResponse->addRedirect($this->_page->_user->getUserPath('networks'));
    				/* Z account already exists, show the message */
    				/*            
    				$popup_window = Warecorp_View_PopupWindow::getInstance();        
    				$popup_window->title(Warecorp::t('Information'));
    				$popup_window->content('<p>' . Warecorp::t('Your Facebook account is already connected to an account.') . '</p>');
    				$popup_window->width(350)->height(100)->reload($objResponse);        
    				*/
    			}
    		} 
    		/* z account is connected already to FB accoutn */
    		else {
                /* 1. если fbID = связанному; 2. если fbID не равен связанному; */
    		    $objFUser = Warecorp_Facebook_User::loadByUserId($this->_page->_user->getId());
    		    if ( $objFUser->getFacebookId() != $facebookId ) {
    		        $objFUser->setFacebookId($facebookId);
    		        $objFUser->save();
    		    }    		    
    		    $objResponse->addRedirect($this->_page->_user->getUserPath('networks'));
    		}
        }
		
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                                       
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function processunlinkAction() {
        $this->checkConnection();
        Warecorp::addTranslation('/modules/facebook/processunlink.php.xml');      
        $objResponse = new xajaxResponse();
        
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        /* if z account isn't connected yet to FB accoutn */
        if ( Warecorp_Facebook_User::isZAccountConnected($this->_page->_user->getId()) ) {
            $facebookUser = Warecorp_Facebook_User::loadByUserId($this->_page->_user->getId());
            $facebookUser->delete(true);
        }
        $objResponse->addRedirect($this->_page->_user->getUserPath('networks'));
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                                               
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
  	public function processremovepermissionAction() {
        $this->checkConnection();
        Warecorp::addTranslation('/modules/facebook/processremovepermission.php.xml');      
        $objResponse = new xajaxResponse();
        
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( $facebookId && $this->getRequest()->getParam('permission', null) ) {
            try {
                Warecorp_Facebook_User::removePermission($this->getRequest()->getParam('permission'));                
                $res = 0;
            } catch ( Exception $ex  ) { $res = 1; }
        }
        $objResponse->addScript('FBApplication.onupdate_permission_ready("'.$this->getRequest()->getParam('itemID', '').'", "'.$this->getRequest()->getParam('permission').'", '.$res.');');
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                                                 	    
  	}
	
  	/**
  	 * 
  	 * @return unknown_type
  	 */
	public function checksessionstateAction()
	{
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( empty($facebookId) ) $result = Zend_Json::encode(0);            
        else $result = Zend_Json::encode(1);
        header('Content-type: application/json; charset=UTF-8'); 
        exit($result);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function publishstreamAction() {
        $this->checkConnection();
        Warecorp::addTranslation('/modules/facebook/publishstream.php.xml');
        $objResponse = new xajaxResponse();
	    
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( $facebookId && $facebookUser = new Warecorp_Facebook_User($facebookId) ) {
            $isSend = false;
            if ( $facebookUser->canPublishStream() ) {
                try {
                     Warecorp_Facebook_Api::getInstance()->api(array(
                        'method'=>'stream.publish', 
                        'uid'=>$facebookId, 
                        'message'=>'Some message...'));
                    $isSend = true;   
                } catch (Exception $ex) {}             
            }
            if ( !$isSend ) {
                $objResponse->addScript("FBApplication.onpublish_stream();");
            }
        }
        
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit; 
	}
	
    public function invitegroupstoeventAction()
    {
        return $this->invitefriendstoeventAction();
    }
    
    /**
     * 
     * @return unknown_type
     * @TODO exclude fb account that linked to current user account from list
     */
    public function invitefriendstoeventAction() {
        $this->checkConnection();
        Warecorp::addTranslation('/modules/facebook/invitefriends.php.xml');
        $objResponse = new xajaxResponse();
        
        $facebookId = Warecorp_Facebook_Api::getFacebookId();  
        /**
         * @TODO : check fb id for currect account
         * 1) account isn't linked - link it
         * 2) account is linked and fbuid is some - it's good, do nothing
         * 3) account is linked and fbuid is different - reinit link to fb account
         */      
        $form = new Warecorp_Form('confirmForm', 'POST', Warecorp::getCrossDomainUrl(array('controller' => 'facebook', 'action' => 'invitefriendstoevent')));
        if ( $form->isPostback() ) {
            /**
             * if invite FB friends popup used as external window (with button)
             * used for z1sky from global invitation page
             */
            if ( 'external' == $this->getRequest()->getParam('mode', null)  ) {
                /*                
                $isValid = true;
                if ( !$this->getRequest()->getParam('from', null) ) $isValid = false;
                if ( !$this->getRequest()->getParam('subject', null) ) $isValid = false;
                if ( !$this->getRequest()->getParam('message', null) ) $isValid = false;
                */
                
                if ( isset($_SESSION['INVITE_PROPERTIES']) ) $properties = $_SESSION['INVITE_PROPERTIES'];
                else $properties = null;
                if ( !empty($properties['returnUrl']) ) $url = $properties['returnUrl'];
                else $url = BASE_URL.'/'.LOCALE.'/';
                
                if ( !$targetToInvite = $this->getRequest()->getParam('targetToInvite', null) ) {
                    $objResponse->addRedirect($url);
                    return $objResponse;
                }
                                
                /* Check Entity to Invite */
                if (empty($properties) || !isset($properties['entityType']) || trim($properties['entityType']) == '' || !isset($properties['entityId']) || trim($properties['entityId']) == '' ) {
                    $objResponse->addRedirect($url);
                    return $objResponse;
                }
                /* Load Inviting Entity */
                switch ( strtolower($properties['entityType']) ) {
                    case 'group':
                        $objEntity = Warecorp_Group_Factory::loadById($properties['entityId']);
                        break;
                    case 'event':
                        if ( !empty($properties['entityId']) && !empty($properties['entityUid']) ) {
                            $objEntity = new Warecorp_ICal_Event($properties['entityId']);
                            if ( $objEntity->getId() ) $objEntity = new Warecorp_ICal_Event($properties['entityUid']);
                        }
                        break;
                    default:
                        $objResponse->addRedirect($url);
                        return $objResponse;
                }
                if ( !$objEntity->getId() ) {
                    $objResponse->addRedirect($url);
                    return $objResponse;
                }
                /* END: Load Inviting Entity */

                switch ( strtolower(trim($properties['entityType'])) ) {
                    case 'group' :
                        $targetToInvite = $this->getRequest()->getParam('targetToInvite', null);
                        foreach ( $targetToInvite as $fbuid ) {
                            $objUser = Warecorp_Facebook_User::loadUserByFacebookId($fbuid);
                            if ( $objUser && $objUser->getId() ) {
                                $objEntity->sendInviteMembers( $this->_page->_user, $this->_page->_user, $objUser->getLogin(), '', '' );
                            }

                            //$fbuid 
                            $joinGroupUrl = $objEntity->getGroupPath('joingroup');
                            /**
                             * Facebook Notification
                             */
                            $notification = "
                                the host of the ".SITE_NAME_AS_STRING." Group ".$objEntity->getName()." has invited you to join.
                                To join, click on the link below or copy it and paste it into your browser:
                                <a href='".$joinGroupUrl."'>".$joinGroupUrl."</a>";
                            Warecorp_Facebook_Feed::postNotification($fbuid, $notification);

                            /*
                             * Facebook Email Notification
                             */
                             
                             /*
                            $message_subject = SITE_NAME_AS_STRING." invitation to group";
                            $message_body = "
                                Hello, <br> 
                                you are invited the ".SITE_NAME_AS_STRING." Group ".$objEntity->getName()." has invited you to join.
                                To join, click on the link below or copy it and paste it into your browser:
                                ".$joinGroupUrl."<br><br>
                                Thanks,<br>
                                ".SITE_NAME_AS_STRING;
                            Warecorp_Facebook_Feed::postEmail($fbuid, $message_subject, $message_body, $message_body);
                            */                
                            
                        }
                        break;
                    case 'event' :
                        $targetToInvite = $this->getRequest()->getParam('targetToInvite', null);
                        //$lstAttendee = new Warecorp_ICal_Attendee_List($objEntity);
						$lstAttendee = $objEntity->getAttendee();
                        foreach ( $targetToInvite as $fbuid ) {
                            $objUser = Warecorp_Facebook_User::loadUserByFacebookId($fbuid);
                            if ( $objUser && $objUser->getId() ) {
                                if ( !$objAttendee = $lstAttendee->findAttendee($objUser) ) {
                                    $objAttendee = new Warecorp_ICal_Attendee();
                                    //$objAttendee->setEventId($objEntity->getId());
									$objAttendee->setEventId($lstAttendee->getEventId());
                                    $objAttendee->setOwnerType('user');
                                    $objAttendee->setOwnerId($objUser->getId());
                                    $objAttendee->setAnswer('NONE');
                                    $objAttendee->setAnswerText('');
                                    $objAttendee->save();
                                    
                                    /**
                                     * save user name into invitation to field
                                     * it needs for editing event and its invitation
                                     */                    
                                    $objInvite = $objEntity->getInvite();
                                    
                                    /**
                                     * @see issue #10184
                                     */
                                    $recipients = Warecorp_ICal_Invitation::prepareRecipientsFromString($this->_page->_user, $this->_page->_user->getLogin());
                                    $objInvite->mergeRecipients( $recipients );                                                                                    
                                }                              
                            } else {
                                if ( !$objAttendee = $lstAttendee->findObjectsAttendee('fbuser', $fbuid) ) {
                                    $objAttendee = new Warecorp_ICal_Attendee();
                                    //$objAttendee->setEventId($objEntity->getId());
									$objAttendee->setEventId($lstAttendee->getEventId());
                                    $objAttendee->setOwnerType('fbuser');
                                    $objAttendee->setOwnerId($fbuid);
                                    $objAttendee->setAnswer('NONE');
                                    $objAttendee->setAnswerText('');
                                    $objAttendee->save();
                                }                              
                            }                                 
                        }                        
                        break;
                    case 'friends' :
                        break;
                }
                $objResponse->addRedirect($url);
                return $objResponse; 
            }
            /**
             * END: if invite FB friends popup used as external window (with button)
             * used for z1sky from global invitation page
             */
            
            
            /**
             * if invite FB friends popup used as part of form
             * standart way to use
             */
            if ( $targetToInvite = $this->getRequest()->getParam('targetToInvite', null) ) {
                $friendsToInvite = Warecorp_Facebook_User::getInfo($targetToInvite);
                $formParams['event_invitations_fbfriends'] = $friendsToInvite;
                $this->view->formParams = $formParams;
                $Content = $this->view->getContents('facebook/invitefriends.template.invited.tpl');
                $objResponse->addAssign('EventInviteFBFriendsObjects', 'innerHTML', $Content);
                $objResponse->addAssign('EventInviteFBFriendsObjects', 'style.display', '');                
                $objResponse->addScript('FBApplication.targetsToInvite = '.Zend_Json_Encoder::encode($targetToInvite).';');
            } else {
                $objResponse->addAssign('EventInviteFBFriendsObjects', 'innerHTML', '');
                $objResponse->addAssign('EventInviteFBFriendsObjects', 'style.display', 'none');                
                $objResponse->addScript('FBApplication.targetsToInvite = [];');
            }            
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->close($objResponse);
            /**
             * END: if invite FB friends popup used as part of form
             * standart way to use
             */            
        } else {
            $mode = $this->getRequest()->getParam('mode', null);
            $friends = Warecorp_Facebook_Api::getInstance()->api(array('method'=>'friends.get','uid'=>$facebookId));
            $friends = ( $friends = Warecorp_Facebook_User::getInfo($friends) ) ? $friends : array();
            $friendsUids = array();
            foreach ( $friends as $k => $v ) {
                $friendsUids[$k] = $v['uid'];
            }
            $invitedFriends = $this->getRequest()->getParam('invited', array());
            
            //  Inviting more people by ajax link in event view page
            if ( !empty($_SESSION['INVITE_PROPERTIES']['newEventInvitation']) && $mode === 'external' ) {
                $properties = $_SESSION['INVITE_PROPERTIES'];
                $alreadyInvited = array();
                switch ( strtolower($properties['entityType']) ) {
                case 'event':
                    $objEntity = new Warecorp_ICal_Event($properties['entityId']);
                    $attendies = $objEntity->getAttendee()->setFetchMode('object')->setDateFilter($objEntity->getDtstartValue())->getList();
                    foreach ( $attendies as &$attendee ) {
                        $fbuid = $attendee->getOwnerId();
                        if ( $attendee->getOwnerType() === 'fbuser' && !in_array($fbuid, $invitedFriends) ) {
                            if ( FALSE !== ($key = array_search($fbuid, $friendsUids)) )
                                unset($friends[$key]);  //  remove all fb friends already invited in previos steps
                        }
                    }
                    break;
                }
                $this->view->newEventInvitation = true;
                $this->view->eventId = $_SESSION['INVITE_PROPERTIES']['entityId'];
                $this->view->eventUid = $_SESSION['INVITE_PROPERTIES']['entityUid'];
            }
            
            $this->view->form = $form;
            $this->view->friends = $friends;
            $this->view->invitedFriends = $invitedFriends;
            $this->view->invitedFriendsCount = sizeof($invitedFriends);
            $this->view->mode = $mode;

            $Content = $this->view->getContents('facebook/invitefriendstoevent.tpl');
    
            $popup_window = Warecorp_View_PopupWindow::getInstance();        
            $popup_window->title(Warecorp::t('Invite Facebook Members'));
            $popup_window->content($Content);
            $popup_window->width(600)->open($objResponse);        
        }
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                               
        
    }
    
    /**
     * 
     * @return unknown_type
     */
    public function removefromeventinviteAction() {
        Warecorp::addTranslation('/modules/facebook/removefromeventinvite.php.xml');
        $objResponse = new xajaxResponse(); 

        $uid = $this->getRequest()->getParam('uid', null);
        if ( 1 == $this->getRequest()->getParam('confirm', null) ) {
            $this->view->fbuid = $uid;
            $Content = $this->view->getContents('facebook/remove.fb.contact.tpl');
    
            $popup_window = Warecorp_View_PopupWindow::getInstance();        
            $popup_window->title(Warecorp::t('Confirmation'));
            $popup_window->content($Content);
            $popup_window->width(450)->open($objResponse);    
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;                
        }
                
        $targetToInvite = $this->getRequest()->getParam('invited', array());
        $key = array_search($uid, $targetToInvite);
        if ( $uid && false !== $key = array_search($uid, $targetToInvite)  ) {  
            array_splice($targetToInvite, $key , 1);
        }
        if ( $targetToInvite ) {
            $friendsToInvite = Warecorp_Facebook_User::getInfo($targetToInvite);
            $formParams['event_invitations_fbfriends'] = $friendsToInvite;
            $this->view->formParams = $formParams;
            $Content = $this->view->getContents('facebook/invitefriends.template.invited.tpl');
            $objResponse->addAssign('EventInviteFBFriendsObjects', 'innerHTML', $Content);
            $objResponse->addAssign('EventInviteFBFriendsObjects', 'style.display', '');                
            $objResponse->addScript('FBApplication.targetsToInvite = '.Zend_Json_Encoder::encode($targetToInvite).';');
        } else {
            $objResponse->addAssign('EventInviteFBFriendsObjects', 'innerHTML', '');
            $objResponse->addAssign('EventInviteFBFriendsObjects', 'style.display', 'none');                
            $objResponse->addScript('FBApplication.targetsToInvite = [];');            
        }       
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->close($objResponse);        
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                               
    } 
	
    /**
     * Process user login after using Facebook Connect login screen
     * @return void
     */
    public function processrsvploginAction() {
    }
	
    
    /**
     * Process user login after using Facebook Connect login screen
     * @return void
     */
    public function processrsvploginzccfAction() {
    }
    
    public function checkrsvpstatusAction() {
        //$this->checkConnection();
        Warecorp::addTranslation('/modules/facebook/checkrsvpstatus.php.xml');
        $objResponse = new xajaxResponse();
                         
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( !$this->_page->_user->getId() ) {
            /**
             * try to autologin user by facebook id
             */
            if ( null !== $user = Warecorp_Facebook_User::loadUserByFacebookId($facebookId) ) {
                $user->authenticate();
                $objResponse->addScript('document.location.reload();');
                $objResponse->printXml($this->_page->Xajax->sEncoding);
                exit;                 
            }            
        }
        
        
        /**
         * user isn't loged in
         * try to find attendee for event
         * 1) there is attendee - write access code in session
         * 2) user hasn't attendee and con not access to rsvp
         */
        
        /**
        * Check event
        */
        if ( null === $this->getRequest()->getParam('event_id', null) || null === $this->getRequest()->getParam('event_uid', null) ) {
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit; 
        }
        $objEvent = new Warecorp_ICal_Event($this->getRequest()->getParam('event_id'));
        if ( null === $objEvent->getId() ) {
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit; 
        }
        $objEvent = new Warecorp_ICal_Event($this->getRequest()->getParam('event_uid'));
        if ( null === $objEvent->getId() ) {
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit; 
        }
        
        $lstAttendee = new Warecorp_ICal_Attendee_List($objEvent);
        if ( $objAttendee = $lstAttendee->findObjectsAttendee('fbuser', $facebookId) ) {
            $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_']       = $objAttendee;
            $_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']    = $objAttendee->getAccessCode();
            $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_']    = $objAttendee->getOwnerType();
             
            $objResponse->addScript('document.location.reload();');
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;             
        }

        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                     
    }

	/**
	 * Calls when user post any newsffed to Facebook
	 * @return unknown_type
	 */
	public function processpublishpostAction() {
	    $this->checkConnection();
	    Warecorp::addTranslation('/modules/facebook/processpublishpost.php.xml');
	    $objResponse = new xajaxResponse();
	    
	    $facebookId = Warecorp_Facebook_Api::getFacebookId();

		/* if z account isn't connected yet to FB accoutn */
		if ( !Warecorp_Facebook_User::isZAccountConnected($this->_page->_user->getId()) ) {
			/* and if ZB account isn't connected to any z account */
			if ( null === $user = Warecorp_Facebook_User::loadUserByFacebookId($facebookId) ) {
				$objFUser = new Warecorp_Facebook_User();
				$objFUser->setFacebookId($facebookId);
				$objFUser->setUserId($this->_page->_user->getId());
				$objFUser->save();
				
				$objResponse->addScript("$('#cFacebookLinkAccount').hide();");
				
				/* link Z account with FB account */
				$popup_window = Warecorp_View_PopupWindow::getInstance();
				$popup_window->title(Warecorp::t('Congratulation'));
				$popup_window->content('<p>' . Warecorp::t('Congratulation! Your account has been connected to Facebook account. Just now you can use your Facebook account to log in our system.') . '</p>');
				$popup_window->width(350)->height(100)->reload($objResponse);        
			} 
			/* if FB account is already connected to any Z account offer to confirm this association  */
			else {            
				//$Content = $this->view->getContents('facebook/processlink.connected.tpl');
				
				//$popup_window = Warecorp_View_PopupWindow::getInstance();        
				//$popup_window->title(Warecorp::t('Information'));
				//$popup_window->content('<p>' . Warecorp::t('Your Facebook account is already connected to an account.') . '</p>');
				//$popup_window->width(350)->height(100)->reload($objResponse);        
				//$objResponse->printXml($this->_page->Xajax->sEncoding);
				//exit;                   
				/* Z account already exists, show the message */
			}       
		
		}
		
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                               
	}	

	public function invitefriendsreadyAction() {
        $this->checkConnection();
        Warecorp::addTranslation('/modules/facebook/invitefriendsready.php.xml');
        $objResponse = new xajaxResponse();
        
        $facebookId = Warecorp_Facebook_Api::getFacebookId();

        /* if z account isn't connected yet to FB accoutn */
        if ( !Warecorp_Facebook_User::isZAccountConnected($this->_page->_user->getId()) ) {
            /* and if ZB account isn't connected to any z account */
            if ( null === $user = Warecorp_Facebook_User::loadUserByFacebookId($facebookId) ) {
                $objFUser = new Warecorp_Facebook_User();
                $objFUser->setFacebookId($facebookId);
                $objFUser->setUserId($this->_page->_user->getId());
                $objFUser->save();
                
                $objResponse->addScript("$('#cFacebookLinkAccount').hide();");
                
                /* link Z account with FB account */
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->title(Warecorp::t('Congratulation'));
                $popup_window->content('<p>' . Warecorp::t('Congratulation! Your account has been connected to Facebook account. Just now you can use your Facebook account to log in our system.') . '</p>');
                $popup_window->width(350)->height(100)->reload($objResponse);        
            } 
            /* if FB account is already connected to any Z account offer to confirm this association  */
            else {            
                //$Content = $this->view->getContents('facebook/processlink.connected.tpl');
                
                //$popup_window = Warecorp_View_PopupWindow::getInstance();        
                //$popup_window->title(Warecorp::t('Information'));
                //$popup_window->content('<p>' . Warecorp::t('Your Facebook account is already connected to an account.') . '</p>');
                //$popup_window->width(350)->height(100)->reload($objResponse);        
                //$objResponse->printXml($this->_page->Xajax->sEncoding);
                //exit;                   
                /* Z account already exists, show the message */
            }       
        
        }
        
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                               	    
	}
}
