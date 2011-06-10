<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.removeTopic.php.xml');

    $objResponse = new xajaxResponse();
    if ( floor($topic_id) != 0 ) {
        $topic = new Warecorp_DiscussionServer_Topic($topic_id);
        if ( null !== $topic->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                //@todo надо добавить еще страницу, с которой пришел пользователь
                $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('topic').'topicid/'.$topic_id.'/';
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                if ( !$topic->getDiscussionAccessManager()->canManageTopic($topic->getId(), $this->_page->_user->getId()) ) {
                    $objResponse->addAlert(Warecorp::t('You can not manage this topic. Contact please host of group.'));
                } else {
                    $this->view->topic = $topic;
                    $content = $this->view->getContents('groups/discussion/delete.topic.popup.tpl');

                    $popup_window = Warecorp_View_PopupWindow::getInstance();
                    $popup_window->title(Warecorp::t('Remove Topic'));
                    $popup_window->content($content);
                    $popup_window->width(306)->height(80)->open($objResponse);
                }
            }
        }
    }

