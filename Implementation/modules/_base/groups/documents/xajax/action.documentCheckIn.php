<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentCheckIn.php.xml');    
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
    
    /* check document */
    $objDocument = new Warecorp_Document_Item($this->params['groups']);
    if ( null === $objDocument->getId() ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->close($objResponse);
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;       
    }
        
    /**
     * if document is weblink
     */
    if ( $objDocument->getIsLink() ) {
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
    $AccessManager = Warecorp_Document_AccessManager_Factory::create();
    if ( !$AccessManager->canManageOwnerDocuments($this->currentGroup, $objOwner, $this->_page->_user->getId()) &&
         !$AccessManager->canEditDocument($objDocument, $this->currentGroup, $this->_page->_user) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t("You can't check in file(s)") . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;
    }
    
    /**
     * if document isn't checked out
     */
    if ( !$objDocument->getIsCheckOut() ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t('You must check out document first.') . '</p>');
        $popup_window->width(350)->height(100)->open($objResponse);   
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    /**
     * if document is checked out by another user
     */
    if ( $objDocument->getCheckOutUserId() != $this->_page->_user->getId() ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t("Access Denied : Document '%s' was checked out by other user.", htmlspecialchars($objDocument->getOriginalName())) . '</p>');
        $popup_window->width(350)->height(100)->open($objResponse);   
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    /**
     * if document is shared
     */
    if ( $objDocument->isDocumentShared($objDocument->getId(),'group', $this->currentGroup->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t("Access Denied : Document '%s' is shared. You can not check in shared documents.", htmlspecialchars($objDocument->getOriginalName())) . '</p>');
        $popup_window->width(350)->height(100)->open($objResponse);   
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    /**
     * Uploaded file isn't exist or isn't valid
     * build error message
     */    
    if ( empty($_FILES["checkin_file"]) || empty($_FILES["checkin_file"]['tmp_name']) ) {
        $errors[] = Warecorp::t('Please select file to upload');
        showErrors($objResponse, $this, $errors);
    }

    if ( $_FILES["checkin_file"]["name"] != $objDocument->getOriginalName() ) {
        $errors[] = Warecorp::t('Document to check in must be named as original document');
        showErrors($objResponse, $this, $errors);
    }
    
    /**
     * Uploaded file exists but isn't valid
     * build error message
     */
    if ( !empty($_FILES["checkin_file"]["name"]) && !empty($_FILES['checkin_file']['error']) ) { 
        switch ($_FILES['checkin_file']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errors[] = Warecorp::t("File is too big. Max filesize is %s Mb", array(floor($_max_size/1024/1024)));
                break;
            case UPLOAD_ERR_NO_FILE:
                $errors[] = Warecorp::t("Please select correct file for upload.");
                break;
            default:
                $errors[] = Warecorp::t("Upload failed");
                break;
        }
        showErrors($objResponse, $this, $errors);
    } 
        
    $_max_size  = DOCUMENTS_SIZE_LIMIT;
    $_max_size  = is_int($_max_size) ? sprintf("%01.1f", $_max_size/1024/1024)."M" : $_max_size;
    
    /**
     * Check max size for file
     */
    if (filesize($_FILES["checkin_file"]["tmp_name"]) > DOCUMENTS_SIZE_LIMIT) {
        $errors[] =  Warecorp::t("File '%s' is too big.  Max filesize is %s Mb", array($_FILES["checkin_file"]["name"], floor($_max_size/1024/1024)));
        showErrors($objResponse, $this, $errors);
    }

    /* Handle 'check in' for selected documents */
    $upload_dir = DOC_ROOT.'/upload/documents/';
    $revisionFileName  = tempnam($upload_dir, '__');
    if ( Warecorp_File_Item::uploadFile( $_FILES['checkin_file']['tmp_name'], $revisionFileName ) ) {
        /* create revision */
        $objRevision = new Warecorp_Document_Revision();
        $objRevision->setDocumentId($objDocument->getId());
        $objRevision->setRevisionDescription($this->params['checkin_reason']);
        $objRevision->setRevisionCreatorId($this->_page->_user->getId());
        if ( true == $objRevision->create($revisionFileName) ) {
            $objDocument->checkIn();
            $objDocument->setRevisionId($objRevision->getRevisionId());
            $objDocument->save();            
        }
        
        $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
        $owner = Warecorp_Group_Factory::loadById($this->params['owner_id']);        
        $this->documentChangeContent($objResponse, $owner, $this->params['folder_id']);
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->close($objResponse);
        
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;        
    } 
    /**
     * Any error from upload process
     */
    else {
        $errors[] =  Warecorp::t('Upload error!' );
        showErrors($objResponse, $this, $errors);
    }
        
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
        $objResponse->addAssign('checkInPanelErrorContainer', 'style.display', '');
        $objResponse->addAssign('checkInPanelErrorContent', 'innerHTML', $Content);
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Check In'));
        $popup_window->target('checkInPanel');
        $popup_window->width(500)->height(400)->reload($objResponse);   
        $objResponse->printXml($controller->_page->Xajax->sEncoding);
        exit;                                       
    }
    
