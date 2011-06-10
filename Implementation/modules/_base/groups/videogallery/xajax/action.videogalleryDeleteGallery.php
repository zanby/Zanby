<?php
    Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryDeleteGallery.php.xml');
    
    $objResponse = new xajaxResponse () ;
    $gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
    
    if ( $gallery->getId() !== null && 
         Warecorp_Video_AccessManager_Factory::create()->canDeleteGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
        
        $gallery->delete();
    
        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "VIDEO", "DELETE", false );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', "DELETE");
//            $mail->addParam('section', "VIDEO");
//            $mail->addParam('chObject', $gallery);
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/
    
        if ($new == false){    
            if (SINGLEVIDEOMODE){   
                $this->_page->showAjaxAlert('Video deleted');
            }else{
                $this->_page->showAjaxAlert(Warecorp::t('Collection deleted'));
            }
            
            $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        }
        
        $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
    } else {
        if ($new == false){
            $objResponse->showAjaxAlert(Warecorp::t('Access denied'));  
        } else {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
        }
    }
