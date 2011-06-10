<?php
Warecorp::addTranslation("/modules/users/calendar/action.event.export.php.xml");

$objRequest  = $this->getRequest();
$objResponse = $this->getResponse();

    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();
    $this->view->Warecorp_Venue_AccessManager = Warecorp_Venue_AccessManager_Factory::create();

    
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    /**
    * Check event
    */
    $objEvent = new Warecorp_ICal_Event($objRequest->getParam('id', NULL));
    if ( !$objEvent->getId() || !$objRequest->getParam('uid', NULL)) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $this->_redirect($this->currentGroup->getGroupPath('calendar.action.confirm'));
    }

    /**
     * Check if user is goes from Facebook site
     */
    if ( FACEBOOK_USED && !$facebookId = Warecorp_Facebook_Api::getFacebookId() ) {
        $isFacebookMode = false;
        if ( isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'facebook.com') ) {
            $isFacebookMode = true;
        } elseif ( null != $this->getRequest()->getParam('m', null) && 'fb' == $this->getRequest()->getParam('m') ) {
            $isFacebookMode = true;
        }
        if ( $isFacebookMode ) {
            $this->view->goUrl = $objEvent->entityURL();
            $this->view->bodyContent = 'groups/calendar/action.event.facebook.wrapper.tpl';
            return;
        }
    }
    /**
    * Check Access By Code for anonymous user
    *///unset($_SESSION['_RSVP_']);
    if ( null === $this->_page->_user->getId() ) {
        $access_code = $objRequest->getParam('code', null) ? $objRequest->getParam('code') : ( !empty($_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']) ? $_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_'] : null );
        /**
         * if access code exists check it
         */
        if ( null !== $access_code ) {
            /**
             * try to find attendee by code for real user (registered or not regigtered)
             */
            if ( $objAttendee = $objEvent->getAttendee()->findAttendeeByCode($access_code) ) {
                $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_']       = $objAttendee;
                $_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']    = $access_code;
                $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_']    = 'user'; 
                $this->view->_RSVP__access_mode_ = $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_'];
                $this->view->_RSVP__attendee_ = $objAttendee;
                $this->_page->_user->setEmail($objAttendee->getEmail());            
            } 
            /**
             * try to find attendee by code for attendee object like fbuser, group, list (probably any other object we have)
             */
            elseif ( $objAttendee = $objEvent->getAttendee()->findObjectsAttendeeByCode($access_code) ) {
                $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_']       = $objAttendee;
                $_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']    = $access_code;
                $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_']    = $objAttendee->getOwnerType();            
                $this->view->_RSVP__access_mode_ = $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_'];
                $this->view->_RSVP__attendee_ = $objAttendee;
            }
        }
        /**
         * Access code isn't exists
         * Check access for Facebook user if Facebook Session exists
         */
        elseif ( FACEBOOK_USED && $facebookId = Warecorp_Facebook_Api::getFacebookId() ) {
            $facebookUser = new Warecorp_Facebook_User($facebookId);
            if ( $facebookUser->getId() ) {
                $objUser = new Warecorp_User('id', $facebookUser->getUserId());
                $objUser->authenticate();

                $this->_page->_user =& $objUser;
                $this->view->user = $objUser;
                Zend_Registry::set("User", $objUser);  
            }
            
            if ( $this->_page->_user && null !== $this->_page->_user && $objAttendee = $objEvent->getAttendee()->findAttendee($this->_page->_user) ) {
                $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_']       = $objAttendee;
                $_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']    = $objAttendee->getAccessCode();
                $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_']    = 'user'; 
                $this->view->_RSVP__access_mode_ = $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_'];
                $this->view->_RSVP__attendee_ = $objAttendee;                
            } elseif ( $objAttendee = $objEvent->getAttendee()->findObjectsAttendee('fbuser', $facebookId) ) {
                $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_']       = $objAttendee;
                $_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']    = $objAttendee->getAccessCode();
                $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_']    = $objAttendee->getOwnerType();            
                $this->view->_RSVP__access_mode_ = $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_'];
                $this->view->_RSVP__attendee_ = $objAttendee;                
            }
        }
        
        if ( empty($_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']) && !Warecorp_ICal_AccessManager_Factory::create()->canAnonymousViewEvent($objEvent, $this->currentGroup)  ) {
            //$this->_redirectToLogin();
            $this->view->errorMessage = Warecorp::t('Sorry, you can not view this event');
            $this->view->bodyContent = 'groups/calendar/action.event.error.message.tpl';
            return ;            
        }        
    } else {
        /**
         * Convert all fb attendee that belong to user
         */
        if ( FACEBOOK_USED ) {
            $facebookUser = Warecorp_Facebook_User::loadByUserId($this->_page->_user->getId());
            if ( !empty($facebookUser) && $facebookUser->getId() ) {
                $lstAttendee = new Warecorp_ICal_Attendee_List($objEvent);
                if ( $objAttendee = $lstAttendee->findObjectsAttendee('fbuser', $facebookUser->getFacebookId()) ) {
                    /**
                     * There is attendee for current user already
                     * remove FB attendee
                     */
                    if ( $objAttendeeUser = $lstAttendee->findAttendee($this->_page->_user) ) {
                        $objAttendee->delete();
                    } else {
                        $objAttendee->setOwnerType('user');
                        $objAttendee->setOwnerId($this->_page->_user->getId());
                        $objAttendee->setEmail(new Zend_Db_Expr('NULL'));
                        $objAttendee->save();
                        
                        /**
                         * save user name into invitation to field it needs for editing event and its invitation
                         */                    
                        $objInvite = $objEvent->getInvite();
						
                        /**
                         * @see issue #10184
                         */
                        $recipients = Warecorp_ICal_Invitation::prepareRecipientsFromString($this->_page->_user, $this->_page->_user->getLogin());
                        $objInvite->mergeRecipients( $recipients );
                    }                    
                }
            }
        }
        unset($_SESSION['_RSVP_']);
    }
    
    /**
    * Check Access
    */
    $eventViewAccessCode = isset($_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']) ? $_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_'] : null;
    if ( false == Warecorp_ICal_AccessManager_Factory::create()->canViewEvent($objEvent, $objEvent->getOwner(), $this->_page->_user, $eventViewAccessCode) ) {
        $this->view->errorMessage = Warecorp::t('Sorry, you can not view this event');
        $this->view->bodyContent = 'groups/calendar/action.event.error.message.tpl';
        return ;
    }



$this->_helper->viewRenderer->setNoRender(true);

if ( Zend_Registry::isRegistered('User') )  $user = Zend_Registry::get('User');
else                                        $user = new Warecorp_User;

$export = new Warecorp_ICal_Export_ICalendar($objEvent, $user);
$strICal = $export->save();
$filename = trim(preg_replace('/[^-a-z0-9]+/i', ' ', $objEvent->getTitle())).'.ics';

$objResponse
    ->clearHeaders()
    ->clearBody();
$objResponse
    ->setHeader('Content-Type', 'text/calendar')
    ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s').' GMT')
    ->setHeader('Content-Length', strlen($strICal))
    ->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"')
    ->setHeader('Content-Transfer-Encoding', 'binary')
    ->setHeader('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
    ->setHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT')
    ->setHeader('Pragma', 'no-cache');
$objResponse->setBody($strICal);

