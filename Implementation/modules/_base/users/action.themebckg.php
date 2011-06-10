<?php
$this->_page->Xajax->registerUriFunction("upload_avatar", "/users/uploadBCKGAvatar/");
$this->_page->Xajax->registerUriFunction("copy_avatar", "/users/copyBCKGAvatarOK/");
$this->_page->Xajax->registerUriFunction("ddImage_select_avatar", "/users/ddImageSelectBCKGAvatar/");
$this->_page->Xajax->registerUriFunction("update_thumbs_area", "/users/ddImageUpdateBCKGThumbsArea/");
$this->_page->Xajax->registerUriFunction("ddImage_show_avatar_preview", "/users/ddImageShowBCKGAvatarPreview/");
//$this->_page->Xajax->registerUriFunction("themeSave", "/users/themeSave/"); 

/*
1. headLineFontFamilyValue  default 2 [1-5]
2. headerFontFamilyValue    default 2 [1-5] +
3. bodyTextFontFamilyValue  default 2 [1-5] +
4. commentFontFamilyValue   default 2 [1-5] + Accent Text
5. linkColorValue           default X [1-70] +
6. headLineColorValue       default 1 [1-70]
7. headerColorValue         default X [1-70] +
8. bodyTextColorValue       default X [1-70] +
9. commentColorValue        default X [1-70] + Accent Text
*/
//$themeString = Warecorp_DDPages::loadThemeFromDB($this->currentUser);
// 
//$themeArray = explode(' ', '0 '.$themeString);
//$this->view->themeString = $themeString;
//$this->view->themeArray = $themeArray;

$this->view->setLayout('main_wide.tpl');
$this->view->bodyContent = 'users/themebckg.tpl';
