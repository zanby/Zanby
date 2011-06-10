<?
    Warecorp::addTranslation('/modules/groups/action.search.php.xml');
    
    if (!isset($actionUrl)) {
        $actionUrl = BASE_URL."/".$this->_page->Locale.'/search/search'; 
    }
     
	$groupSearch          = new Warecorp_Global_Search();
    $_orders              = $groupSearch->getOrders();
        
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
    
        if (isset($this->params['preset']) && $this->params['preset'] == 'new') {
                $this->params['keywords'] = isset($this->params['keywords']) ? trim($this->params['keywords']) : "";
                $groupSearch->setKeywords($this->params['keywords']);
                $this->params['keywords'] = (is_array($groupSearch->keywords) && count($groupSearch->keywords)) ? implode(' ', $groupSearch->keywords) : "";
                $groupSearch->searchByCriterios();
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
    $paging = $P->makePaging($this->params['page']);
    
    $this->_page->setTitle('Groups');
    $this->view->assign($this->params);
    
    $urlParts = explode('/',$this->params['_url']);
    
    $this->view->bodyContent         = $_template;
    $this->view->form                = $searchForm;
    $this->view->groupsList          = $resultsList;
    $this->view->paging              = $paging;
    $this->view->setLayout('main_wide.tpl');
