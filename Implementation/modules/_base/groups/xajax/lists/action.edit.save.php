<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.edit.save.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();

    $objResponse = new xajaxResponse();
    
    if (isset($_SESSION['list_edit'])) {
        $editList = new Warecorp_List_Item($_SESSION['list_edit']['id']); 
        if (!$AccessManager->canManageList($editList, $this->currentGroup, $this->_page->_user->getId())) {
			$objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
            return;
        }        
    } else {
        if (!$AccessManager->canManageLists($this->currentGroup, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
            return;
        }
    }
  
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'edit';

    if ( $record_id !== null && isset($_SESSION['list_edit'])) {
        
        $list_edit = &$_SESSION['list_edit'];
        
        if ( isset($list_edit['records']) && isset($list_edit['type']) && count($list_edit['records']) ) {
            $list = new Warecorp_List_Item();
            $list->setListType($list_edit['type']);
            $rec_ids = array($record_id);
            if (isset($list_edit['records'][$record_id])) {
                $list_edit['records'][$record_id]['data'] = $data;
                $_empty = true;
                if (isset($data['item_fields'])) {
                    $list_edit['records'][$record_id]['title'] = reset($data['item_fields']);
                    foreach ($data['item_fields'] as $field) if (!empty($field)) $_empty = false;
                }
                if ($_empty && empty($data['item_entry']) && empty($data['item_tags'])) {
                    if (is_int($record_id) || !isset($list_edit['post_save']) || $list_edit['post_save']=='publish' ) {
                        $this->listsDeleteRecord($objResponse, $record_id, $list_edit);
                    }
                }
            }

            $_error = $this->listsVerify($list_edit);
            foreach ($list_edit['records'] as $record_id=>&$record) {
                if ( in_array($record_id, $rec_ids) ) {
            	    if (empty($record['errors'])) {
                        $this->listsCollapseRecord($objResponse, $record_id, $list_edit);
            	    } else {
            	        $this->listsExpandRecord($objResponse, $record_id, $list_edit);
            	    }
                }
            }
        }
        if (isset($list_edit['post_save'])) {
            switch ($list_edit['post_save']) {
                case 'change_type' : 
                    $objResponse->addScript("xajax_list_edit_change_type({$list_edit['type']});");
                    break;
                case 'publish' : 
                    if (isset($_error) && $_error) {
                        unset($list_edit['post_save']);
                    } else {
                        $objResponse->addScript("xajax_list_edit_publish();");
                    }
                    break;
                case 'add_record' :
                        unset($list_edit['post_save']);
                        if (empty($_error)) $this->listsAppendRecord($objResponse, $list_edit);
                    break;
                default:
                    break;
            }
        }
    } else {
        if (isset($_SESSION['list_edit'])) $list_edit = &$_SESSION['list_edit']; else $list_edit = array();
        $_expanded = array();
        if ( isset($list_edit['records']) && isset($list_edit['type']) && is_array($list_edit['records']) ) {
            foreach ($list_edit['records'] as $id=>&$record) {
                if ($record['status'] == 'expanded') {
                    $_expanded[] = "{$id}, xajax.getFormValues('record_form_{$id}')";
                }
            }
            if ($_expanded) {
                $objResponse->addScript("xajax_list_edit_save(".implode(", ",$_expanded).")");
                if (empty($list_edit['post_save'])) $list_edit['post_save'] = "add_record";
            } elseif (!empty($list_edit['post_save']) && $list_edit['post_save'] == 'publish')  {
                $objResponse->addScript("xajax_list_edit_publish();");
            } else {
                $this->listsAppendRecord($objResponse, $list_edit);
            }
        }
    }
