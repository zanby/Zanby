<?php
    Warecorp::addTranslation('/modules/users/documents/xajax/action.documentRevertRevision.php.xml');    
    $objResponse = new xajaxResponse();

    /* anonymous hasn't access */
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentUser->getUserPath('documents');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                       
    }
    
    /* check revision */
    if ( !isset($this->params['revision']) || !$this->params['revision'] ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t('Error') . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }
    
    /* check revision */
    $objRevision = new Warecorp_Document_Revision($this->params['revision']);
    if ( null === $objRevision->getRevisionId() ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->close($objResponse);
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
        
    /* check permissions */
    if ( !Warecorp_Document_AccessManager_Factory::create()->canManageOwnerDocuments($this->currentUser, $objOwner, $this->_page->_user->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t("You can't revert revision for this file") . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }
    
    $objRevision->revert();
    
    $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
    $owner = new Warecorp_User('id', $this->params['owner_id']);        
    $this->documentChangeContent($objResponse, $owner, $this->params['folder_id']);

    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->close($objResponse);
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;       
    