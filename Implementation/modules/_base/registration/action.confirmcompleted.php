<?php

    Warecorp::addTranslation('/modules/registration/action.confirmcompleted.php.xml');
    
    if (!isset($_SESSION['_reg_user'])) $this->_redirect('/');
    
    $this->_page->setTitle(Warecorp::t('Confirmation Code Sended'));
    $this->view->userData          = $_SESSION['_reg_user'];
    $this->view->fromRegistration  = false;
    $this->view->bodyContent       = 'registration/completed.tpl';
    
    unset($_SESSION['_reg_user']);
