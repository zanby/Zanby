<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.changeListSize.php.xml');

    $objResponse = new xajaxResponse();
    if ( $mode == 'recenttopic' ) {
        $_SESSION['recenttopic']['size'] = $size;
        if ( $this->_page->_user->isAuthenticated() ) {
            $settings = new Warecorp_DiscussionServer_User_Settings($this->_page->_user->getId());
            $settings->setRecentTopicPerPage($size);
            $settings->save();
        }
        $objResponse->addRedirect($this->currentGroup->getGroupPath('recenttopic'));
    } elseif ( $mode == 'topic' ) {
        $_SESSION['topic']['size'] = $size;
        if ( $this->_page->_user->isAuthenticated() ) {
            $settings = new Warecorp_DiscussionServer_User_Settings($this->_page->_user->getId());
            $settings->setTopicPerPage($size);
            $settings->save();
        }
        $objResponse->addRedirect($this->currentGroup->getGroupPath('topic').'topicid/'.$topic_id.'/');
    } elseif ( $mode == 'search' ) {
        $_SESSION['search']['size'] = $size;
        if ( $this->_page->_user->isAuthenticated() ) {
            $settings = new Warecorp_DiscussionServer_User_Settings($this->_page->_user->getId());
            $settings->setSearchPerPage($size);
            $settings->save();
        }
        $objResponse->addRedirect($this->currentGroup->getGroupPath('discussionsearch'));
    } elseif ( $mode == 'blog' ) {
        $_SESSION['blog']['size'] = $size;
        if ( $this->_page->_user->isAuthenticated() ) {
            $settings = new Warecorp_DiscussionServer_User_Settings($this->_page->_user->getId());
            $settings->setBlogPerPage($size);
            $settings->save();
        }
        $objResponse->addRedirect($this->currentGroup->getGroupPath('blog'));
    } elseif ( $mode == 'blogdetails' ) {
        $_SESSION['blogDetails']['size'] = $size;
        if ( $this->_page->_user->isAuthenticated() ) {
            $settings = new Warecorp_DiscussionServer_User_Settings($this->_page->_user->getId());
            $settings->setBlogCommentsPerPage($size);
            $settings->save();
        }
        $objResponse->addRedirect($this->currentGroup->getGroupPath('blog.details').'id/'.$topic_id.'/');
    }