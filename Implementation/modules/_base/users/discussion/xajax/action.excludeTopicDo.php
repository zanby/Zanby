<?php
    Warecorp::addTranslation("/modules/users/discussion/xajax/action.excludeTopicDo.php.xml");
    $objResponse = new xajaxResponse();

    if ( floor($topic_id) != 0 ) {
        $topic = new Warecorp_DiscussionServer_Topic($topic_id);
        if ( null !== $topic->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                $_SESSION['login_return_page'] = $this->currentUser->getUserPath('discussion/mode/commented');
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
            	if ( $this->_page->_user->getId() == $this->currentUser->getId() ) {
            		Warecorp_DiscussionServer_TopicList::addExcludedTopicIdByUser($this->currentUser->getId(), $topic_id);
	            	$objResponse->addScript('YAHOO.util.Dom.get("tr_Topic_Content'.$topic_id.'").style.display = "none";');
	            	$objResponse->showAjaxAlert(Warecorp::t('Removed'));
            	}
            }
        }
    }



