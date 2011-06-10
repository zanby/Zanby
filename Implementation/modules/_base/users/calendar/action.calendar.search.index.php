<?php

    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();

    if (!Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges($this->currentUser, $this->_page->_user)) {
        $this->_redirect($this->currentUser->getUserPath('calendar.list.view'));
    }

    $eventSearch = new Warecorp_ICal_Search();
    $eventSearch->setUser($this->_page->_user);
    $_url = $this->currentUser->getUserPath(null, false);

    //$tagsList = new Warecorp_User_Tag_List($this->currentUser->getId());
    //$tags = $tagsList->returnAsAssoc()->setCurrentPage(1)->setListSize(30)->getList();

    $_template = 'users/calendar/search.index.tpl';

    $tagsList = new Warecorp_User_Tag_List($this->currentUser->getId());
    $tags = $tagsList->returnAsAssoc()->setCurrentPage(1)->setListSize(30)->getList();
    $this->params['view'] = isset($this->params['view']) ? trim($this->params['view']) : 'index';

    $allCountries = $allStates = $allCities = array();
    $onCol = 0;

    switch ($this->params['view']) {
        case 'countries' :
            $this->params['preset_country'] = true;
            // break missed specially
        case 'allcountries':
            $allCountries   = $eventSearch->getCountriesListWithEvents();
            $onCol          = ceil(count($allCountries)/4);
            $_template      = 'users/calendar/allcountries.tpl';
            break;
        case 'allstates' :
            if ( !isset($this->params["country"]) ) $countryId = $this->params["country"];
            else                                    $countryId = 1;
            $allStates      = $eventSearch->getStatesListWithEvents($countryId);
            $onCol          = ceil(count($allStates)/5);
            $_template      = 'users/calendar/allstates.tpl';

            $objCountry     = Warecorp_Location_Country::create(floor($countryId));
            $this->view->objCountry = $objCountry;
            break;
        case 'allcities' :
            if (isset($this->params["state"])) $stateId = $this->params["state"];
            else                               $stateId = null;
            $allCities      = $eventSearch->getCitiesListWithEvents( $stateId );
            $onCol          = ceil(count($allCities)/5);
            $_template      = 'users/calendar/allcities.tpl';

            if (null !== $stateId) {
                $objState       = Warecorp_Location_State::create(floor($stateId));
                $objCountry     = Warecorp_Location_Country::create($objState->countryId);
                $this->view->objCountry = $objCountry;
                $this->view->objState = $objState;
            }
            break;
        default: break;
    }

    if ($this->params['view'] == 'index') {
        //-------------------------------------------------------------------------------------------------
        // CALENDAR
        $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';
        $this->view->currentTimezone = $currentTimezone;

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
        $objRequest->setParam('year', ( floor($objRequest->getParam('year')) > 2038 ) ? 2038 : floor($objRequest->getParam('year')) );
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

        Warecorp_ICal_Calendar_Cfg::setWkst('SU');
        $objYear = new Warecorp_ICal_Calendar_Year($objRequest->getParam('year'));
        $objYear->setShowMonths($objRequest->getParam('month'));
        $this->view->objYear = $objYear;

        if ( $this->_page->_user && null !== $this->_page->_user->getId() ) {
        $objEvents = new Warecorp_ICal_Event_List_Standard();
        $objEvents->setOwnerIdFilter($this->_page->_user->getId());
        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);
        $objEvents->setShowCopyFilter(true);

        // privacy
        $objEvents->setPrivacyFilter(array(0,1));
        // sharing
        $objEvents->setSharingFilter(array(0,1));

        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getListByUser($this->_page->_user);

        $objEventList = new Warecorp_ICal_Event_List();
        $objEventList->setTimeZone($this->_page->_user->getTimezone());

        // тест, чтобы показывались скрытые события
        $periodStartDate->sub(7, Zend_Date::DAY);
        $periodStartEnd->add(7, Zend_Date::DAY);

        $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
        $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));
        $dates = $objEventList->buildRecurList($arrEvents);
        } else {
            $dates = array();
        }
        /**
        * Assign template vars
        */
        $this->view->arrDates = $dates;
        $this->view->objCurrDate = $objCurrDate;
        $this->view->objPrevDate = $objPrevDate;
        $this->view->objNextDate = $objNextDate;

        /*SEARCH*/
        date_default_timezone_set($currentTimezone);
        $oDate = new Zend_Date();
        $oDate->setLocale('en_EN');
        // convert timezone
        //$oDate->setTimezone( 'UTC' );
        // set date
        $oDate->setYear($objRequest->getParam('year'));
        $oDate->setMonth($objRequest->getParam('month'));
        if($objRequest->getParam('day', null) !== null) $oDate->setDay($objRequest->getParam('day'));

        $daysList = array();
        $daysList['m'] = $oDate->toString("MM");
        $daysList['d'] = $oDate->toString("dd");
        $daysList['y'] = $oDate->toString("yyyy");
        $daysList['date'] = $oDate->toString("MMMM d, yyyy");
        $this->view->daysList = $daysList;

        $filterParams = array(
            'when' => $daysList['y'] . '-' . $daysList['m'] . '-' . $daysList['d']);

        //-------------------------------------------------------
        if ( $this->_page->_user && null !== $this->_page->_user->getId() ) {
        $eventsGSearch = new Warecorp_ICal_Search();
        $eventsGSearch->setUser($this->_page->_user);
        $eventsGSearch->setReturnAsObjects(true);
        $eventsGSearch->parseParams($filterParams);
        $eventsGSearch->setFilter('owner', 'friends');
        $eventzList['friends'] = $eventsGSearch->searchByCriterions();
        while (count($eventzList['friends']) > 2)
            array_pop($eventzList['friends']);
        } else {
            $eventzList['friends'] = array();
        }
        //-------------------------------------------------------
        if ( $this->_page->_user && null !== $this->_page->_user->getId() ) {
        $eventsGSearch = new Warecorp_ICal_Search();
        $eventsGSearch->setUser($this->_page->_user);
        $eventsGSearch->setReturnAsObjects(true);
        $eventsGSearch->parseParams($filterParams);
        $eventsGSearch->setFilter('owner', 'groups');
        $eventzList['groups'] = $eventsGSearch->searchByCriterions();
        while (count($eventzList['groups']) > 2)
            array_pop($eventzList['groups']);
        } else {
            $eventzList['groups'] = array();
        }
        //-------------------------------------------------------
        if ( $this->_page->_user && null !== $this->_page->_user->getId() ) {
        $eventsGSearch = new Warecorp_ICal_Search();
        $eventsGSearch->setUser($this->_page->_user);
        $eventsGSearch->setReturnAsObjects(true);
        $eventsGSearch->parseParams($filterParams);
        $eventsGSearch->setFilter('owner', 'families');
        $eventzList['families'] = $eventsGSearch->searchByCriterions();
        while (count($eventzList['families']) > 2)
            array_pop($eventzList['families']);
        } else {
            $eventzList['families'] = array();
        }
        //-------------------------------------------------------
        if ( $this->_page->_user && null !== $this->_page->_user->getId() ) $count = 2;
        else $count = 8;
        $eventsGSearch = new Warecorp_ICal_Search();
        $eventsGSearch->setUser($this->_page->_user);
        $eventsGSearch->setReturnAsObjects(true);
        $eventsGSearch->parseParams($filterParams);
        $eventsGSearch->setFilter('owner', 'other');
        $eventzList['other'] = $eventsGSearch->searchByCriterions();
        while (count($eventzList['other']) > $count)
            array_pop($eventzList['other']);
        //-------------------------------------------------------
        $this->view->eventsList = $eventzList;

        //---------------------------------------------------------------------------------------
    }



    $form       = new Warecorp_Form('search_events', 'POST', "{$_url}/calendarsearch/");
    $form_sel   = new Warecorp_Form('search_sel', 'POST', "{$_url}/calendarsearch/");
    $categories = $eventSearch->getCategoriesList();
    $_tagList   = new Warecorp_ICal_Event_List_Tag();

    $topWorldCountries = $eventSearch->getTopCountriesList(12);
    $topWorldCities    = $eventSearch->getTopCitiesList(12);

    $this->view->assign($this->params);
    $this->view->bodyContent        = $_template;
    $this->view->form               = $form;
    $this->view->form_sel		     = $form_sel;
    $this->view->_url               = $_url;
    $this->view->savedSearches      = $eventSearch->getSavedSearchesAssoc($this->currentUser->getId(), $_tagList->EntityTypeId);
    $this->view->categories         = $categories;
    $this->view->catOnCol           = (count($categories) % 3 == 0) ? (ceil(count($categories)/3)+1) : (ceil(count($categories)/3));
    $this->view->topCountries       = $topWorldCountries;
    $this->view->topCities          = $topWorldCities;
    $this->view->allCountries       = $allCountries;
    $this->view->allStates          = $allStates;
    $this->view->allCities          = $allCities;
    $this->view->onCol              = $onCol;
    $this->view->tags               = $tags;
    $this->view->topCountriesExists = ( 0 < sizeof($topWorldCountries) );
    $this->view->topCitiesExists    = ( 0 < sizeof($topWorldCities) );
