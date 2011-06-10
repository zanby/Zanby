<?php
$objResponse = new xajaxResponse () ;

$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$video = Warecorp_Video_Factory::loadById($videoId);

if ( $gallery->getId() !== null && $video->getId() !== null  ) {
    $tags = $video->getTagsList();
    $tags_str = array();
    if ( sizeof($tags) != 0 ) {
        foreach ( $tags as $tag ) $tags_str[] = $tag->getPreparedTagName();
    }
    $tags_str = join(' ', $tags_str);
    
    $this->view->gallery = $gallery;
    $this->view->video = $video;
    $this->view->videoTags = $tags_str;
    if (SINGLEVIDEOMODE) {
        $objResponse->addRedirect($this->currentUser->getUserPath('videos'));
        return;
    }else{
        $content = $this->view->getContents('users/videogallery/template.edit.video.view.tpl');
    }    
    $content = $this->view->getContents('users/videogallery/template.edit.video.view.tpl');
    $objResponse->addAssign('photoContent'.$video->getId(), 'innerHTML', $content);
}
