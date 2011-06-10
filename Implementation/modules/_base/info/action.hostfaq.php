<?php
Warecorp::addTranslation('/modules/info/action.hostfaq.php.xml');

$this->_page->setTitle(Warecorp::t('FAQ for Group Hosts'));
$this->view->bodyContent = 'info/hostfaq.tpl';
