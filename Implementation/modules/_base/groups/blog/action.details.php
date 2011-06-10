<?php
Warecorp::addTranslation('/modules/groups/blog/action.details.php.xml');
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
     * check current topic params and create it if requerst is valid
     */
    if ( !isset($this->params['id']) || floor($this->params['id']) == 0 ) {
        $this->_redirect($this->currentGroup->getGroupPath('blog'));
    }
    $topic = new Warecorp_DiscussionServer_Topic($this->params['id']);
    if ( $topic->getId() === null ) {
        $this->_redirect($this->currentGroup->getGroupPath('blog'));
    }
    if ( $topic->getDiscussion()->getGroupId() !== $this->currentGroup->getId() ) {
        if ( !$this->currentGroup->getDiscussionAccessManager()->canViewGroupDiscussions($topic->getDiscussion()->getGroupId(), $this->_page->_user->getId()) ) {
            $this->_redirect($this->currentGroup->getGroupPath('blog'));
        }    
    }

    $this->_page->Xajax->registerUriFunction("remove_blog_post", "/groups/blogRemove/");
    $this->_page->Xajax->registerUriFunction("remove_blog_comment", "/groups/blogRemoveComment/");
    $this->_page->Xajax->registerUriFunction("edit_blog_comment", "/groups/blogEditComment/");
    $this->_page->Xajax->registerUriFunction("change_list_size", "/groups/changeListSize/");

    $form = new Warecorp_Form('postComment', 'post', $this->currentGroup->getGroupPath('blog.details').'id/'.$this->params['id'].'/');
    $form->addRule('comment', 'required', Warecorp::t('Enter please Message'));
    $form->addRule('comment', 'notempty', Warecorp::t('Enter please Message'));
    $this->view->form = $form;
    if ($form->validate($this->params)){
        $post = new Warecorp_DiscussionServer_Post();
        $post->setTopicId($topic->getId());
        $post->setParentId(null);
        $post->setAuthorId($this->_page->_user->getId());
        $post->setContent($this->params['comment']);
        $post->setTopicPart(false);
        $post->setFormat('text');
        $post->save();
        $post->setReadedForUser($this->_page->_user->getId());  
    }    
    
    
    $showTopicPartOnTop = true;
    
    if ( $this->_page->_user->isAuthenticated() ) {
        $settings = new Warecorp_DiscussionServer_User_Settings($this->_page->_user->getId());
        $listSize = $settings->getBlogCommentsPerPage();  
    } else {
        $listSize = ( isset($_SESSION['blogDetails']['size']) ) ? $_SESSION['blogDetails']['size'] : 10;
    }
    $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;
    $this->params['sortmode'] = (isset($this->params['sortmode']))? $this->params['sortmode'] : 2;
    $sortmodes = array("1" => "zdp.created DESC", "2" => "zdp.created ASC");
    if ( $this->params['sortmode'] >= 1 && $this->params['sortmode'] <= 2) $sortmode = $sortmodes[$this->params['sortmode']];
    else $sortmode = $sortmodes[1];
    
    $topic->getPosts()->setListSize($listSize);
    $topic->getPosts()->setOrder($sortmode);
    $topic->getPosts()->setShowTopicPart(!$showTopicPartOnTop);    
    $topicPostsListCount = $topic->getPostsCount();

    if ( $form->isPostback() && $form->isValid() ) {
        if ( $this->params['sortmode'] == 2 ) {
            $this->view->scrollToBottom = true;
            $this->params['page'] = floor($topicPostsListCount / $topic->getPosts()->getListSize()) + 1;
            $this->_redirect($this->currentGroup->getGroupPath('blog.details').'id/'.$this->params['id'].'/page/'.$this->params['page'].'/#p'.$post->getId());
        } else {
            $this->params['page'] = 1;
            $this->_redirect($this->currentGroup->getGroupPath('blog.details').'id/'.$this->params['id'].'/page/'.$this->params['page'].'/');
        }
        
    }
      
    $topic->getPosts()->setCurrentPage($this->params['page']);  
    $topicPostsList = $topic->getPosts()->findByTopicId($topic->getId());
        
    // Paging
    $url = $this->currentGroup->getGroupPath('blog.details').'id/'.$this->params['id'];
    $P = new Warecorp_Common_PagingProduct($topicPostsListCount, $topic->getPosts()->getListSize(), $url);
    $this->view->paging = $P->makeLinkPaging($this->params['page'], 'znbColored');
    
    $this->view->topic = $topic;
    $this->view->listSize = $listSize;
    $this->view->TopicPost = $topic->getTopicPost();
    $this->view->currentPage = $this->params['page'];
    $this->view->sortmode = $this->params['sortmode'];
    $this->view->showTopicPartOnTop = $showTopicPartOnTop;
    $this->view->topicPostsList = $topicPostsList;

    $this->view->bodyContent = 'groups/blog/blog.details.tpl';
