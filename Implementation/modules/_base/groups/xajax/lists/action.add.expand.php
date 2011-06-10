<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.add.expand.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();

    if (!$AccessManager->canCreateLists($this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }

    $objResponse->addScript("unlock_content();");
    $this->view->action = 'add';

    if (isset($record_id) && isset($_SESSION['list_new']['records'][$record_id])) {
        $this->listsExpandRecord($objResponse, $record_id, $_SESSION['list_new']);
    }
