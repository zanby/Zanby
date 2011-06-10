<?php
    Warecorp::addTranslation('/modules/users/action.search.php.xml');

    $userSearch = new Warecorp_User_Search();
    $userSearch->setExcludeAndIncludeIds();
    $_presets = array( Warecorp::t("tag") ,
                       Warecorp::t("category"),
                       Warecorp::t("country"),
                       Warecorp::t("state"),
                       Warecorp::t("city"),
                       Warecorp::t("mytags"),
                       Warecorp::t("friendstag"),
                       Warecorp::t("new")
                      );
    $_url = BASE_URL."/".LOCALE."/users/search";
    $_orders = $userSearch->getOrders();

    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");
    $this->_page->Xajax->registerUriFunction("search_onchange_country", "/users/searchOnChangeCountry/");
    $this->_page->Xajax->registerUriFunction("search_onchange_state", "/users/searchOnChangeState/");

    // Cache settings
    $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
    $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};

    $usersList      = array();
    $size           = 10;
    $count          = 0;


    if (isset($this->params['preset']) && in_array($this->params['preset'], $_presets)) { // new search
        $_SESSION['user_search'] = array();
        $s = &$_SESSION['user_search'];
        $users = array();
        switch ($this->params['preset']) {
            case 'tag' :  // search by tag
                $tag = false;
                if (isset($this->params['id']) && ($tag = new Warecorp_Data_Tag($this->params['id']))) {
                    $tag = $tag->name;
                } elseif (isset($this->params['tname']) && (Warecorp_Data_Tag::isTagExists('name', $this->params['tname']))) {
                    $tag = $this->params['tname'];
                }
                if ($tag) {
                    $userSearch->setKeywords($tag);
                    $this->params["keywords"] = $tag;
                    $userSearch->setDefaultOrder($this->params);
                    $users = $userSearch->searchByCriterions($this->params);
                }
                break;
            case 'category' : // search by category
                if (isset($this->params['id'])) {
                    if (!empty($this->params['city'])) {
                        $s = array();
                        $City  = Warecorp_Location_City::create((int)$this->params['city']);
                        $State = Warecorp_Location_State::create($City->stateId);
                        $s['country']   = !empty($State->countryId)     ? $State->countryId     : "0";
                        $s['state']     = !empty($State->id)            ? $State->id            : "0";
                        $s['city']      = !empty($City->id)             ? $City->id             : "0";
                    } elseif (!empty($this->params['state'])) {
                        $s = array();
                        $State = Warecorp_Location_State::create((int)$this->params['state']);
                        $s['country']   = !empty($State->countryId)     ? $State->countryId     : "0";
                        $s['state']     = !empty($State->id)            ? $State->id            : "0";
                        $s['city']      = "0";
                    } elseif (!empty($this->params['country'])) {
                        $s = array();
                        $Country = Warecorp_Location_Country::create((int)$this->params['country']);
                        $s['country']   = !empty($Country->id)     ?$Country->id     : "0";
                        $s['state']     = "0";
                        $s['city']      = "0";
                    } elseif (!empty($this->params['world'])) {
                        $s = array();
                    }
                    $s['category'] = (int)$this->params['id'];
                    $userSearch->setDefaultOrder($s);
                    $users = $userSearch->searchByCriterions($s);
                    $this->params = array_merge($this->params, $s);
                }
                break;
            case 'country' : // search by country
                if (isset($this->params['id'])) {
                    $Country = Warecorp_Location_Country::create((int)$this->params['id']);
                    $s['country']   = $Country->id;
                    $userSearch->setDefaultOrder($s);
                    $users = $userSearch->searchByCriterions($s);
                }
                break;
            case 'state' : // search by state
                if (isset($this->params['id'])) {
                    $State = Warecorp_Location_State::create((int)$this->params['id']);
                    $s = & $_SESSION['user_search'];
                    $s['state']     = $State->id;
                    $s['country']   = $State->countryId;
                    $userSearch->setDefaultOrder($s);
                    $users = $userSearch->searchByCriterions($s);
                }
                break;
            case 'city' : // search by city
                if (isset($this->params['id'])) {
                    $City = Warecorp_Location_City::create((int)$this->params['id']);
                    $State = Warecorp_Location_State::create($City->stateId);
                    $s['title']     = ($City->id) ? (Warecorp::t("Members near %s", $City->name)) : "";
                    $s['city']      = $City->id;
                    $s['state']     = $State->id;
                    $s['country']   = $State->countryId;
                    $userSearch->setDefaultOrder($s);
                    $users = $userSearch->searchByCriterions($s);
                }
                break;
            case 'mytags' : // search by user tag
                if ($this->_page->_user->getId()) {
                    $users = $userSearch->searchByUserTags($this->_page->_user);
                }
                break;
            case 'friendstag' : // search by friends tag
                if ($this->_page->_user->getId()) {
                    $users = $userSearch->searchByFriendsTags($this->_page->_user);
                }
                break;
            case 'new' : // new search
                $this->params['keywords'] = isset($this->params['keywords']) ? trim($this->params['keywords']) : "";

                $userSearch->setKeywords($this->params['keywords']);
                $this->params['keywords'] = (is_array($userSearch->keywords) && count($userSearch->keywords)) ? implode(' ', $userSearch->keywords) : "";

                $s = &$_SESSION['user_search'];
                $s['keywords']  = $this->params['keywords'];
                $s['gender']    = isset($this->params['gender'])    ? $this->params['gender']       : "0";
                $s['age_from']  = isset($this->params['age_from'])  ? $this->params['age_from']     : "0";
                $s['age_to']    = isset($this->params['age_to'])    ? $this->params['age_to']       : "0";
                $s['country']   = isset($this->params['country'])   ? $this->params['country']      : "0";
                $s['state']     = isset($this->params['state'])     ? $this->params['state']        : "0";
                $s['city']      = isset($this->params['city'])      ? $this->params['city']         : "0";
                $s['category']  = isset($this->params['category'])  ? $this->params['category']     : "0";
                $s['photo_only']= isset($this->params['photo_only'])? $this->params['photo_only']   : "";

                $userSearch->setZipCodes();
                $userSearch->setDefaultOrder($s);

                if ($userSearch->getZipCodes()) {
                    $userSearch->searchByZipCodes();
                    $userSearch->searchByCriterions($s, true);
                    $users = $userSearch->getIntersection();
                } else {
                    $users = $userSearch->searchByCriterions($s);
                }
                break;
            default:
                break;
        }
        if ($userSearch->paramsOrder !== null) {
            $this->params['order'] = $userSearch->paramsOrder['order'];
            $this->params['direction'] = $userSearch->paramsOrder['direction'];
        }

        $cache->save($users, 'search_users_'.session_id(), array(), 7200);

        $usersList = array_slice($users, 0, $size, true);
        $usersList = array_keys($usersList);
        $count = count($users);
    } else { // not new search
        if (!($users = $cache->load('search_users_'.session_id()))) { //
            $this->_redirect(BASE_URL."/".$this->_page->Locale."/users/");
        }

        $cache->save($users, 'search_users_'.session_id(), array(), 7200); //komarovski

        if (isset($_SESSION['user_search'])) $this->params = array_merge($this->params, $_SESSION['user_search']);
        $this->params['order']      = isset($this->params['order']) && isset($_orders[$this->params['order']]) ? trim($this->params['order']) : "";
        $this->params['direction']  = empty($this->params['direction']) ? "DESC"  : $this->params['direction'];
        $this->params['page']       = (isset($this->params['page']) && $this->params['page']>0) ? (int)$this->params['page'] : 1;

        if (empty($this->params['order'])) {
            $usersList = array_slice($users, ($this->params['page']-1)*$size, $size, true);
            $usersList = array_keys($usersList);
            $count = count($users);
        } else {
            $usersList = $userSearch->getOrdered($this->params, $users, $size);
            $count = count($users);
        }

    }

    foreach ($usersList as &$user) {
        $user = new Warecorp_User('id', $user);
        $user->lastOn = $user->getLastOnline();
    }

    $s = &$_SESSION['user_search'];
    $this->params['country']  = !empty($this->params['country']) ? $this->params['country'] : (isset($s['country']) && $s['country']>0 ? (int)$s['country'] : 0);
    $this->params['state']    = !empty($this->params['state']) ? $this->params['state'] : (isset($s['state']) && $s['state']>0 ? (int)$s['state'] : 0);
    $this->params['city']     = !empty($this->params['city']) ? $this->params['city'] : (isset($s['city']) && $s['city']>0 ? (int)$s['city'] : 0);

    $country    = Warecorp_Location_Country::create($this->params['country']);
    $state      = Warecorp_Location_State::create($this->params['state']);
    $city       = Warecorp_Location_City::create($this->params['city']);

    $countries  = array("0"=>Warecorp::t("All Countries")) + Warecorp_Location::getCountriesListAssoc();
    $states     = array("0"=>Warecorp::t("All States")) + $country->getStatesListAssoc($this->params['country']);
    $cities     = array("0"=>Warecorp::t("All Cities")) + $state->getCitiesListAssoc($this->params['state']);

    $allCategoriesObj = new Warecorp_Group_Category_List();
    $categories = array("0"=>Warecorp::t("All Categories")) + $allCategoriesObj->returnAsAssoc()->getList();

    $this->params['page'] = isset($this->params['page']) ? (int)$this->params['page'] : 1;
    $P = new Warecorp_Common_PagingProduct($count, $size, $userSearch->getPagerLink($this->params));
    $paging = $P->makePaging($this->params['page']);

    // breadcrumb
    if (empty($this->_page->breadcrumb)) {
        $this->_page->breadcrumb['Members'] = BASE_URL."/".$this->_page->Locale."/users/";
    }
    $this->_page->breadcrumb['World'] = null;
    if (!empty($country->id)) {
        $this->_page->breadcrumb['World'] = $_url."/preset/new/";
        $this->_page->breadcrumb[$country->name]=null;
    }
    if (!empty($state->id)) {
        $this->_page->breadcrumb[$country->name]=$_url."/preset/country/id/{$country->id}/";
        $this->_page->breadcrumb[$state->name]=null;
    }
    if (!empty($city->id)) {
        $this->_page->breadcrumb[$state->name]=$_url."/preset/state/id/{$state->id}/";
        $this->_page->breadcrumb[$city->name]=null;
    }
    // end breadcrumb
    // hide breadcrumb
    //$this->_page->hideBreadcrumb = true;

    // top tags
    $tagsListObj = new Warecorp_User_Tag_List();
    $tagsListObj->returnAsAssoc()->setListSize(30)->setCurrentPage(1)->setOrder('rating DESC');

    if (!empty($city->id)) {
        if( !$tags = $cache->load('Warecorp_User_Tag_List__getListByLocation__cityid'.$city->id) ) {
            $tags = $tagsListObj->addFilter('city_id', $city->id)->getListByLocation();
            $cache->save($tags, 'Warecorp_User_Tag_List__getListByLocation__cityid'.$city->id, array(), $cfgLifetime->tags);
        }
    } elseif (!empty($state->id)) {
        if( !$tags = $cache->load('Warecorp_User_Tag_List__getListByLocation__stateid'.$state->id) ) {
            $tags = $tagsListObj->addFilter('state_id', $state->id)->getListByLocation();
            $cache->save($tags, 'Warecorp_User_Tag_List__getListByLocation__stateid'.$state->id, array(), $cfgLifetime->tags);
        }
    } elseif (!empty($country->id)) {
        if( !$tags = $cache->load('Warecorp_User_Tag_List__getListByLocation__countryid'.$country->id) ) {
            $tags = $tagsListObj->addFilter('country_id', $country->id)->getListByLocation();
            $cache->save($tags, 'Warecorp_User_Tag_List__getListByLocation__countryid'.$country->id, array(), $cfgLifetime->tags);
        }
    } else {
        if( !$tags = $cache->load('Warecorp_User_Tag_List__getListByLocation__world') ) {
            $tags = $tagsListObj->getListByLocation();
            $cache->save($tags, 'Warecorp_User_Tag_List__getListByLocation__world', array(), $cfgLifetime->tags);
        }
    }
    // end top tags

    // RSS
    $rssUrl = "";
    $params = !empty($_SESSION['user_search']) ? array_merge($_SESSION['user_search'], $this->params)  : $this->params;
    foreach ($params as $key=>&$val) {
        if (in_array($key,array('keywords','gender','age_from','age_to','country','state','city','category')) && !empty($val)) {
            $rssUrl .= "{$key}/{$val}/";
        }
    }
    $rssUrl = BASE_URL."/rss/users/search/preset/new/".$rssUrl;
    if(LOCALE == "rss"){
        include_once(ENGINE_DIR."/rss.class.php");
        $rss = new UniversalFeedCreator();
        $rss->encoding = 'utf-8';
        $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';
         $rss->link = $rssUrl;
         $rss->title = Warecorp::t(SITE_NAME_AS_STRING." members search results");
         $rss->description = (!empty($this->params["keywords"])) ? Warecorp::t(SITE_NAME_AS_STRING." members found for: %s", $this->params["keywords"]) : "";
         $rss->copyright = COPYRIGHT;
         foreach ($usersList as $user){
             $item = new FeedItem();
             $item->title = $user->getLogin();
             $item->link  = str_replace('/rss/','/en/',$user->getUserPath('profile')); //@TODO !!! bad decision (str_replace)
             $item->description = Warecorp::t("Real name: %s %s", array($user->getFirstname(), $user->getLastname())) ."<br/>";
            $item->description .= Warecorp::t("Gender: %s", $user->getGender()) . "<br/>";
            $item->description .= Warecorp::t("Age: %s", $user->getAge())."<br/>";
            $item->description .= Warecorp::t("Location: %s", $user->getCity()->name);
             $item->description .= " ". $user->getState()->name ;
             $item->description .= " " . $user->getCountry()->name . "<br/>";
             $item->description .= Warecorp::t("Date joined: %s", date("d.m.y", strtotime($user->getRegisterDate()))) . "<br />";
             $item->description .= Warecorp::t("Tags: %s", $user->getTagHeadline()) . "<br/>";
             $item->description .= Warecorp::t("Groups: %s", implode(', ', $user->getGroups()->returnAsAssoc()->getList()));
             $rss->addItem($item);
         }

         header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
         print $rss->createFeed("RSS2.0");
         exit;
    }
    // END of RSS

    // shuffle tags
    $shuffledTags = array();
    while (is_array($tags) && count($tags) > 0) {
        $val = array_rand($tags);
        $shuffledTags[$val] = $tags[$val];
        unset($tags[$val]);
    }

    $form = new Warecorp_Form('search_user', 'POST', "/".LOCALE."/users/search/" );

    $this->view->assign($this->params);
    $this->view->_url          = $_url;
    $this->view->bodyContent   = 'users/search.tpl';
    $this->view->form          = $form;
    $this->view->countries     = $countries;
    $this->view->states        = $states;
    $this->view->cities        = $cities;
    $this->view->categories    = $categories;
    $this->view->usersList     = $usersList;
    $this->view->topTags       = $shuffledTags;
    $this->view->searchTitle   = isset($_SESSION['user_search']['title']) ? $_SESSION['user_search']['title'] : "";
    $this->view->paging        = $paging;
    $this->view->City          = isset($City->id) ? $City : "";
    $this->view->ageFrom       = array('0'=>'--')+array_combine(range(13,100), range(13,100)) + array(999 => '>100');
    $this->view->ageTo         = array('0'=>'--')+array_combine(range(13,100), range(13,100)) + array(999 => '>100');
    $this->view->friends       = $this->_page->_user->getId() ?  $this->_page->_user->getFriendsList()->returnAsAssoc()->getList() : array();
    $this->view->rssUrl        = $rssUrl;
    $this->view->setLayout('main_wide.tpl');
