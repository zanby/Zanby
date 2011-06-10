<?php

    Warecorp::addTranslation('/modules/groups/calendar/action.month.view.php.xml');

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
    $this->_page->Xajax->registerUriFunction( "bookmarkit", "/ajax/bookmarkit/" );
    $this->_page->Xajax->registerUriFunction( "addbookmark", "/ajax/addbookmark/" );
    $this->_page->Xajax->registerUriFunction( "addToFriends", "/ajax/addToFriends/" );
    $this->_page->Xajax->registerUriFunction( "addToFriendsDo", "/ajax/addToFriendsDo/" );
    //
    $this->_page->Xajax->registerUriFunction( "doEasyAddEvent", "/groups/calendarEventEasyAdd/" );
    $this->_page->Xajax->registerUriFunction( "doViewDayDetails", "/groups/calendarEventDayDetails/" );
    
    //FIXME определить , какая таймзона является дефолтовой 
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того, 
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';
    
    $objRequest = $this->getRequest();
    
    /**
    * Check date
    */
    $dataIsExist = true;
    $dataIsExist = $dataIsExist && $objRequest->getParam('year', null);
    $dataIsExist = $dataIsExist && $objRequest->getParam('month', null);
    /**
    * Не указан год и месяц просмотра
    */
    if ( !$dataIsExist ) {
        /**
         * block is used for zccf, zccf-base, zccf-alt only
         * for all different projects $this->objRoundFilter is NULL
         * @author Artem Sukharev
         */
        if ( isset($this->objRoundFilter) ) {
            $defaultTimezone = date_default_timezone_get();
            date_default_timezone_set($currentTimezone);
            $objDateNow = new Zend_Date();
            date_default_timezone_set($defaultTimezone);
            /**
             * if 'now' between filter start and end dates - use it as start
             * otherwise - use filter start date
             */
            if ( $objDateNow->isLater($this->objRoundFilter['objDateStart']) && $objDateNow->isEarlier($this->objRoundFilter['objDateEnd']) ) {
                $objRequest->setParam('year', $objDateNow->toString('yyyy'));
                $objRequest->setParam('month', $objDateNow->toString('MM'));
            } else {
                $objRequest->setParam('year', $this->objRoundFilter['objDateStart']->toString('yyyy'));
                $objRequest->setParam('month', $this->objRoundFilter['objDateStart']->toString('MM'));
            }
        } else {
            $defaultTimezone = date_default_timezone_get();
            date_default_timezone_set($currentTimezone);
            $objDateNow = new Zend_Date();
            date_default_timezone_set($defaultTimezone);
            $objRequest->setParam('year', $objDateNow->toString('yyyy'));
            $objRequest->setParam('month', $objDateNow->toString('MM'));
        }
    }
    $objRequest->setParam('year', ( floor($objRequest->getParam('year')) < 1970 ) ? 1970 : floor($objRequest->getParam('year')) );
    $objRequest->setParam('year', ( floor($objRequest->getParam('year')) > 2037 ) ? 2037 : floor($objRequest->getParam('year')) );
    $objRequest->setParam('month', ( floor($objRequest->getParam('month')) < 1 ) ? 1 : floor($objRequest->getParam('month')) );
    $objRequest->setParam('month', ( floor($objRequest->getParam('month')) > 12 ) ? 12 : floor($objRequest->getParam('month')) );
    
    /**
    * Build dates
    */
    $objCurrDate = new Zend_Date(sprintf('%04d', $objRequest->getParam('year')).'-'.sprintf('%02d', $objRequest->getParam('month')).'-01T000000', Zend_Date::ISO_8601, 'en_US');

    /**
     * block is used for zccf, zccf-base, zccf-alt only
     * for all different projects $this->objRoundFilter is NULL
     * @author Artem Sukharev
     */
//    if ( isset($this->objRoundFilter) ) {
//        $d1 = clone $this->objRoundFilter['objDateStart'];
//        $d1->setDay(1);
//        $d2 = clone $this->objRoundFilter['objDateEnd'];
//        $d2->setDay($d2->get(Zend_Date::MONTH_DAYS));
//        if ( $objCurrDate->isEarlier($d1) || $objCurrDate->isLater($d2) ) {
//            $this->_redirect( $this->currentGroup->getGroupPath('calendar.month.view/year/'.$d1->toString('yyyy').'/month/'.$d1->toString('MM')) );
//        }
//        unset($d1, $d2);
//    }

    $objPrevDate = clone $objCurrDate;
    $objPrevDate->sub(1, Zend_Date::MONTH);
    $objNextDate = clone $objCurrDate;
    $objNextDate->add(1, Zend_Date::MONTH);

    /**
     * block is used for zccf, zccf-base, zccf-alt only
     * for all different projects $this->objRoundFilter is NULL
     * @author Artem Sukharev
     */
    if ( isset($this->objRoundFilter) ) {
        $d1 = clone $this->objRoundFilter['objDateStart'];
        $d1->setDay(1);
        if ( $objPrevDate->isEarlier($d1) ) $objPrevDate = null;
        if ( $objNextDate->isLater($this->objRoundFilter['objDateEnd']) ) $objNextDate = null;
    }
    

    date_default_timezone_set($currentTimezone);
    $strPeriodStartDate =  sprintf('%04d', $objRequest->getParam('year')).'-'.sprintf('%02d', $objRequest->getParam('month')).'-01T000000';
    $periodStartDate = new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
    $periodStartEnd = clone $periodStartDate;        
    $periodStartEnd->add(1, Zend_Date::MONTH);
    $objDateNow = new Zend_Date();
    $this->view->objDateNow = $objDateNow;
    $this->view->strObjDateNow = $objDateNow->toString('yyyy-MM-dd');

    Warecorp_ICal_Calendar_Cfg::setWkst('SU');
    $objYear = new Warecorp_ICal_Calendar_Year($objRequest->getParam('year'));
    $objYear->setShowMonths($objRequest->getParam('month'));
    $this->view->objYear = $objYear;

    $lstGroupIds = array($this->currentGroup->getId());

    $objEvents = new Warecorp_ICal_Event_List_Standard();
    $objEvents->setOwnerIdFilter($lstGroupIds);
    $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
    $objEvents->setShowCopyFilter(true);
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
    
    $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        
    /**
    * SubGroups for Family Group
    */
    if ( $this->currentGroup instanceof Warecorp_Group_Family ) {
        $lstChildrenGroups = $this->currentGroup->getGroups()->setTypes(array('simple','family'))->getList();
        if ( sizeof($lstChildrenGroups) != 0 ) {
            foreach ( $lstChildrenGroups as &$objChildGroup ) {
                if ( $AccessManager->canViewEvents($objChildGroup, $this->_page->_user) ) {
                    $objEvents->setOwnerIdFilter($objChildGroup->getId());
                    // privacy
                    if ( $AccessManager->canViewPublicEvents($objChildGroup, $this->_page->_user) && $AccessManager->canViewPrivateEvents($objChildGroup, $this->_page->_user) ) {
                        $objEvents->setPrivacyFilter(array(0,1));
                    } elseif ( $AccessManager->canViewPublicEvents($objChildGroup, $this->_page->_user) ) {
                        $objEvents->setPrivacyFilter(array(0));
                    } elseif ( $AccessManager->canViewPrivateEvents($objChildGroup, $this->_page->_user) ) {
                        $objEvents->setPrivacyFilter(array(1));
                    } else {
                        $objEvents->setPrivacyFilter(null);
                    }
                    // sharing
                    if ( $AccessManager->canViewSharedEvents($objChildGroup, $this->_page->_user) ) {
                        $objEvents->setSharingFilter(array(0,1));
                    } else {
                        $objEvents->setSharingFilter(array(0));
                    }
                    $arrChildEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
                    $arrEvents = array_merge($arrEvents, $arrChildEvents);

                    $lstGroupIds[] = $objChildGroup->getId();
                }
            }
        }
    }
      

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
    * form
    */
    $form_sel = new Warecorp_Form( 'form_select_month', 'POST' );
    $monthnames = array(
        '1'     => Warecorp::t('January'),
        '2'     => Warecorp::t('February'),
        '3'     => Warecorp::t('March'),
        '4'     => Warecorp::t('April'),
        '5'     => Warecorp::t('May'),
        '6'     => Warecorp::t('June'),
        '7'     => Warecorp::t('July'),
        '8'     => Warecorp::t('August'),
        '9'     => Warecorp::t('September'),
        '10'    => Warecorp::t('October'),
        '11'    => Warecorp::t('November'),
        '12'    => Warecorp::t('December')    
    );
    $years = array();
    $currenYear = $objCurrDate->toString('yyyy');
    for ( $i = $currenYear - 5; $i <= $currenYear + 5; $i++ ) {
        if ( $i >= 1970 && $i <= 2037 ) $years[$i] = $i;
    }
    
    /**
    * Assign template vars    
    */
    $this->view->canCreateEvent = $AccessManager->canCreateEvent($this->currentGroup, $this->_page->_user);
    $this->view->arrDates = $dates;
    $this->view->objCurrDate = $objCurrDate;
    $this->view->objPrevDate = $objPrevDate;
    $this->view->objNextDate = $objNextDate;
    $this->view->form_sel = $form_sel;
    $this->view->monthnames = $monthnames;
    $this->view->years = $years;
    $this->view->objEventList = $objEventList;
    $this->view->currentTimezone = $currentTimezone;
    $this->view->currentRound = Warecorp_Round_Item::getCurrentRound($this->currentGroup);
    $this->view->bodyContent = 'groups/calendar/action.month.view.tpl';
    
    /*
    $__db = Zend_Registry::get('DB');
    Zend_Debug::dump($__db->getProfiler()->getTotalNumQueries());
    foreach ($__db->getProfiler()->getQueryProfiles() as $query) {
        Zend_Debug::dump($query->getElapsedSecs() . ' : ' . str_replace("\n", ' ', $query->getQuery()));
    }
    exit;
    */
