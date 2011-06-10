<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.view.append.collapse.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();

	$context = !empty($contextId)?Warecorp_Group_Factory::loadById(intval($contextId)):null;
	
    $objResponse = new xajaxResponse();

    $record_id = isset($record_id) ? (int)$record_id : 0;
    $record = new Warecorp_List_Record($record_id);
    $list = new Warecorp_List_Item($record->getListId());
    
    if (!$AccessManager->canViewList($list, $this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }

    $objResponse->addScript("unlock_content();");

    if ($record->getId()) {
        $record = new Warecorp_List_Record($record_id);
        if ($record->getId()) {
            $this->listsViewRefresh($objResponse, $record->getListId());
        }
    }
