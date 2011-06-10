<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.day.details.php.xml');

    //FIXME определить , какая таймзона является дефолтовой 
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того, 
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    $objResponse = new xajaxResponse();

    /**
     * Check Access to page
     */
    $AccessManager = Warecorp_ICal_AccessManager_Factory::create();
    if ( null === $this->_page->_user->getId() && !$AccessManager->canAnonymousViewEvents($this->currentGroup) ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('calendar.month.view');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        return $objResponse;
    }
    if ( false == $AccessManager->canViewEvents($this->currentGroup, $this->_page->_user) ) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.month.view'));
        return $objResponse;
    }
    
    
    
    if ( preg_match_all('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $strDate, $matches) ) {
        $year   = $matches[1][0];
        $month  = $matches[2][0];
        $day    = $matches[3][0];
        
        /**
        * Check Dates
        */
        if ( $year > 2038 || $year < 1970 ) return;
        if ( $month > 12 || $month < 1 ) return;
        if ( $day > 31 || $day < 1 ) return;
        $oCDate = new Zend_Date(sprintf('%04d', $year).'-'.sprintf('%02d', $month).'-01', Zend_Date::ISO_8601);
        if ( $day > $oCDate->get(Zend_Date::MONTH_DAYS) ) return;
        unset($oCDate);

        date_default_timezone_set($currentTimezone);
        $strPeriodStartDate =  sprintf('%04d', $year).'-'.sprintf('%02d', $month).'-01T000000';
        $periodStartDate = new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
        $periodStartEnd = clone $periodStartDate;        
        $periodStartEnd->add(1, Zend_Date::MONTH);
        
        $lstGroupIds = array($this->currentGroup->getId());
        
        if ( $this->currentGroup instanceof Warecorp_Group_Family ) {
            $lstChildrenGroups = $this->currentGroup->getGroups()->setTypes(array('simple','family'))->getList();
            if ( sizeof($lstChildrenGroups) != 0 ) {
                foreach ( $lstChildrenGroups as &$objChildGroup ) {
                    if ( Warecorp_ICal_AccessManager_Factory::create()->canViewEvents($objChildGroup, $this->_page->_user) ) {
                        $lstGroupIds[] = $objChildGroup->getId();
                    }
                    /**
                    * Если надо включать в показ коммитии для групп входящих в фемели, надо добавить сюда блок
                    */
                }
            }
        }

        /**
         * Find all event that can present on page
         * from all groups
         */
        $objEvents = new Warecorp_ICal_Event_List_Standard();
        $objEvents->setOwnerIdFilter($lstGroupIds);
        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
        $objEvents->setShowCopyFilter(true);
        // privacy
        if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentGroup, $this->_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setPrivacyFilter(array(0,1));
        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setPrivacyFilter(array(0));
        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setPrivacyFilter(array(1));
        } else {
            $objEvents->setPrivacyFilter(null);
        }
        // sharing
        if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setSharingFilter(array(0,1));
        } else {
            $objEvents->setSharingFilter(array(0));
        }
        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();

        /**
         * Build dates for all events
         */
        $objEventList = new Warecorp_ICal_Event_List();
        $objEventList->setTimeZone($currentTimezone);
        
        // тест, чтобы показывались скрытые события
        $periodStartDate->sub(7, Zend_Date::DAY);
        $periodStartEnd->add(7, Zend_Date::DAY);

        $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
        $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));        
        $dates = $objEventList->buildRecurList($arrEvents);
        
        /**
         * if user is anonymous user all dates should be converted to event original timezone from $currentTimezone
         */
        if ( !$this->_page->_user || null === $this->_page->_user->getId() ) {      
            if ( sizeof($dates) != 0 ) {
                $arrDatesAnonymos = array();
                foreach ( $dates as $dateKey => $times ) {
                    foreach ( $times as $timeKey => $events ) {
                        foreach ( $events as $idKey => $eventInfo ) {
                            $arrDatesAnonymos[$eventInfo['original']['key_date']][$eventInfo['original']['key_time']][$eventInfo['original']['key_id']] = $eventInfo; 
                        }
                    }
                }
                ksort($arrDatesAnonymos);
                $dates = $arrDatesAnonymos;            
            }
        }
            
        /**
         * Find (filter) events for current date
         */
        $arrEvents = array();
        if ( isset($dates[$strDate]) ) {            
            foreach ( $dates[$strDate] as $strTime => $events ) {
                if ( sizeof($events) ) {
                    foreach ( $events as $eventInfo ) {
                         $objTmpEvent = new Warecorp_ICal_Event($eventInfo['id']);
                         $objTmpEvent->setTimezone($currentTimezone);
                         /**
                          * Changed according #4241
                          * original : //$objTmpEvent->setDtstart($strDate.'T'.str_replace(':','',$eventInfo['time'])); 
                          */
                         $objTmpEvent->setDtstart($eventInfo['year'].'-'.$eventInfo['month'].'-'.$eventInfo['day'].'T'.str_replace(':','',$eventInfo['time']));
                         $arrEvents[] = $objTmpEvent;
                    }
                }                
            }
        }
        $this->view->arrEvents = $arrEvents;
        $this->view->year = $year;
        $this->view->month = $month;
        $this->view->day = $day;
        $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();
        
        $Content = $this->view->getContents('groups/calendar/ajax/action.event.day.details.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title("");
        $popup_window->content($Content);
        $popup_window->width(500)->height(350)->fixed(false)->open($objResponse);
    }
    
    
