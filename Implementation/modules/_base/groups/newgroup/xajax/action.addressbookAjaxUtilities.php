<?php
Warecorp::addTranslation('/modules/groups/newgroup/xajax/action.addressbookAjaxUtilites.php.xml');

    $objResponse = new xajaxResponse();

    $_SESSION['addresses'] = isset($_SESSION['addresses'])?$_SESSION['addresses']:array();
    $checkedContacts = &$_SESSION['addresses'];
    if (isset($params['contacts']) && $params != 'undefined') {
        foreach($params['contacts'] as $key=>$cid) {
            $checkedContacts[$cid] = $params['contacts_emails_'.$cid];
        }
    }

    $pageSize           = (isset($pageSize)) ? $pageSize : 10;
    $page               = (isset($page)) ? $page : 1;
    $invert_dir['asc']  = 'desc';    
    $invert_dir['desc'] = 'asc';
    $orderBy = (isset($orderby)) ? $orderby : 'firstname';
    $direction = (isset($direction)) ? $direction : 'asc';                
    $fields_name = array("firstname" => Warecorp::t("First"),
                    "lastname" => Warecorp::t("Last"),
                    "email" => Warecorp::t("Email Address"),
                    "maillists" => Warecorp::t("In mailing lists")
                    );
    // headers for tpl
    if (!in_array($orderBy, array('firstname', 'lastname', 'email'))) {
        $orderBy = 'firstname';
    }
    if (!in_array($direction, array('asc', 'desc'))) {
        $orderBy = 'asc';
    }
    foreach ($fields_name as $key => $value)
        if ($key == $orderBy) 
            $headers[$key] = array("active" => true, "order" => $orderBy, "direction" => $invert_dir[$direction], "output" => $value);
        else $headers[$key] = array("active" => false, "order" => null, "direction" => 'asc', "output" => $value);
    

    
    $userAddressbookContactList = $this->_page->_user->getAddressbook()->getContacts();
    $letterCounts = $userAddressbookContactList->getAddressbookLetters($this->_page->_user->getId(), true);
    if ( isset($filter) && $filter!='') $userAddressbookContactList->addWhere('UPPER(SUBSTRING(contact_name, 1, 1)) = ?', $filter);
    $userAddressbookContactList->setOrder($orderBy, $direction);
    $userAddressbookContactList->setCurrentPage($page);
    $userAddressbookContactList->setListSize($pageSize);
    $contacts = $userAddressbookContactList->getList(array(Warecorp_User_Addressbook_eType::USER, Warecorp_User_Addressbook_eType::CUSTOM_USER));
//    foreach ($contacts as $contact){
//        $objResponse->addAlert($contact->getDisplayName());    
//    }
//    $contactList = new Warecorp_User_Addressbook_ContactList();
    //dump($checkedContacts);
    //exit;    
    foreach ($contacts as $contact){
        $currentContacts[$contact->getContactId()] = (!empty($checkedContacts[$contact->getContactId()]))?$contact->getContactId():0;
        $checkedContacts[$contact->getContactId()] = '';
    }

    $contacts = Warecorp_User_Addressbook_ContactList::alterForOutput($contacts);
    
    $letters = array();
    $selected = ((isset($filter)) && (preg_match('/[A-Z]/i',$filter))) ? strtoupper($filter) : false;
            
    // @todo - multibyte charactersets support
    for($i = 'A'; $i != 'AA'; $i++) {
     $letters[] = array('count'   => (isset($letterCounts[$i])) ? $letterCounts[$i] : 0,
                        'letter'  => $i,
                        'selected'=> $selected == $i,
                        );
    }
    
    // contacts list paging
    $contactsCount = $userAddressbookContactList->getCount(array(Warecorp_User_Addressbook_eType::USER, Warecorp_User_Addressbook_eType::CUSTOM_USER)); 
    $P = new Warecorp_Common_PagingProduct($contactsCount, $pageSize, "#");
    //$objResponse->addAlert('xajax_addressbookAjaxUtilities(\''.$maillistId.'\', \''.implode(',',$existingContacts).'\',\''.implode(',',$newContacts).'\',', ',10,\''.$orderby.'\',\''.$direction.'\', \''.$filter.'\');return false;');
    $this->view->linkPaging = $P->makeAjaxLinkPaging($page, 'xajax_addressbookAjaxUtilities(xajax.getFormValues(\'addContactToListForm\'),',','.$pageSize.',\''.$orderby.'\',\''.$direction.'\', \''.$filter.'\');return false;');
    $this->view->infoPaging = $P->makeInfoPaging($page);
    
    $form = new Warecorp_Form('addContactToListForm', 'post', 'javascript:void(0);');
    
    $this->view->contacts = empty($contacts)?null:$contacts;
    $this->view->form = $form;
    $this->view->letters = $letters;
    $this->view->orderBy = $orderBy;
    $this->view->page = $page;
    $this->view->currentContacts = isset($currentContacts)?$currentContacts:null;
    $this->view->pageSize = $pageSize;
    $this->view->headers = $headers;
    //$this->view->maillistId = $maillistId;
    $this->view->filter = $filter;
    $template = 'newgroup/settings.ajaxUtilities.tpl';
    $Content = $this->view->getContents ( $template ) ;
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("My addressbook"));
    $popup_window->content($Content);
    $popup_window->width(450)->height(($pageSize*40+300))->open($objResponse);  
