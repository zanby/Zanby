<?php

    $objResponse = new xajaxResponse();
    
    $list_id = isset($list_id) ? (int)$list_id : 0;
    $list = new Warecorp_List_Item($list_id);
    
    if (!Warecorp_List_AccessManager_Factory::create()->canViewList($list, $this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'view';

    if (isset($order) && isset($list_id)) {
        $_SESSION['list_view'][$list_id]['order'] = $order;
        $this->listsViewRefresh($objResponse, $list_id);
    }
