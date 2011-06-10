<?php
    $objResponse = new xajaxResponse(); 
    $objResponse->addAssign('a_image_preview', 'src', $src);
    $objResponse->addAssign('a_image_preview', 'title', $title);
    $objResponse->addAssign('choosed_photo_id', 'value', $photoId);