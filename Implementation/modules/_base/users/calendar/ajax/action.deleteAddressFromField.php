<?php
    $objResponse = new xajaxResponse();
    
    if ( $mode == 'list' ) {
        $lists = ( null === $lists ) ? array() : $lists;
        $checkedLists = array();
        if ( sizeof($lists) != 0 ) {
            foreach ( $lists as $_listId ) {
                if ( $_listId != $itemId ) {
                    $checkedLists[] = new Warecorp_User_Addressbook_ContactList(false, 'id', $_listId);
                }
            }
        }
        $formParams = array();
        $formParams['event_invitations_lists'] = $checkedLists;
        $this->view->formParams = $formParams;

        $content = $this->view->getContents('users/calendar/action.event.template.contact.list.tpl');
        $objResponse->addAssign('EventInviteListsObjects', 'innerHTML', $content);
        if ( sizeof($checkedLists) == 0 ) {
            $objResponse->addAssign('EventInviteListsObjects', 'style.display', 'none');
        } else {
            $objResponse->addAssign('EventInviteListsObjects', 'style.display', '');
        }

    } elseif( $mode == 'group' ) {
        $groups = ( null === $groups ) ? array() : $groups;
        $checkedGroups = array();
        if ( sizeof($groups) != 0 ) {
            foreach ( $groups as $_groupId ) {
                if ( $_groupId != $itemId ) {
                    $checkedGroups[] = Warecorp_Group_Factory::loadById($_groupId);
                }
            }
        }
        $formParams = array();
        $formParams['event_invitations_groups'] = $checkedGroups;
        $this->view->formParams = $formParams;

        $content = $this->view->getContents('users/calendar/action.event.template.contact.group.tpl');
        $objResponse->addAssign('EventInviteGroupsObjects', 'innerHTML', $content);
        if ( sizeof($checkedGroups) == 0 ) {
            $objResponse->addAssign('EventInviteGroupsObjects', 'style.display', 'none');
        } else {
            $objResponse->addAssign('EventInviteGroupsObjects', 'style.display', '');
        }
    }
