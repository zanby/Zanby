<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryDeleteCommentDo.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);
$comment = new Warecorp_Data_Comment($commentId);
if ( $gallery->getId() !== null && 
     $photo->getId() !== null && 
     $comment->id !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canDeleteCommentGallery($gallery, $comment, $this->currentUser, $this->_page->_user) ) {

    $comment->delete();

    $this->view->comments = $photo->getCommentsList();
    $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
    $this->view->photo = $photo;
    $this->view->gallery = $gallery;

    $content = $this->view->getContents('users/gallery/template.comments.list.tpl');
    $objResponse->addAssign('commentListContent', 'innerHTML', $content);

    $objResponse->showAjaxAlert(Warecorp::t('Comment deleted'));
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
