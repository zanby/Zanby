<?php
    if ($this->currentGroup->getGroupType() != "family") {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }
    if (!Warecorp_Group_AccessManager::canViewMembers($this->currentGroup, $this->_page->_user)) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }
    
    Warecorp::addTranslation('/modules/groups/action.familymembers.all.php.xml');
    
    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");
    
    $items_per_page = 10;
    $user                   = $this->_page->_user;
    $order                  = isset($this->params['order']) ? $this->params['order'] : 'name';
    $direction              = (isset($this->params['order']) && isset($this->params['direction'])) ? $this->params['direction'] : 'asc';
    
    $membersList = new Warecorp_Group_Family_Members($this->currentGroup);
    $membersList->setGroupStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED);
    
    switch ($order) {
        case 'name'    :   $order_path = 'zua.login '.$direction;                  break;
        case 'laston'  :   $order_path = 'zua.last_access '.$direction;            break;
        default        :   $order_path = 'zua.login '.$direction;                  break;
    }
    $membersList->setOrder($order_path);
    
    $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;
    $membersList->setCurrentPage(intval($this->params['page']));
    $membersList->setListSize($items_per_page);
    
    $this->view->friends = $user->getId() ?  $user->getFriendsList()->returnAsAssoc()->getList() : array();
    $this->view->membersList = $membersList;
    $this->view->order = $order;
    $this->view->direction = $direction;
    $this->view->page = isset($this->params['page'])?$this->params['page']:null;
    
    $paging_link = "order/$order/direction/$direction";
    $this->view->paging_link = $paging_link;
    
    $url = $this->currentGroup->getGroupPath('familymembers.all').$paging_link;
    $P = new Warecorp_Common_PagingProduct($membersList->getCount(), $items_per_page, $url);
    $this->view->paging = $P->makePaging(intval(isset($this->params['page'])?$this->params['page']:null));
    
    $this->view->currentUser = $user;
    $this->view->currentGroup = $this->currentGroup;
    
    $this->view->bodyContent = 'groups/familymembers.all.tpl';
