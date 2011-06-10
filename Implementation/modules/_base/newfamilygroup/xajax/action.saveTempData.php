<?php

Warecorp::addTranslation('/modules/newfamilygroup/xajax/action.saveTempData.php.xml');

$objResponse = new xajaxResponse();
if ($_SESSION['newfamilygroup']["tempData"]["lastStep"] < $step){
    $_SESSION['newfamilygroup']["tempData"][$step] = $data;
}
$objResponse->addRedirect("/".LOCALE."/newfamilygroup/step".$gotoStep."/");
