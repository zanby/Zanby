<?php
Warecorp::addTranslation('/modules/groups/xajax/action.showLocation.php.xml');

    $this->view->visibility = true;
    $Content = $this->view->getContents('groups/settings.location.tpl');
    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupSettingsLocation_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsLocation_Content", "innerHTML", $Content );
