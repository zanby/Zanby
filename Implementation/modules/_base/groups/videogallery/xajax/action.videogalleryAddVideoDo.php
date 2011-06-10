<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryAddVideoDo.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$video = Warecorp_Video_Factory::loadById($videoId);

if ( $gallery->getId() !== null &&
     $video->getId() !== null &&
     Warecorp_Video_AccessManager_Factory::create()->canCopyGallery($gallery, $this->currentGroup, $this->_page->_user) &&
	 $video->getCreatorId() == $this->_page->_user->getId()) {

    switch ( $data['mode'] ) {
        case 1 : // add photo to exists gallery
            $new_gallery = Warecorp_Video_Gallery_Factory::loadById($data['galleryId']);
            if ( $new_gallery->getId() !== null ) {
                $video->copy($new_gallery);
                $gallery->saveImportHistory($this->_page->_user, Warecorp_Video_Enum_ImportActionType::MERGE_VIDEO, $new_gallery->getId(), $video->getId());
                $importHistory = $gallery->getImportHistory($this->_page->_user, $video->getId());
                $this->view->importHistory = $importHistory;
                $importContent = $this->view->getContents('groups/videogallery/template.import.history.tpl');
                $objResponse->addAssign('importHistoryBlock', 'innerHTML', $importContent);
                $objResponse->showAjaxAlert(Warecorp::t('Video added'));
            }
            break;
        case 2: // add photo to new gallery
            $data['galleryName'] = empty($data['galleryName'])?'':trim($data['galleryName']);
            if (empty($data['galleryName'])) {
                $errors = 'Please name new gallery';
                $this->view->errors = $errors;
                $errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
                $objResponse->addAssign('errors', 'innerHTML', $errorcontent);
            } else {
                $new_gallery = Warecorp_Video_Gallery_Factory::createByOwner($this->_page->_user);
                $new_gallery->setOwnerType("user");
                $new_gallery->setOwnerId($this->_page->_user->getId());
                $new_gallery->setCreatorId($this->_page->_user->getId());
                $new_gallery->setTitle($data['galleryName']);
                $new_gallery->setDescription("");
                $new_gallery->setCreateDate(new Zend_Db_Expr('NOW()'));
                $new_gallery->setUpdateDate(new Zend_Db_Expr('NOW()'));
                $new_gallery->setSize(0);
                $new_gallery->setIsCreated(1);
                $new_gallery->setPrivate(0);
                $new_gallery->save();
                $video->copy($new_gallery);
                $gallery->saveImportHistory($this->_page->_user, Warecorp_Video_Enum_ImportActionType::SAVE_VIDEO, $new_gallery->getId(), $video->getId());

                $importHistory = $gallery->getImportHistory($this->_page->_user, $video->getId());
                $this->view->importHistory = $importHistory;
                $importContent = $this->view->getContents('groups/videogallery/template.import.history.tpl');
                $objResponse->addAssign('importHistoryBlock', 'innerHTML', $importContent);
                $objResponse->addScript('popup_window.close();');
                $objResponse->showAjaxAlert(Warecorp::t('Video added'));
            }
            break;
    }

    if ( $data['mode'] == 2 ) {
        $_SESSION['NEW_VIDEO_UPLOADED'] = 1;
    }

    if ( $data['mode'] == 2 || ( $data['mode'] != 2 && !isset($_SESSION['NEW_VIDEO_UPLOADED']) ) ) {
        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $new_gallery, "VIDEO", $data['mode'] == 2 ? "NEW" : "CHANGES", false, array($video->getTitle()) );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', $data['mode'] == 2 ? "NEW" : "CHANGES");
//            $mail->addParam('section', "VIDEO");
//            $mail->addParam('chObject', $new_gallery);
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->addParam('items', array($video->getTitle()));
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/
    }
    elseif ( $data['mode'] != 2 && isset($_SESSION['NEW_VIDEO_UPLOADED']) ) {
        unset($_SESSION['NEW_VIDEO_UPLOADED']);
    }

} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}

