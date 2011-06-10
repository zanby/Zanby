<?php
Warecorp::addTranslation('/modules/groups/discussion/action.topic.php.xml');
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
	 * if showTopicPartOnTop == true main topic shows on top
	 */
	$showTopicPartOnTop = true;
	/**
	 * check current topic params and create it if requerst is valid
	 */
	if ( !isset($this->params['topicid']) || floor($this->params['topicid']) == 0 ) {
		$this->_redirect($this->currentGroup->getGroupPath('discussion'));
	}
	$topic = new Warecorp_DiscussionServer_Topic($this->params['topicid']);
	if ( $topic->getId() === null ) {
		$this->_redirect($this->currentGroup->getGroupPath('discussion'));
	}
    if ( $topic->getDiscussion()->getGroupId() !== $this->currentGroup->getId() ) {
        if ( !$this->currentGroup->getDiscussionAccessManager()->canViewGroupDiscussions($topic->getDiscussion()->getGroupId(), $this->_page->_user->getId()) ) {
            $this->_redirect($this->currentGroup->getGroupPath('discussion'));
        }    
    }

	/**
	 * register ajax methods
	 */
	$this->_page->Xajax->registerUriFunction("close_popup", "/groups/closePopup/");
	$this->_page->Xajax->registerUriFunction("reply_post", "/groups/replyPost/");
	$this->_page->Xajax->registerUriFunction("reply_post_do", "/groups/replyPostDo/");
    $this->_page->Xajax->registerUriFunction("save_post_reply", "/groups/savePostReply/");
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
	$this->_page->Xajax->registerUriFunction("move_topic", "/groups/moveTopic/");
	$this->_page->Xajax->registerUriFunction("move_topic_do", "/groups/moveTopicDo/");
	$this->_page->Xajax->registerUriFunction("remove_topic", "/groups/removeTopic/");
	$this->_page->Xajax->registerUriFunction("remove_topic_do", "/groups/removeTopicDo/");
	$this->_page->Xajax->registerUriFunction("close_topic", "/groups/closeTopic/");
    $this->_page->Xajax->registerUriFunction("reopen_topic", "/groups/reopenTopic/");

    if ( $this->_page->_user->isAuthenticated() ) {
        $settings = new Warecorp_DiscussionServer_User_Settings($this->_page->_user->getId());
        if ( isset($this->params['sortmode']) && in_array($this->params['sortmode'], array(1,2)) ) {
            $settings->setTopicOrder($this->params['sortmode']);
            $settings->save();
        }
        $listSize                   = $settings->getTopicPerPage();
        $this->params['sortmode']   = $settings->getTopicOrder();
    } else {
        $listSize                   = ( isset($_SESSION['topic']['size']) ) ? $_SESSION['topic']['size'] : 10;
        $this->params['sortmode']   = (isset($this->params['sortmode']))? $this->params['sortmode'] : 2;
    }
	$this->params['page']       = (isset($this->params['page']))? $this->params['page'] : 1;
	
	$sortmodes = array("1" => "zdp.created DESC", "2" => "zdp.created ASC");
	if ( $this->params['sortmode'] >= 1 && $this->params['sortmode'] <= 2 ) {
		$sortmode = $sortmodes[$this->params['sortmode']];
	} else {
		$sortmode = $sortmodes[1];
	}
    
    /* vsv */
    $discussionList = new Warecorp_DiscussionServer_DiscussionList();
    $dis = $discussionList->findByGroupId($topic->getDiscussion()->getGroupId());
    
    

	$topic->getPosts()->setCurrentPage($this->params['page']);
	$topic->getPosts()->setListSize($listSize);
	$topic->getPosts()->setOrder($sortmode);
	$topic->getPosts()->setShowTopicPart(!$showTopicPartOnTop);

	// Paging
	$url = $this->currentGroup->getGroupPath('topic').'topicid/'.$this->params['topicid'].'/sortmode/'.$this->params['sortmode'];
	$P = new Warecorp_Common_PagingProduct($topic->getPostsCount(), $topic->getPosts()->getListSize(), $url);
	$this->view->paging = $P->makeLinkPaging($this->params['page'], 'znbColored');

    $this->view->countDiscussion = count($dis);
    
	$this->view->topic = $topic;
	$this->view->listSize = $listSize;
	$this->view->TopicPost = $topic->getTopicPost();
	$this->view->currentPage = $this->params['page'];
	$this->view->sortmode = $this->params['sortmode'];
	$this->view->showTopicPartOnTop = $showTopicPartOnTop;

	$this->view->discussion_mode = DISCUSSION_MODE;
	
//@todo - remove block
//	if($this->currentGroup->getGroupType() == "family") {
//		$this->_page->breadcrumb = array_merge($this->_page->breadcrumb, array("Group families" => "/" .$this->_page->Locale. "/summary/", $this->currentGroup->getName() => ""));
//	} else {
//		//breadcrumb
//		$this->_page->breadcrumb = array_merge(
//			$this->_page->breadcrumb,
//			array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
//				$this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
//				$this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
//				$this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
//				$this->currentGroup->getName() => "")
//			); 
//	}

	$this->view->bodyContent = 'groups/discussion/topic.tpl';
