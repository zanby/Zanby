<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentUnshareFile.php.xml');
    $objResponse = new xajaxResponse();

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
    /*if ( !Warecorp_Document_AccessManager_Factory::create()->canManageOwnerDocuments($this->currentGroup, $this->currentGroup, $this->_page->_user->getId()) ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t("You can't unshare this file(s)") . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }*/
    
    /* Handle check out for selected documents */
    $this->params['groups'] = explode(",", $this->params['groups']);
    if ( 0 != sizeof($this->params['groups']) ) {
        $group = Warecorp_Group_Factory::loadById($this->params['owner_id']);
        if ( $group && $group->getGroupType() === 'family' && $group->getId() !== $this->currentGroup->getId() ) {
            //  unshare only for one group
            $unshareForGroup = $this->currentGroup->getId();
        }
        elseif ( $group && $group->getGroupType() === 'family' && $group->getId() === $this->currentGroup->getId() ) {
            //  unshare for all groups in family from this family
            $unshareForGroup = true;
        }
        else {
            $unshareForGroup = false;
        }

        $unshareErrorMessage = null;
        foreach ( $this->params['groups'] as $_docId ) {
            $objDocument = new Warecorp_Document_Item($_docId);
            if ( null !== $objDocument->getId() ) {
                $objDocument->setShare(true);
                if ( $objDocument->canBeUnShared($this->currentGroup, $this->_page->_user->getId()) ) {
                    if ( false === $unshareForGroup ) {
                        //  standard unshare
                        $objDocument->unshareDocument('group', $this->params['owner_id'], false);
                    }
                    elseif ( true === $unshareForGroup ) {
                        //  unshare for all from family
                        $objDocument->unshareDocument('group', $this->params['owner_id'], true);
                        $objDocument->unshareDocument('group', $this->params['owner_id'], false);
                    }
                    elseif ( !Warecorp_Share_Entity::hasShareException($group->getId(), $objDocument->getId(), $objDocument->EntityTypeId, $unshareForGroup) ) {
                        //  unshare for all from simple group for current simple group
                        $objDocument->unshareDocument('group', $this->params['owner_id'], false);
                        Warecorp_Share_Entity::addShareException($group->getId(), $objDocument->getId(), $objDocument->EntityTypeId, $unshareForGroup);						
						$objDocument->unshareDocument('group', $unshareForGroup, false);
                    }
                }
                else {
                    if ( null === $unshareErrorMessage )
                        $unshareErrorMessage = '<p>' . Warecorp::t("You can't unshare this file(s):").'<ul>';
                    $unshareErrorMessage .= '<li>'.$objDocument->getName().'</li>';
                }
            }
        }
    }

    if ( null !== $unshareErrorMessage ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content($unshareErrorMessage."</ul></p>");
        $popup_window->width(350)->height(100)->reload($objResponse);
    }
    else {
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->close($objResponse);
    }
    
    $this->params['folder_id'] = ( $this->params['folder_id'] == 0 ) ? null : $this->params['folder_id'];
    $owner = Warecorp_Group_Factory::loadById($this->params['owner_id']);        
    $this->documentChangeContent($objResponse, $owner, $this->params['folder_id']);
        
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;
    