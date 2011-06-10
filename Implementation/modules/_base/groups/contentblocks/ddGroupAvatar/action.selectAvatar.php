<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupAvatar/action.selectAvatar.php.xml');

$objResponse = new xajaxResponse();

$avatarListObj = new Warecorp_Group_Avatar_List($this->currentGroup->getId());
$avatars = $avatarListObj->getList();

$this->view->a_thumbs_hash = $avatars;
$this->view->cloneId = $cloneId;
$this->view->canUploadAvatar = Warecorp_Group_AccessManager::isHostPrivileges($this->currentGroup,$this->_page->_user);

$this->view->a_thumbs_content = $this->view->getContents('content_objects/ddGroupAvatar/avatar_thumbs_block.tpl');

if (!empty($refer))
{
    $this->view->a_refer = $refer;
}

$content = $this->view->getContents('content_objects/ddGroupAvatar/choose_avatar.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Preview'));
$popup_window->content($content);
$popup_window->width(465)->height(320);

if ($openAction == 'reload') {
    $popup_window->reload($objResponse);    
} else {
    $popup_window->open($objResponse);  
}
