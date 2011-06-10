<?php
    Warecorp::addTranslation("/modules/users/addressbook/action.sidebar.php.xml");
//Your groups list block (groupsList is used below too)
$groupsList = $this->_page->_user->getGroups()->getList();
$this->view->groups = $groupsList;

$this->view->maillists = $this->_page->_user->getMaillists();

//"Add Contact" form
$formAddContact = new Warecorp_Form('addContact','post','/'.$this->_page->Locale.'/addressbook/detail/');
$formAddContact->addRule('firstName', 'required', Warecorp::t('Fill first name'));
$formAddContact->addRule('lastName', 'required', Warecorp::t('Fill last name'));
$formAddContact->addRule('email', 'required', Warecorp::t('Fill email'));
$formAddContact->addRule('email','email', Warecorp::t('Enter correct Email Address'));
if (!empty($this->params['contact']['email2'])) {
    $formAddContact->addRule('email2','email', Warecorp::t('Enter correct Secondary Email Address'));

}
//print_r($this->params);
if (isset($this->params['contact'])) {
    if ($formAddContact->validate($this->params['contact'])) {
        
//        $contact = new Warecorp_User_Addressbook();
//        $contact->init($this->currentUser->getId(), $this->params['contact']);
//        $result = $contact->save();
        
        $this->_redirect('/'.$this->_page->Locale.'/addressbook/');
        
    } elseif (isset($this->params['contact'])) {
        $this->view->contact = $this->params['contact'];
    }
    
}

$this->view->formAddContact = $formAddContact;

//list of extended attributes for "add contact" formAddContact, hidden by default
$addContactMoreInputs = array('linkCollapse',
        					  'addContactEmail2',
        					  'addContactPhoneHome',
        					  'addContactPhoneBusiness',
        					  'addContactPhoneMobile',
        					  'addContactStreet',
        					  'addContactCity',
        					  'addContactState',
        					  'addContactZip',
        					  'addContactNotes',
        					  );

$this->view->formAddContactMoreInputs = $addContactMoreInputs;

//***********************************
//"Add Maillist" form
$formAddMaillist = new Warecorp_Form('addMaillist', 'post', '/'.$this->_page->Locale.'/addressbook/detail/');
$formAddMaillist->addRule('name', 'required', Warecorp::t('Fill Name of the list'));


/**
 * @todo
 * this must be "Text box for adding contacts. This auto-fills with
 * selections from the list of contacts and allows multiple entries, 
 * separated by a comma. (Similar to how 
 * gmail.com’s To: field functionality works)"
 * 
 * By 1_Z_My_Account_Friends_Messages_1.0.vsd, slide "Add list 1.4"
 **/

$groupSelectOptions = array(0=>'== Select group ==');

foreach($groupsList as $group) {
    $groupSelectOptions[$group->getId()] = $group->getName();
}

$this->view->addMaillistGroups = $groupSelectOptions;

if (isset($this->params['item'])) {
    if ($formAddMaillist->validate($this->params['item'])) {

        $maillist = new Warecorp_User_Maillist();
        $maillist->userId = $this->_page->_user->getId();

        $contacts = array_map('trim', explode(",", stripslashes($this->params['item']['contacts'])));
        $maillist->addEntryByName($contacts);
        $maillist->name = stripslashes($this->params['item']['name']);
        $maillist->groupId = ($this->params['item']['groupId']) ? $this->params['item']['groupId'] : NULL;
        $maillist->save();

        $this->_redirect($this->_page->_user->getUserPath('addressbook'));
    }
    $this->view->add_list_tab = 1;
}

$this->view->formAddMaillist = $formAddMaillist;

// sidebar assignment
$this->view->menuContent = 'users/addressbook/sidebar.tpl';
