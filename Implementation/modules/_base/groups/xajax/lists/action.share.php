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
$context = !empty($contextId) ? Warecorp_Group_Factory::loadById(intval($contextId)):null;

if (!$AccessManager->canShareList($list, $this->currentGroup, $this->_page->_user)) {
    $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
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
