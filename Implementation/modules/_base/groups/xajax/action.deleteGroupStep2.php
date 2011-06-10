<?php
Warecorp::addTranslation('/modules/groups/xajax/action.deleteGroupStep2.php.xml');

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

    if ($this->currentGroup->getGroupType() == "simple") {
        /** If group have any value in groupUID then group is special and can't be empty **/
        /** According to the bug #6543 **/
        if ( !trim($this->currentGroup->getGroupUID()) ) {
            $this->currentGroup->delete();
            $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
            $cache->remove('all_mygroups_menu_account_tools_'.$this->_page->_user->getId());
            $this->_page->showAjaxAlert(Warecorp::t('Group deleted'));
            $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
            $objResponse->addRedirect('http://'.BASE_HTTP_HOST);//group deleted, redirect to index
        }
        else return;
    }
    else{
        $Content = $this->view->getContents('groups/setting.deletefamilygroup2.tpl');

        $objResponse->addClear( "GroupSettingsDeleteGroup_Content", "innerHTML" );
        $objResponse->addAssign( "GroupSettingsDeleteGroup_Content", "innerHTML", $Content );
    }
}
