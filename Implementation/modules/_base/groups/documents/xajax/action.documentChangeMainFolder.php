<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentChangeMainFolder.php.xml');
    $AccessManager = Warecorp_Document_AccessManager_Factory::create();
    $objResponse = new xajaxResponse();
    
    $group = Warecorp_Group_Factory::loadById($id);
    
    //  Check permissions
    //*******************************************
    $allFamilySharing = false;
    if ( !($group->getGroupType() === 'family' && $this->currentGroup->getGroupType() === 'simple' && $AccessManager->canViewFamilySharedDocuments($this->currentGroup, $group, $this->_page->_user)) ) {
        if (!$AccessManager->canViewOwnerDocuments($this->currentGroup, $group, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('summary'));
            return;
        }
    }
    else {
        $allFamilySharing = true;
    }
    //*******************************************

    $this->documentChangeContent($objResponse, $group, null);
    /**
    if ( false == $AccessManager->canCreateOwnerDocuments($this->currentGroup, $group, $this->_page->_user->getId()) ) {
        $objResponse->addAssign("DocumentsNewFileLink", "style.visibility", "hidden");
    } else {
        $objResponse->addAssign("DocumentsNewFileLink", "style.visibility", "visible");
    }
        
    if ( false == $AccessManager->canManageOwnerDocuments($this->currentGroup, $group, $this->_page->_user->getId()) ) {
        $objResponse->addAssign("DocumentsNewFolderLink", "style.visibility", "hidden");
        $objResponse->addAssign("DocumentsDeleteButton", "style.visibility", "hidden");
        $objResponse->addAssign("DocumentsMoveButton", "style.visibility", "hidden");
    } else {
        $objResponse->addAssign("DocumentsNewFolderLink", "style.visibility", "visible");
        $objResponse->addAssign("DocumentsDeleteButton", "style.visibility", "visible");
        $objResponse->addAssign("DocumentsMoveButton", "style.visibility", "visible");
    }
    */
    
    $canCreateOwnerDocuments    = $AccessManager->canCreateOwnerDocuments($this->currentGroup, $group, $this->_page->_user->getId());
    $canManageOwnerDocuments    = $AccessManager->canManageOwnerDocuments($this->currentGroup, $group, $this->_page->_user->getId());
    
    $script = array();
    $script[] = ($canCreateOwnerDocuments) ? "$('li[menu=\"add-document\"]').show();"       : "$('li[menu=\"add-document\"]').hide();";
    $script[] = ($canManageOwnerDocuments) ? "$('li[menu=\"new-folder\"]').show();"         : "$('li[menu=\"new-folder\"]').hide();";
    $script[] = ($canManageOwnerDocuments) ? "$('li[menu=\"edit-document\"]').show();"      : "$('li[menu=\"edit-document\"]').hide();";
    $script[] = ($canManageOwnerDocuments) ? "$('li[menu=\"delete-document\"]').show();"    : "$('li[menu=\"delete-document\"]').hide();";
    $script[] = ($canManageOwnerDocuments) ? "$('li[menu=\"move-document\"]').show();"      : "$('li[menu=\"move-document\"]').hide();";
    $script[] = ($canManageOwnerDocuments || $allFamilySharing) ? "$('li[menu=\"moreactions\"]').show();"        : "$('li[menu=\"moreactions\"]').hide();";
    
    $script[] = 'DocumentApplication.menuAddDocument.kill();';
    $script[] = 'DocumentApplication.menuAddDocument.create();';
    $script[] = 'DocumentApplication.menuAddDocument.kill();';
    
    $script[] = 'DocumentApplication.menuCheckInOut.kill();';
    $script[] = 'DocumentApplication.menuCheckInOut.create();';
    $script[] = 'DocumentApplication.menuCheckInOut.kill();';
    
    $script[] = 'DocumentApplication.initContent();';
    
    $script = join('', $script);
    $objResponse->addScript($script);
    /*    
    if ( false == $AccessManager->canManageOwnerDocuments($this->currentGroup, $group, $this->_page->_user->getId()) ) {
        $objResponse->addAssign("DocumentsNewFolderLink", "style.visibility", "hidden");
        $objResponse->addAssign("DocumentsDeleteButton", "style.visibility", "hidden");
        $objResponse->addAssign("DocumentsMoveButton", "style.visibility", "hidden");
    } else {
        $objResponse->addAssign("DocumentsNewFolderLink", "style.visibility", "visible");
        $objResponse->addAssign("DocumentsDeleteButton", "style.visibility", "visible");
        $objResponse->addAssign("DocumentsMoveButton", "style.visibility", "visible");
    }
    */
    