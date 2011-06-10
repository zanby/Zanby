<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.loadTopics.php.xml');

    $descussion = new Warecorp_DiscussionServer_Discussion($discussion_id);
    $topicsList = $descussion->getTopics()->setCurrentPage($currentPage + 1)->setListSize(10)->findByDiscussionId($discussion_id);

    $objResponse = new xajaxResponse();

    $Script     = "";
    $div_name   = "Discussion".$discussion_id."TopicListDiv";
    //$div_name   = "Discussion".$discussion_id."TopicList";
    $table_name = "Discussion".$discussion_id."TopicListTable";
    if ( sizeof($topicsList) != 0 ) {
        $Content = "";
        foreach ( $topicsList as $_ind => $topic ) {

            $this->view->descussion = $descussion;
            $this->view->discussion_id = $discussion_id;
            $this->view->fromScript = true;
            $this->view->topic = $topic;
            $this->view->hideContent = true;
            
            $postList = new Warecorp_DiscussionServer_PostList();
            $postList->setCurrentPage(1);
            $postList->setListSize(1);
            $postList->setOrder('zdp.created DESC');
            $this->view->postList = $postList;
        	
        	$objResponse->addCreate($div_name, 'TR', '_dynamicTopicTr'.$topic->getId());
        	$objResponse->addCreate('_dynamicTopicTr'.$topic->getId(), 'TD', '_dynamicTopicTd1'.$topic->getId());
        	$objResponse->addCreate('_dynamicTopicTr'.$topic->getId(), 'TD', '_dynamicTopicTd2'.$topic->getId());
        	$objResponse->addCreate('_dynamicTopicTr'.$topic->getId(), 'TD', '_dynamicTopicTd3'.$topic->getId());
        	$objResponse->addAssign('_dynamicTopicTd1'.$topic->getId(), 'className', 'znWidgetInner14');
            $objResponse->addAssign('_dynamicTopicTd1'.$topic->getId(), 'align', 'center');
            $objResponse->addAssign('_dynamicTopicTd3'.$topic->getId(), 'className', 'znTRight znWidgetInner3');
            
        	
        	$this->view->currentTD = 'td1';
        	$objResponse->addAssign('_dynamicTopicTd1'.$topic->getId(), 'innerHTML', $this->view->getContents("groups/discussion/index.topic.tr.template.tpl"));
            $this->view->currentTD = 'td2';
            $objResponse->addAssign('_dynamicTopicTd2'.$topic->getId(), 'innerHTML', $this->view->getContents("groups/discussion/index.topic.tr.template.tpl"));
            $this->view->currentTD = 'td3';
            $objResponse->addAssign('_dynamicTopicTd3'.$topic->getId(), 'innerHTML', $this->view->getContents("groups/discussion/index.topic.tr.template.tpl"));
        }        
        $objResponse->addScript($Script);
        if ( sizeof($topicsList) < 10 ) {
            $objResponse->addScript("DynamicContentDivs['Discussion".$discussion_id."TopicList'].eventScrollListener = function(){};");
        }
    } else {
        $objResponse->addScript("DynamicContentDivs['Discussion".$discussion_id."TopicList'].eventScrollListener = function(){};");
    }
    $objResponse->addScript("DynamicContentDivs['Discussion".$discussion_id."TopicList'].onRequestEnd();");
