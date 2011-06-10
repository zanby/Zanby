<?php
    Warecorp::addTranslation("/modules/search/xajax/list.add.to.my.php.xml");
    $objResponse = new xajaxResponse () ;
    
    /* check params */
    if ( empty($this->params['list']) ) {
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }    
    $this->params['handle'] = empty($this->params['handle']) ? false : $this->params['handle'];    

    /* check user */
    if ( null === $this->_page->_user->getId() ) {
        Warecorp_Access::redirectToLoginXajax($this->_page->Xajax, BASE_URL.'/'.LOCALE.'/search/lists/preset/new/');
    }
    
    $listItem = new Warecorp_List_Item($this->params['list']);
    if ( $listItem->getId() === null ) {
        $objResponse->showAjaxAlert(Warecorp::t('Unknown Error'));
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }

    /* check access */
    if (!Warecorp_List_AccessManager_Factory::create()->canViewList($listItem, $listItem->getOwner(), $this->_page->_user)) {
        $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    if ( !$this->params['handle'] ) {
        $list       = new Warecorp_List_List($this->_page->_user); 
        $listsList  = $list->getListsListByTypeAssoc($listItem->getListType(), false);        
        $lastTarget = $listItem->getLastImportTargetData();        
        if ( $lastTarget ) {
            $importType = $lastTarget['import_type'];
            $this->view->importType = $importType;
            $this->view->importDate = $lastTarget['import_date'];
            $lastTarget = new Warecorp_List_Item($lastTarget['target_list_id']);
            $this->view->lastTarget = $lastTarget;
            
            switch ($importType) {
                case 'merge'    : $this->view->checkedType = 'merge';       break;
                case 'new'      : $this->view->checkedType = 'merge';       break;
                case 'watch'    : $this->view->checkedType = 'offwatch';    break;
                default         : break;                    
            }            
        } else {
            if ( $listsList ) $this->view->checkedType = 'merge';
            else $this->view->checkedType = 'new';
        }
        
        $dateObj = new Zend_Date();
        $dateObj->setTimezone($this->_page->_user->getTimezone());
        
        $form = new Warecorp_Form('list_add_form', 'POST', '');
        $this->view->listsList = $listsList;
        $this->view->form = $form;
        $this->view->list = $listItem;
        $this->view->TIMEZONE = $dateObj->get(Zend_Date::TIMEZONE);        
        $title = (!empty($importType)) ? Warecorp::t("Update Lists") : Warecorp::t("Add to My Lists");
        $Content = $this->view->getContents('search/xajax/list.add.to.my.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->content($Content);
        $popup_window->title($title);
        $popup_window->width(450)->height(200)->open($objResponse);
    } else {
        $form = new Warecorp_Form('list_add_form', 'POST', '');
        $_REQUEST['_wf__list_add_form'] = $this->params['_wf__list_add_form'];
        /* check for "save as new list" option only */
        if ( $this->params['add_type'] == 'new' ) {
            $form->addRule('title', 'notempty', Warecorp::t('Enter please New List Name'));
        }
        if ($form->validate($this->params)){
            switch ($this->params['add_type']) {
                case 'merge':
                    if ( Warecorp_List_Item :: isListExists($this->params['merge_list']) ) {
                        $listTarget = new Warecorp_List_Item($this->params['merge_list']);
                        $records = $listItem->getRecordsList();
                        $recordsTarget = $listTarget->getRecordsList();
                        $listTarget->save($listItem->getId(), 'merge', $this->_page->_user->getId());
                        if (count($records)) {
                            foreach ( $records as &$newRecord ) {
                                $tags = $newRecord->getTagsList();
                                $comments = $newRecord->getCommentsList();
                            
                                $newRecord->setId(null);
                                $newRecord->setListId($this->params['merge_list']);
                                $newRecord->setCreatorId($this->_page->_user->getId());
                                $newRecord->save();
                                if ( count($tags) ) {
                                    foreach ($tags as &$tag) $tag = $tag->getPreparedTagName();
                                    $newRecord->addTags(implode(' ', $tags));
                                }
                                if ( count($comments) ) {
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
                    $tags = $listItem->getTagsList();
                    $records = $listItem->getRecordsList();
                
                    $source_id = $listItem->getId();
                    $newList = &$listItem;
                    $newList->setId(null);
                    $newList->setTitle($this->params['title']);
                    $newList->setOwnerType('user');
                    $newList->setOwnerId($this->_page->_user->getId());
                    $newList->setCreatorId($this->_page->_user->getId());
                    $newList->setCreationDate(new Zend_Db_Expr('NOW()'));
                    $newList->save($source_id, 'new', $this->_page->_user->getId());
                
                    if ( count($tags) ) {
                        foreach ($tags as &$tag) $tag = $tag->getPreparedTagName();
                        $newList->addTags(implode(' ', $tags));
                    }
                
                    $tags = array();
                    if ( count($records) ) {
                        foreach ( $records as &$newRecord ) {
                            $tags = $newRecord->getTagsList();
                            $comments = $newRecord->getCommentsList();
                        
                            $newRecord->setId(null);
                            $newRecord->setListId($newList->getId());
                            $newRecord->setCreatorId($this->_page->_user->getId());
                            $newRecord->save();
                            if ( count($tags) ) {
                                foreach ( $tags as &$tag ) $tag = $tag->getPreparedTagName();
                                $newRecord->addTags(implode(' ', $tags));
                            }
                            if ( count($comments) ) {
                                foreach ( $comments as &$comment ) {
                                    $comment->id = null;
                                    $comment->entityId = $newRecord->getId();
                                    $comment->save();
                                }
                            }
                        }
                    }
                    break;
                case 'watch': $listItem->save($listItem->getId(), 'watch', $this->_page->_user->getId()); break;
                case 'offwatch': $listItem->offWatch(); break;
                default : break; 
            }     

            $lastImportData = $listItem->getLastImportTargetData();
            if ( $lastImportData ) $listItem->updateViewDate();        
            /* close popup */
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->close($objResponse);                    
            $objResponse->showAjaxAlert(Warecorp::t('Updated'));
        } else {
            $importType = $this->params['add_type'];
            if ( $listItem->getId() ) {        
                $list = new Warecorp_List_List($this->_page->_user); 
                $listsList = $list->getListsListByTypeAssoc($listItem->getListType(), false);        
                $lastTarget = $listItem->getLastImportTargetData();        
                if ( $lastTarget ) {
                    $importType = $lastTarget['import_type'];
                    $this->view->importType = $importType;
                    $this->view->importDate = $lastTarget['import_date'];
                    $lastTarget = new Warecorp_List_Item($lastTarget['target_list_id']);
                    $this->view->lastTarget = $lastTarget;
            
                    switch ($importType) {
                        case 'merge'    : $this->view->checkedType = 'merge';       break;
                        case 'new'      : $this->view->checkedType = 'merge';       break;
                        case 'watch'    : $this->view->checkedType = 'offwatch';    break;
                        default         : break;                    
                    }            
                } else {
                    if ($listsList) $this->view->checkedType = 'merge';
                    else $this->view->checkedType = 'new';
                }
        
                $dateObj = new Zend_Date();
                $dateObj->setTimezone($this->_page->_user->getTimezone());
        
                $this->view->listsList = $listsList;
                $this->view->form = $form;
                $this->view->list = $listItem;
                $this->view->TIMEZONE = $dateObj->get(Zend_Date::TIMEZONE);        
                $title = (!empty($importType)) ? Warecorp::t("Update Lists") : Warecorp::t("Add to My Lists");
                $Content = $this->view->getContents('search/xajax/list.add.to.my.tpl');
                
                $popup_window = Warecorp_View_PopupWindow::getInstance();        
                $popup_window->content($Content);
                $popup_window->title($title);
                $popup_window->width(450)->height(200)->open($objResponse);

                $objResponse->addAssign("radio_new", "checked", "checked");
                $objResponse->addAssign("title", "value", '');                                
            }            
        }
        
    }
        
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;  
