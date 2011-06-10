<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentAddToMy.php.xml');
    $objResponse = new xajaxResponse();
    
    /* anonymous hasn't access */
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('documents');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    /* check documents */
    if ( !isset($this->params['groups']) || !$this->params['groups'] ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t('Error') . '</p>');
        $popup_window->width(350)->height(100)->open($objResponse);   
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;       
    }
    
    /**
     * Check owner (group)
     * Choose folder for upload new document - owner will be group 
     */
    $folder_item        = new Warecorp_Document_FolderItem($this->params['folder_id']);
    $folder_owner_id    = $folder_item->getOwnerId();
    if( !empty($folder_owner_id) )                      $this->params['owner_id'] = $folder_owner_id;
    elseif(isset($this->params['owner_id']))            $this->params['owner_id'] = floor($this->params['owner_id']);
    else                                                $this->params['owner_id'] = $this->currentGroup->getId();    
    $objOwner = Warecorp_Group_Factory::loadById($this->params['owner_id']);
    $owner_id = $objOwner->getId();
        /* if owner of new document is incorrect show info message : reload opened popup with new content */
        if ( null === $objOwner->getId() ) {
            $objResponse->addScript("DocumentApplication.panelAddFile.hide();");
            $popup_window = Warecorp_View_PopupWindow::getInstance();        
            $popup_window->title(Warecorp::t('Information'));
            $popup_window->content('<p>' . Warecorp::t('Unknown error. Reload your browser and try again.') . '</p>');
            $popup_window->width(350)->height(100)->reload($objResponse);        
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;       
        } 
    
    /* check permissions */
    if ( !Warecorp_Document_AccessManager_Factory::create()->canViewOwnerDocuments($this->currentGroup, $objOwner, $this->_page->_user->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t("You can't check in file(s)") . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }
    
//    $status = array(
//        'success' => array(), 
//        'error' => array( 
//            'ischeckout' =>array(),     // message
//            'notaccess' => array(),     // access denied
//            'weblink' => array(),       // is link
//            'shared' => array()         // is shared
//    ));
    $to_accept = array();
    $this->params['groups_accept']      = ( isset($this->params['groups_accept']) && $this->params['groups_accept'] ) ? $this->params['groups_accept'] : '';
    $this->params['groups_accept_name'] = ( isset($this->params['groups_accept_name']) ) ? trim($this->params['groups_accept_name']) : null;
    $this->params['groups_accept']      = explode(",", $this->params['groups_accept']);
    $this->params['groups_cancel']      = ( isset($this->params['groups_cancel']) && $this->params['groups_cancel'] ) ? $this->params['groups_cancel'] : '';
    $this->params['groups_cancel']      = explode(",", $this->params['groups_cancel']);
    
    $objDocumentList = new Warecorp_Document_List($this->_page->_user);
    $objDocumentList->setFolder(null);
    
    $this->params['groups'] = explode(",", $this->params['groups']);
    if ( 0 != sizeof($this->params['groups']) ) {
        foreach ( $this->params['groups'] as $_docId ) {
            $objDocument = new Warecorp_Document_Item($_docId);
            if ( null !== $objDocument->getId() ) {
                if (Warecorp_Document_AccessManager_Factory::create()->canViewDocument($objDocument, $this->currentGroup, $this->_page->_user)) {
                    
                    /* check if document with some name exists*/
                    if ( !in_array($objDocument->getId(), $this->params['groups_accept']) && $objDocumentList->isDocumentExistsByName($objDocument->getOriginalName()) ) {
                        if ( !in_array($objDocument->getId(), $this->params['groups_cancel']) ) $to_accept[] = $objDocument->getId();
                    } elseif ( in_array($objDocument->getId(), $this->params['groups_accept']) && ($this->params['groups_accept_name'] == '' || $objDocumentList->isDocumentExistsByName($this->params['groups_accept_name'])) ) {
                        if ( !in_array($objDocument->getId(), $this->params['groups_cancel']) ) $to_accept[] = $objDocument->getId();
                    } else {
                        $tags = Warecorp_Data_Tag::getPreparedTagsNamesByEntity($objDocument->getId(), $objDocument->EntityTypeId);
                        if ( in_array($objDocument->getId(), $this->params['groups_accept']) ) {
                            $objDocument->setOriginalName($this->params['groups_accept_name']);
                        }                        
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
                    }
                }                                
            }
        }
    }
    
    if ( sizeof($to_accept) != 0 ) {
        $objDocument = new Warecorp_Document_Item($to_accept[0]);   
        $documentName = ( null !== $this->params['groups_accept_name'] ) ? trim($this->params['groups_accept_name']) : $objDocument->getOriginalName();    
        $Script = "";
        $Script .= "$('#addtomyaccept_documentname').html('".htmlspecialchars($documentName)."');";
        $Script .= "$('#addtomyaccept_groups_accept_name').val('".htmlspecialchars($documentName)."');";
        $Script .= "$('#addtomyaccept_groups').val('".join(',', $to_accept)."');";
        $Script .= "$('#addtomyaccept_groups_cancel').val('".join(',', $this->params['groups_cancel'])."');";
        $Script .= "$('#addtomyaccept_groups_accept').val('".$to_accept[0]."');";
        $Script .= "DocumentApplication.addToMyAccept();";
        $objResponse->addScript($Script);
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->close($objResponse);
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;        
            
    