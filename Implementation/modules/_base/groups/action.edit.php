<?php
Warecorp::addTranslation('/modules/groups/action.edit.php.xml');

$theme = Warecorp_CO_Theme_Item::loadThemeFromDB($this->currentGroup);
$theme->prepareFonts();
$this->view->theme = $theme;

$this->_page->Xajax->registerUriFunction("doAttendeeEvent", "/groups/calendarEventAttendee/" ); 
$this->_page->Xajax->registerUriFunction("doEventInvite", "/groups/calendarEventInvite/"); 
$this->_page->Xajax->registerUriFunction("doEventOrganizerSendMessage", "/groups/calendarEventOrganizerSendMessage/" );




//print $this->currentGroup->getHost()->getId().'qqq'.$this->_page->_user->getId();die;

/**
 * FIXME CHANGED BY ARTEM SUKHAREV
 */
/*
if(($this->currentGroup->getHost()->getId() !== $this->_page->_user->getId()) 
    || !$this->_page->_user->getMembershipPlanEnabled() 
    || ($this->currentGroup->getGroupType() == "family" && $this->_page->_user->getMembershipPlan() != 'premium'))
{
    $this->_redirectError('ACCESS DENIED');
}
*/
 
if (!Warecorp_Group_AccessManager::canUseModifyLayout($this->currentGroup, $this->_page->_user)){
    $this->_redirectError(Warecorp::t('ACCESS DENIED'));
}

//Common
$this->_page->Xajax->registerUriFunction("content_objects_load_from_db", "/groups/contentObjectsLoadFromDb/");
$this->_page->Xajax->registerUriFunction("get_block_content", "/groups/getBlockContent/");
$this->_page->Xajax->registerUriFunction("get_block_content_light", "/groups/getBlockContentLight/"); 
$this->_page->Xajax->registerUriFunction("get_block_content_than_save", "/groups/getBlockContentThanSave/");
$this->_page->Xajax->registerUriFunction("content_objects_save", "/groups/contentObjectsSave/");

//ddScript
$this->_page->Xajax->registerUriFunction("ddScript_save_script_code", "/groups/ddScriptSaveScriptCode/");
$this->_page->Xajax->registerUriFunction("ddScript_remove_script_code", "/groups/ddScriptRemoveScriptCode/");

//ddGroupAvatar
$this->_page->Xajax->registerUriFunction("select_avatar", "/groups/selectAvatar/");
$this->_page->Xajax->registerUriFunction("upload_avatar", "/groups/uploadAvatar/");
$this->_page->Xajax->registerUriFunction("upload_avatar_close", "/groups/uploadAvatarClose/");
$this->_page->Xajax->registerUriFunction("load_avatars", "/groups/loadAvatars/");
$this->_page->Xajax->registerUriFunction("select_avatar_close", "/groups/selectAvatarClose/");
$this->_page->Xajax->registerUriFunction("show_avatar_preview", "/groups/showAvatarPreview/");
$this->_page->Xajax->registerUriFunction("load_avatar_in_edit_mode", "/groups/loadAvatarInEditMode/");

//ddFamilyIcons
$this->_page->Xajax->registerUriFunction("select_bgi", "/groups/selectBGI/");
$this->_page->Xajax->registerUriFunction("upload_bgi", "/groups/uploadBGI/");
//$this->_page->Xajax->registerUriFunction("upload_bgi_close", "/groups/uploadBGIClose/");
$this->_page->Xajax->registerUriFunction("load_bgis", "/groups/loadBGIs/");
//$this->_page->Xajax->registerUriFunction("select_bgi_close", "/groups/selectBGIClose/");
$this->_page->Xajax->registerUriFunction("show_bgi_preview", "/groups/showBGIPreview/");

//ddGroupFamilyIcons
$this->_page->Xajax->registerUriFunction("select_gbgi", "/groups/selectGBGI/");
$this->_page->Xajax->registerUriFunction("show_gbgi_preview", "/groups/showGBGIPreview/");

//ddGroupDocuments
$this->_page->Xajax->registerUriFunction("documents_getcontent", "/groups/documentsGetcontent/");
$this->_page->Xajax->registerUriFunction("select_document", "/groups/documentSelect/");
$this->_page->Xajax->registerUriFunction("share_my_documents_to_group", "/groups/shareMyDocumentsToGroup/");

//ddFamilyMap
$this->_page->Xajax->registerUriFunction("autoCompleteZip", "/ajax/autoCompleteZip/");
$this->_page->Xajax->registerUriFunction("ddFamilyMap_validate", "/groups/ddFamilyMapValidate/");

//ddGroupMap
//$this->_page->Xajax->registerUriFunction("autoCompleteZip", "/ajax/autoCompleteZip/");
$this->_page->Xajax->registerUriFunction("ddGroupMap_validate", "/groups/ddGroupMapValidate/");

//ddGroupPhotos
$this->_page->Xajax->registerUriFunction("select_gallery", "/groups/selectGallery/");
$this->_page->Xajax->registerUriFunction("set_gallery", "/groups/setGallery/");
$this->_page->Xajax->registerUriFunction("ddMyPhotos_load_gallery", "/groups/loadMyPhotosGallery/");

//ddGroupImage
$this->_page->Xajax->registerUriFunction("ddImage_select_avatar", "/groups/ddImageSelectAvatar/");
$this->_page->Xajax->registerUriFunction("update_thumbs_area", "/groups/ddImageUpdateThumbsArea/");
$this->_page->Xajax->registerUriFunction("ddImage_show_avatar_preview", "/groups/ddImageShowAvatarPreview/");

//ddFamilyVideoContentBlock
$this->_page->Xajax->registerUriFunction("ddFamilyVideoContentBlock_select_avatar", "/groups/ddFamilyVideoContentBlockSelectAvatar/");
$this->_page->Xajax->registerUriFunction("update_thumbs_area_mv", "/groups/ddFamilyVideoContentBlockUpdateThumbsArea/");
$this->_page->Xajax->registerUriFunction("ddFamilyVideoContentBlock_show_avatar_preview", "/groups/ddFamilyVideoContentBlockShowAvatarPreview/");

$this->view->SWFUploadID = session_id(); 
$this->view->entity_id = $this->currentGroup->getId();
$this->view->currentUser = $this->_page->_user;
$this->view->currentGroup = $this->currentGroup;
$this->_page->setTitle('Summary');

$this->view->setLayout('main_wide.tpl');

//@todo - remove 
//breadcrumb
//if ($this->currentGroup->getGroupType() == "family") {
//    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, array("Group families" => "/" . $this->_page->Locale . "/summary/" , $this->currentGroup->getName() => ""));
//} else {
//    $this->_page->breadcrumb = array_merge(
//        $this->_page->breadcrumb,
//        array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
//            $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
//            $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
//            $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
//            $this->currentGroup->getName() => "")
//        );
//}


if ($this->currentGroup->getGroupType() == "family")
{
    $this->view->bodyContent = 'groups/editFamily.tpl';
}
else
{
    if ($this->_page->_user->getMembershipPlan() == 'premium')
    {
        $this->view->bodyContent = 'groups/editPremium.tpl';
    }
    else
    {
        $this->view->bodyContent = 'groups/editPremium.tpl';
    }
}

