<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupAvatar/action.loadAvatars.php.xml');

$objResponse = new xajaxResponse();

$avatarListObj = new Warecorp_Group_Avatar_List($this->currentGroup->getId());
$avatars = $avatarListObj->getList();

$this->view->a_thumbs_hash =$avatars;
$this->view->cloneId =$cloneId;
$avatars_thumbs_content = $this->view->getContents('content_objects/ddGroupAvatar/avatar_thumbs_block.tpl');

if ($refresh){
    
    $this->view->a_thumbs_content_GA =$avatars_thumbs_content;  
    $content = $this->view->getContents('content_objects/ddGroupAvatar/choose_avatar.tpl');

    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t('Preview'));
    $popup_window->content($content);
    $popup_window->width(465)->height(300)->reload($objResponse);
}
else
{
    $objResponse->addClear("a_gallery_thumbs_GA","innerHTML");
    $objResponse->addAssign("a_gallery_thumbs_GA","innerHTML", $avatars_thumbs_content);
}

if (count($avatars)>=13) {
    $objResponse->addScript("if (document.getElementById('a_gallery_thumbs_GA_add')) {document.getElementById('a_gallery_thumbs_GA_add').style.display='none';}");
}

$objResponse->addScript("window.top.xajax_select_avatar('$cloneId', '', 'reload');");
