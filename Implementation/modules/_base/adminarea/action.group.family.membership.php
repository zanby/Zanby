<?php
    $this->_page->Xajax->registerUriFunction('groupAddToFamily', '/adminarea/groupAddToFamily');
    $this->_page->Xajax->registerUriFunction('groupRemoveFromFamily', '/adminarea/groupRemoveFromFamily');
    $this->_page->Xajax->registerUriFunction('groupRemoveFromFamilyDo', '/adminarea/groupRemoveFromFamilyDo');
    
    $this->view->groupID = $this->params['id'];
    $this->view->bodyContent = "adminarea/action.group.family.membership.tpl";
    
    $group = Warecorp_Group_Factory::loadById($this->params['id'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
    $familyList = $group->getFamilyGroups()->getList();
    $this->view->group = $group;
    $this->view->familyList = $familyList;
    
    $families = new Warecorp_Group_Family_List();
    $families->setOrder('zgi.name');
    $families->returnAsAssoc();
    $this->view->families = $families->getList();
    $this->view->form = new Warecorp_Form('addToFamilyForm');
