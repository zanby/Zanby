<?php
    Warecorp::addTranslation('/modules/groups/lists/action.lists.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();

    /**
     * for some implementations amon user can view documents
     * e.g. TheUptake
     * access to documents should be checked by AccessManager
     * for implementations that doesn't allow method canViewOwnerDocuments of 
     * current instance of AccessManager should return specific access flag
     * if we get AccessManager through AccessFactory it get specific Manager for current implementation
     * in this AccessManager we should check all permissions
     */
    /*
    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    */
    
    if (!$AccessManager->canViewLists($this->currentGroup, $this->_page->_user->getId())) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }

    $dateObj = new Zend_Date();
    $dateObj->setTimezone($this->_page->_user->getTimezone());
    
    $_url = $this->currentGroup->getGroupPath();

    $listTypes = Warecorp_List_Item::getListTypesListAssoc();
    $listTypes = array('0'=>'All') + $listTypes;

    if (!isset($this->params['type']) || !isset($listTypes[$this->params['type']])) $this->params['type'] = 0;

    $list = new Warecorp_List_List( $this->currentGroup );
    if (!$AccessManager->canViewPrivateLists($this->currentGroup, $this->_page->_user)) {
    	$list->setPrivacy(0);
    }

    //if ( $AccessManager->canViewSharedLists ($this->currentGroup, $this->_page->_user) ) {
        $listsList = $list->setOrder('vli.title')->getListsListByType( $this->params['type'], true, true );
    //} else {
    //    $listsList = $list->setOrder('vli.title')->getListsListByType( $this->params['type'], false, false );
    //}

	if ($this->params['type'] != 0) $listsList = array($this->params['type'] => $listsList);

    $_listsList = array();
	foreach ($listsList as $key=>$value) {
		$_listsList[$key]['plain'] = $value;
    }
    
    $listsList = $_listsList;

    $list->getAllListTags();

    foreach ($listTypes as $id => &$type) {
        $type = array(
            'title'     =>$type,
            'url'       =>$_url.'lists/type/'.$id.'/',
            'active'    =>($id == $this->params['type']),
        );
    }

    $myListMenu = array(
        array('title' => Warecorp::t('List Index'), 'url' => $_url."/lists/", 'active' =>1),
        array('title' => Warecorp::t('Search and Browse Lists'), 'url' => $_url."/listssearch/", 'active' =>0),
    );

    $this->view->listsList = $listsList;
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");
    $this->_page->Xajax->registerUriFunction("closePopup", "/ajax/closePopup/");
    $this->_page->Xajax->registerUriFunction("list_confirm_popup_show", "/groups/listsConfirmPopupShow/");
    $this->_page->Xajax->registerUriFunction("list_confirm_popup_close", "/groups/listsConfirmPopupClose/");
    $this->_page->Xajax->registerUriFunction("list_share_popup_show", "/groups/listsSharePopupShow/");
    $this->_page->Xajax->registerUriFunction("list_share_popup_close", "/groups/listsSharePopupClose/");
    $this->_page->Xajax->registerUriFunction("list_share", "/groups/listsShare/");
    $this->_page->Xajax->registerUriFunction("list_unshare", "/groups/listsUnshare/");
 
    
    $this->view->bodyContent = 'groups/lists/lists.tpl';
    $this->view->addListLink = $_url.'listsadd/'.(!empty($this->params['type']) ? "type/".floor($this->params['type'])."/" :"" );
    $this->view->deleteListLink = $_url.'listsdelete/';
    $this->view->viewListLink = $_url.'listsview/';
    $this->view->editListLink = $_url.'listsedit/';
    $this->view->offWatchLink = $_url.'listsoffwatch/';
    $this->view->typesTabs = $listTypes;
    $this->view->myListMenu = $myListMenu;
    $this->view->type = $this->params['type'];
    $this->view->recordObj = new Warecorp_List_Record();
    $this->view->listsTags = $list->getAllListTags();
    $this->view->Warecorp_List_AccessManager = $AccessManager;
    $this->view->TIMEZONE = $dateObj->get(Zend_Date::TIMEZONE);
