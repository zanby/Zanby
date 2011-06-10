<?php
$this->view->visibility = false;

$Content = $this->view->getContents('users/settings.accountCancel.tpl');

$objResponse = new xajaxResponse();
$objResponse->addClear( "AccountCancel_Content", "innerHTML" );
$objResponse->addAssign( "AccountCancel_Content", "innerHTML", $Content );
