<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.invite.group.php.xml');
    $objResponse = new xajaxResponse();
    
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('calendar.month.view');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        return $objResponse;
    }
    
    $objGroup = Warecorp_Group_Factory::loadById($groupId);
    if ( null === $objGroup->getId() ) return;
    
    if ( $checked == 'true' ) {
        $groups = ( null === $groups ) ? array() : $groups;
        $checkedGroups = array();
        
        if ( !in_array($objGroup->getId(), $groups) )  $checkedGroups[] = $objGroup;        
        if ( sizeof($groups) != 0 ) {
            foreach ( $groups as $_groupId ) {
                if ( $_groupId != $groupId ) $checkedGroups[] = Warecorp_Group_Factory::loadById($_groupId);
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
    } else {
        $groups = ( null === $groups ) ? array() : $groups;
        $checkedGroups = array();
        if ( sizeof($groups) != 0 ) {
            foreach ( $groups as $_groupId ) {
                if ( $_groupId != $groupId ) $checkedGroups[] = Warecorp_Group_Factory::loadById($_groupId);
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
