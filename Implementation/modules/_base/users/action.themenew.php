<?php

	Warecorp::addTranslation('/modules/users/action.themenew.xml');

$this->_page->Xajax->registerUriFunction("remove_bckg_image", "/users/removeBCKGImage/");

$this->_page->Xajax->registerUriFunction("upload_avatar", "/users/uploadBCKGAvatar/");
$this->_page->Xajax->registerUriFunction("copy_avatar", "/users/copyBCKGAvatarOK/");
$this->_page->Xajax->registerUriFunction("ddImage_select_avatar", "/users/ddImageSelectBCKGAvatar/");
$this->_page->Xajax->registerUriFunction("update_thumbs_area", "/users/ddImageUpdateBCKGThumbsArea/");
$this->_page->Xajax->registerUriFunction("ddImage_show_avatar_preview", "/users/ddImageShowBCKGAvatarPreview/");

$this->_page->Xajax->registerUriFunction("themeSave", "/users/themeSave/"); 

//theme initialisation
if (!isset($this->params['clear'])){
    $theme = Warecorp_CO_Theme_Item::loadThemeFromDB($this->currentUser);
}else{
    $theme = new Warecorp_CO_Theme_Item; 
}
$this->view->themeVariables = Zend_Json::encode($theme);
$this->view->theme = $theme;



$this->_page->breadcrumb = array(Warecorp::t("My Profile") => $this->_page->_user->getUserPath('profile') , Warecorp::t("Template Editor") => "");

$this->view->setLayout('main_wide.tpl');
$this->view->bodyContent = 'users/themenew.tpl';
$this->view->isRightBlockHidden = true;
