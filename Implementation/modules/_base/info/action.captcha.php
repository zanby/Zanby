<?php

Warecorp::addTranslation('/modules/info/action.captcha.php.xml');

$this->_page->setTitle(Warecorp::t('What is a CAPTCHA?'));
$this->view->bodyContent = 'info/captcha.tpl';
