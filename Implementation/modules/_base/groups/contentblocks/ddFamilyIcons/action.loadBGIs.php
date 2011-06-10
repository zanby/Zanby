<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddFamilyIcons/action.loadBGIs.php.xml');
//print($cloneId);die;
$objResponse = new xajaxResponse();

$avatarListObj = new Warecorp_Group_BrandPhoto_List($this->currentGroup->getId());
$avatars = $avatarListObj->getList();

$this->view->a_thumbs_hash =$avatars;
$this->view->cloneId =$cloneId;
$avatars_thumbs_content = $this->view->getContents('content_objects/ddFamilyIcons/avatar_thumbs_block.tpl');

if ($refresh){
    
    $this->view->a_thumbs_content =$avatars_thumbs_content;  
    $this->view->currentAvatar = new Warecorp_Group_BrandPhoto_Item();
    $content = $this->view->getContents('content_objects/ddFamilyIcons/choose_avatar.tpl');

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
$objResponse->addScript("window.top.xajax_select_bgi('$cloneId', '', 'reload');");
