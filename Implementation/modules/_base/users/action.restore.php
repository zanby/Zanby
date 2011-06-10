<?php

    Warecorp::addTranslation('/modules/users/action.restore.php.xml');

    if ( $this->_page->_user->isAuthenticated() ) {
        $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/');
    }

    $data = array();
    $user = false;

    $form = new Warecorp_Form('form_remind_username', 'POST');
    $form->addRule('username_or_email', 'required', Warecorp::t('Fill your email address or username'));

    if ( isset($this->params['username_or_email']) && strpos($this->params['username_or_email'], '@') ) {
        $form->addRule('username_or_email', 'email', Warecorp::t('The e-mail address %s incorrect, please enter correct one', $this->params['username_or_email']));
    }
	
    if (isset($this->params['username_or_email']) && $form->validate($this->params))
    {
        if ( strpos($this->params['username_or_email'], '@') ) {
            $user = new Warecorp_User('email', $this->params['username_or_email']);
            if (!$user->getId())
            {
                $form->addRule('error_todo', 'required', Warecorp::t('Sorry. The e-mail address %s is not recognized.', $this->params['username_or_email']));
                $form->validate($this->params);
            }
        }
        else {
            $user = new Warecorp_User('login', $this->params['username_or_email']);
            if (!$user->getId())
            {
                $form->addRule('error_todo', 'required', Warecorp::t('Sorry. The username %s is not recognized.', $this->params['username_or_email']));
                $form->validate($this->params);
            }
        }		
    }
    else
    {
        $data = $this->params;
    }

    $_template = 'users/restore.tpl';
    if ($user && $user->getId())
    {
        if (defined('USERS_LOG') && USERS_LOG) {
            Warecorp_Log_User::addEntry('password_restore',Warecorp_Log_User::SUCCESS,array('user_id'=>$user->getId()));
        }

    	if (USE_NEW_RESTORE_PASSWORD == 'on') {
            $user->restorePasswordByUrl();
    	} else {
    		$user->restorePassword();
    	}
        $_template = 'users/restore.confirm.tpl';
    }
    
    $this->view->data              = $data;
    $this->view->form_username     = $form;
    $this->view->bodyContent       = $_template;
