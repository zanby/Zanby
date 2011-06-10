<?php
Warecorp::addTranslation("/modules/users/action.compose.php.xml");

$theme = Warecorp_CO_Theme_Item::loadThemeFromDB($this->currentUser);
$theme->prepareFonts();
$this->view->theme = $theme;

$this->_page->Xajax->registerUriFunction("doAttendeeEvent", "/users/calendarEventAttendee/" ); 
$this->_page->Xajax->registerUriFunction("doEventInvite", "/users/calendarEventInvite/"); 
$this->_page->Xajax->registerUriFunction("doEventOrganizerSendMessage", "/users/calendarEventOrganizerSendMessage/" );

//------------------------------------------------------------------------------------------------
$this->view->currentUser = $this->currentUser;
$this->view->entity_id = $this->currentUser->getId();
//Common
$this->_page->Xajax->registerUriFunction("content_objects_load_from_db", "/users/contentObjectsLoadFromDb/");
$this->_page->Xajax->registerUriFunction("get_block_content", "/users/getBlockContent/");
$this->_page->Xajax->registerUriFunction("get_block_content_light", "/users/getBlockContentLight/");
$this->_page->Xajax->registerUriFunction("get_block_content_than_save", "/users/getBlockContentThanSave/");
$this->_page->Xajax->registerUriFunction("content_objects_save", "/users/contentObjectsSave/");
//ddProfileDetails
$this->_page->Xajax->registerUriFunction("update_user_profile", "/users/updateUserProfile/");

//ddScript
$this->_page->Xajax->registerUriFunction("ddScript_save_script_code", "/users/ddScriptSaveScriptCode/");
$this->_page->Xajax->registerUriFunction("ddScript_remove_script_code", "/users/ddScriptRemoveScriptCode/");


//ddPicture
$this->view->SWFUploadID = session_id(); 
$this->_page->Xajax->registerUriFunction("select_avatar", "/users/selectAvatar/");
$this->_page->Xajax->registerUriFunction("upload_avatar", "/users/uploadAvatar/");
$this->_page->Xajax->registerUriFunction("upload_avatar_close", "/users/uploadAvatarClose/");
$this->_page->Xajax->registerUriFunction("load_avatars", "/users/loadAvatars/");
$this->_page->Xajax->registerUriFunction("select_avatar_close", "/users/selectAvatarClose/");
$this->_page->Xajax->registerUriFunction("show_avatar_preview", "/users/showAvatarPreview/");
$this->_page->Xajax->registerUriFunction("load_avatar_in_edit_mode", "/users/loadAvatarInEditMode/");
//ddMyPhotos
$this->_page->Xajax->registerUriFunction("select_gallery", "/users/selectGallery/");
$this->_page->Xajax->registerUriFunction("set_gallery", "/users/setGallery/");
$this->_page->Xajax->registerUriFunction("ddMyPhotos_load_gallery", "/users/loadMyPhotosGallery/");

//ddMyVideos
$this->_page->Xajax->registerUriFunction("select_video_gallery", "/users/selectVideoGallery/");
$this->_page->Xajax->registerUriFunction("set_video_gallery", "/users/setVideoGallery/");
$this->_page->Xajax->registerUriFunction("ddMyVideos_load_gallery", "/users/loadMyVideosGallery/");

//ddMyDocuments
$this->_page->Xajax->registerUriFunction("documents_getcontent", "/users/documentsGetcontent/");
$this->_page->Xajax->registerUriFunction("select_document", "/users/documentSelect/");
$this->_page->Xajax->registerUriFunction("select_document_close", "/users/documentSelectClose/");
//ddImage
$this->_page->Xajax->registerUriFunction("ddImage_select_avatar", "/users/ddImageSelectAvatar/");
$this->_page->Xajax->registerUriFunction("update_thumbs_area", "/users/ddImageUpdateThumbsArea/");
$this->_page->Xajax->registerUriFunction("ddImage_show_avatar_preview", "/users/ddImageShowAvatarPreview/");
$this->_page->Xajax->registerUriFunction("doAvatarLoadFromGalleries", "/users/doAvatarLoadFromGalleries/" );
$this->_page->Xajax->registerUriFunction("avatarLoadFromGalleries", "/users/avatarLoadFromGalleries/" );
$this->_page->Xajax->registerUriFunction("updateAttachPhoto", "/users/calendarEventAttachPhotoUpdate/" );
$this->_page->Xajax->registerUriFunction("chooseAttachPhoto", "/users/calendarEventAttachPhotoChoose/" );    
//ddFamilyVideoContentBlock
$this->_page->Xajax->registerUriFunction("ddMyVideoContentBlock_select_avatar", "/users/ddMyVideoContentBlockSelectAvatar/");
$this->_page->Xajax->registerUriFunction("update_thumbs_area_mv", "/users/ddMyVideoContentBlockUpdateThumbsArea/");
$this->_page->Xajax->registerUriFunction("ddMyVideoContentBlock_show_avatar_preview", "/users/ddMyVideoContentBlockShowAvatarPreview/");

//------------------------------------------------------------------------------------------------
$this->view->menuContent = '';
$this->_page->breadcrumb = array(Warecorp::t("My Profile") => $this->_page->_user->getUserPath('profile') , Warecorp::t("Template Editor") => "");
$this->view->setLayout('main_wide.tpl');
$this->view->bodyContent = 'users/compose.tpl';
