<?php
    Warecorp::addTranslation('/modules/search/action.groups.php.xml');

    if (!isset($actionUrl)) {
        $actionUrl = BASE_URL."/".$this->_page->Locale.'/search/groups/';
    }


    $this->params['keywords'] = isset($this->params['keywords']) ? trim(urldecode($this->params['keywords'])) : "";

    if ( $this->params['keywords'] == '' ) {
        $this->view->bodyContent = 'search/empty.keywords.tpl';
    }
    else {
        $groupSearch          = new Warecorp_Group_Search();
        $groupSearch->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
        $_presets             = array("tag", "category", "country", "state", "city", "new");
        $_orders              = $groupSearch->getOrders();

        $this->params['_url'] = $this->params['_actionUrl'] = $actionUrl;
        $_template            = 'search/groups.tpl';

        // Cache settings
        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
        $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};

        //
        $groupsList = array();
        $size       = 10;
        $count      = 0;

        if (isset($this->params['preset']) && in_array($this->params['preset'], $_presets)) { // new(?) search

            $this->params['_url'] = rtrim($this->params['_url'], '/').'/preset/'.$this->params['preset'];

            $groups = array();
            switch ($this->params['preset']) {
                 case 'new' : // new search
                    $_SESSION['group_search'] = array();
                    $keywordParser = new Warecorp_Search_KeywordsParser($this->params['keywords'], 'groups');
                    $parsedArray = $keywordParser->parseKeywordsToArray();

                    $this->params['keywords'] = $keywordParser->getOriginalKeywords();
                    if ($this->params['keywords']) {
                        $this->params['_url'] = rtrim($this->params['_url'], '/').'/keywords/'.urlencode($this->params['keywords']);
                    }

                    
                    $this->params['keywords'] = $keywordParser->getOriginalKeywords();

                    $s = &$_SESSION['group_search'];
                    $s['keywords']  = $this->params['keywords'];

                    if (Warecorp_Search_KeywordsParser::isSearchAvailable('groups', $parsedArray)) {
                        $groupSearch->setKeywords($parsedArray['keywords']);

                        //$groupSearch->setZipCodes();
                        $groupSearch->setDefaultOrder($parsedArray);
                        $groups = $groupSearch->searchByCriterions($parsedArray);
                    }else {
                        $groups = array();
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
                $groupsList = array_slice($groups, (($this->params['page']-1)*$size), $size, true);
                $groupsList = array_keys($groupsList);
            }

            $this->params['order']      = isset($this->params['order']) && isset($_orders[$this->params['order']]) ? trim($this->params['order']) : "";
        }

        foreach ($groupsList as &$group) {
            $group = new Warecorp_Group_Simple('id', $group);
        }

        $this->params['page']       = isset($this->params['page']) ? (int)$this->params['page'] : 1;
        $P = new Warecorp_Common_PagingProduct($count, $size, $groupSearch->getPagerLinkGlobalSearch($this->params));
        $P->setSearchPhrase($this->params['keywords']);
        $paging = $P->makePaging($this->params['page']);

        $this->_page->setTitle(Warecorp::t('Groups'));
        $this->view->assign($this->params);

        if( !$newtags = $cache->load('Global_Search_Tags_Groups') ) {
            $tagsList = new Warecorp_User_Tag_List();
            $tagsList->addFilter('entity_type_name', crc32('simple'));
            $tags = $tagsList->returnAsAssoc()->setCurrentPage(1)->setListSize(30)->setEntityTypeId(2)->setOrder('@count desc')->getList();

            /*@TODO KOMAROVSKI REMOVE THIS AFTER OCTOBER 2009*/
            /*$newtags = array();
            * while (is_array($tags) && count($tags) > 0) {
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

            $cache->save($newtags, 'Global_Search_Tags_Groups', array(), $cfgLifetime->tags);
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
        $this->view->groupsList          = $groupsList;
        $this->view->_urlWithoutLocation = $urlWithoutLocation;
        $this->view->paging              = $paging;
        $this->view->tagsList            = $newtags;
        $this->view->searchTitle         = isset($_SESSION['group_search']['title']) ? $_SESSION['group_search']['title'] : "";
    }

    $this->view->setLayout('main_wide.tpl');
