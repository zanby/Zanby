<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.add.list.popup.show.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();

    $list_id = isset($list_id) ? $list_id : 0;
    $listItem = new Warecorp_List_Item($list_id);
    
    if (!$AccessManager->canViewList($listItem, $this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }
    
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
        
        $form = new Warecorp_Form('search_list', 'POST', '');
        $this->view->listsList = $listsList;
        $this->view->form = $form;
        $this->view->list = $listItem;
        $this->view->TIMEZONE = $dateObj->get(Zend_Date::TIMEZONE);
        
        $title = (!empty($importType)) ? Warecorp::t("Update Lists") 
                                        : Warecorp::t("Add to My Lists");
        $content = $this->view->getContents('groups/lists/add.popup.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t('Add List'));
        $popup_window->content($content);
        $popup_window->width(500)->height(350)->open($objResponse);

        
    }
