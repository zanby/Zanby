<?php
Warecorp::addTranslation('/modules/groups/xajax/action.hideDetails.php.xml');

    $this->view->visibility = false;

    if($this->currentGroup->getGroupType() == "simple"){
        $Content = $this->view->getContents('groups/settings.details.tpl');
    } elseif ($this->currentGroup->getGroupType() == "family"){
        $Content = $this->view->getContents('groups/settings.familydetails.tpl');
    }

    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupSettingsGroupDetails_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsGroupDetails_Content", "innerHTML", $Content );
