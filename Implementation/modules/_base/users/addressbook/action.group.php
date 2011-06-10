<?php
    Warecorp::addTranslation("/modules/users/addressbook/action.group.php.xml");
    $this->_page->Xajax->registerUriFunction ( "addressbookAjaxUtilities", "/users/addressbookAjaxUtilities/" ) ; 
    $this->_page->Xajax->registerUriFunction ( "addressbookAjaxUtilitiesDoMaillist", "/users/addressbookAjaxUtilitiesDoMaillist/" ) ; 
    $this->_page->Xajax->registerUriFunction ( "addressbookDeleteContact", "/users/addressbookDeleteContact/" ) ; 
    if ( !$this->_page->_user->isAuthenticated() )
        $this->_redirectToLogin();
    
    if (!isset($this->params['id'])) 
        $this->_redirect($this->currentUser->getUserPath("addressbook"));
    
    $this->_page->Xajax->registerUriFunction ( "addressbookAjaxUtilities", "/users/addressbookAjaxUtilities/" ) ; 
    
    $urls = array('base' => $this->currentUser->getUserPath('addressbookgroup') . 'id/' . $this->params['id'] . '/',
                  'current' => ($this->currentUser->getUserPath('addressbookgroup') . 'id/' . $this->params['id'] . '/' . 
                    ((isset($this->params['filter'])) ? 'filter/' . $this->params['filter'] . '/' : '') .
                    ((isset($this->params['pageSize'])) ? 'pageSize/' . $this->params['pageSize'] . '/' : '') . 
                    ((isset($this->params['page'])) ? 'page/' . $this->params['page'] . '/' : '') . 
                    ((isset($this->params['orderby'])) ? 'orderby/' . $this->params['orderby'] . '/' : '') .
                    ((isset($this->params['direction'])) ? 'direction/' . $this->params['direction'] . '/' : '')),
                  'for_sort' => ($this->currentUser->getUserPath('addressbookgroup') . 'id/' . $this->params['id'] . '/' . 
                    ((isset($this->params['filter'])) ? 'filter/' . $this->params['filter'] . '/' : '') .
                    ((isset($this->params['pageSize'])) ? 'pageSize/' . $this->params['pageSize'] . '/' : '')),
                  'for_paging' => ($this->currentUser->getUserPath('addressbookgroup') . 'id/' . $this->params['id'] . '/' . 
                    ((isset($this->params['filter'])) ? 'filter/' . $this->params['filter'] . '/' : '') .
                    ((isset($this->params['pageSize'])) ? 'pageSize/' . $this->params['pageSize'] . '/' : '') . 
                    ((isset($this->params['orderby'])) ? 'orderby/' . $this->params['orderby'] . '/' : '') .
                    ((isset($this->params['direction'])) ? 'direction/' . $this->params['direction'] . '/' : '')),
                  'for_filter' => ($this->currentUser->getUserPath('addressbookgroup') . 'id/' . $this->params['id'] . '/' . 
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
    try {
        $maillist = new Warecorp_User_Addressbook_Group($this->params['id']);
    } catch (Exception $exc) {
        $this->_redirect($this->currentUser->getUserPath("addressbook"));
    }
    $maillistContactList = $maillist->getContacts();
    $letterCounts = $maillistContactList->getLettersCount();
    if ( isset($this->params['filter']) ) $maillistContactList->addWhere('UPPER(SUBSTRING(firstname, 1, 1)) = ?', $this->params['filter']);
    
    $maillistContactList->setOrder($orderBy . ' ' . $direction);
    $maillistContactList->setCurrentPage($page);
    $maillistContactList->setListSize($pageSize);
        
    $contacts = $maillistContactList->getList();
    foreach ($contacts as &$contact) {
        $userId = $contact->getId();
        $user = Warecorp_User_Addressbook_User::loadByEntityId($userId, $this->_page->_user->getId());
        if (!$user){
            $user = new Warecorp_User_Addressbook_User();
            $user->setContactOwnerId($this->_page->_user->getId());
            $user->setEntityId($userId);
            $user->setUserId($userId);
        }
        $contact = $user;
        unset($user);
    }
    $contacts = Warecorp_User_Addressbook_ContactList::alterForOutput($contacts);
    $letters = array();
    $selected = ((isset($this->params['filter'])) && (preg_match('/[A-Z]/i',$this->params['filter']))) ? strtoupper($this->params['filter']) : false;

    for($i = 'A'; $i != 'AA'; $i++) {
     $letters[] = array('count'   => (isset($letterCounts[$i])) ? $letterCounts[$i] : 0,
                        'letter'  => $i,
                        'selected'=> $selected == $i,
                        );
    }

    $contactsCount = $maillistContactList->getCount(); 
    $P = new Warecorp_Common_PagingProduct($contactsCount, $pageSize, substr($urls['for_paging'], 0, -1));
    $this->view->infoPaging = $P->makeInfoPaging($page);
    $this->view->linkPaging = $P->makeLinkPaging($page);
    $fullContactList = $maillist->getContacts();
    $fullContacts = $fullContactList->getList();
    $contactIds = array();
    foreach ($fullContacts as $fullContact){
        $contactIds[] = $fullContact->getId();
    }
    
    $contactLists = Warecorp_User_Addressbook_ContactList::getContactLists($this->_page->_user->getId());
    $this->view->contactLists = $contactLists;
    $this->view->page = (isset($this->params['page'])) ? $this->params['page'] : null;
    $this->view->pageSize = (isset($this->params['pageSize'])) ? $this->params['pageSize'] : null;
    $this->view->headers = $headers;
    $this->view->filter = isset($this->params['filter']) ? $this->params['filter'] : null;
    $this->view->contacts = $contacts;
    $this->view->contactIds = implode(',',$contactIds);
    $this->view->letters = $letters;
    $this->view->urls = $urls;
    $this->view->maillist = $maillist;
    $this->view->location = 'maillist';
    $this->view->bodyContent = 'users/addressbook/group.tpl';
