<?php
    Warecorp::addTranslation('/modules/groups/xajax/action.showTransfer.php.xml');
    
    $this->view->visibility = true;
    
    $form = isset($form) ? $form : new Warecorp_Form('transferForm', 'POST', 'javasript:void(0);');        
    $this->view->form = $form;
    $Content = $this->view->getContents('groups/settings.transferaccount.tpl');
    
    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupSettingsTransfer_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsTransfer_Content", "innerHTML", $Content );
    
    return $objResponse;
