<?php
Warecorp::addTranslation('/modules/groups/xajax/action.hidePriveleges.php.xml');

    $this->view->visibility = false;
    $Content = $this->view->getContents('groups/settings.privileges.tpl');
    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupSettingsPrivilegies_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsPrivilegies_Content", "innerHTML", $Content );
