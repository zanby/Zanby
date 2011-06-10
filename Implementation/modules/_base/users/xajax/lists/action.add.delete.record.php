<?php

    $objResponse = new xajaxResponse();
    
    if (!Warecorp_List_AccessManager_Factory::create()->canCreateLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'add';
    
    if (isset($_SESSION['list_new']) && isset($record_id) && isset($_SESSION['list_new']['records'][$record_id])){
        $list_new = &$_SESSION['list_new'];
        $this->listsDeleteRecord($objResponse, $record_id, $list_new);
    }
