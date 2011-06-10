<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupFamilyIcons/action.selectGBGI.php.xml');

$objResponse = new xajaxResponse();

$families = $this->currentGroup->getFamilyGroups()->getList();
$this->view->groupFamilies = $families;

// cheking family id
if (empty($selectedFamily)) {


    if (!empty($avatar_id)){
      $avatar_id = intval($avatar_id);

      if (Warecorp_Group_BrandPhoto_Item::isPhotoExists($avatar_id)){
          $currentBrandPhoto = new Warecorp_Group_BrandPhoto_Item($avatar_id);
          if (in_array($currentBrandPhoto->getGroupId(),  $this->currentGroup->getFamilyGroups()->returnAsAssoc(true)->setAssocValue('family_id')->getList()) ) {
              $selectedFamily =  $currentBrandPhoto->getGroupId();
          }
      }
    }

    if (empty($selectedFamily)) {
        if (!empty($families[0])){
            $selectedFamily = $families[0]->getId();
        } else {
            $selectedFamily = 0;
        }
    }
} else {
    if (!in_array(intval($selectedFamily), $this->currentGroup->getFamilyGroups()->returnAsAssoc(true)->setAssocValue('family_id')->getList() )) {
        $selectedFamily = 0;
    }
}
$this->view->selectedFamily = $selectedFamily;

// thumbnails
$avatarListObj = new Warecorp_Group_BrandPhoto_List($selectedFamily);
$avatars = $avatarListObj->getList();


$this->view->a_thumbs_hash = $avatars;
$this->view->cloneId = $cloneId;
$this->view->a_thumbs_content = $this->view->getContents('content_objects/ddGroupFamilyIcons/avatar_thumbs_block.tpl');


if (!empty($avatar_id))
{
  $avatar_id = intval($avatar_id);

        if (Warecorp_Group_BrandPhoto_Item::isPhotoExists($avatar_id)){
            $currentBrandPhoto = new Warecorp_Group_BrandPhoto_Item($avatar_id);
            if (in_array($currentBrandPhoto->getGroupId(),  $this->currentGroup->getFamilyGroups()->returnAsAssoc(true)->setAssocValue('family_id')->getList()) )
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

$content = $this->view->getContents('content_objects/ddGroupFamilyIcons/choose_avatar.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Preview'));
$popup_window->content($content);
$popup_window->width(465)->height(300)->open($objResponse);
