<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryEditGallPhotoDo.php.xml");
    $objResponse = new xajaxResponse();
    
    $gallery = Warecorp_Photo_Gallery_Factory::loadById($this->params['gallery_id']);
    $photo = Warecorp_Photo_Factory::loadById($this->params['photo_id']);
    
    if ( $gallery->getId() !== null && $photo->getId() !== null && Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {      
        $form = new Warecorp_Form('editPhotoForm'.$photo->getId(), 'post', $this->currentUser->getUserPath('galleryEditGallPhotoDo'));
        $form->addRule('title', 'required', Warecorp::t('Enter please title'));
        if (isset($this->params['_wf__editPhotoForm'])) $_REQUEST['_wf__editPhotoForm'.$photo->getId()] = '1';
        if ( $form->validate($this->params) ) {
            $photo->setTitle($this->params["title"]);
            $photo->setDescription($this->params["description"]);
            $photo->setAdditionalInfo('');
            $photo->save();
            $photo->deleteTags();
            $photo->addTags($this->params['tags']);
    
            $this->view->gallery = $gallery;
            $this->view->photo = $photo;
            $content = $this->view->getContents('users/gallery/template.edit.photo.view.tpl');
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
            $this->view->photoTags = $tags_str;
            $content = $this->view->getContents('users/gallery/template.edit.photo.edit.tpl');
            $objResponse->addAssign('photoContent'.$photo->getId(), 'innerHTML', $content);
        }
    } else {
        $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
    }
    
    $sContentHeader = "Content-type: text/xml;";
    if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
    if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
    header($sContentHeader);
    print $objResponse; exit;
