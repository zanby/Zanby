<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.add.publish.php.xml');
    $listNew = false;
    $AccessManager = Warecorp_List_AccessManager_Factory::create();

    $objResponse = new xajaxResponse();

    if (!$AccessManager->canCreateLists($this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }

    $objResponse->addClear('list_errors', 'innerHTML');
    $objResponse->addAssign('list_errors', 'innerHTML', '');
    $this->view->action = 'add';

    if (!isset($_SESSION['list_new']['post_save']) || $_SESSION['list_new']['post_save']!='publish') {
        if (empty($data['title'])                           ||
            mb_strlen($data['description'], 'UTF-8') > 1024 ||
            mb_strlen($data['title'], 'UTF-8')       > 200  ||
            mb_strlen($data['tags'], 'UTF-8')        > 200)
        {
            if (empty($data['title']))                           $record['errors'][] = Warecorp::t("Please enter name of your list");
            if (mb_strlen($data['description'], 'UTF-8') > 1024) $record['errors'][] = Warecorp::t("Description too long (max %s)", 1024);
            if (mb_strlen($data['title'], 'UTF-8') > 200)        $record['errors'][] = Warecorp::t("Name of your list too long (max %s)", 200);
            if (mb_strlen($data['tags'], 'UTF-8') > 200)         $record['errors'][] = Warecorp::t("Tags of your list too long (max %s)", 200);
            $this->view->record = $record;
            $this->view->width = '538px';
            $output = $this->view->getContents('groups/lists/errors.tpl');
            $objResponse->addClear('list_errors', 'innerHTML');
            $objResponse->addAssign('list_errors', 'innerHTML', $output);
            $objResponse->addAssign('listTitle', 'className', 'znbErrorRow');
            $objResponse->addScript('window.scroll (0,-100000);');
            $objResponse->addScript('unlock_content();');
        } else {
            $_SESSION['list_new']['title']      = $data['title'];
			if (isset($data['owner']))
				$_SESSION['list_new']['owner']      = $data['owner'];
            $_SESSION['list_new']['description']= $data['description'];
            $_SESSION['list_new']['tags']       = $data['tags'];
            $_SESSION['list_new']['private']    = $data['private'];
            $_SESSION['list_new']['ranking']    = (empty($data['ranking'])) ? 0 : 1;
            $_SESSION['list_new']['adding']     = (empty($data['adding'])) ? 0 : 1;
            $_SESSION['list_new']['post_save']    = 'publish';
            $objResponse->addAssign('listTitle', 'className', '');
            $objResponse->addScript("xajax_list_add_save()");
        }
    } else {
        $list_new = &$_SESSION['list_new'];
        $newList = new Warecorp_List_Item();

        $newList->setListType($list_new['type']);
        $newList->setTitle($list_new['title']);
        $newList->setDescription($list_new['description']);
        $newList->setOwnerType('group');
        $newList->setOwnerId($this->currentGroup->getId());
        $newList->setCreatorId($this->_page->_user->getId());
        $newList->setCreationDate(new Zend_Db_Expr('NOW()'));
        $newList->setIsPrivate($list_new['private']);
        $newList->setRanking($list_new['ranking']);
        $newList->setAdding($list_new['adding']);

        $newList->save();
        $listNew = true;
        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $newList, "LISTS", "NEW", false );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', "NEW");
//            $mail->addParam('section', "LISTS");
//            $mail->addParam('chObject', $newList);
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/
        
        /**
         * Facebook Feed
         */
		if ( FACEBOOK_USED ) {
			$params = array(
				'title' => htmlspecialchars($newList->getTitle()), 
				'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
			);
			$action_links[] = array('text' => 'View List', 'href' => $this->currentGroup->getGroupPath('listsview/listid/'.$newList->getId()));
			$objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_LIST, $params); 
			$result = Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);

			/**
			 * facebook deprecated
			 * must remove it block after December 20th, 2009
			 * @author Artem Sukharev
			 *
			$params = array(
				'url' => $this->currentGroup->getGroupPath('listsview/listid/'.$newList->getId()),
				'title' => $newList->getTitle(), 
				'orgurl' => BASE_URL,
				'orgname' => SITE_NAME_AS_STRING
			);
			$objMessage = Warecorp_Facebook_Feed::getFeedActionMessage(Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_LIST);
			$result = Warecorp_Facebook_Feed::postFeed($objMessage, $params, '', '', FacebookRestClient::STORY_SIZE_SHORT, '');
			*/
        }

        $newList->addTags($list_new['tags']);

        if (isset($list_new['share']) && is_array($list_new['share'])) {
            foreach ($list_new['share'] as $share_id=>&$name) {
                list($target, $id) = explode('_', $share_id);
                switch ($target) {
                    case "u":
                        if (Warecorp_User::isUserExists('id',$id) && !Warecorp_List_Item::isListShared($newList->getId(), 'user', $id)) {
                            $newList->shareList('user', $id);
                        }
                        break;
                    case "g":
                        if (Warecorp_Group_Simple::isGroupExists('id',$id) && !Warecorp_List_Item::isListShared($newList->getId(), 'group', $id)) {
                            $newList->shareList('group', $id);
                        }
                        break;
                    default:
                        break;
                }
            }
        }


        if (isset($list_new['records']) && is_array($list_new['records'])) {
            foreach ($list_new['records'] as &$record) {
                if (isset($record['data']['item_fields']) && isset($record['title']) ) {
                    $newRecord = new Warecorp_List_Record();


                    $newRecord->setListId($newList->getId());
                    $newRecord->setTitle($record['title']);
                    $domXml             = $newList->arrayToXml($record['data']['item_fields']);

                    $newRecord->setXml($domXml->saveXML());
                    $newRecord->setEntry($record['data']['item_entry']);
                    $newRecord->setCreatorId($this->_page->_user->getId());
                    $newRecord->setCreationDate( new Zend_Db_Expr('NOW()') );

                    $newRecord->save();

                    if (!$listNew) {
                        /** Send notification to host **/
                        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $newList, "LISTS", "CHANGES", false, array($newList->getTitle()) );
                        
//                        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                            $mail->setSender($this->currentGroup);
//                            $mail->addRecipient($this->currentGroup->getHost());
//                            $mail->addParam('Group', $this->currentGroup);
//                            $mail->addParam('action', "CHANGES");
//                            $mail->addParam('section', "LISTS");
//                            $mail->addParam('chObject', $newList);
//                            $mail->addParam('User', $this->_page->_user);
//                            $mail->addParam('isPlural', false);
//                            $mail->addParam('items', array($newList->getTitle()));
//                            $mail->sendToPMB(true);
//                            $mail->send();
//                        }
                        /** --- **/
                    }

                    $newRecord->addTags($record['data']['item_tags']);
                }

            }
        }

        unset($_SESSION['list_new']);
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
    }
