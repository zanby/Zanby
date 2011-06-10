<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddFamilyIcons/action.uploadBGI.php.xml');

$objResponse = new xajaxResponse();

$avatarListObj = new Warecorp_Group_Avatar_List($this->currentGroup->getId());

$this->view->cloneId = $cloneId;

$content=$this->view->getContents('content_objects/ddFamilyIcons/upload_avatar.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Upload'));
$popup_window->content($content);
$popup_window->width(435)->height(300)->open($objResponse);
