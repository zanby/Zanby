<?php
    if ( !Warecorp_ICal_Calendar_Cfg::isMapViewEnabled() ) $this->_redirect($this->currentUser->getUserPath('calendar.list.view'));
    
    Warecorp::addTranslation("/modules/users/calendar/action.map.view.php.xml");
    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();

    if ( null === $this->_page->_user->getId() && !Warecorp_ICal_AccessManager_Factory::create()->canAnonymousViewEvents($this->currentUser) ) $this->_redirectToLogin();
    if ( false == Warecorp_ICal_AccessManager_Factory::create()->canViewEvents($this->currentUser, $this->_page->_user) ) {
        $this->view->friendsAssoc = $this->_page->_user->getId() ? $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array() ;
        $this->view->errorMessage = Warecorp::t('Sorry, you can not view this calendar');
        $this->view->bodyContent = 'users/calendar/action.event.error.message.tpl';
        return ;
    }
    
    /**
    * Register Ajax Functions
    */
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/" );
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/" );
    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/" );
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/" );
    
    $this->_page->Xajax->registerUriFunction("doAttendeeEvent", "/users/calendarEventAttendee/" );
    $this->_page->Xajax->registerUriFunction("doAttendeeEventSignup", "/groups/calendarEventAttendeeSignup/" );
    
    //FIXME определить , какая таймзона является дефолтовой 
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того, 
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    $objRequest = $this->getRequest();
    
    if ($objRequest->getParam('page', null) !== null) $currentPage =  $objRequest->getParam('page', null);
    else $currentPage = 1;
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
     * Find events that belog to user
     * $arrEvents will contains all this events
     */
    $objEvents = new Warecorp_ICal_Event_List_Standard();
    $objEvents->setTimezone($currentTimezone);
    $objEvents->setOwnerIdFilter($this->currentUser->getId());
    $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);
    $objEvents->setWithVenueOnly( true );
    // privacy
    if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentUser, $this->_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentUser, $this->_page->_user) ) {
        $objEvents->setPrivacyFilter(array(0,1));
    } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentUser, $this->_page->_user) ) {
        $objEvents->setPrivacyFilter(array(0));
    } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentUser, $this->_page->_user) ) {
        $objEvents->setPrivacyFilter(array(1));
    } else {
        $objEvents->setPrivacyFilter(null);
    }
    // sharing
    if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($this->currentUser, $this->_page->_user) ) {
        $objEvents->setSharingFilter(array(0,1));
    } else {
        $objEvents->setSharingFilter(array(0));
    }
    // upcomming events only
    $objEvents->setCurrentEventFilter(true);
    $objEvents->setExpiredEventFilter(false);

    /****************************************/
    
    if ( $this->currentUser->getId() == $this->_page->_user->getId() ) {
        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getListByUser($this->currentUser);
    } else {
        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();
    }
    // paging
    $arrEventIds = array_keys($arrEvents);
    $count = count($arrEvents);
    $arrEvents = array_slice($arrEvents, ($currentPage - 1)*$pageSize, $pageSize, true);

    $P = new Warecorp_Common_PagingProduct($count, $pageSize, $this->currentUser->getUserPath('calendar.map.view', false));
    $paging = $P->makePaging($currentPage);
    $this->view->paging = $paging;
    
    $arrEventsLinks = array();
    if ( sizeof($arrEvents) != 0 ) {
        foreach ( $arrEvents as $ev_id => &$ev ) {
            //  Find the event first date
            $ev = new Warecorp_ICal_Event($ev_id);

            $strFirstDate = $lstEventsObj->findFirstEventDate($ev, $objNowDate);

            if ( null !== $strFirstDate ) {
                $ev->setTimezone($currentTimezone);
                $ev->setDtstart($strFirstDate);
            }
            $objRootEvent = new Warecorp_ICal_Event($ev->getRootId());
            if ( Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($ev, $this->currentUser, $this->_page->_user) && $objRootEvent->getSharing()->getCount() ) {
                $arrEventsLinks[$ev->getId()] = ( $objRootEvent->getSharing()->getCount() ) ? true : false;
            } elseif ( $objRootEvent->getSharing()->isShared($this->currentUser) && Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges( $this->currentUser, $this->_page->_user ) ) {
                $arrEventsLinks[$ev->getId()] = true;
            } else {
                $arrEventsLinks[$ev->getId()] = false;
            }
            
            //if ( $objVenue = $ev->getEventVenue() ) $objVenue->getGeoCordinates();
        }
    }
    //  Sort events by date
    if ( $this->_page->_user && null !== $this->_page->_user->getId() ) usort($arrEvents, "eventDateCmpDesc");
    else usort($arrEvents, "eventDateCmpDescAnonymous");
    
    /****************************************/
    
    //$arrEventIds = array();
    $arrEventsLinks = array();
    $lstEventsObj = new Warecorp_ICal_Event_List();
    $lstEventsObj->setTimezone($currentTimezone);
    if ( sizeof($arrEvents) != 0 ) { 
        $tz = date_default_timezone_get();
        date_default_timezone_set($currentTimezone);
        $objNowDate = new Zend_Date();
        date_default_timezone_set($tz);
        foreach ( $arrEvents as &$ev ) {
            //  Find the event first date
            //$arrEventIds[] = $ev->getId();
            $strFirstDate = $lstEventsObj->findFirstEventDate($ev, $objNowDate);
            if ( null !== $strFirstDate ) {
                $ev->setTimezone($currentTimezone);
                $ev->setDtstart($strFirstDate);
            }

            $objRootEvent = new Warecorp_ICal_Event($ev->getRootId());
            if ( Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($ev, $this->currentUser, $this->_page->_user) && $objRootEvent->getSharing()->getCount() ) {
                $arrEventsLinks[$ev->getId()] = ( $objRootEvent->getSharing()->getCount() ) ? true : false;
            } elseif ( $objRootEvent->getSharing()->isShared($this->currentUser) && Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges( $this->currentUser, $this->_page->_user ) ) {
                $arrEventsLinks[$ev->getId()] = true;
            }
            else {
                $arrEventsLinks[$ev->getId()] = false;
            }
        }
    }
    
    //  Sort events by date
    //if ( $this->_page->_user && null !== $this->_page->_user->getId() ) usort($arrEvents, "eventDateCmpDesc"); 
    //else usort($arrEvents, "eventDateCmpDescAnonymous");
    

    //  Load Tags
    $lstTags = new Warecorp_ICal_Event_List_Tag();
    $lstTags->setEntityIdsFilter($arrEventIds);
    
    //
    $this->view->arrEventsLinks = $arrEventsLinks;
    $this->view->arrEvents = $arrEvents;
    $this->view->currentTimezone = $currentTimezone;
    $this->view->lstTags = $lstTags;
    $this->view->_RSVP_ = (empty($_SESSION['_RSVP_']) ? false : true);
    
    $this->view->bodyContent = 'users/calendar/action.map.view.tpl';

    //  Save events location to cache to use it on map       
    $mapCache = md5(uniqid(mt_rand(), true));
    $cache = Warecorp_Cache::getFileCache();
    $cache->save(array_unique($arrEventIds), $mapCache, array(), 60*60*10);
    $this->view->mapCache = $mapCache;
    $this->view->clat = ( $this->_page->_user && $this->_page->_user->getId() ) ? $this->_page->_user->getCity()->getLatitude() : '';
    $this->view->clng = ( $this->_page->_user && $this->_page->_user->getId() ) ? $this->_page->_user->getCity()->getLongitude() : '';
    

    $this->view->friendsAssoc = $this->_page->_user->getId() ? $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array() ;
    
    /**
    * +-------------------------------------------------------------------
    * |
    * |
    * +-------------------------------------------------------------------
    */
    function eventDateCmpDesc($event1, $event2)
    {
        return $event1->getDtstartValue() > $event2->getDtstartValue();
    }
    function eventDateCmpDescAnonymous($event1, $event2)
    {
        return $event1->getOriginalDtstartValue() > $event2->getOriginalDtstartValue();
    }
