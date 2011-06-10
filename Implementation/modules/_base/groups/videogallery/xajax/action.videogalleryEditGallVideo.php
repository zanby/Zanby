<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryEditGallVideo.php.xml');

$objResponse = new xajaxResponse () ;

$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$video = Warecorp_Video_Factory::loadById($videoId);

if ( $gallery->getId() !== null && 
     $video->getId() !== null &&
     Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user)  &&
	 $video->getSource() != Warecorp_Video_Enum_VideoSource::NONVIDEO) {
    
/*    $tags = $photo->getTagsList();
    $tags_str = array();
    if ( sizeof($tags) != 0 ) {
        foreach ( $tags as $tag ) $tags_str[] = $tag->getPreparedTagName();
    }
    $tags_str = join(' ', $tags_str);*/
    
    $form = new Warecorp_Form('editPhotoForm'.$video->getId(), 'post', $this->currentGroup->getGroupPath('videogalleryEditGallVideoDo'));
    $this->view->sourceEnum = Warecorp_Video_Enum_VideoSource::getInstance();
    $this->view->form = $form;
    $this->view->gallery = $gallery;
    $this->view->video = $video;
//    $this->view->photoTags = $tags_str;
    $content = $this->view->getContents('groups/videogallery/'.VIDEOMODEFOLDER.'template.edit.video.edit.tpl');
    $objResponse->addAssign('photoContent'.$video->getId(), 'innerHTML', $content);
    $objResponse->addScript("tinyMCE.execCommand('mceAddControl', true, 'videoDescription".$video->getId()."');");
}
