<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.chooseDiscussionForEdit.php.xml');

    $objResponse = new xajaxResponse();
    if ( floor($discussion_id) != 0 ) {
        $discussion = new Warecorp_DiscussionServer_Discussion($discussion_id);
        if ( null != $discussion->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('discussionhostsettings');
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                $objResponse->addAssign('edit_discussion_name', 'value', $discussion->getTitle());
                $objResponse->addAssign('edit_discussion_description', 'value', $discussion->getDescription());
            }
        } else {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('discussionhostsettings'));
        }
    } else {
        $objResponse->addAssign('edit_discussion_name', 'value', '');
        $objResponse->addAssign('edit_discussion_description', 'value', '');
    }
