<?php
Warecorp::addTranslation('/modules/groups/xajax/action.useraddPrivileges.php.xml');
$objResponse = new xajaxResponse();

$form = new Warecorp_Form('gpForm', 'post', 'javasript:void(0);');
$privileges = $this->currentGroup->getPrivileges();

if (isset($params['_wf__gpForm'])) {
    $_REQUEST['_wf__gpForm'] = $params['_wf__gpForm'];
}
$login = 'text_'.$tool_type;
$values[$tool_type] = $params[$login];
if ($form->validate($params)) {
	$membersList = $this->currentGroup->getMembers();
	$user = new Warecorp_User('login', $params[$login]);

    if (!$user->getId()) {
        $params[$login] = "error";
        $form->addCustomErrorMessage(Warecorp::t('Sorry. Unrecognized username'));
        //$form->addRule('bu_login', 'numeric', 'Sorry. Unrecognized username');
        //$form->validate($params);
    } elseif ($privileges->getUsersListByTool($tool_type)->isExist($user)) {
        $params[$login] = "error";
        $form->addCustomErrorMessage(Warecorp::t('User already in List'));
        //$form->addRule('bu_login', 'numeric', 'User already blocked');
        //$form->validate($params);
    } elseif ($user->getId() == $this->_page->_user->getId()){
        $params[$login] = "error";
        $form->addCustomErrorMessage(Warecorp::t("Sorry. You can't add yourself"));
        //$form->addRule('bu_login', 'numeric', "Sorry. You can't block yourself");
        //$form->validate($params);
    } elseif (!$membersList->isMemberExistsAndApproved($user->getId())) {
    	$params[$login] = "error";
        $form->addCustomErrorMessage(Warecorp::t("User is not member of group"));
    } elseif ($membersList->isHost($user->getId()) || $membersList->isCohost($user->getId())) {
	    $params[$login] = "error";
	    $form->addCustomErrorMessage(Warecorp::t('User has host Privileges'));
	} else {
		$privileges->getUsersListByTool($tool_type)->add($user);
        $login = "";
        $values[$tool_type] = "";
    }
}

$this->view->privileges = $privileges;
$this->view->wf_form_object = $form;
$this->view->form = $form;
$this->view->tool = $tool_type;
$this->view->option = $params[$tool_type.'_access'];
$this->view->maxValue = $params[$tool_type.'_access'];
$this->view->values = $values;
$this->view->group = $this->currentGroup;

$output = $this->view->getContents('groups/users.tpl');
$objResponse->addClear($tool_type.'_access_td', "innerHTML");
$objResponse->addAssign($tool_type.'_access_td','innerHTML', $output);
$objResponse->addScript('var '.$tool_type.'_myAutoComp = new YAHOO.widget.AutoComplete("text_'.$tool_type.'", "'.$tool_type.'_acLogins", myDataSource);');


