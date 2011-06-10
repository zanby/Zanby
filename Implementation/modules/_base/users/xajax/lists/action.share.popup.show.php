<?php
    Warecorp::addTranslation("/modules/users/xajax/lists/action.share.popup.show.php.xml");
    $objResponse = new xajaxResponse();

	$AccessManager = Warecorp_List_AccessManager_Factory::create();

    if (empty($group_id)) $group_id = null;

    $list = new Warecorp_List_Item($list_id);

    if (!$AccessManager->canShareList($list, $this->currentUser, $this->_page->_user)) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }

    $groupsList = $this->_page->_user->getGroups()->setReturnTypes()->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))->returnAsAssoc()->getList();
    $sharedGroupsList = $list->getSharedGroups();
    foreach ( $sharedGroupsList as $shareGroup ) {
        unset($groupsList[$shareGroup->getId()]);
    }

    foreach ($groupsList as $groupId=>$groupType) {
    	$group = Warecorp_Group_Factory::loadById($groupId, $groupType);
    	if (!$AccessManager->canManageLists($group, $this->_page->_user)) {
    		unset($groupsList[$groupId]);
        } else {
            $groupsList[$groupId] = $group->getName();
        }
    }

    $friendsList = array_flip($this->_page->_user->getFriendsList()->returnAsAssoc()->getList());

    $sharedUsersIds = array();

    $sharedUsersList = $list->getSharedUsers();
    foreach ( $sharedUsersList as $shareUser ) {
        unset($friendsList[$shareUser->getId()]);
    }
    
    if ($list->getOwner()->EntityTypeName == 'user') {
        unset($friendsList[$list->getOwner()->getId()]);
    }
    
    
    foreach ($friendsList as  $id=>&$friend ) {
    	$u = new Warecorp_User('id', $id);
    	$friend = $u->getLogin();
    }

	if (empty($group_id)) {
        if (!empty($groupsList)) {
        	reset($groupsList);
        	$group_id = key($groupsList);
        }
    }else{
		if (!isset($groupsList[$group_id])) $group_id = null;
	}

    $familySharingList = new Warecorp_Share_List_Family();
    $familySharingList
        ->setUser($this->_page->_user)
        ->returnAsAssoc(true)
        ->setContext($this->_page->_user)
        ->setEntity($list->getId(), $list->EntityTypeId);

    $familyNotSharedWith = $familySharingList->getListNotSharedFamilies();
    $familyNotSharedWith = Warecorp_Share_List_Family::prepeareArrayKeys($familyNotSharedWith);
    $groupsList = (array)$familyNotSharedWith + (array)$groupsList;

    $familySharedWith   = $familySharingList->returnAsAssoc(false)->getListSharedFamilies();
    $familySharedWithAliases = array();
    if ( $familySharedWith ) {
        foreach ( $familySharedWith as $family ) {
            $familySharedWithAliases[$family->getId()] = $family->getName();
        }
        $familySharedWithAliases = Warecorp_Share_List_Family::prepeareArrayKeys($familySharedWithAliases);
        $sharedGroupsList = (array)$familySharedWith + (array)$sharedGroupsList;
    }

	$this->view->groupsList = $groupsList;
    $this->view->selectedGroup = $group_id;
    $this->view->friendsList = $friendsList;
    $this->view->list = $list;
    $this->view->familySharedWithAliases = $familySharedWithAliases;
    $this->view->sharedGroupsList = $sharedGroupsList;
    $this->view->sharedFriendsList = $sharedUsersList;
    $this->view->Warecorp_List_AccessManager = $AccessManager;
    
    $content = $this->view->getContents('users/lists/share.popup.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Share List"));
    $popup_window->content($content);
    $popup_window->width(500)->height(350)->open($objResponse);
