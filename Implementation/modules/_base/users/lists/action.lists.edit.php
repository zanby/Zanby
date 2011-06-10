<?php

    if ( !isset($this->params['listid']) ||
         floor($this->params['listid']) == 0 ||
         !Warecorp_List_Item::isListExists($this->params['listid'])  )
    {
        $this->_redirect($this->currentUser->getUserPath('lists'));
    }
    $list = new Warecorp_List_Item($this->params['listid']);
    $list->setForceDbTags(true);
    
    if (!Warecorp_List_AccessManager_Factory::create()->canManageList($list, $this->currentUser, $this->_page->_user->getId())) {
        $this->_redirect($this->currentUser->getUserPath('lists'));
    }

    $_url = $this->currentUser->getUserPath(null, false);

    $this->_page->Xajax->registerUriFunction("list_edit_delete_record", "/users/listsEditDeleteRecord/");
    $this->_page->Xajax->registerUriFunction("list_edit_expand", "/users/listsEditExpand/");
    $this->_page->Xajax->registerUriFunction("list_edit_save", "/users/listsEditSave/");
    $this->_page->Xajax->registerUriFunction("list_edit_publish", "/users/listsEditPublish/");
    $this->_page->Xajax->registerUriFunction("list_edit_change_type", "/users/listsEditChangeType/");
    $this->_page->Xajax->registerUriFunction("list_edit_share", "/users/listsEditShare/");
    $this->_page->Xajax->registerUriFunction("list_edit_unshare", "/users/listsEditUnshare/");
    $this->_page->Xajax->registerUriFunction("reload_share_whom", "/users/listsReloadShareWhom/");

    $list_tags = $list->getTagsList();
    foreach ($list_tags as &$_tag) {
        $_tag = $_tag->getPreparedTagName();
    }

    $_SESSION['list_edit'] = array(
        'id'            => $list->getId(),
        'type'          => $list->getListType(),
        'title'         => $list->getTitle(),
        'description'   => $list->getDescription(),
        'private'       => $list->getIsPrivate(),
        'ranking'       => $list->getRanking(),
        'adding'        => $list->getAdding(),
        'tags'          => implode(' ', $list_tags),
    );


    $list_edit = &$_SESSION['list_edit'];
    $listSharedUsers = array();
    $listSharedGroups = array();

    $list_edit['share'] = array();
    foreach ($list->getSharedUsers() as $u) {
    	$list_edit['share']['u_'.$u->getId()] = $u->getLogin();
    	$listSharedUsers[] = $u->getId();
    }
    foreach ($list->getSharedGroups() as $g) {
    	$list_edit['share']['g_'.$g->getId()] = $g->getName();
    	$listSharedGroups[] = $g->getId();
    }

	if ($records_list = $list->getRecordsListAssoc()) {
	    $i=1;
	    foreach ($records_list as $id=>&$record) {
	        $record = new Warecorp_List_Record($id);
	        $record_tags = $record->setForceDbTags(true)->getTagsList();

	        foreach ($record_tags as &$_tag) {
	            $_tag = $_tag->getPreparedTagName();
	        }
	        $record_data = array(
	           'item_fields'   => $list->xmlToArray($record->getXml()),
	           'item_entry'    => $record->getEntry(),
	           'item_tags'     => implode(' ', $record_tags),
	        );
	        $list_edit['records'][$id] = array(
	           'id'            => $record->getId(),
	           'display_index' => $i++,
               'status'        => 'collapsed',
	           'title'         => $record->getTitle(),
               'data'          => $record_data,
	        );
	    }
	    // last expanded
	    if (isset($id)) {
	        $list_edit['records'][$id]['status']='expanded';
	        $list_edit['records'][$id]['xml'] = $list->arrayToXml($list_edit['records'][$id]['data']['item_fields']);

	        $XSLTProcessor = new XSLTProcessor();
	        $XSLTProcessor->importStyleSheet($list -> getXslForm());
            $form_record = new Warecorp_Form('record_form_'.$id, 'POST', '');
	        $this->view->form_record = $form_record;
	        $this->view->XSLTProcessor = $XSLTProcessor;
	    }
	}
	if (isset($list_edit['records']) && count($list_edit['records'])) {
	    $new_id = max(array_keys($list_edit['records']))+1;
	    $display_index = count($list_edit['records'])+1;
	} else {
	    $list_edit['records'] = array();
	    $new_id = 0;
	    $display_index = 1;
	}

    $list_edit['new_id'] = $new_id;

    $form = new Warecorp_Form('list_edit', 'POST', $_url.'/listsedit/');

    $groupsList = $this->_page->_user->getGroups()->setReturnTypes()->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))->returnAsAssoc()->getList();

    foreach ($groupsList as $groupId=>$groupType) {
        $group = Warecorp_Group_Factory::loadById($groupId, $groupType);
        if (in_array($groupId,$listSharedGroups) || !Warecorp_List_AccessManager_Factory::create()->canManageLists($group, $this->_page->_user)) {
        	unset($groupsList[$groupId]);
        } else {
            $groupsList[$groupId] = $group->getName();
        }
    }
    
    $friendsList = array_flip($this->_page->_user->getFriendsList()->returnAsAssoc()->getList());
    foreach ($friendsList as  $id => $friend ) {
        $u = new Warecorp_User('id', $id);
        if (in_array($id,$listSharedUsers)) {
        	unset($friendsList[$id]);
        } else { 
            $friendsList[$id] = $u->getLogin();
        }
    }

    $list_edit['canshareusers'] = $friendsList;
    $list_edit['cansharegroups'] = $groupsList;
    
    $this->view->assign($list_edit);
    $this->view->bodyContent       = 'users/lists/lists.edit.tpl';
    $this->view->form              = $form;
    $this->view->type_title        = $list->getListTypeName();
    $this->view->types             = Warecorp_List_Item::getListTypesListAssoc();
    $this->view->showExtraFields   = $list->needExtraFields();
    $this->view->groupsList        = $groupsList;
    $this->view->friendsList       = $friendsList;
    $this->view->sharedWith        = empty($list_edit['share']) ? array() : $list_edit['share'];
    $this->view->recordObj         = new Warecorp_List_Record();
    $this->view->listType          = $list_edit['type'];
