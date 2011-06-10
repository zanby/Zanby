<?php

    $objResponse = new xajaxResponse();
    
    if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $objResponse->addClear('list_errors', 'innerHTML');
    $objResponse->addAssign('list_errors', 'innerHTML', '');
    $this->view->action = 'edit';
    
    if (!isset($_SESSION['list_edit']['post_save']) || $_SESSION['list_edit']['post_save']!='change_type' || empty($_SESSION['list_edit']['change_type'])) {
        $_SESSION['list_edit']['post_save'] = 'change_type';
        $_SESSION['list_edit']['change_type'] = $type_id;
        $objResponse->addScript("xajax_list_edit_save();");
    } elseif (isset($_SESSION['list_edit'])) {
        
        $type_id = $_SESSION['list_edit']['change_type'];
        unset($_SESSION['list_edit']['post_save']);
        unset($_SESSION['list_edit']['change_type']);
        
        $list_edit = &$_SESSION['list_edit'];
        $list_edit['type'] = $type_id;
        
        $list = new Warecorp_List_Item();
        $list->setListType($list_edit['type']);
        foreach ($_SESSION['list_edit']['records'] as $id=>&$record) {
            if (isset($record['data']['item_fields'])) {
                $_xml = simplexml_import_dom($list->getXmlEmpty());
                list($_key_new,) = each($_xml);
                list($_key_old,) = each($record['data']['item_fields']);
                if ($_key_new != $_key_old) {
                    $_val = $record['data']['item_fields'][$_key_old];
                    unset($record['data']['item_fields'][$_key_old]);
                    $record['data']['item_fields'][$_key_new] = $_val;
                }
                $record['title'] = reset($record['data']['item_fields']);
                
                $record['errors'] = $list->getErrors($record['data']);
        	    if (isset($record['errors']) && count($record['errors'])) {
                    $this->view->record = $record;
        	        $output = $this->view->getContents('users/lists/errors.tpl');
                    $objResponse->addClear('record_errors_'.$id,'innerHTML');
                    $objResponse->addAssign('record_errors_'.$id,'innerHTML', $output);
        	    }
        	    $record['xml'] = $list->arrayToXml($record['data']['item_fields']);
            } else {
                $record['xml'] = $list->getXmlEmpty();
            }
            
            $this->listsExpandRecord($objResponse, $id, $_SESSION['list_edit']);
        }
    }
    session_write_close();
