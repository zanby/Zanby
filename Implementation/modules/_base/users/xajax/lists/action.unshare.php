<?php
$objResponse = new xajaxResponse();

$allGroupsSharing = false;
if ( false != ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($owner_id)) ) {
    $allGroupsSharing = true;
    $owner_id = $familyId;
}

$AccessManager = Warecorp_List_AccessManager_Factory::create();
$list = new Warecorp_List_Item($list_id);

if (!$AccessManager->canUnshareList($list, $this->currentUser, $this->_page->_user->getId())) {
    $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
    return;
}

if ( $allGroupsSharing ) {
    $family = new Warecorp_Group_Family('id', $owner_id);
    if ( $family && $family->getId() && $AccessManager->canUnshareListToAllFamilyGroups($list, $family, $this->_page->_user) ) {
        $list->unshareList($owner_type, $owner_id, true);
    }
    else {
        $objResponse->addRedirect($this->currentUser->getGroupPath('lists'));
        return;
    }
}
else {
    $list->unshareList($owner_type, $owner_id, false);
}
$objResponse = $this->listsSharePopupShowAction($list_id);