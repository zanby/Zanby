<?php
Warecorp::addTranslation('/modules/newgroup/action.newgroup.index.php.xml');

unset($_SESSION['newgroup']);
$_SESSION['newgroup'] = array();
$this->_redirect('/'.LOCALE.'/newgroup/step1/');