<?php
    Warecorp::addTranslation("/modules/users/xajax/lists/action.add.list.popup.close.php.xml");
    
    $objResponse = new xajaxResponse();  
    if (count($data) == 0) {
        $objResponse->addScript("popup_window.close();");
        if (!Warecorp_List_AccessManager_Factory::create()->canViewLists($this->currentUser, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
            return;
        }
    } else {

        $form = new Warecorp_Form('list_add_form', 'POST', '');
        $_REQUEST['_wf__list_add_form'] = $data['_wf__list_add_form'];
        //check for "save as new list" option only
        if ($data['add_type']=='new') {
            $form->addRule('title',     'notempty',     Warecorp::t('Enter please New List Name'));
        }
        if ($form->validate($data)){
            $data['list_id'] = isset($data['list_id']) ? (int)$data['list_id'] : 0;
            $list = new Warecorp_List_Item($data['list_id']);
            if (!Warecorp_List_AccessManager_Factory::create()->canViewList($list, $list->getOwner(), $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
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
                            foreach ($records as &$newRecord) {
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

            $output = $this->view->getContents('users/lists/lists.view.update.tpl');
            $objResponse->addAssign('listUpdateBlock','innerHTML', $output);
        
            $objResponse->addScript("popup_window.close();");
            $objResponse->showAjaxAlert('Updated');
        } else {
            $importType = $data['add_type'];
            $list_id = isset($data['list_id']) ? $data['list_id'] : 0;
            $listItem = new Warecorp_List_Item($list_id);

            if ($listItem->getId()) {
        
                $list = new Warecorp_List_List($this->_page->_user); 
                $listsList = $list->getListsListByTypeAssoc($listItem->getListType(), false);
        
                $lastTarget = $listItem->getLastImportTargetData();
        
                if ($lastTarget) {
                    $importType=$lastTarget['import_type'];
                    $this->view->importType = $importType;
                    $this->view->importDate = $lastTarget['import_date'];
                    $lastTarget = new Warecorp_List_Item($lastTarget['target_list_id']);
                    $this->view->lastTarget = $lastTarget;
            
                    switch ($importType) {
                        case 'merge':
                            $this->view->checkedType = 'merge';
                            break;
                        case 'new':
                            $this->view->checkedType = 'merge';
                            break;
                        case 'watch':
                            $this->view->checkedType = 'offwatch';
                            break;
                        default: 
                            break;
                    
                    }
            
                } else {
                    if ($listsList) {
                        $this->view->checkedType = 'merge';
                    } else {
                        $this->view->checkedType = 'new';
                    }
                }
        
                $dateObj = new Zend_Date();
                $dateObj->setTimezone($this->_page->_user->getTimezone());
        
                $this->view->listsList = $listsList;
                $this->view->form = $form;
                $this->view->list = $listItem;
                $this->view->TIMEZONE = $dateObj->get(Zend_Date::TIMEZONE);
        
                $title = (!empty($importType)) ? Warecorp::t("Update Lists") : Warecorp::t("Add to My Lists");
                $content = $this->view->getContents('users/lists/add.popup.tpl');
                
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->title($title);
                $popup_window->content($content);
                $popup_window->width(500)->height(350)->open($objResponse);

                $objResponse->addAssign("radio_new", "checked", "checked");
                $objResponse->addAssign("title", "value", '');
                
            }
        }
    }
