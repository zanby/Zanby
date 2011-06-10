<?php
$this->view->visibility = false;

$Content = $this->view->getContents('users/settings.basicInformation.tpl');

$objResponse = new xajaxResponse();
$objResponse->addClear( "AccountBasicInformation_Content", "innerHTML" );
$objResponse->addAssign( "AccountBasicInformation_Content", "innerHTML", $Content );
