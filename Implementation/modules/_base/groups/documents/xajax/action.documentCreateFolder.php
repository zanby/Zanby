<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentCreateFolder.php.xml');
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
    if ( false == Warecorp_Document_AccessManager_Factory::create()->canCreateFolders($this->currentGroup, $objOwner, $this->_page->_user->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t("Access denied. You can't create folder") . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);        
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;       
    }

    $folder_id  = ( floor($this->params['folder_id']) == 0 ) ? null : floor($this->params['folder_id']);
    $name       = trim($this->params['folder_name']);
    
    /* check name of folder */
    if ( $name == "" ) {
        $errors[] =  Warecorp::t('Enter please Folder Name' );
        showErrors($objResponse, $this, $errors);        
    } elseif( !Warecorp_Form_Validation::isFolderNameValid($name) ) {
        $errors[] =  Warecorp::t('Incorrect Folder Name' );
        showErrors($objResponse, $this, $errors);        
    }
    
    /* check if folder already exists */
    $objFolderList = new Warecorp_Document_FolderList($objOwner);
    $objFolderList->setFolder($folder_id);
    if ( $objFolderList->isFolderExistsByName($name) ) {
        $errors[] =  Warecorp::t('A folder with the same name already exists.' );
        showErrors($objResponse, $this, $errors);        
    }
    
    $private = 0;
    if ( $private != 0 && $private != 1 ) $private = 0;

    $folder = new Warecorp_Document_FolderItem();
    $folder->setName            ($name)
           ->setOwnerType       ("group")
           ->setOwnerId         ($owner_id)
           ->setParentFolderId  ($folder_id)
           ->setCreatorId       ($this->_page->_user->getId())
           ->setPrivate         ($private);
    $folder->save();

    /** Send notification to host **/
    $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $folder, "FILE", "NEW", false, array($folder->getName()), "FOLDER" );
    
//    if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//        $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//        $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//        $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//        $mail->setSender($this->currentGroup);
//        $mail->addRecipient($this->currentGroup->getHost());
//        $mail->addParam('Group', $this->currentGroup);
//        $mail->addParam('action', "NEW");
//        $mail->addParam('section', "FILE");
//        $mail->addParam('chObject', $folder);
//        $mail->addParam('User', $this->_page->_user);
//        $mail->addParam('isPlural', false);
//        $mail->addParam('items', array($folder->getName()));
//        $mail->addParam('type', "FOLDER");
//        $mail->sendToPMB(true);
//        $mail->send();
//    }
    /** --- **/

    /* reload documents tree */
        $Script = "";
        $Script .= "tmpObj = { href : 'javascript:void(0);', label : '".Warecorp_Document_Tree::generateFolderLabel($folder, true, 'tree_0')."', id : '".$folder->getId()."', oType : 'folder', name : '".str_replace(array("\\","'"),array("\\\\","\'"),$folder->getName())."', ownerType : '".$folder->getOwnerType()."', ownerId : '".$folder->getOwnerId()."', callbackParam : '".$folder->getId()."'};";
        $Script .= "node_".$folder->getId()." = new YAHOO.widget.TextNode(tmpObj, ". (($folder_id === null)?"tree_0_root_node_group_".$owner_id."":"node_".$folder_id) .", true);";
        $Script .= 'node_'.$folder->getId().'.labelStyle = "";';
        $Script .= (($folder_id === null)?"tree_0_root_node_group_".$owner_id."":"node_".$folder_id).'.refresh();';
        $objResponse->addScript($Script);

    /* reload documents area to apply changes*/
        $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
        $objOwner = Warecorp_Group_Factory::loadById($this->params['owner_id']);        
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
        $objResponse->addAssign('addFolderPanelErrorContainer', 'style.display', '');
        $objResponse->addAssign('addFolderPanelErrorContent', 'innerHTML', $Content);
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Add Folder'));
        $popup_window->target('addFolderPanel');
        $popup_window->width(500)->height(250)->reload($objResponse);   
        $objResponse->printXml($controller->_page->Xajax->sEncoding);
        exit;                                       
    }
