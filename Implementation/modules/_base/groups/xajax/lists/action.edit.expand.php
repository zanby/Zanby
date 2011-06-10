<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.edit.expand.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();

//     if (!$AccessManager->canManageLists($this->currentGroup, $this->_page->_user->getId())) {
//         $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
//         return;
//     }

    $objResponse->addScript("unlock_content();");
    $this->view->action = 'edit';

	if (isset($_SESSION['list_edit'])){
	    $_list = new Warecorp_List_Item($_SESSION['list_edit']['id']);
	    if (!$AccessManager->canManageList($_list, $this->currentGroup, $this->_page->_user->getId())) {
    	    $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
       	 	return;
    	}
		if (!empty($record_id) && isset($_SESSION['list_edit']['records'][$record_id])) {
	        $this->listsExpandRecord($objResponse, $record_id, $_SESSION['list_edit']);
	    } elseif (isset($_SESSION['list_edit']['records']) && count(isset($_SESSION['list_edit']['records']))) {
	        foreach ($_SESSION['list_edit']['records'] as $record_id=>&$record) {
	            $this->listsExpandRecord($objResponse, $record_id, $_SESSION['list_edit']);
	        }
	    }
    }
	
