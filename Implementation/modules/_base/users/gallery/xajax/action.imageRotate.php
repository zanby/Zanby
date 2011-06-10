<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.imageRotate.php.xml");

$objResponse = new xajaxResponse () ;

$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if ( $gallery->getId() !== null &&
$photo->getId() !== null &&
Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {

    Warecorp_Image_Thumbnail::imageRotate($photo->getSrc()."_orig.jpg", $direction);
    $photo->deleteThumbnails();
    $objResponse->showAjaxAlert(Warecorp::t('Rotated'));

    //$objResponse->addAssign($elementId, "src", $photo->getSrc().$options.'.jpg?'.rand());
    $objResponse->addScript("document.getElementById('$elementId').src = document.getElementById('$elementId').src + ?".rand());

} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}