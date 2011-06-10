<?php

    $objResponse = new xajaxResponse();

    $form = new Warecorp_Form('cvForm', 'post', 'javascript:void(0);');
    $privacy = $this->_page->_user->getPrivacy();

    $this->view->privacy = $privacy;
    $this->view->form = $form;
    $this->view->cv_view = 'expanded';
    $this->view->cvSelectOptions = Warecorp_User_Privacy_Enum_PublicMeans::getPublicMeansAssoc();
    if ($privacy->getCvAnyone()) {
        $this->view->cvRadio = 2;
    } elseif ($privacy->getCvAnyMembers()) {
        $this->view->cvRadio = 1;
    }

    $output = $this->view->getContents('users/privacy.contentVisibility.tpl');
    $objResponse->addClear("contentVisibility_Content", "innerHTML");
    $objResponse->addAssign("contentVisibility_Content",'innerHTML', $output);
    $objResponse->addScriptCall("innerHTMLScript", "cv");
