<?php
    Warecorp::addTranslation("/modules/users/calendar/action.list.view.php.xml");
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
    //
    $this->_page->Xajax->registerUriFunction("doCancelEvent", "/users/calendarEventCancel/" );
    $this->_page->Xajax->registerUriFunction("doAttendeeEvent", "/users/calendarEventAttendee/" );
    $this->_page->Xajax->registerUriFunction("doAttendeeEventSignup", "/groups/calendarEventAttendeeSignup/" );
    $this->_page->Xajax->registerUriFunction("viewAttendeeEvent", "/users/calendarEventAttendeeView/" );
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");
    $this->_page->Xajax->registerUriFunction("doEventShare", "/users/calendarEventShare/" );
    $this->_page->Xajax->registerUriFunction("doEventUnShare", "/users/calendarEventUnShare/" );
    $this->_page->Xajax->registerUriFunction("doClientUnshareEvent", "/users/calendarClientUnshareEvent/" );
    $this->_page->Xajax->registerUriFunction("doEventRemoveMe", "/users/calendarEventRemoveMe/" );            

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
    /**
     * Initialization global objects that is used in script 
     */
    $lstEventsObj = new Warecorp_ICal_Event_List();
    $lstEventsObj->setTimezone($currentTimezone);
    $tz = date_default_timezone_get();
    date_default_timezone_set($currentTimezone);
    $objNowDate = new Zend_Date();
    date_default_timezone_set($tz);

    if ( !Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges($this->currentUser, $this->_page->_user) ) {
        $mode = 'active';
    }
    
    /**
     * Find events that belog to user
     * $arrEvents will contains all this events
     */
    $objEvents = new Warecorp_ICal_Event_List_Standard();
    $objEvents->setTimezone($currentTimezone);
    $objEvents->setOwnerIdFilter($this->currentUser->getId());
    $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);
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
    
    if ( $mode == 'active' ) {
        $objEvents->setCurrentEventFilter(true);
        $objEvents->setExpiredEventFilter(false);
    } else {
        $objEvents->setCurrentEventFilter(false);
        $objEvents->setExpiredEventFilter(true);    
    }
    
    if ( $this->currentUser->getId() == $this->_page->_user->getId() ) {
        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getListByUser($this->currentUser);
    } else {
        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
    }

    $arrEventIds = array();
    $arrEventsLinks = array();
    $lstEventsObj = new Warecorp_ICal_Event_List();
    $lstEventsObj->setTimezone($currentTimezone);
    if ( sizeof($arrEvents) != 0 ) { 
        $tz = date_default_timezone_get();
        date_default_timezone_set($currentTimezone);
        $objNowDate = new Zend_Date();
        date_default_timezone_set($tz);
        foreach ( $arrEvents as &$ev ) {
            /**
             * Find the event first date
             */
            $arrEventIds[] = $ev->getId();
            $strFirstDate = $lstEventsObj->findFirstEventDate($ev, $objNowDate);
            if ( null !== $strFirstDate ) {
                $ev->setTimezone($currentTimezone);
                $ev->setDtstart($strFirstDate);
            }

            $objRootEvent = new Warecorp_ICal_Event($ev->getRootId());
            if ( Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($ev, $this->currentUser, $this->_page->_user) && $objRootEvent->getSharing()->getCount() ) {
                $arrEventsLinks[$ev->getId()] = ( $objRootEvent->getSharing()->getCount() ) ? true : false;
            }
            elseif ( $objRootEvent->getSharing()->isShared($this->currentUser) && Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges( $this->currentUser, $this->_page->_user ) ) {
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
/*    if(LOCALE == "rss"){
        include_once(ENGINE_DIR."/rss.class.php");
        $rss = new UniversalFeedCreator();
        $rss->encoding = 'utf-8';
        $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';    
        $rss->link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $rss->title = $this->currentUser->getLogin() . " calendar ";
        $rss->description = $this->currentUser->getLogin() . " calendar events ";
        $rss->copyright = "Copyright &copy; 2007, Zanby";
        
        foreach ($arrEvents as $event){
            $item = new FeedItem();
            $item->title = $event->getTitle();
            $item->link = "http://".$_SERVER['HTTP_HOST'] . "/" . "en/calendar.event.view/id/". $event->getId() . "/uid/".$event->getUid()."/";
            
            $item->description = "Date: ";
            $objEventDtstartLocal = $event->convertTZ($event->getDtstart(), $currentTimezone);
            $objEventDtendLocal = $event->convertTZ($event->getDtend(), $currentTimezone);
            if ($objEventDtstartLocal->toString('MM/dd/yyyy') != $objEventDtendLocal->toString('MM/dd/yyyy')) {
                $item->description .= $objEventDtstartLocal->toString('MM/dd/yyyy').' - '.$objEventDtendLocal->toString('MM/dd/yyyy');
            } else {
                $item->description .= $objEventDtstartLocal->toString('MM/dd/yyyy');
            }
            if ($event->isAllDay()) {
                $item->description .= ' All Day';
            } else {
                $item->description .= ' '.$objEventDtstartLocal->toString('h:mm').$objEventDtstartLocal->get('MERIDIEM').' - '.$objEventDtendLocal->toString('h:mm').$objEventDtendLocal->get('MERIDIEM');
                if ($event->isTimezoneExists()) {
                    $item->description .= ' '.$objEventDtstartLocal->get(Zend_Date::TIMEZONE);
                }
            }
            $item->description .= '<br/>';
            $item->description .= 'Host: '.$event->getCreator()->getLogin().'<br/>';
            $item->description .= 'Description: '.$event->getDescription().'<br/>';
            $venue = $event->getEventVenue();
            if ($venue !== null) {
                $item->description .= 'Location: '.$venue->getCity()->name.', '.$venue->getCity()->getState()->name.', '.$venue->getCity()->getState()->getCountry()->name.'<br/>';
            } else {
                $item->description .= 'Location: No Venue<br/>';
            }            
            $tags = $event->getTags()->getList();
            if (!empty($tags)) {
                $item->description .= 'Tags: ';
                foreach ($tags as $tag) {
                    $item->description .= $tag->name.' ';
                }
            } else {
                $item->description .= 'Tags: No tags';
            }
            $item->description .= '<br/>';
            if ($event->getRrule() !== null) {                
                $item->description .= 'Recurrence: '.$event->getRrule()->getFreq();
            } else {
                $item->description .= 'Recurrence: No';
            }
            $rss->addItem($item);
        }
        header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
        print $rss->createFeed("RSS2.0");
        exit;
    }  */
    // RSS end    

    $lstTags = new Warecorp_ICal_Event_List_Tag();
    $lstTags->setEntityIdsFilter($arrEventIds);

    $this->view->arrEventsLinks = $arrEventsLinks;
    $this->view->arrEvents = $arrEvents;
    $this->view->currentTimezone = $currentTimezone;
    $this->view->viewMode = $mode;
    $this->view->lstTags = $lstTags;
    $this->view->_RSVP_ = (empty($_SESSION['_RSVP_']) ? false : true);

    //$l = new Warecorp_ICal_Event_List_Tag();

    $this->view->bodyContent = 'users/calendar/action.list.view.tpl';
    $this->view->friendsAssoc = $this->_page->_user->getId() ? $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array() ;

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
    
    /*
    $__db = Zend_Registry::get('DB');
    Zend_Debug::dump($__db->getProfiler()->getTotalNumQueries());
    foreach ($__db->getProfiler()->getQueryProfiles() as $query) {
        Zend_Debug::dump($query->getElapsedSecs() . ' : ' . str_replace("\n", ' ', $query->getQuery()));
    }
    exit;
    */
