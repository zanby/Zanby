<?php
    Warecorp::addTranslation("/modules/users/xajax/action.showAccountCancel.php.xml");

$this->view->visibility = true;

$this->_page->setTitle(Warecorp::t('Accounts Cancel'));
$form = new Warecorp_Form('acForm', 'post', '#');

$form->addRule('confirm',        'required',  Warecorp::t('Confirm account deleting'));
$this->view->form = $form;

$Content = $this->view->getContents('users/settings.accountCancel.tpl');

$objResponse = new xajaxResponse();
$objResponse->addClear( "AccountCancel_Content", "innerHTML" );
$objResponse->addAssign( "AccountCancel_Content", "innerHTML", $Content );
