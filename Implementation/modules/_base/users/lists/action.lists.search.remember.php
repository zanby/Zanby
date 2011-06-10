<?php

    if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
        $this->_redirect($this->currentUser->getUserPath('lists'));
    }

    //print_r($_SESSION['list_search']);
    $_url = $this->currentUser->getUserPath(null, false);
    $this->params['page'] = (!empty($this->params['page'])) ? (int)$this->params['page'] : 1;
    $_list = new Warecorp_List_Item();

    $listSearch = new Warecorp_List_Search();
    $listSearch->name = $this->params['search_name'];
    $listSearch->EntityTypeId = $_list->EntityTypeId;
    $listSearch->userId = $this->currentUser->getId();
    $listSearch->params = (isset($_SESSION['list_search'])) ? $_SESSION['list_search'] : array();
    $listSearch->save();

    $this->_redirect($_url.$listSearch->getPagerLink($this->params)."/page/{$this->params['page']}/");