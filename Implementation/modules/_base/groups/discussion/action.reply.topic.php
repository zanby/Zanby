<?php
Warecorp::addTranslation('/modules/groups/discussion/action.reply.topic.php.xml');
    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    if ( !isset($this->params['topicid']) || floor($this->params['topicid']) == 0 ) {
        $this->_redirect($this->currentGroup->getGroupPath('discussion'));
    }
    $topic = new Warecorp_DiscussionServer_Topic($this->params['topicid']);
    if ( null === $topic->getId() ) {
        $this->_redirect($this->currentGroup->getGroupPath('discussion'));
    }
    if ( $topic->isClosed() ) {
        $this->_redirect($this->currentGroup->getGroupPath('discussion'));
    }    
    if ( !$this->currentGroup->getDiscussionAccessManager()->canCreateDiscussionTopic($topic->getDiscussionId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getGroupPath('discussion'));
    }

    $form = new Warecorp_Form('createTopicForm', 'post', $this->currentGroup->getGroupPath('replytopic'));
    $form->addRule('discussion', 'required', Warecorp::t('Choose please To'));
    $form->addRule('content', 'required', Warecorp::t('Enter please Message'));
    $form->addRule('content', 'maxlength', Warecorp::t('Message too long (max %s)', 4096), array('max' => 4096));
    if ($form->validate($this->params)){
        $new_post = new Warecorp_DiscussionServer_Post();
        $new_post->setTopicId($topic->getId());
        $new_post->setParentId(null);
        $new_post->setAuthorId($this->_page->_user->getId());
        $new_post->setContent($this->params['content']);
        $new_post->setFormat(DISCUSSION_MODE);
        $new_post->save();
        $new_post->setReadedForUser($this->_page->_user->getId());
        $new_post = new Warecorp_DiscussionServer_Post($new_post->getId());

        /**
        * Removed according to Bug #3042
        * Don't remove this commented block
        * 
        if ( !Warecorp_DiscussionServer_Enum_SubscriptionType::isIn($this->params['subscription']) ) $this->params['subscription'] = -1;
        $subscription = Warecorp_DiscussionServer_TopicSubscription::findByTopicAndUserId($topic->getId(), $this->_page->_user->getId());
        if ( $subscription !== null ) {
            if ( $this->params['subscription'] == -1 ) {
                $subscription->delete();
            } else {
                $subscription->setSubscriptionType($this->params['subscription']);
                $subscription->update();
            }
        } else {
            if ( $this->params['subscription'] != -1 ) {
                $subscription = new Warecorp_DiscussionServer_TopicSubscription();
                $subscription->setTopicId($topic->getId());
                $subscription->setUserId($this->_page->_user->getId());
                $subscription->setSubscriptionType($this->params['subscription']);
                $subscription->save();
            }
        }
        */
        
        /**
         * build redirect
         * default mode sortmode = 2
         */
        $this->params['page']       = (isset($this->params['page'])) ? $this->params['page'] : 1 ;
        $this->params['sortmode']   = (isset($this->params['sortmode']))? $this->params['sortmode'] : 2;
        
        if ( $this->params['sortmode'] == 2 ) {
            /**
             * see action.topic.php
             */
            $showTopicPartOnTop = true;
            $listSize = ( isset($_SESSION['topic']['size']) ) ? $_SESSION['topic']['size'] : 10;
            $sortmodes = array("1" => "zdp.created DESC", "2" => "zdp.created ASC");
            if ( $this->params['sortmode'] >= 1 && $this->params['sortmode'] <= 2) $sortmode = $sortmodes[$this->params['sortmode']];
            else $sortmode = $sortmodes[1];
            
            $topic->getPosts()->setOrder($sortmode);
            $topic->getPosts()->setShowTopicPart(!$showTopicPartOnTop);
            $topicsCount = $topic->getPostsCount();
            $this->params['page'] = floor($topicsCount / $listSize) + 1;
        } else {
            $this->params['page'] = 1;
        }
        
        $this->_redirect($this->currentGroup->getGroupPath('topic').'topicid/'.$topic->getId().'/sortmode/'.$this->params['sortmode'].'/page/'.$this->params['page'].'/#p'.$new_post->getPosition());
    }
    $this->params['subscription'] = ( !isset($this->params['subscription']) ) ? -1 : $this->params['subscription'];
    $discussion = new Warecorp_DiscussionServer_Discussion($topic->getDiscussionId());
    $group = Warecorp_Group_Factory::loadById($discussion->getGroupId());
    $discussion->setGroup($group);

    $this->view->subscription = $this->params['subscription'];
    $this->view->subscribeContentOptions = Warecorp_DiscussionServer_Enum_SubscriptionType::getAsOptions();
    $this->view->discussionObj = $discussion;
    $this->view->discussion = $topic->getDiscussionId();
    $this->view->topic = $topic;
    $this->view->form = $form;
    
//    $this->view->content = isset($this->params['topicid']) ? $_SESSION['Post'.$this->params['postid']] : "";
    $this->view->content = ( isset($this->params['content']) ) ? $this->params['content'] : ( isset($this->params['postid']) ? $_SESSION['Post'.$this->params['postid']] : "" );
    //$this->view->content = isset($this->params['postid']) ? $_SESSION['Post'.$this->params['postid']] : "";


    $this->view->page = (isset($this->params['page'])) ? $this->params['page'] : 1;
    $this->view->sortmode = (isset($this->params['sortmode'])) ? $this->params['sortmode'] : 2;

    $this->view->discussion_mode = DISCUSSION_MODE;
    
//@todo - remove block
//    if($this->currentGroup->getGroupType() == "family") {
//	    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, array("Group families" => "/" .$this->_page->Locale. "/summary/", $this->currentGroup->getName() => ""));
//    } else {
//    //breadcrumb
//        $this->_page->breadcrumb = array_merge(
//            $this->_page->breadcrumb,
//            array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
//                $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
//                $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
//                $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
//                $this->currentGroup->getName() => "")
//            ); 
//    } 
    
    $this->view->bodyContent = 'groups/discussion/reply.topic.tpl';
