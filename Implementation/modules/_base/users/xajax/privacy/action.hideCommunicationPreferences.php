<?php

    $objResponse = new xajaxResponse();
    
    $privacy = $this->_page->_user->getPrivacy();

    $this->view->privacy = $privacy;
    $this->view->cp_view = 'collapsed';
    
    $output = $this->view->getContents('users/privacy.communicationPreferences.tpl');
    $objResponse->addClear("communicationPreferences_Content", "innerHTML");
    $objResponse->addAssign("communicationPreferences_Content",'innerHTML', $output);    
