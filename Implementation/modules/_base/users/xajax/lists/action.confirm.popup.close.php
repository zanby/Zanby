<?php
    
    $objResponse = new xajaxResponse();
    
    $list =(isset($data['list_id'])) ? new Warecorp_List_Item($data['list_id']) : new Warecorp_List_Item();
    $action = isset($data['action']) ? $data['action'] : "";

    switch ($action) {
        case 'offwatch':
            if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
                return;
            }
            break;
        case 'unshare':
            if (!Warecorp_List_AccessManager_Factory::create()->canUnshareList($list, $this->currentUser, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
                return;
            }
            break;
        case 'delete':
            if (!Warecorp_List_AccessManager_Factory::create()->canManageList($list, $this->currentUser, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
                return;
            }
            break;
        default: 
            $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
            return;
            break;
    }
    
    if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }
    
    if (isset($data['action']) && $list->getId()) {
        switch ($data['action']) {
            case 'offwatch':
                $list->offWatch();
                break;
            case 'delete':
                $list->delete();                
                break;
            case 'unshare':
                $list->unshareList('user', $this->currentUser->getId());                
                break;
            default:
                $objResponse->addScript('popup_window.close();');
                break;
        }
        $objResponse->addScript("document.location.reload();");
    } else {
        $objResponse->addScript('popup_window.close();');
    }