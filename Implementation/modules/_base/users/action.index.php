<?php
Warecorp::addTranslation("/modules/users/action.index.php.xml");

/**
* Init Zend Cache
*/
$cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
$cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};

$allCategoriesObj = new Warecorp_Group_Category_List();
$allCategories = $allCategoriesObj->returnAsAssoc()->setRelation('simple')->getList();

$_url = BASE_URL."/".LOCALE."/users";
$tagsListObj = new Warecorp_User_Tag_List();
$tagsListObj->returnAsAssoc()->setListSize(30)->setCurrentPage(1)->setOrder('rating DESC');

$this->_page->hideBreadcrumb = false;
if (!isset($this->params["view"]) || !in_array($this->params["view"], array('allcities', 'allstates', 'allcountries'))) {
    $this->_page->hideBreadcrumb = true;
}

$recentUsers                = array();
$recentUsers['city']        = array();
$recentUsers['state']       = array();
$recentUsers['country']     = array();
$recentUsers['world']       = array();

if ( !($this->_page->_user->isAuthenticated()) ) {
    if (!isset($this->params["view"]) || !in_array($this->params["view"], array('allcities', 'allstates', 'allcountries'))) {
        $this->params["view"] = 'world';
    }
    $city = $state = $country = "";
    $usersList              = new Warecorp_User_List();
    $recentUsers = $cache->load('Warecorp_User_List__getNewestUsersByLocation__world_page1_limit15');
    if ( !isset($recentUsers['world']) ) {
        $recentUsers['city']        = array();
        $recentUsers['state']       = array();
        $recentUsers['country']     = array();
        $recentUsers['world']       = $usersList->setCurrentPage(1)->setListSize(15)->getNewestUsersByLocation();
        $cache->save($recentUsers, 'Warecorp_User_List__getNewestUsersByLocation__world_page1_limit15', array(), $cfgLifetime->recentUsers);
    }
} else {
    $city       = Warecorp_Location_City::create($this->_page->_user->getCityId());
    $state      = Warecorp_Location_State::create($city->stateId);
    $country    = Warecorp_Location_Country::create($state->countryId);

    $usersList  = new Warecorp_User_List();
    $usersList->setCurrentPage(1)->setListSize(15);

    $recentUsers = $cache->load('Warecorp_User_List__getNewestUsersByLocation__cityid'.$city->id.'_page1_limit15');
    if ( !isset($recentUsers['city']) || !isset($recentUsers['state']) || !isset($recentUsers['country']) || !isset($recentUsers['world']) ) {
        $recentUsers['city']    = $usersList->addWhere('city_id = ?', $city->id)->getNewestUsersByLocation();
        $excludeIds             = array_keys($usersList->returnAsAssoc()->getNewestUsersByLocation());
        $usersList->clearWhere();

        $recentUsers['state']   = $usersList->returnAsAssoc(false)->setExcludeIds($excludeIds)->addWhere('state_id = ?', $state->id)->getNewestUsersByLocation();
        $excludeIds             = array_merge($excludeIds, array_keys($usersList->returnAsAssoc()->getNewestUsersByLocation()));
        $usersList->clearWhere();

        $recentUsers['country'] = $usersList->returnAsAssoc(false)->setExcludeIds($excludeIds)->addWhere('country_id = ?', $country->id)->getNewestUsersByLocation();
        $excludeIds             = array_merge($excludeIds, array_keys($usersList->returnAsAssoc()->getNewestUsersByLocation()));
        $usersList->clearWhere();
        $recentUsers['world']   = $usersList->returnAsAssoc(false)->setExcludeIds($excludeIds)->getNewestUsersByLocation();
        $cache->save($recentUsers, 'Warecorp_User_List__getNewestUsersByLocation__cityid'.$city->id.'_page1_limit15', array(), $cfgLifetime->recentUsers);
    }
}

$userSearch = new Warecorp_User_Search();
$userSearch->setExcludeAndIncludeIds();

$topCities = ""; $topStates = ""; $topCountries = "";
$currentTab = ""; $tags = "";

$allCountries = ""; $allCities = ""; $allStates = ""; $onCol = 0; $CountUsersByStates = ""; $CountUsersByCountries = "";
$CountUsersByCities = "";

$template = 'users/index.tpl';
if (!isset($this->params["view"])) $this->params["view"] = "state";
if ($this->params["view"] == "city"){
    $currentTab = "city";

    if( !$tags = $cache->load('Warecorp_User_Tag_List__getListByLocation__cityid'.$city->id) ) {
        $tags = $tagsListObj->addFilter('city_id', $city->id)->getListByLocation();
        $tags = Warecorp_List_Tags::shuffleTagsArray($tags);
        $cache->save($tags, 'Warecorp_User_Tag_List__getListByLocation__cityid'.$city->id, array(), $cfgLifetime->tags);
    }

    if( !$allCategories = $cache->load('Warecorp_User_Search__getCategoriesListAssoc__countryid__stateid__cityid'.$city->id.'') ) {
        $allCategories = $userSearch->getCategoriesListAssoc(null, null, $city);
        $cache->save($allCategories, 'Warecorp_User_Search__getCategoriesListAssoc__countryid__stateid__cityid'.$city->id.'', array(), $cfgLifetime->categories);
    }
} elseif ($this->params["view"] == "allcities"){
    if (!isset($this->params["state"])) $this->params["state"] = "1";
    $state = Warecorp_Location_State::create(floor($this->params["state"]));
    if (!$state->name) $state = Warecorp_Location_State::create(1);
    $country = Warecorp_Location_Country::create($state->countryId);
    $allCities = $state->getCitiesListAssoc();
    $CountUsersByCities = Warecorp_Location ::getCountUsersByCities($this->params["state"]);
    //$onCol = ceil(count($allCities)/4);
    $onCol = ceil(count($CountUsersByCities)/4);
    $topCities = Warecorp_Location::getUsersTopCitiesList($state->id,null, 12);
    $template = 'users/allcities.tpl';
} elseif ($this->params["view"] == "state"){
    $currentTab = "state";

    if( !$topCities = $cache->load('Warecorp_Location__getUserTopCitiesList__city'.$city->stateId.'__state__limit12') ) {
        $topCities = Warecorp_Location::getUsersTopCitiesList($city->stateId, null, 12);
        $cache->save($topCities, 'Warecorp_Location__getUserTopCitiesList__city'.$city->stateId.'__state__limit12', array(), $cfgLifetime->topCities);
    }

    if( !$tags = $cache->load('Warecorp_User_Tag_List__getListByLocation__stateid'.$city->stateId) ) {
        $tags = $tagsListObj->addFilter('state_id', $city->stateId)->getListByLocation();
        $tags = Warecorp_List_Tags::shuffleTagsArray($tags);
        $cache->save($tags, 'Warecorp_User_Tag_List__getListByLocation__stateid'.$city->stateId, array(), $cfgLifetime->tags);
    }

    if( !$allCategories = $cache->load('Warecorp_User_Search__getCategoriesListAssoc__countryid__stateid'.$state->id.'__cityid') ) {
        $allCategories = $userSearch->getCategoriesListAssoc(null, $state, null);
        $cache->save($allCategories, 'Warecorp_User_Search__getCategoriesListAssoc__countryid__stateid'.$state->id.'__cityid', array(), $cfgLifetime->categories);
    }
} elseif ($this->params["view"] == "allstates"){
    if (!isset($this->params["country"])) $this->params["country"] = "1";
    $country = Warecorp_Location_Country::create(floor($this->params["country"]));
    if (!$country->name) $country = Warecorp_Location_Country::create(1);
    $allStates = $country->getStatesListAssoc();
    $CountUsersByStates = Warecorp_Location ::getCountUsersByStates($this->params["country"]);
    $onCol = ceil(count($allStates)/4);
    //    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb,
    //                                           array($country->name => "/" .$this->_page->Locale. "/users/index/view/allstates/country/" .$country->id. "/"));
    $template = 'users/allstates.tpl';
} elseif ($this->params["view"] == "country"){
    $currentTab = "country";

    if( !$topCities = $cache->load('Warecorp_Location__getUserTopCitiesList__city__state'.$state->countryId.'__limit12') ) {
        $topCities = Warecorp_Location::getUsersTopCitiesList(null, $state->countryId, 12);
        $cache->save($topCities, 'Warecorp_Location__getUserTopCitiesList__city__state'.$state->countryId.'__limit12', array(), $cfgLifetime->topCities);
    }

//    $topStates = Warecorp_Location::getGroupTopStatesList($state->countryId, 12);

    if( !$tags = $cache->load('Warecorp_User_Tag_List__getListByLocation__countryid'.$state->countryId) ) {
        $tags = $tagsListObj->addFilter('country_id', $state->countryId)->getListByLocation();
        $tags = Warecorp_List_Tags::shuffleTagsArray($tags);
        $cache->save($tags, 'Warecorp_User_Tag_List__getListByLocation__countryid'.$state->countryId, array(), $cfgLifetime->tags);
    }

    if( !$allCategories = $cache->load('Warecorp_User_Search__getCategoriesListAssoc__countryid'.$country->id.'__stateid__cityid') ) {
        $allCategories = $userSearch->getCategoriesListAssoc($country, null, null);
        $cache->save($allCategories, 'Warecorp_User_Search__getCategoriesListAssoc__countryid'.$country->id.'__stateid__cityid', array(), $cfgLifetime->categories);
    }
} elseif ($this->params["view"] == "allcountries"){
    $allCountries = Warecorp_Location::getCountriesListAssoc();
    $CountUsersByCountries = Warecorp_Location ::getCountUsersByCountries();
    //$onCol = ceil(count($allCountries)/4);
    $onCol = ceil(count($CountUsersByCountries)/4);
    $template = 'users/allcountries.tpl';
} elseif ($this->params["view"] == "world"){
    $currentTab = "world";

    if( !$topCities = $cache->load('Warecorp_Location__getUserTopCitiesList__city__state__limit12') ) {
        $topCities = Warecorp_Location::getUsersTopCitiesList(null, null, 12);
        $cache->save($topCities, 'Warecorp_Location__getUserTopCitiesList__city__state__limit12', array(), $cfgLifetime->topCities);
    }

    if( !$topCountries = $cache->load('Warecorp_Location__getUserTopCountriesList__limit12') ) {
        $topCountries = Warecorp_Location::getUsersTopCountriesList(12);
        $cache->save($topCountries, 'Warecorp_Location__getUserTopCountriesList__limit12', array(), $cfgLifetime->topCountries);
    }

    if( !$tags = $cache->load('Warecorp_User_Tag_List__getListByLocation__world') ) {
        $tags = $tagsListObj->getListByLocation();
        $tags = Warecorp_List_Tags::shuffleTagsArray($tags);
        $cache->save($tags, 'Warecorp_User_Tag_List__getListByLocation__world', array(), $cfgLifetime->tags);
    }

    if( !$allCategories = $cache->load('Warecorp_User_Search__getCategoriesListAssoc__countryid__stateid__cityid') ) {
        $allCategories = $userSearch->getCategoriesListAssoc();
        $cache->save($allCategories, 'Warecorp_User_Search__getCategoriesListAssoc__countryid__stateid__cityid', array(), $cfgLifetime->categories);
    }

//    $topCities = Warecorp_Location::getUsersTopCitiesList(null, null, 12);
//    $topCountries = Warecorp_Location::getUsersTopCountriesList(12);
//    $tags = $tagsListObj->getListByLocation();
//    $allCategories = $userSearch->getCategoriesListAssoc();
} else {
    $currentTab = "city";
    $tags = $tagsListObj->addFilter('city_id', $this->_page->_user->getCityId())->getListByLocation();
    $tags = Warecorp_List_Tags::shuffleTagsArray($tags);
}

$form = new Warecorp_Form('searchForm','post',$_url.'/search/preset/new/');

$this->_page->breadcrumb = array(Warecorp::t('Home')=>"http://" .BASE_HTTP_HOST.'/'.LOCALE.'/', Warecorp::t('Members')=>"http://" .BASE_HTTP_HOST.'/'.LOCALE.'/users/', Warecorp::t('Browse Members')=>null);
$this->view->form = $form;
$this->view->categories = $allCategories;
$this->view->_url = $_url;
$this->view->city = $city;
$this->view->state = $state;
$this->view->country = $country;
$this->view->currentTab = $currentTab;
$this->view->topCities = $topCities;
$this->view->topStates = $topStates;
$this->view->topCountries = $topCountries;
$this->view->allUserTags = $tags;
$this->view->allCountries = $allCountries;
$this->view->allStates = $allStates;
$this->view->allCities = $allCities;
$this->view->onCol = $onCol;
$this->view->CountUsersByCountries = $CountUsersByCountries;
$this->view->CountUsersByStates = $CountUsersByStates;
$this->view->CountUsersByCities = $CountUsersByCities;
$this->view->recentCityUsers = $recentUsers['city'];
$this->view->recentStateUsers = $recentUsers['state'];
$this->view->recentCountryUsers = $recentUsers['country'];
$this->view->recentWorldUsers = $recentUsers['world'];
$this->view->bodyContent = $template;
$this->view->setLayout('main_wide.tpl');
$this->view->isRightBlockHidden = true;
$this->view->isAuthenticated = $this->_page->_user->isAuthenticated() ;

if (IMPLEMENTATION_TYPE == 'EIA') $this->view->objGlobalGroup = Zend_Registry::get('globalGroup');
