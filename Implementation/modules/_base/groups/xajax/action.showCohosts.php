<?php
Warecorp::addTranslation('/modules/groups/xajax/action.showCosts.php.xml');

$objResponse = new xajaxResponse();
$this->view->visibility = true;
$form = new Warecorp_Form('chForm', 'POST', 'javasript:void(0);');
$cohosts    = $this->currentGroup->getMembers()
                                 ->setMembersRole(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST)
                                 ->returnAsAssoc()
                                 ->getList();
$users      = $this->currentGroup->getMembers()
                                 ->setMembersRole(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER)
                                 ->returnAsAssoc()
                                 ->getList();
$this->view->form = $form;
$this->view->cohosts = $cohosts;
$this->view->users = $users;
$this->view->group = $this->currentGroup;	

if($this->currentGroup->getGroupType() == "family") {
    $Content = $this->view->getContents('groups/settings.familycohosts.tpl');
} else {    
    $Content = $this->view->getContents('groups/settings.cohosts.tpl');
}
$objResponse->addClear( "GroupSettingsCoHost_Content", "innerHTML" );
$objResponse->addAssign( "GroupSettingsCoHost_Content", "innerHTML", $Content );
$objResponse->addScript('var cohost_myAutoComp = new YAHOO.widget.AutoComplete("cohost", "acCohost", myDataSource);');
