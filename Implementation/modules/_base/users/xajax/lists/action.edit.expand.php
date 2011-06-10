<?php

    $objResponse = new xajaxResponse();
    
    if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'edit';

    if (isset($record_id) && isset($_SESSION['list_edit']['records'][$record_id])) {
        $this->listsExpandRecord($objResponse, $record_id, $_SESSION['list_edit']);
    } elseif (isset($_SESSION['list_edit']['records']) && count(isset($_SESSION['list_edit']['records']))) {
        foreach ($_SESSION['list_edit']['records'] as $record_id=>&$record) {
            $this->listsExpandRecord($objResponse, $record_id, $_SESSION['list_edit']);
        }
    }
