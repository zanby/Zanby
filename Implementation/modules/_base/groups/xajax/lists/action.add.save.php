<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.add.save.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();
    
    if (!$AccessManager->canCreateLists($this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'add';

    if ( $record_id !== null && isset($_SESSION['list_new'])) {
        
        $list_new = &$_SESSION['list_new'];
        
        if ( isset($list_new['records']) && isset($list_new['type']) && count($list_new['records']) ) {
            $list = new Warecorp_List_Item();
            $list->setListType($list_new['type']);
            $rec_ids = array($record_id);
                
            if (isset($list_new['records'][$record_id])) {
                $list_new['records'][$record_id]['data'] = $data;
                $_empty = true;
                if (isset($data['item_fields'])) {
                    $list_new['records'][$record_id]['title'] = reset($data['item_fields']);
                    foreach ($data['item_fields'] as $field) if (!empty($field)) $_empty = false;
                }
                if ($_empty && empty($data['item_entry']) && empty($data['item_tags'])) {
                    if (is_int($record_id) || !isset($list_new['post_save']) || $list_new['post_save']=='publish' || $list_new['post_save']=='change_type') {
                        $this->listsDeleteRecord($objResponse, $record_id, $list_new);
                    }
                }
            }

            $_error = $this->listsVerify($list_new);
            foreach ($list_new['records'] as $record_id=>&$record) {
                if ( in_array($record_id, $rec_ids) ) {
                    if (empty($record['errors'])) {
                        $this->listsCollapseRecord($objResponse, $record_id, $list_new);
                    } else {
                        $this->listsExpandRecord($objResponse, $record_id, $list_new);
                    }
                }
            }
        }
        if (isset($list_new['post_save'])) {
            switch ($list_new['post_save']) {
                case 'change_type' : 
                    $objResponse->addScript("xajax_list_add_change_type({$list_new['type']});");
                    break;
                case 'publish' : 
                    if (isset($_error) && $_error) {
                        unset($list_new['post_save']);
                    } else {
                        $objResponse->addScript("xajax_list_add_publish();");
                    }
                    break;
                case 'add_record' :
                        unset($list_new['post_save']);
                        if (empty($_error)) $this->listsAppendRecord($objResponse, $list_new);
                    break;
                default:
                    break;
            }
        }
    } else {
        if (isset($_SESSION['list_new'])) $list_new = &$_SESSION['list_new']; else $list_new = array();
        $_expanded = array();
        if ( isset($list_new['records']) && isset($list_new['type']) && is_array($list_new['records']) ) {
            foreach ($list_new['records'] as $id=>&$record) {
                if ($record['status'] == 'expanded') {
                    $_expanded[] = "{$id}, xajax.getFormValues('record_form_{$id}')";
                }
            }
            if ($_expanded) {
                $objResponse->addScript("xajax_list_add_save(".implode(", ",$_expanded).")");
                if (empty($list_new['post_save'])) $list_new['post_save'] = "add_record";
            } elseif (!empty($list_new['post_save']) && $list_new['post_save'] == 'publish')  {
                $objResponse->addScript("xajax_list_add_publish();");
            } else {
                $this->listsAppendRecord($objResponse, $list_new);
            }
        }

    }
