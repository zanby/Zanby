<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.view.append.comment.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();

    $params['record_id'] = isset($params['record_id']) ? (int)$params['record_id'] : 0;
    $record = new Warecorp_List_Record($params['record_id']);
    $list = new Warecorp_List_Item($record->getListId());

	$context = !empty($contextId)?Warecorp_Group_Factory::loadById(intval($contextId)):null;
    
    if (!$AccessManager->canViewList($list, $this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }

    $objResponse->addScript("unlock_content();");
    if ($record->getId() && $list->getId()) {
    
        if (isset($params['_wf__form_comment'])) {
            $_REQUEST['_wf__form_comment'] = $params['_wf__form_comment'];
        }
        
        $form = new Warecorp_Form('form_comment', 'POST', '');
        $form->addRule('comment', 'required', 'Enter please comment text');
    
        if ($form->validate($params)) {
            $record = new Warecorp_List_Record($params['record_id']);
            $record->addComment($params['comment']);
            
            /**
             * Facebook Feed
             */
			if ( FACEBOOK_USED ) {
				$comment = $params['comment'];
				$params = array(
					'title' => htmlspecialchars($list->getTitle()), 
					'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
				);
				$action_links[] = array('text' => 'View List', 'href' => $this->currentGroup->getGroupPath('listsview/listid/'.$list->getId()));
				$objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_LIST, $params); 
				if ( $comment ) $objMessage['message'] .= "\n" . htmlspecialchars($comment);
				$result = Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);

				/**
				 * facebook deprecated
				 * must remove it block after December 20th, 2009
				 * @author Artem Sukharev
				 *
				$params = array(
					'url' => $this->currentGroup->getGroupPath('listsview/listid/'.$list->getId()),
					'title' => $list->getTitle(), 
					'orgurl' => BASE_URL,
					'orgname' => SITE_NAME_AS_STRING
				);
				$objMessage = Warecorp_Facebook_Feed::getFeedActionMessage(Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_COMMENTED_LIST);
				$result = Warecorp_Facebook_Feed::postFeed($objMessage, $params, '', '', FacebookRestClient::STORY_SIZE_SHORT, $comment);
				*/
				if ( false === $result && '' != $js = Warecorp_Facebook_Feed::getJsResponse() ) $objResponse->addScript($js);            
			}
        }
        
        $this->listsViewRefresh($objResponse, $record->getListId());
        $objResponse->addScript("var display_index = document.getElementById('display_index_{$record->getId()}').innerHTML;");
    
        $xsl_view = $list -> getXslView($list->getListType());
        $XSLTProcessor = new XSLTProcessor();
        $XSLTProcessor->registerPHPFunctions();
        $XSLTProcessor->importStyleSheet($xsl_view);
        $dom = DOMDocument::loadXML($record->getXml());
        
        $dateObj = new Zend_Date();
        $dateObj->setTimezone($this->_page->_user->getTimezone());
        
        $this->view->form_comment      = $form;
        $this->view->record            = $record;
        $this->view->record_view       = $XSLTProcessor->transformToXml($dom);
        $this->view->Warecorp_List_AccessManager = $AccessManager;
        $this->view->TIMEZONE      = $dateObj->get(Zend_Date::TIMEZONE);
        
        $output = $this->view->getContents('groups/lists/lists.view.record.details.tpl');
        $objResponse->addClear("list_items", "div", "item_".($record->getId()));
        $objResponse->addAssign("item_".($record->getId()),'innerHTML', $output);
        $objResponse->addScript("document.getElementById('display_index_{$record->getId()}').innerHTML = display_index; display_index='';");
    }
