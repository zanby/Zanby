<?php
    Warecorp::addTranslation('/modules/search/action.photos.php.xml');

    $this->params['keywords'] = isset($this->params['keywords']) ? trim(urldecode($this->params['keywords'])) : "";
    $searchPhrase = $this->params['keywords'];

    if ( $this->params['keywords'] == '' ) {
        $this->view->bodyContent = 'search/empty.keywords.tpl';
    }
    else {
        $items_per_page = 12;
        $_url           = BASE_URL.'/'.LOCALE.'/search/photos/keywords/'.urlencode($this->params['keywords']);

        $presets    = array("where", "country", "state", "city", "new");
        $page       = ( isset($this->params['page']) && $this->params['page']+0 > 0 ) ? (int)$this->params['page'] : 1;
        $template   = 'search/photos.tpl';

        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");

        if ( isset($this->params['preset']) )
            $this->params['preset'] = strtolower(trim($this->params['preset']));

        if ( isset($this->params['preset']) && in_array($this->params['preset'], $presets) ) {

            switch ( $this->params['preset'] ) {
                case 'new':
                    $parseParams = new Warecorp_Search_KeywordsParser($this->params['keywords'], 'photos');
                    $parsedKeywords = $parseParams->parseKeywordsToArray();
                    $this->params['keywords'] = $parseParams->getOriginalKeywords();
                    
                    if (Warecorp_Search_KeywordsParser::isSearchAvailable('photos', $parsedKeywords)) {       
                        $photosSearch = new Warecorp_Photo_Search();
                        $photosSearch->setKeywords($parsedKeywords['keywords']);
                        $photos = $photosSearch->searchByCriterions($parsedKeywords);
                    } else {
                        $photos = array();
                    }
                    if ( $photos )
                        $cache->save($photos, 'photos_search_'.session_id(), array(), 7200);
                    break;

                default:
                    break;
            }
        }

        if ( !isset($photos) && false === ($photos = $cache->load('photos_search_'.session_id())) ) {
            $this->_redirect(rtrim($_url, '/').'/preset/new/');
        }
        $foundCount = sizeof($photos);
        if ( $foundCount > $items_per_page ) {
            $photos = array_slice($photos, 0+($page-1)*$items_per_page, $items_per_page);
        }
        foreach ( $photos as &$photo )
            $photo = Warecorp_Photo_Factory::loadById($photo);

        /*
        $photoSearch = new Warecorp_Photo_Search();
        $photosSearch->setOrder($sort);
        $photosSearch->setCurrentPage($page);
        $photosSearch->setListSize($items_per_page);
        $photosList = $photosSearch->getSearchResult();
        */
        $P = new Warecorp_Common_PagingProduct($foundCount, $items_per_page, $_url);
        $P->setSearchPhrase($this->params['keywords']);
        $paging = $P->makePaging($page);


        if( !$newtags = $cache->load('Global_Search_Tags_Photos') ) {
            $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};
            $tagsList = new Warecorp_User_Tag_List();
            $tags = $tagsList
                ->returnAsAssoc()
                ->setCurrentPage(1)
                ->setListSize(30)
                ->setEntityTypeId(4)
                ->setOrder('@count desc')
                ->getList();
            $newtags = array_flip($tags);
            Warecorp_List_Tags::normalizeArray($newtags);
            $newtags = Warecorp_List_Tags::shuffleTagsArray($newtags);
            $cache->save($newtags, 'Global_Search_Tags_Photos', array(), $cfgLifetime->tags);
        }

        $this->view->keywords = $this->params['keywords'];
        $this->view->paging = $paging;
        $this->view->tagsList = $newtags;
        $this->view->photosList = $photos;
        $this->view->user = $this->_page->_user;
        $this->view->bodyContent = $template;
    }
    $this->view->setLayout('main_wide.tpl');
