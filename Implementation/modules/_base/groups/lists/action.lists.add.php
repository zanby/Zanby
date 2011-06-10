<?php
Warecorp::addTranslation('/modules/groups/lists/action.lists.add.php.xml');

    if (!Warecorp_List_AccessManager_Factory::create()->canCreateLists($this->currentGroup, $this->_page->_user->getId())) {
        $this->_redirect($this->currentGroup->getGroupPath('lists'));
    }

    $_url = $this->currentGroup->getGroupPath().$this->_page->Locale;

    $this->_page->Xajax->registerUriFunction("list_add_save", "/groups/listsAddSave/");
    $this->_page->Xajax->registerUriFunction("list_add_delete_record", "/groups/listsAddDeleteRecord/");
    $this->_page->Xajax->registerUriFunction("list_add_expand", "/groups/listsAddExpand/");
    $this->_page->Xajax->registerUriFunction("list_add_publish", "/groups/listsAddPublish/");
    $this->_page->Xajax->registerUriFunction("list_add_change_type", "/groups/listsAddChangeType/");
    $this->_page->Xajax->registerUriFunction("list_add_share", "/groups/listsAddShare/");
    $this->_page->Xajax->registerUriFunction("list_add_unshare", "/groups/listsAddUnshare/");

    $_types = Warecorp_List_Item::getListTypesListAssoc();

    $_SESSION['list_new']['type'] = $this->params['type'] = (empty($this->params['type']) || !isset($_types[$this->params['type']])) ? 1 : (int)$this->params['type'];

    $list = new Warecorp_List_Item();
    $list->setListType($this->params['type']);

    $list_new = &$_SESSION['list_new'];

    if ( isset($list_new['records']) && isset($list_new['type']) && count($list_new['records']) ) {
        $_last = end($list_new['records']);
        if (isset($_last['status']) && $_last['status']=='expanded') {
            $new_id = key($list_new['records']);
            $display_index = count($list_new['records']);
        } else {
            $new_id = max(array_keys($list_new['records']))+1;
            $display_index = count($list_new['records'])+1;
        }
        $xsl_form = $list -> getXslForm();
    } else {
        if (isset($list_new['type']) && ($xsl_form = $list -> getXslForm()) ) {
            $new_id = 0;
            $display_index = 1;
            $list_new['records']  = array();
        }
    }

    $XSLTProcessor = new XSLTProcessor();
    $XSLTProcessor->importStyleSheet($xsl_form);

    $list_new['new_id'] = $new_id;
    $list_new['records'][$new_id] = array(
        'status' => 'expanded',
        'display_index' => $display_index,
    );

    foreach ($list_new['records'] as &$record) {
        if (isset($record['data']['item_fields'])) {
            $record['xml'] = $list->arrayToXml($record['data']['item_fields']);
        } else {
            $record['xml'] = $list->getXmlEmpty();
        }

    }

    $form = new Warecorp_Form('list_add', 'POST', $_url.'/listsadd/');
    $form_record = new Warecorp_Form('record_form_'.$new_id, 'POST', '');

// @todo - remove this block
//    if($this->currentGroup->getGroupType() == "family") {
//	    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, array("Group families" => "/" .$this->_page->Locale. "/summary/", $this->currentGroup->getName() => ""));
//    } else {
//        //breadcrumb
//        $this->_page->breadcrumb = array_merge(
//            $this->_page->breadcrumb,
//            array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
//                 $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
//                 $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
//                 $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
//                 $this->currentGroup->getName() => "")
//            );
//    }

    $this->view->bodyContent       = 'groups/lists/lists.add.tpl';
    $this->view->type              = $list->getListType();
    $this->view->form              = $form;
    $this->view->form_record       = $form_record;
    $this->view->list_records      = $list_new['records'];
    $this->view->type_title        = $list->getListTypeName();
    $this->view->XSLTProcessor     = $XSLTProcessor;
    $this->view->new_id            = $new_id;
    $this->view->types             = $_types;
    $this->view->showExtraFields   = $list->needExtraFields();
    $this->view->sharedWith        = empty($list_new['share']) ? array() : $list_new['share'];
    $this->view->recordObj         = new Warecorp_List_Record();
    $this->view->listType          = $list->getListType();
