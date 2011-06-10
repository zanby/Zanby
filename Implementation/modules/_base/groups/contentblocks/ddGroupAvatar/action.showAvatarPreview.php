<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupAvatar/action.showAvatarPreview.php.xml');

$objResponse = new xajaxResponse();

$objResponse->addAssign("a_image_preview_GA","src", strip_tags($url));
$objResponse->addAssign("a_image_preview_GA","name", strip_tags($id));
//$objResponse->addAssign("a_image_preview_title","innerHTML", strip_tags($title));

//$objResponse->addScript("alert(document.getElementById('a_image_preview').name);");
