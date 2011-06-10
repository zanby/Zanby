<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryMoveToDo.php.xml');

    $objResponse = new xajaxResponse() ;
    
    $application = empty($application)?'PGPLApplication':$application;
    
    if (empty($collectionId) || empty($videoId)) return;

    if (!Warecorp_Video_AccessManager_Factory::create()->canUploadVideos($this->currentGroup, $this->_page->_user)) return;

    $gallery = Warecorp_Video_Gallery_Factory::loadById($collectionId);

    if (!$gallery->getId()) return;
    if ($gallery->getOwnerType() != 'group' || $gallery->getOwnerId() != $this->currentGroup->getId()) return;

    $video = Warecorp_Video_Factory::loadById($videoId);
    
    if (!$video->getId()) return;
    if ($video->getGallery()->getOwnerType() != 'group' || $video->getGallery()->getOwnerId() != $this->currentGroup->getId()) return;    
    
    $video->setGalleryId($gallery->getId());
    $video->save();

    /** Send notification to host **/
    $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "VIDEO", "CHANGES", false, array($video->getTitle()) );
    
//    if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//        $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//        $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//        $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//        $mail->setSender($this->currentGroup);
//        $mail->addRecipient($this->currentGroup->getHost());
//        $mail->addParam('Group', $this->currentGroup);
//        $mail->addParam('action', "CHANGES");
//        $mail->addParam('section', "VIDEO");
//        $mail->addParam('chObject', $gallery);
//        $mail->addParam('User', $this->_page->_user);
//        $mail->addParam('isPlural', false);
//        $mail->addParam('items', array($video->getTitle()));
//        $mail->sendToPMB(true);
//        $mail->send();
//    }
    /** --- **/

    $this->_page->showAjaxAlert(Warecorp::t('Video moved'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    $objResponse->addRedirect($this->currentGroup->getGroupPath("videos"));