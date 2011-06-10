<?php
Warecorp::addTranslation("/modules/users/xajax/lists/action.volunteer.popup.close.php.xml");
    $objResponse = new xajaxResponse();

    $record = isset($data['record_id']) ? new Warecorp_List_Record($data['record_id']) : new Warecorp_List_Record();

    if (count($data) != 0 && $this->_page->_user->getId() && $record->getId()) {
        $list = new Warecorp_List_Item($record->getListId());

        if (!Warecorp_List_AccessManager_Factory::create()->canViewList($list, $this->currentUser, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
            return;
        }

        $form = new Warecorp_Form('volunteer_form', 'POST', 'javascript:void(0);');
        $form->addRule('comment', 'maxlength', Warecorp::t('Comment too long. %s characters available', 100), array('max' => 100));

        if (isset($data['_wf__volunteer_form'])) {
            $_REQUEST['_wf__volunteer_form'] = $data['_wf__volunteer_form'];
        }

        if ($form->validate($data)) {
            $record->addVolunteer($data['comment']);
            $this->listsViewRefresh($objResponse, $record->getListId());
            $objResponse->addScript("popup_window.close();");
        } else {
            $list = new Warecorp_List_Item($record->getListId());
            $this->view->form = $form;
            $this->view->list = $list;
            $this->view->record = $record;
            $this->view->assign($data);

            $content = $this->view->getContents('users/lists/volunteer.popup.tpl');
            $objResponse->addClear("ajaxMessagePanelContent", "innerHTML");
            $objResponse->addAssign("ajaxMessagePanelContent", "innerHTML", $content);
        }
    } else {
        if (!Warecorp_List_AccessManager_Factory::create()->canViewLists($this->currentUser, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
            return;
        }
        $objResponse->addScript("popup_window.close();");
    }
