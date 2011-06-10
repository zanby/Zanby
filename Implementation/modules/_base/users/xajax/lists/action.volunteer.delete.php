<?php

    $objResponse = new xajaxResponse();
    $objResponse->addScript("unlock_content();");

    $volunteer_id = isset($volunteer_id) ? (int)$volunteer_id : 0;
    $record_id    = isset($record_id)    ? (int)$record_id : 0;
    $record = new Warecorp_List_Record($record_id);

    if (!Warecorp_List_AccessManager_Factory::create()->canDeleteVolunteer($volunteer_id, $record, $this->currentUser, $this->_page->_user->getId())) {
        return;
    }

    $this->view->action = 'view';

    if ($record->getId()) {
        $record->deleteVolunteer($volunteer_id);
        $objResponse->addRemove("volunteer_".($volunteer_id));
        $this->listsViewRefresh($objResponse, $record->getListId());
    }
