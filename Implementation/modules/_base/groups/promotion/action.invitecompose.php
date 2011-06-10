<?php
    Warecorp::addTranslation('/modules/groups/promotion/action.invitecompose.php.xml');
    
    if ( !$this->_page->_user->isAuthenticated() ) {
    	$this->_redirectToLogin();
    }
    if ((!isset($_SESSION['selected_groups'])) && (!isset($this->params['id']))) {
    	$this->_redirect("/" . LOCALE . "/invitesearch/preset/new/");
    }
    
    $this->_page->Xajax->registerUriFunction("nameInvitation", "/groups/nameinvitation/");
    $isNamed = true;
    if (isset($this->params['id'])) {
    	$composeForm = new Warecorp_Form('compose_form', 'POST',  $this->currentGroup->getGroupPath('invitecompose').'id/'.$this->params['id'].'/');
    	$invitation = new Warecorp_Group_Invitation_Item($this->params['id']);
    	if ($invitation->getId() === null) $this->_redirect($this->currentGroup->getGroupPath('invitelist').'folder/draft/');
    	if (isset($this->params['remove'])) {
    		$invitation->getGroups()->deleteGroup($this->params['remove']);
    		$this->_page->showAjaxAlert('Removed');
    		$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    		$this->_redirect($this->currentGroup->getGroupPath('invitecompose').'id/'.$this->params['id'].'/');
    	}
    	if ($invitation->getGroups()->getCount() > 0) {
    		$groupsIds = array_keys($invitation->getGroups()->getList());
    		$recipients = $invitation->getGroups()->setOnlyGroups()->getList();
    		$this->view->body = $invitation->getBody();
    		$this->view->subject = $invitation->getSubject();
    		$inv_name = $invitation->getName();
    	} else $this->_redirect($this->currentGroup->getGroupPath('invitesearch'));
    } else {
    	if (isset($this->params['remove'])) {
    		unset($_SESSION['selected_groups'][$this->params['remove']]);
    		$this->_page->showAjaxAlert('Removed');
    		$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    		if (sizeof($_SESSION['selected_groups']) == 0) $this->_redirect($this->currentGroup->getGroupPath('invitesearch'));
    		else $this->_redirect($this->currentGroup->getGroupPath('invitecompose'));
    	}
    	$composeForm = new Warecorp_Form('compose_form', 'POST',  $this->currentGroup->getGroupPath('invitecompose'));
    	$invitation = new Warecorp_Group_Invitation_Item();
    	$groupsIds = $_SESSION['selected_groups'];
    	if (!empty($_SESSION['selected_groups'])) {
    		$recipients = array();
    		foreach ($_SESSION['selected_groups'] as $recipientId) {
    			$recipients[] = Warecorp_Group_Factory::loadById($recipientId);
    		}
    	} else $this->_redirect($this->currentGroup->getGroupPath('invitesearch'));
    	$isNamed = false; $inv_name="";
    }
    if(isset($this->params['send']) || isset($this->params['draft'])) {
    	$invitation->setSubject(htmlentities($this->params['subject']));
    	$invitation->setBody(htmlentities($this->params['body']));
    	//??????????????????????????????????????????????????????????????????????????????????????????
    	if (!$isNamed){
    		$invitation->setName(htmlentities(isset($_SESSION['inv_name'])?$_SESSION['inv_name']:''));
    		if (isset($_SESSION['inv_name'])) unset($_SESSION['inv_name']);
    	}
    
    	if (isset($this->params['send'])) {
    		$alert = Warecorp::t('Invitation sent');
    		$composeForm->addRule('subject', 'required', Warecorp::t('Please enter Subject'));
    		$composeForm->addRule('subject', 'maxlength', Warecorp::t('Subject of your message is too big (maximum %s letters)',250), array("max" => 250));
    		$composeForm->addRule('body', 'required', Warecorp::t('Please enter Message'));
    		$composeForm->addRule('body', 'maxlength', Warecorp::t('Body of your message is too big (maximum %s letters)',65535), array("max" => 65535));
    
    		$invitation->setFolder(Warecorp_Group_Invitation_eFolders::SENT);
    		$url = $this->currentGroup->getGroupPath('invitelist').'folder/sent/';
    	} else {
    		$alert = Warecorp::t('Saved in draft');
    		$invitation->setFolder(Warecorp_Group_Invitation_eFolders::DRAFT);
    		$url = $this->currentGroup->getGroupPath('invitelist').'folder/draft/';
    	}
    	if ($composeForm->validate($this->params)) {
    		if (isset($this->params['id'])){
    			$invitation->update();
    		} else {
    			$this->currentGroup->getInvitationList()->addInvitationItem($invitation);
    			$invitation->getGroups()->addGroups($groupsIds);
    			unset($_SESSION['selected_groups']);
    		}
    		if (isset($this->params['send'])) {
    			$invitation->send();    			
    			$this->currentGroup->sendFamilyInvitation( $this->_page->_user, $recipients, $this->params['subject'], $this->params['body'] );
    		}
    		$this->_page->showAjaxAlert($alert);
    		$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    		$this->_redirect($url);
    	} else {
    		if ( !empty($this->params['subject']) ) $this->view->subject = $this->params['subject'];
    		if ( !empty($this->params['body'])  ) $this->view->body = $this->params['body'] ;
    	}
    }
    
    $this->view->recipients = $recipients;
    $_template = 'groups/promotion/invitecompose.tpl';
    $this->view->composeForm           = $composeForm;
    $this->view->bodyContent 			= $_template;
    $this->view->recipients 			= $recipients;
    $this->view->isNamed 				= $isNamed;
    $this->view->name					= $inv_name;
