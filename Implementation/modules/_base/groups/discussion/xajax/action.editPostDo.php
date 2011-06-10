<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.editPostDo.php.xml');

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
                    if ( trim($content) != '' ) {
                        if ( mb_strlen($content, 'UTF-8') <= 4096 ) {
                            $post->setFormat(DISCUSSION_MODE);
                            $post->setContent($content);
                            $post->updateContent();

                            if ( !Warecorp_DiscussionServer_Enum_SubscriptionType::isIn($subscriptionType) ) $subscriptionType = -1;
                            $subscription = Warecorp_DiscussionServer_TopicSubscription::findByTopicAndUserId($post->getTopicId(), $this->_page->_user->getId());
                            if ( $subscription !== null ) {
                                if ( $subscriptionType == -1 ) {
                                    $subscription->delete();
                                } else {
                                    $subscription->setsubscriptionType($subscriptionType);
                                    $subscription->update();
                                }
                            } else {
                                if ( $subscriptionType != -1 ) {
                                    $subscription = new Warecorp_DiscussionServer_TopicSubscription();
                                    $subscription->setTopicId($post->getTopicId());
                                    $subscription->setUserId($this->_page->_user->getId());
                                    $subscription->setSubscriptionType($subscriptionType);
                                    $subscription->save();
                                }
                            }
                            $objResponse->addScript('popup_window.close()');
                            $objResponse->addScript('document.location.reload();');
                        } else {
                            $objResponse->addAssign('ErrorMessageMainTooLong', 'style.display', '');
                            $objResponse->addAssign('ErrorMessageMain', 'style.display', 'none');
                        }
                    } else {
                        $objResponse->addAssign('ErrorMessageMain', 'style.display', '');
                        $objResponse->addAssign('ErrorMessageMainTooLong', 'style.display', 'none');
                    }
                }
            }
        } else {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('discussion'));
        }
    } else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('discussion'));
    }
