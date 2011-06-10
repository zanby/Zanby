<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.deletePostDo.php.xml');

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
                    $post->delete();
                    $objResponse->addScript('popup_window.close();');
                    $objResponse->addScript('document.location.reload();');
                } else {
                    $objResponse->addScript('popup_window.close();');
                    $objResponse->addAlert(Warecorp::t('You can not remove this post. Contact please host of group.'));
                }
            }
        } else {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('discussion'));
        }
    } else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('discussion'));
    }
