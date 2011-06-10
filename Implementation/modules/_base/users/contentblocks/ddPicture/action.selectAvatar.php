<?php
Warecorp::addTranslation("/modules/users/contentblocks/ddPicture/action.selectAvatar.php.xml");
$objResponse = new xajaxResponse();

$avatarListObj = new Warecorp_User_Avatar_List($this->_page->_user->getId());
$avatars = $avatarListObj->getList();

$this->view->a_thumbs_hash = $avatars;
$this->view->cloneId = $cloneId;
$this->view->a_thumbs_content = $this->view->getContents('content_objects/ddPicture/avatar_thumbs_block.tpl');

if (!empty($refer))
{
    $this->view->a_refer = $refer;
}

$content = $this->view->getContents('content_objects/ddPicture/choose_avatar.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Preview'));
$popup_window->content($content);
$popup_window->width(480)->height(360);

if ($openAction == 'reload') {
    $popup_window->reload($objResponse);	
} else {
    $popup_window->open($objResponse);	
}
