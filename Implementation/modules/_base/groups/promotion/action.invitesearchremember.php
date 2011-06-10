<?php
Warecorp::addTranslation('/modules/groups/promotion/action.invitesearchremember.php.xml');

$_url = substr($this->_page->_user->getUserPath(), 0, -1);
$this->params['page'] = (!empty($this->params['page'])) ? (int)$this->params['page'] : 1;
$this->params['order'] = (!empty($this->params['order'])) ? (int)$this->params['order'] : "";
$this->params['filter'] = (!empty($this->params['filter'])) ? (int)$this->params['filter'] : "";

$listSearch = new Warecorp_List_Search();
$listSearch->name = $this->params['search_name'];
$listSearch->EntityTypeId = "2";
$listSearch->userId = $this->_page->_user->getId();
$search_params = array(
    'page' => $this->params['page'],
    'order' => $this->params['order'],
    'filter' => $this->params['filter']);
if (isset($_SESSION['group_search'])) {
    $search_params = array_merge($_SESSION['group_search'], $search_params);
}

$listSearch->params = $search_params;
$listSearch->save();
$alert = Warecorp::t('Saved');
$this->_page->showAjaxAlert($alert);
$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();

$this->_redirect(strtolower($this->currentGroup->getGroupPath('invitesearch/preset/new')));