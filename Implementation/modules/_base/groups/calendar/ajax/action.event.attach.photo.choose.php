<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.attach.photo.choose.php.xml');
    $objResponse = new xajaxResponse(); 
    $objResponse->addAssign('a_image_preview', 'src', $src);
    $objResponse->addAssign('a_image_preview', 'title', $title);
    $objResponse->addAssign('choosed_photo_id', 'value', $photoId);