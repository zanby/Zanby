<?php
    Warecorp::addTranslation('/modules/groups/xajax/action.resignChangeHost.php.xml');
    
    $objResponse    = new xajaxResponse();
    $ErrorString    = array();
    $isValid        = true;
    $Script         = "";
    
    //  проверить на существование текущей группы и текущего пользователя
    if ( $this->currentGroup->getId() === null || $this->_page->_user->getId() === null ) {
        $objResponse->addRedirect("/");
        return $objResponse;
    }
    
    //  проверить права на хоста
    //  проверить на ввод нового пользователя
    if ( trim($new_host) == "" ) {
        $ErrorString[] = Warecorp::t("Enter user login or user email.");
        $isValid = false;
    }
    
    //  проверить на валидность введенного пользователя
    if ( $isValid ) {
        $new_user = new Warecorp_User('login', $new_host);
        if ( $new_user->getId() === null ) $new_user = new Warecorp_User('email', $new_host);
        if ( $new_user->getId() === null ) {
            $ErrorString[] = Warecorp::t("Sorry, '%s' is not a valid %s username.",array ($new_host,SITE_NAME_AS_STRING));
            $isValid = false;
        }
    }
    
    //  проверка на самого себя
    if ( $isValid ) {
        if ( $this->_page->_user->getId() == $new_user->getId() ) {
            $ErrorString[] = Warecorp::t("Sorry, '%s' is Host already",$new_host);
            $isValid = false;
        }
    }

    //different check for simple and family groups
    if ($isValid) {	       
        if ( !$this->currentGroup->getMembers()->isMemberExistsAndApproved($new_user->getId()) ) {
            $ErrorString[] = Warecorp::t("Sorry, '%s' isn't a member of your group.",$new_host);
            $isValid = false;
        }
	}     
	
    $ContentBlockID = "GroupSettingsResign_Content";
    $group_type = ($this->currentGroup->getGroupType() == 'simple') ? 1 :( ($this->currentGroup->getGroupType() == 'family' ) ? 2 : 0 );
    $isValid = ($isValid == true) ? ( ($group_type != 0) ? true : false ) : false;
    
    if ( $isValid ) {	
    	if ($confirm == 'true') {			
    		$template = ($group_type == 1) ? 'groups/changehost.confirm.tpl' : 'groups/changefamilyowner.confirm.tpl';
    		$title = ($group_type == 1) ? Warecorp::t('Change Host') : Warecorp::t('Change Family Owner');
    	    $this->view->onclick = "xajax_privileges_resign_change_host('$new_host', false);";
    	    $this->view->newhost = $new_host;
    	    $Content = $this->view->getContents ( $template ) ;
    	    $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title($title);
            $popup_window->content($Content);
            $popup_window->width(500)->height(350)->open($objResponse);
    	} else {   	
    		$req = new Warecorp_Group_Resign_Requests();
    		$req->setGroupId($this->currentGroup->getId());
    		$req->setUserId($new_user->getId());
    		$req->save();
    		    		
    		$this->currentGroup->sendResignRequestNewHost( $this->currentGroup, $new_user, md5($req->getId()), '', '' );
            
    		$this->view->visibility = false;
    		$Content = ($group_type == 1) ? $this->view->getContents('groups/settings.resign.tpl'):
    		$this->view->getContents('groups/settings.familyresign.tpl');
    		$objResponse->addClear( $ContentBlockID, "innerHTML" );
    		$objResponse->addAssign( $ContentBlockID, "innerHTML", $Content );
    		$objResponse->addScript('TitltPaneAppGroupSettingsResign.hide();');
    		$objResponse->addScript('popup_window.close();');		
    	}
    } else {
        $this->view->visibility = true;
        $this->view->newhost = $new_host;
        $this->view->errors = ( sizeof($ErrorString) != 0 ) ? $ErrorString[0] : null;
    	$Content = ($group_type == 1) 
    	           ? $this->view->getContents('groups/settings.resign.tpl')
    	           : ( ($group_type == 2) ? $this->view->getContents('groups/settings.familyresign.tpl') : '' );
    	$objResponse->addClear( $ContentBlockID, "innerHTML" );
    	$objResponse->addAssign( $ContentBlockID, "innerHTML", $Content );	
    	$objResponse->addScript('var resign_myAutoComp = new YAHOO.widget.AutoComplete("newhost", "acMembers", myDataSource);');
    }

