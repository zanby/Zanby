<?php
    Warecorp::addTranslation('/modules/search/action.videos.php.xml');

    $this->params['keywords'] = isset($this->params['keywords']) ? trim(urldecode($this->params['keywords'])) : "";
    $searchPhrase = $this->params['keywords'];

    if ( $this->params['keywords'] == '' ) {
        $this->view->bodyContent = 'search/empty.keywords.tpl';
    }
    else {
        $_url = BASE_URL.'/'.LOCALE.'/search/videos/keywords/'.urlencode($this->params['keywords']);

        $items_per_page = 12;
        $presets        = array("where", "country", "state", "city", "new");
        $page           = ( isset($this->params['page']) && $this->params['page']+0 > 0 ) ? (int)$this->params['page'] : 1;
        $template       = 'search/videos.tpl';
        $cache          = $this->getInvokeArg("bootstrap")->getResource("FileCache");

        if ( isset($this->params['preset']) )
            $this->params['preset'] = strtolower(trim($this->params['preset']));

        if ( isset($this->params['preset']) && in_array($this->params['preset'], $presets) ) {

            switch ( $this->params['preset'] ) {
                case 'new':
                    $parseParams = new Warecorp_Search_KeywordsParser($this->params['keywords'], 'videos');
                    $parsedKeywords = $parseParams->parseKeywordsToArray();
                    $this->params['keywords'] = $parseParams->getOriginalKeywords();
                    
                    if (Warecorp_Search_KeywordsParser::isSearchAvailable('videos', $parsedKeywords)) {       
                        $videosSearch = new Warecorp_Video_Search();
                        $videosSearch->setKeywords($parsedKeywords['keywords']);
                        $videos = $videosSearch->searchByCriterions($parsedKeywords);
                    }else{
                        $videos = array();
                    }
                    
                    if ( $videos )
                        $cache->save($videos, 'videos_search_'.session_id(), array(), 7200);
                    break;

                default:
                    break;
            }
        }

        if ( !isset($videos) && false === ($videos = $cache->load('videos_search_'.session_id())) ) {
            $this->_redirect(rtrim($_url, '/').'/preset/new/');
        }
        $foundCount = sizeof($videos);
        if ( $foundCount > $items_per_page ) {
            $videos = array_slice($videos, 0+($page-1)*$items_per_page, $items_per_page);
        }

        foreach ( $videos as &$video )
            $video = Warecorp_Video_Factory::loadById($video);

        $P = new Warecorp_Common_PagingProduct($foundCount, $items_per_page, $_url);
        $P->setSearchPhrase($this->params['keywords']);
        $paging = $P->makePaging($page);


        if( !$newtags = $cache->load('Global_Search_Tags_Videos') ) {
            $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};
            $tagsList = new Warecorp_User_Tag_List();
            $tags = $tagsList
                ->returnAsAssoc()
                ->setCurrentPage(1)
                ->setListSize(30)
                ->setEntityTypeId(37)
                ->setOrder('@count desc')
                ->getList();
            /*@TODO KOMAROVSKI REMOVE THIS AFTER OCTOBER 2009*/
            //$newtags = array();
            /*while (is_array($tags) && count($tags) > 0) {
                $val = array_rand($tags);
                $tagObj = new Warecorp_Data_Tag($val);
                $newtags[$tagObj->name] = $tags[$val];
                unset($tags[$val]);
            }*/
            //@author Alexander Komarovski
            $newtags = array_flip($tags);
            Warecorp_List_Tags::normalizeArray($newtags);
            $newtags = Warecorp_List_Tags::shuffleTagsArray($newtags);
            $cache->save($newtags, 'Global_Search_Tags_Videos', array(), $cfgLifetime->tags);
        }

        $this->view->keywords = $this->params['keywords'];
        $this->view->paging = $paging;
        $this->view->tagsList = $newtags;
        $this->view->videosList = $videos;
        $this->view->user = $this->_page->_user;
        $this->view->bodyContent = $template;
    }
    $this->view->setLayout('main_wide.tpl');
