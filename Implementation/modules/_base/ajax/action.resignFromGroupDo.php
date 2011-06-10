<?php
    Warecorp::addTranslation("/modules/ajax/action.resignFromGroupDo.php.xml");
    $objResponse = new xajaxResponse ( ) ;
    // copy of action.groupresign.php
    // copied by Halauniou
    // bit modified by Targonsky :)

    $Group = Warecorp_Group_Factory::loadById($groupId);
    if ($Group->getGroupUID() == 'theuptake-main-group') return;
    if ( $Group->getId() ) {
    	if ( !$Group->getMembers()->isHost($this->_page->_user->getId()) ) {
            $Group->getMembers()->removeMember($this->_page->_user->getId());
            $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
            $cache->remove('all_mygroups_menu_account_tools_'.$this->_page->_user->getId());
    		//  Send message to host of group
    		if ($Group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE) {
    		    $Group->sendMemberResignFromGroup( $Group, $this->_page->_user );
    		}
    	}
    }

    //remove, if no members

    if ($Group->getMembers()->getCount() === 0) $Group->delete();
    $objResponse->addRedirect($_SERVER['HTTP_REFERER']);