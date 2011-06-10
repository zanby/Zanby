<?php
    Warecorp::addTranslation("/modules/users/calendar/action.month.view.php.xml");
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
    $this->_page->Xajax->registerUriFunction( "bookmarkit", "/ajax/bookmarkit/" );
    $this->_page->Xajax->registerUriFunction( "addbookmark", "/ajax/addbookmark/" );
    $this->_page->Xajax->registerUriFunction( "addToFriends", "/ajax/addToFriends/" );
    $this->_page->Xajax->registerUriFunction( "addToFriendsDo", "/ajax/addToFriendsDo/" );
    //
    $this->_page->Xajax->registerUriFunction( "doEasyAddEvent", "/users/calendarEventEasyAdd/" );
    $this->_page->Xajax->registerUriFunction( "doViewDayDetails", "/users/calendarEventDayDetails/" );
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");
    

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
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set($currentTimezone);
        $objDateNow = new Zend_Date();
        date_default_timezone_set($defaultTimezone);
        $objRequest->setParam('year', $objDateNow->toString('yyyy'));
        $objRequest->setParam('month', $objDateNow->toString('MM'));
        
    }
    $objRequest->setParam('year', ( floor($objRequest->getParam('year')) < 1970 ) ? 1970 : floor($objRequest->getParam('year')) );
    $objRequest->setParam('year', ( floor($objRequest->getParam('year')) > 2037 ) ? 2037 : floor($objRequest->getParam('year')) );
    $objRequest->setParam('month', ( floor($objRequest->getParam('month')) < 1 ) ? 1 : floor($objRequest->getParam('month')) );
    $objRequest->setParam('month', ( floor($objRequest->getParam('month')) > 12 ) ? 12 : floor($objRequest->getParam('month')) );
    
    /**
    * Build dates
    */
    $objCurrDate = new Zend_Date(sprintf('%04d', $objRequest->getParam('year')).'-'.sprintf('%02d', $objRequest->getParam('month')).'-01T000000', Zend_Date::ISO_8601, 'en_US');
    $objPrevDate = clone $objCurrDate;
    $objPrevDate->sub(1, Zend_Date::MONTH);
    $objNextDate = clone $objCurrDate;
    $objNextDate->add(1, Zend_Date::MONTH);

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


    $objEvents = new Warecorp_ICal_Event_List_Standard();
    $objEvents->setOwnerIdFilter($this->currentUser->getId());
    $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);
    $objEvents->setShowCopyFilter(true);
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
    
    if ( $this->currentUser->getId() == $this->_page->_user->getId() ) {
        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getListByUser($this->currentUser);
    } else {
        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
    }

    $objEventList = new Warecorp_ICal_Event_List();
    $objEventList->setTimezone($currentTimezone);
    
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
    $this->view->arrDates = $dates;
    $this->view->objCurrDate = $objCurrDate;
    $this->view->objPrevDate = $objPrevDate;
    $this->view->objNextDate = $objNextDate;
    $this->view->form_sel = $form_sel;
    $this->view->monthnames = $monthnames;
    $this->view->years = $years;
    $this->view->objEventList = $objEventList;
    $this->view->currentTimezone = $currentTimezone;
    $this->view->bodyContent = 'users/calendar/action.month.view.tpl';
    $this->view->friendsAssoc = $this->_page->_user->getId() ? $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array() ;
