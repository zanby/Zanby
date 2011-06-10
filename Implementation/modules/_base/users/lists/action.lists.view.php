<?php
    Warecorp::addTranslation("/modules/users/lists/action.lists.view.php.xml");

    if ( !isset($this->params['listid']) ||
         floor($this->params['listid']) == 0 ||
         !Warecorp_List_Item::isListExists($this->params['listid'])  )
    {
        $this->_redirect($this->currentUser->getUserPath('lists'));
    }
    $list = new Warecorp_List_Item($this->params['listid']);

    $canShare = Warecorp_List_AccessManager_Factory::create()->canManageList($list,$this->currentUser,$this->_page->_user);

    if (strcasecmp(Warecorp::$actionName, 'calendar.event.view') == 0 || strcasecmp(Warecorp::$actionName, 'calendarEventExpandList') == 0 ) {

    } else {
        if (!Warecorp_List_AccessManager_Factory::create()->canViewList($list, $this->currentUser, $this->_page->_user->getId())) {
            $this->_redirect($this->currentUser->getUserPath('lists'));
        }
    }
	$_url = $this->currentUser->getUserPath(null, false);

    $this->_page->Xajax->registerUriFunction("closePopup", "/ajax/closePopup/");
    $this->_page->Xajax->registerUriFunction("list_view_expand", "/users/listsViewExpand/");
    $this->_page->Xajax->registerUriFunction("list_view_save", "/users/listsViewSave/");
    $this->_page->Xajax->registerUriFunction("list_view_add_form", "/users/listsViewAddForm/");
    $this->_page->Xajax->registerUriFunction("list_view_collapse", "/users/listsViewCollapse/");
    $this->_page->Xajax->registerUriFunction("list_view_append_comment", "/users/listsViewAppendComment/");
    $this->_page->Xajax->registerUriFunction("list_view_save_comment", "/users/listsViewSaveComment/");
    $this->_page->Xajax->registerUriFunction("list_view_delete_record", "/users/listsViewDeleteRecord/");
    $this->_page->Xajax->registerUriFunction("list_view_delete_comment", "/users/listsViewDeleteComment/");
    $this->_page->Xajax->registerUriFunction("list_view_rank_record", "/users/listsViewRankRecord/");
    $this->_page->Xajax->registerUriFunction("list_view_onchange_order", "/users/listsViewOnchangeOrder/");
    $this->_page->Xajax->registerUriFunction("list_confirm_popup_show", "/users/listsConfirmPopupShow/");
    $this->_page->Xajax->registerUriFunction("list_confirm_popup_close", "/users/listsConfirmPopupClose/");    
    $this->_page->Xajax->registerUriFunction("list_share_popup_show", "/users/listsSharePopupShow/");
    $this->_page->Xajax->registerUriFunction("list_share", "/users/listsShare/");
    $this->_page->Xajax->registerUriFunction("list_unshare", "/users/listsUnshare/");
    $this->_page->Xajax->registerUriFunction("list_share_popup_close", "/users/listsSharePopupClose/");
    $this->_page->Xajax->registerUriFunction("list_add_popup_show", "/users/listsAddListPopupShow/");
    $this->_page->Xajax->registerUriFunction("list_add_popup_close", "/users/listsAddListPopupClose/");
    $this->_page->Xajax->registerUriFunction("list_volunteer_popup_show", "/users/listsVolunteerPopupShow/");
    $this->_page->Xajax->registerUriFunction("list_volunteer_popup_close", "/users/listsVolunteerPopupClose/");
    $this->_page->Xajax->registerUriFunction("list_volunteer_delete", "/users/listsVolunteerDelete/");
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");

    switch ($list->isSpecialView()) {
        case 1 :
            $template = 'users/lists/lists.view.special.tpl';
            break;
        default:
            $template = 'users/lists/lists.view.tpl';
            break;
    }

    $XSLTProcessor = new XSLTProcessor();
    $xsl_title = $list->getXslTitleExtra();
    $XSLTProcessor->registerPHPFunctions();
    $XSLTProcessor->importStyleSheet($xsl_title);

    $_order = array(
        'createdesc'    => Warecorp::t('Newest item to Oldest'),
        'createasc'     => Warecorp::t('Oldest item to Newest'),
        'commentsdesc'  => Warecorp::t('Most Commented to least'),
        'commentsasc'   => Warecorp::t('Least Commented to Most'),
    );

    $xmlSort = $list->getXmlSortConfigAssoc();
    if ($xmlSort) {
        $_order = $xmlSort + $_order;
    }
    $defaultOrder = null;
    if ($list->getRanking()) {
        $_order = array('rankdesc'=>Warecorp::t('Rank:  Highest to Lowest'), 'rankasc'=>Warecorp::t('Rank:  Lowest to Highest')) + $_order;
        $defaultOrder = 'rankdesc';
    }

    $_order = array(''=>'')+$_order;

    $records = $list->getRecordsList($defaultOrder);
    $_SESSION['list_view'][$list->getId()]['order'] = $defaultOrder;

    $_index = 1;
    foreach ($records as &$record) {
        $record->domXml = DOMDocument::loadXML($record->getXml());
        $record->displayIndex = $_index++;
    }

    $form = new Warecorp_Form('sort_form', 'POST', '');

    $lastImportData = $list->getLastImportTargetData();
    if ($lastImportData) {
        $this->view->lastImportData    = $lastImportData;
        $this->view->lastTargetList    = new Warecorp_List_Item($lastImportData['target_list_id']);
        $list->updateViewDate();
    }

    $dateObj = new Zend_Date();
    $dateObj->setTimezone($this->_page->_user->getTimezone());
    
    $this->view->sortForm      = $form;
    $this->view->orderVariants = $_order;
    $this->view->bodyContent   = $template;
    $this->view->editListLink  = $_url.'/listsedit/';
    $this->view->list          = $list;
    $this->view->records       = $records;
    $this->view->XSLTProcessor = $XSLTProcessor;
    $this->view->defaultOrder  = $defaultOrder;
    $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();
    $this->view->TIMEZONE      = $dateObj->get(Zend_Date::TIMEZONE);
    $this->view->friendsAssoc  = $this->_page->_user->getId() ? $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array();
    $this->view->canShare  = $canShare;
