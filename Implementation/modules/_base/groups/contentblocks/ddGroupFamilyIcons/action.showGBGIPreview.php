<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupFamilyIcons/action.selectGBGIPreview.php.xml');

$objResponse = new xajaxResponse();

$objResponse->addAssign("a_image_preview","src", strip_tags($url));
$objResponse->addAssign("a_image_preview","name", strip_tags($id));
$objResponse->addAssign("a_image_preview_title","innerHTML", strip_tags($title));
