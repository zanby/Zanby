<?php
Warecorp::addTranslation('/modules/groups/lists/action.lists.view.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    if ( !$AccessManager->canViewLists($this->currentGroup, $this->_page->_user) ) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }

    if ( !isset($this->params['listid']) ||
         floor($this->params['listid']) == 0 ||
         !Warecorp_List_Item::isListExists($this->params['listid'])  )
    {
        $this->_redirect($this->currentGroup->getGroupPath('lists'));
    }
    $list = new Warecorp_List_Item($this->params['listid']);

    $canShareThisList = Warecorp_List_AccessManager_Factory::create()->canManageList($list,$this->currentGroup,$this->_page->_user);

    if (strcasecmp(Warecorp::$actionName, 'calendar.event.view') == 0 || strcasecmp(Warecorp::$actionName, 'calendarEventExpandList') == 0 ) {

    } else {
        if (!$AccessManager->canViewList($list, $this->currentGroup, $this->_page->_user->getId())) {
            $this->_redirect($this->currentGroup->getGroupPath('lists'));
        }
    }

    $_url = $this->currentGroup->getGroupPath();

    $this->_page->Xajax->registerUriFunction("closePopup", "/ajax/closePopup/");
    $this->_page->Xajax->registerUriFunction("list_view_expand", "/groups/listsViewExpand/");
    $this->_page->Xajax->registerUriFunction("list_view_save", "/groups/listsViewSave/");
    $this->_page->Xajax->registerUriFunction("list_view_add_form", "/groups/listsViewAddForm/");
    $this->_page->Xajax->registerUriFunction("list_view_collapse", "/groups/listsViewCollapse/");
    $this->_page->Xajax->registerUriFunction("list_view_append_comment", "/groups/listsViewAppendComment/");
    $this->_page->Xajax->registerUriFunction("list_view_save_comment", "/groups/listsViewSaveComment/");
    $this->_page->Xajax->registerUriFunction("list_view_delete_record", "/groups/listsViewDeleteRecord/");
    $this->_page->Xajax->registerUriFunction("list_view_delete_comment", "/groups/listsViewDeleteComment/");
    $this->_page->Xajax->registerUriFunction("list_view_rank_record", "/groups/listsViewRankRecord/");
    $this->_page->Xajax->registerUriFunction("list_view_onchange_order", "/groups/listsViewOnchangeOrder/");

    $this->_page->Xajax->registerUriFunction("list_confirm_popup_show", "/groups/listsConfirmPopupShow/");
    $this->_page->Xajax->registerUriFunction("list_confirm_popup_close", "/groups/listsConfirmPopupClose/");

    $this->_page->Xajax->registerUriFunction("list_share_popup_show", "/groups/listsSharePopupShow/");
    $this->_page->Xajax->registerUriFunction("list_share", "/groups/listsShare/");
    $this->_page->Xajax->registerUriFunction("list_unshare", "/groups/listsUnshare/");
    $this->_page->Xajax->registerUriFunction("list_share_popup_close", "/groups/listsSharePopupClose/");
    $this->_page->Xajax->registerUriFunction("list_add_popup_show", "/groups/listsAddListPopupShow/");
    $this->_page->Xajax->registerUriFunction("list_add_popup_close", "/groups/listsAddListPopupClose/");
    $this->_page->Xajax->registerUriFunction("list_volunteer_popup_show", "/groups/listsVolunteerPopupShow/");
    $this->_page->Xajax->registerUriFunction("list_volunteer_popup_close", "/groups/listsVolunteerPopupClose/");
    $this->_page->Xajax->registerUriFunction("list_volunteer_delete", "/groups/listsVolunteerDelete/");

    $this->_page->Xajax->registerUriFunction( "doEventOrganizerSendMessage", "/groups/calendarEventOrganizerSendMessage/" );

    switch ($list->isSpecialView()) {
        case 1 :
            $template = 'groups/lists/lists.view.special.tpl';
            break;
        default:
            $template = 'groups/lists/lists.view.tpl';
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
        $_order = array('rankdesc'=>Warecorp::t('Rank:  Highest to Lowest'),
                        'rankasc'=>Warecorp::t('Rank:  Lowest to Highest'))+$_order;
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
    $this->view->editListLink  = $_url.'listsedit/';
    $this->view->list          = $list;
    $this->view->records       = $records;
    $this->view->XSLTProcessor = $XSLTProcessor;
    $this->view->defaultOrder  = $defaultOrder;
    $this->view->Warecorp_List_AccessManager = $AccessManager;
    $this->view->TIMEZONE      = $dateObj->get(Zend_Date::TIMEZONE);
    $this->view->canShare      = $canShareThisList;
