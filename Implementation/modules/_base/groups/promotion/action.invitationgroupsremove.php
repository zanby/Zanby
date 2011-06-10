<?php	
Warecorp::addTranslation('/modules/groups/promotion/action.invitationgroupsremove.php.xml');

	$objResponse = new xajaxResponse();
	if ($params !== null) {		
		$_SESSION['params'] = $params;
	    $template = 'groups/promotion/deletegroup.tpl';
	    $onclick = "xajax_groupsremove(); return false;";
	    if (!isset($params['groups']) || !is_array($params['groups']) || sizeof($params['groups']) == 0) {
	    	$this->view->question = Warecorp::t('No selected groups');
	    	$this->view->dnull = 1;     	
	    } elseif (sizeof($params['groups']) == 1) {
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
	    if (is_array($params['groups'])) {
	    	$this->currentGroup->getInvitationList()->getGroups()->deleteGroups($params['groups']);
	    }
		$alert = (sizeof($params['groups']) > 1)?'Groups deleted':'Group deleted';
	    $this->_page->showAjaxAlert($alert);
		$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
	    $objResponse->addScript("location.reload();");
	}
