<?php

    $objResponse = new xajaxResponse();


    $form = new Warecorp_Form('srForm', 'post', 'javascript:void(0);');
    $privacy = $this->_page->_user->getPrivacy();

    $this->view->privacy = $privacy;
    $this->view->form = $form;
    $this->view->sr_view = 'expanded';

    $output = $this->view->getContents('users/privacy.searchResultSettings.tpl');
    $objResponse->addClear("searchResultSettings_Content", "innerHTML");
    $objResponse->addAssign("searchResultSettings_Content",'innerHTML', $output);
    $objResponse->addScriptCall("innerHTMLScript","sr");
