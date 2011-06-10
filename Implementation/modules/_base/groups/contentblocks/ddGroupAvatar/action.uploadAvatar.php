<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupAvatar/action.uploadAvatar.php.xml');

$objResponse = new xajaxResponse();

$avatarListObj = new Warecorp_Group_Avatar_List($this->currentGroup->getId());

$this->view->cloneId = $cloneId;
$this->view->avatarsLeft = 12-$avatarListObj->getCount();

$content=$this->view->getContents('content_objects/ddGroupAvatar/upload_avatar.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title('Upload');
$popup_window->content($content);
$popup_window->width(435)->height(300)->open($objResponse);

$this->view->errors = array(Warecorp::t('Please select files to upload'));
$errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
$objResponse->addClear('swferror', 'innerHTML');
$objResponse->addAssign('swferror', 'innerHTML', $errorcontent);
$avatarsCount = $avatarListObj->getCount();
$objResponse->addScript('maxavatars = '.(12 - $avatarsCount).';'); 
$objResponse->addScript('turnOnSWFUpload();'); 
