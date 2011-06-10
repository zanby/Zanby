<?php

Warecorp::addTranslation('/modules/registration/action.sentforapprove.php.xml');
    
if ( !isset($_SESSION['_reg_user']) ) $this->_redirect('/');

$user = new Warecorp_User('login', $_SESSION['_reg_user']['login']);
   
$this->_page->setTitle(Warecorp::t('Registration details for approval sent'));
$this->view->currentUser		=	$user;
$this->view->fromRegistration  = true;
$this->view->bodyContent       = 'registration/sentforapprove.tpl';
   
unset($_SESSION['_reg_user']);
