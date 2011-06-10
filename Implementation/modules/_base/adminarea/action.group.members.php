<?php
    $this->params['id'] = $this->getRequest()->getParam('id', null);
    $objGroup = Warecorp_Group_Factory::loadById( $this->params['id'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE );
    
    if ( isset($this->params['ajax_mode']) ) {
        $objResponse = new xajaxResponse();
        if ( !$objGroup || !$objGroup->getId() ) $objResponse->addREdirect(BASE_URL.'/'.LOCALE.'/adminarea/groups/');
        else {        
            switch ( $this->params['ajax_mode'] ) {
                case 'delete' :
                    if ( isset($this->params['members']) && trim($this->params['members']) ) {
                        $members = explode(',', $this->params['members']);
                        if ( sizeof($members) ) {
                            foreach ( $members as $memberID ) {
                                $objUser = new Warecorp_User('id', $memberID);
                                if ( $objUser && $objUser->getId() ) {
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
        }
        $objResponse->printXml($this->_page->Xajax->sEncoding); exit();
    }

    if ( !$objGroup || !$objGroup->getId() ) { $this->_redirect(BASE_URL.'/'.LOCALE.'/adminarea/groups/'); exit; }
    
    $this->params['order'] = isset($this->params['order']) ? $this->params['order'] : 'login';
    $this->params['direction'] = isset($this->params['direction']) ? $this->params['direction'] : 'asc';
    $this->params['page'] = isset($this->params['page']) ? $this->params['page'] : 1;
    $items_per_page = 10;
    
    $membersList = $objGroup->getMembers();
    $membersList->setMembersStatus( 'approved' );
    
    switch ( $this->params['order'] ) {
       case 'login'         :   $order_path = 'zua.login '.$this->params['direction'];                  break;
       case 'register_date' :   $order_path = 'zgm.creation_date '.$this->params['direction'];          break;
       case 'last_access'   :   $order_path = 'zua.last_access '.$this->params['direction'];            break;
       default              :   $order_path = 'zua.login '.$this->params['direction'];                  break;
    }

    $membersList->setOrder($order_path);
    $membersList->setMembersRole( array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST) );
    
    $membersList->setCurrentPage(intval($this->params['page']));
    $membersList->setListSize($items_per_page);
            
    $paging_link = '/order/'.$this->params['order'].'/direction/'.$this->params['direction'];
    
    $url = BASE_URL.'/'.LOCALE.'/adminarea/groupMembers/id/'.$this->params['id'].$paging_link;
    $P = new Warecorp_Common_PagingProduct($membersList->getCount(), $items_per_page, $url);
    $this->view->paging = $P->makePaging(intval($this->params['page']));
    
    //$membersList->addWhere("zua.login LIKE '".$query."%'");
    $members = $membersList->getList();

    $this->view->order = $this->params['order'];
    $this->view->direction = $this->params['direction'];
    $this->view->page = $this->params['page'];
    $this->view->paging_link = $paging_link;
    
    $this->view->membersList = $members;
    $this->view->objGroup = $objGroup;
	$this->view->groupID = $this->params['id'];
	$this->view->bodyContent = "adminarea/action.group.members.tpl";
?>