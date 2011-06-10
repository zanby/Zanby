<?php
    Warecorp::addTranslation("/modules/users/addressbook/action.addmaillist.php.xml");
    $this->_page->Xajax->registerUriFunction ( "addressbookAjaxUtilities", "/users/addressbookAjaxUtilities/" ) ; 
    $this->_page->Xajax->registerUriFunction ( "addressbookAjaxUtilitiesDo", "/users/addressbookAjaxUtilitiesDo/" );

    function checkLogin($array)
    {
        $user = new Warecorp_User('login', $array[0]);
        return !$user->isExist || !Warecorp_User_Addressbook_User::loadByEntityId($user->getId(), $array[1]);
    }
    function isMaillistExist($maillist)
    {
        return $maillist->isContactListExist($maillist->getContactListOwnerId(), $maillist->getContactListName());
    }
    
    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    
    $_url = $this->_page->_user->getUserPath('addressbookaddmaillist');
    
    $form = new Warecorp_Form('newList', 'post', $_url);
    if (isset($this->params['submit']))
    {
        $maillist = new Warecorp_User_Addressbook_ContactList();
        $maillist->setContactListName($this->params['nameList']);
        $maillist->setContactListOwnerId($this->_page->_user->getId());
        $form->addRule('nameList',    'required',  Warecorp::t('Enter please Name of this list'));
        $form->addRule('nameList',    'maxlength', Warecorp::t('Name of this list too long (max %s)',50), array('max' => 50));
        $form->addRule('nameList',    'regexp',    Warecorp::t('Name of this list must start with letter'), array('regexp' => "/^[a-zA-Z]{1}/"));
        $form->addRule('nameList',    'regexp',    Warecorp::t('Name of this list may consist of a-Z, 0-9, \', -, underscores, space, and dot (.)'), array('regexp' => "/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/"));
        $form->addRule('nameList', 'callback', Warecorp::t("Mailing List with name '%s' is already exist. Enter please another name", $maillist->getContactListName()),
                                        array('func'=>'isMaillistExist', 'params'=>$maillist));
        $contacts = Warecorp_User_Addressbook_ContactList::stringToArray(isset($this->params['addContacts'])? $this->params['addContacts'] : '', array(';', ',', ' '));
        foreach ($contacts as $item)
        {
            $form->addRule('addContacts', 'callback', Warecorp::t("Contact with name '%s' does not exist", $item),
                                        array('func'=>'checkLogin', 'params'=> array($item, $this->_page->_user->getId())));
        }

        $contactsFromAjax = isset($this->params['contacts']) ? $this->params['contacts'] : array();
        foreach ($contactsFromAjax as $index => &$contact)
        {
            $contact = Warecorp_User_Addressbook_Factory::loadById($contact);
            if ($contact->getContactOwnerId() != $this->_page->_user->getId() && ($contact->getClassname() == Warecorp_User_Addressbook_eType::CUSTOM_USER || $contact->getClassname() == Warecorp_User_Addressbook_eType::USER)) {
            	unset($contactsFromAjax[$index]);
            }
        }
        if ($form->validate($this->params)) {
            $maillist->save();
            //Adding contacts from textbox
            foreach ($contacts as &$contact)
            {
                $userId = $contact;
                $user = new Warecorp_User('login', $userId);
                $contact = Warecorp_User_Addressbook_User::loadByEntityId($user->getId(), $this->_page->_user->getId());
            }
            $maillist->addContacts($contacts);

//            Adding contacts from ajax

            $maillist->addContacts($contactsFromAjax);
            $this->_page->showAjaxAlert(Warecorp::t('Added'));
            $this->_redirect($this->_page->_user->getUserPath('addressbook'));
        } else {
            $this->view->nameList = $this->params['nameList'];
            $this->view->addContacts = $this->params['addContacts'];
            $this->view->contacts = Warecorp_User_Addressbook_ContactList::alterForOutput($contactsFromAjax);
        }
    }
    $contactLists = Warecorp_User_Addressbook_ContactList::getContactLists($this->_page->_user->getId());

    $this->view->contactLists = $contactLists;
    $this->view->form = $form;
    $this->view->bodyContent = 'users/addressbook/addmaillist.tpl';
