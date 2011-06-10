<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentCheckOut.php.xml');    
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
    if ( !$AccessManager->canManageOwnerDocuments($this->currentGroup, $objOwner, $this->_page->_user->getId()) ) {
        $docIds = explode(",", $this->params['groups']);
        $cantDocs = array();
        foreach ( $docIds as $docId ) {
            $objDocument = new Warecorp_Document_Item($docId);
            if ( !$AccessManager->canEditDocument($objDocument, $this->currentGroup, $this->_page->_user) ) {
                $cantDocs[] = $objDocument->getName();
            }
        }
        if ( sizeof($docIds) === sizeof($cantDocs) ) {
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t('Information'));
            $popup_window->content('<p>' . Warecorp::t("You can't check out file(s)") . '</p>');
            $popup_window->width(350)->height(100)->reload($objResponse);
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;
        } else if ( sizeof($cantDocs) > 0 ) {
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t('Information'));
            $popup_window->content('<p>' . Warecorp::t("You can't check out file(s):") .'<br />'. implode('<br />', $cantDocs) .'</p>');
            $popup_window->width(350)->height(100)->reload($objResponse);
            $objResponse->printXml($this->_page->Xajax->sEncoding);
            exit;
        }
        unset($docIds, $docId, $cantDocs, $objDocument);
    }
    
    /* Handle check out for selected documents */
    $status = array(
        'success' => array(), 
        'error' => array( 
            'ischeckout' =>array(),     // message
            'notaccess' => array(),     // access denied
            'weblink' => array(),       // is link
            'shared' => array()         // is shared
    ));
    $this->params['groups'] = explode(",", $this->params['groups']);
    if ( 0 != sizeof($this->params['groups']) ) {
        foreach ( $this->params['groups'] as $_docId ) {
            $objDocument = new Warecorp_Document_Item($_docId);
            if ( null !== $objDocument->getId() ) {
                if ( $objDocument->getIsCheckOut() ) {
                    if ( $objDocument->getCheckOutUserId() == $this->_page->_user->getId() ) $status['error']['ischeckout'][] = $objDocument->getOriginalName();
                    else $status['error']['notaccess'][] = $objDocument->getOriginalName();
                } 
                elseif ( $objDocument->getIsLink() ) {
                    $status['error']['weblink'][] = $objDocument->getOriginalName();
                } elseif ( $objDocument->isDocumentShared($objDocument->getId(),'group', $this->currentGroup->getId()) ) {
                    $status['error']['shared'][] = $objDocument->getOriginalName();
                } else {
                    $objDocument->checkOut($this->_page->_user->getId(), $this->params['checkout_reason']);
                    $status['success'][] = $objDocument->getOriginalName();
                    $objResponse->addDownloadFile($this->currentGroup->getGroupPath('docget/docid/'.$objDocument->getId()));
                }
            }
        }
    }
    
    $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
    $owner = Warecorp_Group_Factory::loadById($this->params['owner_id']);        
    $this->documentChangeContent($objResponse, $owner, $this->params['folder_id']);
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    if ( 
        sizeof($status['error']['ischeckout']) != 0 || 
        sizeof($status['error']['notaccess']) != 0 ||
        sizeof($status['error']['weblink']) != 0 ||
        sizeof($status['error']['shared']) != 0
    ) {
        $output = array();                
        if ( sizeof($status['error']['ischeckout']) != 0 ) {
            foreach ( $status['error']['ischeckout'] as $_error ) {            
                $output[] = '<li>'.Warecorp::t("Message : Document '%s' is checked out already.", htmlspecialchars($_error)).'</li>';
            }
        }
        if ( sizeof($status['error']['notaccess']) != 0 ) {
            foreach ( $status['error']['notaccess'] as $_error ) {            
                $output[] = '<li>'.Warecorp::t("Access Denied : Document '%s' was checked out by other user.", htmlspecialchars($_error)).'</li>';
            }
        }
        if ( sizeof($status['error']['weblink']) != 0 ) {
            foreach ( $status['error']['weblink'] as $_error ) {            
                $output[] = '<li>'.Warecorp::t("Error : Document '%s' is web link. This document cannot be checked out because it is stored on an outside service, such as Google Documents.", htmlspecialchars($_error)).'</li>';
            }
        }
        if ( sizeof($status['error']['shared']) != 0 ) {
            foreach ( $status['error']['shared'] as $_error ) {            
                $output[] = '<li>'.Warecorp::t("Error : Document '%s' is shared. You can not check out shared documents.", htmlspecialchars($_error)).'</li>';
            }
        }
        $output = '<ul>'.join('', $output).'</ul>';
        $popup_window->title(Warecorp::t('Check Out Status'));
        $popup_window->content($output);
        $popup_window->width(500)->height(200)->reload($objResponse);        
    } else {
        $popup_window->close($objResponse);
    }
        
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;        
    