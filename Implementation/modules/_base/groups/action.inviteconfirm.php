<?php
Warecorp::addTranslation('/modules/groups/action.inviteconfirm.php.xml');

	if (empty($this->params['id'])) $this->_redirect($this->currentGroup->getGroupPath('summary'));
	$invitationSentList = $this->currentGroup->getInvitationList()->setFolder(Warecorp_Group_Invitation_eFolders::SENT);
	
	if (!$invitationSentList->getGroups()->isExist($this->params['id'])) $this->_redirect($this->currentGroup->getGroupPath('summary'));
	
	if (isset($this->params['yes']) || isset($this->params['no']))
		$invitationSentList->getGroups()->setDeclined($this->params['id']);
	
	if (isset($this->params['yes'])) $this->_redirect($this->currentGroup->getGroupPath('joinfamilystep0'));
	if (isset($this->params['no'])) $this->_redirect($this->currentGroup->getGroupPath('summary'));
	
	$invitedGroup = Warecorp_Group_Factory::loadById($this->params['id']);

	$this->view->group = $this->currentGroup;
	$this->view->bodyContent = 'groups/inviteconfirm.tpl';
	$this->view->invitedGroup = $invitedGroup;
	
