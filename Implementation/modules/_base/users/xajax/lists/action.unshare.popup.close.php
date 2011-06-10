<?php

    $objResponse = new xajaxResponse();
    
    if (!Warecorp_List_AccessManager_Factory::create()->canViewLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }
    
    $objResponse->addScript("popup_window.close();");