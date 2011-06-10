<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentChangeContent.php.xml');

    //  Check permissions        
    //*******************************************
    $AccessManager = Warecorp_Document_AccessManager_Factory::create();
    $allFamilySharing = false;
    if ( !($objOwner->getGroupType() === 'family' && $this->currentGroup->getGroupType() === 'simple' && $AccessManager->canViewFamilySharedDocuments($this->currentGroup, $objOwner, $this->_page->_user)) ) {
        if (!$AccessManager->canViewOwnerDocuments($this->currentGroup, $objOwner, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('summary'));
            return;
        }
    }
    else {
        $allFamilySharing = true;
    }
    //*******************************************

    if ( !$allFamilySharing ) {

        //  Refresh Content
        $privacy = array();
        $shared = false;
        if ( $AccessManager->canViewPublicDocuments($this->currentGroup, $objOwner, $this->_page->_user->getId()) ) {
            $privacy[] = 0;
        }
        if ( $AccessManager->canViewPrivateDocuments($this->currentGroup, $objOwner, $this->_page->_user->getId()) ) {
            $privacy[] = 1;
            $shared = true;
        }
        $s = &$_SESSION['documents']['group']['order'][$this->currentGroup->getId()];
        if (empty($s['files']) || empty($s['dirs'])) {
            $s['files'] = "original_name asc";
            $s['dirs']  = "name asc";
        }
        $listObj = $objOwner->getArtifacts()->createDocumentList();
        $listObj->setCurrentUser($this->_page->_user)
                ->setFolder($currentFolderId)
                ->setPrivacy($privacy)
                ->setShowShared($shared)
                ->setOrder($s['files']);
        $documentsList = $listObj->getList();

        $foldersList = $objOwner
            ->getArtifacts()
            ->getDocumentsFoldersList()
            ->setFolder($currentFolderId)
            ->setOrder($s['dirs'])
            ->getList();
        $currentFolder = new Warecorp_Document_FolderItem($currentFolderId);
    }
    else {
        //  Refresh Content
        $s = &$_SESSION['documents']['group']['order'][$this->currentGroup->getId()];
        if (empty($s['files']) || empty($s['dirs'])) {
            $s['files'] = "original_name asc";
            $s['dirs']  = "name asc";
        }
        $listObj = $this->currentGroup->getArtifacts()->createDocumentList();
        $listObj->setSharedToGroup($this->currentGroup)
                ->setCurrentUser($this->_page->_user)
                ->setFolder($currentFolderId)
                ->setSharedAllFamilyChildrenOnly($objOwner->getId())
                ->setOrder($s['files']);
        $documentsList = $listObj->getList();

        $foldersList = array();
        $currentFolder = new Warecorp_Document_FolderItem($currentFolderId);
    }

    $this->view->currGroup = $objOwner;
    $this->view->foldersList = $foldersList;
    $this->view->currentFolder = $currentFolder;
    $this->view->documentsList = $documentsList;
    $this->view->AccessManager = Warecorp_Document_AccessManager_Factory::create();
    $content = $this->view->getContents('groups/documents/documents.content.template.tpl');
    $objResponse->addAssign('document_tree_content', 'innerHTML', $content);
    if ($documentsList || $foldersList)
        $objResponse->addAssign('document_tree_content', 'className', 'znbMyDocsFilesFrame');
    else
        $objResponse->addAssign('document_tree_content', 'className', 'znbMyDocsNodocsFrame');
    
    if ( $AccessManager->canManageOwnerDocuments($this->currentGroup, $objOwner, $this->_page->_user->getId()) ) {
        $objResponse->addScript('DocumentApplication.initDranDrop();');
    }
    
    return $objResponse;
