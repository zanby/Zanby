<?php
Warecorp::addTranslation('/modules/groups/action.themenew.php.xml');

if ($this->_page->_user->getMembershipPlan() == 'free'){//free account can't use themes
     $this->_redirectToLogin();
}

$this->_page->Xajax->registerUriFunction("remove_bckg_image", "/groups/removeBCKGImage/");

$this->_page->Xajax->registerUriFunction("upload_avatar", "/groups/uploadBCKGAvatar/");
$this->_page->Xajax->registerUriFunction("copy_avatar", "/groups/copyBCKGAvatarOK/");
$this->_page->Xajax->registerUriFunction("ddImage_select_avatar", "/groups/ddImageSelectBCKGAvatar/");
$this->_page->Xajax->registerUriFunction("update_thumbs_area", "/groups/ddImageUpdateBCKGThumbsArea/");
$this->_page->Xajax->registerUriFunction("ddImage_show_avatar_preview", "/groups/ddImageShowBCKGAvatarPreview/");

$this->_page->Xajax->registerUriFunction("themeSave", "/groups/themeSave/"); 

if (!isset($this->params['clear'])){
    $theme = Warecorp_CO_Theme_Item::loadThemeFromDB($this->currentGroup);
}else{
    $theme = new Warecorp_CO_Theme_Item; 
}

$this->view->themeVariables = Zend_Json::encode($theme);
$this->view->theme = $theme;

$this->view->setLayout('main_wide.tpl');
$this->view->bodyContent = 'groups/themenew.tpl';
$this->view->isRightBlockHidden = true;
