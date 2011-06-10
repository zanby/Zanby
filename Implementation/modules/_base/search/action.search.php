<?php
    Warecorp::addTranslation('/modules/search/action.search.php.xml');

    if (!isset($actionUrl)) {
        $actionUrl = BASE_URL."/".$this->_page->Locale.'/search/search';
    }

    if ( isset($this->params['keywords']) )
        $this->params['keywords'] = trim(urldecode($this->params['keywords']));

    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");

    $groupSearch          = new Warecorp_Global_Search();
    //$_orders              = $groupSearch->getOrders();
    $keywordParser = new Warecorp_Search_KeywordsParser($this->params['keywords'], 'groups');
    $parsedArray = $keywordParser->parseKeywordsToArray();
    //var_dump(Warecorp_Search_KeywordsParser::isSearchAvailable('lists', $parsedArray));
    //var_dump($parsedArray);

    $this->params['_url'] = $this->params['_actionUrl'] = $actionUrl;
    $_template            = 'search/search.tpl';
    $searchForm = new Warecorp_Form('search_group', 'POST',  "/".LOCALE."/search/search/");

    // Cache settings
    $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
    $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};

    //
    $resultsList = array();
    $size       = 10;
    $count      = 0;

    $this->params['keywords'] = $keywordParser->getOriginalKeywords();
    $searchPhrase = $this->params['keywords'];

    if ( empty($this->params['keywords']) ) {
        $this->view->bodyContent = 'search/empty.keywords.tpl';
    }
    else {
        if (isset($this->params['preset']) && $this->params['preset'] == 'new') {
                //var_dump($parsedArray);
                $groupSearch->setKeywords((string)$parsedArray['keywords']);
                $this->params['keywords'] = (is_array($groupSearch->keywords) && count($groupSearch->keywords)) ? implode(' ', $groupSearch->keywords) : "";
                $groupSearch->searchByCriterios($parsedArray);
                $results = $groupSearch->getResultIE();
                $cache->save($results, 'global_search_'.session_id(), array(), 7200);
        }

        if (!isset($results)){
            $results = $cache->load('global_search_'.session_id());
        }

        $resultsList = array();
        if ($results && count($results) > 0)
        {
            $count = count($results);
            $this->params['page'] = (isset($this->params['page']) && $this->params['page']>0) ? (int)$this->params['page'] : 1;
            $resultsPage = array_slice($results, (($this->params['page']-1)*$size), $size, true);
            foreach ($resultsPage as $id => $entityId) {
                $resultsList[] = Warecorp_Global_Factory::loadObject($id, $entityId);
            }
        }

        $this->params['page']       = isset($this->params['page']) ? (int)$this->params['page'] : 1;
        $P = new Warecorp_Common_PagingProduct($count, $size, $groupSearch->getPagerLink($this->params));
        $P->setSearchPhrase($searchPhrase);
        $paging = $P->makePaging($this->params['page']);

        $this->_page->setTitle('Search');
        //$this->view->assign($this->params);
        $this->view->keywords = $searchPhrase;

        $urlParts = explode('/',$this->params['_url']);

        $this->view->bodyContent         = $_template;
        $this->view->form                = $searchForm;
        $this->view->groupsList          = $resultsList;
        $this->view->paging              = $paging;
    }
    $this->view->setLayout('main_wide.tpl');
