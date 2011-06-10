<?php
$objResponse = new xajaxResponse();

$family = Warecorp_Group_Factory::loadById($familyId, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
if (!$family->getId()) {
    $objResponse->addRedirect($this->admin->getAdminPath('groupFamilyMembership').'/id/' . $groupId);
}

$family->getGroups()->removeGroup($groupId);
$objResponse->addRedirect($this->admin->getAdminPath('groupFamilyMembership').'/id/' . $groupId);
