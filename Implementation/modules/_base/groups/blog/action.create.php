<?php
Warecorp::addTranslation('/modules/groups/blog/action.create.php.xml');
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
     * get blog discussion for current group
     */
    $discussionList = new Warecorp_DiscussionServer_DiscussionList();
    $discussion = $discussionList->findBlogByGroupId($this->currentGroup->getId());    
    if ( null === $discussion ) $this->_redirect('/');

    /**
     * check access
     */
    if ( !$this->currentGroup->getDiscussionAccessManager()->canCreateBlogPosts($discussion->getId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getGroupPath('blog'));
    }
    

    $form = new Warecorp_Form('createTopicForm', 'post', $this->currentGroup->getGroupPath('blog.create'));
    $form->addRule('subject', 'required', Warecorp::t('Enter please Subject'));
    $form->addRule('subject', 'notempty', Warecorp::t('Enter please Subject'));
    $form->addRule('subject', 'maxlength', Warecorp::t('Subject too long (max %s)',200), array('max' => 200));
    //$form->addRule('subject', 'regexp',    'Subject must start with letter', array('regexp' => "/^[a-zA-Z]{1}/"));
    //$form->addRule('subject', 'regexp',    'Subject may consist of a-Z, 0-9, punctuation marks( except [, ], {, } )', array('regexp' => "/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.,;:\?!%@#\&\(\)\+\*=$]{0,}$/"));
    $form->addRule('subject', 'regexp',  Warecorp::t('Subject may not consist [, ], {, } '), array('regexp' => "/^[^\{\}\[\]]{1,}$/"));
    
    $form->addRule('content', 'required', Warecorp::t('Enter please Message'));
    $form->addRule('content', 'notempty', Warecorp::t('Enter please Message'));

    if ($form->validate($this->params)){
        $topic = new Warecorp_DiscussionServer_Topic();
        $topic->setSubject($this->params['subject']);
        $topic->setDiscussionId($discussion->getId());
        $topic->setAuthorId($this->_page->_user->getId());
        $topic->save();

        $post = new Warecorp_DiscussionServer_Post();
        $post->setTopicId($topic->getId());
        $post->setParentId(null);
        $post->setAuthorId($this->_page->_user->getId());
        $post->setContent($this->params['content']);
        $post->setTopicPart(true);
        $post->setFormat('html');
        $post->save();
        $post->setReadedForUser($this->_page->_user->getId());

        $this->_redirect($this->currentGroup->getGroupPath('blog'));
    } else {
        $postData['subject'] = (isset($this->params['subject'])) ? $this->params['subject'] : '';
        $postData['content'] = (isset($this->params['content'])) ? $this->params['content'] : '';
        $this->view->postData = $postData;
    }
    
    $this->view->form = $form;
    $this->view->bodyContent = 'groups/blog/blog.create.tpl';
