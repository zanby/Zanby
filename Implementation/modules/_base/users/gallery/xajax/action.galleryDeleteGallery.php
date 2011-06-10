<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryDeleteGallery.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);

if ( $gallery->getId() !== null && 
     Warecorp_Photo_AccessManager_Factory::create()->canDeleteGallery($gallery, $this->currentUser, $this->_page->_user) ) {

    $gallery->delete();

    if ($new == false){
        $this->_page->showAjaxAlert(Warecorp::t('Gallery deleted'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    }

    $objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
} else {
    if ($new == false){
        $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
    } else {
        $objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
    }
}