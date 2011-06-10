<?php
Warecorp::addTranslation('/modules/groups/xajax/action.hideResign.php.xml');

$this->view->visibility = false;

if($this->currentGroup->getGroupType() == "simple"){
    $Content = $this->view->getContents('groups/settings.resign.tpl');
} elseif ($this->currentGroup->getGroupType() == "family"){
    $Content = $this->view->getContents('groups/settings.familyresign.tpl');
}

$objResponse = new xajaxResponse();
$objResponse->addClear( "GroupSettingsResign_Content", "innerHTML" );
$objResponse->addAssign( "GroupSettingsResign_Content", "innerHTML", $Content );
