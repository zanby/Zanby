<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentManageSharing.php.xml');
    $objResponse = new xajaxResponse();

    /* anonymous hasn't access */
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('documents');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                       
    }
    
    /* check document */
    if ( !isset($this->params['documentId']) || !$this->params['documentId'] ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t('Error') . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }
    
    /**
     * Check owner (group)
     * Choose folder for upload new document - owner will be group 
     */
    $folder_item        = new Warecorp_Document_FolderItem($this->params['folder_id']);
    $folder_owner_id    = $folder_item->getOwnerId();
    if( !empty($folder_owner_id) )                      $this->params['owner_id'] = $folder_owner_id;
    elseif(isset($this->params['owner_id']))            $this->params['owner_id'] = floor($this->params['owner_id']);
    else                                                $this->params['owner_id'] = $this->currentGroup->getId();    
    $objOwner = Warecorp_Group_Factory::loadById($this->params['owner_id']);
    $owner_id = $objOwner->getId();
        /* if owner of new document is incorrect show info message : reload opened popup with new content */
        if ( null === $objOwner->getId() ) {
            $objResponse->addScript("DocumentApplication.panelAddFile.hide();");
            $popup_window = Warecorp_View_PopupWindow::getInstance();        
            $popup_window->title(Warecorp::t('Information'));
            $popup_window->content('<p>' . Warecorp::t('Unknown error. Reload your browser and try again.') . '</p>');
            $popup_window->width(350)->height(100)->reload($objResponse);        
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;       
        } 
        
    /* Check permissions */
    if ( !Warecorp_Document_AccessManager_Factory::create()->canManageOwnerDocuments($this->currentGroup, $objOwner, $this->_page->_user->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t("You can't manage sharing") . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }
    
    /* Check document */
    $objDocument = new Warecorp_Document_Item($this->params['documentId']);
    if ( null === $objDocument->getId() ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->close($objResponse);
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;       
    }
    
    /**
     * HANDLER
     */
    /* if it is handler - do unshare document */
    if ( !empty($this->params['ownerType']) && !empty($this->params['ownerId']) ) {
        $allGroupsSharing = false;
        if ( false != ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($this->params['ownerId'])) ) {
            $allGroupsSharing = true;
            $this->params['ownerId'] = $familyId;
        }

        if ($this->params['ownerType'] == 'user')
            $owner = new Warecorp_User('id',$this->params['ownerId']);
        elseif ($this->params['ownerType'] == 'group')
            $owner = Warecorp_Group_Factory::loadById($this->params['ownerId']);

        if ( !empty($owner) && $owner->getId() && Warecorp_Document_AccessManager_Factory::create()->canUnshareDocument($objDocument, $objDocument->getOwner(), $this->_page->_user->getId()) ) {
            if ( $allGroupsSharing && Warecorp_Document_AccessManager_Factory::create()->canUnshareDocumentToAllFamilyGroups($objDocument, $owner, $this->_page->_user) ) {
                $objDocument->unshareDocument($this->params['ownerType'], $this->params['ownerId'], true);
            }
            elseif ( !$allGroupsSharing ) {
                $objDocument->unshareDocument($this->params['ownerType'], $this->params['ownerId'], false);
            }
        }
    }
    

    /* create list of groups to sharing */
    $sharedGroupsList = $objDocument->getSharedGroups();
    $familySharingList = new Warecorp_Share_List_Family();
    $familySharingList
        ->setUser($this->_page->_user)
        ->returnAsAssoc(false)
        ->setContext($this->currentGroup)
        ->setEntity($objDocument->getId(), $objDocument->EntityTypeId);

    $familySharedWithAlias = array();
    $familySharedWith = $familySharingList->getListSharedFamilies();
    if ( !empty($familySharedWith) ) {
        foreach ( $familySharedWith as $family ) {
            $familySharedWithAlias[$family->getId()] = $family->getName();
        }
        $familySharedWithAlias = Warecorp_Share_List_Family::prepeareArrayKeys($familySharedWithAlias);
        $familySharedWith = Warecorp_Share_List_Family::prepeareArrayKeysOnly($familySharedWith);
        $sharedGroupsList = (array)$familySharedWith + (array)$sharedGroupsList;
    }

    /* create list of users to sharing */
    $sharedUsersList = $objDocument->getSharedUsers();

    $this->view->document = $objDocument;
    $this->view->familySharedWithAlias = $familySharedWithAlias;
    $this->view->sharedGroupsList = $sharedGroupsList;
    $this->view->sharedFriendsList = $sharedUsersList;
    $this->view->Warecorp_Document_AccessManager = Warecorp_Document_AccessManager_Factory::create();
    $content = $this->view->getContents('groups/documents/manage.share.popup.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->title(Warecorp::t('Manage Sharing'));
    $popup_window->content($content);
    $popup_window->width(500)->height(350)->open($objResponse);   
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;               
    
