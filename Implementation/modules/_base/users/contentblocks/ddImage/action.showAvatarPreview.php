<?php

$objResponse = new xajaxResponse();
//print $url;  
$objResponse->addAssign("a_image_preview_ddImage","src", strip_tags($url));
$objResponse->addAssign("a_image_preview_ddImage","name", strip_tags($id));
$objResponse->addAssign("a_image_preview_title","innerHTML", strip_tags($title));
