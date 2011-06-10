<?php
    Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryDeleteGallVideoDo.php.xml");
$objResponse = new xajaxResponse();
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$video = Warecorp_Video_Factory::loadById($videoId);

if ( $gallery->getId() !== null && 
    $video->getId() !== null &&
    Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {

    if (SINGLEVIDEOMODE) {
        $gallery->delete();
        $this->_page->showAjaxAlert(Warecorp::t('Video deleted'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        $objResponse->addRedirect($this->currentUser->getUserPath('videos'));
        return;
    }else{
        $video->delete();
    }

    $objResponse = $this->videoeditshowpageAction(1, $gallery->getId());

    $objResponse->showAjaxAlert(Warecorp::t('Video deleted'));
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}