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
        $formParams['mail_lists'] = $checkedLists;
        $this->view->formParams = $formParams;

        $content = $this->view->getContents('users/messages/contact.list.tpl');
        $objResponse->addAssign('ListsObjects', 'innerHTML', $content);
        if ( sizeof($checkedLists) == 0 ) {
            $objResponse->addAssign('ListsObjects', 'style.display', 'none');
        } else {
            $objResponse->addAssign('ListsObjects', 'style.display', '');
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
        $formParams['mail_groups'] = $checkedGroups;
        $this->view->formParams = $formParams;

        $content = $this->view->getContents('users/messages/contact.group.tpl');
        $objResponse->addAssign('GroupsObjects', 'innerHTML', $content);
        if ( sizeof($checkedGroups) == 0 ) {
            $objResponse->addAssign('GroupsObjects', 'style.display', 'none');
        } else {
            $objResponse->addAssign('GroupsObjects', 'style.display', '');
        }
    }
