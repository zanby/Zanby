<?php

    $objResponse = new xajaxResponse();
    
    $privacy = $this->_page->_user->getPrivacy();

    $this->view->privacy = $privacy;
    $this->view->bu_view = 'collapsed';
    
    $output = $this->view->getContents('users/privacy.blockUsers.tpl');
    $objResponse->addClear("blockUsers_Content", "innerHTML");
    $objResponse->addAssign("blockUsers_Content",'innerHTML', $output);    
