<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.emailAuthorDo.php.xml');

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
                    if ( trim($content) != '' ) {
                        $post->sendMessageToAuthor( $this->_page->_user, $content );
        			    
                        $objResponse->addScript('popup_window.close();');
        			    $objResponse->showAjaxAlert(Warecorp::t('Message sent'));
                    } else {
                        $objResponse->addAssign('ErrorMessageMain', 'style.display', '');
                    }
                }                    
            }
        } else {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('discussion'));
        }
    } else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('discussion'));
    }

