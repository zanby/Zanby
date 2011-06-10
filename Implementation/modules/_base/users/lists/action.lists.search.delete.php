<?php

    if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
        $this->_redirect($this->currentUser->getUserPath('lists'));
    }

    
    if (isset($this->params['id'])) {
        $listSearch = new Warecorp_List_Search($this->params['id']);
        $listSearch->delete();
    }
    
    $this->params['page'] = (!empty($this->params['page'])) ? (int)$this->params['page'] : 1;
    if (isset($this->params['preset']) && isset($this->params['preset_id'])) {
    	$redirect_url = $this->currentUser->getUserPath('listssearch')."preset/".$this->params['preset']."/id/".$this->params['preset_id']."/";
    } else {
    	$redirect_url = $this->currentUser->getUserPath(null, false).(Warecorp_List_Search::getPagerLink($this->params))."/page/".$this->params['page']."/";
    }
    
    $this->_redirect($redirect_url);