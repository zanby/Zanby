<?php
Warecorp::addTranslation('/modules/groups/videogallery/action.videogalleryCreateTrackStatus.php.xml');

if (empty($this->params['gallery'])) {
    $this->_redirect($this->currentGroup->getGroupPath('videos'));
}
$gallery = Warecorp_Video_Gallery_Factory::loadById(floor($this->params['gallery']));
if ($gallery->getId() === null) $this->_redirect($this->currentGroup->getGroupPath('videos'));
if (!USE_VIDEO_SUSPENDED_PROCESSING) {
    $this->_redirect($this->currentGroup->getGroupPath('videogalleryedit/gallery').$gallery->getId()."/");
}
if ( !Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
    $this->_page->showAjaxAlert(Warecorp::t('Access Denied'));
    $this->_redirect($this->currentGroup->getGroupPath('videos'));
}

$this->view->gallery = $gallery;

$this->view->bodyContent = 'groups/videogallery/'.VIDEOMODEFOLDER.'create_trackstatus.tpl';

$_SESSION['NEW_VIDEO_UPLOADED'] = 1;
