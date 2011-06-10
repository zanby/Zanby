<?php
Warecorp::addTranslation('/modules/groups/promotion/action.invite1.php.xml');

    /**
    * Init Zend Cache
    */
    $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
    $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};

    $this->view->bodyContent = "groups/promotion/invite1.tpl";
    $this->_page->Xajax->registerUriFunction("search_onchange_country", "/groups/searchOnChangeCountry/");
    $this->_page->Xajax->registerUriFunction("deletesearch", "/groups/deletesearch/");    
    unset($_SESSION['group_search']);
    $this->params['country']  = !empty($this->params['country']) ? $this->params['country'] : (isset($_SESSION['group_search']['country']) && $_SESSION['group_search']['country']>0 ? (int)$_SESSION['group_search']['country'] : 0);
    $this->params['state']    = !empty($this->params['state']) ? $this->params['state'] : (isset($_SESSION['group_search']['state']) && $_SESSION['group_search']['state']>0 ? (int)$_SESSION['group_search']['state'] : 0);
    $this->params['city']     = !empty($this->params['city']) ? $this->params['city'] : (isset($_SESSION['group_search']['city']) && $_SESSION['group_search']['city']>0 ? (int)$_SESSION['group_search']['city'] : 0);
    
    $country    = Warecorp_Location_Country::create($this->params['country']);
    $state      = Warecorp_Location_State::create($this->params['state']);
    $city       = Warecorp_Location_City::create($this->params['city']);
    
    $countries  = array("0"=>Warecorp::t("All Countries")) + Warecorp_Location::getCountriesListAssoc();
    $states     = array("0"=>Warecorp::t("All States")) + $country->getStatesListAssoc($this->params['country']);
    $cities     = array("0"=>Warecorp::t("All Cities")) + $state->getCitiesListAssoc($this->params['state']);

    $allCategoriesObj = new Warecorp_Group_Category_List();
    $allCategories = array("0"=>Warecorp::t("All Categories")) + $allCategoriesObj->returnAsAssoc()->getList();
    
    $groupSearch = new Warecorp_Group_Search();
    
    if( !$categories = $cache->load('Warecorp_Group_Search__getCategoriesListAssoc__countryid__stateid__cityid') ) {        
        $categories = $groupSearch->getCategoriesListAssoc();
        $cache->save($categories, 'Warecorp_Group_Search__getCategoriesListAssoc__countryid__stateid__cityid', array(), $cfgLifetime->categories);
    }
    
    if (!isset($groupSearch) || !($groupSearch instanceof Warecorp_Group_Search )) $groupSearch = new Warecorp_Group_Search();

    $form = new Warecorp_Form('search_group', 'POST',  $this->currentGroup->getGroupPath("invitesearch") . "preset/new/");
	$this->view->_url = $this->currentGroup->getGroupPath('invitesearch', false);
	
    $tagsListObj = new Warecorp_Group_Tag_List(null);
    $tagsListObj->returnAsAssoc()->setListSize(30)->setCurrentPage(1)->setOrder('rating DESC');
    $tags = $tagsListObj->getListByLocation();
    // end top tags

    // shuffle tags
	$shuffledTags = array();
	while (is_array($tags) && count($tags) > 0) {
	    $val = array_rand($tags);
	    $shuffledTags[$val] = $tags[$val];
	    unset($tags[$val]);
	}
    
//	$topCities = Warecorp_Location::getGroupTopCitiesList(null, null, 12);
//    $topCountries = Warecorp_Location::getGroupTopCountriesList(12);
    if( !$topCities = $cache->load('Warecorp_Location__getGroupTopCitiesList__city__state__limit12') ) {
        $topCities = Warecorp_Location::getGroupTopCitiesList(null, null, 12);
        $cache->save($topCities, 'Warecorp_Location__getGroupTopCitiesList__city__state__limit12', array(), $cfgLifetime->topCities);
    }
    
    if( !$topCountries = $cache->load('Warecorp_Location__getGroupTopCountriesList__limit12') ) {
        $topCountries = Warecorp_Location::getGroupTopCountriesList(12);
        $cache->save($topCountries, 'Warecorp_Location__getGroupTopCountriesList__limit12', array(), $cfgLifetime->topCountries);
    }
    
    $this->view->bodyContent   = "groups/promotion/invite1.tpl";
    $this->view->form          = $form;
    $this->view->allCategories = $allCategories;
    $this->view->categories	= $categories;
    $this->view->countries     = $countries;
    $this->view->states        = $states;
    $this->view->topTags       = $shuffledTags;
    $this->view->topCities		= $topCities;
    $this->view->savedSearches = $groupSearch->getSavedSearchesAssoc($this->_page->_user->getId(), 2);
    $this->view->topCountries	= $topCountries;
