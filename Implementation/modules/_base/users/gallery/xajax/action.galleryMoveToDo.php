<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryMoveToDo.php.xml");
    $objResponse = new xajaxResponse() ;

    $application = empty($application)?'PGPLApplication':$application;

    if (empty($galleryId) || empty($photoId)) return;

    if (!Warecorp_Photo_AccessManager_Factory::create()->canUploadPhotos($this->currentUser, $this->_page->_user)) return;

    $gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);

    if (!$gallery->getId()) return;
    if ($gallery->getOwnerType() != 'user' || $gallery->getOwnerId() != $this->_page->_user->getId()) return;

    $photo = Warecorp_Photo_Factory::loadById($photoId);

    if (!$photo->getId()) return;
    if ($photo->getGallery()->getOwnerType() != 'user' || $photo->getGallery()->getOwnerId() != $this->_page->_user->getId()) return;

    $photo->setGalleryId($gallery->getId());
    $photo->save();

    $this->_page->showAjaxAlert(Warecorp::t('Photo Moved'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    $objResponse->addRedirect($this->_page->_user->getUserPath("photos"));