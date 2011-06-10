<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.deleteTopicSubscription.php.xml');

    $objResponse = new xajaxResponse();

    if ( isset($subsctiption_id) ) {
        $subscription = new Warecorp_DiscussionServer_TopicSubscription($subsctiption_id);
        if ( $subscription->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('discussionsettings');
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                $subscription->delete();                
                $objResponse->addRemove('TopicSubscriptionTR'.$subsctiption_id);
                $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));
            }
        } else {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('discussionsettings'));
        }
    } else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('discussionsettings'));
    }

