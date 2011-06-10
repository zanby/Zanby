<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.edit.unshare.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();
    
    if (!$AccessManager->canManageLists($this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'edit';
    
    if (isset($_SESSION['list_edit']) && !empty($share_id)) {
        $list_edit = &$_SESSION['list_edit'];
        if (isset($list_edit['share'][$share_id])) {
            unset($list_edit['share'][$share_id]);
            $objResponse->addRemove($share_id);
        }
    }
