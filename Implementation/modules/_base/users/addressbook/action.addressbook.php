<?php
    Warecorp::addTranslation("/modules/users/addressbook/action.addressbook.php.xml");
    $this->_page->Xajax->registerUriFunction ( "addressbookAjaxUtilities", "/users/addressbookAjaxUtilities/" ) ; 
    $this->_page->Xajax->registerUriFunction ( "addressbookDeleteContact", "/users/addressbookDeleteContact/" ) ; 
    $urls = array('base' => $this->currentUser->getUserPath('addressbook'),
                  'current' => ($this->currentUser->getUserPath('addressbook') . 
                    ((isset($this->params['filter'])) ? 'filter/' . $this->params['filter'] . '/' : '') .
                    ((isset($this->params['pageSize'])) ? 'pageSize/' . $this->params['pageSize'] . '/' : '') . 
                    ((isset($this->params['page'])) ? 'page/' . $this->params['page'] . '/' : '') . 
                    ((isset($this->params['orderby'])) ? 'orderby/' . $this->params['orderby'] . '/' : '') .
                    ((isset($this->params['direction'])) ? 'direction/' . $this->params['direction'] . '/' : '')),
                  'for_sort' => ($this->currentUser->getUserPath('addressbook') . 
                    ((isset($this->params['filter'])) ? 'filter/' . $this->params['filter'] . '/' : '') .
                    ((isset($this->params['pageSize'])) ? 'pageSize/' . $this->params['pageSize'] . '/' : '')),
                  'for_paging' => ($this->currentUser->getUserPath('addressbook') . 
                    ((isset($this->params['filter'])) ? 'filter/' . $this->params['filter'] . '/' : '') .
                    ((isset($this->params['pageSize'])) ? 'pageSize/' . $this->params['pageSize'] . '/' : '') . 
                    ((isset($this->params['orderby'])) ? 'orderby/' . $this->params['orderby'] . '/' : '') .
                    ((isset($this->params['direction'])) ? 'direction/' . $this->params['direction'] . '/' : '')),
                  'for_filter' => ($this->currentUser->getUserPath('addressbook') . 
                    ((isset($this->params['pageSize'])) ? 'pageSize/' . $this->params['pageSize'] . '/' : '')));
    
    $pageSize = (isset($this->params['pageSize'])) ? $this->params['pageSize'] : 10;
    $page = (isset($this->params['page'])) ? $this->params['page'] : 1;
        
    $orderBy = (isset($this->params['orderby'])) ? $this->params['orderby'] : 'firstname';
    $direction = (isset($this->params['direction'])) ? $this->params['direction'] : 'asc';
    if (!in_array($orderBy, array('firstname', 'lastname', 'email'))) {
    	$orderBy = 'firstname';
    }
    if (!in_array($direction, array('asc', 'desc'))) {
    	$orderBy = 'asc';
    }

    $fields_name = array("firstname" => Warecorp::t("First"),
                    "lastname" => Warecorp::t("Last"),
                    "email" => Warecorp::t("Email Address"),
                    "maillists" => Warecorp::t("In mailing lists")
                    );
    // headers for tpl
    foreach ($fields_name as $key => $value)
        if ($key == $orderBy) 
            $headers[$key] = array("active" => true, "order" => $orderBy, "direction" => $direction, "output" => $value, "url" => ($direction == 'asc') ? 'orderby/' . $key . "/direction/desc/" : 'orderby/' . $key . "/direction/asc/");
        else $headers[$key] = array("active" => false, "order" => null, "direction" => 'asc', "output" => $value, "url" => 'orderby/' . $key . "/direction/asc/");
        
    $userAddressbookContactList = $this->_page->_user->getAddressbook()->getContacts();
    $letterCounts = $userAddressbookContactList->getAddressbookLetters($this->_page->_user->getId());
    if ( isset($this->params['filter']) ) $userAddressbookContactList->addWhere('UPPER(SUBSTRING(contact_name, 1, 1)) = ?', $this->params['filter']);
    $userAddressbookContactList->setOrder($orderBy, $direction);
    $userAddressbookContactList->setCurrentPage($page);
    $userAddressbookContactList->setListSize($pageSize);
    $addressbookId = $userAddressbookContactList->getContactListId();
    $contacts = $userAddressbookContactList->getList();
    $contactList = new Warecorp_User_Addressbook_ContactList();
    
    $contacts = Warecorp_User_Addressbook_ContactList::alterForOutput($contacts);
    
    $contactLists = Warecorp_User_Addressbook_ContactList::getContactLists($this->_page->_user->getId());
    $letters = array();
    $selected = ((isset($this->params['filter'])) && (preg_match('/[A-Z]/i',$this->params['filter']))) ? strtoupper($this->params['filter']) : false;
            
    // @todo - multibyte charactersets support
    for($i = 'A'; $i != 'AA'; $i++) {
     $letters[] = array('count'   => (isset($letterCounts[$i])) ? $letterCounts[$i] : 0,
                        'letter'  => $i,
                        'selected'=> $selected == $i,
                        );
    }
    
    // contacts list paging
    
    $contactsCount = $userAddressbookContactList->getCount(); 
    $P = new Warecorp_Common_PagingProduct($contactsCount, $pageSize, substr($urls['for_paging'], 0, -1));
    $this->view->infoPaging = $P->makeInfoPaging($page);
    $this->view->linkPaging = $P->makeLinkPaging($page);
    
    $formAddressbook = new Warecorp_Form('addressbook', 'post', $this->currentUser->getUserPath('addressbook'));
    if ($formAddressbook->validate($this->params)) {
        if (isset($this->params['contacts']) && is_array($this->params['contacts']) && count($this->params['contacts'])) {
            foreach($this->params['contacts'] as $contactId) {
                $contact =  Warecorp_User_Addressbook_Factory::loadById($contactId);
                if ($contact->isExist) {
                    if ($contact->getContactOwnerId() == $this->_page->_user->getId()) {
                        $contact->delete();
                    }
                }
            }
        }
        $this->_redirect($this->_page->_user->getUserPath('addressbook'));
    }
    $this->view->letters = $letters;
    $this->view->urls = $urls;
    $this->view->contactLists = $contactLists;
    $this->view->contacts = $contacts;
    $this->view->addressbookId = $addressbookId;
    $this->view->headers = $headers;
    $this->view->location = 'addressbook';
    $this->view->formAddressbook = $formAddressbook;
    $this->view->bodyContent = 'users/addressbook/addressbook.tpl';
