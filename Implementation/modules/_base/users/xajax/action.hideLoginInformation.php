<?php
$this->view->visibility = false;

$Content = $this->view->getContents('users/settings.loginInformation.tpl');

$objResponse = new xajaxResponse();
$objResponse->addClear( "AccountLoginInformation_Content", "innerHTML" );
$objResponse->addAssign( "AccountLoginInformation_Content", "innerHTML", $Content );
