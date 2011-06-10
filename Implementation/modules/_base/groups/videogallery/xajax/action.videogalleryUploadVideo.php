<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryUploadVideo.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);

if ( $gallery->getId() !== null && 
     Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
    
    $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentGroup->getGroupPath('videogalleryUploadVideoDo'));
    $this->view->uploadPhotosForm = $form;
    $this->view->sourceEnum = Warecorp_Video_Enum_VideoSource::getInstance();
    $this->view->versionSwitcher = 0; 
    $this->view->gallery = $gallery;    
    $content = $this->view->getContents('groups/videogallery/xajax.upload.videos.tpl');
    
    $objResponse->addScript('turnOnSWFUpload();');

    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->content($content);
    $popup_window->title('');
    $popup_window->width(500)->height(350)->open($objResponse);
}
