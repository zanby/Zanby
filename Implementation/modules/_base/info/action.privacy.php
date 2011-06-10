<?php

Warecorp::addTranslation('/modules/info/action.privacy.php.xml');

$this->_page->setTitle(Warecorp::t('Privacy Policy'));
$this->view->bodyContent = 'info/privacy.tpl';
