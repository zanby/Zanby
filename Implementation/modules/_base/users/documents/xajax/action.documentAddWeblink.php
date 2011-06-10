<?php
    Warecorp::addTranslation('/modules/users/documents/xajax/action.documentAddWeblink.php.xml');    
    $objResponse = new xajaxResponse();

    /* anonymous hasn't access */
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentUser->getUserPath('documents');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
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
    else                                                $this->params['owner_id'] = $this->currentUser->getId();    
    $objOwner = new Warecorp_User('id', $this->params['owner_id']);
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
    
    /**
     * Check permissions
     * if user can not create new documents show info message about it
     * : reload opened popup with new content
     */
    if ( !Warecorp_Document_AccessManager_Factory::create()->canCreateOwnerDocuments($this->currentUser, $objOwner, $this->_page->_user->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t('Access denied: You can\'t add document link') . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);        
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;       
    }
            
    /* check entered url */
    if ( empty($this->params['weblink']) || trim($this->params['weblink']) == '' ) {
        $errors[] =  Warecorp::t('Please enter url' );
        showErrors($objResponse, $this, $errors);        
    }
            
    /* Save document */
        $this->params['folder_id'] = (!isset($this->params['folder_id']) || $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
        $newDoc = new Warecorp_Document_Item( );
        $newDoc->setOwnerType( 'user' )->setOwnerId( $this->params['owner_id'] )
               ->setCreatorId( $this->_page->_user->getId() )
               ->setOriginalName( $this->params['weblink'] )
               ->setMimeType( 'application/octet-stream' )
               ->setDescription( $this->params['weblink_description'] )
               ->setCreationDate( new Zend_Db_Expr( 'NOW()' ) )
               ->setPrivate( $this->params['weblink_privacy'] )
               ->setFolderId( $this->params['folder_id'] )
               ->setIsLink( 1 );
        $newDoc->save();
    /* Save tags for document */
        if ( trim( $this->params['weblink_tags'] ) != '' ) $newDoc->addTags( $this->params['weblink_tags'] );
                
    /* reload documents area to apply changes*/
        $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
        $objOwner = new Warecorp_User('id', $this->params['owner_id']);        
        $this->documentChangeContent($objResponse, $objOwner, $this->params['folder_id']);
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->close($objResponse);
        
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;        
    
    
    /* ------------------------------------------------------- */
    
    
    /**
     * Helper to show form errors
     * @param xajaxResponse $objResponse
     * @param Warecorp_Controller Action
     * @param array $errors
     */
    function showErrors($objResponse, $controller, $errors) {
        $controller->view->errors = $errors;
        $Content = $controller->view->getContents('_design/form/form_errors_summary.tpl');
        $objResponse->addAssign('addWebLinkPanelErrorContainer', 'style.display', '');
        $objResponse->addAssign('addWebLinkPanelErrorContent', 'innerHTML', $Content);
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Add Document Link'));
        $popup_window->target('addWebLinkPanel');
        $popup_window->width(500)->height(400)->reload($objResponse);   
        $objResponse->printXml($controller->_page->Xajax->sEncoding);
        exit;                                       
    }
    
