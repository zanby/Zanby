<?php
    $objResponse = new xajaxResponse();

    $record_id = isset($record_id) ? (int)$record_id : 0;
    $record = new Warecorp_List_Record($record_id);

    if (!Warecorp_List_AccessManager_Factory::create()->canVolunteer($record, $this->currentUser, $this->_page->_user->getId())) {
    	return;
    }

    $form = new Warecorp_Form('volunteer_form', 'POST', '');
    $list = new Warecorp_List_Item($record->getListId());

    $this->view->form = $form;
    $this->view->list = $list;
    $this->view->record = $record;

    $content = $this->view->getContents('users/lists/volunteer.popup.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title($list->getListTypeName());
    $popup_window->content($content);
    $popup_window->width(500)->height(350)->open($objResponse);
