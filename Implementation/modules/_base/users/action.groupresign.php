<?php
    if ( isset($this->params['groups']) && count($this->params['groups']) != 0 ) {
        foreach ( $this->params['groups'] as $group ) {
            $Group = Warecorp_Group_Factory::loadById($group);
            if ( !$Group->getMembers()->isHost($this->_page->_user->getId()) ) {
                $Group->getMembers()->removeMember($this->_page->_user->getId());
                //  Send message to host of group
                if ($Group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE) {
                    $Group->sendMemberResignFromGroup( $Group, $this->_page->_user );
                }
            }
        }
    }
    $this->_redirect($this->_page->_user->getUserPath('groups'));