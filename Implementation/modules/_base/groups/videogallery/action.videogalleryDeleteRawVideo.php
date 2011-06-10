<?php
Warecorp::addTranslation('/modules/groups/videogallery/action.videogalleryDeleteRawVideo.php.xml');

    if (!isset($this->params['id'])) {
        $this->videosAction();
        return;
    }
    $video = Warecorp_Video_Factory::loadById((int)$this->params['id']);
    if ($video->getId() === null) {
        $this->videosAction();
        return;
    }

    if (!Warecorp_Video_AccessManager_Factory::create()->canDeleteRawVideo($video, $this->currentGroup, $this->_page->_user)) {
        $this->videosAction();
        return;
    }

    $gallery = Warecorp_Video_Gallery_Factory::loadById($video->getGalleryId());

    $video->deleteRawVideo();

    if ( isset($_SESSION['NEW_VIDEO_UPLOADED']) ) {
        unset($_SESSION['NEW_VIDEO_UPLOADED']);
    }
    else {
        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "VIDEO", "CHANGES", array($video->getTitle()) );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', "CHANGES");
//            $mail->addParam('section', "VIDEO");
//            $mail->addParam('chObject', $gallery);
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->addParam('items', array($video->getTitle()));
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/
    }

    $this->_page->showAjaxAlert(Warecorp::t('Raw Video deleted'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();

    $this->_redirect($this->currentGroup->getGroupPath('videogalleryedit').'gallery/'.$video->getGalleryId().'/');
