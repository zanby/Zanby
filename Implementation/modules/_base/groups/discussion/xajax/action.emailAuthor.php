<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.emailAuthor.php.xml');

    $objResponse = new xajaxResponse();
    if ( floor($post_id) != 0 ) {
        $post = new Warecorp_DiscussionServer_Post($post_id);
        if ( null !== $post->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                //@todo надо добавить еще страницу, с которой пришел пользователь
                $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('topic').'topicid/'.$post->getTopicId().'/';
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                if ( !$post->getDiscussionAccessManager()->canEmailAuthorPost($post, $this->_page->_user->getId()) ) {
                    $objResponse->addAlert(Warecorp::t('You can not email author. Contact please host of group.'));
                } else {
                    $this->view->post = $post;
                    $this->view->discussion_mode = DISCUSSION_MODE;
                    $content = $this->view->getContents('groups/discussion/email.author.post.popup.tpl');

                    $popup_window = Warecorp_View_PopupWindow::getInstance();
                    $popup_window->title(Warecorp::t('Email Author'));
                    $popup_window->content($content);
                    $popup_window->width(DISCUSSION_MODE == 'html' ? 750 : 450)->height(DISCUSSION_MODE == 'html' ? 450 : 350)->open($objResponse);
                    
                    if ( DISCUSSION_MODE == 'html' ) $objResponse->addScript("initTinyMCE('');");
                }
            }
        }
    }

