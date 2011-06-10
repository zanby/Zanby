<?
    Warecorp::addTranslation('/modules/groups/action.search.php.xml');

    if (!isset($actionUrl)) {
        $actionUrl = BASE_URL."/".$this->_page->Locale.'/groups/search';
    }

    // если пришла форма POST, формируем красивый url и идём туда

//    print_r($_POST);
//    die;

    //Zend_Debug::dump($this->params); Zend_Debug::dump($_POST);exit;

    /*if (!empty($_POST) && Warecorp::$actionName != 'invitesearch') {
        $addUrl = '';
        $post = $_POST;
        if (array_key_exists('_wf__search_group', $post))  		unset($post['_wf__search_group']);
        if (array_key_exists('_wf__searchForm', $post))    		unset($post['_wf__searchForm']);
        if (array_key_exists('Search', $post))             		unset($post['Search']);
        if (!array_key_exists('preset', $post))            		$post['preset'] = 'new';
        foreach ($post as $key => $value) {
			if ($value) {
                $value = trim(str_replace("/",'',$value));
                $addUrl .= "/$key/".urlencode($value);
            }
        }
        if ($addUrl) {
            $this->_redirect($actionUrl.$addUrl.'/');
        }
    }
    */
    if ( !isset($this->params['preset']) ) {
        foreach ( array_keys($this->params) as $v ) {
            if ( preg_match('/\/'.LOCALE.'\/groups/i', $v) ) {
                $this->_redirect(BASE_URL.$v.'preset/new/');
            }
        }
    }


	$groupSearch          = new Warecorp_Group_Search();
    $groupSearch->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
    $_presets             = array("tag", "category", "country", "state", "city", "new");
    $_orders              = $groupSearch->getOrders();

    // begin of block for invite-search
    if (strcasecmp(Warecorp::$actionName, 'invitesearch') == 0 && $this->currentGroup->getGroupType() == "family") {
        include('promotion/action.invitesearch.php');
    }
    // end of block for invite-search
    elseif (strcasecmp(Warecorp::$actionName, 'search') == 0) {
            $this->params['_url'] = $this->params['_actionUrl'] = $actionUrl;
            $_template            = 'groups/search.tpl';
            $searchForm = new Warecorp_Form('search_group', 'POST',  "/".LOCALE."/groups/search/");
    }

    $this->_page->Xajax->registerUriFunction("search_onchange_country", "/groups/searchOnChangeCountry/");
    $this->_page->Xajax->registerUriFunction("search_onchange_state", "/groups/searchOnChangeState/");

    // Cache settings
    $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
    $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};

    //
    $groupsList = array();
    $size       = 10;
    $count      = 0;

    if (isset($this->params['preset']) && in_array($this->params['preset'], $_presets)) { // new(?) search

        $this->params['_url'] .= '/preset/'.$this->params['preset'];

        $groups = array();
        switch ($this->params['preset']) {
            case 'tag' :  // search by tag
                $_SESSION['group_search'] = array();
                $tag = false;
                if (isset($this->params['id']) && ($tag = new Warecorp_Data_Tag($this->params['id']))) {
                    $tag = $tag->name;
                    $this->params['_url'] .= '/id/'.$this->params['id'];
                }
                elseif (isset($this->params['tname']) && (Warecorp_Data_Tag::isTagExists('name', $this->params['tname']))) {
                    $tag = $this->params['tname'];
                    $this->params['_url'] .= '/tname/'.$this->params['tname'];
                }

                if ($tag) {
                    $groupSearch->setKeywords($tag);
                    $this->params["keywords"] = $tag;
                    $groupSearch->setDefaultOrder($this->params);
                    $groups = $groupSearch->searchByCriterions($this->params);
                }

                $_SESSION['group_search']['keywords'] = isset($this->params['keywords']) ? $this->params['keywords'] : null;
                break;
            case 'category' : // search by category
                if (isset($this->params['id'])) {
                    $this->params['_url'] .= '/id/'.(int)$this->params['id'];

                    $s = &$_SESSION['group_search'];
                    if (!empty($this->params['city'])) {
                        $s = array();
                        $City  = Warecorp_Location_City::create((int)$this->params['city']);
                        $State = Warecorp_Location_State::create($City->stateId);
                        $s['country']   = !empty($State->countryId)     ? $State->countryId     : "0";
                        $s['state']     = !empty($State->id)            ? $State->id            : "0";
                        $s['city']      = !empty($City->id)             ? $City->id             : "0";
                        //$this->params['_url'] .= !empty($State->countryId) ? '/country/'.$State->countryId  : "";
                        //$this->params['_url'] .= !empty($State->id)        ? '/state/'.$State->id           : "";
                        $this->params['_url'] .= !empty($City->id)         ? '/city/'.$City->id             : "";
                    } elseif (!empty($this->params['state'])) {
                        $s = array();
                        $State = Warecorp_Location_State::create((int)$this->params['state']);
                        $s['country']   = !empty($State->countryId)     ? $State->countryId     : "0";
                        $s['state']     = !empty($State->id)            ? $State->id            : "0";
                        $s['city']      = "0";
                        //$this->params['_url'] .= !empty($State->countryId) ? '/country/'.$State->countryId  : "";
                        $this->params['_url'] .= !empty($State->id)        ? '/state/'.$State->id           : "";
                    } elseif (!empty($this->params['country'])) {
                        $s = array();
                        $Country = Warecorp_Location_Country::create((int)$this->params['country']);
                        $s['country']   = !empty($Country->id)     ?$Country->id     : "0";
                        $s['state']     = "0";
                        $s['city']      = "0";
                        $this->params['_url'] .= !empty($Country->id) ? '/country/'.$Country->id  : "";
                    } elseif (!empty($this->params['world'])) {
                    	$s = array();
                        $s['country']   = "0";
                        $s['state']     = "0";
                        $s['city']      = "0";
                    }

                    $s['keywords']  = (isset($s['keywords'])) ? $s['keywords'] : "";
                    $groupSearch->setKeywords($s['keywords']);
                    $this->params['keywords'] = (is_array($groupSearch->keywords) && count($groupSearch->keywords)) ? implode(' ', $groupSearch->keywords) : "";
                    $s['keywords'] = $this->params['keywords'];
                    $s['category'] = (int)$this->params['id'];

                    $groupSearch->setZipCodes();
                    $groupSearch->setDefaultOrder($s);

                    if ($groupSearch->getZipCodes()) {
                        //$groupSearch->searchByZipCodes();
						$s['zipcode'] = array_map(create_function('$elem', 'return $elem["zipcode"];'), $groupSearch->getZipCodes());
                        //$groupSearch->searchByCriterions($s);
                        $groups = $groupSearch->searchByCriterions($s);
                        //$groups = $groupSearch->getIntersection();
                    } else {
                        $groups = $groupSearch->searchByCriterions($s);
                    }
                    $this->params = array_merge($this->params, $s);
                    /*
                    $s['category'] = (int)$this->params['id'];
                    $groupSearch->setDefaultOrder($s);
                    $groups = $groupSearch->searchByCriterions($s);
                    $this->params = array_merge($this->params, $s);
                    */
                }
                break;
            case 'country' : // search by country
                $_SESSION['group_search'] = array();
                $s = & $_SESSION['group_search'];
                if (isset($this->params['id'])) {
                    $Country = Warecorp_Location_Country::create((int)$this->params['id']);
                    $s['country']   = $Country->id;
                    $this->params['_url'] .= '/id/'.$this->params['id'];
                    $groupSearch->setDefaultOrder($s);
                    $groups = $groupSearch->searchByCriterions($s);
                }
                break;
            case 'state' : // search by state
                $_SESSION['group_search'] = array();
                $s = &$_SESSION['group_search'];
                if (isset($this->params['id'])) {
                    $State = Warecorp_Location_State::create((int)$this->params['id']);
                    $s['state']     = $State->id;
                    $s['country']   = $State->countryId;
                    $this->params['_url'] .= '/id/'.$this->params['id'];
                    $groupSearch->setDefaultOrder($s);
                    $groups = $groupSearch->searchByCriterions($s);
                }
                break;
            case 'city' : // search by city
                $_SESSION['group_search'] = array();
                $s = &$_SESSION['group_search'];
                if (isset($this->params['id'])) {
                    $City = Warecorp_Location_City::create((int)$this->params['id']);
                    $State = Warecorp_Location_State::create($City->stateId);
                    $s['city']      = $City->id;
                    $s['state']     = $State->id;
                    $s['country']   = $State->countryId;
                    $this->params['_url'] .= '/id/'.$this->params['id'];
                    $groupSearch->setDefaultOrder($s);
                    $groups = $groupSearch->searchByCriterions($s);
                    $s['title']     = ($City->id) ? (Warecorp::t("Groups and group categories near ").$City->name) : "";
                }
                break;
            case 'new' : // new search
                $_SESSION['group_search'] = array();

                /** Clean params from empty values **/
                if ( isset($this->params['keywords']) && !trim($this->params['keywords']) )
                    $this->params['keywords'] = '';
                if ( isset($this->params['category']) ) {
                    $this->params['category'] = (int)trim($this->params['category']);
                    if ( empty($this->params['category']) ) unset($this->params['category']);
                }
                if ( isset($this->params['country']) ) {
                    $this->params['country'] = (int)trim($this->params['country']);
                    if ( empty($this->params['country']) ) unset($this->params['country']);
                }
                if ( isset($this->params['state']) ) {
                    $this->params['state'] = (int)trim($this->params['state']);
                    if ( empty($this->params['state']) ) unset($this->params['state']);
                }
                if ( isset($this->params['city']) ) {
                    $this->params['city'] = (int)trim($this->params['city']);
                    if ( empty($this->params['city']) ) unset($this->params['city']);
                }
                if ( isset($this->params['page']) ) {
                    $this->params['page'] = (int)trim($this->params['page']);
                    if ( empty($this->params['page']) ) unset($this->params['page']);
                }
                if ( isset($this->params['order']) ) {
                    $this->params['order'] = trim($this->params['order']);
                    if ( empty($this->params['order']) ) unset($this->params['order']);
                }
                if ( isset($this->params['filter']) ) {
                    $this->params['filter'] = trim($this->params['filter']);
                    if ( empty($this->params['filter']) ) unset($this->params['filter']);
                }
                /** Clean params from empty values **/

                $this->params['keywords'] = isset($this->params['keywords']) ? trim($this->params['keywords']) : "";
                if ($this->params['keywords']) {
                    $this->params['_url'] .= '/keywords/'.$this->params['keywords'];
                }

                $groupSearch->setKeywords($this->params['keywords']);
                $this->params['keywords'] = (is_array($groupSearch->keywords) && count($groupSearch->keywords)) ? implode(' ', $groupSearch->keywords) : "";

                $s = &$_SESSION['group_search'];
                $s['keywords']  = $this->params['keywords'];
                $s['category']  = isset($this->params['category'])  ? $this->params['category']     : "0";

                /*
                if (isset($this->params['city'])) {
                    $s['city'] = $this->params['city'];
                } else {
                    $s['country']   = isset($this->params['country'])   ? $this->params['country']      : "0";
                    $s['state']     = isset($this->params['state'])     ? $this->params['state']        : "0";
                }
                */

                $s['country']   = isset($this->params['country'])   ? $this->params['country']      : "0";
                $s['state']     = isset($this->params['state'])     ? $this->params['state']        : "0";
                $s['city']      = isset($this->params['city'])      ? $this->params['city']         : "0";


                if (isset($this->params['country']))  $this->params['_url'] .= '/country/'  .$this->params['country'];
                if (isset($this->params['state']))    $this->params['_url'] .= '/state/'    .$this->params['state'];
                if (isset($this->params['city']))     $this->params['_url'] .= '/city/'     .$this->params['city'];
                if (isset($this->params['category'])) $this->params['_url'] .= '/category/' .$this->params['category'];

                $groupSearch->setZipCodes();
                $groupSearch->setDefaultOrder($s);

                if ($groupSearch->getZipCodes()) {

                    //$groupSearch->searchByZipCodes();
					$s['zipcode'] = array_map(create_function('$elem', 'return $elem["zipcode"];'), $groupSearch->getZipCodes());
                    //$groupSearch->searchByCriterions($s);
                    //$groups = $groupSearch->getIntersection();
                    $groups = $groupSearch->searchByCriterions($s);
                } else {
                    $groups = $groupSearch->searchByCriterions($s);
                }
            default:
                break;
        }
        $cache->save($groups, 'search_groups_'.session_id(), array(), 7200);

        /*
        if ($groupSearch->paramsOrder !== null) {
            $this->params['order'] = $groupSearch->paramsOrder['order'];
            $this->params['direction'] = $groupSearch->paramsOrder['direction'];
        }
        */
        $count = count($groups);

        $this->params['direction']  = empty($this->params['direction']) ? "DESC"  : $this->params['direction'];
        $this->params['page']       = (isset($this->params['page']) && $this->params['page']>0) ? (int)$this->params['page'] : 1;

        if (isset($this->params['order'])) {
            $groupsList = $groupSearch->setIncludeIds(array_keys($groups))->getOrdered($this->params, $size);
            $count = $groupSearch->getCount($this->params);
        } else {
            $groupsList = array_slice($groups, ($this->params['page']-1)*$size, $size, true);
            $groupsList = array_keys($groupsList);
        }

        $this->params['order']      = isset($this->params['order']) && isset($_orders[$this->params['order']]) ? trim($this->params['order']) : "";


    } /*else { // not new search

    	$groups = $cache->load('search_groups_'.session_id());
        if (!is_array($groups)) { //
           $this->_redirect(BASE_URL."/".$this->_page->Locale."/groups/");
        }

        $cache->save($groups, 'search_groups_'.session_id(), array(), 7200);

        $this->params               = array_merge($this->params, $_SESSION['group_search']);
        $this->params['order']      = isset($this->params['order']) && isset($_orders[$this->params['order']]) ? trim($this->params['order']) : "";
        $this->params['direction']  = empty($this->params['direction']) ? "DESC"  : $this->params['direction'];
        $this->params['page']       = (isset($this->params['page']) && $this->params['page']>0) ? (int)$this->params['page'] : 1;
        $this->params['filter']     = (!empty($this->params['filter'])) ? (int)$this->params['filter'] : 0;

        if (empty($this->params['order'])) {
            $filtered = $groupSearch->setIncludeIds(array_keys($groups))->getFiltered($this->params);
            $groupsList = array_intersect_key($groups, $filtered);
            $groupsList = array_slice($groupsList, ($this->params['page']-1)*$size, $size, true);
            $groupsList = array_keys($groupsList);
            $count = count($filtered);
        } else {
            $groupsList = $groupSearch->setIncludeIds(array_keys($groups))->getOrdered($this->params, $size);
            $count = $groupSearch->getCount($this->params);
        }
    }
    */

    foreach ($groupsList as &$group) {
        $group = new Warecorp_Group_Simple('id', $group);
    }


    $this->params['country']  = !empty($this->params['country']) ? $this->params['country'] : (isset($_SESSION['group_search']['country']) && $_SESSION['group_search']['country']>0 ? (int)$_SESSION['group_search']['country'] : 0);
    $this->params['state']    = !empty($this->params['state']) ? $this->params['state'] : (isset($_SESSION['group_search']['state']) && $_SESSION['group_search']['state']>0 ? (int)$_SESSION['group_search']['state'] : 0);
    $this->params['city']     = !empty($this->params['city']) ? $this->params['city'] : (isset($_SESSION['group_search']['city']) && $_SESSION['group_search']['city']>0 ? (int)$_SESSION['group_search']['city'] : 0);

    $country    = Warecorp_Location_Country::create($this->params['country']);
    $state      = Warecorp_Location_State::create($this->params['state']);
    $city       = Warecorp_Location_City::create($this->params['city']);

    $countries  = array("0"=>Warecorp::t("All Countries")) + Warecorp_Location::getCountriesListAssoc();
    $states     = array("0"=>Warecorp::t("All States")) + $country->getStatesListAssoc($this->params['country']);
    $cities     = array("0"=>Warecorp::t("All Cities")) + $state->getCitiesListAssoc($this->params['state']);

    if (isset($groups) && $groups) {
        $categories = array("0"=>Warecorp::t("All Categories")) + $groupSearch->setIncludeIds(array_keys($groups))->getCategoriesListAssoc($country, $state, $city);
    }else{
        $categories = array();
    }

    $allCategoriesObj = new Warecorp_Group_Category_List();
    $allCategories = array("0"=>Warecorp::t("All Categories")) + $allCategoriesObj->returnAsAssoc()->getList();

    $this->params['page']       = isset($this->params['page']) ? (int)$this->params['page'] : 1;
    $P = new Warecorp_Common_PagingProduct($count, $size, $groupSearch->getPagerLink($this->params));
    $paging = $P->makePaging($this->params['page']);

    $this->_page->setTitle(Warecorp::t('Groups'));
    $this->view->assign($this->params);

    //breadcrumb
    $this->_page->breadcrumb[Warecorp::t('World')] = null;
    if (!empty($country->id)) {
        $this->_page->breadcrumb[Warecorp::t('World')] = $this->params['_url']."/preset/new/";
        $this->_page->breadcrumb[$country->name]=null;
    }
    if (!empty($state->id)) {
        $this->_page->breadcrumb[$country->name]=$this->params['_url']."/preset/country/id/{$country->id}/";
        $this->_page->breadcrumb[$state->name]=null;
    }
    if (!empty($city->id)) {
        $this->_page->breadcrumb[$state->name]=$this->params['_url']."/preset/state/id/{$state->id}/";
        $this->_page->breadcrumb[$city->name]=null;
    }
    $this->_page->hideBreadcrumb = false;
    // end breadcrumb

  $tags = array();


    // top tags
    $tagsListObj = new Warecorp_Group_Tag_List(null);
    $tagsListObj->returnAsAssoc()->setListSize(30)->setCurrentPage(1)->setOrder('rating DESC');
    $tagsListObj->addWhere('group_type IN (?)', array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE));

    if (!empty($city->id)) {
        if( !$tags = $cache->load('Warecorp_Group_Tag_List__getListByLocation__cityid'.$city->id) ) {
            $tags = $tagsListObj->addFilter('city_id', $city->id)->getListByLocation();
            $cache->save($tags, 'Warecorp_Group_Tag_List__getListByLocation__cityid'.$city->id, array(), $cfgLifetime->tags);
        }
    } elseif (!empty($state->id)) {
        if( !$tags = $cache->load('Warecorp_Group_Tag_List__getListByLocation__stateid'.$state->id) ) {
            $tags = $tagsListObj->addFilter('state_id', $state->id)->getListByLocation();
            $cache->save($tags, 'Warecorp_Group_Tag_List__getListByLocation__stateid'.$state->id, array(), $cfgLifetime->tags);
        }
    } elseif (!empty($country->id)) {
        if( !$tags = $cache->load('Warecorp_Group_Tag_List__getListByLocation__countryid'.$country->id) ) {
            $tags = $tagsListObj->addFilter('country_id', $country->id)->getListByLocation();
            $cache->save($tags, 'Warecorp_Group_Tag_List__getListByLocation__countryid'.$country->id, array(), $cfgLifetime->tags);
        }
    } else {
        if( !$tags = $cache->load('Warecorp_Group_Tag_List__getListByLocation__world') ) {
            $tags = $tagsListObj->getListByLocation();
            $cache->save($tags, 'Warecorp_Group_Tag_List__getListByLocation__world', array(), $cfgLifetime->tags);
        }
    }
    // end top tags



    // shuffle tags
    $shuffledTags = array();
    while (is_array($tags) && count($tags) > 0) {
        $val = array_rand($tags);
        $shuffledTags[$val] = $tags[$val];
        unset($tags[$val]);
    }

    $rssUrl = "";
    $params = !empty($_SESSION['group_search']) ? array_merge($_SESSION['group_search'], $this->params)  : $this->params;
    foreach ($params as $key=>&$val) {
        if (in_array($key,array('keywords','country','state','city','category')) && !empty($val)) {
            $rssUrl .= "{$key}/{$val}/";
        }
    }
    $rssUrl = BASE_URL."/rss/groups/search/preset/new/".$rssUrl;

    if(LOCALE == "rss"){
        include_once(ENGINE_DIR."/rss.class.php");

        $rss = new UniversalFeedCreator();
        $rss->encoding = 'utf-8';
        $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';
        $rss->title = SITE_NAME_AS_STRING." groups search results";
        $rss->description = (!empty($this->params["keywords"])) ? Warecorp::t(SITE_NAME_AS_STRING." groups found for: ") . $this->params["keywords"] : "";
        $rss->link = $rssUrl;
        $rss->copyright = COPYRIGHT;
        foreach($groupsList as $key=>&$group){
            $item = new FeedItem();
            $item->title = $group->getName();
            $item->link = str_replace('/rss/','/en/', $group->getGroupPath('summary'));
            $item->description = Warecorp::t("Description").": ".$group->getDescription().'<br/>';
            $item->description .= Warecorp::t("Category").": ".$group->getCategory()->getName().'<br/>';
            $item->description .= Warecorp::t("Host").": ".$group->getHost()->getLogin().'<br/>';
            $item->description .= Warecorp::t("Location").": ".$group->getCity()->name.', '.$group->getState()->name.', '.$group->getCountry()->name.'<br/>';
            $item->description .= Warecorp::t("Founded").": ".$group->getCreateDate().'<br/>';
            $item->description .= Warecorp::t("Members").": ".$group->getMembers()->getCount().'<br/>';
            switch ($group->getJoinMode()) {
                case 0: $membership = 'anyone'; break;
                case 1: $membership = 'request'; break;
                case 2: $membership = 'code'; break;
                default: $membership = 'anyone'; break;
            }
            $item->description .= Warecorp::t("Membership").": ".$membership.'<br/>';
            $rss->addItem($item);
        }
        header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
        print $rss->createFeed("RSS2.0");
        exit;
    }

    $urlParts = explode('/',$this->params['_url']);
    $location = array("city", "state", "country");
    foreach ($urlParts as $key => $value) {
        if (in_array($value,$location)) {
            unset($urlParts[$key]);
            unset($urlParts[$key+1]);
        }
    }
    $urlWithoutLocation = implode('/',$urlParts);

    $this->view->bodyContent         = $_template;
    $this->view->form                = $searchForm;
    $this->view->categories          = $categories;
    $this->view->allCategories       = $allCategories;
    $this->view->countries           = $countries;
    $this->view->states              = $states;
    $this->view->cities              = $cities;
    $this->view->groupsList          = $groupsList;
    $this->view->_urlWithoutLocation = $urlWithoutLocation;
    $this->view->paging              = $paging;
    $this->view->topTags             = $shuffledTags;
    $this->view->searchTitle         = isset($_SESSION['group_search']['title']) ? $_SESSION['group_search']['title'] : "";
    $this->view->rssUrl              = $rssUrl;

    $this->view->setLayout('main_wide.tpl');

    if ( Warecorp::$actionName === 'invitesearch' ) {
        $this->view->setLayout('main.tpl');
    }
