<?php

    $objResponse = new xajaxResponse();

    if (!Warecorp_List_AccessManager_Factory::create()->canCreateLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }

    $objResponse->addScript("unlock_content();");
    $this->view->action = 'add';

    if (isset($record_id) && isset($_SESSION['list_new']['records'][$record_id])) {
        $this->listsExpandRecord($objResponse, $record_id, $_SESSION['list_new']);
    }
