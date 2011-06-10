<?php

$objResponse = new xajaxResponse();

$videoId = intval($videoId);

if (Warecorp_Video_Standard::isVideoExists($videoId)) {
    $currentImage = Warecorp_Video_Factory::loadById($videoId);
    if (! Warecorp_Video_AccessManager_Factory::create()->canViewGallery($currentImage->getGallery(), $this->currentUser, $this->_page->_user)) {
        $currentImage = Warecorp_Video_Factory::createByOwner($this->currentUser);
    }
} else {
    $currentImage = Warecorp_Video_Factory::createByOwner($this->currentUser);
}

$this->view->currentImage = $currentImage;

$objResponse->addAssign("video_preview_block_MVCB","innerHTML", $this->view->getContents('content_objects/ddMyVideoContentBlock/videoPreviewBlock.tpl'));
