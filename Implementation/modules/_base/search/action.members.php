<?php
    Warecorp::addTranslation('/modules/search/action.members.php.xml');

    $this->params['keywords'] = isset($this->params['keywords']) ? trim($this->params['keywords']) : "";

    if ( $this->params['keywords'] == '' ) {
        $this->view->bodyContent = 'search/empty.keywords.tpl';
    }
    else {
        $userSearch = new Warecorp_User_Search();
        $userSearch->setExcludeAndIncludeIds();
        $_presets = array("tag", "category", "country", "state", "city", "mytags", "friendstag", "new");
        $_url = BASE_URL."/".LOCALE."/search/members";
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
                case 'new' : // new search
                    $this->params['keywords'] = isset($this->params['keywords']) ? trim($this->params['keywords']) : "";

                    $keywordParser = new Warecorp_Search_KeywordsParser($this->params['keywords'], 'members');
                    $parsedArray = $keywordParser->parseKeywordsToArray();

                    $this->params['keywords'] = $keywordParser->getOriginalKeywords();

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


                    if (Warecorp_Search_KeywordsParser::isSearchAvailable('users', $parsedArray)) {
                        $userSearch->setKeywords($parsedArray['keywords']);
                        $userSearch->setZipCodes();
                        $userSearch->setDefaultOrder($parsedArray);

                        if ($userSearch->getZipCodes()) {
                            $userSearch->searchByZipCodes();
                            $userSearch->searchByCriterions($parsedArray, true);
                            $users = $userSearch->getIntersection();
                        } else {
                            $users = $userSearch->searchByCriterions($parsedArray);
                        }
                    }else{
                        $users = array();
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
                $this->_redirect(BASE_URL."/".$this->_page->Locale."/search/members/preset/new/");
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

        $this->params['page'] = isset($this->params['page']) ? (int)$this->params['page'] : 1;
        $P = new Warecorp_Common_PagingProduct($count, $size, $userSearch->getPagerLinkGlobalSearch($this->params));
        $P->setSearchPhrase($this->params['keywords']);
        $paging = $P->makePaging($this->params['page']);

        /*
        // top tags
        $tagsListObj = new Warecorp_User_Tag_List();
        $tagsListObj->returnAsAssoc()->setListSize(30)->setCurrentPage(1)->setOrder('rating DESC');

        if( !$tags = $cache->load('Warecorp_User_Tag_List__getListByLocation__world') ) {
            $tags = $tagsListObj->setEntityTypeId(1)->getList();
            $cache->save($tags, 'Warecorp_User_Tag_List__getListByLocation__world', array(), $cfgLifetime->tags);
        }
        // end top tags
        // shuffle tags
        $shuffledTags = array();
        while (is_array($tags) && count($tags) > 0) {
            $val = array_rand($tags);
            $shuffledTags[$val] = $tags[$val];
            unset($tags[$val]);
        }   */

        if( !$newtags = $cache->load('Global_Search_Tags_Members') ) {
            $tagsList = new Warecorp_User_Tag_List();
            $tags = $tagsList->returnAsAssoc()->setCurrentPage(1)->setListSize(30)->setEntityTypeId(1)->setOrder('@count desc')->getList();
            /*@TODO KOMAROVSKI REMOVE THIS AFTER OCTOBER 2009*/
            /*$newtags = array();
            while (is_array($tags) && count($tags) > 0) {
                $val = array_rand($tags);
                $tagObj = new Warecorp_Data_Tag($val);
                $newtags[$tagObj->name] = $tags[$val];
                unset($tags[$val]);
            }*/
            //@author Alexander Komarovski
            $newtags = array_flip($tags);
            Warecorp_List_Tags::normalizeArray($newtags);
            $newtags = Warecorp_List_Tags::shuffleTagsArray($newtags);
            //
            $cache->save($newtags, 'Global_Search_Tags_Members', array(), $cfgLifetime->tags);
        }

        $this->view->assign($this->params);
        $this->view->_url          = $_url;
        $this->view->bodyContent   = 'search/members.tpl';
        $this->view->usersList     = $usersList;
        $this->view->searchTitle   = isset($_SESSION['user_search']['title']) ? $_SESSION['user_search']['title'] : "";
        $this->view->paging        = $paging;
        $this->view->tagsList      = $newtags;
        $this->view->City          = isset($City->id) ? $City : "";
        $this->view->ageFrom       = array('0'=>'--')+array_combine(range(13,100), range(13,100)) + array(999 => '>100');
        $this->view->ageTo         = array('0'=>'--')+array_combine(range(13,100), range(13,100)) + array(999 => '>100');
        $this->view->friends       = $this->_page->_user->getId() ? $this->_page->_user->getFriendsList()->returnAsAssoc()->getList() : array();
    }
    $this->view->setLayout('main_wide.tpl');
