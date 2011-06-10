<?php
// creating addressbook for user - main contact list
//$contactList = new Warecorp_User_Addressbook_ContactList(false);
//$contactList->setIsMain('1');
//$contactList->setContactListName('Addressbook1');
//$contactList->setContactListOwnerId($this->_page->_user->getId());
//$contactList->save();
//dump($contactList);


//creating contact list
//    
//$contactList = new Warecorp_User_Addressbook_ContactList();
//$contactList->setIsMain('0');
//$contactList->setContactListName('my first contact list');
//$contactList->setContactListOwnerId($this->_page->_user->getId());
//$contactList->save();
//
//dump($contactList);

// creating user contact

//$contactUser = new Warecorp_User_Addressbook_User();
//$contactUser->setContactName('newUser');
//$contactUser->setUserId('2');
//$contactUser->setContactOwnerId($this->_page->_user->getId());
//$contactUser->setContactEmail('user@tut.by');
//$contactUser->save();

//dump($contactUser);

// creating custom user contact

//$contactCustomUser = new Warecorp_User_Addressbook_CustomUser();
//$contactCustomUser->setContactName('newCustomUser');
//$contactCustomUser->setFirstName('Anrdew');
//$contactCustomUser->setContactOwnerId($this->_page->_user->getId());
//$contactCustomUser->setEmail('customUser@tut.by');
//$contactCustomUser->save();
//dump($contactCustomUser);

// creating group contact

//$contactGroup = new Warecorp_User_Addressbook_Group();
//$contactGroup->setGroupId('2');
//$contactGroup->setContactName('ShadyGroup');
//$contactGroup->setContactOwnerId($this->_page->_user->getId());
//$contactGroup->setContactEmail('Shadygroup@tut.by');
//$contactGroup->save();
//dump($contactGroup);


// add contacts to contact list
//$contactList = new Warecorp_User_Addressbook_ContactList(true, 'id', '2');
//$contact = new Warecorp_User_Addressbook_CustomUser('id', '3');
//$contactList->addContact($contact);

// printing addressbook list
//$abstract = new Warecorp_User_Addressbook_Abstract();
//$addressbookId = $abstract->getMainContactListId($this->_page->_user->getId());
//$addressbookList = new Warecorp_User_Addressbook_List($addressbookId);
//dump($addressbookList->getList());
//dump($addressbookList->getCount());

// deleting all contact from contact list
//$contactList  = new Warecorp_User_Addressbook_ContactList(false, 'id', '1');
//dump($contactList);
//$contactList->removeAllContacts();

// deleting contact from contact list
//$contactList  = new Warecorp_User_Addressbook_ContactList(false, 'id', '1');
//$contact = new Warecorp_User_Addressbook_CustomUser('id', '102');
//$contactList->removeContact($contact);

// remove user
//$contact = new Warecorp_User_Addressbook_User('id', '68');
//$contact->delete();

// remove group
//$contact = new Warecorp_User_Addressbook_Group('id', '90');
//$contact->delete();

//remove custom user
//$contact = new Warecorp_User_Addressbook_CustomUser('id', '5');
//$contact->delete();

// remove contact_list
//$contact = new Warecorp_User_Addressbook_ContactList(true, 'id', '95');
//$contact->delete();

// remove addressbook
//$addressbook = new Warecorp_User_Addressbook_ContactList(false, 'id', '1');
//$addressbook->delete();

//exit;
Warecorp::addTranslation("/modules/users/action.addressbook.php.xml");
    $pageSize = (isset($this->params['pageSize'])) ? $this->params['pageSize'] : 10;
    $page = (isset($this->params['page'])) ? $this->params['page'] : 1;

    $order = (isset($this->params['orderby'])) ? $this->params['orderby'] : null;

    switch ($order) {
        case "first_name-asc": $orderBy = "first_name"; $desc = false; break;
        case "first_name-desc": $orderBy = "first_name"; $desc = true; break;
        case "last_name-asc": $orderBy = "last_name"; $desc = false; break;
        case "last_name-desc": $orderBy = "last_name"; $desc = true; break;
        case "email-asc": $orderBy = "email"; $desc = false; break;
        case "email-desc": $orderBy = "email"; $desc = true; break;
        case "maillists-asc": $orderBy = "maillists"; $desc = false; break;
        case "maillists-desc": $orderBy = "maillists"; $desc = true; break;
        default: $orderBy = "first_name"; $desc = false;
    }

    $fields_name = array("first_name" => Warecorp::t("firstname"),
                         "last_name"  => Warecorp::t("lastname"),
                         "email"      => Warecorp::t("email"),
                         "maillists"  => Warecorp::t("In mailing lists")
                    );
    // headers for tpl
    foreach ($fields_name as $key => $value)
        if ($key == $orderBy) 
            $headers[$key] = array("active" => true, "order" => $order, "desc" => $desc, "output" => $value, "url" => (!$desc) ? $key . "-desc" : $key . "-asc");
        else $headers[$key] = array("active" => true, "order" => null, "desc" => null, "output" => $value, "url" => $key . "-asc");
        
    $addressbook = $this->_page->_user->getAddressbook();
    $addressbook = new Warecorp_User_Addressbook_List('');
//    $contacts = $this->_page->_user->getAddressbook($orderBy,
//                                                    $desc,
//                                                    $page,
//                                                    $pageSize,
//                                                    (isset($this->params['filter'])) ? $this->params['filter'] : false
//                                                   );
//
//    $letterCounts = $this->_page->_user->getAddressbookLetters();
    //addressbook
//    $this->view->contacts = $contacts;
    //addressbookList
//    $this->view->contact_list = $this->_page->_user->getAddressbookList();

    $letters = array();
    $selected = ((isset($this->params['filter'])) && (preg_match('/[A-Z]/i',$this->params['filter']))) ? strtoupper($this->params['filter']) : false;

                 // @todo - multibyte charactersets support
    for($i = 'A'; $i != 'AA'; $i++) {
     $letters[] = array('count'   => (isset($letterCounts[$i])) ? $letterCounts[$i] : 0,
                        'letter'  => $i,
                        'selected'=> $selected == $i,
                        );
    }
    
    $this->view->letters = $letters;
    
    $this->view->orderBy = $order;
    
    // contacts list paging
//    $contactsCount = $this->_page->_user->getAddressbookCount((isset($this->params['filter'])) ? $this->params['filter'] : false);
    $P = new Warecorp_Common_PagingProduct($contactsCount, $pageSize, substr($this->currentUser->getUserPath((isset($this->params['filter'])) ? "addressbook/filter/".$this->params['filter'] : "addressbook"), 0, -1) );
    $this->view->paging = $P->makePaging($page);
    
    $formAddressbook = new Warecorp_Form('addressbook', 'post', $this->currentUser->getUserPath('addressbook'));
//    if ($formAddressbook->validate($this->params)) {
//        if (isset($this->params['contacts']) && is_array($this->params['contacts']) && count($this->params['contacts'])) {
//            foreach($this->params['contacts'] as $contactId) {
////                $contact = new Warecorp_User_Addressbook($contactId);
//                if ($contact->isExist) {
//                    if ($contact->ownerId == $this->_page->_user->getId()) {
//                        $contact->delete();
//                    }
//                }
//            }
//        }
//        
//        if (isset($this->params['maillists']) && is_array($this->params['maillists']) && count($this->params['maillists'])) {
//            foreach($this->params['maillists'] as $maillistId) {
//                $maillist = new Warecorp_User_Maillist($maillistId);
//                if ($maillist->isExist) {
//                    if ($maillist->userId == $this->_page->_user->getId()) {
//                        $maillist->delete();
//                    }
//                }
//            }
//        }
//        
//        $this->_redirect($this->_page->_user->getUserPath('addressbook'));
//    }
    
    $this->view->page = (isset($this->params['page'])) ? $this->params['page'] : null;
    $this->view->pageSize = (isset($this->params['pageSize'])) ? $this->params['pageSize'] : null;
    $this->view->headers = $headers;
    $this->view->filter = isset($this->params['filter']) ? $this->params['filter'] : null;
    $this->view->formAddressbook = $formAddressbook;
    $this->view->bodyContent = 'users/addressbook.tpl';
