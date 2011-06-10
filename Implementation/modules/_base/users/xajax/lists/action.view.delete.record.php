<?php
    Warecorp::addTranslation("/modules/users/xajax/lists/action.view.delete.php.xml");

    $objResponse = new xajaxResponse();
    $objResponse->addScript("unlock_content();");

    $record_id = isset($record_id) ? (int)$record_id : null;
    $record = new Warecorp_List_Record($record_id);
    $objResponse->addScript('popup_window.close();');

    if (!Warecorp_List_AccessManager_Factory::create()->canManageRecord($record, $this->currentUser, $this->_page->_user->getId())) {
        return;
    }

    $this->view->action = 'view';

    if ($record->getId()) {
        $list = new Warecorp_List_Item($record->getListId());
        $objResponse->addRemove("item_".($record->getId()));
        $record->delete();
        $recordsCount = ($list->getRecordsCount()!=1) ?
            Warecorp::t("There are %s items in this list", array($list->getRecordsCount())) :
            Warecorp::t("There is %s item in this list", array($list->getRecordsCount()));
        $objResponse->addAssign("records_count",'innerHTML',  $recordsCount);
        $this->listsViewRefresh($objResponse, $record->getListId());
    }
