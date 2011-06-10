<?php
Warecorp::addTranslation('/modules/users/documents/xajax/action.documentChangeFolder.php.xml');
    $AccessManager = Warecorp_Document_AccessManager_Factory::create();
    $objResponse = new xajaxResponse();

    //  Check folder
    //*******************************************
    $folder_id = ( floor($folder_id) == 0 ) ? null : floor($folder_id);
    $folder = new Warecorp_Document_FolderItem($folder_id);
    if ( $folder_id === null || null === $folder->getId() ) {
        $objResponse->addScript("DocumentApplication.showInfo('Incorrect folder!');");
        return;
    }
    //*******************************************

    //  Check permissions
    //*******************************************
    if (!$AccessManager->canViewOwnerDocuments($this->currentUser, $folder->getOwner(), $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('profile'));
        return;
    }
    //*******************************************
    
    $this->documentChangeContent($objResponse, $folder->getOwner(), $folder_id);

    if ( false == $AccessManager->canCreateOwnerDocuments($this->currentUser, $folder->getOwner(), $this->_page->_user->getId()) ) {
        $objResponse->addAssign("DocumentsNewFileLink", "style.visibility", "hidden");
    } else {
        $objResponse->addAssign("DocumentsNewFileLink", "style.visibility", "visible");
    }
        
    if ( false == $AccessManager->canManageOwnerDocuments($this->currentUser, $folder->getOwner(), $this->_page->_user->getId()) ) {
        $objResponse->addAssign("DocumentsNewFolderLink", "style.visibility", "hidden");
        $objResponse->addAssign("DocumentsDeleteButton", "style.visibility", "hidden");
        $objResponse->addAssign("DocumentsMoveButton", "style.visibility", "hidden");
    } else {
        $objResponse->addAssign("DocumentsNewFolderLink", "style.visibility", "visible");
        $objResponse->addAssign("DocumentsDeleteButton", "style.visibility", "visible");
        $objResponse->addAssign("DocumentsMoveButton", "style.visibility", "visible");
    }
    
    $objResponse->addScript('DocumentApplication.initContent();');