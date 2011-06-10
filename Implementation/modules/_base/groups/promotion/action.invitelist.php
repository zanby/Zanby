<?php
Warecorp::addTranslation('/modules/groups/promotion/action.invitelist.php.xml');

if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    $items_per_page = 10;
    $this->_page->Xajax->registerUriFunction("groupsremove", "/groups/groupsremove/");
    $this->_page->Xajax->registerUriFunction("invitationsremove", "/groups/invitationsremove/");
    $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;    
    $paging_link = '';
	if (isset($this->params['order'])) {
		$order = $this->params['order'];
		$direction = $this->params['direction'];
		$paging_link = "/order/$order/direction/$direction";
	    if ($this->params['direction'] == 'asc') $this->params['direction'] = 'desc';
	      else $this->params['direction'] = 'asc';  
	}
	      
    if ($this->params['folder'] == 'draft') {
    	$order = isset($this->params['order'])?$this->params['order']:'name';
    	$direction = (isset($this->params['order']) && isset($this->params['direction']))?$this->params['direction']:'asc';    	
    	
	   	switch ($order) {
	   		case 'name':
	   			$order_path = 'zgi.name '.$direction; 
	   			break;
	   		case 'date':
	   			$order_path = 'zgi.creation_date '.$direction; 
	   			break;
	   		default:
	   			$order_path = 'zgi.name '.$direction; 
	   			break;
	   	}	   	
    	$order = isset($this->params['order'])?$this->params['order']:'name';
		$direction = (isset($this->params['order']) && isset($this->params['direction']))?$this->params['direction']:'asc';
    	$invitationsList = $this->currentGroup->getInvitationList()->setFolder(Warecorp_Group_Invitation_eFolders::DRAFT); 
    	$invitationsList->setCurrentPage(intval($this->params['page']));
		$invitationsList->setListSize($items_per_page);
		$invitationsList->setOrder($order_path);
        $invitations = $invitationsList->getList();
        $this->view->invitations = $invitations;
        $template = 'groups/promotion/draftlist.tpl';
        $question = Warecorp::t("Are you sure you want to delete selected invitations");
        $onclick="formsubmit();return false;";
        $link='#null';
        $url = $this->currentGroup->getGroupPath('invitelist').'folder/draft'.$paging_link;
        $P = new Warecorp_Common_PagingProduct($invitationsList->getCount(), $items_per_page, $url);
        $form = new Warecorp_Form('draft_form', 'POST', $this->currentGroup->getGroupPath('invitationremove'));
    } else {
    	$order = isset($this->params['order'])?$this->params['order']:'name';
    	$direction = (isset($this->params['order']) && isset($this->params['direction']))?$this->params['direction']:'asc';    	
	   	switch ($order) {
	   		case 'name':
	   			$order_path = 'zgit.name '.$direction; 
	   			break;
	   		case 'status':
	   			$order_path = 'zgr.status '.$direction.', zgii.declined '.$direction;
	   			break;
	   		case 'date':
	   			$order_path = 'zgi.creation_date '.$direction;
	   			break;
	   		default:
	   			$order_path = 'zgit.name '.$direction; 
	   			break;
	   	}	   		   			
    	$groupsList = $this->currentGroup->getInvitationList()->setFolder(Warecorp_Group_Invitation_eFolders::SENT)->getGroups();
    	$groupsList->setCurrentPage(intval($this->params['page']));
		$groupsList->setListSize($items_per_page);
		$groupsList->setOrder($order_path);
		$groups = $groupsList->getList();
    	$this->view->groups = $groups;
        $template ='groups/promotion/sentlist.tpl';
        $question = Warecorp::t("Are you sure you want to delete selected groups");
        $onclick='formsubmit();return false;';
        $link='#null';
        $url = $this->currentGroup->getGroupPath('invitelist').'folder/sent'.$paging_link;
        $P = new Warecorp_Common_PagingProduct($groupsList->getCount(), $items_per_page, $url);        
        $form = new Warecorp_Form('send_form', 'POST', $this->currentGroup->getGroupPath('invitationgroupsremove'));
    }
    $menu = $this->params['folder'];

	$this->view->order = isset($this->params['order'])?$this->params['order']:null;
	$this->view->direction = isset($this->params['direction'])?$this->params['direction']:null;	
	$this->view->paging = $P->makePaging($this->params['page']);
    $this->view->bodyContent = $template;
    $this->view->form = $form;
    $this->view->menu = $menu;
    $this->view->question = $question;
    $this->view->onclick =$onclick;
    $this->view->link =$link;
