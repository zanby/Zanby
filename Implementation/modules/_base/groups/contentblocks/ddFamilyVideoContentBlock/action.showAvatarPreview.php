<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddFamilyVideoContentBlock/action.selectAvatarPreview.php.xml');

$objResponse = new xajaxResponse();

$videoId = intval($videoId);  

if (Warecorp_Video_Standard::isVideoExists($videoId)) {
    $currentImage = Warecorp_Video_Factory::loadById($videoId);
  
    if (! Warecorp_Video_AccessManager_Factory::create()->canViewGallery($currentImage->getGallery(), $this->currentGroup, $this->_page->_user)) {
        $currentImage = Warecorp_Video_Factory::createByOwner($this->currentGroup);
    }
} else {
    $currentImage = Warecorp_Video_Factory::createByOwner($this->currentGroup);
}

$this->view->currentImage = $currentImage;
$objResponse->addAssign("video_preview_block_FVCB","innerHTML", $this->view->getContents('content_objects/ddFamilyVideoContentBlock/videoPreviewBlock.tpl'));
