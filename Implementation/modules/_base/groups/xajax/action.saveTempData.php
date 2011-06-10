<?php
Warecorp::addTranslation('/modules/groups/xajax/action.saveTempData.php.xml');

$objResponse = new xajaxResponse();

if ($_SESSION["tempData"]["lastStep"] < $step){
    $_SESSION["tempData"][$step] = $data;
}
if ($gotoStep == 2) {
    $url = $this->currentGroup->setUsePathParamsMode()->getGroupPath("joinfamilystep".$gotoStep);
} else {
    $url = $this->currentGroup->setUsePathParamsMode(false)->getGroupPath("joinfamilystep".$gotoStep);
    //$url = "/".LOCALE."/joinfamilystep".$gotoStep."/";
}
$objResponse->addRedirect($url);
