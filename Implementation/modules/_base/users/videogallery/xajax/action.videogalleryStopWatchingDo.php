<?php
Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryStopWatchingDo.php.xml");

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);

if ( $gallery->getId() !== null && 
     Warecorp_Video_AccessManager_Factory::create()->canStopWatchingGallery($gallery, $this->currentUser, $this->_page->_user) ) {

    $gallery->stopWatch($this->_page->_user);

    $this->_page->showAjaxAlert(Warecorp::t('Watching stopped'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    
    $objResponse->addRedirect($this->_page->_user->getUserPath('videos'));
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}