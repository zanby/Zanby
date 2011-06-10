<?php
Warecorp::addTranslation('/modules/groups/newgroup/xajax/action.addAddress.php.xml');

    $objResponse = new xajaxResponse();
    $_SESSION['addresses'] = isset($_SESSION['addresses'])?$_SESSION['addresses']:array();
    $checkedContacts = &$_SESSION['addresses'];
    if (isset($params['contacts']) && $params != 'undefined') {
        foreach($params['contacts'] as $key=>$cid) {
            $checkedContacts[$cid] = $params['contacts_emails_'.$cid];
        }
    }
    $checkedContacts = array_unique($checkedContacts);    
    $addresses = Warecorp_User_Addressbook_ContactList::arrayToString($checkedContacts, ',',' ');
    $_SESSION['addresses'] = null;
    unset($_SESSION['addresses']);

    $objResponse->addScript("document.getElementById('emails').value = document.getElementById('emails').value + '$addresses';");
    $objResponse->addScript("popup_window.close();");
    
    