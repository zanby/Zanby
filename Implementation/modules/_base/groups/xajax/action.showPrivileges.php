<?php    
Warecorp::addTranslation('/modules/groups/xajax/action.showPrivileges.php.xml');
    
$objResponse = new xajaxResponse();
$form = new Warecorp_Form('gpForm', 'POST', 'javasript:void(0);');    

$privileges = $this->currentGroup->getPrivileges();

$this->view->visibility    =  true;
$this->view->privileges    =  $privileges;
$this->view->group         =  $this->currentGroup;
$this->view->form          =  $form;

$templatePrefix = ( $this->currentGroup->getGroupType() == 'simple' ) ? '' : $this->currentGroup->getGroupType();
$Content = $this->view->getContents('groups/settings.'.$templatePrefix.'privileges.tpl');

$objResponse->addClear( "GroupSettingsPrivilegies_Content", "innerHTML" );
$objResponse->addAssign( "GroupSettingsPrivilegies_Content", "innerHTML", $Content );   
$objResponse->addScript('setAutoComplete();'); 
