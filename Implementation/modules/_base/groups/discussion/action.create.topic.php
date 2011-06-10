<?php
    Warecorp::addTranslation('/modules/groups/discussion/action.create.topic.php.xml');
    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    if ( !isset($this->params['discussion']) || floor($this->params['discussion']) == 0 ) {
        $this->_redirect($this->currentGroup->getGroupPath('discussion'));
    }
    if ( !$this->currentGroup->getDiscussionAccessManager()->canCreateDiscussionTopic($this->params['discussion'], $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getGroupPath('discussion'));
    }
    
    $form = new Warecorp_Form('createTopicForm', 'post', $this->currentGroup->getGroupPath('createtopic'));
    $form->addRule('discussion', 'required', Warecorp::t('Choose please To'));
    $form->addRule('subject', 'required', Warecorp::t('Enter please Subject'));
    $form->addRule('subject', 'notempty', Warecorp::t('Enter please Subject'));
    $form->addRule('subject', 'maxlength', Warecorp::t('Topic Subject too long (max %s)',200), array('max' => 200));
    $form->addRule('content', 'maxlength', Warecorp::t('Message too long (max %s)', 4096), array('max' => 4096));
    //$form->addRule('subject', 'regexp',    'Topic Subject must start with letter', array('regexp' => "/^[a-zA-Z]{1}/"));
    //$form->addRule('subject', 'regexp',    'Topic Subject may consist of a-Z, 0-9, punctuation marks( except [, ], {, } )', array('regexp' => "/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.,;:\?!%@#\&\(\)\+\*=$]{0,}$/"));
    $form->addRule('subject', 'regexp',    Warecorp::t('Topic Subject may not consist [, ], {, } '), array('regexp' => "/^[^\{\}\[\]]{1,}$/"));
    
    $form->addRule('content', 'required', Warecorp::t('Enter please Message'));
    $form->addRule('content', 'notempty', Warecorp::t('Enter please Message'));

    if ($form->validate($this->params)){
        $topic = new Warecorp_DiscussionServer_Topic();
        $topic->setSubject($this->params['subject']);
        $topic->setDiscussionId($this->params['discussion']);
        $topic->setAuthorId($this->_page->_user->getId());
        $topic->save();
        
        if ( FACEBOOK_USED ) {
            $paramsFB = array(
                'title' => htmlspecialchars($this->params['subject']), 
                'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
            );
            $action_links[] = array('text' => 'View Discussion', 'href' => $this->currentGroup->getGroupPath('topic/topicid/'.$topic->getId()));
            $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_DISCUSSION, $paramsFB);    
            Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);             
        }

        $post = new Warecorp_DiscussionServer_Post();
        $post->setTopicId($topic->getId());
        $post->setParentId(null);
        $post->setAuthorId($this->_page->_user->getId());
        $post->setContent($this->params['content']);
        $post->setTopicPart(true);
        $post->setFormat(DISCUSSION_MODE);
        $post->save();
        $post->setReadedForUser($this->_page->_user->getId());

        /**
        * Removed according to Bug #3042
        * Don't remove this commented block
        *        
        if ( !Warecorp_DiscussionServer_Enum_SubscriptionType::isIn($this->params['subscription']) ) $this->params['subscription'] = -1;
        if ( $this->params['subscription'] != -1 ) {
            $subscription = new Warecorp_DiscussionServer_TopicSubscription();
            $subscription->setTopicId($topic->getId());
            $subscription->setUserId($this->_page->_user->getId());
            $subscription->setSubscriptionType($this->params['subscription']);
            $subscription->save();
        }
        */
        
        /**
         * open current topic for this user
         */
        if ( !isset($_SESSION['DiscussionServer']) ) $_SESSION['DiscussionServer'] = array();
        $_SESSION['DiscussionServer']['openDiscussions'][$this->params['discussion']] = true;
        
        $this->_redirect($this->currentGroup->getGroupPath('discussion'));
    } else {
        $postData['subject'] = (isset($this->params['subject'])) ? $this->params['subject'] : '';
        $postData['content'] = (isset($this->params['content'])) ? $this->params['content'] : '';
        $this->view->postData = $postData;
    }
    $this->params['subscription'] = ( !isset($this->params['subscription']) ) ? -1 : $this->params['subscription'];

    $discussion = new Warecorp_DiscussionServer_Discussion($this->params['discussion']);

    $group = new Warecorp_Group_Base();
    $group->loadByPk($discussion->getGroupId());
    if ( $group->getGroupType() == 'simple' ) $group = new Warecorp_Group_Simple('id', $group->getId());
    elseif ( $group->getGroupType() == 'family' ) $group = new Warecorp_Group_Family('id', $group->getId());
    else throw new Zend_Exception(Warecorp::t("Incorrect group type"));
    $discussion->setGroup($group);

    $this->view->subscription = $this->params['subscription'];
    $this->view->subscribeContentOptions = Warecorp_DiscussionServer_Enum_SubscriptionType::getAsOptions();
    $this->view->discussionObj = $discussion;
    $this->view->discussion = $this->params['discussion'];
    $this->view->form = $form;

    $this->view->discussion_mode = DISCUSSION_MODE;

    $this->view->bodyContent = 'groups/discussion/create.topic.tpl';
