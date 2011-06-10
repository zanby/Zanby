<?php

Warecorp::addTranslation('/modules/info/action.copyright.php.xml');

$this->_page->setTitle(Warecorp::t('Copyright'));
$this->view->bodyContent = 'info/copyright.tpl';
