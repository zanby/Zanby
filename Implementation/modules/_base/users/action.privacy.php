<?php
if ( $this->currentUser->getId() !== $this->_page->_user->getId() ) {
    $this->_redirect($this->currentUser->getUserPath('profile'));
}

$this->_page->Xajax->registerUriFunction("privacy_cp_show",     "/users/showCommunicationPreferences/");
$this->_page->Xajax->registerUriFunction("privacy_cp_hide",     "/users/hideCommunicationPreferences/");
$this->_page->Xajax->registerUriFunction("privacy_cp_save",     "/users/saveCommunicationPreferences/");
$this->_page->Xajax->registerUriFunction("privacy_cv_show",     "/users/showContentVisibility/");
$this->_page->Xajax->registerUriFunction("privacy_cv_hide",     "/users/hideContentVisibility/");
$this->_page->Xajax->registerUriFunction("privacy_cv_save",     "/users/saveContentVisibility/");
$this->_page->Xajax->registerUriFunction("privacy_sr_show",     "/users/showSearchResultSettings/");
$this->_page->Xajax->registerUriFunction("privacy_sr_hide",     "/users/hideSearchResultSettings/");
$this->_page->Xajax->registerUriFunction("privacy_sr_save",     "/users/saveSearchResultSettings/");
$this->_page->Xajax->registerUriFunction("privacy_bu_show",     "/users/showBlockUsers/");
$this->_page->Xajax->registerUriFunction("privacy_bu_hide",     "/users/hideBlockUsers/");
$this->_page->Xajax->registerUriFunction("privacy_bu_block",    "/users/blockBlockUsers/");
$this->_page->Xajax->registerUriFunction("privacy_bu_unblock",  "/users/unblockBlockUsers/");
$this->_page->Xajax->registerUriFunction("privacy_bu_ac_logins","/users/autocompleteLogins/");


$privacy = $this->_page->_user->getPrivacy();

$this->view->bodyContent = 'users/privacy.tpl';
$this->view->privacy = $privacy;
$this->view->cp_view = 'collapsed';
$this->view->cv_view = 'collapsed';
$this->view->sr_view = 'collapsed';
$this->view->bu_view = 'collapsed';
