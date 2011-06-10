<?php
Warecorp::addTranslation('/modules/groups/blog/xajax/action.remove.php.xml');

    $objResponse = new xajaxResponse();    
    
    if ( floor($id) == 0 ) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('blog')); return;
    }
    
    /**
     * check access
     */    
    if ( !$this->_page->_user->isAuthenticated() ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('blog');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');    
    }
    
    /**
     * get blog discussion for current group
     */
    $discussionList = new Warecorp_DiscussionServer_DiscussionList();
    $discussion = $discussionList->findBlogByGroupId($this->currentGroup->getId());    
    if ( null === $discussion ) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('blog')); return;
    }
    
    /**
     * check access
     */
    if ( !$this->currentGroup->getDiscussionAccessManager()->canCreateBlogPosts($discussion->getId(), $this->_page->_user->getId()) ) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('blog')); return;
    }
    
    $topic = new Warecorp_DiscussionServer_Topic($id);
    if ( null === $topic->getId() ) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('blog')); return;
    }
    
    if ( null === $handle ) {
        $this->view->topic = $topic;
        $content = $this->view->getContents('groups/blog/xajax/delete.post.popup.tpl');

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title('Remove Post');
        $popup_window->content($content);
        $popup_window->width(306)->height(350)->open($objResponse);
        
    } else {
        $topic->delete();
        $objResponse->addRedirect($this->currentGroup->getGroupPath('blog'));
    }

