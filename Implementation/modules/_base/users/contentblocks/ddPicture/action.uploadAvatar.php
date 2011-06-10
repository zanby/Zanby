<?php
Warecorp::addTranslation("/modules/users/contentblocks/ddPicture/action.uploadAvatar.php.xml");
$objResponse = new xajaxResponse();

$avatarListObj = new Warecorp_User_Avatar_List($this->_page->_user->getId());

$this->view->cloneId = $cloneId;
$this->view->avatarsLeft = 12-$avatarListObj->getCount();

$content=$this->view->getContents('content_objects/ddPicture/upload_avatar.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Upload'));
$popup_window->content($content);
$popup_window->width(435)->height(300)->reload($objResponse);

$this->view->errors = array(Warecorp::t('Please select files to upload'));
$errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
$objResponse->addClear('swferror', 'innerHTML');
$objResponse->addAssign('swferror', 'innerHTML', $errorcontent); 
$avatarsCount = $avatarListObj->getCount();
$objResponse->addScript('maxavatars = '.(12 - $avatarsCount).';');
$objResponse->addScript('turnOnSWFUpload();');
