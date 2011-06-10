<?php
Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryUpdateCommentDo.php.xml");

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$video = Warecorp_Video_Factory::loadById($videoId);
$comment = new Warecorp_Data_Comment($commentId);
if ( $gallery->getId() !== null &&
    $video->getId() !== null &&
    $comment->id !== null &&
    Warecorp_Video_AccessManager_Factory::create()->canEditCommentGallery($gallery, $comment, $this->currentUser, $this->_page->_user) ) {

    if (mb_strlen($message, 'UTF-8') > 2000) {
        $objResponse->addScript("addError('".Warecorp::t("Text too long (%s characters max)", 2000)."', 'divErrorEdit_".$comment->id."')");
    }
    else {
        $objResponse->addScript('PGPLApplication.cancelEditComment();');

        $comment->content = $message;
        $comment->save();

        $this->view->comments = $video->getCommentsList();
        $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
        $this->view->video = $video;
        $this->view->gallery = $gallery;

        $content = $this->view->getContents('users/videogallery/template.comments.list.tpl');
        $objResponse->addAssign('commentListContent', 'innerHTML', $content);
        $objResponse->showAjaxAlert(Warecorp::t('Comment updated'));
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
