<?php

Warecorp::addTranslation('/modules/info/action.about.php.xml');

$this->_page->setTitle(Warecorp::t('About Us'));
$this->view->bodyContent = 'info/about.tpl';
