<?php
    Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryEditGallPhotoDo.php.xml');
    
    $objResponse = new xajaxResponse();
        
    $gallery = Warecorp_Photo_Gallery_Factory::loadById($this->params['gallery_id']);
    $photo = Warecorp_Photo_Factory::loadById($this->params['photo_id']);
    
    if ( $gallery->getId() !== null && $photo->getId() !== null && Warecorp_Photo_AccessManager_Factory::create()->canEditPhoto($photo, $this->currentGroup, $this->_page->_user) ) {      
        $form = new Warecorp_Form('editPhotoForm'.$photo->getId(), 'post', $this->currentGroup->getGroupPath('galleryEditGallPhotoDo'));
        $form->addRule('title', 'required', Warecorp::t('Enter please title'));
        if (isset($this->params['_wf__editPhotoForm'])) $_REQUEST['_wf__editPhotoForm'.$photo->getId()] = '1';
        if ( $form->validate($this->params) ) {
            $photo->setTitle($this->params["title"]);
            $photo->setDescription($this->params["description"]);
            $photo->setAdditionalInfo('');
            $photo->save();
    
            /** Send notification to host **/
            $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "PHOTO", "CHANGES", false );
            
//            if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setSender($this->currentGroup);
//                $mail->addRecipient($this->currentGroup->getHost());
//                $mail->addParam('Group', $this->currentGroup);
//                $mail->addParam('action', "CHANGES");
//                $mail->addParam('section', "PHOTO");
//                $mail->addParam('chObject', $gallery);
//                $mail->addParam('User', $this->_page->_user);
//                $mail->addParam('isPlural', false);
//                $mail->addParam('items', array());
//                $mail->sendToPMB(true);
//                $mail->send();
//            }
            /** --- **/
    
            $photo->deleteTags();
            $photo->addTags($this->params['tags']);
    
            $this->view->gallery = $gallery;
            $this->view->photo = $photo;
            $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
            $content = $this->view->getContents('groups/gallery/template.edit.photo.view.tpl');
            $objResponse->addAssign('photoContent'.$photo->getId(), 'innerHTML', $content);
            $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));
        } else {
            $photo->setTitle($this->params["title"]);
            $photo->setDescription($this->params["description"]);
            $photo->setAdditionalInfo('');
            $tags_str = $this->params['tags'];
    
            $this->view->form = $form;
            $this->view->gallery = $gallery;
            $this->view->photo = $photo;
            $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
            $this->view->photoTags = $tags_str;
            $content = $this->view->getContents('groups/gallery/template.edit.photo.edit.tpl');
            $objResponse->addAssign('photoContent'.$photo->getId(), 'innerHTML', $content);
        }
    } else {
        $objResponse->showAjaxAlert(Warecorp::t('You can not edit photo'));  
    }  
    
    
    $sContentHeader = "Content-type: text/xml;";
    if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
    if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
    header($sContentHeader);
    print $objResponse; exit;
