<?php
Warecorp::addTranslation('/modules/search/action.discussions.search.php.xml');\
    $this->params['keywords'] = isset($this->params['keywords']) ? trim(urldecode($this->params['keywords'])) : "";

    if ( $this->params['keywords'] == '' ) {
        $this->view->bodyContent = 'search/empty.keywords.tpl';
    }
    else {
        $parseParams = new Warecorp_Search_KeywordsParser($this->params['keywords'], 'discussion');
        $parsedKeywords = $parseParams->parseKeywordsToArray();
        $keyword = $this->params['keywords'] = $parseParams->getOriginalKeywords();

        $_url       = BASE_URL.'/'.LOCALE.'/search/discussions/keywords/'.urlencode($this->params['keywords']);
        $_presets   = array("new");
        $size           = 10;

        if ( isset($this->params['page']) && is_numeric($this->params['page']) && (int)$this->params['page'] > 0 ) {
            $page = (int) $this->params['page'];
        }
        else $page = 1;

        if ( isset($this->params['preset']) )
            $this->params['preset'] = strtolower(trim($this->params['preset']));

        if (Warecorp_Search_KeywordsParser::isSearchAvailable('discussions', $parsedKeywords)) { 
            $searchObj = new Warecorp_DiscussionServer_Search();
            $posts = $searchObj->searchByCriterions($parsedKeywords);
        }else{
            $posts = array();
        }

        $foundCount = sizeof($posts);
        if ( $foundCount > $size ) {
            $posts = array_slice($posts, ($page-1)*$size, $size);
        }
        foreach ( $posts as &$post ) {
            $post = new Warecorp_DiscussionServer_Post($post);
            if ( $parsedKeywords['keywords'] )
                $post->setContent($searchObj->highlightKeyword($post->getContent(), $parsedKeywords['keywords']));
        }

        $P = new Warecorp_Common_PagingProduct($foundCount, $size, $_url);
        $P->setSearchPhrase($this->params['keywords']);

        $this->view->paging        =  $P->makePaging($page);
        $this->view->keywords      =  $this->params['keywords'];
        $this->view->totalPosts    =  $foundCount;
        $this->view->emptyTopic    =  new Warecorp_DiscussionServer_Topic();
        $this->view->listSize      =  $size;
        $this->view->results       =  $posts;
        $this->view->bodyContent   =  'search/discussions.tpl';
    }

    $this->view->setLayout('main_wide.tpl');
