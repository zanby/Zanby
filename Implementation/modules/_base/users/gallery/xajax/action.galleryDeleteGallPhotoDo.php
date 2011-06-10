<?php
Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryDeleteGallPhotoDo.php.xml");
$objResponse = new xajaxResponse();

$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if ( $gallery->getId() !== null && 
    $photo->getId() !== null &&
    Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {

    $photo->delete();
    $objResponse = $this->editshowpageAction(1, $gallery->getId());

    $objResponse->showAjaxAlert(Warecorp::t('Photo deleted'));
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
