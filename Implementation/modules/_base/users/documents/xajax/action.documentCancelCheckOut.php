<?php
    Warecorp::addTranslation('/modules/users/documents/xajax/action.documentCancelCheckOut.php.xml');    
    $objResponse = new xajaxResponse();
    
    /* anonymous hasn't access */
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentUser->getUserPath('documents');
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
        $popup_window->content('<p>' . Warecorp::t("You can't check in file(s)") . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }
    
    /* Handle check out for selected documents */
    $status = array(
        'success' => array(), 
        'error' => array( 
            'notcheckout' =>array(),    // isn't checked out 
            'notaccess' => array(),     // access denied
            'shared' => array()         // is shared
    ));
    $this->params['groups'] = explode(",", $this->params['groups']);
    if ( 0 != sizeof($this->params['groups']) ) {
        foreach ( $this->params['groups'] as $_docId ) {
            $objDocument = new Warecorp_Document_Item($_docId);
            if ( null !== $objDocument->getId() ) {
                if ( !$objDocument->getIsCheckOut() ) {
                    $status['error']['notcheckout'][] = $objDocument->getOriginalName();
                } elseif ( $objDocument->isDocumentShared($objDocument->getId(),'user', $this->currentUser->getId()) ) {
                    $status['error']['shared'][] = $objDocument->getOriginalName();
                } elseif ( $objDocument->getCheckOutUserId() != $this->_page->_user->getId() ) {
                    $status['error']['notaccess'][] = $objDocument->getOriginalName();
                } else {
                    $objDocument->checkIn();
                    $status['success'][] = $objDocument->getOriginalName();                    
                }
            }
        }
    }
    
    $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
    $owner = new Warecorp_User('id', $this->params['owner_id']);        
    $this->documentChangeContent($objResponse, $owner, $this->params['folder_id']);
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    if ( 
        sizeof($status['error']['notcheckout']) != 0 || 
        sizeof($status['error']['notaccess']) != 0 || 
        sizeof($status['error']['shared']) != 0
    ) {
        $output = array();                
        if ( sizeof($status['error']['notcheckout']) != 0 ) {
            foreach ( $status['error']['notcheckout'] as $_error ) {            
                $output[] = '<li>'.Warecorp::t("Message : Document '%s' isn't checked out.", htmlspecialchars($_error)).'</li>';
            }
        }
        if ( sizeof($status['error']['notaccess']) != 0 ) {
            foreach ( $status['error']['notaccess'] as $_error ) {            
                $output[] = '<li>'.Warecorp::t("Access Denied : Document '%s' was checked out by other user.", htmlspecialchars($_error)).'</li>';
            }
        }
        if ( sizeof($status['error']['shared']) != 0 ) {
            foreach ( $status['error']['shared'] as $_error ) {            
                $output[] = '<li>'.Warecorp::t("Error : Document '%s' is shared. You can not cancel check out shared documents.", htmlspecialchars($_error)).'</li>';
            }
        }
        $output = '<ul>'.join('', $output).'</ul>';
        $popup_window->title(Warecorp::t('Cancel Check Out Status'));
        $popup_window->content($output);
        $popup_window->width(500)->height(200)->reload($objResponse);        
    } else {
        $popup_window->close($objResponse);
    }
        
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;        
    