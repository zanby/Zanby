<?php
    $objResponse = new xajaxResponse();
    $contactList = new Warecorp_User_Addressbook_ContactList();
    $ownerId = $this->_page->_user->getId();
    $contactList = $contactList->loadByEntityId($params['maillistId'], $ownerId);
    if(isset($params['contacts'])){
    	foreach ($params['contacts'] as $contactId){
            $factory = new Warecorp_User_Addressbook_Factory();
            $contact = $factory->loadById($contactId);
            $contactList->addContact($contact);
        }
        $contactList->save();
        $objResponse->addRedirect('');
    }
    $objResponse->addScript('popup_window.close();');