<?php

    $objResponse = new xajaxResponse();

    $record_id = isset($record_id) ? (int)$record_id : 0;
    $record = new Warecorp_List_Record($record_id);
    $list = new Warecorp_List_Item($record->getListId());
    
    if (!Warecorp_List_AccessManager_Factory::create()->canViewList($list, $this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }

    $objResponse->addScript("unlock_content();");

    if ($record->getId()) {
        $record = new Warecorp_List_Record($record_id);
        if ($record->getId()) {
            $this->listsViewRefresh($objResponse, $record->getListId());
        }
    }