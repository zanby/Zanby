<?php

Warecorp::addTranslation('/modules/info/action.terms.php.xml');

$this->_page->setTitle(Warecorp::t('Terms of Service'));
$this->view->bodyContent = 'info/terms.tpl';
