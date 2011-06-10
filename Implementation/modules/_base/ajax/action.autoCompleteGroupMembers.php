<?php
    Warecorp::addTranslation("/modules/ajax/action.autoCompleteGroupMembers.php.xml");
    $objResponse = new xajaxResponse();
    //$objResponse->addAlert('autoCompleteCity Function called : query = '.$query.'');

    $result = array();
    $type = !is_array($type) || sizeof($type) == 0 ? array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST) : $type;
    $roles = array();
    if ( in_array( Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, $type ) ) $roles[] = Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER;
    if ( in_array( Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, $type ) ) $roles[] = Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST;
    if ( in_array( Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, $type ) ) $roles[] = Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST;
    if ( sizeof($roles) == 0 ) $roles = array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST);
          
    
    $objGroup = Warecorp_Group_Factory::loadById( $group );
    if ( $objGroup && $objGroup->getId() ) {
        if( $objGroup->getGroupType() == "family" ) {
            $membersList = new Warecorp_Group_Family_Members( $objGroup );
            $membersList->setGroupStatus( Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED );
            $membersList->setMembersStatus( 'approved' );
            $membersList->setOrder( 'zua.login ASC' );
            $membersList->returnAsAssoc();
            $membersList->setMembersRole( $roles );
            $membersList->addWhere("zua.login LIKE '".$query."%'");
            $members = $membersList->getList();            
        } else {
            $membersList = $objGroup->getMembers();
            $membersList->setMembersStatus( 'approved' );
            $membersList->setOrder( 'zua.login ASC' );
            $membersList->returnAsAssoc();
            $membersList->setMembersRole( $roles );
            $membersList->addWhere("zua.login LIKE '".$query."%'");
            $members = $membersList->getList();
        }
    }
    if ( isset($members) && is_array($members) && sizeof($members) != 0 ) {
        foreach ( $members as $uID => &$value ) {
            if( $objGroup->getGroupType() == "family" ) {
                $coowners = $objGroup->getMembers()->returnAsAssoc()->setMembersRole(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST)->getList();
                $coownersIDs = array_keys($coowners);
                $host = $objGroup->getHost();
                $hostID = ( $host && $host->getId() ) ? $host->getId() : null;
                if ( $hostID == $uID ) {
                    if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, $roles) ) {
                        $value = preg_replace("/[\n\r]/", "", $value);
                        $value = preg_replace("/\s{1,}/", " ", $value);
                        $result[][0] = $value;                    
                    }
                } elseif ( in_array($uID, $coownersIDs) ) {
                    if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, $roles) ) {
                        $value = preg_replace("/[\n\r]/", "", $value);
                        $value = preg_replace("/\s{1,}/", " ", $value);
                        $result[][0] = $value;                    
                    }
                } elseif (  in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, $roles) ) {
                    $value = preg_replace("/[\n\r]/", "", $value);
                    $value = preg_replace("/\s{1,}/", " ", $value);
                    $result[][0] = $value;                    
                }
            } else {
                $value = preg_replace("/[\n\r]/", "", $value);
                $value = preg_replace("/\s{1,}/", " ", $value);
                $result[][0] = $value;
            }
        }
    }
    $objResponse->addScriptCall($function, $result);
    