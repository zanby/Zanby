<?php

    $objResponse = new xajaxResponse();

    $form = new Warecorp_Form('cpForm', 'post', 'javascript:void(0);');
    $privacy = $this->_page->_user->getPrivacy();

    $this->view->privacy = $privacy;
    $this->view->form = $form;
    $this->view->cp_view = 'expanded';

    $output = $this->view->getContents('users/privacy.communicationPreferences.tpl');
    $objResponse->addClear("communicationPreferences_Content", "innerHTML");    
    $objResponse->addAssign("communicationPreferences_Content",'innerHTML', $output);
    $objResponse->addScriptCall("innerHTMLScript", "cp");
