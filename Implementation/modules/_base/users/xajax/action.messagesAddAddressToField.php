<?php
    $objResponse = new xajaxResponse();

    $lists = ( null === $lists ) ? array() : $lists;
    $checkedLists = array();
    if ( sizeof($lists) != 0 ) {
        foreach ( $lists as $_listId ) $checkedLists[] = new Warecorp_User_Addressbook_ContactList(false, 'id', $_listId);
    }
    $groups = ( null === $groups ) ? array() : $groups;
    $checkedGroups = array();
    if ( sizeof($groups) != 0 ) {
        foreach ( $groups as $_groupId ) $checkedGroups[] = Warecorp_Group_Factory::loadById($_groupId);
    }

    $_SESSION['addresses']  = isset($_SESSION['addresses']) ? $_SESSION['addresses'] : array();
    $checkedContacts        = &$_SESSION['addresses'];

    if (null !== $params && isset($params['contacts'])) {
        foreach($params['contacts'] as $key=>$cid) {
            $checkedContacts[$cid] = $params['contacts_emails_'.$cid];
        }
    }

    $contacts = new Warecorp_User_Addressbook_List(null, $this->_page->_user->getId());
    $contacts = $contacts->setIncludeCidsCondition($checkedContacts)->getList();
    $checkedContacts = array();
    foreach ($contacts as &$contact) {
         if ($contact->getClassName() == Warecorp_User_Addressbook_eType::CONTACT_LIST) {
            //$checkedContacts[$contact->getContactId()] = $contact->getDisplayName().'[list]';
            if ( !in_array($contact->getContactListId(), $lists) ) $checkedLists[] = $contact;
         } elseif ($contact->getClassName() == Warecorp_User_Addressbook_eType::GROUP) {
            //$checkedContacts[$contact->getContactId()] = $contact->getGroup()->getName().'[group]'; 
            if ( !in_array($contact->getGroup()->getId(), $groups) ) $checkedGroups[] = $contact->getGroup();
         } else {
            $checkedContacts[$contact->getContactId()] = '<'.$contact->getEmail().'>';
         }
    }

    /**
    * @desc 
    */
    $formParams = array();
    $formParams['mail_lists'] = $checkedLists;
    $formParams['mail_groups'] = $checkedGroups;
    $this->view->formParams = $formParams;

    $content = $this->view->getContents('users/messages/contact.list.tpl');
    $objResponse->addAssign('ListsObjects', 'innerHTML', $content);
    if ( sizeof($checkedLists) == 0 ) {
        $objResponse->addAssign('ListsObjects', 'style.display', 'none');
    } else {
        $objResponse->addAssign('ListsObjects', 'style.display', '');
    }
    
    $content = $this->view->getContents('users/messages/contact.group.tpl');
    $objResponse->addAssign('GroupsObjects', 'innerHTML', $content);
    if ( sizeof($checkedGroups) == 0 ) {
        $objResponse->addAssign('GroupsObjects', 'style.display', 'none');
    } else {
        $objResponse->addAssign('GroupsObjects', 'style.display', '');
    }
    /**
    * @desc 
    */

    if ('' !== trim($old_value)) {
        $old_value = explode(',',$old_value);
        $old_value = array_map('trim',$old_value);    
        $checkedContacts = array_merge($old_value,$checkedContacts);
    }

    $checkedContacts = array_map('trim',$checkedContacts);
    $checkedContacts = array_unique($checkedContacts);    
    $checkedContacts = array_filter($checkedContacts);    
    $addresses = implode(', ',$checkedContacts);

    unset($_SESSION['addresses']);
        
    $objResponse->addScript("document.getElementById('target_emails').value = '$addresses';");
    $objResponse->addScript("popup_window.close();");
    
    
