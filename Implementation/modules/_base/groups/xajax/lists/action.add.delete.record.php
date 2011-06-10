<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.add.delete.record.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();
    
    if (!$AccessManager->canCreateLists($this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'add';
    
    if (isset($_SESSION['list_new']) && isset($record_id) && isset($_SESSION['list_new']['records'][$record_id])){
        $list_new = &$_SESSION['list_new'];
        $this->listsDeleteRecord($objResponse, $record_id, $list_new);
    }
