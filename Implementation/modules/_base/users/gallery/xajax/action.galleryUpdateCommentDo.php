<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryUpdateCommentDo.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);
$comment = new Warecorp_Data_Comment($commentId);
if ( $gallery->getId() !== null &&
    $photo->getId() !== null &&
    $comment->id !== null &&
    Warecorp_Photo_AccessManager_Factory::create()->canEditCommentGallery($gallery, $comment, $this->currentUser, $this->_page->_user) ) {

    if (mb_strlen($message, 'UTF-8') > 2000) {
        $objResponse->addScript("addError('".Warecorp::t("Text too long (%s characters max)", 2000)."', 'divErrorEdit_".$comment->id."')");
    }
    else {
        $objResponse->addScript('PGPLApplication.cancelEditComment();');
        $comment->content = $message;
        $comment->save();

        $this->view->comments = $photo->getCommentsList();
        $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
        $this->view->photo = $photo;
        $this->view->gallery = $gallery;

        $content = $this->view->getContents('users/gallery/template.comments.list.tpl');
        $objResponse->addAssign('commentListContent', 'innerHTML', $content);

        $objResponse->showAjaxAlert(Warecorp::t('Comment updated'));
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
