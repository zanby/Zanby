<?php

    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();

    if (!Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges($this->currentUser, $this->_page->_user)) {
        $this->_redirect($this->currentUser->getUserPath('calendar.list.view'));
    }
    
    if (isset($this->params['id'])) {
    	$eventSearch = new Warecorp_ICal_Search($this->params['id']);
    	$eventSearch->setUser($this->_page->_user);
        $eventSearch->delete();
    }
    
    $this->params['page'] = (!empty($this->params['page'])) ? (int)$this->params['page'] : 1;
    if (isset($this->params['preset']) && isset($this->params['preset_id'])) {
    	$redirect_url = $this->currentUser->getUserPath('calendarsearch')."preset/".$this->params['preset']."/id/".$this->params['preset_id']."/";
    } else {
    	$redirect_url = $this->currentUser->getUserPath('calendarsearch', false);
    	if (isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], 'calendarsearchindex')) {
    		$redirect_url = $this->currentUser->getUserPath('calendarsearchindex', false);
    	}
    	$redirect_url = $redirect_url.(Warecorp_ICal_Search::getPagerLink($this->params))."/page/".$this->params['page']."/";
    }
    
    $this->_redirect($redirect_url);
