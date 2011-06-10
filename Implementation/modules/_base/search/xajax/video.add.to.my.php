<?php
    Warecorp::addTranslation("/modules/search/xajax/video.add.to.my.php.xml");
    $objResponse = new xajaxResponse () ;
    
    /* check params */
    if ( empty($this->params['gallery']) || empty($this->params['video']) ) {
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }    
    $this->params['handle'] = empty($this->params['handle']) ? false : $this->params['handle'];    

    /* check user */
    if ( null === $this->_page->_user->getId() ) {
        Warecorp_Access::redirectToLoginXajax($this->_page->Xajax, BASE_URL.'/'.LOCALE.'/search/videos/preset/new/');
    }
    
    $gallery = Warecorp_Video_Gallery_Factory::loadById($this->params['gallery']);
    $video = Warecorp_Video_Factory::loadById($this->params['video']);
    if ( $gallery->getId() === null || $video->getId() === null ) {
        $objResponse->showAjaxAlert(Warecorp::t('Unknown Error'));
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    /* check access */
    if ( !Warecorp_Video_AccessManager_Factory::create()->canCopyGallery($gallery, $gallery->getOwner(), $this->_page->_user) ) {
        $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    $new_gallery = Warecorp_Video_Gallery_Factory::createByOwner($this->_page->_user);
    $new_gallery->setOwnerType("user");
    $new_gallery->setOwnerId($this->_page->_user->getId());
    $new_gallery->setCreatorId($this->_page->_user->getId());
    $new_gallery->setTitle($video->getTitle());
    $new_gallery->setDescription($video->getDescription());
    $new_gallery->setCreateDate(new Zend_Db_Expr('NOW()'));
    $new_gallery->setUpdateDate(new Zend_Db_Expr('NOW()'));
    $new_gallery->setSize(0);
    $new_gallery->setIsCreated(1);
    $new_gallery->setPrivate(0);
    $new_gallery->save();
    $video->copy($new_gallery);
    $gallery->saveImportHistory($this->_page->_user, Warecorp_Video_Enum_ImportActionType::SAVE_VIDEO, $new_gallery->getId(), $video->getId());
    $objResponse->showAjaxAlert(Warecorp::t('Video added'));
    
    
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;               
    