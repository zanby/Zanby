<?php
    Warecorp::addTranslation('/modules/users/documents/xajax/action.documentDeleteGroup.php.xml');
    $objResponse = new xajaxResponse();

    /* anonymous hasn't access */
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentUser->getUserPath('documents');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    $isAnyChecked = ( ( isset($this->params['groups']) && $this->params['groups'] ) || ( isset($this->params['fgroups']) && $this->params['fgroups'] ) ) ? true : false;
    if ( !$isAnyChecked ) {
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
        $popup_window->content('<p>' . Warecorp::t("You can't delete file or filders") . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }
    
    $isChanged = false;
    
    if ( isset($this->params['groups']) && $this->params['groups'] ) {
        $this->params['groups'] = explode(",", $this->params['groups']);
        if ( 0 != sizeof($this->params['groups']) ) {
            foreach ( $this->params['groups'] as $_docId ) {
                $objDocument = new Warecorp_Document_Item($_docId);
                if ( null !== $objDocument->getId() ) {
                    if ( $objDocument->isDocumentShared($objDocument->getId(),'user', $this->currentUser->getId()) ) {
                        $objDocument->unshareDocument('user', $this->currentUser->getId());
                    } else {
                        $objDocument->delete();
                        $isChanged = true;       
                    }
                }
            }
        }
    }
    
    $to_accept = array();
    $this->params['fgroups_accept'] = ( isset($this->params['fgroups_accept']) && $this->params['fgroups_accept'] ) ? $this->params['fgroups_accept'] : '';
    $this->params['fgroups_accept'] = explode(",", $this->params['fgroups_accept']);
    $this->params['fgroups_cancel'] = ( isset($this->params['fgroups_cancel']) && $this->params['fgroups_cancel'] ) ? $this->params['fgroups_cancel'] : '';
    $this->params['fgroups_cancel'] = explode(",", $this->params['fgroups_cancel']);
    if ( isset($this->params['fgroups']) && $this->params['fgroups'] ) {
        $this->params['fgroups'] = explode(",", $this->params['fgroups']);
        if ( 0 != sizeof($this->params['fgroups']) ) {
            foreach ( $this->params['fgroups'] as $_folderId ) {
                $objFolder = new Warecorp_Document_FolderItem($_folderId);
                if ( null !== $objFolder->getId() ) {
                    if ( !in_array($objFolder->getId(), $this->params['fgroups_accept']) && !$objFolder->isDeletable() ) {
                        if ( !in_array($objFolder->getId(), $this->params['fgroups_cancel']) ) $to_accept[] = $objFolder->getId();
                    } else {
                        $objFolder->deleteFolderRecursively();       
                        $Script = "";
                        $Script .= "var currNode = tree_0.getNodeByProperty('id', ".$objFolder->getId().");";
                        $Script .= "var parent = currNode.parent;";
                        $Script .= "tree_0.removeNode(currNode);";
                        $Script .= "parent.refresh();";
                        $objResponse->addScript($Script);
                    }
                }
            }
        }
    }
    
    if ( sizeof($to_accept) != 0 ) {
        $objFolder = new Warecorp_Document_FolderItem($to_accept[0]);        
        $Script = "";
        $Script .= "$('#groupdeleteaccept_foldername').html('".htmlspecialchars($objFolder->getName())."');";
        $Script .= "$('#groupdeleteaccept_fgroups').val('".join(',', $to_accept)."');";
        $Script .= "$('#groupdeleteaccept_fgroups_cancel').val('".join(',', $this->params['fgroups_cancel'])."');";
        $Script .= "$('#groupdeleteaccept_fgroups_accept').val('".$to_accept[0]."');";
        $Script .= "DocumentApplication.deleteGroupAccept();";
        $objResponse->addScript($Script);
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }

    
    $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
    $owner = new Warecorp_User('id', $this->params['owner_id']);        
    $this->documentChangeContent($objResponse, $owner, $this->params['folder_id']);
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->close($objResponse);
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;        
  