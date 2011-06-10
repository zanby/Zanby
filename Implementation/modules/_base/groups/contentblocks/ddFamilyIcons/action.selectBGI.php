<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddFamilyIcons/action.selectBGI.php.xml');

$objResponse = new xajaxResponse();

$avatarListObj = new Warecorp_Group_BrandPhoto_List($this->currentGroup->getId());
$avatars = $avatarListObj->getList();

$this->view->a_thumbs_hash = $avatars;
$this->view->cloneId = $cloneId;
$this->view->a_thumbs_content = $this->view->getContents('content_objects/ddFamilyIcons/avatar_thumbs_block.tpl');


if (!empty($avatar_id))
{
  $avatar_id = intval($avatar_id);
               
        if (Warecorp_Group_BrandPhoto_Item::isPhotoExists($avatar_id)){
            $currentBrandPhoto = new Warecorp_Group_BrandPhoto_Item($avatar_id);
            if ($currentBrandPhoto->getGroupId() == $this->currentGroup->getId())
            {
            $this->view->currentAvatar = $currentBrandPhoto;
            }
        }
        else 
        {
         $this->view->currentAvatar = new Warecorp_Group_BrandPhoto_Item();   
        }
}else 
        {
         $this->view->currentAvatar = new Warecorp_Group_BrandPhoto_Item();   
        }


if (!empty($refer))
{
    $this->view->a_refer = $refer;
}

$content = $this->view->getContents('content_objects/ddFamilyIcons/choose_avatar.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Preview'));
$popup_window->content($content);
$popup_window->width(465)->height(320);

if ($openAction == "reload") {
    $popup_window->reload($objResponse);	
} else {
    $popup_window->open($objResponse);	
}

