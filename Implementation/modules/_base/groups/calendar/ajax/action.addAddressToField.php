<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.addAddressToField.php.xml');
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

    $_SESSION['addresses'] = isset($_SESSION['addresses'])?$_SESSION['addresses']:array();
    $checkedContacts = &$_SESSION['addresses'];

    if (null !== $params && isset($params['contacts'])) {
        foreach($params['contacts'] as $key=>$cid) {
            $checkedContacts[$cid] = $params['contacts_emails_'.$cid];
        }
    }
    
    $contacts = new Warecorp_User_Addressbook_List(null, $this->_page->_user->getId());
    $contacts = $contacts->setIncludeCidsCondition($checkedContacts)->getList();
    $checkedContacts = array();
    foreach ($contacts as $contact) {
         if ($contact->getClassName() == Warecorp_User_Addressbook_eType::CONTACT_LIST) {
            //$checkedContacts[$contact->getContactId()] = $contact->getDisplayName().'[list]';
            if ( !in_array($contact->getContactListId(), $lists) ) $checkedLists[] = $contact;
         } elseif ($contact->getClassName() == Warecorp_User_Addressbook_eType::GROUP) {
            if ( !in_array($contact->getGroup()->getId(), $groups) ) $checkedGroups[] = $contact->getGroup();
            if ( $this->currentGroup->getId() == $contact->getGroup()->getId() ) {
                $objResponse->addScript('YAHOO.util.Dom.get("event_invite_entire_group").checked = true;');
            }
            //$checkedContacts[$contact->getContactId()] = $contact->getGroup()->getName().'[group]';         
         } else {
            $checkedContacts[$contact->getContactId()] = $contact->getEmail();
         }
    }
    
    /**
    * @desc 
    */
    $formParams = array();
    $formParams['event_invitations_lists'] = $checkedLists;
    $formParams['event_invitations_groups'] = $checkedGroups;
    $this->view->formParams = $formParams;

    $content = $this->view->getContents('groups/calendar/action.event.template.contact.list.tpl');
    $objResponse->addAssign('EventInviteListsObjects', 'innerHTML', $content);
    if ( sizeof($checkedLists) == 0 ) {
        $objResponse->addAssign('EventInviteListsObjects', 'style.display', 'none');
    } else {
        $objResponse->addAssign('EventInviteListsObjects', 'style.display', '');
    }
    
    $content = $this->view->getContents('groups/calendar/action.event.template.contact.group.tpl');
    $objResponse->addAssign('EventInviteGroupsObjects', 'innerHTML', $content);
    if ( sizeof($checkedGroups) == 0 ) {
        $objResponse->addAssign('EventInviteGroupsObjects', 'style.display', 'none');
    } else {
        $objResponse->addAssign('EventInviteGroupsObjects', 'style.display', '');
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
    $addresses = implode(', ',$checkedContacts);  

    unset($_SESSION['addresses']);
        
    $objResponse->addScript("document.getElementById('inv_emails').value = '$addresses';");
    $objResponse->addScript("popup_window.close();");
    
?>
