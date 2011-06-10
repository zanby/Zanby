<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.unshare.php.xml');
$objResponse = new xajaxResponse();

$AccessManager = Warecorp_List_AccessManager_Factory::create();
$list = new Warecorp_List_Item($list_id);

$allGroupsSharing = false;
if ( false != ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($owner_id)) ) {
    $allGroupsSharing = true;
    $owner_id = $familyId;
}


$context = !empty($contextId)?Warecorp_Group_Factory::loadById(intval($contextId)):null;

if (!$AccessManager->canUnshareList($list, $this->currentGroup, $this->_page->_user->getId())) {
    $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
    return;
}

if ( $allGroupsSharing ) {
    $family = new Warecorp_Group_Family('id', $owner_id);
    if ( $family && $family->getId() && $AccessManager->canUnshareListToAllFamilyGroups($list, $family, $this->_page->_user) ) {
        $list->unshareList($owner_type, $owner_id, true);
    }
    else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }
}
else {
    $list->unshareList($owner_type, $owner_id, false);
}
if (!empty($context)) {
    $objResponse = $this->listsSharePopupShowAction($list_id, null, $context->getId());
    return;
}else{
    $objResponse = $this->listsSharePopupShowAction($list_id);
    return;
}
$objResponse = $this->listsSharePopupShowAction($list_id);
