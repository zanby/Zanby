<?php

    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();

    if (!Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges($this->currentUser, $this->_page->_user)) {
        $this->_redirect($this->currentUser->getUserPath('calendar.list.view'));
    }

    $_url = $this->currentUser->getUserPath('calendarsearch', false);
    $this->params['page'] = (!empty($this->params['page'])) ? (int)$this->params['page'] : 1;
    $_tagList = new Warecorp_ICal_Event_List_Tag();

    $eventSearch = new Warecorp_ICal_Search();
    $eventSearch->setUser($this->_page->_user);
    $eventSearch->name = $this->params['search_name'];
    $eventSearch->EntityTypeId = $_tagList->EntityTypeId;
    $eventSearch->userId = $this->currentUser->getId();
    $eventSearch->params = (isset($_SESSION['event_search'])) ? $_SESSION['event_search'] : array();
    $eventSearch->save();

    $this->_redirect($_url.$eventSearch->getPagerLink($this->params)."/page/{$this->params['page']}/");
