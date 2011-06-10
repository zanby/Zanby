<?php
Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryDeleteGallery.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);

if ( $gallery->getId() !== null && 
     Warecorp_Video_AccessManager_Factory::create()->canDeleteGallery($gallery, $this->currentUser, $this->_page->_user) ) {

    $gallery->delete();

    if ($new == false){ 
        if (SINGLEVIDEOMODE){
            $this->_page->showAjaxAlert(Warecorp::t('Video deleted'));
        }else{
            $this->_page->showAjaxAlert(Warecorp::t('Collection deleted'));
        }

        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    }

    $objResponse->addRedirect($this->_page->_user->getUserPath('videos'));
} else {
    if ($new == false){
        $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
    } else {
        $objResponse->addRedirect($this->_page->_user->getUserPath('videos'));
    }
}