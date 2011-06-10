<?php
    Warecorp::addTranslation('/modules/users/documents/xajax/action.documentChangeContent.php.xml');

    //  Check permissions        
    //*******************************************
    if (!Warecorp_Document_AccessManager_Factory::create()->canViewOwnerDocuments($this->currentUser, $objOwner, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('profile'));
        return;
    }
    //*******************************************
    
    //  Refresh Content
    $privacy = array();
    $shared = false;
    if ( Warecorp_Document_AccessManager_Factory::create()->canViewPublicDocuments($this->currentUser, $objOwner, $this->_page->_user->getId()) ) {
        $privacy[] = 0;
    }
    if ( Warecorp_Document_AccessManager_Factory::create()->canViewPrivateDocuments($this->currentUser, $objOwner, $this->_page->_user->getId()) ) {
        $privacy[] = 1;
        $shared = true;
    }
    $s = &$_SESSION['documents']['user']['order'][$this->currentUser->getId()];
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
    $documentsList = $listObj->getList($this->_page->_user);

    $foldersList = $objOwner->getArtifacts()
                         ->getDocumentsFoldersList()
                         ->setFolder($currentFolderId)->setOrder($s['dirs'])->getList();
    $currentFolder = new Warecorp_Document_FolderItem($currentFolderId);
    
    $this->view->currUser = $objOwner;
    $this->view->foldersList = $foldersList;
    $this->view->currentFolder = $currentFolder;
    $this->view->documentsList = $documentsList;
    $this->view->AccessManager = Warecorp_Document_AccessManager_Factory::create();
    $content = $this->view->getContents('users/documents/documents.content.template.tpl');
    $objResponse->addAssign('document_tree_content', 'innerHTML', $content);
    if ($documentsList || $foldersList) $objResponse->addAssign('document_tree_content', 'className', 'znbMyDocsFilesFrame');
    else $objResponse->addAssign('document_tree_content', 'className', 'znbMyDocsNodocsFrame');
    
    if ( Warecorp_Document_AccessManager_Factory::create()->canManageOwnerDocuments($this->currentUser, $objOwner, $this->_page->_user->getId()) ) {
        $objResponse->addScript('DocumentApplication.initDranDrop();');
    }
    
    return $objResponse;
