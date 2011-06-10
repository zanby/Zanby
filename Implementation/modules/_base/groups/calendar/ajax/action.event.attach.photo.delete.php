<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.attach.photo.delete.php.xml');
    $objResponse = new xajaxResponse();

    $objResponse->addAssign('EventPictureBlockNONE', 'style.display', '');
    $objResponse->addAssign('EventPictureBlock', 'style.display', 'none');        

    $objResponse->addAssign('event_picture_id', 'value', 0);