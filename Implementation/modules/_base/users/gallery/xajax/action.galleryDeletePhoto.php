<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryDeletePhoto.php.xml");
$objResponse = new xajaxResponse();

$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if ( $gallery->getId() !== null && 
    $photo->getId() !== null &&
    Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {

    $photo->delete();

    $photos = $gallery->getPhotos()->returnAsAssoc()->getList();
    if ( sizeof($photos) == 0 ) {
        $this->_page->showAjaxAlert(Warecorp::t('Photo deleted'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        $objResponse->addRedirect($this->currentUser->getUserPath('photos'));
    } else {
        $photos = array_keys($photos);
        $photoId = $photos[0];
        $this->_page->showAjaxAlert(Warecorp::t('Photo Deleted'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        $objResponse->addRedirect($this->currentUser->getUserPath('galleryView/id').$photoId.'/');
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}