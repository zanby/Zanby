<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.popup.show.php.xml');

	$AccessManager = Warecorp_List_AccessManager_Factory::create();

    if (empty($group_id)) $group_id = null;
    $objResponse = new xajaxResponse();

    $list = new Warecorp_List_Item($list_id);

	$context = !empty($contextId)?Warecorp_Group_Factory::loadById(intval($contextId)):null;

    if (!$AccessManager->canShareList($list, $this->currentGroup, $this->_page->_user)) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }
    $groupsList = $this->_page->_user->getGroups()->setReturnTypes()->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))->returnAsAssoc()->getList();
    $sharedGroupsList = $list->getSharedGroups();
    foreach ( $sharedGroupsList as $shareGroup ) {
        unset($groupsList[$shareGroup->getId()]);
    }

	if (isset($groupsList[$this->currentGroup->getId()])) unset($groupsList[$this->currentGroup->getId()]);

    foreach ($groupsList as $groupId=>$groupType) {
    	$group = Warecorp_Group_Factory::loadById($groupId, $groupType);
    	if (!$AccessManager->canManageLists($group, $this->_page->_user)) {
    		unset($groupsList[$groupId]);
        } else {
            $groupsList[$groupId] = $group->getName();
        }
    }

    if (isset($groupsList[$this->currentGroup->getId()])) unset ($groupsList[$this->currentGroup->getId()]);
    $friendsList = array_flip($this->_page->_user->getFriendsList()->returnAsAssoc()->getList());

    $sharedUsersIds = array();

    $sharedUsersList = $list->getSharedUsers();

    foreach ( $sharedUsersList as $shareUser ) {
        unset($friendsList[$shareUser->getId()]);
    }

    foreach ( $sharedUsersList as $id => $shareUser ) {
        if (!$AccessManager->canManageList($list, $this->currentGroup, $this->_page->_user)) {
            unset($sharedUsersList[$id]);
        }
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
    } else {
		if (!isset($groupsList[$group_id])) $group_id = null;
	}


$familySharingList = new Warecorp_Share_List_Family();
$familySharingList
    ->setUser($this->_page->_user)
    ->returnAsAssoc(true)
    ->setContext($this->currentGroup)
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
    $this->view->familySharedWithAliases = $familySharedWithAliases;
    $this->view->list = $list;
    $this->view->sharedGroupsList = $sharedGroupsList;
    $this->view->sharedFriendsList = $sharedUsersList;
    $this->view->Warecorp_List_AccessManager = $AccessManager;


    $content = $this->view->getContents('groups/lists/share.popup.tpl');

    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Share List"));
    $popup_window->content($content);
    $popup_window->width(500)->height(350)->open($objResponse);
