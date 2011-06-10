<?php
Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentChangeFolder.php.xml');
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
    if (!$AccessManager->canViewOwnerDocuments($this->currentGroup, $folder->getOwner(), $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('summary'));
        return;
    }
    //*******************************************
    
    $this->documentChangeContent($objResponse, $folder->getOwner(), $folder_id);

    if ( false == $AccessManager->canCreateOwnerDocuments($this->currentGroup, $folder->getOwner(), $this->_page->_user->getId()) ) {
        $objResponse->addAssign("DocumentsNewFileLink", "style.visibility", "hidden");
    } else {
        $objResponse->addAssign("DocumentsNewFileLink", "style.visibility", "visible");
    }
        
    if ( false == $AccessManager->canManageOwnerDocuments($this->currentGroup, $folder->getOwner(), $this->_page->_user->getId()) ) {
        $objResponse->addAssign("DocumentsNewFolderLink", "style.visibility", "hidden");
        $objResponse->addAssign("DocumentsDeleteButton", "style.visibility", "hidden");
        $objResponse->addAssign("DocumentsMoveButton", "style.visibility", "hidden");
    } else {
        $objResponse->addAssign("DocumentsNewFolderLink", "style.visibility", "visible");
        $objResponse->addAssign("DocumentsDeleteButton", "style.visibility", "visible");
        $objResponse->addAssign("DocumentsMoveButton", "style.visibility", "visible");
    }
    
    $objResponse->addScript('DocumentApplication.initContent();');