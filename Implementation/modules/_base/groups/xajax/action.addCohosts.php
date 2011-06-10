<?php
Warecorp::addTranslation('/modules/groups/xajax/action.addCohosts.php.xml');

$this->view->visibility = true;
$form = new Warecorp_Form('chForm', 'POST', 'javascript:void()');
$objResponse = new xajaxResponse();

$membersList = $this->currentGroup->getMembers();
if (Warecorp_User::isUserExists('login', $Id)) {
	$user = new Warecorp_User('login', $Id);
	if ($membersList->isMemberExistsAndApproved($user->getId()) && !$membersList->isHost($user->getId())
	    && !$membersList->isCohost($user->getId()))  $this->currentGroup->getMembers()->setAsCohost($user);
	else { 
		 $form->addCustomErrorMessage(Warecorp::t('User is not plain member of this group'));
		 $this->view->Login = $Id;
	}
} else {
	$form->addCustomErrorMessage(Warecorp::t('Please enter valid username'));
	$this->view->Login = $Id;
}
$cohosts    = $membersList
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
