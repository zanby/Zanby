<?php

    $listObj = new Warecorp_List_Item();
    $listObj->setListType($list['type']);

    if ( isset($list['records']) && count($list['records']) ) {
        $new_id = max(array_keys($list['records']))+1;
        $xsl_form = $listObj -> getXslForm();
    } else {
        if ( ($xsl_form = $listObj -> getXslForm()) ) {
            $new_id = 0;
            $list['records']  = array();
        }
    }

    $XSLTProcessor = new XSLTProcessor();
    $XSLTProcessor->importStyleSheet($xsl_form);

    $record = array(
        'status'        => 'expanded',
        'display_index' => count($list['records'])+1,
        'xml'           => $listObj->getXmlEmpty(),
    );

    $list['records'][$new_id] = $record;
    $list['new_id'] = $new_id;

    $form_record = new Warecorp_Form('item_'.$new_id, 'POST', '');
    $this->view->form_record       = $form_record;
    $this->view->record            = $record;
    $this->view->recordObj         = new Warecorp_List_Record();
    $this->view->id                = $new_id;
    $this->view->XSLTProcessor     = $XSLTProcessor;
    $this->view->new_id            = $new_id;
    $this->view->showExtraFields   = $listObj->needExtraFields();
    $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();

    $output = $this->view->getContents('users/lists/lists.record.form.tpl');

    $objResponse->addCreate("list_items", "div", "item_".($new_id));
    $objResponse->addAssign("item_".($new_id),'innerHTML', $output);
