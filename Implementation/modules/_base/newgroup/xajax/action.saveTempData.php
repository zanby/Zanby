<?php

Warecorp::addTranslation('/modules/newgroup/xajax/action.saveTmpData.php.xml');

$objResponse = new xajaxResponse();
if ($_SESSION['newgroup']["tempData"]["lastStep"] < $stepfrom){
    $_SESSION['newgroup']["tempData"][$stepfrom] = $data;
}

$objResponse->addRedirect("/".LOCALE."/newgroup/step".($stepto)."/");
