<?php
    $objResponse = new xajaxResponse();

    $discussionList     = new Warecorp_DiscussionServer_DiscussionList();
    $discussionListObj  = new Warecorp_DiscussionServer_DiscussionList();
    $postListObj        = new Warecorp_DiscussionServer_PostList();    
    $topicListObj       = new Warecorp_DiscussionServer_TopicList();

    $postListObj->buildCacheCountByTopicId();
    $postListObj->buildCacheCountAuthorsByTopicId();
    $postListObj->buildCacheCountUnreadByTopicId($this->_page->_user->getId());
    $postListObj->buildCacheCountByTopicIdAndDate(Warecorp_DiscussionServer_Topic::getDateHotStart(), Warecorp_DiscussionServer_Topic::getDateHotEnd());

    $discussion = new Warecorp_DiscussionServer_Discussion($discussion_id); 

    $this->view->discussion = $discussion;
    $this->view->postList = $postListObj;
    $this->view->group = Warecorp_Group_Factory::loadById($discussion->getGroupId());
    $content = $this->view->getContents('users/discussion/template.topics.tpl');

    $objResponse->addAssign('Discussion'.$discussion_id.'PreContent', 'innerHTML', $content);
    $objResponse->addScript("YAHOO.util.Dom.get('Discussion".$discussion_id."ContentLink').innerHTML = 'Hide';");
