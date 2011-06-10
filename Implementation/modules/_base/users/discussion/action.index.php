<?php
    if ( $this->_page->_user->getId() === null ) $this->redirectToLogin();
    if ( $this->_page->_user->getId() != $this->currentUser->getId() ) $this->_redirect($this->_page->_user->getUserPath('discussion'));

    $this->params['mode'] = (!isset($this->params['mode'])) ? 'groups' : $this->params['mode'];
    if ( !in_array($this->params['mode'], array('groups', 'families', 'commented')) ) $this->params['mode'] = 'groups';

    $discussionList     = new Warecorp_DiscussionServer_DiscussionList();
    $discussionListObj  = new Warecorp_DiscussionServer_DiscussionList();
    $postListObj        = new Warecorp_DiscussionServer_PostList();
    $topicListObj       = new Warecorp_DiscussionServer_TopicList();

    /*
    $postListObj->buildCacheCountByTopicId();
    $postListObj->buildCacheCountAuthorsByTopicId();
    $postListObj->buildCacheCountUnreadByTopicId($this->_page->_user->getId());
    $postListObj->buildCacheCountByTopicIdAndDate(Warecorp_DiscussionServer_Topic::getDateHotStart(), Warecorp_DiscussionServer_Topic::getDateHotEnd());
    */
    $groupsList = array();

    /**
     * register ajax methods
     */
    $this->_page->Xajax->registerUriFunction("exclude_topic", "/users/excludeTopic/");
    $this->_page->Xajax->registerUriFunction("exclude_topic_do", "/users/excludeTopicDo/");
    $this->_page->Xajax->registerUriFunction("show_topics", "/users/discussionShowTopics/");

    switch ( $this->params['mode'] ) {
    	case 'groups' :
            
		    $groupsList = $this->currentUser->getGroups()->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))->getList();

		    if ( sizeof($groupsList) != 0 ) {
		        foreach ( $groupsList as &$group ) {
		            if ( Warecorp_DiscussionServer_AccessManager_Factory::create()->canViewGroupDiscussions($group, $this->_page->_user->getId()) ) {
		                /**
		                 * find discussions for this group
		                 */
		                $discussions = $discussionList->findByGroupId($group->getId());
		                /**
		                 * add discussions to group
		                 */
		                $group->discussionsList         = $discussions;
		                $group->discussionsListCount    = sizeof($discussions);
                        
		            }
		        }
		    }
		    $this->view->groupsList = $groupsList;
    		break;
        case 'families' :
		    $groupsList = $this->currentUser->getGroups()->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY))->getList();
		    if ( sizeof($groupsList) != 0 ) {
		        foreach ( $groupsList as &$group ) {
		            if ( Warecorp_DiscussionServer_AccessManager_Factory::create()->canViewGroupDiscussions($group, $this->_page->_user->getId()) ) {
		                /**
		                 * find discussions for this group
		                 */
		                $discussions = $discussionList->findByGroupId($group->getId());
		                /**
		                 * add discussions to group
		                 */
		                $group->discussionsList         = $discussions;
		                $group->discussionsListCount    = sizeof($discussions);
		            }
		        }
		    }
        	$this->view->groupsList = $groupsList;
            break;
        case 'commented' :

            $postListObj->buildCacheCountByTopicId();
            $postListObj->buildCacheCountAuthorsByTopicId();
            $postListObj->buildCacheCountUnreadByTopicId($this->_page->_user->getId());
            $postListObj->buildCacheCountByTopicIdAndDate(Warecorp_DiscussionServer_Topic::getDateHotStart(), Warecorp_DiscussionServer_Topic::getDateHotEnd());

        	$discussions = array();
        	$sortModeAllowed = array('zdt.lastpostcreated DESC', 'zdt.lastpostcreated ASC');
        	$this->params['sortMode'] = ( !isset($this->params['sortMode']) ) ? 1 : floor($this->params['sortMode']);
        	if ( !in_array($this->params['sortMode'], array(1,2)) ) $this->params['sortMode'] = 1;

        	$exclude = Warecorp_DiscussionServer_TopicList::getExcludedTopicIdsByUser($this->currentUser->getId());

		    $topics = $topicListObj->setOrder($sortModeAllowed[$this->params['sortMode']-1])->findByUserCommented($this->currentUser->getId(), $exclude); 
		    $createdGroups = array();
            if ( sizeof($topics) != 0 ) {
		        foreach ( $topics as $_ind => &$topic ) {
                    /**
                    * Set Discussion Group Object
                    */
                    if ( !array_key_exists($topic->getDiscussion()->getGroupId(), $createdGroups) ) {
                        $tmpGroup = Warecorp_Group_Factory::loadById($topic->getDiscussion()->getGroupId());
                        $createdGroups[$topic->getDiscussion()->getGroupId()] = $tmpGroup;
                    } else {
                    	$tmpGroup = $createdGroups[$topic->getDiscussion()->getGroupId()];
                    }
                    
                    $topic->getDiscussion()->setGroup($tmpGroup);
                    /**
                    * Add to my commented discussions
                    */
                    if ( !isset($discussions[$topic->getDiscussionId()]) ) {
                      $discussions[$topic->getDiscussionId()] = array();
                      $discussions[$topic->getDiscussionId()]['discussion'] = $topic->getDiscussion();
                      $discussions[$topic->getDiscussionId()]['topics'] = array();
                    }
                    $discussions[$topic->getDiscussionId()]['topics'][] = $topic;
		        }
		    }
		    $this->view->commentedDiscussions = $discussions;
		    $this->view->commentedTopics = $topics;
		    $this->view->sortMode = $this->params['sortMode'];
            break;
    }

    $this->view->mode = $this->params['mode'];
    $this->view->discussionListObj = $discussionListObj;
    $this->view->topicListObj = $topicListObj;
    $this->view->postListObj = $postListObj;
    $this->view->postList = $postListObj;
    $this->view->bodyContent = 'users/discussion/index.tpl';
    
    /*
    $__db = Zend_Registry::get('DB');
    Zend_Debug::dump($__db->getProfiler()->getTotalNumQueries());
    foreach ($__db->getProfiler()->getQueryProfiles() as $query) {
        Zend_Debug::dump($query->getElapsedSecs() . ' : ' . str_replace("\n", ' ', $query->getQuery()));
    }
    exit;
    */
