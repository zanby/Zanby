<?php

    $objResponse = new xajaxResponse();

    $list_id = isset($list_id) ? (int)$list_id : 0;
    $list = new Warecorp_List_Item($list_id);

    if (!Warecorp_List_AccessManager_Factory::create()->canViewList($list, $this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }

    $objResponse->addScript("unlock_content();");
    $this->view->action = 'view';

    if ($list->getId()){
        if ($list->getAdding()) {
            $record = new Warecorp_List_Record();

            $record->setId('new');
            $record->domXml = $list->getXmlEmpty();
            $record->setXml($record->domXml->saveXML());
            $record->setListId($list->getId());

            $xsl_form = $list -> getXslForm();
            $XSLTProcessor = new XSLTProcessor();
            $XSLTProcessor->importStyleSheet($xsl_form);

            $form_record = new Warecorp_Form('item_'.$record->getId(), 'POST', '');
            $this->view->list_id           = $list->getId();
            $this->view->form_record       = $form_record;
            $this->view->record            = $record;
            $this->view->XSLTProcessor     = $XSLTProcessor;
            $this->view->showExtraFields   = $list->needExtraFields();
            $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();
            $output = $this->view->getContents('users/lists/lists.view.record.form.tpl');
            $objResponse->addRemove("item_".($record->getId()));
            $objResponse->addCreate("new_record", "div", "item_".($record->getId()));
            $objResponse->addAssign("item_".($record->getId()),'innerHTML', $output);

        }
    }
