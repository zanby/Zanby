<?php
Warecorp::addTranslation('/modules/groups/gallery/action.galleryUnshare.php.xml');
$gallery_id = isset($this->params['gallery']) ? (int)floor($this->params['gallery']) : 0;

$galleries = $this->currentUser->getArtifacts()->getGalleriesListAssoc();

if (! key_exists($gallery_id, $galleries)){
    $this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
}

$gallery = new Warecorp_Photo_Gallery($gallery_id);
$gallery->unshareGalleryFromUser($this->currentUser->getId(), $gallery_id);

$this->_redirect("/".$this->_page->Locale."/photos/");

