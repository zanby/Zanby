<?php
	Warecorp::addTranslation('/modules/adminarea/action.login.php.xml');
    if (!empty($this->params['password'])) $this->params['password'] = strtolower($this->params['password']);

    $form = new Warecorp_Form('loginForm', 'post', '/'.$this->_page->Locale.'/adminarea/login/');

    $form->addRule('login','required',Warecorp::t('Enter please Username'));
    $form->addRule('password','required',Warecorp::t('Enter please Password'));

    if ( $form->validate($this->params) ) {
		$admin = Zend_Registry::get("Admin");
		if ($admin->isAdmin($this->params['login'], $this->params['password'])) {
			$admin->login();	
	        // save LOG
    	    $this->appendLog('',0,'login'); 
			
			Zend_Registry::set("Admin", $admin);	
			$this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/adminarea/index/');
		} elseif($admin->getStatus()=='user') {
			$form->addCustomErrorMessage(Warecorp::t("Not administrator login"));
		} else {
			$form->addCustomErrorMessage(Warecorp::t("Not valid UserName or/and Password"));
		}
		
    } 
	$this->params['login'] = isset($this->params['login'])?$this->params['login']:'';
	$this->params['password'] = isset($this->params['password'])?$this->params['password']:'';
    $this->_page->setTitle(Warecorp::t('Log In'));
    $this->view->login = $this->params['login'];
    $this->view->password = $this->params['password'];
    $this->view->form = $form;    
    $this->view->setLayout('main_admin.tpl');
    $this->view->bodyContent = 'adminarea/login.tpl';
