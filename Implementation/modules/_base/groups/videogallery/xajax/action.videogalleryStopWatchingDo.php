<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryStopWatchingDo.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);

if ( $gallery->getId() !== null && 
     Warecorp_Video_AccessManager_Factory::create()->canStopWatchingGallery($gallery, $this->currentGroup, $this->_page->_user) ) {

    $gallery->stopWatch($this->_page->_user);
    
    $this->_page->showAjaxAlert(Warecorp::t('Watching stopped'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    
    $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));  
}