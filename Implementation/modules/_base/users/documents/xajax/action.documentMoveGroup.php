<?php
    Warecorp::addTranslation('/modules/users/documents/xajax/action.documentMoveGroup.php.xml');
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
    
    /* Check permissions */
    if ( !Warecorp_Document_AccessManager_Factory::create()->canManageOwnerDocuments($this->currentUser, $objOwner, $this->_page->_user->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t('Access denied: You can\'t add document') . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);        
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;       
    }
    
    if ( empty($this->params['to_folder_id']) && empty($this->params['to_owner_id']) ) {
		/**
		 * if shared document has been choosed for moving return error message
		 * Bug #6215
		 */
        if ( isset($this->params['groups']) && $this->params['groups'] ) {
            $this->params['groups'] = explode(",", $this->params['groups']);
            if ( 0 != sizeof($this->params['groups']) ) {
                foreach ( $this->params['groups'] as $_docId ) {
                    $objDocument = new Warecorp_Document_Item($_docId);
                    if ( null !== $objDocument->getId() ) {
                        if ( $objDocument->isDocumentShared($objDocument->getId(),'user', $this->currentUser->getId()) ) {
							$popup_window = Warecorp_View_PopupWindow::getInstance();        
							$popup_window->title(Warecorp::t('Information'));
							$popup_window->content('<p>' . Warecorp::t('Document \''.$objDocument->getName().'\' is shared. You can not move shared documents.') . '</p>');
							$popup_window->width(350)->height(100)->open($objResponse);   
							$objResponse->printXml($this->_page->Xajax->sEncoding);
							exit;       
                        }
                    }
                }
            }
        }
		
        /* Build Folders Tree */
        $treeObj = $this->currentUser->getArtifacts()->createDocumentTree();
        $treeObj->setCallbackFunction('DocumentApplication.selectMoveGroup');
        $tree = $treeObj->startTree('moveTree', 'moveGroupPanelContentDiv');
        
        $users = array();
        array_unshift($users, $this->currentUser);
        $allowed_users = array();
        if ( sizeof($users) != 0 ) {
            foreach ( $users as $user ) {
                if ( true == Warecorp_Document_AccessManager_Factory::create()->canManageUserDocuments($user, $this->_page->_user->getId()) ) {
                    $tmpTreeObj = $user->getArtifacts()->createDocumentTree();
                    $tmpTreeObj->setShowDocuments(false);
                    $tmpTreeObj->setShowMainFolder(true);
                    $tmpTreeObj->setMainFolderName($user->getLogin());
                    $tmpTreeObj->setMainCallbackFunction('DocumentApplication.selectMoveGroup');
                    $tmpTreeObj->setShowShared(false);
                    $tree .= $tmpTreeObj->buildTree('moveTree');
                    $allowed_users[] = $user;
                }
            }
        }
        $users = $allowed_users;
        unset($allowed_users);
        $tree .= $treeObj->endTree('moveTree');
        $objResponse->addScript($tree);
    
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Move document(s) and folder(s)'));
        $popup_window->target("moveGroupPanel");
        $popup_window->width(500)->height(350)->open($objResponse);        
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;       
    } 
    /**
     * HANDLER
     */
    else {
        $this->params['to_folder_id'] = ( floor($this->params['to_folder_id']) == 0 ) ? null : floor($this->params['to_folder_id']);
        
        /* check new owner and new folder */    
        $objOwner = new Warecorp_User('id', $this->params['to_owner_id']);
        $objFolder = new Warecorp_Document_FolderItem($this->params['to_folder_id']);
        if ( $objOwner->getId() === null && $objFolder->getId() === null ) {
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->close($objResponse);
            $objResponse->printXml($this->_page->Xajax->sEncoding);  
            exit;          
        }
        
        /* Check permissions */
        if ( !Warecorp_Document_AccessManager_Factory::create()->canManageOwnerDocuments($this->currentUser, $objOwner, $this->_page->_user->getId()) ) {
            $popup_window = Warecorp_View_PopupWindow::getInstance();        
            $popup_window->title(Warecorp::t('Information'));
            $popup_window->content('<p>' . Warecorp::t('Access denied: You can\'t move files or folders') . '</p>');
            $popup_window->width(350)->height(100)->reload($objResponse);        
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;       
        }
        
        if ( isset($this->params['groups']) && $this->params['groups'] ) {
            $this->params['groups'] = explode(",", $this->params['groups']);
            if ( 0 != sizeof($this->params['groups']) ) {
                foreach ( $this->params['groups'] as $_docId ) {
                    $objDocument = new Warecorp_Document_Item($_docId);
                    if ( null !== $objDocument->getId() ) {
                        if ( $objDocument->isDocumentShared($objDocument->getId(),'user', $this->currentUser->getId()) ) {
                            //$objDocument->unshareDocument('user', $this->currentUser->getId());
                        } else {
                            $objDocument->setOwnerType ('user')
                                        ->setOwnerId   ($this->params['to_owner_id'])
                                        ->setFolderId  ($this->params['to_folder_id']);
                            $objDocument->save();            
                        }
                    }
                }
            }
        }
    
        $errors = array();
        if ( isset($this->params['fgroups']) && $this->params['fgroups'] ) {
            $this->params['fgroups'] = explode(",", $this->params['fgroups']);
            if ( 0 != sizeof($this->params['fgroups']) ) {
                $destFolder = new Warecorp_Document_FolderItem($this->params['to_folder_id']); // target folder
                foreach ( $this->params['fgroups'] as $_folderId ) {
                    $objFolder = new Warecorp_Document_FolderItem($_folderId);  // folder that sould be moved
                    if ( null !== $objFolder->getId() ) {                    
                        if ( $destFolder->isSubParent($objFolder->getId()) ) {
                            $errors[] = Warecorp::t("Can't move recursively.");
                        } elseif ( $destFolder->getId() == $objFolder->getId() ) {
                            $errors[] = htmlspecialchars($objFolder->getName()) . Warecorp::t(" : You can't move this folder.");
                        } elseif ( false == Warecorp_Document_AccessManager_Factory::create()->canMoveFolders($this->currentUser, $objFolder->getOwner(), $this->_page->_user->getId()) ) {
                            $errors[] = htmlspecialchars($objFolder->getName()) . Warecorp::t(" : You can't move this folder. No permissions.");
                        } else {
                            $objFolder->move($objOwner->getId(), 'user', $destFolder->getId());
            
                            $Script = "";
                            $Script = "var moveToNull = false;";
                            if ( $destFolder->getId() === null ) {
                                $Script .= "if ( !tree_0_root_node_user_".$objOwner->getId()." ) {var moveToNull = true;}";
                                $Script .= "if ( !moveToNull ) {var newParent = tree_0_root_node_user_".$objOwner->getId().";}";
                            } else {
                                $Script .= "if ( !tree_0.getNodeByProperty(\"id\", ".$destFolder->getId().") ) {var moveToNull = true;}";
                                $Script .= "if ( !moveToNull ) {var newParent = tree_0.getNodeByProperty('id', ".$destFolder->getId().");}";
                            }
                            $Script .= "var currNode = tree_0.getNodeByProperty('id', ".$objFolder->getId().");";
                            $Script .= "var oldParent = currNode.parent;";
                            $Script .= "tree_0.popNode(currNode);";
                            $Script .= "if ( !moveToNull ) {currNode.appendTo(newParent);}";
                            $Script .= "oldParent.refresh();";
                            $Script .= "if (!moveToNull) {newParent.refresh();}";
                            $objResponse->addScript($Script);                            
                        }                    
                    }
                }
            }
        }    
        
        if ( sizeof($errors) != 0 ) {
            $popup_window = Warecorp_View_PopupWindow::getInstance();        
            $popup_window->title(Warecorp::t('Information'));
            $popup_window->content('<p>' . $errors[0] . '</p>');
            $popup_window->width(350)->height(100)->reload($objResponse);        
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;                   
        } else {
            $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
            $owner = new Warecorp_User('id', $this->params['owner_id']);        
            $this->documentChangeContent($objResponse, $owner, $this->params['folder_id']);
            
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->close($objResponse);
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;   
        }     
                
    }

    
    
    
    
    
    
    
    
    
