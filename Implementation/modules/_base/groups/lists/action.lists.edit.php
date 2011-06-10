<?php
Warecorp::addTranslation('/modules/groups/lists/action.lists.edit.php.xml');

	$AccessManager = Warecorp_List_AccessManager_Factory::create();

	if ( !isset($this->params['listid']) ||
         floor($this->params['listid']) == 0 ||
         !Warecorp_List_Item::isListExists($this->params['listid'])  )
    {
        $this->_redirect($this->currentGroup->getGroupPath('lists'));
    }
    $list = new Warecorp_List_Item($this->params['listid']);
    $list->setForceDbTags(true); 
    
    if (!$AccessManager->canManageList($list, $this->currentGroup, $this->_page->_user->getId())) {
        $this->_redirect($this->currentGroup->getGroupPath('lists'));
    }

    $_url = $this->currentGroup->getGroupPath().$this->_page->Locale;

    $this->_page->Xajax->registerUriFunction("list_edit_delete_record", "/groups/listsEditDeleteRecord/");
    $this->_page->Xajax->registerUriFunction("list_edit_expand", "/groups/listsEditExpand/");
    $this->_page->Xajax->registerUriFunction("list_edit_save", "/groups/listsEditSave/");
    $this->_page->Xajax->registerUriFunction("list_edit_publish", "/groups/listsEditPublish/");
    $this->_page->Xajax->registerUriFunction("list_edit_change_type", "/groups/listsEditChangeType/");
    $this->_page->Xajax->registerUriFunction("list_edit_share", "/groups/listsEditShare/");
    $this->_page->Xajax->registerUriFunction("list_edit_unshare", "/groups/listsEditUnshare/");

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
    
	unset($_SESSION['list_edit']['owner']);


    $list_edit = &$_SESSION['list_edit'];

    $list_edit['share'] = array();
    foreach ($list->getSharedUsers() as $u) {
    	$list_edit['share']['u_'.$u->getId()] = $u->getLogin();
    }
    foreach ($list->getSharedGroups() as $g) {
    	$list_edit['share']['g_'.$g->getId()] = $g->getName();
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
    unset ($groupsList[$this->currentGroup->getId()]);
    foreach ($groupsList as $groupId=>$groupType) {
        $group = Warecorp_Group_Factory::loadById($groupId, $groupType);
        if (!$AccessManager->canManageLists($group, $this->_page->_user)) {
            unset($groupsList[$groupId]);
        } else {
            $groupsList[$groupId] = $group->getName();
        }
    }
    
    $friendsList = array_flip($this->_page->_user->getFriendsList()->returnAsAssoc()->getList());
    foreach ($friendsList as  $id=>&$friend ) {
        $u = new Warecorp_User('id', $id);
        $friend = $u->getLogin();
    }

	$this->view->assign($list_edit);
	$this->view->bodyContent       = 'groups/lists/lists.edit.tpl';
	$this->view->form              = $form;
	$this->view->type_title        = $list->getListTypeName();
	$this->view->types             = Warecorp_List_Item::getListTypesListAssoc();
	$this->view->showExtraFields   = $list->needExtraFields();
	$this->view->groupsList        = $groupsList;
	$this->view->friendsList       = $friendsList;
	$this->view->sharedWith        = empty($list_edit['share']) ? array() : $list_edit['share'];
	$this->view->recordObj         = new Warecorp_List_Record();
	$this->view->listType          = $list_edit['type'];
    $this->view->isSystemWhoWill   = $list->isSystemWhoWillFor(HTTP_CONTEXT);
