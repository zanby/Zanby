<?php
    Warecorp::addTranslation("/modules/users/calendar/action.event.view.php.xml");
    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();
    $this->view->Warecorp_Venue_AccessManager = new Warecorp_Venue_AccessManager();
    
    /**
    * Register Ajax Functions
    */
    $this->_page->Xajax->registerUriFunction( "bookmarkit", "/ajax/bookmarkit/" );
    $this->_page->Xajax->registerUriFunction( "addbookmark", "/ajax/addbookmark/" );
    $this->_page->Xajax->registerUriFunction( "addToFriends", "/ajax/addToFriends/" );
    $this->_page->Xajax->registerUriFunction( "addToFriendsDo", "/ajax/addToFriendsDo/" );
    //
    $this->_page->Xajax->registerUriFunction( "doCancelEvent", "/users/calendarEventCancel/" );
    $this->_page->Xajax->registerUriFunction( "doAttendeeEvent", "/users/calendarEventAttendee/" );
    $this->_page->Xajax->registerUriFunction( "doAttendeeEventSignup", "/users/calendarEventAttendeeSignup/" );
    $this->_page->Xajax->registerUriFunction( "doCopyEvent", "/users/calendarEventCopy/" );
    $this->_page->Xajax->registerUriFunction( "doEventShare", "/users/calendarEventShare/" );
    $this->_page->Xajax->registerUriFunction( "doEventUnShare", "/users/calendarEventUnShare/" );
    $this->_page->Xajax->registerUriFunction( "doEventInvite", "/users/calendarEventInvite/" );
    $this->_page->Xajax->registerUriFunction( "doEventSendMessage", "/users/calendarEventSendMessage/" );
    $this->_page->Xajax->registerUriFunction( "doEventOrganizerSendMessage", "/users/calendarEventOrganizerSendMessage/" );
    $this->_page->Xajax->registerUriFunction( "doEventRemoveMe", "/users/calendarEventRemoveMe/" );    
    $this->_page->Xajax->registerUriFunction( "doEventRemoveGuest", "/users/calendarEventRemoveGuest/" );
    $this->_page->Xajax->registerUriFunction( "doChangeHost", "/users/calendarEventChangeHost/" );
    $this->_page->Xajax->registerUriFunction( "doAddToMy", "/users/calendarEventAddToMy/" );
    $this->_page->Xajax->registerUriFunction( "doExpandList", "/users/calendarEventExpandList/" );
    $this->_page->Xajax->registerUriFunction( "doCollapseList", "/users/calendarEventCollapseList/" );

    $objRequest = $this->getRequest();

    //FIXME определить , какая таймзона является дефолтовой
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того,
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    /**
    * Check event
    */
    if ( null === $objRequest->getParam('id', null) || null === $objRequest->getParam('uid', null) ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $this->_redirect($this->currentUser->getUserPath('calendar.action.confirm'));
    }
    $objEvent = new Warecorp_ICal_Event($objRequest->getParam('id'));
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $this->_redirect($this->currentUser->getUserPath('calendar.action.confirm'));
    }
//    $objEvent = new Warecorp_ICal_Event($objRequest->getParam('uid'));
//    if ( null === $objEvent->getId() ) {
//        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
//        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
//        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
//        $this->_redirect($this->currentUser->getUserPath('calendar.action.confirm'));
//    }

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
            $this->view->bodyContent = 'users/calendar/action.event.facebook.wrapper.tpl';
            return;
        }
    }
    /**
    * Check Access By Code for anonymous user
    */
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
        
        if ( empty($_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']) && !Warecorp_ICal_AccessManager_Factory::create()->canAnonymousViewEvents($this->currentUser)  ) {
            //$this->_redirectToLogin();
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
                                             
                        /* save user name into invitation to field, it needs for editing event and its invitation */                    
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
    if ( false == Warecorp_ICal_AccessManager_Factory::create()->canViewEvent($objEvent, $this->currentUser, $this->_page->_user, $eventViewAccessCode) ) {
        $this->view->friendsAssoc = $this->_page->_user->getId() ? $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array() ;
        $this->view->errorMessage = Warecorp::t('Sorry, you can not view this event');
        $this->view->bodyContent = 'users/calendar/action.event.error.message.tpl';
        return ;
    }
    

    /**
    * Check date
    */
    $dataIsExist = true;
    $dataIsExist = $dataIsExist && $objRequest->getParam('year', null);
    $dataIsExist = $dataIsExist && $objRequest->getParam('month', null);
    $dataIsExist = $dataIsExist && $objRequest->getParam('day', null);
    if ( !$dataIsExist )  $viewMode = 'ROW'; else $viewMode = 'COPY';

    $objRequest->setParam('year', ( floor($objRequest->getParam('year')) < 1970 ) ? 1970 : floor($objRequest->getParam('year')) );
    $objRequest->setParam('year', ( floor($objRequest->getParam('year')) > 2038 ) ? 2038 : floor($objRequest->getParam('year')) );
    $objRequest->setParam('month', ( floor($objRequest->getParam('month')) < 1 ) ? 1 : floor($objRequest->getParam('month')) );
    $objRequest->setParam('month', ( floor($objRequest->getParam('month')) > 12 ) ? 12 : floor($objRequest->getParam('month')) );
    $oCDate = new Zend_Date(sprintf('%04d', $objRequest->getParam('year')).'-'.sprintf('%02d', $objRequest->getParam('month')).'-01', Zend_Date::ISO_8601);
    $objRequest->setParam('day', ( floor($objRequest->getParam('day')) < 1 ) ? 1 : floor($objRequest->getParam('day')) );
    $objRequest->setParam('day', ( floor($objRequest->getParam('day')) > $oCDate->get(Zend_Date::MONTH_DAYS)) ?  $oCDate->get(Zend_Date::MONTH_DAYS) : floor($objRequest->getParam('day')) );
    unset($oCDate);

    /**
     * Показыаем событие как ряд
     */
    //if ( $viewMode == 'ROW' ) {
        //THIS SHOULD NEVER HAPPEN

//        $objRef = new Warecorp_ICal_Event_List_Reference($objEvent);
//        $rootId = $objRef->getRootId();
//        $objOriginalEvent = clone $objEvent;
//        $objEvent = new Warecorp_ICal_Event($rootId);
//
//        /**
//        *  если для события не было указана таймзона, считаем, что это текущая
//        */
//        if ( null === $objEvent->getTimezone() ) $objEvent->setTimezone($currentTimezone);
//
//        $defaultTimeZone = date_default_timezone_get();
//        date_default_timezone_set( $objEvent->getTimezone() );
//
//        $objNowDate = new Zend_Date();
//        $lstEventsObj = new Warecorp_ICal_Event_List();
//        $lstEventsObj->setTimezone($objEvent->getTimezone());
//        $strFirstDate = $lstEventsObj->findFirstEventDate($objEvent, $objNowDate);
//
//        if ( null !== $strFirstDate ) {
//            $oFirstDate = new Zend_Date($strFirstDate, Zend_Date::ISO_8601);
//            $oFirstDate->addSecond($objEvent->getDurationSec());
//            $objEvent->setDtstart($strFirstDate);
//            $objEvent->setDtend($oFirstDate->toString('yyyy-MM-ddTHHmmss'));
//        }
//
//        $objEventDtstart = clone $objEvent->getDtstart();
//        $objEventDtend = clone $objEvent->getDtend();
//        date_default_timezone_set($defaultTimeZone);
//
//        if ( ($objEvent->getRecurrences() !== null) && ($objRequest->getParam('code', null) === null) ){
//            $this->_redirect($objEvent->entityURL());
//            //$dtStart = $objEvent->convertTZ($objEvent->getDtstart(), $currentTimezone);
//            //$this->_redirect($this->currentUser->getUserPath('calendar.event.view').'id/'.$objRequest->getParam('id', null).'/uid/'.$objRequest->getParam('uid', null).'/year/'.$dtStart->toString('yyyy').'/month/'.$dtStart->toString('MM').'/day/'.$dtStart->toString('dd').'/');
//        }
//
//        if ( $objNowDate->isLater($objEventDtstart) ) $objEvent->setExpired(true);
//        else $objEvent->setExpired(false);
//
//        $objCopyEvent = $objEvent;
//    }
    /**
     * Показываем событие как конкретную копию
     */
//    else {
        $objEventList = new Warecorp_ICal_Event_List();
        $objEventList->setTimezone( $currentTimezone );
        $eventInfo = $objEventList->findEvent($objEvent, $objRequest->getParam('id'), $objRequest->getParam('uid'), $objRequest->getParam('year'), $objRequest->getParam('month'), $objRequest->getParam('day'));

        if ( null === $eventInfo ) {
            $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
            $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
            $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
            $this->_redirect($this->currentUser->getUserPath('calendar.action.confirm'));
        }

        $objCopyEvent           = $eventInfo['objEvent'];
        $objEventDtstart        = $eventInfo['date_in_event_timezone'];
        $objEventDtend          = clone $objEventDtstart;
        $objEventDtend->addSecond($eventInfo['durationSec']);
        $durationSec            = $eventInfo['durationSec'];

        /**
         * added according bug #4241
         * it set event dtstart and dtend to correct value after validating date
         */
        $objCopyEvent->setDtstart($objEventDtstart->toString('yyyy-MM-ddTHHmmss'));
        $objCopyEvent->setDtend($objEventDtend->toString('yyyy-MM-ddTHHmmss'));
        
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set( $currentTimezone );
        $objNowDate = new Zend_Date();
        date_default_timezone_set($defaultTimeZone);
        if ( $objNowDate->isLater($objEventDtstart) ) $objCopyEvent->setExpired(true);
        else $objCopyEvent->setExpired(false);

        $this->view->year = sprintf('%04d', $objRequest->getParam('year'));
        $this->view->month = sprintf('%02d', $objRequest->getParam('month'));
        $this->view->day = sprintf('%02d', $objRequest->getParam('day'));
//    }

    $objRootEvent = new Warecorp_ICal_Event($objEvent->getRootId());
    if ( Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentUser, $this->_page->_user) && $objRootEvent->getSharing()->getCount() ) {
        $showUnShareLink = ( $objRootEvent->getSharing()->getCount() ) ? true : false;
    } elseif ( $objRootEvent->getSharing()->isShared($this->_page->_user) ) {
        $showUnShareLink = true;
    } else {
        $showUnShareLink = false;
    }

    /**
    * Event Lists
    */
    $lstLists = $objCopyEvent->getLists()->setFetchMode('object')->getList();
    if ( sizeof($lstLists) != 0 && null !== $lstLists[0]->getId() ) {
        $this->params['listid'] = $lstLists[0]->getId();
        $this->listsViewAction();
    }
    $this->view->lstLists = $lstLists;

    /**
    * Assign template vars
    */
    $this->view->objEvent = $objEvent;
    $this->view->objCopyEvent = $objCopyEvent;
    $this->view->objEventDtstart = $objEventDtstart;
    $this->view->objEventDtend = $objEventDtend;
    $this->view->viewMode = $viewMode;
    $this->view->currentTimezone = $currentTimezone;
    $this->view->bodyContent = 'users/calendar/action.event.view.tpl';
    $this->view->showUnShareLink = $showUnShareLink;

    $attendeeAjaxParamsOnClick = "xajax_doAttendeeEvent('".$objCopyEvent->getId()."', '".$objCopyEvent->getUid()."', 'month', 0, '".$objEventDtstart->toString('yyyy-MM-ddTHHmmss')."'); return false;";
    $this->view->attendeeAjaxParamsOnClick = $attendeeAjaxParamsOnClick;

    $this->view->friendsAssoc = $this->_page->_user->getId() ? $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array() ;
