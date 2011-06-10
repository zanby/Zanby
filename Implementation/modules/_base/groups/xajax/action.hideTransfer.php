<?php
    Warecorp::addTranslation('/modules/groups/xajax/action.hideTransfer.php.xml');

    $this->view->visibility = false;
    $Content = $this->view->getContents('groups/settings.transferaccount.tpl');

    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupSettingsTransfer_Content_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsTransfer_Content_Content", "innerHTML", $Content );
