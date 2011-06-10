<?php
    $objResponse = new xajaxResponse();
    //loading from session if exist
    if (!isset($_SESSION['contacts'.$this->_page->_user->getId()])){
        $contacts = array();
        $list = $this->_page->_user->getAddressbook()->getContacts()->getList();
        foreach ($list AS $contact){
            //custom users are Warecorp_User objects
            if ($contact->getClassName() == Warecorp_User_Addressbook_eType::CUSTOM_USER){
                $contacts[] = array('<'.$contact->getEmail().'>', '"'.$contact->getFirstName().' '.$contact->getLastName().'" &lt;'.$contact->getEmail().'&gt;');
            } elseif ($contact->getClassName() == Warecorp_User_Addressbook_eType::GROUP_MEMBER || 
                      $contact->getClassName() == Warecorp_User_Addressbook_eType::FRIEND ||
                      $contact->getClassName() == Warecorp_User_Addressbook_eType::USER){
                $contacts[] = array('<'.$contact->getEmail().'>', '"'.$contact->getUser()->getFirstName().' '.$contact->getUser()->getLastName().'" &lt;'.$contact->getEmail().'&gt; - ('.$contact->getUser()->getLogin().')');
            }         
        }
        $_SESSION['contacts'.$this->_page->_user->getId()] = $contacts;
    }
    //loading from session
    $contacts = $_SESSION['contacts'.$this->_page->_user->getId()]; 

    $params_array = array();
    //if value contains $filter and not already inserted - add to output
    foreach ($contacts as $contact){
        if (preg_match('/'.$filter.'/i', $contact[1]) && strpos($fieldValue, $contact[0]) === false){
            $params_array[] = $contact;
        }         
    }

	$objResponse->addScriptCall($function_name, $params_array);
