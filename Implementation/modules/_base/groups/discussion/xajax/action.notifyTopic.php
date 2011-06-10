<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.notifyTopic.php.xml');

    $objResponse = new xajaxResponse();
    if ( floor($topic_id) != 0 ) {
        $topic = new Warecorp_DiscussionServer_Topic($topic_id);
        if ( null !== $topic->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                //@todo надо добавить еще страницу, с которой пришел пользователь
                $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('topic').'topicid/'.$topic_id.'/';
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                $subscription = Warecorp_DiscussionServer_TopicSubscription::findByTopicAndUserId($topic_id, $this->_page->_user->getId());
                $this->view->topic = $topic;
                $this->view->subscription = $subscription;
                $this->view->subscribeContentOptions = Warecorp_DiscussionServer_Enum_SubscriptionType::getAsOptions();
                $content = $this->view->getContents('groups/discussion/notify.topic.popup.tpl');
                
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->title(Warecorp::t('Notify Topic'));
                $popup_window->content($content);
                $popup_window->width(260)->height(350)->open($objResponse);

                // !!! CHECK IT @autor Komarovski 
                //$params->id = 'notifyTopic';
                //$params->width = "260px";
                //$params->modal = false;
                //$params->x = floor($x);
                //$params->y = floor($y) + 10;
                //$params->fixedcenter = false;
                //$params->constraintoviewport = false;
                //$params->close = true;
                //$params->title = Warecorp::t('Notify Topic');
                //$objResponse->addProcessMessage($content, $params);
            }
        }
    }
