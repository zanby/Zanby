<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryAddCommentDo.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$video = Warecorp_Video_Factory::loadById($videoId);

if ( $gallery->getId() !== null &&
    $video->getId() !== null &&
    Warecorp_Video_AccessManager_Factory::create()->canPostCommentsGallery($gallery, $this->currentGroup, $this->_page->_user) ) {

    if (mb_strlen($message, 'UTF-8') > 2000) {
        $objResponse->addScript("addError('".Warecorp::t("Text too long (%s characters max)", 2000)."', 'divErrorNew')");
    }
    else {
        $objResponse->addScript('PGPLApplication.cancelEditComment();');

        $video->addComment($message);
        $this->view->comments = $video->getCommentsList();
        $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
        $this->view->video = $video;
        $this->view->gallery = $gallery;

        $content = $this->view->getContents('groups/videogallery/template.comments.list.tpl');
        $objResponse->addAssign('commentListContent', 'innerHTML', $content);

        $objResponse->showAjaxAlert(Warecorp::t('Comment added'));
         if ( FACEBOOK_USED ) {
            $params = array(
                'title' => htmlspecialchars($video->getTitle()), 
                'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
            );
            $action_links[] = array('text' => 'View Photo', 'href' => $this->currentGroup->getGroupPath('galleryView/id/'.$videoId));
            $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_VIDEO, $params); 
            if ( $message ) $objMessage['message'] .= "\n" . htmlspecialchars($message);
            $result = Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);

            if ( false === $result && '' != $js = Warecorp_Facebook_Feed::getJsResponse() ) $objResponse->addScript($js);
        }
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('You can not add comment'));
}

