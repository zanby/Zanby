<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.add.unshare.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();
    
    if (!$AccessManager->canCreateLists($this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'add';
    
    if (isset($_SESSION['list_new']) && !empty($share_id)) {
        $list_new = &$_SESSION['list_new'];
        if (isset($list_new['share'][$share_id])) {
            unset($list_new['share'][$share_id]);
            $objResponse->addRemove($share_id);
        }
    }
