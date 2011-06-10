<?php

Warecorp::addTranslation('/modules/info/action.tour.php.xml');

$this->_page->setTitle(Warecorp::t('Tour'));
$this->view->bodyContent = 'info/tour.tpl';
