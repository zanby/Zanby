<?php	
Warecorp::addTranslation('/modules/groups/promotion/action.invitationremove.php.xml');

	$objResponse = new xajaxResponse();
	if ($params !== null) {		
		$_SESSION['params'] = $params;
	    $template = 'groups/promotion/deleteinvitation.tpl';
	    $onclick = "xajax_invitationsremove(); return false;";
	    if (!isset($params['invitations']) || !is_array($params['invitations']) || sizeof($params['invitations']) == 0) {
	    	$this->view->question = Warecorp::t('No selected invitations');
	    	$this->view->dnull = 1;   	
	    } elseif (sizeof($params['invitations']) == 1) {
	    	$this->view->question = Warecorp::t('Are you sure you want to delete this invitation');
	    } else {
	    	$this->view->question = Warecorp::t('Are you sure you want to delete these invitations');
	    }
	    $this->view->onclick = $onclick;
	    $Content = $this->view->getContents ( $template ) ;
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t('Remove invitations'));
        $popup_window->content($Content);
        $popup_window->width(500)->height(350)->open($objResponse);
	} else {
		$params = $_SESSION['params'];
    	$this->currentGroup->getInvitationList()->deleteInvitations($params['invitations']); 
		$alert = (sizeof($params['invitations']) > 1)?Warecorp::t('Invitations deleted')
                                                     :Warecorp::t('Invitation deleted');
	    $this->_page->showAjaxAlert($alert);
		$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
	    $objResponse->addScript("location.reload();");
	}
