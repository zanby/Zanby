<?php
Warecorp::addTranslation('/modules/groups/xajax/action.hideGroupDelete.php.xml');

$this->view->visibility = false;

if($this->currentGroup->getGroupType() == "simple"){
    $Content = $this->view->getContents('groups/settings.deletegroup.tpl');
} elseif ($this->currentGroup->getGroupType() == "family"){
    $Content = $this->view->getContents('groups/settings.deletefamilygroup.tpl');
}

$objResponse = new xajaxResponse();
$objResponse->addClear( "GroupSettingsDeleteGroup_Content", "innerHTML" );
$objResponse->addAssign( "GroupSettingsDeleteGroup_Content", "innerHTML", $Content );
