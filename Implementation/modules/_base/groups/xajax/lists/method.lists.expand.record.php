<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/method.lists.expand.record.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $record = &$list['records'][$record_id];
    $record['status'] = 'expanded';
    $listObj = new Warecorp_List_Item();
    $listObj->setListType($list['type']);

    if (isset($record['data']['item_fields'])) {
    	if (isset($record['errors']) && count($record['errors'])) foreach ($record['errors'] as $key=>$val) $record['data']['item_fields']['error_'.$key] = 1; 
    	$record['xml'] = $listObj->arrayToXml($record['data']['item_fields']);
        if (isset($record['errors']) && count($record['errors'])) foreach ($record['errors'] as $key=>$val) unset($record['data']['item_fields']['error_'.$key]); 
    } else {
        $record['xml'] = $listObj->getXmlEmpty();
    }
    
    $xsl_form = $listObj -> getXslForm();
    $XSLTProcessor = new XSLTProcessor();
    $XSLTProcessor->importStyleSheet($xsl_form);
    
    $form_record = new Warecorp_Form('item_'.$record_id, 'POST', '');
    $this->view->form_record       = $form_record;
    $this->view->record            = $record;
    $this->view->recordObj         = new Warecorp_List_Record();
    $this->view->listType          = $listObj->getListType();
    $this->view->id                = $record_id;
    $this->view->new_id            = $list['new_id'];
    $this->view->XSLTProcessor     = $XSLTProcessor;
    $this->view->showExtraFields   = $listObj->needExtraFields();
    $this->view->Warecorp_List_AccessManager = $AccessManager;
    $output = $this->view->getContents('groups/lists/lists.record.form.tpl');
    $objResponse->addClear("list_items", "div", "item_".($record_id));
    $objResponse->addAssign("item_".($record_id),'innerHTML', $output);
