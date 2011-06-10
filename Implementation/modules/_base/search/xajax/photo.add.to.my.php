<?php
    Warecorp::addTranslation("/modules/search/xajax/photo.add.to.my.php.xml");
    $objResponse = new xajaxResponse () ;
    
    /* check params */
    if ( empty($this->params['gallery']) || empty($this->params['photo']) ) {
        Warecorp_Access::redirectToLoginXajax($this->_page->Xajax, BASE_URL.'/'.LOCALE.'/search/photos/preset/new/');
    }    
    $this->params['handle'] = empty($this->params['handle']) ? false : $this->params['handle'];    

    /* check user */
    if ( null === $this->_page->_user->getId() ) {
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    $gallery = Warecorp_Photo_Gallery_Factory::loadById($this->params['gallery']);
    $photo = Warecorp_Photo_Factory::loadById($this->params['photo']);
    if ( $gallery->getId() === null || $photo->getId() === null ) {
        $objResponse->showAjaxAlert(Warecorp::t('Unknown Error'));
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }

    /* check access */
    if ( !Warecorp_Photo_AccessManager_Factory::create()->canCopyGallery($gallery, $gallery->getOwner(), $this->_page->_user) ) {
        $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    if ( !$this->params['handle'] ) {
        $galleries = $this->_page->_user->getGalleries()
                          ->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
                          ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
                          ->getList();
        
        $this->view->gallery = $gallery;
        $this->view->photo = $photo;
        $this->view->galleries = $galleries;
        $Content = $this->view->getContents('search/xajax/photo.add.to.my.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->content($Content);
        $popup_window->title(Warecorp::t('Add Selected Photo to My Photos'));
        $popup_window->width(450)->height(200)->open($objResponse);
    
        $Script = '';
        $Script .= 'if ( YAHOO.util.Dom.get("addPhotoMode1") ) YAHOO.util.Dom.get("addPhotoMode1").checked = true;';
        $objResponse->addScript($Script);
    } else {
        switch ( $this->params['handle'] ) {
            case 1 : // add photo to exists gallery
                $new_gallery = Warecorp_Photo_Gallery_Factory::loadById($this->params['data']);
                if ( $new_gallery->getId() !== null ) {
                    $photo->copy($new_gallery, true);
                    $gallery->saveImportHistory($this->_page->_user, Warecorp_Photo_Enum_ImportActionType::MERGE_PHOTO, $new_gallery->getId(), $photo->getId());
                    $objResponse->showAjaxAlert(Warecorp::t('Photo added'));
                }
                /* close popup */
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->close($objResponse);                
                break;
            case 2: // add photo to new gallery
                $data['galleryName'] = empty($this->params['data']) ? '' : mb_substr( trim($this->params['data']), 0, 100, 'UTF-8');
                if (empty($data['galleryName'])) {
                    $errors = Warecorp::t('Please name new gallery');
                    $this->view->errors = $errors;
                    $errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
                    $objResponse->addAssign('errors', 'innerHTML', $errorcontent);
                } else {
                    $new_gallery = Warecorp_Photo_Gallery_Factory::createByOwner($this->_page->_user);
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
                    $photo->copy($new_gallery, true);
                    $gallery->saveImportHistory($this->_page->_user, Warecorp_Photo_Enum_ImportActionType::SAVE_PHOTO, $new_gallery->getId(), $photo->getId());
                    $objResponse->showAjaxAlert(Warecorp::t('Photo added'));
                    /* close popup */
                    $popup_window = Warecorp_View_PopupWindow::getInstance();
                    $popup_window->close($objResponse);                    
                }
                break;
        }
    }
        
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;               
    
