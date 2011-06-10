<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryDeleteCommentDo.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$video = Warecorp_Video_Factory::loadById($videoId);
$comment = new Warecorp_Data_Comment($commentId);
if ( $gallery->getId() !== null && 
     $video->getId() !== null && 
     $comment->id !== null &&
     Warecorp_Video_AccessManager_Factory::create()->canDeleteCommentGallery($gallery, $comment, $this->currentGroup, $this->_page->_user) ) {

    $comment->delete();
    
    $this->view->comments = $video->getCommentsList();
    $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
    $this->view->video = $video;
    $this->view->gallery = $gallery;
    
    $content = $this->view->getContents('groups/videogallery/template.comments.list.tpl');
    $objResponse->addAssign('commentListContent', 'innerHTML', $content);
    
    $objResponse->showAjaxAlert(Warecorp::t('Comment deleted'));
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
