<?php
Warecorp::addTranslation('/modules/groups/discussion/action.recent.topic.php.xml');

//    if ( !$this->_page->_user->isAuthenticated() ) {
//        $this->_redirectToLogin();
//    }
    if ( !$this->currentGroup->getDiscussionAccessManager()->canViewRecentMessages($this->currentGroup->getId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getDiscussionGroupHomePageLink());
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

    /**
     * get groups
     */
    $groups = array($this->currentGroup);
    $groupIds = array();
    //  include to search group
    //_______________________________________
    foreach ( $groups as $group ) {
        if ( $group->getDiscussionAccessManager()->canViewGroupDiscussions($group, $this->_page->_user->getId()) ) {
            $groupIds[] = $group->getId();
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
                $groupIds[] = $group->getId();
            }
        }
    }

    
    if ( $this->_page->_user->isAuthenticated() ) {
        $settings = new Warecorp_DiscussionServer_User_Settings($this->_page->_user->getId());
        $listSize = $settings->getRecentTopicPerPage();  
    } else {
        $listSize = ( isset($_SESSION['recenttopic']['size']) ) ? $_SESSION['recenttopic']['size'] : 10;
    }
    $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;    

    $postList = new Warecorp_DiscussionServer_PostList();
    $postList->setCurrentPage($this->params['page']);
    $postList->setListSize($listSize);
    $postList->setOrder('zdd.position ASC, zdt.topic_id ASC, zdp.created ASC');
    $posts = $postList->findRecentByGroupId($this->_page->_user->getId(), $groupIds);
    $totalPosts = $postList->countRecentByGroupId($this->_page->_user->getId(), $groupIds);

    $recents = array();
    $recentTopics = array();
    if ( sizeof($posts) != 0 ) {
        foreach ( $posts as $post ) {
            $recents[$post->getTopic()->getDiscussion()->getId()][$post->getTopic()->getId()][] = $post;
            $recentTopics[$post->getTopic()->getId()] = $post->getTopic();
        }
    }

    // Paging
    $url = $this->currentGroup->getGroupPath('recenttopic', false);
    $P = new Warecorp_Common_PagingProduct($totalPosts, $postList->getListSize(), $url);
    $this->view->paging = $P->makeLinkPaging($this->params['page'], 'znbColored');

    $this->view->totalPosts = $totalPosts;
    $this->view->groups = $groups;
    $this->view->recents = $recents;
    $this->view->recentTopics = $recentTopics;
    $this->view->listSize = $listSize;
    $this->view->emptyTopic = new Warecorp_DiscussionServer_Topic();
    $this->view->bodyContent = 'groups/discussion/recent.topic.tpl';
