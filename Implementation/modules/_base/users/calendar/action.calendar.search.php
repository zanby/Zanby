<?php

    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();
    $this->view->Warecorp_Venue_AccessManager = new Warecorp_Venue_AccessManager();
    $this->view->Warecorp_Group_Factory = new Warecorp_Group_Factory();

    if (!Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges($this->currentUser, $this->_page->_user)) {
        $this->_redirect($this->currentUser->getUserPath('calendar.list.view'));
    }

    $this->_page->Xajax->registerUriFunction( "doAttendeeEvent", "/users/calendarEventAttendee/" );

    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    /**
     * @todo remove this
     * $arrEvents is not used
     */
    //$query = $this->_db->select()->from('calendar_events', array('event_id'));
    //$arrEvents = $this->_db->fetchCol($query);

    $objEventList = new Warecorp_ICal_Event_List();
    $objEventList->setTimeZone($currentTimezone);

    $eventSearch    = new Warecorp_ICal_Search();
    $eventSearch->setUser($this->_page->_user);
    $_presets       = array('new', 'category', 'country', 'city', 'date', 'tag', 'worldwide', 'section');
    $_url           = $this->currentUser->getUserPath('calendarsearch', false);
    $_orders        = $eventSearch->getOrders();

    $eventsList     = array();
    $size           = 10;
    $count          = 0;

//    $tagsList = new Warecorp_User_Tag_List($this->currentUser->getId());
//    $tags = $tagsList->returnAsAssoc()->setCurrentPage(1)->setListSize(30)->getList();

    // Cache settings
    $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");

    if ( !empty($this->params['saved']) ) {
        $eventSearch = new Warecorp_ICal_Search($this->params['saved']);
        $eventSearch->setUser($this->_page->_user);
        if ( isset($eventSearch->params) && is_array($eventSearch->params) ) {
            $this->params = array_merge($this->params, $eventSearch->params);
        }
    }

    if ( isset($this->params['preset']) && in_array($this->params['preset'], $_presets) ) {

        // new search
        $events = array();
        $s = &$_SESSION['event_search'];
        $s = array();

        $this->params['when'] = isset($this->params['when']) ? trim($this->params['when']) : "";
        $eventSearch->parseParams($this->params);
        /**
         * @author Artem Sukharev
        if ( empty($this->params['when']) ) {
            $this->params['when'] = 'all future';
            $eventSearch->parseParams($this->params);
        }
        */

        switch ($this->params['preset']) {
            case 'new' :
                /**
                 * @author Artem Sukharev
                $eventSearch->parseParams($this->params);
                 */
                $this->params['where'] = implode(', ', array_reverse(array_unique($eventSearch->whereParts)));
                $eventSearch->setDefaultOrder();
                $events = $eventSearch->searchByCriterions();
                break;
            case 'date' :
                $datePresets = array('today'=> 'today', 'week'=>'this week', 'nweek'=>'next week', 'month'=>'this month', 'future'=>'all future');
                $this->params['when'] = ( isset($this->params['id']) && isset($datePresets[$this->params['id']]) ) ? $datePresets[$this->params['id']] : "";
                $eventSearch->parseParamsWhen($this->params);
                $eventSearch->setDefaultOrder();
                $events = $eventSearch->searchByCriterions();
                break;
            case 'section' :
                $this->params['when']="all future";
                $eventSearch->parseParamsWhen($this->params);
                $eventSearch->setDefaultOrder();
                if (!empty($this->params['filter']) && !empty($this->params['filterid'])) { // filter
                    $eventSearch->setFilter( $this->params['filter'], $this->params['filterid']);
                }
                $events = $eventSearch->searchByCriterions();
                break;
            case 'category' :
                $this->params['id'] = isset($this->params['id']) ? floor($this->params['id']) : "0";
                $eventSearch->setDefaultOrder();
                $events = $eventSearch->searchByCategory($this->params['id']);
                break;
            case 'country' :
                $this->params['id'] = isset($this->params['id']) ? floor($this->params['id']) : "0";
                $country = Warecorp_Location_Country::create($this->params['id']);
                $this->params['where'] = $country->name;
                $eventSearch->parseParamsWhere($this->params);
                $eventSearch->setDefaultOrder();
                $events = $eventSearch->searchByCriterions();
                break;
            case 'city' :
                $this->params['id'] = isset($this->params['id']) ? floor($this->params['id']) : "0";
                $city       = Warecorp_Location_City::create($this->params['id']);
                $state      = Warecorp_Location_State::create($city->stateId);
                $country    = Warecorp_Location_Country::create($state->countryId);
                if ($country->name && $state->name && $city->name) {
                    $this->params['where'] = $city->name.", ".$state->name.", ".$country->name;
                    $eventSearch->parseParamsWhere($this->params);
                    $eventSearch->setDefaultOrder();
                    $events = $eventSearch->searchByCriterions();
                }
                break;
            case 'tag' :
                $this->params['id'] = isset($this->params['id']) ? floor($this->params['id']) : "0";
                $tag = new Warecorp_Data_Tag($this->params['id']);
                $this->params['keywords'] = $tag->name;
                $eventSearch->parseParamsKeywords($this->params);
                $eventSearch->setDefaultOrder();
                $events = $eventSearch->searchByCriterions();
                break;
            case 'worldwide' :
                $eventSearch->setDefaultOrder();
                $events = $eventSearch->searchByWorldwide();
            default:
                break;
        }

        if ( is_array($events) && sizeof($events) > 1 ) {
            $events = array_unique( $events );
        }

        $s['preset']    = $this->params['preset'];
        $s['id']        = isset($this->params['id']) ? $this->params['id'] : null;
        $s['keywords']  = isset($this->params['keywords']) ? $this->params['keywords'] : null;
        $s['where']     = isset($this->params['where']) ? $this->params['where'] : null;
        $s['when']      = isset($this->params['when']) ? $this->params['when'] : null;
        $cache->save($events, 'search_events_'.session_id(), array(), 7200);
        if ( isset($this->params['saved']) ) { // if restored from saved search then redirect
            $this->params['page'] = ( !empty($this->params['page']) ) ? (int)$this->params['page'] : 1;
            $this->_redirect($_url.$eventSearch->getPagerLink($this->params)."/page/{$this->params['page']}/");
        }
        if ( !empty($eventSearch->paramsOrder) ) $this->params = array_merge($this->params, $eventSearch->paramsOrder);
        $eventsList = array_slice($events, 0, $size, true);
        $count = count($events);
        unset($events);

    } else {
        // old search
        $s = &$_SESSION['event_search'];
        $events = $cache->load('search_events_'.session_id());

        if ( !is_array($events) ) {
            $this->_redirect($this->currentUser->getUserPath('calendarsearchindex'));
        } else {
            $events = array_unique( $events );
        }
        $cache->save($events, 'search_events_'.session_id(), array(), 7200);


        $this->params['page']       = (isset($this->params['page'])) ? floor($this->params['page']) : 1;
        $this->params['keywords']   = isset($s['keywords']) ? trim($s['keywords']) : "";
        $this->params['where']      = isset($s['where']) ? trim($s['where']) : "";
        $this->params['when']       = isset($s['when']) ? trim($s['when']) : "";

        $s['order']     = !empty($this->params['order']) ? $this->params['order'] : "";
        $s['filter']    = !empty($this->params['filter']) ? $this->params['filter'] : "";
        $s['filterid']  = !empty($this->params['filterid']) ? $this->params['filterid'] : "";
        $s['direction'] = !empty($this->params['direction']) ? $this->params['direction'] : "";

        if (empty($this->params['order']) && empty($this->params['filter'])) {
            $eventsList = array_slice($events, ($this->params['page']-1)*$size, $size, true);
        } elseif (!empty($this->params['order'])) {
            $eventsList = $eventSearch->getOrdered($this->params, $events, $size);
            if ($eventSearch->getIncludeIds()!== null) $events = array_intersect($events, $eventSearch->getIncludeIds());
            if ($eventSearch->getExcludeIds()!== null) $events = array_diff($events, $eventSearch->getExcludeIds());
        }
        if (!empty($this->params['filter']) && !empty($this->params['filterid'])) { // filter
            $eventSearch->setFilter( $this->params['filter'], $this->params['filterid']);
            if ($eventSearch->getIncludeIds()!== null) $events = array_intersect($events, $eventSearch->getIncludeIds());
            if ($eventSearch->getExcludeIds()!== null) $events = array_diff($events, $eventSearch->getExcludeIds());
            $eventsList = array_slice($events, ($this->params['page']-1)*$size, $size, true);
        }
        $count = count($events);
        unset($events);
    }

    /**
     * Initialization global objects that is used in script
     * #4241 
     */
    $lstEventsObj = new Warecorp_ICal_Event_List();
    $lstEventsObj->setTimezone($currentTimezone);

    foreach ($eventsList as &$event) {
        $event = new Warecorp_ICal_Event($event);
        /**
         * Find the event first date
         * Added according issue #4241
         */
        if ( !$this->_page->_user || null == $this->_page->_user->getId() )     
            $usedTimezone = $event->getTimezone() ? $event->getTimezone() : 'UTC';
        else $usedTimezone = $currentTimezone;
        
        $lstEventsObj->setTimezone($usedTimezone);
        $strFirstDate = $lstEventsObj->findFirstEventDate($event, 
            ( !empty($eventSearch->timeInterval['begin']) ) ? $eventSearch->timeInterval['begin'] : null, 
            ( !empty($eventSearch->timeInterval['end']) ) ? $eventSearch->timeInterval['end'] : null
        );
        if ( null !== $strFirstDate ) {
            /**
             * Added according issue #4241
             */
            if ( !$this->_page->_user || null == $this->_page->_user->getId() ) {
                $DurationSec = $event->getDurationSec();
                $event->setDtstart($strFirstDate);
                $objEndDate = clone $event->getDtstart();
                $objEndDate->add($DurationSec, Zend_Date::SECOND);
                $event->setDtend($objEndDate->toString('yyyy-MM-ddTHHmmss'));
            } else {
                $DurationSec = $event->getDurationSec();
                $event->setTimezone($currentTimezone);
                $event->setDtstart($strFirstDate);
                $objEndDate = clone $event->getDtstart();
                $objEndDate->add($DurationSec, Zend_Date::SECOND);
                $event->setDtend($objEndDate->toString('yyyy-MM-ddTHHmmss'));
            }
        }        
    }

//    $llist = new Warecorp_ICal_Event_List_Standard();
//    $llist->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->setExpiredEventFilter(true)->setCurrentEventFilter(true)->getList();

    $this->params['page'] = isset($this->params['page']) ? (int)$this->params['page'] : 1;
    $P = new Warecorp_Common_PagingProduct($count, $size, $_url.$eventSearch->getPagerLink($this->params));
    $paging = $P->makePaging($this->params['page']);

    $form = new Warecorp_Form('search_events', 'POST',  $this->currentUser->getUserPath('calendarsearch'));
    $formRemember = new Warecorp_Form('search_remember', 'POST', $this->currentUser->getUserPath('calendarsearchremember'));
    $_tagList = new Warecorp_ICal_Event_List_Tag();

    $dateObj = new Zend_Date();
    $dateObj->setTimezone($this->_page->_user->getTimezone());

    $this->view->assign($this->params);
    $this->view->bodyContent   = 'users/calendar/search.result.tpl';
    $this->view->form          = $form;
    $this->view->formRemember  = $formRemember;
    $this->view->categories    = $eventSearch->getCategoriesList();
    $this->view->savedSearches = $eventSearch->getSavedSearchesAssoc($this->currentUser->getId(), $_tagList->EntityTypeId);
    $this->view->_url          = $_url;
    $this->view->eventsList    = $eventsList;
    $this->view->beginInterval = empty($eventSearch->timeInterval['begin']) ? null : $eventSearch->timeInterval['begin'];
    $this->view->endInterval   = empty($eventSearch->timeInterval['end']) ? null : $eventSearch->timeInterval['end'];
    $this->view->paging        = $paging;
    $this->view->TIMEZONE      = $dateObj->get(Zend_Date::TIMEZONE);
    $this->view->objEventList  = $objEventList;
    $this->view->params        = $this->params;
    $this->view->currentTimezone = $currentTimezone;
