<?php
Warecorp::addTranslation('/modules/groups/xajax/action.hideCohosts.php.xml');

    $this->view->visibility = false;

    if($this->currentGroup->getGroupType() == "simple"){
        $Content = $this->view->getContents('groups/settings.cohosts.tpl');
    } elseif ($this->currentGroup->getGroupType() == "family"){
        $Content = $this->view->getContents('groups/settings.familycohosts.tpl');
    }

    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupSettingsCoHost_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsCoHost_Content", "innerHTML", $Content );
