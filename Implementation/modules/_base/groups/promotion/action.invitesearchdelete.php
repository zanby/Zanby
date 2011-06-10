<?php
Warecorp::addTranslation('/modules/groups/promotion/action.invitesearchdelete.php.xml');

if (isset($this->params['id'])) {
    $listSearch = new Warecorp_List_Search($this->params['id']);
    if ($listSearch->userId === $this->_page->_user->getId()) {
        $listSearch->delete();
        $alert = Warecorp::t('Deleted');
        $this->_page->showAjaxAlert($alert);
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    }
}
if (isset($_SESSION['group_search']))
    $redirect_url = strtolower($this->currentGroup->getGroupPath('invitesearch/preset/new'));
else
    $redirect_url = strtolower($this->currentGroup->getGroupPath('invite1'));
$this->_redirect($redirect_url);
