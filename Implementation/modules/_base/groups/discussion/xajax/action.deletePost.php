<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.deletePost.php.xml');

    $objResponse = new xajaxResponse();
    if ( floor($post_id) != 0 ) {
        $post = new Warecorp_DiscussionServer_Post($post_id);
        if ( null !== $post->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                //@todo надо добавить еще страницу, с которой пришел пользователь
                $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('topic').'topicid/'.$post->getTopicId().'/';
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                if ( $post->getDiscussionAccessManager()->canDeletePost($post, $this->_page->_user->getId()) ) {
                    $this->view->post = $post;
                    $content = $this->view->getContents('groups/discussion/delete.post.popup.tpl');

                    $popup_window = Warecorp_View_PopupWindow::getInstance();
                    $popup_window->title(Warecorp::t('Remove Post'));
                    $popup_window->content($content);
                    $popup_window->width(306)->height(90)->open($objResponse);

                } else {
                    $objResponse->addAlert(Warecorp::t('You can not remove this post. Contact please host of group.'));
                }
            }
        }
    }

