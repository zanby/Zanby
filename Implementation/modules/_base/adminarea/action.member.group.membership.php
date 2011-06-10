<?php
    $this->params['id']= $this->getRequest()->getParam('id', null);
    $objUser = new Warecorp_User('id', $this->params['id']);
    
    if ( !$objUser || !$objUser->getId() ) { $this->_redirect(BASE_URL.'/'.LOCALE.'/adminarea/members'); exit; }
    
    if ( isset($this->params['ajax_mode']) ) {
        $objResponse = new xajaxResponse();
        switch ( $this->params['ajax_mode'] ) {
            case 'delete' :
                if ( isset($this->params['groups']) && trim($this->params['groups']) ) {
                    $groups = explode(',', $this->params['groups']);
                    if ( sizeof($groups) ) {
                        foreach ( $groups as $groupID ) {
                            $objGroup = Warecorp_Group_Factory::loadById($groupID,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                            if ( $objGroup && $objGroup->getId() ) {
                                $objGroup->getMembers()->removeMember($objUser->getId());
                            }
                        }
                    }
                }
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->close($objResponse);
                $objResponse->addScript('document.location.reload();');                
                break;
        }
        $objResponse->printXml($this->_page->Xajax->sEncoding); exit();
    }
        
    $this->params['order'] = isset($this->params['order']) ? $this->params['order'] : 'name';
    $this->params['direction'] = isset($this->params['direction']) ? $this->params['direction'] : 'asc';
    $this->params['page'] = isset($this->params['page']) ? $this->params['page'] : 1;
    $items_per_page = 10;
    
    $groupsList = $objUser->getGroups()->setTypes('simple');

    /**
     * set order
     */
    switch ( $this->params['order'] ) {
        case 'name'             : $order_path = 'zgi.name '.$this->params['direction']; break;
        case 'creation_date'    : $order_path = 'zgi.creation_date '.$this->params['direction']; break;
        default                 : $order_path = 'zgi.name '.$this->params['direction']; break;
    }
    $groupsList->setOrder($order_path);
    
    $groupsList->setCurrentPage(intval($this->params['page']));
    $groupsList->setListSize($items_per_page);
    
    $paging_link = '/order/'.$this->params['order'].'/direction/'.$this->params['direction'];
    $url = BASE_URL.'/'.LOCALE.'/adminarea/memberGroupMembership/id/'.$this->params['id'].$paging_link;
    $P = new Warecorp_Common_PagingProduct($groupsList->getCount(), $items_per_page, $url);
    $this->view->paging = $P->makePaging(intval($this->params['page']));    

    $arrGroups = $groupsList->getList();
    
    $this->view->order = $this->params['order'];
    $this->view->direction = $this->params['direction'];
    $this->view->page = $this->params['page'];
    
    $this->view->groupsList = $arrGroups;
	$this->view->objUser = $objUser;
    $this->view->memberID = $this->params['id'];
	$this->view->bodyContent = "adminarea/action.member.group.membership.tpl";
?>