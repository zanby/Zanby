<?php

    $objResponse = new xajaxResponse();
    
    if (!Warecorp_List_AccessManager_Factory::create()->canCreateLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
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
