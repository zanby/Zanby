<?php
    Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryUnShareDo.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);

if ( !empty($gallery) && $gallery->getId() !== null &&
     Warecorp_Video_AccessManager_Factory::create()->canUnShareGallery($gallery, $this->currentUser, $this->_page->_user) ) {
    
    $gallery->unshare($this->currentUser);
    
    $this->_page->showAjaxAlert(Warecorp::t('Video is unshared'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    
    $objResponse->addRedirect($this->currentUser->getUserPath('videos'));
} else {
    $objResponse->addRedirect($this->_page->_user->getUserPath('videos'));
    //$objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
