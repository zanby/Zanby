<?php

    $this->params['id'] = $this->getRequest()->getParam('id', null);
    $objGroup = Warecorp_Group_Factory::loadById( $this->params['id'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY );

    if ( !$objGroup || !$objGroup->getId() ) { $this->_redirect(BASE_URL.'/'.LOCALE.'/adminarea/groups/'); exit; }
    if ( $objGroup->getGroupType() != Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY ) { $this->_redirect(BASE_URL.'/'.LOCALE.'/adminarea/groupMembers/id/'.$objGroup->getId().'/'); exit; }

    if ( isset($this->params['ajax_mode']) ) {
        $objResponse = new xajaxResponse();
        switch ( $this->params['ajax_mode'] ) {
            /**
             * Detach Group from family : Family Details View
             */
            case 'delete' :
                if ( isset($this->params['members']) && trim($this->params['members']) ) {
                    $groups = explode(',', $this->params['members']);
                    if ( sizeof($groups) ) {
                        foreach ( $groups as $groupID ) {                            
                            $objSubGroup = Warecorp_Group_Factory::loadById($groupID,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                            if ( $objSubGroup && $objSubGroup->getId() ) {
                                $objGroup->getGroups()->removeGroup($objSubGroup->getId());
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
    
    $membersList = $objGroup->getGroups()->setTypes(array('simple', 'family'));
    $membersList->setStatus('active');    
    /**
     * set order
     */
    switch ( $this->params['order'] ) {
        case 'name'             : $order_path = 'zgi.name '.$this->params['direction']; break;
        case 'creation_date'    : $order_path = 'zgi.creation_date '.$this->params['direction']; break;
        default                 : $order_path = 'zgi.name '.$this->params['direction']; break;
    }
    $membersList->setOrder($order_path);
    
    $membersList->setCurrentPage(intval($this->params['page']));
    $membersList->setListSize($items_per_page);

    $paging_link = '/order/'.$this->params['order'].'/direction/'.$this->params['direction'];
    
    $url = BASE_URL.'/'.LOCALE.'/adminarea/familyMembers/id/'.$this->params['id'].$paging_link;
    $P = new Warecorp_Common_PagingProduct($membersList->getCount(), $items_per_page, $url);
    $this->view->paging = $P->makePaging(intval($this->params['page']));
    
    $members = $membersList->getList();
    
    $this->view->order = $this->params['order'];
    $this->view->direction = $this->params['direction'];
    $this->view->page = $this->params['page'];
    $this->view->paging_link = $paging_link;
    
    $this->view->groupsList = $members;
    $this->view->objGroup = $objGroup;    
	$this->view->groupID = $this->params['id'];
	$this->view->bodyContent = "adminarea/action.family.members.tpl";
?>