<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryAddCommentDo.php.xml');
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if ( $gallery->getId() !== null &&
    $photo->getId() !== null &&
    Warecorp_Photo_AccessManager_Factory::create()->canPostCommentsGallery($gallery, $this->currentGroup, $this->_page->_user) ) {

    if (mb_strlen($message, 'UTF-8') > 2000) {
        $objResponse->addScript("addError('".Warecorp::t("Text too long (%s characters max)", 2000)."', 'divErrorNew')");
    }
    else {
        $objResponse->addScript('PGPLApplication.cancelEditComment();');
        $photo->addComment($message);
        $this->view->comments = $photo->getCommentsList();
        $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
        $this->view->photo = $photo;
        $this->view->gallery = $gallery;

        $content = $this->view->getContents('groups/gallery/template.comments.list.tpl');
        $objResponse->addAssign('commentListContent', 'innerHTML', $content);
        $objResponse->showAjaxAlert(Warecorp::t('Comment added'));

        /**
         * Facebook Feed
         */
		if ( FACEBOOK_USED ) {
			$params = array(
				'title' => htmlspecialchars($photo->getTitle()), 
				'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
			);
			$action_links[] = array('text' => 'View Photo', 'href' => $this->currentGroup->getGroupPath('galleryView/id/'.$photoId));
			$objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_VIDEO, $params); 
			if ( $message ) $objMessage['message'] .= "\n" . htmlspecialchars($message);
			$result = Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);

			if ( false === $result && '' != $js = Warecorp_Facebook_Feed::getJsResponse() ) $objResponse->addScript($js);
		}
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('You can not add comment'));
}

