<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.add.change.type.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();
    
    if (!$AccessManager->canCreateLists($this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $objResponse->addClear('list_errors', 'innerHTML');
    $objResponse->addAssign('list_errors', 'innerHTML', '');
    $this->view->action = 'add';
    
    if (!isset($_SESSION['list_new']['post_save']) || $_SESSION['list_new']['post_save']!='change_type' || empty($_SESSION['list_new']['change_type'])) {
        $_SESSION['list_new']['post_save'] = 'change_type';
        $_SESSION['list_new']['change_type'] = $type_id;
        $objResponse->addScript("xajax_list_add_save();");
    } elseif (isset($_SESSION['list_new'])) {
        
        $type_id = $_SESSION['list_new']['change_type'];
        unset($_SESSION['list_new']['post_save']);
        unset($_SESSION['list_new']['change_type']);
        
        $list_new = &$_SESSION['list_new'];
        $list_new['type'] = $type_id;
        
        $list = new Warecorp_List_Item();
        $list->setListType($list_new['type']);
        if (isset($_SESSION['list_new']['records']) && is_array($_SESSION['list_new']['records'])) {
            foreach ($_SESSION['list_new']['records'] as $id=>&$record) {
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
            	        $output = $this->view->getContents('groups/lists/errors.tpl');
                        $objResponse->addClear('record_errors_'.$id,'innerHTML');
                        $objResponse->addAssign('record_errors_'.$id,'innerHTML', $output);
            	    }
            	    $record['xml'] = $list->arrayToXml($record['data']['item_fields']);
                } else {
                    $record['xml'] = $list->getXmlEmpty();
                }
                
                $this->listsExpandRecord($objResponse, $id, $_SESSION['list_new']);
            }
        }
        if (!isset($_SESSION['list_new']['records']) || count($_SESSION['list_new']['records']) == 0) {
            $this->listsAppendRecord($objResponse, $_SESSION['list_new']);
        }
    }
    session_write_close();
