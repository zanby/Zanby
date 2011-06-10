<?php

    $objResponse = new xajaxResponse();
    
    if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'edit';
    
    if (isset($_SESSION['list_edit']) && isset($record_id) && isset($_SESSION['list_edit']['records'][$record_id])){
        $list_edit = &$_SESSION['list_edit'];
        $this->listsDeleteRecord($objResponse, $record_id, $list_edit);
    }
