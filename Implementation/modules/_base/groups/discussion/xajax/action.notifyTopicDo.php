<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.notifyTopicDo.php.xml');

    $objResponse = new xajaxResponse();

    if ( floor($topic_id) != 0 ) {
        $topic = new Warecorp_DiscussionServer_Topic($topic_id);
        if ( null !== $topic->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                //@todo надо добавить еще страницу, с которой пришел пользователь
                $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('topic').'topicid/'.$topic_id.'/';
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                if ( !Warecorp_DiscussionServer_Enum_SubscriptionType::isIn($subscriptionType) ) $subscriptionType = -1;
                $subscription = Warecorp_DiscussionServer_TopicSubscription::findByTopicAndUserId($topic_id, $this->_page->_user->getId());
                if ( $subscription !== null ) {
                    if ( $subscriptionType == -1 ) {
                        $subscription->delete();
                    } else {
                        $subscription->setSubscriptionType($subscriptionType);
                        $subscription->update();
                        /**
                         * turn on Only subscribe to specific discussions and topics
                         */
                        $subscriptionGroup = Warecorp_DiscussionServer_GroupSubscription::findByGroupAndUserId($this->currentGroup->getId(), $this->_page->_user->getId());
                        if ( $subscriptionGroup->getSubscriptionMode() == 1 ) {
                            $subscriptionGroup->setSubscriptionMode(3);
                            $subscriptionGroup->update();
                        }
                    }
                } else {
                    if ( $subscriptionType != -1 ) {
                        $subscription = new Warecorp_DiscussionServer_TopicSubscription();
                        $subscription->setTopicId($topic_id);
                        $subscription->setUserId($this->_page->_user->getId());
                        $subscription->setSubscriptionType($subscriptionType);
                        $subscription->save();
                        /**
                         * turn on Only subscribe to specific discussions and topics
                         */
                        $subscriptionGroup = Warecorp_DiscussionServer_GroupSubscription::findByGroupAndUserId($this->currentGroup->getId(), $this->_page->_user->getId());
                        if ( $subscriptionGroup->getSubscriptionMode() == 1 ) {
                            $subscriptionGroup->setSubscriptionMode(3);
                            $subscriptionGroup->update();
                        }
                    }
                }
                $objResponse->addScript('popup_window.close();');
            }
        } else {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('discussion'));
        }
    } else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('discussion'));
    }
