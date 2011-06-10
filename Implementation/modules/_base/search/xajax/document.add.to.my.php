<?php
    Warecorp::addTranslation("/modules/search/xajax/document.add.to.my.php.xml");
    $objResponse = new xajaxResponse () ;
    
    /* check params */
    if ( empty($this->params['document']) ) {
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }    
    $this->params['handle'] = empty($this->params['handle']) ? false : $this->params['handle'];    

    /* check user */
    if ( null === $this->_page->_user->getId() ) {
        Warecorp_Access::redirectToLoginXajax($this->_page->Xajax, BASE_URL.'/'.LOCALE.'/search/documents/preset/new/');
    }

    $objDocument = new Warecorp_Document_Item($this->params['document']);
    if ( $objDocument->getId() === null ) {
        $objResponse->showAjaxAlert(Warecorp::t('Unknown Error'));
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    $objOwner = $objDocument->getOwner();
    
    /* check access */
    if ( !Warecorp_Document_AccessManager_Factory::create()->canViewDocument($objDocument, $objOwner, $this->_page->_user->getId()) ) {
        $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    $tags = Warecorp_Data_Tag::getPreparedTagsNamesByEntity($objDocument->getId(), $objDocument->EntityTypeId);
    $objDocument->setId(null)
                ->setOwnerId($this->_page->_user->getId())
                ->setOwnerType('user')
                ->setCreatorId($this->_page->_user->getId())
                ->setFolderId(null);
    $objDocument->save();
    if (is_array($tags) && $tags) $objDocument->addTags(implode(" ", $tags));
    
    /* create the copy of document file */                       
    if ( !$objDocument->getIsLink() ) {
        $filePath = DOC_ROOT.$objDocument->getFilePath();
        if ($objDocument->getFilePath() && file_exists(DOC_ROOT.$objDocument->getFilePath())) {
            copy($filePath, DOC_ROOT.'/upload/documents/'.md5($objDocument->getId()).'.file');
        }                        
    }                                                        
    $objResponse->showAjaxAlert(Warecorp::t('Document added'));
    
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;  