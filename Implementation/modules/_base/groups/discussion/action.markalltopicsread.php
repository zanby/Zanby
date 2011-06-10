<?php
Warecorp::addTranslation('/modules/groups/discussion/action.markalltopicsread.php.xml');

    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    if ( isset($this->params['discussionid']) && floor($this->params['discussionid']) != 0 ) {
        $tmpDiscussion = new Warecorp_DiscussionServer_Discussion($this->params['discussionid']);
        if ( null !== $tmpDiscussion->getId() ) {
            $tmpDiscussion->setReadedForUser($this->_page->_user->getId());
        }
    } else {
	    /**
	     * get groups
	     */
        $groups = array($this->currentGroup);

        $discussionList = new Warecorp_DiscussionServer_DiscussionList();
        
        
        $subGroups = array();
        if ( $this->currentGroup->getGroupType() == 'family' ) {
            $subGroups = $this->currentGroup->getGroups()
                              ->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
                              ->getList();
        }
        $groups = array_merge($groups,$subGroups);
        foreach ( $groups as $group ) {
            $discussions = $discussionList->findByGroupId($group->getId());
            if ( sizeof($discussions) != 0 ) {
                foreach ( $discussions as $discus ) {
                    $discus->setReadedForUser($this->_page->_user->getId());
                }
                
            }
        }
    }
    $this->_redirect($this->currentGroup->getGroupPath('discussion'));