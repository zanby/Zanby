<?php
    Warecorp::addTranslation('/modules/groups/joinfamily/action.joinfamilystep2.php.xml');

    $this->_page->Xajax->registerUriFunction("saveTempData", "/groups/saveTempData/groupid/".$this->currentGroup->getId()."/");
    
    if ( !isset($_SESSION['joinfamily']) || !isset($_SESSION['joinfamily']['step1']) ) {
        $this->_redirect($this->currentGroup->getGroupPath('joinfamilystep1', true, false));
    }
    
    $family =Warecorp_Group_Factory::loadById($this->currentGroup->getId(),Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
    if ( $family->getJoinMode() == 0 || $family->getJoinMode() == 2) $status = "active"; 
    else $status = "pending";
    
    $groups = array();
    foreach ( $_SESSION["joinfamily"]["group_id"] as $group ) {
        $family->getGroups()->addGroup($group, $status);
        $groups[] = Warecorp_Group_Factory::loadById($group,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
    }
    
    switch ( $this->currentGroup->getJoinMode() ) {
        case 0 : //так надо  без брейка
        case 2 :
            $Subject = isset($_SESSION['joinfamily']['subject']) ? $_SESSION['joinfamily']['subject'] : null;
            $Message = isset($_SESSION['joinfamily']['text']) ? $_SESSION['joinfamily']['text'] : null;
            $this->currentGroup->sendFamilyJoinNewGroup( $this->currentGroup, $groups, $Subject, $Message );
            break;
        case 1 :
            $this->currentGroup->getMembers()->addMember($this->_page->_user->getId(), 'member', 'pending');
            $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
            $cache->remove('all_mygroups_menu_account_tools_'.$this->_page->_user->getId());
            
            $lstRecipients = array();
            
            /* START : Fix according Bug #1130 */
            if ( $this->currentGroup->getGroupType() == 'family' ) {
                $privilegesValues['OwnersOnly']                 = 0;
                $privilegesValues['OwnersAndGroupHosts']        = 1;
                $privilegesValues['AllMembers']                 = 2;
                $privilegesValues['OwnersAndCertainMembers']    = 3;
            } else {
                $privilegesValues['OwnersOnly']                 = 1;
                $privilegesValues['AllMembers']                 = 0;
                $privilegesValues['OwnersAndCertainMembers']    = 2;
                $privilegesValues['OwnersAndGroupHosts']        = -1;
            }
            $privileges = $this->currentGroup->getPrivileges();
            $listMembersIds = array();
            $listMembers = array();
            
            /* Owners already should receive notification */
            $listOwners = $this->currentGroup->getMembers()->setDistinct(true)->setAssocValue('id')->setMembersRole(array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST))->getList();
            switch ( $privileges->getManageMembers() ) {
                /* OwnersOnly */
                case $privilegesValues['OwnersOnly'] :
                    break;
                /* OwnersAndGroupHosts */
                case $privilegesValues['OwnersAndGroupHosts'] :
                    if ( $this->currentGroup->getGroupType() == 'family' ) {
                        $listMembersIds = $this->currentGroup->getMembers()->getHostsOfAllGroupsInFamily(true);
                        $listMembersIds = array_keys($listMembersIds);
                    }
                    break;
                /* AllMembers */
                case $privilegesValues['AllMembers'] :
                    $listMembersIds = $this->currentGroup->getMembers()->setDistinct(true)->setAssocValue('id')->returnAsAssoc()->setMembersRole(array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER))->getList();
                    break;
                /* OwnersAndCertainMembers */
                case $privilegesValues['OwnersAndCertainMembers'] :
                    $privileges = $this->currentGroup->getPrivileges();
                    $objUsersListByTool = $privileges->getUsersListByTool('gpManageMembers');
                    $listMembersIds = $objUsersListByTool->returnAsAssoc()->getList();
                    $listMembersIds = array_keys($listMembersIds);
                    break;
            }
            $usedUsersIds = array();
            
            /* Add owners to recipient list */
            foreach ( $listOwners as $_user ) {
                if ( !in_array($_user->getId(), $usedUsersIds) ) {
                    $lstRecipients[] = $_user;
                    $usedUsersIds[] = $_user->getId();
                }
            }
            unset($listOwners);
            
            /**
             * Add users to recipient list
             * load all user in one sql-query
             */
            if ( sizeof($listMembersIds) != 0 ) {
                $objUserList = new Warecorp_User_List();
                $objUserList->addWhere('zua.id IN ('.join(',', $listMembersIds).')');
                $listMembers = $objUserList->getList();
                unset($listMembersIds);
                unset($objUserList);
                foreach ( $listMembers as $_user ) {
                    if ( !in_array($_user->getId(), $usedUsersIds) ) {
                        $lstRecipients[] = $_user;
                        $usedUsersIds[] = $_user->getId();
                    }
                }
                unset($listMembers);
                unset($usedUsersIds);
            }
            unset($privilegesValues);
            unset($privileges);            
            /* END : Fix according Bug #1130 */

            $Subject = isset($_SESSION['joinfamily']['subject']) ? $_SESSION['joinfamily']['subject'] : null;
            $Message = isset($_SESSION['joinfamily']['text']) ? $_SESSION['joinfamily']['text'] : null;            
            $this->currentGroup->sendFamilyJoinRequest( $this->currentGroup, $lstRecipients, $groups, $Subject, $Message );
            
            break;
    }
    
    $this->_page->_user->setMembershipPeriod( 'annualy' ); 
    $this->_page->_user->setMembershipPlan( 'premium' );
    $this->_page->_user->setMembershipDowngrade( new Zend_Db_Expr('NULL') );
    $this->_page->_user->save();
    
    $this->view->family = $this->currentGroup;
    $this->view->groups = $groups;

    $this->view->bodyContent = 'groups/joinfamily/step2.tpl';
    
    unset($_SESSION['joinfamily']);
