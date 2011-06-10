<?php
    Warecorp::addTranslation("/modules/users/videogallery/action.videogalleryCreateTrackStatus.php.xml");
if (empty($this->params['gallery'])) {
    $this->_redirect($this->currentUser->getUserPath('videos'));
}

$gallery = Warecorp_Video_Gallery_Factory::loadById(floor($this->params['gallery']));

if ($gallery->getId() === null) $this->_redirect($this->currentUser->getUserPath('videos'));

if (!USE_VIDEO_SUSPENDED_PROCESSING) {
    $this->_redirect($this->currentUser->getUserPath('videogalleryedit/gallery').$gallery->getId()."/");
}

if ( !Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {
    $this->_page->showAjaxAlert(Warecorp::t('Access Denied'));
    $this->_redirect($this->currentUser->getUserPath('videos'));
}

$this->view->gallery = $gallery;

$this->view->bodyContent = 'users/videogallery/'.VIDEOMODEFOLDER.'create_trackstatus.tpl';
