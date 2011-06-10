<?php

    $objResponse = new xajaxResponse();
    
    if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $objResponse->addScript("xajax_reload_share_whom();");
    $this->view->action = 'edit';
    
    if (isset($_SESSION['list_edit']) && !empty($share_id)) {
        $list_edit = &$_SESSION['list_edit'];
        if (isset($list_edit['share'][$share_id])) {
        	list($target, $id) = explode('_', $share_id);
        	switch ($target) {
                case "u":
                     $list_edit['canshareusers'][$id] = $list_edit['share'][$share_id];
                	 break;
                case "g":  
                	 $list_edit['cansharegroups'][$id] = $list_edit['share'][$share_id];
                	 break;
                default:
                     break;                	               	
        	}
            unset($list_edit['share'][$share_id]);
            $objResponse->addRemove($share_id);
        }
    }
