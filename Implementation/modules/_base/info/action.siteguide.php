<?php

Warecorp::addTranslation('/modules/info/action.siteguide.php.xml');

$this->_page->setTitle(Warecorp::t('Guide'));
$this->view->bodyContent = 'info/siteguide.tpl';
