<?php
Warecorp::addTranslation("/modules/users/videogallery/action.videogalleryDelete.php.xml");

$gallery_id = isset($this->params['gallery']) ? (int)floor($this->params['gallery']) : 0;

if ($gallery_id == 0 || ! Warecorp_Video_Gallery_Abstract::isGalleryExists($gallery_id)) {
    $this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
}

$gallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id);

if ( !Warecorp_Video_AccessManager_Factory::create()->canDeleteGallery($gallery, $this->currentUser, $this->_page->_user) ) {
    $this->_redirect($this->currentUser->getUserPath('videos'));
}

$gallery->delete();

$this->_redirect("/".$this->_page->Locale."/videos/");

