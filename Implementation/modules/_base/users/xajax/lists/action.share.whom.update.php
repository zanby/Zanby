<?php

    $objResponse = new xajaxResponse();
    
    $this->view->loadFromSession = 1;    
    $output = $this->view->getContents('users/lists/lists.share.whom.tpl');    
    $objResponse->addAssign('share_whom','innerHTML', $output);
    
    $objResponse->addScript("document.getElementById('shared_with').style.display='';");
