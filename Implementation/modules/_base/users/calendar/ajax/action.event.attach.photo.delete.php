<?php
    $objResponse = new xajaxResponse();

    $objResponse->addAssign('EventPictureBlockNONE', 'style.display', '');
    $objResponse->addAssign('EventPictureBlock', 'style.display', 'none');        

    $objResponse->addAssign('event_picture_id', 'value', 0);