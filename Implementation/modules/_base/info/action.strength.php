<?php

Warecorp::addTranslation('/modules/info/action.strength.php.xml');

$this->_page->setTitle(Warecorp::t('Password Strength'));
$this->view->bodyContent = 'info/strength.tpl';
