<?php
Warecorp::addTranslation('/modules/groups/gallery/action.galleryShare.php.xml');

$gallery_id = isset($this->params['gallery']) ? (int)floor($this->params['gallery']) : 0;

$galleries_list = $this->_page->_user->getArtifacts()->getGalleriesListAssoc();

if (! key_exists($gallery_id, $galleries_list)){
    $this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
}

$gallery = new Warecorp_Photo_Gallery($gallery_id);
$gallery->shareGalleryToGroup($this->currentGroup->getId(), $gallery_id);

$this->_redirect("/".$this->_page->Locale."/photos/");

