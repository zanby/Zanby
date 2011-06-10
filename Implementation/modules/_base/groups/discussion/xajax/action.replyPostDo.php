<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.replyPostDo.php.xml');

	$objResponse = new xajaxResponse();

	if ( floor($post_id) != 0 ) {
		$post = new Warecorp_DiscussionServer_Post($post_id);
		if ( null !== $post->getId() ) {
			if ( !$this->_page->_user->isAuthenticated() ) {
				//@todo надо добавить еще страницу, с которой пришел пользователь
				$_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('topic').'topicid/'.$post->getTopicId().'/';
				$objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
			} else {
                if ( !$post->getDiscussionAccessManager()->canReplyPost($post, $this->_page->_user->getId()) ) {
                    $objResponse->addAlert(Warecorp::t('You can not reply this post. Contact please host of group.'));
                } else {
    				//@todo  Validate Content
    				if ( trim($content) != '' ) {
    				    if ( mb_strlen($content, 'UTF-8') <= 4096 ) {
                            $new_post = new Warecorp_DiscussionServer_Post();
                            $new_post->setTopicId($post->getTopicId());
                            $new_post->setParentId($post->getId());
                            $new_post->setAuthorId($this->_page->_user->getId());
                            $new_post->setContent($content);
                            $new_post->setFormat(DISCUSSION_MODE);
                            $new_post->save();
                            $new_post->setReadedForUser($this->_page->_user->getId());

                            $new_post = new Warecorp_DiscussionServer_Post($new_post->getId());
                            
                            /**
                            * Removed according to Bug #3042
                            * Don't remove this commented block
                            * 
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
                            */
                            
                            /**
                            * build redirect
                            * default mode sortmode = 2
                            */
                            $currentPage       = (isset($currentPage)) ? $currentPage : 1 ;
                            $sortmode   = (isset($sortmode))? $sortmode : 2;
                            
                            if ( $sortmode == 2 ) {
                                /**
                                * see action.topic.php
                                */
                                $showTopicPartOnTop = true;
                                $listSize = ( isset($_SESSION['topic']['size']) ) ? $_SESSION['topic']['size'] : 10;
                                $sortmodes = array("1" => "zdp.created DESC", "2" => "zdp.created ASC");
                                if ( $sortmode >= 1 && $sortmode <= 2) $strSortmode = $sortmodes[$sortmode];
                                else $strSortmode = $sortmodes[1];
                                
                                $topic = $post->getTopic();
                                $topic->getPosts()->setOrder($strSortmode);
                                $topic->getPosts()->setShowTopicPart(!$showTopicPartOnTop);
                                $topicsCount = $topic->getPostsCount();
                                $currentPage = floor($topicsCount / $listSize) + 1;
                            } else {
                                $currentPage = 1;
                            }
                            $objResponse->addScript('popup_window.close()');
                            $url = $this->currentGroup->getGroupPath('topic').'topicid/'.$post->getTopicId().'/page/'.$currentPage.'/sortmode/'.$sortmode.'/'.$new_post->getId().'/#p'.$new_post->getPosition();
                            $objResponse->addRedirect($url) ;
    					} else {
                            $objResponse->addAssign('ErrorMessageMain', 'style.display', 'none');
                            $objResponse->addAssign('ErrorMessageMainTooLong', 'style.display', '');
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
