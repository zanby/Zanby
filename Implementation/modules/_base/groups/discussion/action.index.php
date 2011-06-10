<?php

    Warecorp::addTranslation('/modules/groups/discussion/action.discussion.index.php.xml');
    /**
     * check access
     */
    if ( !$this->_page->_user->isAuthenticated() && !$this->currentGroup->getDiscussionAccessManager()->canAnonymousViewGroupDiscussions($this->currentGroup->getId()) ) {
        $this->_redirectToLogin();
    }
    if ( !$this->currentGroup->getDiscussionAccessManager()->canViewGroupDiscussions($this->currentGroup->getId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getDiscussionGroupHomePageLink());
    }
    /**
     * register ajax methods
     */
    $this->_page->Xajax->registerUriFunction("load_topics", "/groups/loadDisTopics/");
    $this->_page->Xajax->registerUriFunction("show_discussion", "/groups/showDiscussionContent/");
    $this->_page->Xajax->registerUriFunction("show_subgroup", "/groups/showSubgroupContent/");
    $this->_page->Xajax->registerUriFunction ( "resignFromGroup", "/ajax/resignFromGroup/" ) ;
    $this->_page->Xajax->registerUriFunction ( "resignFromGroupDo", "/ajax/resignFromGroupDo/" ) ;
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");    
    /**
     * get groups
     */
    $groups = array($this->currentGroup);
    /**
     * get group members (group object) if current group is family group
     */
    $subGroups = array();
    if ( $this->currentGroup->getGroupType() == 'family' ) {
        $subGroups = $this->currentGroup->getGroups()
                          ->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
                          ->getList();
    }
    
    /**
     * get discussions list for current group
     */
    $discussionList = new Warecorp_DiscussionServer_DiscussionList();
    $discussions = $discussionList->findByGroupId($this->currentGroup->getId());
    
    $topicList = new Warecorp_DiscussionServer_TopicList();
    /**
     * get posts list object
     */
    $postList = new Warecorp_DiscussionServer_PostList();
     
     /*
    $postList->buildCacheCountByTopicId();
    $postList->buildCacheCountAuthorsByTopicId();
    $postList->buildCacheCountUnreadByTopicId($this->_page->_user->getId());
    $postList->buildCacheCountByTopicIdAndDate(Warecorp_DiscussionServer_Topic::getDateHotStart(), Warecorp_DiscussionServer_Topic::getDateHotEnd());
    */
    $recentMessages = 0;
    /**
     * find recent for group
     */
    
    foreach ( $groups as $group ) {
        if ( $group->getDiscussionAccessManager()->canViewGroupDiscussions($group->getId(), $this->_page->_user->getId()) ) {
            $recentMessages += $postList->countRecentByGroupId($this->_page->_user->getId(), $group->getId());
        }
    }
    /**
     * find recent for sub groups
     */
    if ( sizeof($subGroups) != 0 ) {
        foreach ( $subGroups as &$group ) {
            if ( $group->getDiscussionAccessManager()->canPublishToFamily($group->getId(), $this->currentGroup->getId()) && $group->getDiscussionAccessManager()->canViewGroupDiscussions($group->getId(), $this->_page->_user->getId()) ) {
                
                $recentMessages += $postList->countRecentByGroupId($this->_page->_user->getId(), $group->getId());
            }
        }
    }
    
    //  open first discussion as default
    //  added according by bug 4050
    if ( sizeof($groups) != 0 ) {    
        $discussionLst = $groups[0]->getDiscussionGroupDiscussions()->findByGroupId($groups[0]->getDiscussionGroupId());
        if ( sizeof($discussionLst) != 0 ) {
            if ( !isset($_SESSION['DiscussionServer']) || !isset($_SESSION['DiscussionServer']['openDiscussions']) || !isset($_SESSION['DiscussionServer']['openDiscussions'][$discussionLst[0]->getId()]) ) {
                $_SESSION['DiscussionServer']['openDiscussions'][$discussionLst[0]->getId()] = true;
            }
        }
    }
    /**
     * detect opened discussions
     */
    if ( isset($_SESSION['DiscussionServer']) && isset($_SESSION['DiscussionServer']['openDiscussions']) ) {
        $this->view->openedDiscussions = $_SESSION['DiscussionServer']['openDiscussions'];    	
    } else {
        $this->view->openedDiscussions = array();
    }
    /**
     * detect opened subgroups
     */
    if ( isset($_SESSION['DiscussionServer']) && isset($_SESSION['DiscussionServer']['openSubgroups']) && isset($_SESSION['DiscussionServer']['openSubgroups'][$this->currentGroup->getId()]) ) {
        $this->view->openSubgroups = $_SESSION['DiscussionServer']['openSubgroups'][$this->currentGroup->getId()];      
    } else {
        $this->view->openSubgroups = array();
    }
    
    /**
     * assign template vars
     */
    $this->view->groups = $groups;
    $this->view->subGroups = $subGroups;
    $this->view->discussions = $discussions;
    $this->view->recentMessages = $recentMessages;
    
    $postList->setCurrentPage(1);
    $postList->setListSize(1);
    $postList->setOrder('zdp.created DESC');
    $this->view->postList = $postList;
    /**
     * build breadcrumb
     * @todo  - remove this block
     */
//    if($this->currentGroup->getGroupType() == "family") {
//	    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, 
//	       array("Group families" => "/" .$this->_page->Locale. "/summary/", 
//	       $this->currentGroup->getName() => "")
//	    );
//    } else {
//        $this->_page->breadcrumb = array_merge(
//            $this->_page->breadcrumb,
//            array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
//                $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
//                $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
//                $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
//                $this->currentGroup->getName() => "")
//            ); 
//    }
    $this->view->bodyContent = 'groups/discussion/index.tpl';

    /**
     * rss
     */
/*    if(LOCALE == "rss" && !empty($this->params['id'])){
        include_once(ENGINE_DIR."/rss.class.php"); 
        $discussion = new Warecorp_DiscussionServer_Discussion(floor($this->params['id']));
        $rss = new UniversalFeedCreator();
        $rss->encoding = 'utf-8';
        $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';        
        $rss->link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $rss->title = "Group: ".$this->currentGroup->getName() . ". Discussion: ".$discussion->getTitle();
        $rss->description = "Group: ".$this->currentGroup->getName() . ". ".$discussion->getTitle()." discussion topics ";
        $rss->copyright = "Copyright &copy; 2007, Zanby";
        $topics = $discussion->getTopics()->findByDiscussionId($discussion->getId());
        foreach ($topics as $topic){
            $item = new FeedItem();
            $item->title = $topic->getSubject();
            $path = substr($this->currentGroup->getGroupPath(), 0, strlen($this->currentGroup->getGroupPath()) - 4);
            $item->link = $path . "en/topic/topicid/" . $topic->getId();
            $item->description = "Posts: " . count($topic->getPosts()->findByTopicId($topic->getId())) . " Authors: " . count($topic->getAuthorId()) . "<br />" ;
            $posts = $topic->getPosts()->findByTopicId($topic->getId());
            $lastPost = array_pop($posts);
            if (isset($lastPost)) {
                $item->description .= "Last Post: " . $rss->iTrunc($lastPost->getContent(), 200);
            }
            elseif (!isset($lastPost)) $item->description .= "Last Post: " . "No Recent Messages";

            $rss->addItem($item);
        }
        header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
        print $rss->createFeed("RSS2.0");
        exit; 
    }*/
    

