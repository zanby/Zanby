<?php
    Warecorp::addTranslation("/modules/users/videogallery/action.videogalleryDeleteRawVideo.php.xml");
    if (!isset($this->params['id'])) {
        $this->videosAction();
        return;
    }
    $video = Warecorp_Video_Factory::loadById((int)$this->params['id']);
    if ($video->getId() === null) {
        $this->videosAction();
        return;
    }

    if (!Warecorp_Video_AccessManager_Factory::create()->canDeleteRawVideo($video, $this->currentUser, $this->_page->_user)) {
        $this->videosAction();
        return;
    }

    $video->deleteRawVideo();

    $this->_page->showAjaxAlert(Warecorp::t('Raw Video deleted'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();

    $this->_redirect($this->_page->_user->getUserPath('videogalleryedit').'gallery/'.$video->getGalleryId().'/');
