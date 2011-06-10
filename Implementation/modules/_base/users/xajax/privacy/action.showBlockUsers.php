<?php

    $objResponse = new xajaxResponse();


    $form = new Warecorp_Form('buForm', 'post', 'javascript:void(0);');
    $privacy = $this->_page->_user->getPrivacy();

    $this->view->privacy = $privacy;
    $this->view->form = $form;
    $this->view->bu_view = 'expanded';

    $output = $this->view->getContents('users/privacy.blockUsers.tpl');
    $objResponse->addClear("blockUsers_Content", "innerHTML");
    $objResponse->addAssign("blockUsers_Content",'innerHTML', $output);

    $objResponse->addScript('var myAutoComp = new YAHOO.widget.AutoComplete("buLogin", "acLogins", myDataSource);');
