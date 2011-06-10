<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentAddFile.php.xml');
    $objResponse = new xajaxResponse();
    
    /* anonymous hasn't access */
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('documents');
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
    
    /* Check permissions */
    if ( !Warecorp_Document_AccessManager_Factory::create()->canCreateOwnerDocuments($this->currentGroup, $objOwner, $this->_page->_user->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t('Access denied: You can\'t add document') . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);        
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
    
    /**
     * Uploaded file isn't exist or isn't valid
     * build error message
     */        
    if ( empty($_FILES["file"]) || empty($_FILES["file"]['tmp_name']) ) {
        $errors[] = Warecorp::t('Please select file to upload');
        showErrors($objResponse, $this, $errors);
    }
    
    /**
     * Uploaded file exists but isn't valid
     * build error message
     */
    if ( !empty($_FILES["file"]["name"]) && !empty($_FILES['file']['error']) ) { 
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errors[] = Warecorp::t("File is too big. Max filesize is &s Mb", array(floor($_max_size/1024/1024)));
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
    if (filesize($_FILES["file"]["tmp_name"]) > DOCUMENTS_SIZE_LIMIT) {
        $errors[] =  Warecorp::t("File '%s' is too big. Max filesize is %s Mb", array($_FILES["file"]["name"], floor($_max_size/1024/1024)));
        showErrors($objResponse, $this, $errors);
    }
    
    /* file must be zip if bulk upload choosed*/
    if ( !empty($this->params['file_is_bulk']) && $this->params['file_is_bulk'] == 1 ) {
        $path = pathinfo($_FILES["file"]["name"]);
        if ( strtolower($path['extension']) != 'zip' ) {
            $errors[] =  Warecorp::t("To use bulk upload file must be valid zip archive. File '%s' isn't valid zip archive.", array($_FILES["file"]["name"]));
            showErrors($objResponse, $this, $errors);            
        }
    }
        
    /* Handle document upload */
    $this->params['folder_id'] = (!isset($this->params['folder_id']) || $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
    $upload_dir = DOC_ROOT.'/upload/documents/';
    $file_name = tempnam($upload_dir, '__');

    /* check if document with some name exists*/
    $objDocumentList = new Warecorp_Document_List($objOwner);
    $objDocumentList->setFolder($this->params['folder_id']);
    if ( $objDocumentList->isDocumentExistsByName($_FILES["file"]["name"]) ) {
        $errors[] =  Warecorp::t('Document with some name already exists.' );
        showErrors($objResponse, $this, $errors);                
    }
    
    if ( Warecorp_File_Item::uploadFile( $_FILES['file']['tmp_name'], $file_name ) ) {
        if ( !empty($this->params['file_is_bulk']) && $this->params['file_is_bulk'] == 1 ) {
            $objZip = new ZipArchive();
            if ( true == $zipStatus = $objZip->open($file_name) ) {
                $sessionId = session_id();
                $tmpFolderName = '_tmp_'.$sessionId.'_'.$this->_page->_user->getId().'/';
                $objZip->extractTo($upload_dir.$tmpFolderName);
                exec('chmod 0777 -R '.$upload_dir.$tmpFolderName);
            
                $objFolderList      = new Warecorp_Document_FolderList($objOwner);
                $objDocumentList    = new Warecorp_Document_List($objOwner);    
                $callback = array(
                    'errors' => array(
                    ),
                    'success' => array(
                        'files' => array(), 
                        'folders' => array()                    
                    ),
                    'script' => array()
                );
                
                scan($this->params, $callback, $upload_dir.$tmpFolderName, $this->_page->_user->getId(), $objFolderList, $objDocumentList, $this->params['folder_id']);                 
                exec('rm -rf '.$upload_dir.$tmpFolderName);  
                if ( file_exists($file_name) ) unlink($file_name);
                
                if ( sizeof($callback['script']) != 0 ) $objResponse->addScript(join('', $callback['script']));
            } else {
                switch ( $zipStatus ) {
                    case 'ZIPARCHIVE::ER_EXISTS' :
                        break;
                    case 'ZIPARCHIVE::ER_INCONS' :
                        break;
                    case 'ZIPARCHIVE::ER_INVAL' :
                        break;
                    case 'ZIPARCHIVE::ER_MEMORY' :
                        break;
                    case 'ZIPARCHIVE::ER_NOENT' :
                        break;
                    case 'ZIPARCHIVE::ER_NOZIP' :
                        break;
                    case 'ZIPARCHIVE::ER_OPEN' :
                        break;
                    case 'ZIPARCHIVE::ER_READ' :
                        break;
                    case 'ZIPARCHIVE::ER_SEEK' :
                        break;
                }
                $errors[] =  Warecorp::t("File '%s' isn't valid zip archive.", array($_FILES["file"]["name"]));
                showErrors($objResponse, $this, $errors);                            
            }                          
        } else {
            /* Save document */
                $newDoc = new Warecorp_Document_Item( );
                $newDoc->setOwnerType( 'group' )->setOwnerId( $owner_id )
                       ->setCreatorId( $this->_page->_user->getId() )
                       ->setOriginalName( $_FILES['file']['name'] )
                       ->setMimeType( $_FILES['file']['type'] )
                       ->setDescription( $this->params['file_description'] )
                       ->setCreationDate( new Zend_Db_Expr( 'NOW()' ) )
                       ->setPrivate( $this->params['file_privacy'] )
                       ->setFolderId( $this->params['folder_id'] );
                $newDoc->save();     
                if ( FACEBOOK_USED ) {
                    $paramsFB = array(
                        'title' => htmlspecialchars($newDoc->getOriginalName()), 
                        'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                    );                                                 
                    $action_links[] = array('text' => 'View Document', 'href' => $this->currentGroup->getGroupPath('documents/'));
                    $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_DOCUMENT, $paramsFB);    
                    $result = Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);
                    if ( false === $result && '' != $js = Warecorp_Facebook_Feed::getJsResponse() ) $objResponse->addScript($js);             
                }           
            /* Save tags for document */
                if ( trim( $this->params['file_tags'] ) != '' ) $newDoc->addTags( $this->params['file_tags'] );
            /**/
                rename( $file_name, $upload_dir . md5( $newDoc->getId() ) . '.file' );
            /* Send notification to host */
            $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $newDoc, "FILE", "NEW", false, array ($newDoc->getName()), "FILE" );                
        }
                            
        /* reload documents area to apply changes*/
            $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
            $objOwner = Warecorp_Group_Factory::loadById($this->params['owner_id']);        
            $this->documentChangeContent($objResponse, $objOwner, $this->params['folder_id']);
            
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
        $objResponse->addAssign('addFilePanelErrorContainer', 'style.display', '');
        $objResponse->addAssign('addFilePanelErrorContent', 'innerHTML', $Content);
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Add Document'));
        $popup_window->target('addFilePanel');
        $popup_window->width(500)->height(480)->reload($objResponse);   
        $objResponse->printXml($controller->_page->Xajax->sEncoding);
        exit;                                       
    }
    
    /**
     * Create folders and files from zip archive
     *
     * @param unknown_type $params
     * @param unknown_type $callback
     * @param unknown_type $dir
     * @param unknown_type $userID
     * @param unknown_type $objFolderList
     * @param unknown_type $objDocumentList
     * @param unknown_type $parentFolderID
     */
    function scan( &$params, &$callback, $dir, $userID, $objFolderList, $objDocumentList, $parentFolderID = null ) {
        $dh  = opendir($dir);
        while (false !== ($filename = readdir($dh))) {
            if ( $filename != '.' && $filename != '..' ) {
                if ( is_dir($dir.$filename) ) {
                    /* find folder whith some name */
                        $objFolderList->setFolder($parentFolderID);
                        $folderID = $objFolderList->isFolderExistsByName($filename);
                        $folderID = ( $folderID ) ? $folderID : null;
                    /* folder wasn't found, create it */
                        if ( null === $folderID ) {
                            $folder = new Warecorp_Document_FolderItem();
                            $folder->setName            ($filename)
                                   ->setOwnerType       ($objFolderList->getOwnerType())
                                   ->setOwnerId         ($objFolderList->getOwner()->getId())
                                   ->setParentFolderId  ($parentFolderID)
                                   ->setCreatorId       ($userID)
                                   ->setPrivate         (1);
                            $folder->save();     
                            $folderID = $folder->getId();  
                            $callback['success']['folders'][] = $folderID;    

                            $Script = "";
                            $Script .= "tmpObj = { href : 'javascript:void(0);', label : '".Warecorp_Document_Tree::generateFolderLabel($folder, true, 'tree_0')."', id : '".$folder->getId()."', callbackParam : '".$folder->getId()."', oType : 'folder', name : '".str_replace(array("\\","'"),array("\\\\","\'"),$folder->getName())."'};";
                            $Script .= "node_".$folder->getId()." = new YAHOO.widget.TextNode(tmpObj, ". (($folder->getParentFolderId() === null)?"tree_0_root_node_".$folder->getOwnerType()."_".$folder->getOwnerId()."":"node_".$folder->getParentFolderId()) .", true);";
                            $Script .= 'node_'.$folder->getId().'.labelStyle = "";';
                            $Script .= (($folder->getParentFolderId() === null)?"tree_0_root_node_".$folder->getOwnerType()."_".$folder->getOwnerId()."":"node_".$folder->getParentFolderId()).'.refresh();';
                            $callback['script'][] = $Script;
                        }    
                } else {
                    $objDocumentList->setFolder($parentFolderID);
                    $documentId = $objDocumentList->isDocumentExistsByName($filename);
                    /* update existing document */
                    if ( $documentId ) {
                        $objDocument = new Warecorp_Document_Item($documentId);
                        $canDo = false;
                        if ( !$objDocument->getIsCheckOut() ) $canDo = true;
                        elseif ( $objDocument->getCheckOutUserId() == $userID ) $canDo = true; 
                        else $canDo = false;
 
                        if ( $canDo ) {                        
                            /* create revision */
                            $objRevision = new Warecorp_Document_Revision();
                            $objRevision->setDocumentId($objDocument->getId());
                            $objRevision->setRevisionDescription('Bulk Upload');
                            $objRevision->setRevisionCreatorId($userID);
                            if ( true == $objRevision->create($dir.$filename) ) {
                                $objDocument->checkIn();
                                $objDocument->setRevisionId($objRevision->getRevisionId());
                                $objDocument->save();                                                                
                            }              
                        }          
                    } 
                    /* create new document */
                    else {
                        $newDoc = new Warecorp_Document_Item( );
                        $newDoc->setOwnerType($objDocumentList->getOwnerType())
                               ->setOwnerId($objDocumentList->getOwner()->getId())
                               ->setCreatorId($userID)
                               ->setOriginalName($filename)
                               ->setMimeType('application/octet-stream')
                               ->setDescription($params['file_description'])
                               ->setCreationDate(new Zend_Db_Expr('NOW()'))
                               ->setPrivate($params['file_privacy'])
                               ->setFolderId($parentFolderID);
                        $newDoc->save();  
                        if ( trim( $params['file_tags'] ) != '' ) $newDoc->addTags( $params['file_tags'] );
                        rename( $dir.$filename, DOC_ROOT.'/upload/documents/' . md5( $newDoc->getId() ) . '.file' );
                        $callback['success']['files'][] = $newDoc->getId();                  
                    }
                }
                /* folders recursion */
                if ( is_dir( $dir.$filename ) ) {    
                    scan( $params, $callback, $dir.$filename.'/', $userID, $objFolderList, $objDocumentList, $folderID );
                }
            } 
        }
    }
    
