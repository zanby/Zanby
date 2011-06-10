<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryAddVideo.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$video = Warecorp_Video_Factory::loadById($videoId);

if ( $gallery->getId() !== null && 
     $video->getId() !== null &&
     Warecorp_Video_AccessManager_Factory::create()->canCopyGallery($gallery, $this->currentGroup, $this->_page->_user) ) {

    $galleries = $this->_page->_user->getVideoGalleries()
                      ->setSharingMode(Warecorp_Video_Enum_SharingMode::OWN)
                      ->setWatchingMode(Warecorp_Video_Enum_WatchingMode::OWN)
                      ->getList();
    
    $this->view->gallery = $gallery;
    $this->view->video = $video;
    $this->view->galleries = $galleries;
    $this->view->JsApplication = $application;
    $Content = $this->view->getContents('groups/videogallery/xajax.add.video.tpl');
    
    $Script = '';
    $Script .= 'if ( YAHOO.util.Dom.get("addPhotoMode1") ) YAHOO.util.Dom.get("addPhotoMode1").checked = true;';
    $objResponse->addScript($Script);

    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->content($Content);
    $popup_window->title(Warecorp::t('Add Selected Video to My Videos'));
    $popup_window->width(500)->height(350)->open($objResponse);

} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));  
}                  

