<?php
    Warecorp::addTranslation("/modules/users/discussion/xajax/action.excludeTopic.php.xml");
    $objResponse = new xajaxResponse();

    if ( floor($topic_id) != 0 ) {
        $topic = new Warecorp_DiscussionServer_Topic($topic_id);
        if ( null !== $topic->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                $_SESSION['login_return_page'] = $this->currentUser->getUserPath('discussion/mode/commented');
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                if ( $this->_page->_user->getId() == $this->currentUser->getId() ) {
	                $this->view->topic = $topic;
	                $content = $this->view->getContents('users/discussion/exclude.topic.popup.tpl');
                    
                    $popup_window = Warecorp_View_PopupWindow::getInstance();
                    $popup_window->title(Warecorp::t('Remove Topic'));
                    $popup_window->content($content);
                    $popup_window->width(450)->height(350)->open($objResponse);
                }
            }
        }
    }
