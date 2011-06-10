<?php
    Warecorp::addTranslation('/modules/groups/blog/xajax/action.edit.comment.php.xml');

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
    if ( !$this->currentGroup->getDiscussionAccessManager()->canEditPost($post->getId(), $this->_page->_user->getId()) ) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('blog')); return;
    }
    
    $form = new Warecorp_Form('editComment', 'post', '?');
    $form->addRule('comment', 'required', Warecorp::t('Enter please Message'));
    $form->addRule('comment', 'notempty', Warecorp::t('Enter please Message'));
    $this->view->form = $form;
    $this->view->post = $post;

    $cancelLink = 'xajax_edit_blog_comment('.$post->getId().', 0); return false;';
    $oklLink = 'xajax_edit_blog_comment('.$post->getId().', {comment : document.getElementById(\'commentContent'.$post->getId().'\').value, _wf__editComment : 1}); return false;';
    $this->view->cancelLink = $cancelLink;
    $this->view->oklLink = $oklLink;
    
    if ( null === $handle ) {
        $content = $this->view->getContents('groups/blog/xajax/edit.comment.template.tpl');
        $objResponse->addAssign('PostInnerHTML'.$post->getId(), 'innerHTML', $content);
    } elseif ( 0 == $handle ) {
        $objResponse->addAssign('PostInnerHTML'.$post->getId(), 'innerHTML', $post->getPostContent());
    } else {
        $_REQUEST['_wf__editComment'] = 1;
        $post->setContent($handle['comment']);
        $post->setFormat('text');
        if ( $form->validate($handle) ) {              
            $post->updateContent();
            $objResponse->addAssign('PostInnerHTML'.$post->getId(), 'innerHTML', $post->getPostContent());
        } else {
            $content = $this->view->getContents('groups/blog/xajax/edit.comment.template.tpl');
            $objResponse->addAssign('PostInnerHTML'.$post->getId(), 'innerHTML', $content);
        }
    }

