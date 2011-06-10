<?php
Warecorp::addTranslation('/modules/groups/xajax/action.userdeletePrivileges.php.xml');

$objResponse = new xajaxResponse();
    
$form = new Warecorp_Form('gpForm', 'post', 'javasript:void(0);');
$privileges = $this->currentGroup->getPrivileges();
$privileges->getUsersListByTool($tool_type)->remove($userId);

$this->view->wf_form_object = $form;
$this->view->form = $form;
$this->view->privileges = $privileges;
$this->view->tool = $tool_type;
$this->view->option = $params[$tool_type.'_access'];
$this->view->maxValue = $params[$tool_type.'_access'];
$this->view->group = $this->currentGroup;

$output = $this->view->getContents('groups/users.tpl');
$objResponse->addClear($tool_type.'_access_td', "innerHTML");
$objResponse->addAssign($tool_type.'_access_td','innerHTML', $output);  
$objResponse->addScript('var '.$tool_type.'_myAutoComp = new YAHOO.widget.AutoComplete("text_'.$tool_type.'", "'.$tool_type.'_acLogins", myDataSource);');  
