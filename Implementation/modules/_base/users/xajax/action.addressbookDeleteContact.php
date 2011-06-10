<?php
    Warecorp::addTranslation("/modules/users/xajax/action.addressbookDeleteContact.php.xml");

    $objResponse = new xajaxResponse();
    $contactIds = explode(',', $listContacts);
    foreach ($contactIds as $index=>$contactId){
        if ($contactId=='') {
        	unset($contactIds[$index]);
        }
    }
    if ($isShowed == 'showed') {
        $factory = new Warecorp_User_Addressbook_Factory();
        if ($isContact=='false') {
           foreach ($contactIds as $contactId){
                $contact = $factory->loadById($contactId);
                if ($contact->isExist) {
                  if ($contact->getContactOwnerId() == $this->_page->_user->getId()) {                  	
                     $contact->delete();
                  }
                }
            }
        }
        elseif ($isContact=='true'){
            $maillist = new Warecorp_User_Addressbook_ContactList();
            $maillist = $maillist->loadByEntityId($maillistId, $this->_page->_user->getId());
            foreach ($contactIds as $contactId){
                $contact = $factory->loadById($contactId);
                if ($contact->isExist) {
                  if ($contact->getContactOwnerId() == $this->_page->_user->getId()) {
                     $maillist->removeContact($contact);
                  }
                }
            }
        }
        $report = Warecorp::t('Deleted');
        $objResponse->addScript('popup_window.close();');
        $objResponse->addRedirect('');
        $objResponse->showAjaxAlert($report);
    }
    elseif ($isShowed == 'notShowed'){
        if (count($contactIds)>0) {
            if ($isMailList == 'true') {
                $deleteQuestion = Warecorp::t('Are you sure you want to delete this mailList?');
                $capture = Warecorp::t('Delete MailList');
                $temp = new Warecorp_User_Addressbook_ContactList(true, 'entity_id',$listContacts);
                $listContacts = $temp->getContactId();
            }elseif (count($contactIds)==1) {
                $deleteQuestion = Warecorp::t('Are you sure you want to delete this contact?');
                $capture = Warecorp::t('Delete Contact');
            }else {
                $deleteQuestion = Warecorp::t('Are you sure you want to delete these contacts?');
                $capture = Warecorp::t('Delete Contacts');
            }
            $this->view->deleteQuestion = $deleteQuestion;
            $this->view->maillistId = $maillistId;
            $this->view->isContact = $isContact;
            $this->view->listContacts = $listContacts;
            $template = 'users/addressbook/popups/contact_delete.tpl';
            $Content = $this->view->getContents ( $template ) ;
            
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title($capture);
            $popup_window->content($Content);
            $popup_window->width(250)->height(120)->open($objResponse);
        }
        elseif (count($contactIds) == 0){
            $template = 'users/addressbook/popups/contact_empty.tpl';
            $Content = $this->view->getContents ( $template ) ;
            
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t('Delete Contact'));
            $popup_window->content($Content);
            $popup_window->width(250)->height(90)->open($objResponse);
        }
    }
