<?php
Warecorp::addTranslation('/modules/groups/blog/action.edit.php.xml');
    /**
     * check access
     */
    if ( !$this->_page->_user->isAuthenticated() ) {
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
    /**
     * check access
     */
    if ( !$this->currentGroup->getDiscussionAccessManager()->canManageTopic($topic->getId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getGroupPath('blog'));
    }
    
    /**
     * get blog discussion for current group
     */
    $discussionList = new Warecorp_DiscussionServer_DiscussionList();
    $discussion = $discussionList->findBlogByGroupId($this->currentGroup->getId());    
    if ( null === $discussion ) $this->_redirect('/');
    
    
    $form = new Warecorp_Form('createTopicForm', 'post', $this->currentGroup->getGroupPath('blog.edit').'id/'.$topic->getId().'/');
    $form->addRule('subject', 'required', Warecorp::t('Enter please Subject'));
    $form->addRule('subject', 'notempty', Warecorp::t('Enter please Subject'));
    $form->addRule('subject', 'maxlength', Warecorp::t('Subject too long (max %s)',200), array('max' => 200));
    //$form->addRule('subject', 'regexp',   'Subject must start with letter', array('regexp' => "/^[a-zA-Z]{1}/"));
    //$form->addRule('subject', 'regexp',   'Subject may consist of a-Z, 0-9, punctuation marks( except [, ], {, } )', array('regexp' => "/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.,;:\?!%@#\&\(\)\+\*=$]{0,}$/"));
    $form->addRule('subject', 'regexp',   Warecorp::t('Subject may not consist [, ], {, } '), array('regexp' => "/^[^\{\}\[\]]{1,}$/"));
    
    $form->addRule('content', 'required', Warecorp::t('Enter please Message'));
    $form->addRule('content', 'notempty', Warecorp::t('Enter please Message'));

    if ($form->validate($this->params)){
        $topic->setSubject($this->params['subject']);
        $topic->update();
        
        $post = $topic->getTopicPost();
        $post->setContent($this->params['content']);
        $post->setFormat('html');
        $post->updateContent();

        $this->_redirect($this->currentGroup->getGroupPath('blog'));
        
    } else {
        $postData['subject'] = (isset($this->params['subject'])) ? $this->params['subject'] : $topic->getSubject();
        $postData['content'] = (isset($this->params['content'])) ? $this->params['content'] : $topic->getTopicPost()->getContent();
        $this->view->postData = $postData;
    }
    
    $this->view->form = $form;
    $this->view->bodyContent = 'groups/blog/blog.edit.tpl';
