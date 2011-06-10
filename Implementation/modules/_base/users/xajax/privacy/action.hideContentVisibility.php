<?php

    $objResponse = new xajaxResponse();

    $privacy = $this->_page->_user->getPrivacy();

    $this->view->privacy = $privacy;
    $this->view->cv_view = 'collapsed';

    $output = $this->view->getContents('users/privacy.contentVisibility.tpl');
    $objResponse->addClear("contentVisibility_Content", "innerHTML");
    $objResponse->addAssign("contentVisibility_Content",'innerHTML', $output);
