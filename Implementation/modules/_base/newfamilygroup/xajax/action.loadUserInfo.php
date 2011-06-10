<?php

Warecorp::addTranslation('/modules/newfamilygroup/xajax/action.loadUserInfo.php.xml');

// this action not used - used step3-submit
$objResponse = new xajaxResponse();

$objResponse->addAssign("name", "value",            $_SESSION["newfamilygroup"]["name"]);
$objResponse->addAssign("address1", "value",        $_SESSION["newfamilygroup"]["address1"]);
$objResponse->addAssign("address2", "value",        $_SESSION["newfamilygroup"]["address2"]);

$objResponse->addAssign("countryId", "selectedIndex",        $_SESSION["newfamilygroup"]["countryId"]);

$objResponse->addScript("xajax_changeCountry(".$_SESSION["newfamilygroup"]["countryId"].", ".$_SESSION["newfamilygroup"]["stateId"].")");
$objResponse->addScript("xajax_changeState(".$_SESSION["newfamilygroup"]["stateId"].",".$_SESSION["newfamilygroup"]["cityId"].")");
$objResponse->addScript("xajax_changeCity(".$_SESSION["newfamilygroup"]["cityId"].",".$_SESSION["newfamilygroup"]["zipId"].")");

return $objResponse->getXML();