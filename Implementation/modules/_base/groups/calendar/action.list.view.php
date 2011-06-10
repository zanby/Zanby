<?php

    Warecorp::addTranslation('/modules/groups/calendar/action.list.view.php.xml');
    $AccessManager = Warecorp_ICal_AccessManager_Factory::create();
    $this->view->Warecorp_ICal_AccessManager = $AccessManager;

    if ( null === $this->_page->_user->getId() && !$AccessManager->canAnonymousViewEvents($this->currentGroup) ) $this->_redirectToLogin();
    if ( false == $AccessManager->canViewEvents($this->currentGroup, $this->_page->_user) ) {
        $this->view->errorMessage = Warecorp::t('Sorry, you can not view this calendar');
        $this->view->bodyContent = 'groups/calendar/action.event.error.message.tpl';
        return ;
    }

    /**
    * Register Ajax Functions
    */
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/" );
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/" );
    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/" );
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/" );
    //
    $this->_page->Xajax->registerUriFunction("doCancelEvent", "/groups/calendarEventCancel/" );
    $this->_page->Xajax->registerUriFunction("doAttendeeEvent", "/groups/calendarEventAttendee/" );
    $this->_page->Xajax->registerUriFunction("doAttendeeEventSignup", "/groups/calendarEventAttendeeSignup/" );
    $this->_page->Xajax->registerUriFunction("viewAttendeeEvent", "/groups/calendarEventAttendeeView/" );
    $this->_page->Xajax->registerUriFunction("doEventShare", "/groups/calendarEventShare/" );
    $this->_page->Xajax->registerUriFunction("doEventUnShare", "/groups/calendarEventUnShare/" );
    $this->_page->Xajax->registerUriFunction("doHostUnshareEvent", "/groups/calendarHostUnshareEvent/" );
    $this->_page->Xajax->registerUriFunction("doEventRemoveMe", "/groups/calendarEventRemoveMe/" );


    //FIXME определить , какая таймзона является дефолтовой
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того,
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    $objRequest = $this->getRequest();

    if ( null === $objRequest->getParam('mode', null) || !in_array(strtolower($objRequest->getParam('mode', '')), array('active', 'expired')) ) {
        $mode = 'active';
    } else {
        $mode = strtolower($objRequest->getParam('mode'));
    }
    if ( !$AccessManager->isHostPrivileges($this->currentGroup, $this->_page->_user) ) {
        $mode = 'active';
    }


    if ($objRequest->getParam('page', null) !== null) {
        $currentPage =  $objRequest->getParam('page', null);
    }else{
        $currentPage = 1;
    }
    $pageSize = 10;
    /**
     * Initialization global objects that is used in script
     */
    $lstEventsObj = new Warecorp_ICal_Event_List();
    $lstEventsObj->setTimezone($currentTimezone);
    $tz = date_default_timezone_get();
    date_default_timezone_set($currentTimezone);
    $objNowDate = new Zend_Date();
    date_default_timezone_set($tz);

    /**
     * Find events that belog to main group
     * $arrEvents will contains all this events
     */
    if ( $mode == 'expired' && !$AccessManager->isHostPrivileges($this->currentGroup, $this->_page->_user) ) {
        $arrEvents = array();
    } else {
        $objEvents = new Warecorp_ICal_Event_List_Standard();
        $objEvents->setTimezone($currentTimezone);
        $objEvents->setOwnerIdFilter($this->currentGroup->getId());
        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
        // privacy
        if ( $AccessManager->canViewPublicEvents($this->currentGroup, $this->_page->_user) && $AccessManager->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setPrivacyFilter(array(0,1));
        } elseif ( $AccessManager->canViewPublicEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setPrivacyFilter(array(0));
        } elseif ( $AccessManager->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setPrivacyFilter(array(1));
        } else {
            $objEvents->setPrivacyFilter(null);
        }
        // sharing
        if ( $AccessManager->canViewSharedEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setSharingFilter(array(0,1));
        } else {
            $objEvents->setSharingFilter(array(0));
        }


        if ( $mode == 'active' ) {
            $objEvents->setCurrentEventFilter(true);
            $objEvents->setExpiredEventFilter(false);
        } else {
            $objEvents->setCurrentEventFilter(false);
            $objEvents->setExpiredEventFilter(true);
        }

        /**
         * block is used for zccf, zccf-base, zccf-alt only
         * this is filter by round and dates that presents on zccf project
         * for all different projects $this->objRoundFilter is NULL
         * @author Artem Sukharev
         */
        if ( isset($this->objRoundFilter) ) {
            if ( $this->objRoundFilter['round_filter_mode1'] ) {
                $objEvents->setFilterPartOfRound($this->objRoundFilter['objRound']->getRoundId());
            }
            $objEvents->setFilterPartOfNonRound($this->objRoundFilter['round_filter_mode2']);
            /* start date */
            if ( $this->objRoundFilter['objDateStart'] && $this->objRoundFilter['objDateEnd'] ) {
                $utcDateStart = clone $this->objRoundFilter['objDateStart'];
                $utcDateStart->setTimeZone('UTC');
                $utcDateEnd = clone $this->objRoundFilter['objDateEnd'];
                $utcDateEnd->setTimeZone('UTC');
                $objEvents->setFilterStartDateRange($utcDateStart, $utcDateEnd);
            }
        }


        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();
    }

    $arrEventIds = array_keys($arrEvents);
    $count = count($arrEvents);
    $arrEvents = array_slice($arrEvents, ($currentPage - 1)*$pageSize, $pageSize, true);

    $P = new Warecorp_Common_PagingProduct($count, $pageSize, $this->currentGroup->getGroupPath('calendar.list.view').'mode/'.$mode);
    $paging = $P->makePaging($currentPage);
    $this->view->paging = $paging;

    $arrEventsLinks = array();
    if ( sizeof($arrEvents) != 0 ) {
        foreach ( $arrEvents as $ev_id => &$ev ) {
            /**
             * Find the event first date
             */
            $ev = new Warecorp_ICal_Event($ev_id);

            $strFirstDate = $lstEventsObj->findFirstEventDate($ev, $objNowDate);
            if ( null !== $strFirstDate ) {
                $ev->setTimezone($currentTimezone);
                $ev->setDtstart($strFirstDate);
            }
            $objRootEvent = new Warecorp_ICal_Event($ev->getRootId());
            if ( Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($ev, $this->currentGroup, $this->_page->_user) && $objRootEvent->getSharing()->getCount() ) {
                $arrEventsLinks[$ev->getId()] = ( $objRootEvent->getSharing()->getCount() ) ? true : false;
            }
            elseif ( $objRootEvent->getSharing()->isShared($this->currentGroup) && Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges( $this->currentGroup, $this->_page->_user ) ) {
                $arrEventsLinks[$ev->getId()] = true;
            }
            else {
                $arrEventsLinks[$ev->getId()] = false;
            }
        }
    }
    /**
     * Sort events by date
     */
    if ( $this->_page->_user && null !== $this->_page->_user->getId() ) {
        if ( $mode == 'active' ) usort($arrEvents, "eventDateCmpDesc");
        else usort($arrEvents, "eventDateCmpAsc");
    } else {
        if ( $mode == 'active' ) usort($arrEvents, "eventDateCmpDescAnonymous");
        else usort($arrEvents, "eventDateCmpAscAnonymous");
    }

    // RSS
    if(LOCALE == "rss" && Warecorp_Access::isRssAllowed(Warecorp::$controllerName, Warecorp::$actionName)){
        require_once(ENGINE_DIR."/rss.class.php");
        $rss = FeedManager::getEvents($arrEvents,$currentTimezone,$this->currentGroup);
        header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
        print $rss->createFeed("RSS2.0");
        exit;
    }
    // RSS end

    $arrEventsLinks = array();
    $lstEventsObj = new Warecorp_ICal_Event_List();
    $lstEventsObj->setTimezone($currentTimezone);
    $evAtt = array();
    if ( sizeof($arrEvents) != 0 ) {
        $tz = date_default_timezone_get();
        date_default_timezone_set($currentTimezone);
        $objNowDate = new Zend_Date();
        date_default_timezone_set($tz);
        foreach ( $arrEvents as $key=>&$ev ) {
            $strFirstDate = $lstEventsObj->findFirstEventDate($ev, $objNowDate);
            if ( null !== $strFirstDate ) $ev->setDtstart($strFirstDate);
            // *****************
            $evAtt[$key]['event'] = $ev;
                $objEventInvite = $ev->getInvite();
                if($objEventInvite->isAnybodyJoin()) {
                    /* allow join anybody to event */
                    $userAttendee = $ev->getAttendee()->findAttendee($this->_page->_user);
                    if(!isset($userAttendee)) {
                        $tmpAttendee = new Warecorp_ICal_Attendee();
                        $tmpAttendee->setEventId($ev->getId());
                        $tmpAttendee->setOwnerType(Warecorp_ICal_Enum_OwnerType::USER);
                        $tmpAttendee->setOwnerId($this->_page->_user->getId());
                        $tmpAttendee->setAnswer('NONE');
                        $tmpAttendee->setAnswerText('');
                        $userAttendee = $tmpAttendee;
                    }
                    $evAtt[$key]['attendee'] = $userAttendee;
                }
            // *****************

            /** --- **/
            $objRootEvent = new Warecorp_ICal_Event($ev->getRootId());
            if ( Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($ev, $this->currentGroup, $this->_page->_user) && $objRootEvent->getSharing()->getCount() ) {
                $arrEventsLinks[$ev->getId()] = ( $objRootEvent->getSharing()->getCount() ) ? true : false;
            } elseif ( $objRootEvent->getSharing()->isShared($this->currentGroup) && Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges( $this->currentGroup, $this->_page->_user ) ) {
                $arrEventsLinks[$ev->getId()] = true;
            } else {
                $arrEventsLinks[$ev->getId()] = false;
            }
            /** --- **/
        }
    }

    $lstTags = new Warecorp_ICal_Event_List_Tag();
    $lstTags->setEntityIdsFilter($arrEventIds);

    $this->view->arrEventsLinks = $arrEventsLinks;
    $this->view->arrEvents = $arrEvents;
    $this->view->currentTimezone = $currentTimezone;
    $this->view->viewMode = $mode;
    $this->view->lstTags = $lstTags;
    $this->view->currentRound = Warecorp_Round_Item::getCurrentRound($this->currentGroup);
    $this->view->_RSVP_ = (empty($_SESSION['_RSVP_']) ? false : true);

    if ( $this->currentGroup instanceof Warecorp_Group_Family ) {
        $this->view->bodyContent = 'groups/calendar/action.list.view.family.tpl';
    } else {
        $this->view->bodyContent = 'groups/calendar/action.list.view.tpl';
    }

    /**
    * +-------------------------------------------------------------------
    * |
    * |
    * +-------------------------------------------------------------------
    */
    function eventDateCmpAsc($event1, $event2)
    {
        return $event1->getDtstartValue() < $event2->getDtstartValue();
    }
    function eventDateCmpDesc($event1, $event2)
    {
        return $event1->getDtstartValue() > $event2->getDtstartValue();
    }
    function eventDateCmpAscAnonymous($event1, $event2)
    {
        return $event1->getOriginalDtstartValue() < $event2->getOriginalDtstartValue();
    }
    function eventDateCmpDescAnonymous($event1, $event2)
    {
        return $event1->getOriginalDtstartValue() > $event2->getOriginalDtstartValue();
    }

