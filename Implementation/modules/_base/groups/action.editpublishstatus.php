<?php
Warecorp::addTranslation('/modules/groups/action.editpublishstatus.php.xml');

if ($this->_page->_user->getMembershipPlan() == 'free'){//free account cant use publishing
     $this->_redirectToLogin();
}


$this->view->menuContent = '';

$Data = new Warecorp_Group_Publish("group_id", $this->currentGroup->getId());

$publishnow     = (isset($this->params['update'])) ? $this->params['update'] == 'yes' ? true : false : false;

$this->view->action = 'editpublishstatus';
$this->view->Data = $Data;
$this->view->publishnow = $publishnow;
$this->view->Path = $this->currentGroup->getGroupPath();

$this->view->setLayout('main_wide.tpl');



$this->view->bodyContent = 'groups/editpublishstatus.tpl';
$this->view->isRightBlockHidden = true;
/**/

