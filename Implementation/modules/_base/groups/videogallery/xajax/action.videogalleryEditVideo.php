<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryEditVide.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$video = Warecorp_Video_Factory::loadById($videoId);

if ( $gallery->getId() !== null && 
     $video->getId() !== null &&
     Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user)) {
    
    if ($video->getSource() == 'nonvideo' && (!defined('ALLOW_EDIT_NONVIDEO_VIDEO') || ALLOW_EDIT_NONVIDEO_VIDEO !== 1)) return;
    
    $tags = $video->setForceDbTags()->getVideoTags();
    /*
    $tags_str = array();
    if ( sizeof($tags) != 0 ) {
        foreach ( $tags as $tag ) $tags_str[] = $tag->getPreparedTagName();
    }
    */
    $tags_str = $tags; //= join(' ', $tags);
    
    $form = new Warecorp_Form('editPhotoForm', 'post', $this->currentGroup->getGroupPath('videogalleryEditVideoDo'));
    $this->view->form = $form;
    $this->view->sourceEnum = Warecorp_Video_Enum_VideoSource::getInstance();
    $this->view->gallery = $gallery;
    $this->view->video = $video;
    $this->view->videoTags = $tags_str;
    $this->view->JsApplication = $application;
    $content = $this->view->getContents('groups/videogallery/'.VIDEOMODEFOLDER.'xajax.edit.video.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->content($content);
    $popup_window->title('Edit Video');
    $popup_window->width(500)->height(495)->open($objResponse);

    $objResponse->addScript("tinyMCE.execCommand('mceAddControl', true, 'videoDescription');");
}
