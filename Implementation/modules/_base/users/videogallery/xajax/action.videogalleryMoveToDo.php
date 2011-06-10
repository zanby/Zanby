<?php
    Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryMoveToDo.php.xml");
    $objResponse = new xajaxResponse() ;

    $application = empty($application)?'PGPLApplication':$application;

    if (empty($collectionId) || empty($videoId)) return;

    if (!Warecorp_Video_AccessManager_Factory::create()->canUploadVideos($this->currentUser, $this->_page->_user)) return;

    $gallery = Warecorp_Video_Gallery_Factory::loadById($collectionId);

    if (!$gallery->getId()) return;
    if ($gallery->getOwnerType() != 'user' || $gallery->getOwnerId() != $this->_page->_user->getId()) return;

    $video = Warecorp_Video_Factory::loadById($videoId);

    if (!$video->getId()) return;
    if ($video->getGallery()->getOwnerType() != 'user' || $video->getGallery()->getOwnerId() != $this->_page->_user->getId()) return;

    $video->setGalleryId($gallery->getId());
    $video->save();

    $this->_page->showAjaxAlert(Warecorp::t('Video moved'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    $objResponse->addRedirect($this->_page->_user->getUserPath("videos"));