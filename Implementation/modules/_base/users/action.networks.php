<?php
	
    if ( !FACEBOOK_USED ) $this->_redirect($this->currentUser->getUserPath('profile'));
    if ( $this->currentUser->getId() !== $this->_page->_user->getId() ) $this->_redirect($this->currentUser->getUserPath('profile'));
    
    $facebookId = null;
    $facebookId = Warecorp_Facebook_Api::getFacebookId();

    $fbDisplayMode = null;
    $facebookUser = null;
    $facebookUserInfo = null;
    /* Z account is connected */
    if ( Warecorp_Facebook_User::isZAccountConnected($this->_page->_user->getId()) ) {
        /* there is fb session */
        if ( !empty($facebookId) ) {
            $fbDisplayMode = 'linkedWithFbSession';
            $facebookUser = Warecorp_Facebook_User::loadByUserId($this->_page->_user->getId());
            $facebookUserInfo = Warecorp_Facebook_User::getInfo($facebookUser->getFacebookId());
            if ( $facebookId != $facebookUser->getFacebookId() ) {
                /**
                 * пользователь залогинен и его аккаунт связан, но используется не его facebookID
                 * 1) данный facebookID может использоваться другим аккаунтом
                 * 2) данный facebookID не используется другим аккаунтом
                 */
                if ( null !== $facebookUser = new Warecorp_Facebook_User($facebookId) ) {
                } else {
                }
                $facebookUser->setUserId($this->_page->_user->getId());
                $facebookUser->save();
                $facebookUserInfo = Warecorp_Facebook_User::getInfo($facebookId);                                    
            }
            $this->view->canPublishStream = $facebookUser->canPublishStream();
            $this->view->canEmail = $facebookUser->canEmail();
        } 
        /* there isn't fb session */
        else {
            $fbDisplayMode = 'linkedWithoutFbSession';
            $facebookUser = Warecorp_Facebook_User::loadByUserId($this->_page->_user->getId());
            $facebookUserInfo = Warecorp_Facebook_User::getInfo($facebookUser->getFacebookId());
        }        
    } 
    /* Z account isn't connected */
    else {
        /* there is fb session */
        if ( !empty($facebookId) ) {
            $fbDisplayMode = 'unlinkedWithFbSession';
            $facebookUser = new Warecorp_Facebook_User($facebookId);
            $facebookUserInfo = Warecorp_Facebook_User::getInfo($facebookId);
        } 
        /* there isn't fb session */
        else {
            $fbDisplayMode = 'unlinkedWithoutFbSession';
        }        
    }
    
    $this->view->facebookUser = $facebookUser;
    $this->view->facebookUserInfo = $facebookUserInfo;
    $this->view->fbDisplayMode = $fbDisplayMode;
    $this->view->bodyContent = 'users/networks.tpl';
    $this->view->Warecorp = new Warecorp();
