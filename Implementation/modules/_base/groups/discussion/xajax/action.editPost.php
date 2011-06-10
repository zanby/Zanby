<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.editPost.php.xml');

    $objResponse = new xajaxResponse();
    if ( floor($post_id) != 0 ) {
        $post = new Warecorp_DiscussionServer_Post($post_id);
        if ( null !== $post->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                //@todo надо добавить еще страницу, с которой пришел пользователь
                $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('topic').'topicid/'.$post->getTopicId().'/';
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                if ( !$post->getDiscussionAccessManager()->canEditPost($post, $this->_page->_user->getId()) ) {
                    $objResponse->addAlert(Warecorp::t('You can not edit this post. Contact please host of group.'));
                } else {
                    $subscription = Warecorp_DiscussionServer_TopicSubscription::findByTopicAndUserId($post->getTopicId(), $this->_page->_user->getId());
                    $this->view->post = $post;
 
                    if ( $post->getFormat() == 'bbcode' && DISCUSSION_MODE == 'html' ) {
                        $this->view->post_content = $post->getBBContent();
                    } elseif ( $post->getFormat() == 'html' && DISCUSSION_MODE == 'bbcode' ) {
                        $this->view->post_content = Warecorp_DiscussionServer_MailParser::prepareHtml( $post->getContent() );
                    } else {
                        $this->view->post_content = $post->getContent();
                    }
                    
                    $this->view->subscription = $subscription;
                    $this->view->subscribeContentOptions = Warecorp_DiscussionServer_Enum_SubscriptionType::getAsOptions();
                    $this->view->discussion_mode = DISCUSSION_MODE;
                    $content = $this->view->getContents('groups/discussion/edit.post.popup.tpl');
                    
                    $popup_window = Warecorp_View_PopupWindow::getInstance();
                    $popup_window->title(Warecorp::t('Edit Post'));
                    $popup_window->content($content);
                    $popup_window->width(DISCUSSION_MODE == 'html' ? 750 : 450)->height(DISCUSSION_MODE == 'html' ? 450 : 350)->open($objResponse);
                    
                    if ( DISCUSSION_MODE == 'html' ) $objResponse->addScript("initTinyMCE('');");
                }
            }
        }
    }


