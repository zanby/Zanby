<?php
$objResponse = new xajaxResponse();

$group = Warecorp_Group_Factory::loadById($groupId, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
if (!$group->getId()) {
    $objResponse->addRedirect($this->admin->getAdminPath('groups'));
}
$family = Warecorp_Group_Factory::loadById($familyId, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
if (!$family->getId()) {
    $objResponse->addRedirect($this->admin->getAdminPath('groupFamilyMembership').'/id/' . $groupId);
}

$family->getGroups()->addGroup($group->getId(), Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED);
$objResponse->addRedirect($this->admin->getAdminPath('groupFamilyMembership').'/id/' . $groupId);
