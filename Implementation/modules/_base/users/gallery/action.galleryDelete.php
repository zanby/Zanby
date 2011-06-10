<?php
Warecorp::addTranslation("/modules/users/gallery/action.galleryDelete.php.xml");
$gallery_id = isset($this->params['gallery']) ? (int)floor($this->params['gallery']) : 0;

if ($gallery_id == 0 || ! Warecorp_Photo_Gallery_Abstract::isGalleryExists($gallery_id)) {
    $this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
}

$gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);

if ( !Warecorp_Photo_AccessManager_Factory::create()->canDeleteGallery($gallery, $this->currentUser, $this->_page->_user) ) {
    $this->_redirect($this->currentUser->getUserPath('photos'));
}

$gallery->delete();

$this->_redirect("/".$this->_page->Locale."/photos/");