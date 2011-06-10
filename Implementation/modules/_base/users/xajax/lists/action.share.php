<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.share.php.xml');
$objResponse = new xajaxResponse();

$allGroupsSharing = false;
if ( false != ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($owner_id)) ) {
    $allGroupsSharing = true;
    $owner_id = $familyId;
}

$AccessManager = Warecorp_List_AccessManager_Factory::create();
$list = new Warecorp_List_Item($list_id);

if (!$AccessManager->canShareList($list, $this->currentUser, $this->_page->_user)) {
    $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
    return;
}

if ($owner_type == 'group') {
    $group = Warecorp_Group_Factory::loadById($owner_id);

    if ( $allGroupsSharing && $group->getGroupType() == 'family' ) {
        if ( $AccessManager->canShareListToAllFamilyGroups($list, $group, $this->_page->_user) ) {
            $list->shareList($owner_type, $owner_id, true);
        }
    }
    elseif ( !$allGroupsSharing ) {
        $list->shareList($owner_type, $owner_id, false);
    }
}
else {
    $list->shareList($owner_type, $owner_id, false);
}
$objResponse = $this->listsSharePopupShowAction($list_id);
