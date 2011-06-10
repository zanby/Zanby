<?php
Warecorp::addTranslation('/modules/groups/xajax/action.autocompleteMembers.php.xml');
    
    $objResponse = new xajaxResponse();
    
    $members =  $this->currentGroup
    				 ->getMembers()
    				 ->returnAsAssoc()
    				 ->setMembersRole(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER)
    				 ->setOrder('login')
    				 ->setListSize(10)
    				 ->setCurrentPage(1)
    				 ->addWhere("zua.login LIKE '".preg_replace("/(_|%|'|\\\\)/", "\\\\\\1", $filter)."%'")
    				 ->getList();
    $members = array_values($members);
    foreach ($members as &$member) {
        $member = array($member);
    }
    
	$objResponse->addScriptCall($function, $members);