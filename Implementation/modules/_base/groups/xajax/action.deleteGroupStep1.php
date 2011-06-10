<?php
Warecorp::addTranslation('/modules/groups/xajax/action.deleteGroupStep1.php.xml');

$objResponse = new xajaxResponse();
$isValid        = true;
//  @todo проверить на существование текущей группы и текущего пользователя
if ( $this->currentGroup->getId() === null || $this->_page->_user->getId() === null ) {
    $objResponse->addRedirect("/");
    $isValid = false;
}
//  @todo проверить права на хоста
if ( !$this->currentGroup->getMembers()->isHost($this->_page->_user->getId()) ) {
    $objResponse->addRedirect("/");
    $isValid = false;
}
if ( $isValid ) {

    $this->view->currentGroup = $this->currentGroup;

    if ($this->currentGroup->getGroupType() == "simple")
    $Content = $this->view->getContents('groups/setting.deletegroup1.tpl');
    else
    $Content = $this->view->getContents('groups/setting.deletefamilygroup1.tpl');

    $objResponse->addClear( "GroupSettingsDeleteGroup_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsDeleteGroup_Content", "innerHTML", $Content );
}
