<?php
    Warecorp::addTranslation('/modules/users/documents/xajax/action.documentShareFile.php.xml');
    $objResponse = new xajaxResponse();

    /* anonymous hasn't access */
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentUser->getUserPath('documents');
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
    else                                                $this->params['owner_id'] = $this->currentUser->getId();    
    $objOwner = new Warecorp_User('id', $this->params['owner_id']);
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
    if ( !Warecorp_Document_AccessManager_Factory::create()->canManageOwnerDocuments($this->currentUser, $objOwner, $this->_page->_user->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t("You can't share this file") . '</p>');
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
    
    /* if it is handler - do share document */
    $allGroupsSharing = false;
    if ( !empty($this->params['ownerType']) && !empty($this->params['ownerId']) ) {
        if ($this->params['ownerType'] == 'user') {
            $owner = new Warecorp_User('id', $this->params['ownerId']);
        }
        elseif ($this->params['ownerType'] == 'group') {
            $allGroupsSharing = false;
            if ( false != ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($this->params['ownerId'])) ) {
                $allGroupsSharing = true;
                $this->params['ownerId'] = $familyId;
            }

            $owner = Warecorp_Group_Factory::loadById($this->params['ownerId']);
        }

        $AccessManager = Warecorp_Document_AccessManager_Factory::create();
        if ( isset($owner) && $owner->getId() ) {
            if ( $allGroupsSharing && $AccessManager->canShareDocumentToAllFamilyGroups($objDocument, $owner, $this->_page->_user) )
                $objDocument->shareDocument($this->params['ownerType'], $owner->getId(), true);
            elseif ( !$allGroupsSharing && $AccessManager->canShareDocument($objDocument, $owner, $this->_page->_user->getId())  ) {
                $objDocument->shareDocument($this->params['ownerType'], $owner->getId(), false);
            }
        }
    }
    
    
    /* create list of groups to sharing */
    $groupsList = $this->_page->_user->getGroups()->setReturnTypes()->returnAsAssoc()->getList();    
    if(isset($groupsList[$objDocument->getOwner()->getId()])) unset($groupsList[$objDocument->getOwner()->getId()]);
        /* exclude groups that document is shared in */
        $sharedGroupsList = $objDocument->getSharedGroups();
        foreach ( $sharedGroupsList as $shareGroup ) {
            unset($groupsList[$shareGroup->getId()]);
        }
        /* exclude curretn group */
        if ($objDocument->getOwner() instanceof Warecorp_Group_Base && $objDocument->getOwner()->getId() == $this->currentUser->getId()) { 
            unset($groupsList[$this->currentUser->getId()]);
        }
        /* check if user can share to grous*/
        foreach ($groupsList as $groupId=>$groupType) {
            $group = Warecorp_Group_Factory::loadById($groupId, $groupType);
            if (!Warecorp_Document_AccessManager_Factory::create()->canManageGroupDocuments($group, $this->_page->_user->getId())) {
                unset($groupsList[$groupId]);
            } else {
                $groupsList[$groupId] = $group;
            }
        }

        $familySharingList = new Warecorp_Share_List_Family();
        $familySharingList
            ->setUser($this->_page->_user)
            ->returnAsAssoc(false)
            ->setContext($this->_page->_user)
            ->setEntity($objDocument->getId(), $objDocument->EntityTypeId);

        $familyNotSharedWithAlias = array();
        $familyNotSharedWith = $familySharingList->getListNotSharedFamilies();
        if ( !empty($familyNotSharedWith) ) {
            foreach ( $familyNotSharedWith as $family ) {
                $familyNotSharedWithAlias[$family->getId()] = $family->getName();
            }
            $familyNotSharedWithAlias = Warecorp_Share_List_Family::prepeareArrayKeys($familyNotSharedWithAlias);
            $familyNotSharedWith = Warecorp_Share_List_Family::prepeareArrayKeysOnly($familyNotSharedWith);
            $groupsList = (array)$familyNotSharedWith + (array)$groupsList;
        }

        $familySharedWith   = $familySharingList
            ->returnAsAssoc(true)
            ->getListSharedFamilies();
        $familySharedWith = Warecorp_Share_List_Family::prepeareArrayKeys($familySharedWith);
        $sharedGroupsList = (array)$familySharedWith + (array)$sharedGroupsList;
    /* create list of users to sharing */
    $friendsList = array_flip($this->_page->_user->getFriendsList()->returnAsAssoc()->getList());
    $sharedUsersIds = array();
        /* exclude users that document is shared in */
        $sharedUsersList = $objDocument->getSharedUsers();
        foreach ( $sharedUsersList as $shareUser ) {
            unset($friendsList[$shareUser->getId()]);
        }
        /* create user object */
        foreach ($friendsList as  $id=>&$friend ) {
            $u = new Warecorp_User('id', $id);
            $friend = $u->getLogin();
        }

    $this->view->groupsList = $groupsList;
    $this->view->friendsList = $friendsList;
    $this->view->familyNotSharedWithAlias = $familyNotSharedWithAlias;
    $this->view->document = $objDocument;
    $this->view->sharedGroupsList = $sharedGroupsList;
    $this->view->sharedFriendsList = $sharedUsersList;
    $this->view->Warecorp_Document_AccessManager = Warecorp_Document_AccessManager_Factory::create();
    $content = $this->view->getContents('users/documents/share.popup.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->title(Warecorp::t('Share Document'));
    $popup_window->content($content);
    $popup_window->width(500)->height(350)->open($objResponse);   
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;               
    
