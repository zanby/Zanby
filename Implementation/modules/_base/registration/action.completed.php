<?php

    Warecorp::addTranslation('/modules/registration/action.completed.php.xml');
    
    if ( !isset($_SESSION['_reg_user']) ) $this->_redirect('/');
    
    if ( 'facebook' == $this->getRequest()->getParam('mode', null) ) {
        $this->view->setLayout('main_wide.tpl');
    } else {
    
    }
    
    $this->_page->setTitle(Warecorp::t('Confirmation code sent'));
    $this->view->userData          = $_SESSION['_reg_user'];
    $this->view->fromRegistration  = true;
    $this->view->bodyContent       = 'registration/completed.tpl';
    $this->view->mode              = $this->getRequest()->getParam('mode', null);
    
    unset($_SESSION['_reg_user']);
