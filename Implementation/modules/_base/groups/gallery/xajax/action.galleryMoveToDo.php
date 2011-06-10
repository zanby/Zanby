<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryMoveToDo.php.xml');

    $objResponse = new xajaxResponse() ;
    
    $application = empty($application)?'PGPLApplication':$application;
    
    if (empty($galleryId) || empty($photoId)) return;

    if (!Warecorp_Photo_AccessManager_Factory::create()->canUploadPhotos($this->currentGroup, $this->_page->_user)) return;

    $gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
    
    if (!$gallery->getId()) return;
    if ($gallery->getOwnerType() != 'group' || $gallery->getOwnerId() != $this->currentGroup->getId()) return;
    
    $photo = Warecorp_Photo_Factory::loadById($photoId);
    
    if (!$photo->getId()) return;
    if ($photo->getGallery()->getOwnerType() != 'group' || $photo->getGallery()->getOwnerId() != $this->currentGroup->getId()) return;    
    
    $photo->setGalleryId($gallery->getId());
    $photo->save();

    /** Send notification to host **/
    $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "PHOTO", "CHANGES", false );
    
//    if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//        $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//        $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//        $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//        $mail->setSender($this->currentGroup);
//        $mail->addRecipient($this->currentGroup->getHost());
//        $mail->addParam('Group', $this->currentGroup);
//        $mail->addParam('action', "CHANGES");
//        $mail->addParam('section', "PHOTO");
//        $mail->addParam('chObject', $gallery);
//        $mail->addParam('User', $this->_page->_user);
//        $mail->addParam('isPlural', false);
//        $mail->addParam('items', array($photo->getTitle()));
//        $mail->sendToPMB(true);
//        $mail->send();
//    }
    /** --- **/

    $this->_page->showAjaxAlert(Warecorp::t('Photo Moved'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    $objResponse->addRedirect($this->currentGroup->getGroupPath("photos"));