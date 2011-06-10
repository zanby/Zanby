<?php
Warecorp::addTranslation('/modules/groups/discussion/action.discussion.settings.php.xml');
    /**
     * check access
     */
    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    if ( !$this->currentGroup->getDiscussionAccessManager()->canViewGroupDiscussions($this->currentGroup->getId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getDiscussionGroupHomePageLink());
    }
    if ( !$this->currentGroup->getDiscussionAccessManager()->canConfigureSettings($this->currentGroup->getId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getGroupPath('discussion'));
    }
    /**
     * register ajax methods
     */
    $this->_page->Xajax->registerUriFunction("delete_topic_subscription", "/groups/deleteTopicSubscription/");
    /**
     * create form
     */
    $form = new Warecorp_Form("settingsForm", "post", $this->currentGroup->getGroupPath("discussionsettings"));
    $form->addRule('digest_type', 'required', 'Coose Digest Mode');
    /**
     * load subscription settings
     */
    $subscription = Warecorp_DiscussionServer_GroupSubscription::findByGroupAndUserId($this->currentGroup->getId(), $this->_page->_user->getId());
    
    /**
     * opened tabs
     */
    $ContentOpen = array();
    /*
    $ContentOpen['DigestSubscriptionsContent'] = 1;
    $ContentOpen['PermissionsContent'] = 1;
    $ContentOpen['ModeratorsContent'] = 1;
    $ContentOpen['GroupFamiliesContent'] = 1;
    */
    /**
     * handle form submit
     */
    if ( $form->validate($this->params) ) {
    	/**
    	 * update sabscription settings
    	 */
        $subscription->setSubscriptionMode($this->params['digest_type']);
        $this->params['digest_type_value_all'] = ( !isset($this->params['digest_type_value_all']) ) ? 1 : $this->params['digest_type_value_all'];
        $subscription->setSubscriptionType($this->params['digest_type_value_all']);
        $subscription->setGroupAsOne((isset($this->params['group_as_one'])) ? 1 : 0);
        $subscription->update();
        
        /**
         * if turn off all subscriptions or Subscribe to all content on the discussion boards checked
         * pause all specific subscription 
         */
        if ( $this->params['digest_type'] == 1 || $this->params['digest_type'] = 2 ) {
	        /**
	         * turn off subscription for certain discussion if need
	         */
	        if ( isset($this->params['digest_type_value_discussions_hidden']) && sizeof($this->params['digest_type_value_discussions_hidden']) != 0 ) {
	            foreach ( $this->params['digest_type_value_discussions_hidden'] as $_ind => $_value ) {
	                $tmpSubscription = Warecorp_DiscussionServer_DiscussionSubscription::findByDiscussionAndUserId($_ind, $this->_page->_user->getId());
	                $tmpSubscription->setSubscriptionType($_value);
	                $tmpSubscription->update();
	            }
	        }
	        /**
	         * turn off subscription settings for certain topic
	         */
	        if ( isset($this->params['digest_type_value_topics_hidden']) && sizeof($this->params['digest_type_value_topics_hidden']) != 0 ) {
	            foreach ( $this->params['digest_type_value_topics_hidden'] as $_ind => $_value ) {
	                $tmpSubscription = new Warecorp_DiscussionServer_TopicSubscription($_ind);
	                $tmpSubscription->setSubscriptionType($_value);
	                $tmpSubscription->update();
	            }
	        }
	        	
        }
        
        /**
         * update subscription settings for certain discussion if need
         */
        if ( isset($this->params['digest_type_value_discussions']) && sizeof($this->params['digest_type_value_discussions']) != 0 ) {
            foreach ( $this->params['digest_type_value_discussions'] as $_ind => $_value ) {
                $tmpSubscription = Warecorp_DiscussionServer_DiscussionSubscription::findByDiscussionAndUserId($_ind, $this->_page->_user->getId());
                $tmpSubscription->setSubscriptionType($_value);
                $tmpSubscription->update();
            }
        }
        /**
         * update subscription settings for certain topic
         */
        if ( isset($this->params['digest_type_value_topics']) && sizeof($this->params['digest_type_value_topics']) != 0 ) {
            foreach ( $this->params['digest_type_value_topics'] as $_ind => $_value ) {
                $tmpSubscription = new Warecorp_DiscussionServer_TopicSubscription($_ind);
                $tmpSubscription->setSubscriptionType($_value);
                $tmpSubscription->update();
            }
        }
        $ContentOpen['DigestSubscriptionsContent'] = 1;
        $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
    }
    /**
     * create moderators list
     */
    $moderatorsList = new Warecorp_DiscussionServer_ModeratorList();
    $moderators = $moderatorsList->findByGroupId($this->currentGroup->getId());
    if ( sizeof($moderators) != 0 ) {
        foreach ($moderators as &$moderator) $moderator = new Warecorp_User("id", $moderator);
    }
    /**
     * create discussions list for current group
     */
    $discussions = $this->currentGroup->getDiscussionGroupDiscussions()->findByGroupId($this->currentGroup->getId());
    if ( sizeof($discussions) != 0 ) {
        foreach ( $discussions as &$discussion ) {
            $discussion->subscription = Warecorp_DiscussionServer_DiscussionSubscription::findByDiscussionAndUserId($discussion->getId(), $this->_page->_user->getId());
        }
    }
    /**
     * create topic subscription list for current group and gurrent user
     * @todo temporary turn off - don't remove
     */
    /*
    $topicSucriptionsList = new Warecorp_DiscussionServer_TopicSubscriptionList();
    $topicSucriptions = $topicSucriptionsList->findByGroupAndUserId($this->currentGroup->getId(), $this->_page->_user->getId());
    */
    $topicSucriptions = array();
    /**
     * get family groups list for simple group
     */
    if ( $this->currentGroup->getGroupType() == 'simple' ) {
    	$familyGroups = $this->currentGroup->getFamilyGroups()->getList();
        $this->view->familyGroups = $familyGroups;
    }
        
    /**
     * assign template vars
     */    
    $this->view->form = $form;
    $this->view->moderators = $moderators;
    $this->view->discussions = $discussions;
    $this->view->subscription = $subscription;
    $this->view->topicSucriptions = $topicSucriptions;
    $this->view->subscribeContentOptions = Warecorp_DiscussionServer_Enum_SubscriptionType::getAsOptions();
    $this->view->subscribeContentOptionsWithPause = Warecorp_DiscussionServer_Enum_SubscriptionType::getAsOptionsWithPuse();
    $this->view->ContentOpen = $ContentOpen;
    $this->view->GroupDiscussionStyle = $this->currentGroup->getDiscussionGroupSettings()->getDiscussionStyle();
    
    /**
     * build breadcrumb
     * @todo remove block
     */
//    if($this->currentGroup->getGroupType() == "family") {
//	    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, 
//            array(
//                "Group families" => "/" .$this->_page->Locale. "/summary/", 
//                $this->currentGroup->getName() => ""));
//    } else {
//        $this->_page->breadcrumb = array_merge(
//            $this->_page->breadcrumb,
//            array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
//                $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
//                $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
//                $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
//                $this->currentGroup->getName() => "")
//            ); 
//    } 
    
    $this->view->bodyContent = 'groups/discussion/settings.user.tpl';
