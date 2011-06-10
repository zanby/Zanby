<?php
Warecorp::addTranslation('/modules/groups/newgroup/xajax/action.saveTempData.php.xml');

$objResponse = new xajaxResponse();
if ($_SESSION['newgroupMember']["tempData"]["lastStep"] < $stepfrom){
    $_SESSION['newgroupMember']["tempData"][$stepfrom] = $data;
}
$objResponse->addRedirect($this->currentGroup->getGroupPath('membersAddStep'.($stepto)));