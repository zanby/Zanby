<?php
    Warecorp::addTranslation("/modules/users/xajax/lists/action.view.save.php.xml");

    $objResponse = new xajaxResponse();

    $objResponse->addScript("unlock_content();");
    $this->view->action = 'view';

    if ( isset($record_id) && isset($data) && isset($data['item_fields'])) {
        $record = new Warecorp_List_Record($record_id);
        if ($record->getId()) {
            $list = new Warecorp_List_Item($record->getListId());

            if (!Warecorp_List_AccessManager_Factory::create()->canViewList($list, $this->currentUser, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
                return;
            }

            $record->errors = $list->getErrors($data);
            if ($record->errors) {
                $objResponse->addScript("var display_index = document.getElementById('display_index_{$record->getId()}').innerHTML;");

                if (count($record->errors)) foreach ($record->errors as $key=>$val) $data['item_fields']['error_'.$key] = 1; 
                $record->domXml = $list->arrayToXml($data['item_fields']);
                if (count($record->errors)) foreach ($record->errors as $key=>$val) unset($data['item_fields']['error_'.$key]); 

    	        $record->tags = $record->getTagsList();
    	        foreach ($record->tags as &$_tag) {
    	            $_tag = $_tag->getPreparedTagName();
    	        }
                $record->tags = implode(' ', $record->tags);

                $xsl_form = $list -> getXslForm();
                $XSLTProcessor = new XSLTProcessor();
                $XSLTProcessor->importStyleSheet($xsl_form);

                $form_record = new Warecorp_Form('item_'.$record->getId(), 'POST', '');
                $this->view->form_record       = $form_record;
                $this->view->record            = $record;
                $this->view->XSLTProcessor     = $XSLTProcessor;
                $this->view->showExtraFields   = $list->needExtraFields();
                $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();
                $this->view->width             = '85%'; // errors blocks widt;
                $output = $this->view->getContents('users/lists/lists.view.record.form.tpl');
                $objResponse->addClear("list_items", "div", "item_".($record->getId()));
                $objResponse->addAssign("item_".($record->getId()),'innerHTML', $output);
                $objResponse->addScript("document.getElementById('display_index_{$record->getId()}').innerHTML = display_index; display_index='';");
            } else {
                $domXml         = $list->arrayToXml($data['item_fields']);
                $record->setTitle(reset($data['item_fields']));
                $record->setXml($domXml->saveXML());
                $record->setEntry($data['item_entry']);
                $record->save();
                $record->deleteTags();
                $record->addTags($data['item_tags']);
                $this->listsViewRefresh($objResponse, $record->getListId());
            }
        } elseif ($record_id == 'new' && !empty($data['list_id'])) {
            $list = new Warecorp_List_Item($data['list_id']);

            if (!Warecorp_List_AccessManager_Factory::create()->canViewList($list, $this->currentUser, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
                return;
            }

            $record->errors = $list->getErrors($data);
            if ($record->errors) {

                if (count($record->errors)) foreach ($record->errors as $key=>$val) $data['item_fields']['error_'.$key] = 1; 
                $record->domXml = $list->arrayToXml($data['item_fields']);
                if (count($record->errors)) foreach ($record->errors as $key=>$val) unset($data['item_fields']['error_'.$key]); 
                $record->setId('new');
                $record->setTitle(reset($data['item_fields']));
                $record->setXml($record->domXml->saveXML());
                $record->setEntry($data['item_entry']);
                $record->tags   = $data['item_tags'];
                $xsl_form = $list -> getXslForm();
                $XSLTProcessor = new XSLTProcessor();
                $XSLTProcessor->importStyleSheet($xsl_form);

                $form_record = new Warecorp_Form('item_'.$record->getId(), 'POST', '');
                $this->view->form_record       = $form_record;
                $this->view->record            = $record;
                $this->view->list_id           = $data['list_id'];
                $this->view->XSLTProcessor     = $XSLTProcessor;
                $this->view->showExtraFields   = $list->needExtraFields();
                $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();
                $this->view->width             = '85%'; // errors blocks widt;
                $output = $this->view->getContents('users/lists/lists.view.record.form.tpl');
                $objResponse->addClear("list_items", "div", "item_".($record->getId()));
                $objResponse->addAssign("item_".($record->getId()),'innerHTML', $output);

            } else {
                $domXml             = $list->arrayToXml($data['item_fields']);
                $record->setTitle(reset($data['item_fields']));
                $record->setXml($domXml->saveXML());
                $record->setEntry($data['item_entry']);
                $record->setListId($data['list_id']);
                $record->setCreatorId($this->_page->_user->getId());
                $record->setCreationDate(new Zend_Db_Expr('NOW()'));
                $record->save();
                $record->addTags($data['item_tags']);
                $objResponse->addRemove("item_".($record_id));

                $recordsCount = ($list->getRecordsCount()!=1) ?
                    Warecorp::t("There are %s items in this list", $list->getRecordsCount()) :
                    Warecorp::t("There is %s item in this list", $list->getRecordsCount());

                $objResponse->addAssign("records_count",'innerHTML',  $recordsCount);
                $objResponse->addCreate("list_items", "div", "item_".($record->getId()));
                $objResponse->addAssign("item_".($record->getId()),'innerHTML', '');

                $this->listsViewRefresh($objResponse, $record->getListId());
                $objResponse->addScript("xajax_list_view_add_form({$record->getListId()});");
            }
        }
    }
