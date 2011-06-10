<?php
    Warecorp::addTranslation('/modules/search/action.documents.php.xml');
    $this->params['keywords'] = isset($this->params['keywords']) ? trim(urldecode(strip_tags($this->params['keywords']))) : "";
    $searchPhrase = $this->params['keywords'];

    if ( $this->params['keywords'] === '' ) {
        $this->view->bodyContent = 'search/empty.keywords.tpl';
    }
    else {
        $_url       = BASE_URL.'/'.LOCALE.'/search/documents/keywords/'.urlencode($this->params['keywords']);
        if (isset($this->params['order'])) $_url .= '/order/'.$this->params['order'];
        if (isset($this->params['direction'])) $_url .= '/direction/'.$this->params['direction'];
        
        $_presets   = array("where", "country", "state", "city", "new");
        $_template  = 'search/documents.tpl';

        $cache          = $this->getInvokeArg("bootstrap")->getResource("FileCache");
        $size           = 10;

        if ( isset($this->params['page']) && is_numeric($this->params['page']) && $this->params['page'] >= 1 ) {
            $page = (int) $this->params['page'];
        }
        else $page = 1;

        if ( isset($this->params['preset']) )
            $this->params['preset'] = strtolower(trim($this->params['preset']));

        if (isset($this->params['preset']) && in_array($this->params['preset'], $_presets)) {

            switch ($this->params['preset']) {
                case 'new' :
                    $parseParams = new Warecorp_Search_KeywordsParser($this->params['keywords'], 'documents');
                    $parsedKeywords = $parseParams->parseKeywordsToArray();
                    $this->params['keywords'] = $parseParams->getOriginalKeywords();

                    if (Warecorp_Search_KeywordsParser::isSearchAvailable('documents', $parsedKeywords)) {       
                        $documentSearch = new Warecorp_Document_Search();
                        $documentSearch->setKeywords($parsedKeywords['keywords']);
                        $documentSearch->setOrder($this->params['order'],$this->params['direction']);

                        $documents = $documentSearch->searchByCriterions($parsedKeywords);
                    }else {
                        $documents = array();
                    }

                    if ( $documents )
                        $cache->save($documents, 'documents_search_'.session_id(), array(), 7200);
                    break;
                default:
                    break;
            }
        }

        if ( !isset($documents) && false === ($documents = $cache->load('documents_search_'.session_id())) ) {
            $this->_redirect(rtrim($_url, '/').'/preset/new/');
        }

        $foundCount = sizeof($documents);
        if ( $foundCount > $size )
            $documents = array_slice($documents, ($page-1)*$size, $size);

        foreach ($documents as &$doc)
            $doc = new Warecorp_Document_Item($doc);


        $P = new Warecorp_Common_PagingProduct($foundCount, $size, $_url);
        $P->setSearchPhrase($searchPhrase);
        $paging = $P->makePaging($page);

        //$this->_page->setTitle('Groups');
        //$this->view->assign($this->params);


        if( !$newtags = $cache->load('Global_Search_Tags_Documents') ) {
            $tagsList = new Warecorp_User_Tag_List();
            $tags = $tagsList->returnAsAssoc()->setCurrentPage(1)->setListSize(30)->setEntityTypeId(5)->setOrder('@count desc')->getList();
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
            $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};
            $cache->save($newtags, 'Global_Search_Tags_Documents', array(), $cfgLifetime->tags);
        }

        $this->view->tagsList            = $newtags;
        $this->view->bodyContent         = $_template;
        $this->view->documents           = $documents;
        $this->view->paging              = $paging;
        $this->view->assign($this->params); 
    }
    $this->view->setLayout('main_wide.tpl');
