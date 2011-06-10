<?php
Warecorp::addTranslation('/modules/groups/xajax/action.deleteGroupStep3.php.xml');

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

    $this->currentGroup->delete();
    $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
    $cache->remove('all_mygroups_menu_account_tools_'.$this->_page->_user->getId());

/*    $this->view->currentGroup = $this->currentGroup;
    $Content = $this->view->getContents('groups/setting.deletefamilygroup3.tpl');



    $objResponse->addClear( "GroupSettingsDeleteGroup", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsDeleteGroup", "innerHTML", $Content );*/
    $objResponse->addRedirect(BASE_URL); //Family group deleted, redirect to index
}
