<?php
Warecorp::addTranslation("/modules/users/contentblocks/ddPicture/action.loadAvatars.php.xml");
$objResponse = new xajaxResponse();

$avatarListObj = new Warecorp_User_Avatar_List($this->_page->_user->getId());
$avatars = $avatarListObj->getList();

$this->view->a_thumbs_hash = $avatars;
$this->view->cloneId = $cloneId;
$avatars_thumbs_content = $this->view->getContents('content_objects/ddPicture/avatar_thumbs_block.tpl');

if ($refresh){
    
    $this->view->a_thumbs_content = $avatars_thumbs_content;  
    $content = $this->view->getContents('content_objects/ddPicture/choose_avatar.tpl');

    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t('Preview'));
    $popup_window->content($content);
    $popup_window->width(465)->height(300)->reload($objResponse);
}
else
{
    $objResponse->addClear("a_gallery_thumbs","innerHTML");
    $objResponse->addAssign("a_gallery_thumbs","innerHTML", $avatars_thumbs_content);
}
$objResponse->addScript("window.top.xajax_select_avatar('$cloneId', '', 'reload');");
