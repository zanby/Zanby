<?php
    Warecorp::addTranslation("/modules/users/lists/action.lists.search.php.xml");

    if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
        $this->_redirect($this->currentUser->getUserPath('lists'));
    }

    $_url = $this->currentUser->getUserPath(null, false);

    $this->_page->Xajax->registerUriFunction("closePopup", "/ajax/closePopup/");
    $this->_page->Xajax->registerUriFunction("list_share", "/users/listsShare/");
    $this->_page->Xajax->registerUriFunction("list_unshare", "/users/listsUnshare/");
    $this->_page->Xajax->registerUriFunction("list_share_popup_show", "/users/listsSharePopupShow/");
    $this->_page->Xajax->registerUriFunction("list_share_popup_close", "/users/listsSharePopupClose/");
    $this->_page->Xajax->registerUriFunction("list_add_popup_show", "/users/listsAddListPopupShow/");
    $this->_page->Xajax->registerUriFunction("list_add_popup_close", "/users/listsAddListPopupClose/");

    if (!empty($this->params['saved'])) {
        $listSearch = new Warecorp_List_Search($this->params['saved']);
        if (isset($listSearch->params) && is_array($listSearch->params)) {
            $this->params = array_merge($this->params, $listSearch->params);
        }
    } else {
        $listSearch = new Warecorp_List_Search();
    }

    $myListMenu = array(
        array('title' => Warecorp::t('List Index'), 'url' => $_url."/lists/", 'active' =>0),
        array('title' => Warecorp::t('Search and Browse Lists'), 'url' => $_url."/listssearch/", 'active' =>1),
    );

    $_presets = array('search', 'type', 'friends', 'tag', 'groups', 'families', 'country', 'city', 'category');

    $listsList      = array();
    $tags           = array();
    $allCountries   = array();
    $allStates      = array();
    $allCities      = array();
    $size           = 10;
    $onCol          = 1;

    $listTypes = Warecorp_List_Item::getListTypesListAssoc();

    // Cache settings
    $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");


    //
    $_template = 'users/lists/search.result.tpl';
    if (isset($this->params['preset']) && in_array($this->params['preset'], $_presets)) {
        // search by preset
        $this->params['id'] = isset($this->params['id']) ? (int)$this->params['id'] : 0;

        $_SESSION['list_search'] = array();
        $_SESSION['list_search']['preset'] = $this->params['preset'];
        $_SESSION['list_search']['id'] = $this->params['id'];

        switch ($this->params['preset']) {
            case 'search' :
                $lists = array();
                $listSearch = new Warecorp_List_Search($this->params['id']);
                break;
            case 'type':
                $this->params['type'] = $this->params['id'];
                $this->params['filter'] = $this->params['id'];
                //$listSearch->searchByType($this->params['id']);
                $lists = $listSearch->searchByCriterions($this->params);
                //$lists = $listSearch->getIntersection();
                break;
            case 'friends':
                $_SESSION['list_search']['title'] = Warecorp::t(SITE_NAME_AS_STRING.' Lists from my friends');
                $lists = $listSearch->searchByFriends($this->currentUser);
                break;
            case 'groups':
                $_SESSION['list_search']['title'] = Warecorp::t(SITE_NAME_AS_STRING.' Lists from my groups');
                $lists = $listSearch->searchByGroups($this->currentUser);
                break;
            case 'families':
                $_SESSION['list_search']['title'] = Warecorp::t(SITE_NAME_AS_STRING.' Lists from my group families');
                $lists = $listSearch->searchByFamilies($this->currentUser);
                break;
            case 'tag':
                $tag = new Warecorp_Data_Tag($this->params['id']);
                $this->params['keywords'] = $tag->name;
                $listSearch->setKeywords($tag->name);
                $lists = $listSearch->searchByCriterions($this->params);
                // $listSearch->searchByKeywords();
                // $listSearch->searchByType(0);
                // $lists = $listSearch->getIntersection();
                $_SESSION['list_search']['keywords']    = $this->params['keywords'] =  (is_array($listSearch->keywords)) ? (implode(' ', $listSearch->keywords)) : "";
                break;
            case 'country':
                $objCountry = Warecorp_Location_Country::create(floor($this->params['id']));
                if ($objCountry->name) {
                    $_SESSION['list_search']['title'] = Warecorp::t(SITE_NAME_AS_STRING.' Lists from %s', $objCountry->name);
                }
                $lists = $listSearch->searchByCountry($this->params['id']);
                break;
            case 'city':
                $objCity = Warecorp_Location_City::create(floor($this->params['id']));
                if ($objCity->name) {
                    $_SESSION['list_search']['title'] = Warecorp::t(SITE_NAME_AS_STRING.' Lists from %s', $objCity->name);
                }
                $lists = $listSearch->searchByCity($this->params['id']);
                break;
            case 'category':
                $objCategory = new Warecorp_Group_Category(floor($this->params['id']));
                if ($objCategory->name) {
                    $_SESSION['list_search']['title'] = Warecorp::t(SITE_NAME_AS_STRING.' Lists from groups with %s category', $objCategory->name);
                }
                $lists = $listSearch->searchByGroupCategory($this->params['id']);
                break;
            default:
                break;
        }

        $cache->save($lists, 'search_lists_'.session_id(), array(), 7200);
        if (isset($this->params['saved'])) { // if restored from saved search then redirect
            $this->params['page'] = (!empty($this->params['page'])) ? (int)$this->params['page'] : 1;
            $this->_redirect($_url.$listSearch->getPagerLink($this->params)."/page/{$this->params['page']}/");
        }

        $output = array_slice($lists, 0, $size, true);
        $listsList = array_keys($output);

        $count = count($lists);

    } elseif (isset($this->params['new'])) {
        // search throw form
        $_SESSION['list_search'] = array();
        $_SESSION['list_search']['keywords']    = $this->params['keywords'] = isset($this->params['keywords']) ? trim($this->params['keywords']) : "";
        $_SESSION['list_search']['type']        = $this->params['type']     = isset($this->params['type']) ? trim($this->params['type']) : 0;
        $_SESSION['list_search']['new']         = $this->params['new'];
        $this->params['filter'] = $_SESSION['list_search']['type'];

        $listSearch->setKeywords($this->params['keywords']);
        $lists = $listSearch->searchByCriterions($this->params);
        
//        $listSearch->searchByKeywords();
//        $listSearch->searchByType($this->params['type']);
//        $lists = $listSearch->getIntersection();

        $_SESSION['list_search']['keywords']    = $this->params['keywords'] =  (is_array($listSearch->keywords)) ? (implode(' ', $listSearch->keywords)) : "";
        
        $cache->save($lists, 'search_lists_'.session_id());

        if (isset($this->params['saved'])) { // if restored from saved search then redirect
            $this->params['page'] = (!empty($this->params['page'])) ? (int)$this->params['page'] : 1;
            $this->_redirect($_url.$listSearch->getPagerLink($this->params)."/page/{$this->params['page']}/");
        }

        if (empty($_SESSION['list_search']['keywords'])) {
            $this->params['order'] = "items";
        }

        $output = $listSearch->applyFilter($this->params, $lists);
        $count = count($output);
        $output = array_slice($output, 0, $size, true); //
        $listsList = array_keys($output);

    } elseif (isset($this->params['order']) || isset($this->params['page']) || isset($this->params['filter'])) {

        $this->params['page']       = isset($this->params['page']) ? (int)$this->params['page'] : 1;
        $this->params['keywords']   = isset($_SESSION['list_search']['keywords']) ? trim($_SESSION['list_search']['keywords']) : "";
        $this->params['type']       = isset($_SESSION['list_search']['type']) ? trim($_SESSION['list_search']['type']) : "";

        $_SESSION['list_search']['order']       = !empty($this->params['order']) ? $this->params['order'] : "";
        $_SESSION['list_search']['filter']      = !empty($this->params['filter']) ? $this->params['filter'] : "";
        $_SESSION['list_search']['direction']   = !empty($this->params['direction']) ? $this->params['direction'] : "";

        // restore from cache
        $lists = $cache->load('search_lists_'.session_id());

        if ( !is_array($lists) ) {
            $this->_redirect($_url.'/listssearch/');
        }
        if (empty($lists)) $lists = array(''=>'');

	$offset = ($this->params['page'] - 1) * $size;
        $output = $listSearch->applyFilter($this->params, $lists);
        $count = count($output);
        $output = array_slice($output, $offset, $size, true);  
        $listsList = array_keys($output); 

    } elseif (isset($this->params['view'])) {
        // display lists of countries or states or cities
        switch ($this->params['view']) {
            case 'countries' :
                $this->params['preset_country'] = true;
                // break missed specially
            case 'allcountries':
                $allCountries = Warecorp_Location::getCountriesListAssoc();
                $countriesListsCounts = Warecorp_List_Search::getCountriesListsCounts();
                foreach ($countriesListsCounts as $row) {
                    if (isset($allCountries[$row['id']])) $allCountries[$row['id']] = array('name'=>$allCountries[$row['id']], 'cnt'=>$row['lists_cnt']);
                }
/*                $countries = array();
                foreach ($countriesListsCounts as $row) {
                    if (isset($allCountries[$row['id']])) $countries[$row['id']] = array('name'=>$allCountries[$row['id']], 'cnt'=>$row['lists_cnt']);
                }
                $allCountries = $countries;*/
                $onCol = ceil(count($allCountries)/4);
                $_template = 'users/lists/allcountries.tpl';
                break;
            case 'allstates' :
                if (!isset($this->params["country"])) $this->params["country"] = "1";
                $objCountry = Warecorp_Location_Country::create(floor($this->params["country"]));
                if (!$objCountry->name) $country = Warecorp_Location_Country::create(1);
                $allStates = $objCountry->getStatesListAssoc();
                $statesListsCount = Warecorp_List_Search::getStatesListsCounts($objCountry->id);
                foreach ($statesListsCount as $row) {
                    if (isset($allStates[$row['id']])) $allStates[$row['id']] = array('name'=>$allStates[$row['id']], 'cnt'=>$row['lists_cnt']);
                }
                $onCol = ceil(count($allStates)/5);
                $_template = 'users/lists/allstates.tpl';
                $this->view->objCountry = $objCountry;
                break;
            case 'allcities' :
                if (!isset($this->params["state"])) $this->params["state"] = "1";
                $objState = Warecorp_Location_State::create(floor($this->params["state"]));
                if (!$objState->name) $objState = Warecorp_Location_State::create(1);
                $objCountry = Warecorp_Location_Country::create($objState->countryId);
                $allCities = $objState->getCitiesListAssoc();

                $citiesListsCount = Warecorp_List_Search::getCitiesListsCounts($objState->id);
                $cities = array();
                foreach ($citiesListsCount as $row) {
                    if (isset($allCities[$row['id']])) $cities[$row['id']] = array('name'=>$allCities[$row['id']], 'cnt'=>$row['lists_cnt']);
                }
                $allCities = $cities;
/*                foreach ($citiesListsCount as $row) {
                    if (isset($allCities[$row['id']])) $allCities[$row['id']] = array('name'=>$allCities[$row['id']], 'cnt'=>$row['lists_cnt']);
                }*/

                $onCol = ceil(count($allCities)/5);
                $_template = 'users/lists/allcities.tpl';
                $this->view->objCountry = $objCountry;
                $this->view->objState = $objState;
                break;
        }

    } else {
        // index page of search
        $tagsList = new Warecorp_User_Tag_List($this->currentUser->getId());
        $tags = $tagsList->returnAsAssoc()->setCurrentPage(1)->setListSize(30)->getListTags();
        $_template = 'users/lists/search.index.tpl';
    }


    foreach ($listsList as &$list) {
        $list = new Warecorp_List_Item($list);
    }

    if (!isset($count)) $count = 0;

    $this->params['page'] = (isset($this->params['page'])) ? (int)$this->params['page'] : 1;
    $this->params['filter'] = (!isset($this->params['filter']) || !isset($listTypes[$this->params['filter']])) ? 0 : $this->params['filter'];

    $typesTabs = array('0'=>'All') + $listTypes;
    $_order_params = "";
    if (!empty($this->params['order']) && isset($_order[$this->params['order']]) &&  isset($this->params['direction']) && in_array($this->params['direction'], array('asc','desc'))) {
        $_order_params = "order/".$this->params['order']."/direction/".$this->params['direction']."/";
    }

    foreach ($typesTabs as $id => &$type) {
        $type = array(
            'title'     =>$type,
            'url'       =>$_url.'/listssearch/filter/'.$id.'/'.$_order_params,
        );
    }

    $dateObj = new Zend_Date();
    $dateObj->setTimezone($this->_page->_user->getTimezone());
    
    $P = new Warecorp_Common_PagingProduct($count, $size, $_url.$listSearch->getPagerLink($this->params));
    $paging = $P->makePaging($this->params['page']);

    $form = new Warecorp_Form('search_list', 'POST', $_url.'/listssearch/');
    $formRemember = new Warecorp_Form('search_remember', 'POST', $_url.'/listssearchremember/');
    $this->view->assign($this->params);
    $_list = new Warecorp_List_Item();
    $this->view->bodyContent   = $_template;
    $this->view->form          = $form;
    $this->view->formRemember  = $formRemember;
    $this->view->myListMenu    = $myListMenu;
    $this->view->listTypes     = Warecorp_List_Search::getListTypesList();//array('0'=>'All List Types') + $listTypes;
    $this->view->listTypesAssoc= array('0'=>Warecorp::t('All List Types')) + $listTypes;
    $this->view->typesTabs     = $typesTabs;
    $this->view->categories    = Warecorp_List_Search::getGroupCategoriesList();
    $this->view->_url          = $_url;
    $this->view->topCountries  = Warecorp_List_Search::getTopCountries(12);//Warecorp_Location::getGroupTopCountriesList(12);
    $this->view->topCities     = Warecorp_List_Search::getTopCities(null, 12); //Warecorp_Location::getGroupTopCitiesList(null, null, 12);
    $this->view->listsList     = $listsList;
    $this->view->paging        = $paging;
    $this->view->tags          = $tags;
    $this->view->allCountries  = $allCountries;
    $this->view->allStates     = $allStates;
    $this->view->allCities     = $allCities;
    $this->view->onCol         = $onCol;
    $this->view->savedSearches = $listSearch->getSavedSearchesAssoc($this->currentUser->getId(), $_list->EntityTypeId);
    $this->view->searchTitle   = isset($_SESSION['list_search']['title']) ? $_SESSION['list_search']['title'] : "";
    $this->view->TIMEZONE      = $dateObj->get(Zend_Date::TIMEZONE);
    $this->view->friendsListsCount     = $listSearch->getFriendsListsCount($this->currentUser);
    $this->view->groupsListsCount      = $listSearch->getGroupsListsCount($this->currentUser);
    $this->view->familiesListsCount    = $listSearch->getFamiliesListsCount($this->currentUser);
    $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();
