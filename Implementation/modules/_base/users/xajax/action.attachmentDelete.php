<?php
    $objResponse = new xajaxResponse();
    $objResponse->addRemove("attachment_".($attach_id));
    $this->params['attachrel'] = $attach_id;
    $this->attachdelAction();