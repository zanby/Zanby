<?php

    $objResponse = new xajaxResponse();

    $privacy = $this->_page->_user->getPrivacy();

    $this->view->privacy = $privacy;
    $this->view->sr_view = 'collapsed';

    $output = $this->view->getContents('users/privacy.searchResultSettings.tpl');
    $objResponse->addClear("searchResultSettings_Content", "innerHTML");
    $objResponse->addAssign("searchResultSettings_Content",'innerHTML', $output);
