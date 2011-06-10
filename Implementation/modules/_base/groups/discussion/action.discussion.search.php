<?php
Warecorp::addTranslation('/modules/groups/discussion/action.discussion.search.php.xml');
    if ( !$this->currentGroup->getDiscussionAccessManager()->canViewGroupDiscussions($this->currentGroup->getId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getDiscussionGroupHomePageLink());
    }
    if ( !$this->currentGroup->getDiscussionAccessManager()->canSearcGroupDiscussions($this->currentGroup->getId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getGroupPath('discussion'));
    }
    if ( isset($this->params['_wf_']) && $this->params['_wf_'] == 1 ) {
        if ( !isset($this->params['keywords']) || trim($this->params['keywords']) == '' ) {
            $this->_redirect($this->currentGroup->getGroupPath('discussion'));
        } else {
            $_SESSION['search']['keywords'] = $this->params['keywords'];
            $keyword = $this->params['keywords'];
        }
    } else {
        if ( !isset($_SESSION['search']['keywords']) || trim($_SESSION['search']['keywords']) == '' ) {
            $this->_redirect($this->currentGroup->getGroupPath('discussion'));
        } else {
            $keyword = $_SESSION['search']['keywords'];
        }
    }

    $this->_page->Xajax->registerUriFunction("close_popup", "/groups/closePopup/");
    $this->_page->Xajax->registerUriFunction("reply_post", "/groups/replyPost/");
    $this->_page->Xajax->registerUriFunction("reply_post_do", "/groups/replyPostDo/");
    $this->_page->Xajax->registerUriFunction("edit_post", "/groups/editPost/");
    $this->_page->Xajax->registerUriFunction("edit_post_do", "/groups/editPostDo/");
    $this->_page->Xajax->registerUriFunction("delete_post", "/groups/deletePost/");
    $this->_page->Xajax->registerUriFunction("delete_post_do", "/groups/deletePostDo/");
    $this->_page->Xajax->registerUriFunction("email_author", "/groups/emailAuthor/");
    $this->_page->Xajax->registerUriFunction("email_author_do", "/groups/emailAuthorDo/");
    $this->_page->Xajax->registerUriFunction("report_post", "/groups/reportPost/");
    $this->_page->Xajax->registerUriFunction("report_post_do", "/groups/reportPostDo/");
    $this->_page->Xajax->registerUriFunction("notify_topic", "/groups/notifyTopic/");
    $this->_page->Xajax->registerUriFunction("notify_topic_do", "/groups/notifyTopicDo/");
    $this->_page->Xajax->registerUriFunction("change_list_size", "/groups/changeListSize/");

    $searchGroup = array();
    //  include to search group
    //_______________________________________
    $groups = array($this->currentGroup);
    foreach ( $groups as $group ) {
        if ( $group->getDiscussionAccessManager()->canViewGroupDiscussions($group, $this->_page->_user->getId()) ) {
            $searchGroup[] = $group->getId();
        }
    }
    //  include to search sub groups
    //_______________________________________
    $subGroups = array();
    if ( $this->currentGroup->getGroupType() == 'family' ) {
    	$subGroups = $this->currentGroup->getGroups()
    	                  ->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
    	                  ->getList();
    }
    if ( sizeof($subGroups) != 0 ) {
        foreach ( $subGroups as $group ) {
            if ( $group->getDiscussionAccessManager()->canPublishToFamily($group->getId(), $this->currentGroup->getId()) &&  $group->getDiscussionAccessManager()->canViewGroupDiscussions($group, $this->_page->_user->getId()) ) {
                $searchGroup[] = $group->getId();
            }
        }
    }

    if ( $this->_page->_user->isAuthenticated() ) {
        $settings = new Warecorp_DiscussionServer_User_Settings($this->_page->_user->getId());
        $listSize = $settings->getSearchPerPage();  
    } else {
        $listSize = ( isset($_SESSION['search']['size']) ) ? $_SESSION['search']['size'] : 10;
    }
    $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;

    $searchObj = new Warecorp_DiscussionServer_Search();
    $searchObj->setGroupId($searchGroup);
    $searchObj->setKeyword($keyword);
    $searchObj->setCurrentPage($this->params['page']);
    $searchObj->setListSize($listSize);
    $searchObj->setOrder('zdd.position ASC, zdt.topic_id ASC, zdp.created ASC');
    $posts = $searchObj->findPostsByKeyword();
    $totalPosts = $searchObj->countPostsByKeyword();

    $recents = array();
    $recentTopics = array();
    if ( sizeof($posts) != 0 ) {
        foreach ( $posts as $post ) {
            $recents[$post->getTopic()->getDiscussion()->getId()][$post->getTopic()->getId()][] = $post;
            $recentTopics[$post->getTopic()->getId()] = $post->getTopic();
        }
    }

    // Paging
    $url = $this->currentGroup->getGroupPath('discussionsearch', false);
    $P = new Warecorp_Common_PagingProduct($totalPosts, $searchObj->getListSize(), $url);
    $this->view->paging = $P->makeLinkPaging($this->params['page'], 'znbColored');

    $this->view->totalPosts = $totalPosts;
    $this->view->recents = $recents;
    $this->view->recentTopics = $recentTopics;
    $this->view->emptyTopic = new Warecorp_DiscussionServer_Topic();
    $this->view->listSize = $listSize;
    $this->view->results = $posts;

//@todo - remove
//    if($this->currentGroup->getGroupType() == "family") {
//	    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, array("Group families" => "/" .$this->_page->Locale. "/summary/", $this->currentGroup->getName() => ""));
//    } else {
//        //breadcrumb
//        $this->_page->breadcrumb = array_merge(
//            $this->_page->breadcrumb,
//            array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
//                $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
//                $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
//                $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
//                $this->currentGroup->getName() => "")
//            ); 
//    }
//
    $this->view->bodyContent = 'groups/discussion/search.results.tpl';
