<?php

    $objResponse = new xajaxResponse();

    $record_id = isset($record_id) ? (int)$record_id : 0;
    $record = new Warecorp_List_Record($record_id);
    $list = new Warecorp_List_Item($record->getListId());
    
    if (!Warecorp_List_AccessManager_Factory::create()->canViewList($list, $this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }

    $objResponse->addScript("unlock_content();");
    $this->view->action = 'view';

    if ($record->getId() && $list->getId() && isset($mode)) {
        switch ($mode) {
           case "edit" : 
                $objResponse->addScript("var display_index = document.getElementById('display_index_{$record->getId()}').innerHTML;");
                $this->listsViewRefresh($objResponse, $record->getListId());
                $record->domXml = DOMDocument::loadXML($record->getXml());

        	    $record->tags = $record->getTagsList();
        	    foreach ($record->tags as &$_tag) {
        	        $_tag = $_tag->getPreparedTagName();
        	    }
                $record->tags = implode(' ', $record->tags);

                $xsl_form = $list->getXslForm();
                $XSLTProcessor = new XSLTProcessor();
                $XSLTProcessor->registerPHPFunctions();
                $XSLTProcessor->importStyleSheet($xsl_form);

                $form_record = new Warecorp_Form('item_'.$record->getId(), 'POST', '');

                $this->view->form_record       = $form_record;
                $this->view->record            = $record;
                $this->view->XSLTProcessor     = $XSLTProcessor;
                $this->view->showExtraFields   = $list->needExtraFields();
                $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();

                $output = $this->view->getContents('users/lists/lists.view.record.form.tpl');
                $objResponse->addClear("list_items", "div", "item_".($record->getId()));
                $objResponse->addAssign("item_".($record->getId()),'innerHTML', $output);
                $objResponse->addScript("document.getElementById('display_index_{$record->getId()}').innerHTML = display_index; display_index='';");
                break;
            default: // view details
                //$record = new Warecorp_List_Record($record_id);
                $list = new Warecorp_List_Item($record->getListId());
                $this->listsViewRefresh($objResponse, $record->getListId());
                $objResponse->addScript("var display_index = document.getElementById('display_index_{$record->getId()}').innerHTML;");
                $xsl_view = $list -> getXslView($list->getListType());
                $XSLTProcessor = new XSLTProcessor();
                
                $XSLTProcessor->registerPHPFunctions();
                $XSLTProcessor->importStyleSheet($xsl_view);
                $dom = DOMDocument::loadXML($record->getXml());
                $form = new Warecorp_Form('form_comment', 'POST', '');

                $dateObj = new Zend_Date();
                $dateObj->setTimezone($this->_page->_user->getTimezone());
                
                $this->view->form_comment      = $form;
                $this->view->record            = $record;
                $this->view->record_view       = $XSLTProcessor->transformToXml($dom);
                $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();
                $this->view->TIMEZONE      = $dateObj->get(Zend_Date::TIMEZONE);

                $output = $this->view->getContents('users/lists/lists.view.record.details.tpl');
                $objResponse->addClear("list_items", "div", "item_".($record_id));
                $objResponse->addAssign("item_".($record_id),'innerHTML', $output);
                $objResponse->addScript("document.getElementById('display_index_{$record->getId()}').innerHTML = display_index; display_index='';");
                $objResponse->addScript('popup_window.close();');
        }
    }
