<?php
    Warecorp::addTranslation('/modules/groups/action.index.php.xml');

    /**
    * Init Zend Cache
    */
    $cache = $this->getInvokeArg('bootstrap')->getResource('FileCache');
    $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};

    $allCategoriesObj = new Warecorp_Group_Category_List();
    $allCategories = $allCategoriesObj->returnAsAssoc()->setRelation('simple')->getList();

    $_url = BASE_URL."/".LOCALE."/groups";

    $this->_page->breadcrumb = array();
    $this->_page->hideBreadcrumb = true;
    if (!isset($this->params["view"]) || !in_array($this->params["view"], array('allcities', 'allstates', 'allcountries'))) {
        $this->_page->hideBreadcrumb = true;
    }

    if (!($this->_page->_user->isAuthenticated())) {
        if (!isset($this->params["view"]) || !in_array($this->params["view"], array('allcities', 'allstates', 'allcountries'))) {
            $this->params["view"] = 'world';
        }
        $city = $state = $country = "";
    } else {
        $city = Warecorp_Location_City::create($this->_page->_user->getCityId());
        $state = Warecorp_Location_State::create($city->stateId);
        $country = Warecorp_Location_Country::create($state->countryId);
    }

    $tagsListObj = new Warecorp_Group_Tag_List(null);
    $tagsListObj->returnAsAssoc()->setListSize(30)->setCurrentPage(1)->setOrder('rating DESC');
    $tagsListObj->addFilter('entity_type_name', crc32('simple'));

    $groupListObj = new Warecorp_Group_List();
    $lastFamily = $groupListObj->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY)
                               ->setCurrentPage(1)
                               ->setListSize(5)
                               ->setOrder('zgi.creation_date DESC')
                               ->getList();
    $topCities = ""; $topStates = ""; $topCountries = "";
    $currentTab = ""; $tags = "";

    $allCountries = ""; $allCities = ""; $allStates = ""; $onCol = 0;
    $CountGroupsByStates = ""; $CountGroupsByCountries = "";
    $CountGroupsByCities = "";
    $groupSearch = new Warecorp_Group_Search();
    $template = 'groups/index.tpl';
    if (!isset($this->params["view"])) $this->params["view"] = "country";
    if ($this->params["view"] == "city"){
        $currentTab = "city";

        if( !$tags = $cache->load('Warecorp_Group_Tag_List__getListByLocation__cityid'.$city->id) ) {
            $tags = $tagsListObj->addFilter('city_id', $city->id)->getListByLocation();
            $tags = Warecorp_List_Tags::shuffleTagsArray($tags);
            $cache->save($tags, 'Warecorp_Group_Tag_List__getListByLocation__cityid'.$city->id, array(), $cfgLifetime->tags);
        }

        if( !$allCategories = $cache->load('Warecorp_Group_Search__getCategoriesListAssoc__countryid__stateid__cityid'.$city->id.'') ) {
            $allCategories = $groupSearch->getCategoriesListAssoc(null, null, $city);
            $cache->save($allCategories, 'Warecorp_Group_Search__getCategoriesListAssoc__countryid__stateid__cityid'.$city->id.'', array(), $cfgLifetime->categories);
        }

    } elseif ($this->params["view"] == "allcities"){
        ////////////////////////////
        if (!isset($this->params["state"])) {
        /* VSV ALL world cities */
            $cities_list = Warecorp_Location::getGroupTopCitiesList(null,null,500);
            $_allCities = array();
            $CountGroupsByCities = array();
            foreach($cities_list as $key=>$city) {
                $_allCities[$city['city_id']] = $city['city_name'];
                $CountGroupsByCities[$city['city_id']] = $city['groups_count'];
            }
            $topCities = Warecorp_Location::getGroupTopCitiesList(null, null, 10, true);
            $city = null;
            $state = null;
            $country = null;

        } else {
            $state = Warecorp_Location_State::create(floor($this->params["state"]));
            if (!$state->name) $state = Warecorp_Location_State::create(1);
            $country = Warecorp_Location_Country::create($state->countryId);
            $_allCities0 = $state->getCitiesListAssoc();
            $CountGroupsByCities = Warecorp_Location ::getCountGroupsByCities($state->id);
            $topCities = Warecorp_Location::getGroupTopCitiesList($state->id, null, 10, true);
            $_allCities = array();
            foreach($CountGroupsByCities as $key=>$count) {
                $_allCities[$key] = $_allCities0[$key];
            }
        }
        asort($_allCities,SORT_STRING);
        $allCities = array();
        $letter = array();
        $next_letter = '*';
        foreach($_allCities as $key=>$city) {
            if($CountGroupsByCities[$key]>0) {
                $allCities[$key] = $city;
                if($next_letter!==$city[0]) {
                    $letter[$key] = $city[0];
                    $next_letter = $city[0];
                }
            }
        }
        $onCol = ceil(count($CountGroupsByCities)/3);
        $template = 'groups/allcities.tpl';
    ////////////////////////////
    } elseif ($this->params["view"] == "state"){
        $currentTab = "state";

        if( !$topCities = $cache->load('Warecorp_Location__getGroupTopCitiesList__city'.$city->stateId.'__state__limit12') ) {
            $topCities = Warecorp_Location::getGroupTopCitiesList($city->stateId, null, 12);
            $cache->save($topCities, 'Warecorp_Location__getGroupTopCitiesList__city'.$city->stateId.'__state__limit12', array(), $cfgLifetime->topCities);
        }

        if( !$tags = $cache->load('Warecorp_Group_Tag_List__getListByLocation__stateid'.$city->stateId) ) {
            $tags = $tagsListObj->addFilter('state_id', $city->stateId)->getListByLocation();
            $tags = Warecorp_List_Tags::shuffleTagsArray($tags);
            $cache->save($tags, 'Warecorp_Group_Tag_List__getListByLocation__stateid'.$city->stateId, array(), $cfgLifetime->tags);
        }

        if( !$allCategories = $cache->load('Warecorp_Group_Search__getCategoriesListAssoc__countryid__stateid'.$state->id.'__cityid') ) {
            $allCategories = $groupSearch->getCategoriesListAssoc(null, $state, null);
            $cache->save($allCategories, 'Warecorp_Group_Search__getCategoriesListAssoc__countryid__stateid'.$state->id.'__cityid', array(), $cfgLifetime->categories);
        }

    } elseif ($this->params["view"] == "allstates"){
        if (!isset($this->params["country"])) $this->params["country"] = "1";
        $country = Warecorp_Location_Country::create(floor($this->params["country"]));
        if (!$country->name) $country = Warecorp_Location_Country::create(1);

    /*
        $allStates = $country->getStatesListAssoc();
        $CountGroupsByStates = Warecorp_Location ::getCountGroupsByStates($this->params["country"]);
        $onCol = ceil(count($allStates)/3);
    */

        $CountGroupsByStates = Warecorp_Location ::getCountGroupsByStates($this->params["country"]);
        $_allStates = $country->getStatesListAssoc();
        asort($_allStates,SORT_STRING);
        $allStates = array();
        $letter = array();
        $next_letter = '*';
        foreach($_allStates as $key=>$state) {
            if($CountGroupsByStates[$key]>0) {
                $allStates[$key] = $state;
                if($next_letter!==$state[0]) {
                    $letter[$key] = $state[0];
                    $next_letter = $state[0];
                }
            }
        }
        $onCol = ceil(count($allStates)/3);
        $template = 'groups/allstates.tpl';
    } elseif ($this->params["view"] == "country"){
        $currentTab = "country";

        if( !$topCities = $cache->load('Warecorp_Location__getGroupTopCitiesList__city__state'.$state->countryId.'__limit12') ) {
            $topCities = Warecorp_Location::getGroupTopCitiesList(null, $state->countryId, 12);
            $cache->save($topCities, 'Warecorp_Location__getGroupTopCitiesList__city__state'.$state->countryId.'__limit12', array(), $cfgLifetime->topCities);
        }

    //    $topStates = Warecorp_Location::getGroupTopStatesList($state->countryId, 12);

        if( !$tags = $cache->load('Warecorp_Group_Tag_List__getListByLocation__countryid'.$state->countryId) ) {
            $tags = $tagsListObj->addFilter('country_id', $state->countryId)->getListByLocation();
            $tags = Warecorp_List_Tags::shuffleTagsArray($tags);
            $cache->save($tags, 'Warecorp_Group_Tag_List__getListByLocation__countryid'.$state->countryId, array(), $cfgLifetime->tags);
        }

        if( !$allCategories = $cache->load('Warecorp_Group_Search__getCategoriesListAssoc__countryid'.$country->id.'__stateid__cityid') ) {
            $allCategories = $groupSearch->getCategoriesListAssoc($country, null, null);
            $cache->save($allCategories, 'Warecorp_Group_Search__getCategoriesListAssoc__countryid'.$country->id.'__stateid__cityid', array(), $cfgLifetime->categories);
        }

    } elseif ($this->params["view"] == "allcountries"){
    /*
        $allCountries = Warecorp_Location::getCountriesListAssoc();
        $CountGroupsByCountries = Warecorp_Location ::getCountGroupsByCountries();
        $onCol = ceil(count($CountGroupsByCountries)/3);
        //$onCol = ceil(count($allCountries)/3);
        $template = 'groups/allcountries.tpl';
    */
        $CountGroupsByCountries = Warecorp_Location ::getCountGroupsByCountries();
        $_allCountries = Warecorp_Location::getCountriesListAssoc();
        asort($_allCountries,SORT_STRING);
        $allCountries = array();
        $letter = array();
        $next_letter = '*';
        foreach($_allCountries as $key=>$country) {
            if($CountGroupsByCountries[$key]>0) {
                $allCountries[$key] = $country;
                if($next_letter!==$country[0]) {
                    $letter[$key] = $country[0];
                    $next_letter = $country[0];
                }
            }
        }

        $onCol = ceil(count($allCountries)/3);
        $template = 'groups/allcountries.tpl';


    } elseif ($this->params["view"] == "world"){
        $currentTab = "world";

        if( !$topCities = $cache->load('Warecorp_Location__getGroupTopCitiesList__city__state__limit12') ) {
            $topCities = Warecorp_Location::getGroupTopCitiesList(null, null, 12);
            $cache->save($topCities, 'Warecorp_Location__getGroupTopCitiesList__city__state__limit12', array(), $cfgLifetime->topCities);
        }

        if( !$topCountries = $cache->load('Warecorp_Location__getGroupTopCountriesList__limit12') ) {
            $topCountries = Warecorp_Location::getGroupTopCountriesList(12);
            $cache->save($topCountries, 'Warecorp_Location__getGroupTopCountriesList__limit12', array(), $cfgLifetime->topCountries);
        }

        if( !$tags = $cache->load('Warecorp_Group_Tag_List__getListByLocation__world') ) {
            $tags = $tagsListObj->getListByLocation();
            $tags = Warecorp_List_Tags::shuffleTagsArray($tags);
            $cache->save($tags, 'Warecorp_Group_Tag_List__getListByLocation__world', array(), $cfgLifetime->tags);
        }

        if( !$allCategories = $cache->load('Warecorp_Group_Search__getCategoriesListAssoc__countryid__stateid__cityid') ) {
            $allCategories = $groupSearch->getCategoriesListAssoc();
            $cache->save($allCategories, 'Warecorp_Group_Search__getCategoriesListAssoc__countryid__stateid__cityid', array(), $cfgLifetime->categories);
        }
    } else {
        $currentTab = "city";
        $tags = $tagsListObj->addFilter('city_id', $this->_page->_user->getCityId())->getListByLocation();
        $tags = Warecorp_List_Tags::shuffleTagsArray($tags);
        $allCategories = $groupSearch->getCategoriesListAssoc(null, null, $city);
    }

    //==========================================================================

    $form = new Warecorp_Form('searchForm','post',$_url.'/search/preset/new/');

    $this->_page->breadcrumb = array(Warecorp::t('Home')=>"http://" .BASE_HTTP_HOST.'/'.LOCALE.'/', Warecorp::t('Groups')=>"http://" .BASE_HTTP_HOST.'/'.LOCALE.'/groups/', 'Browse Groups'=>null);
    $this->view->categories = $allCategories;
    if(isset($letter)) {
        $this->view->letter = $letter;
    }
    $this->view->_url = $_url;
    $this->view->form = $form;
    $this->view->lastFamily = $lastFamily;
    $this->view->city = $city;
    $this->view->state = $state;
    $this->view->country = $country;
    $this->view->currentTab = $currentTab;
    $this->view->topCities = $topCities;
    $this->view->topStates = $topStates;
    $this->view->topCountries = $topCountries;
    $this->view->allGroupTags = $tags;
    $this->view->allCountries = $allCountries;
    $this->view->allStates = $allStates;
    $this->view->allCities = $allCities;
    $this->view->onCol = $onCol;

    $this->view->CountGroupsByCountries = $CountGroupsByCountries;
    $this->view->CountGroupsByStates = $CountGroupsByStates;
    $this->view->CountGroupsByCities = $CountGroupsByCities;

    $this->view->bodyContent = $template;
    /**/
    $this->view->setLayout('main_wide.tpl');
    $this->view->isRightBlockHidden = true;
