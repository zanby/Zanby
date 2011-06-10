<?php
Warecorp::addTranslation('/modules/groups/theme/xajax/action.uploadAvatar.php.xml');

$objResponse = new xajaxResponse();
$content=$this->view->getContents('groups/theme/upload_avatar.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Upload background image'));
$popup_window->content($content);
$popup_window->width(435)->height(350)->open($objResponse);