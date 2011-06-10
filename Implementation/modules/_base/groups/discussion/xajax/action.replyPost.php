<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.replyPost.php.xml');

	$objResponse = new xajaxResponse();
	if ( floor($post_id) != 0 ) {
		$post = new Warecorp_DiscussionServer_Post($post_id);
		if ( null !== $post->getId() ) {
			if ( !$this->_page->_user->isAuthenticated() ) {
				//@todo надо добавить еще страницу, с которой пришел пользователь
				$_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('topic').'topicid/'.$post->getTopicId().'/';
				$objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
			} else {
                if ( $post->getDiscussionAccessManager()->canReplyPost($post, $this->_page->_user->getId()) ) {
    				$subscription = Warecorp_DiscussionServer_TopicSubscription::findByTopicAndUserId($post->getTopicId(), $this->_page->_user->getId());

    				$currentPage = ( floor($currentPage < 1) ) ? 1 : floor($currentPage);
    				$sortmode = ( floor($sortmode) == 1 || floor($sortmode) == 2 ) ? floor($sortmode) : 2;
    				
    				$this->view->post = $post;
    				$this->view->currentPage = $currentPage;
    				$this->view->sortmode = $sortmode;
    				$this->view->subscription = $subscription;
    				$this->view->subscribeContentOptions = Warecorp_DiscussionServer_Enum_SubscriptionType::getAsOptions();
    				$this->view->discussion_mode = DISCUSSION_MODE;
    				$content = $this->view->getContents('groups/discussion/reply.post.popup.tpl');
                    
                    $popup_window = Warecorp_View_PopupWindow::getInstance();
                    $popup_window->title(Warecorp::t('Post Reply'));
                    $popup_window->content($content);                    
                    $popup_window->width(DISCUSSION_MODE == 'html' ? 750 : 450)->height(DISCUSSION_MODE == 'html' ? 450 : 350)->open($objResponse);
    				
    				if ( DISCUSSION_MODE == 'html' ) {
    				    $content = Warecorp_DiscussionServer_Post::formatQuoteStart().
    				               str_replace("'", "\'", $post->getContent()).
    				               Warecorp_DiscussionServer_Post::formatQuoteEnd()."<br/>";
    				    $content = str_replace("\n", "", $content);
    				    $content = str_replace("\r", "", $content);
    				    //print $content;exit;
    				    $objResponse->addScript("initTinyMCE('".$content."');");
    				} else {
    				    $objResponse->addAssign('content', 'value', "[QUOTE]".$post->getContent()."[/QUOTE]\n\n");
    				}
                } else {
                    $objResponse->addAlert(Warecorp::t('You can not reply this post. Contact please host of group.'));
                }
			}
		}
	}

