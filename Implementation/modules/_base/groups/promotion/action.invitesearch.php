<?php
Warecorp::addTranslation('/modules/groups/promotion/action.invitesearch.php.xml');

	//at first look at action.search.php
    //$host = $_SERVER['HTTP_HOST'];
    //$_SERVER['HTTP_HOST'] = strtolower($this->currentGroup->getGroupPath());
	$this->_page->Xajax->registerUriFunction("addgroups", "/groups/addgroups/");
    $this->_page->Xajax->registerUriFunction("deletesearch", "/groups/deletesearch/");
    //$_SERVER['HTTP_HOST'] = $host; unset($host);

	$AllsentGroups = $this->currentGroup->getInvitationList()->setFolder(Warecorp_Group_Invitation_eFolders::SENT)->getGroups()->returnAsAssoc()->getList();
	$AllsentGroups = array_keys($AllsentGroups);
	$includedGroups = $this->currentGroup->getGroups()->returnAsAssoc()->getList();
	$includedGroups = array_keys($includedGroups);
	$allExcludingGroups = array_merge($AllsentGroups,$includedGroups);
	array_push($allExcludingGroups, $this->currentGroup->getId());

    $this->params['_url'] = $this->currentGroup->getGroupPath('invitesearch', false);
    $this->view->url = $this->currentGroup->getGroupPath('invitegroups');
    $_template = 'groups/promotion/invitesearch.tpl'; // insert your template

    $searchForm = new Warecorp_Form('search_group', 'POST',  $this->currentGroup->getGroupPath('invitesearch') . "preset/new/");
    $rememberForm = new Warecorp_Form('search_remember', 'POST', $this->currentGroup->getGroupPath('invitesearchremember'));

    $url = $this->currentGroup->getGroupPath('invitesearch');
    foreach ( array_keys($this->params) as $key ) {
        if ( preg_match('/\/'.LOCALE.'\/groups\/invitesearch/i', $key) ) {
            $url .= substr($key, strlen('/'.LOCALE.'/groups/invitesearch/name/'.$this->currentGroup->getPath().'/'));
            break;
        }
    }
    $addSelectedGroupsForm = new Warecorp_Form('add_selected_groups_form', 'POST',  $url);

    if (isset($this->params['remove'])) {
    	unset($_SESSION['selected_groups'][$this->params['remove']]);
		$this->_page->showAjaxAlert('Removed');
    }

    $selectedGroups = (isset($_SESSION['selected_groups'])) ?  $_SESSION['selected_groups'] : array();
    if (isset($this->params['selected_groups'])) {
        foreach ($this->params['selected_groups'] as $selectedGroup) {
            $selectedGroups[$selectedGroup] = $selectedGroup;
        }
        $_SESSION['selected_groups'] = $selectedGroups;
    }

    $allExcludingGroups = array_merge($allExcludingGroups, array_values($selectedGroups));
    array_unique($allExcludingGroups);
    $groupSearch->setExcludeIds($allExcludingGroups);

    if (isset($this->params['saved'])) {
        $listSearch = new Warecorp_List_Search($this->params['saved']);
        if (isset($listSearch->params) && is_array($listSearch->params)) {
            $this->params = array_merge($this->params, $listSearch->params);
        }
    } else {
        $listSearch = new Warecorp_List_Search();
    }

    if(isset($_SESSION['selected_groups'])) {
        $selectedGroups = array();
        foreach ($_SESSION['selected_groups'] as $selectedGroup) {
            $selectedGroups[$selectedGroup] = Warecorp_Group_Factory::loadById($selectedGroup);
        }
        $inviteListForm = new Warecorp_Form('invite_list_form', 'POST',  $this->currentGroup->getGroupPath('invitecompose'));
        $this->view->selectedGroups = $selectedGroups;
        $this->view->inviteForm = $inviteListForm;
    }
    $this->view->addSelectedGroupsForm = $addSelectedGroupsForm;
    $this->view->savedSearches = $listSearch->getSavedSearchesAssoc($this->_page->_user->getId(), 2);
    $this->view->rememberForm = $rememberForm;
    $this->view->currentGroup = $this->currentGroup;

