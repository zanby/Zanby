<?php
    Warecorp::addTranslation("/modules/ajax/action.viewCounting.php.xml");

$objResponse = new xajaxResponse();

if (empty($videoId)) return;

$video = Warecorp_Video_Factory::loadById($videoId);

if (!$video->getId() || !$this->_page->_user->getId()) return;

$video->addView($this->_page->_user);

