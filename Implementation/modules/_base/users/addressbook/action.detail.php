<?php
Warecorp::addTranslation("/modules/users/addressbook/action.detail.php.php.xml");

//view member/list/group details
$detail = Warecorp_User_Addressbook_Factory::loadById($this->params['detail']);
$this->view->detail = $detail;
$formEditContact = new Warecorp_Form('editContact',
                                     'post',
                                     '/en/addressbook/detail/' . (int)$this->params['detail'] . '/');

if ($formEditContact->validate($this->params)){
    //@todo - magic_quotes_gpc check
    $fields = array_map('stripslashes', $this->params['item']);
        
//    $contact = new Warecorp_User_Addressbook($fields['id']);
//        
//    if ($contact->init($this->currentUser->getId(), $fields)) {
//        $contact->save();
//        	
//        $this->_redirect('/en/addressbook/detail/'. (int)$this->params['detail'] .'/');
//    } else {
//        $this->_redirectError(Warecorp::t('Unauthorised: Invalid addressbook ID'));
//    }

}
//addressbookList
$this->view->contact_list = $this->_page->_user->getAddressbookList();
    
$this->view->formEditContact = $formEditContact;
    
$this->view->bodyContent = 'users/addressbook/detail.tpl';
