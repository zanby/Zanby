<?php
Warecorp::addTranslation('/modules/groups/xajax/action.hideLocation.php.xml');

    $this->view->visibility = false;
    $Content = $this->view->getContents('groups/settings.location.tpl');
    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupSettingsLocation_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsLocation_Content", "innerHTML", $Content );
