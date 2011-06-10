<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.reportPost.php.xml');

    $objResponse = new xajaxResponse();
    if ( floor($post_id) != 0 ) {
        $post = new Warecorp_DiscussionServer_Post($post_id);
        if ( null !== $post->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                //@todo надо добавить еще страницу, с которой пришел пользователь
                $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('topic').'topicid/'.$post->getTopicId().'/';
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                if ( !$post->getDiscussionAccessManager()->canReportPost($post, $this->_page->_user->getId()) ) {
                    $objResponse->addAlert(Warecorp::t('You can not report this post. Contact please host of group.'));
                } else {
                    $this->view->post = $post;
                    $content = $this->view->getContents('groups/discussion/report.post.popup.tpl');

                    $popup_window = Warecorp_View_PopupWindow::getInstance();
                    $popup_window->title(Warecorp::t('Report Post'));
                    $popup_window->content($content);
                    $popup_window->width(400)->height(100)->open($objResponse);
                }
            }
        }
    }

