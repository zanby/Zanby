<?php
    Warecorp::addTranslation("/modules/search/action.lists.php.xml");

    $this->params['keywords'] = isset($this->params['keywords']) ? trim(urldecode(strip_tags($this->params['keywords']))) : "";
    $searchPhrase = $this->params['keywords'];

    if ( $this->params['keywords'] == '' ) {
        $this->view->bodyContent = 'search/empty.keywords.tpl';
    }
    else {
        $listsSearch = new Warecorp_List_Search();

        $this->_page->Xajax->registerUriFunction("closePopup", "/ajax/closePopup/");
        $this->_page->Xajax->registerUriFunction("list_share", "/users/listsShare/");
        $this->_page->Xajax->registerUriFunction("list_unshare", "/users/listsUnshare/");
        $this->_page->Xajax->registerUriFunction("list_share_popup_show", "/users/listsSharePopupShow/");
        $this->_page->Xajax->registerUriFunction("list_share_popup_close", "/users/listsSharePopupClose/");
        $this->_page->Xajax->registerUriFunction("list_add_popup_show", "/users/listsAddListPopupShow/");
        $this->_page->Xajax->registerUriFunction("list_add_popup_close", "/users/listsAddListPopupClose/");

        $_url       = BASE_URL.'/'.LOCALE.'/search/lists/keywords/'.urlencode($this->params['keywords']);
        $_presets   = array("where", "country", "state", "city", "new", 'list_type');
        $_orders    = array('title' => 'title', 'author' => 'creator_login', 'created' => 'creation_date', 'items' => 'record_count');
        $_directions= array('asc', 'desc');
        $_template  = 'search/lists.tpl';

        $listsList  = array();
        $tags       = array();
        $size       = 10;
        $onCol      = 1;
        $cache      = $this->getInvokeArg("bootstrap")->getResource("FileCache");

        if ( isset($this->params['page']) && is_numeric($this->params['page']) && $this->params['page'] >= 1 ) {
            $page = (int) $this->params['page'];
        }
        else $page = 1;

        if ( isset($this->params['order']) ) {
            $this->params['order'] = strtolower(trim($this->params['order']));
            $order = ( in_array($this->params['order'], array_keys($_orders)) ) ? $_orders[$this->params['order']] : null;
        }
        else $order = null;

        if ( isset($this->params['direction']) ) {
            $this->params['direction'] = strtolower(trim($this->params['direction']));
            $direction = ( in_array($this->params['direction'], $_directions)) ? $this->params['direction'] : 'asc';
        }
        else $direction = 'asc';

        if ( isset($this->params['preset']) )
            $this->params['preset'] = strtolower(trim($this->params['preset']));

        if (isset($this->params['preset']) && in_array($this->params['preset'], $_presets)) {

            switch ($this->params['preset']) {
                case 'new' :
                    $parseParams = new Warecorp_Search_KeywordsParser($this->params['keywords'], 'lists');
                    $parsedKeywords = $parseParams->parseKeywordsToArray();
                    $searchPhrase = $this->params['keywords'] = $parseParams->getOriginalKeywords();

                    if (Warecorp_Search_KeywordsParser::isSearchAvailable('lists', $parsedKeywords)) {
                        $listsSearch->setKeywords($parsedKeywords['keywords']);

                        if ( !empty($order) ) {
                            $listsSearch->setOrder($order);
                            $listsSearch->setDirection($direction);
                        }

                        $lists = $listsSearch->searchByCriterions($parsedKeywords);
                    }else{
                        $lists = array();
                    }

                    if ( $lists )
                        $cache->save($lists, 'lists_search_'.session_id(), array(), 7200);
                    break;
                default:
                    break;
            }
        }

        if ( !isset($lists) && false === ($lists = $cache->load('lists_search_'.session_id())) ) {
            $this->_redirect(rtrim($_url, '/').'/preset/new/');
        }

        $foundCount = sizeof($lists);
        if ( $foundCount > $size )
            $lists = array_slice($lists, ($page-1)*$size, $size);

        foreach ($lists as &$list)
            $list = new Warecorp_List_Item($list);

        $dateObj = new Zend_Date();
        $dateObj->setTimezone($this->_page->_user->getTimezone());

        $P = new Warecorp_Common_PagingProduct($foundCount, $size, rtrim($_url, '/'). ($order? 'order/'.$this->params['order'].'/direction/'.$this->params['direction'] : ''));
        $P->setSearchPhrase($this->params['keywords']);
        $paging = $P->makePaging($page);

        if( !$newtags = $cache->load('Global_Search_Tags_Lists') ) {
            $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};
            $tagsList = new Warecorp_User_Tag_List();
            $tags = $tagsList->returnAsAssoc()->setCurrentPage(1)->setListSize(30)->setEntityTypeId(20)->setOrder('@count desc')->getList();
            /*@TODO KOMAROVSKI REMOVE THIS AFTER OCTOBER 2009*/
            /* $newtags = array();
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
            $cache->save($newtags, 'Global_Search_Tags_Lists', array(), $cfgLifetime->tags);
        }

        $this->view->assign($this->params);
        $this->view->tagsList      = $newtags;
        $this->view->bodyContent   = $_template;
        $this->view->_url          = $_url;
        $this->view->listsList     = $lists;
        $this->view->paging        = $paging;
        $this->view->tags          = $tags;
        $this->view->onCol         = $onCol;
        //'searchTitle'   => $this->params['keywords'],
        $this->view->TIMEZONE      = $dateObj->get(Zend_Date::TIMEZONE);
    }
    $this->view->setLayout('main_wide.tpl');
