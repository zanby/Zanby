<?php
Warecorp::addTranslation('/modules/groups/promotion/action.webbadges.php.xml');

$this->_page->Xajax->registerUriFunction("custom_badge_upload_image", "/groups/custombadgeupload/");
$this->_page->Xajax->registerUriFunction("webbadgeDelete", "/groups/webbadgeDelete/");
$this->_page->Xajax->registerUriFunction("webbadgeDeleteDo", "/groups/webbadgeDeleteDo/");



$oBadgesList = new Warecorp_Group_WebBadges_List($this->currentGroup->getId());
$oBadgesList->returnAsAssoc(false);

$this->view->webBadgesList = $oBadgesList->getList();
$this->view->SWFUploadID = session_id(); 
$this->view->menuContent = '';
$this->view->bodyContent = 'groups/promotion/webbadges.tpl';


