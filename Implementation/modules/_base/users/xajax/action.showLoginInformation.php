<?php
    Warecorp::addTranslation("/modules/users/xajax/action.showLoginInformation.php.xml");

$this->view->visibility = true;


$this->_page->setTitle(Warecorp::t('Accounts settings'));
$form = new Warecorp_Form('liForm', 'post', 'javasript:void(0);');

$this->view->edituser = $this->currentUser;
$this->view->email_confirm = $this->currentUser->getEmail();
$this->view->form = $form;

$Content = $this->view->getContents('users/settings.loginInformation.tpl');

$objResponse = new xajaxResponse();
$objResponse->addClear( "AccountLoginInformation_Content", "innerHTML" );
$objResponse->addAssign( "AccountLoginInformation_Content", "innerHTML", $Content );
