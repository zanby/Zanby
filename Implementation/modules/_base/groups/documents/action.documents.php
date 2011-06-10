<?php
    Warecorp::addTranslation('/modules/groups/documents/action.documents.php.xml');
    $AccessManager = Warecorp_Document_AccessManager_Factory::create();
        
    if ( !$AccessManager->canViewOwnerDocuments($this->currentGroup, $this->currentGroup, $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }  

    $this->_page->Xajax->registerUriFunction("change_main_folder", "/groups/documentChangeMainFolder/");
    $this->_page->Xajax->registerUriFunction("change_folder", "/groups/documentChangeFolder/");
    $this->_page->Xajax->registerUriFunction("sort_files", "/groups/documentSort/");
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");

    //  Build Folders Tree
    //**************************************************************
    $treeObj = $this->currentGroup->getArtifacts()->createDocumentTree();
    $treeObj->setCallbackFunction('DocumentApplication.changeActiveFolder');

    $tree = $treeObj->startTree('tree_0', 'tree_div_0');
    
    $groups = array();
    if ($this->currentGroup instanceof Warecorp_Group_Family) {
        $groups = $this->currentGroup->getGroups()->setTypes(array("simple"))->getList();
    }
    array_unshift($groups,$this->currentGroup);
    
    $allowed_groups = array();
    if ( sizeof($groups) != 0 ) {
        foreach ( $groups as $group ) {
            if ( $AccessManager->canViewOwnerDocuments($this->currentGroup, $group, $this->_page->_user->getId()) ) {
                $tmpTreeObj = $group->getArtifacts()->createDocumentTree();
                $tmpTreeObj->setShowDocuments(false);
                $tmpTreeObj->setShowMainFolder(true);
                $tmpTreeObj->setMainFolderName($group->getName());
                $tmpTreeObj->setMainCallbackFunction('DocumentApplication.cangeActiveMainFolder');
                $tmpTreeObj->setShowShared(false);
                $tree .= $tmpTreeObj->buildTree('tree_0');
                $allowed_groups[] = $group;
            }
        }
    }

    $families = array();
    if ( $this->currentGroup->getGroupType() !== 'family' ) {
        $families = $this->currentGroup->getFamilyGroups()->returnAsAssoc(false)->getList();
    }
    if ( sizeof($families) != 0 ) {
        foreach ( $families as $group ) {
            if ( $AccessManager->canViewFamilySharedDocuments($this->currentGroup, $group, $this->_page->_user) ) {
                $tmpTreeObj = $group->getArtifacts()->createDocumentTree();
                $tmpTreeObj->setShowDocuments(false);
                $tmpTreeObj->setShowMainFolder(true);
                $tmpTreeObj->setMainFolderName($group->getName());
                $tmpTreeObj->setMainCallbackFunction('DocumentApplication.cangeActiveMainFolder');
                $tmpTreeObj->setShowShared(false);
                $tmpTreeObj->setBuildChildren(false);
                $tree .= $tmpTreeObj->buildTree('tree_0');
                $allowed_groups[] = $group;
            }
        }
    }

    $groups = $allowed_groups;
    unset($allowed_groups);
    $tree .= $treeObj->endTree('tree_0');
    $this->view->DocumentTreeJS = $tree;
    //**************************************************************

    //  Build Current Folder List
    //**************************************************************
    $currGroup = $groups[0];
    $folder_id = isset($this->params['folder']) ? (int)floor($this->params['folder']) : null;
    $privacy = array();
    $shared = false;
    if ( $AccessManager->canViewPublicDocuments($this->currentGroup, $currGroup, $this->_page->_user->getId()) ) {
        $privacy[] = 0;
    }
    if ( $AccessManager->canViewPrivateDocuments($this->currentGroup, $currGroup, $this->_page->_user->getId()) ) {
        $privacy[] = 1;
        $shared = true;
    }

    $s = &$_SESSION['documents']['group']['order'][$this->currentGroup->getId()];
    $s['files'] = "original_name asc";
    $s['dirs']  = "name asc";
    
    $listObj = $currGroup->getArtifacts()->createDocumentList();
    $listObj->setCurrentUser($this->_page->_user);
    $listObj->setFolder($folder_id);
    $listObj->setPrivacy($privacy);
    $listObj->setShowShared($privacy);
    $listObj->setOrder($s['files']);
    $documentsList = $listObj->getList($this->_page->_user);

    foreach($documentsList as &$doc) {
        $ddd = new Warecorp_Document_Item($doc->getId());
        $doc->setDescription($ddd->getDescription());
    }
    
    $foldersList = $currGroup->getArtifacts()->getDocumentsFoldersList()->setFolder($folder_id)->setOrder($s['dirs'])->getList();
    $currentFolder = new Warecorp_Document_FolderItem($folder_id);

    //  Used in template for displaying "More Actions" button
    $allFamilySharing = false;
    if ( $this->currentGroup->getGroupType() === 'family' && $this->_page->_user->getId() ) {
        $families = $this->_page->_user->getGroups()
            ->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY)
            ->setMembersStatus(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED)
            ->returnAsAssoc(true)
            ->getList();
        if ( !empty($families) && in_array($this->currentGroup->getId(), array_keys($families)) ) {
            $allFamilySharing = true;
        }
    }
    //  End Used in template for displaying "More Actions" button

    $this->view->allFamilySharing = $allFamilySharing;
    $this->view->Warecorp = new Warecorp();
    $this->view->currGroup = $currGroup;
    $this->view->foldersList = $foldersList;
    $this->view->currentFolder = $currentFolder;
    $this->view->documentsList = $documentsList;
    $this->view->AccessManager = $AccessManager;
    $this->view->errors = array(Warecorp::t('Please select file to upload'));
    $this->view->upload_max_filesize = DOCUMENTS_SIZE_LIMIT;    
    $this->view->bodyContent = 'groups/documents/documents.tpl';
    
    /*
    if(LOCALE == "rss"){
        include_once(ENGINE_DIR."/rss.class.php");
        $rss = new UniversalFeedCreator();
        $rss->encoding = 'utf-8';
        $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';  
        $rss->link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $rss->title = $this->currentGroup->getName() . " documents ";
        $rss->description = $this->currentGroup->getName() . " documents ";
        $rss->copyright = "Copyright &copy; 2007, Zanby";
        $listObj->resetList();
        $documentsList = $this->currentGroup->getArtifacts()->createDocumentList()
                              ->setShowShared($shared)->setFromAllFolders(true)->setOrder('vdl.creation_date DESC')->getList();
        if ( sizeof($documentsList) != 0 ) {      
            foreach ($documentsList as $document){
                if (!$document->getPrivate()) {
                    $item = new FeedItem();
                    $item->title = $document->getOriginalName();
                    $item->link = "http://".$_SERVER['HTTP_HOST'] . "/" . "en/docget/docid/" . $document->getId() . "/";
                    $item->description = $document->getDescription();
                    $rss->addItem($item);
                }
            }
        }
        header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
        print $rss->createFeed("RSS2.0");
        exit;
    }
    */
    
