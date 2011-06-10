<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentEdit.php.xml');    
    $objResponse = new xajaxResponse();
    
    $isHandler = ( isset($this->params['handle']) && !empty($this->params['handle']) ) ? true : false;
    
    /* anonymous hasn't access */
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('documents');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }

    /* check document or folder */
    if ( !isset($this->params['itemId']) || !$this->params['itemId'] ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t('Error') . '</p>');
        $popup_window->width(350)->height(100)->open($objResponse);   
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;       
    }
    
    /* check item type */
    if ( !isset($this->params['itemType']) || !$this->params['itemType'] || !in_array($this->params['itemType'], array('document', 'weblink', 'folder')) ) {
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
    if ( !Warecorp_Document_AccessManager_Factory::create()->canManageOwnerDocuments($this->currentGroup, $objOwner, $this->_page->_user->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t("You can't check in file(s)") . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }

    /* check item */
    $objDocument = null;
    $objFolder = null;
    switch ( $this->params['itemType'] ) {
        case 'document' :
        case 'weblink' :
            /* check item */
            $objDocument = new Warecorp_Document_Item($this->params['itemId']);
            if ( null === $objDocument->getId() ) {
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->close($objResponse);
                $objResponse->printXml($this->_page->Xajax->sEncoding);
                exit;       
            }            
            /* check if item is shared */
            if ( $objDocument->isDocumentShared($objDocument->getId(), 'group', $objOwner->getId()) ) {
                $popup_window = Warecorp_View_PopupWindow::getInstance();        
                $popup_window->title(Warecorp::t('Information'));
                $popup_window->content('<p>' . Warecorp::t('Document is shared. You can not edit shared documents.') . '</p>');
                $popup_window->width(350)->height(100)->open($objResponse);   
                $objResponse->printXml($this->_page->Xajax->sEncoding);
                exit;       
            }
            break;
        case 'folder' :
            $objFolder = new Warecorp_Document_FolderItem($this->params['itemId']);
            if ( null === $objFolder->getId() ) {
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->close($objResponse);
                $objResponse->printXml($this->_page->Xajax->sEncoding);
                exit;       
            }            
            break;
    }
    
    /**
     * HANDLER
     */
    if ( $isHandler ) {
        /* save document */
        if ( null !== $objDocument ) {
            /* save document */
            if ( !$objDocument->getIsLink() ) {
                $objDocument->setDescription($this->params['file_description'])->setPrivate($this->params['file_privacy']);
                $objDocument->save();
                $objDocument->deleteTags();
                $objDocument->addTags($this->params['file_tags']);            
            } 
            /* save document link */
            else {
                /* check entered url */
                if ( empty($this->params['weblink']) || trim($this->params['weblink']) == '' ) {
                    $errors[] =  Warecorp::t('Please enter url' );                    
                    showErrors($objResponse, $this, $errors, 'editWebLinkPanel');        
                }                
                $objDocument->setOriginalName($this->params['weblink'])->setDescription($this->params['weblink_description'])->setPrivate($this->params['weblink_privacy']);
                $objDocument->save();
                $objDocument->deleteTags();
                $objDocument->addTags($this->params['weblink_tags']);                            
            }
            if ( FACEBOOK_USED ) {
                $paramsFB = array(
                    'title' => htmlspecialchars($objDocument->getOriginalName()), 
                    'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                );                                                 
                $action_links[] = array('text' => 'View Document', 'href' => $this->currentGroup->getGroupPath('documents/'));
                $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_DOCUMENT, $paramsFB);    
                $result = Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);
                if ( false === $result && '' != $js = Warecorp_Facebook_Feed::getJsResponse() ) $objResponse->addScript($js);             
            } 
            $title = str_replace( "\n"," ",$objDocument->getOriginalName()." ".$objDocument->getDescription());
            $objResponse->addScript("document.getElementById('docid".trim($objDocument->getId())."').title = '".$title."';");
            
  
            
            /** Send notification to host **/
            $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $objDocument, "FILE", "CHANGES", false, array($objDocument->getName()), "FILE" );
            
//            if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setSender($this->currentGroup);
//                $mail->addRecipient($this->currentGroup->getHost());
//                $mail->addParam('Group', $this->currentGroup);
//                $mail->addParam('action', "CHANGES");
//                $mail->addParam('section', "FILE");
//                $mail->addParam('chObject', $objDocument);
//                $mail->addParam('User', $this->_page->_user);
//                $mail->addParam('isPlural', false);
//                $mail->addParam('items', array($objDocument->getName()));
//                $mail->addParam('type', "FILE");
//                $mail->sendToPMB(true);
//                $mail->send();
//            }
            /** --- **/
        } 
        /* save folder */
        elseif ( null !== $objFolder ) {
            $name = trim($this->params['folder_name']);
            
            /* check name of folder */
            if ( $name == "" ) {
                $errors[] =  Warecorp::t('Enter please Folder Name' );
                showErrors($objResponse, $this, $errors, 'editFolderPanel');        
            } elseif( !Warecorp_Form_Validation::isFolderNameValid($name) ) {
                $errors[] =  Warecorp::t('Incorrect Folder Name' );
                showErrors($objResponse, $this, $errors, 'editFolderPanel');        
            }
            
            /* check if folder already exists */
            $objFolderList = new Warecorp_Document_FolderList($objOwner);
            $objFolderList->setFolder($objFolder->getParentFolderId());
            if ( $fid = $objFolderList->isFolderExistsByName($name) ) {
                if ( $fid != $objFolder->getId() ) {
                    $errors[] =  Warecorp::t('A folder with the same name already exists.' );
                    showErrors($objResponse, $this, $errors, 'editFolderPanel');
                }        
            }
            
            $objFolder->setName($name);
            $objFolder->save();
        
            $Script = "var currNode = tree_0.getNodeByProperty('id', ".$objFolder->getId().");";
            $Script .= "var parent = currNode.parent;";
            $Script .= "currNode.data.name = '".str_replace(array("\\","'"),array("\\\\","\'"),$objFolder->getName())."';";
            $Script .= "currNode.label = '".Warecorp_Document_Tree::generateFolderLabel($objFolder, true, 'tree_0')."';";
            $Script .= "parent.refresh();";
            $Script .= "DocumentApplication.panelEditFolder.hide();";
            $objResponse->addScript($Script);
            
            /** Send notification to host **/
            $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $objFolder, "FILE", "NEW", false, array($objFolder->getName()), "FOLDER" );
            
//            if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setSender($this->currentGroup);
//                $mail->addRecipient($this->currentGroup->getHost());
//                $mail->addParam('Group', $this->currentGroup);
//                $mail->addParam('action', "NEW");
//                $mail->addParam('section', "FILE");
//                $mail->addParam('chObject', $objFolder);
//                $mail->addParam('User', $this->_page->_user);
//                $mail->addParam('isPlural', false);
//                $mail->addParam('items', array($objFolder->getName()));
//                $mail->addParam('type', "FOLDER");
//                $mail->sendToPMB(true);
//                $mail->send();
//            }
            /** --- **/
        }

        $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
        $owner = Warecorp_Group_Factory::loadById($this->params['owner_id']);        
        $this->documentChangeContent($objResponse, $owner, $this->params['folder_id']);
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->close($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;           
    }
        
    if ( null !== $objDocument ) {
        $tags = $objDocument->setForceDbTags(true)->getTagsList();
        $tagStr = array();
        if ( sizeof($tags) != 0 ) foreach ( $tags as $tag ) $tagStr[] = $tag->getPreparedTagName();
        
        if ( !$objDocument->getIsLink() ) {
            $objResponse->addAssign("edit_file_description", "value", htmlspecialchars($objDocument->getDescription()));
            $objResponse->addAssign("edit_file_tags", "value", htmlspecialchars(join(" ", $tagStr)));
            if ( $objDocument->getPrivate() ) $objResponse->addAssign("edit_file_privacy_private", "checked", true);
            else $objResponse->addAssign("edit_file_privacy_public", "checked", true);
            
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t('Edit Document'));
            $popup_window->target('editFilePanel');
            $popup_window->width(500)->height(350)->open($objResponse);  
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;   
        } else {
            $objResponse->addAssign('edit_weblink', 'value', htmlspecialchars($objDocument->getOriginalName()));
            $objResponse->addAssign("edit_weblink_description", "value", htmlspecialchars($objDocument->getDescription()));
            $objResponse->addAssign("edit_weblink_tags", "value", htmlspecialchars(join(" ", $tagStr)));
            if ( $objDocument->getPrivate() ) $objResponse->addAssign("edit_weblink_privacy_private", "checked", true);
            else $objResponse->addAssign("edit_weblink_privacy_public", "checked", true);            
            
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t('Edit Document Link'));
            $popup_window->target('editWebLinkPanel');
            $popup_window->width(500)->height(350)->open($objResponse);  
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;   
        }        
    } elseif ( null !== $objFolder ) {
        $objResponse->addAssign('edit_folder_name', 'value', htmlspecialchars($objFolder->getName()));
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t('Edit Folder'));
        $popup_window->target('editFolderPanel');
        $popup_window->width(500)->height(150)->open($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }

            
    
            

    
    /* ------------------------------------------------------- */
    
   
    /**
     * Helper to show form errors
     * @param xajaxResponse $objResponse
     * @param Warecorp_Controller Action
     * @param array $errors
     */
    function showErrors($objResponse, $controller, $errors, $target) {
        if ( $target == 'editWebLinkPanel' ) {
            $title = Warecorp::t('Edit Document Link');
            $errorsPrefix = 'editWebLinkPanelError';
            $width = 500;
            $height = 380;
        } elseif ( $target == 'editFolderPanel' ) {
            $title = Warecorp::t('Edit Folder');
            $errorsPrefix = 'editFolderPanelError';
            $width = 500;
            $height = 180;
        }
        $controller->view->errors = $errors;
        $Content = $controller->view->getContents('_design/form/form_errors_summary.tpl');
        $objResponse->addAssign($errorsPrefix.'Container', 'style.display', '');
        $objResponse->addAssign($errorsPrefix.'Content', 'innerHTML', $Content);
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title($title);
        $popup_window->target($target);
        $popup_window->width($width)->height($height)->reload($objResponse);   
        $objResponse->printXml($controller->_page->Xajax->sEncoding);
        exit;                                       
    }
