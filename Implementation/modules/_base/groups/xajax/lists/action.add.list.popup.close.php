<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.add.list.popup.close.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();

    $objResponse = new xajaxResponse();

    if (count($data) == 0) {
        $objResponse->addScript("popup_window.close();");
        if (!$AccessManager->canViewLists($this->currentGroup, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
            return;
        }
    } else {
        $data['list_id'] = isset($data['list_id']) ? (int)$data['list_id'] : 0;
        $list = new Warecorp_List_Item($data['list_id']);
        if (!$AccessManager->canViewList($list, $this->currentGroup, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
            return;
        }

        switch ($data['add_type']) {
            case 'merge':
                if (Warecorp_List_Item :: isListExists($data['merge_list'])) {
                    $listTarget = new Warecorp_List_Item($data['merge_list']);
                    $records = $list->getRecordsList();
                    $recordsTarget = $listTarget->getRecordsList();
                    $listTarget->save($list->getId(), 'merge', $this->_page->_user->getId());
                    if (count($records)) {
                        $items = array();
                        foreach ($records as &$newRecord) {
                            $items[] = $newRecord->getTitle();
                            $tags = $newRecord->getTagsList();
                            $comments = $newRecord->getCommentsList();
                            $newRecord->setId(null);
                            $newRecord->setListId($data['merge_list']);
                            $newRecord->setCreatorId($this->_page->_user->getId());
                            $newRecord->save();
                            if (count($tags)) {
                                foreach ($tags as &$tag) {
                                    $tag = $tag->getPreparedTagName();
                                }
                                $newRecord->addTags(implode(' ', $tags));
                            }
                            if (count($comments)) {
                                foreach ($comments as &$comment) {
                                    $comment->id = null;
                                    $comment->entityId = $newRecord->getId();
                                    $comment->save();
                                }
                            }
                        }

                        /** Send notification to host **/
                        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $listTarget, "LISTS", "CHANGES", count($items) > 1 ? true:false, $items );
                        
//                        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                            $mail->setSender($this->currentGroup);
//                            $mail->addRecipient($this->currentGroup->getHost());
//                            $mail->addParam('Group', $this->currentGroup);
//                            $mail->addParam('action', "CHANGES");
//                            $mail->addParam('section', "LISTS");
//                            $mail->addParam('chObject', $listTarget);
//                            $mail->addParam('User', $this->_page->_user);
//                            $mail->addParam('isPlural', count($items) > 1 ? true:false);
//                            $mail->addParam('items', $items);
//                            $mail->sendToPMB(true);
//                            $mail->send();
//                        }
                        /** --- **/
                    }
                }

                break;
            case 'new':

                $tags = $list->getTagsList();
                $records = $list->getRecordsList();

                $source_id = $list->getId();
                $newList = &$list;
                $newList->setId(null);
                $newList->setTitle($data['title']);
                $newList->setOwnerType('user');
                $newList->setOwnerId($this->_page->_user->getId());
                $newList->setCreatorId($this->_page->_user->getId());
                $newList->setCreationDate(new Zend_Db_Expr('NOW()'));
                $newList->save($source_id, 'new', $this->_page->_user->getId());

                /** Send notification to host **/
                $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $newList, "LISTS", "NEW", false );
                
//                if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                    $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                    $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setSender($this->currentGroup);
//                    $mail->addRecipient($this->currentGroup->getHost());
//                    $mail->addParam('Group', $this->currentGroup);
//                    $mail->addParam('action', "NEW");
//                    $mail->addParam('section', "LISTS");
//                    $mail->addParam('chObject', $newList);
//                    $mail->addParam('User', $this->_page->_user);
//                    $mail->addParam('isPlural', false);
//                    $mail->sendToPMB(true);
//                    $mail->send();
//                }
                /** --- **/

                if (count($tags)) {
                    foreach ($tags as &$tag) {
                        $tag = $tag->getPreparedTagName();
                    }
                    $newList->addTags(implode(' ', $tags));
                }

                $tags = array();
                if (count($records)) {
                    foreach ($records as &$newRecord) {
                        $tags = $newRecord->getTagsList();
                        $comments = $newRecord->getCommentsList();

                        $newRecord->setId(null);
                        $newRecord->setListId($newList->getId());
                        $newRecord->setCreatorId($this->_page->_user->getId());
                        $newRecord->save();
                        if (count($tags)) {
                            foreach ($tags as &$tag) {
                                $tag = $tag->getPreparedTagName();
                            }
                            $newRecord->addTags(implode(' ', $tags));
                        }
                        if (count($comments)) {
                            foreach ($comments as &$comment) {
                                $comment->id = null;
                                $comment->entityId = $newRecord->getId();
                                $comment->save();
                            }
                        }
                    }
                }
                break;
            case 'watch':
                $list->save($list->getId(), 'watch', $this->_page->_user->getId());
                break;
            case 'offwatch':
                $list->offWatch();
                break;
            default :
                break;


        }

        $list = new Warecorp_List_Item($data['list_id']);
        $lastImportData = $list->getLastImportTargetData();
        if ($lastImportData) {
            $this->view->lastImportData    = $lastImportData;
            $this->view->lastTargetList    = new Warecorp_List_Item($lastImportData['target_list_id']);
            $list->updateViewDate();
        }
        $dateObj = new Zend_Date();
        $dateObj->setTimezone($this->_page->_user->getTimezone());
        $this->view->TIMEZONE = $dateObj->get(Zend_Date::TIMEZONE);
        $this->view->list = $list;
        $this->view->Warecorp_List_AccessManager = $AccessManager;

        $output = $this->view->getContents('groups/lists/lists.view.update.tpl');
        $objResponse->addAssign('listUpdateBlock','innerHTML', $output);

        $objResponse->addScript("popup_window.close();");
        $objResponse->showAjaxAlert(Warecorp::t('Updated'));

    }
