<?php
Warecorp::addTranslation('/modules/groups/blog/xajax/action.remove.comment.php.xml');

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
       
    $post = new Warecorp_DiscussionServer_Post($id);
    if ( null === $post->getId() ) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('blog')); return;
    }
    
    /**
     * check access
     */
    if ( !$this->currentGroup->getDiscussionAccessManager()->canDeletePost($post->getId(), $this->_page->_user->getId()) ) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('blog')); return;
    }
    
    if ( null === $handle ) {
        $this->view->post = $post;
        $content = $this->view->getContents('groups/blog/xajax/delete.comment.popup.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title("Remove Comment");
        $popup_window->content($content);
        $popup_window->width(306)->height(350)->open($objResponse);

    } else {
        $post->delete();
        $objResponse->addScript('document.location.reload();');
    }

