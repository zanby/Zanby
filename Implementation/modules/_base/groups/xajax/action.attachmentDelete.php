<?php
Warecorp::addTranslation('/modules/groups/xajax/action.attachmentDelete.php.xml');

    $objResponse = new xajaxResponse();
    $objResponse->addRemove("attachment_".($attach_id));
    $this->params['attachrel'] = $attach_id;
    $this->attachdelAction();
