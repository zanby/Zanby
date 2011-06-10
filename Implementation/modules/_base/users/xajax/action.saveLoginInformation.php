<?php
    Warecorp::addTranslation("/modules/users/xajax/action.saveLoginInformation.php.xml");

    if ( $this->currentUser->getId() !== $this->_page->_user->getId() ) {
        $this->_redirect($this->currentUser->getUserPath('profile'));
    }
    $objResponse = new xajaxResponse();

    $this->_page->setTitle(Warecorp::t('Accounts settings'));
    $form = new Warecorp_Form('liForm', 'post', 'javasript:void(0);');

     if (isset($params['_wf__liForm'])) $_REQUEST['_wf__liForm'] = $params['_wf__liForm'];

    $redirect       = false;
    $change_pass    = false;
    if (!empty($params['old_pass'])) $params['old_pass'] = strtolower($params['old_pass']);
    $userParams = isset($params['old_pass'])
                  ? array('name'   => $this->currentUser->getLogin(),
                          'pass'   => $params['old_pass'],
                          'status' => Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE)
                  : '';

    $loginParams = array('login'=>$params['login'], 'excludeIds' => array($this->currentUser->getId()));
    $emailParams = array('email'=>$params['email'], 'excludeIds' => array($this->currentUser->getId()));

    $params['old_pass'] = isset($params['old_pass'])?strtolower($params['old_pass']):null;
    $params['new_pass'] = isset($params['new_pass'])?strtolower($params['new_pass']):null;  
    $params['new_pass_confirm'] = isset($params['new_pass_confirm'])?strtolower($params['new_pass_confirm']):null;

    $form->addRule('login',        'required',  Warecorp::t('Enter please User ID'));
    $form->addRule('login',        'callback',  Warecorp::t('User ID (login) already exist'), array('func' => 'Warecorp_Form_Validation::isNewLoginExist', 'params' => $loginParams));
    $form->addRule('login',        'maxlength', Warecorp::t('Login Name too long (max %s)', 50), array('max' => 50));
    $form->addRule('login',        'alphanumeric',  Warecorp::t('Use a-z 0-9 for username'));
    
    if (!(empty($params['old_pass']) && empty($params['new_pass']) && empty($params['new_pass_confirm']))) {
        $form->addRule('old_pass',         'callback',  Warecorp::t('Incorrect current password'), array('func' => 'Warecorp_Form_Validation::isUserExist', 'params' => $userParams));
        $form->addRule('old_pass',         'required',  Warecorp::t('Enter please Current Password'));
        $form->addRule('new_pass', 		   'required',  Warecorp::t('Enter please New Password'));
        $form->addRule('new_pass',         'minlength', Warecorp::t('Minimum password length is %s characters', "six"), array('min' => 6));
        $form->addRule('new_pass',         'maxlength', Warecorp::t('New Password too long (max %s)', 50), array('max' => 50));
        $form->addRule('new_pass_confirm', 'required',  Warecorp::t('Enter please New Password Confirmation'));
        $form->addRule('new_pass_confirm', 'compare',   Warecorp::t('Confirm Password not equal Password'), array('rule' => '==', 'value' => isset($params['new_pass'])?$params['new_pass']:''));
        $change_pass = true;
    }
    $form->addRule('email',        'required',  Warecorp::t('Enter please Email Address'));
    $form->addRule('email',        'email',     Warecorp::t('Enter please correct Email Address'));
    $form->addRule('email',        'callback',  Warecorp::t('Email address already exist'), array('func' => 'Warecorp_Form_Validation::isNewUserEmailExist', 'params' => $emailParams));
    $form->addRule('email',        'maxlength', Warecorp::t('Email too long (max %s)', 255), array('max' => 255));
    
    if ($form->validate($params)) {
        if ( !empty($params['email_confirm']) )
            $form->addRule('email_confirm',        'compare',   Warecorp::t('Confirm Email not equal Email'), array('rule' => '==', 'value' => isset($params['email'])?$params['email']:''));
        $form->addRule('email_confirm','required',  Warecorp::t('Enter please Email Address Confirmation'));
    }

    $user = new Warecorp_User('id', $this->currentUser->getId());
    if ($user->getLogin() != trim($params['login'])) $redirect = true; else $redirect = false;
    $user->setLogin(trim($params['login']));
    $user->setEmail($params['email']);
    $b = false;
    
    if ($form->validate($params)) {
        /**
         * we cant update login field in user object, created as new User('login', 'blah').
         * so create user object by UserID
         */
        if ($change_pass) {
            $user->setPass(md5(strtolower($params['new_pass'])));
            //$redirect = true;
        }

        #$user->calendarPrivacy   = $params['calendar'];

        /*
        if (!isset($params['contact'])) $params['contact'] = "all";
        $user->contactMode       = ($params['contact'] == 'set') ?
        (0 + (isset($params['cb1']) ? 1 : 0)
        + (isset($params['cb2']) ? 2 : 0)
        + (isset($params['cb3']) ? 4 : 0)
        + (isset($params['cb4']) ? 8 : 0)) : 16;
        */

        $user->save();
        if (isset($_COOKIE["zanby_username"]) && isset($_COOKIE["zanby_password"])){
            setcookie("zanby_username", $user->getLogin(), time()+2592000, "/",'.'.BASE_HTTP_HOST); //
            setcookie("zanby_password", md5($user->getPass()), time()+2592000, "/", '.'.BASE_HTTP_HOST);    //  2592000 = 60*60*24*30
        }
        $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));
        /**
         * Hide AppAccountLoginInformation TitlePane
         */
        $objResponse->addScript('TitltPaneAppAccountLoginInformation.hide();');
    } else {
       $this->view->visibility = true;
       $redirect = false;
    }

    if ( $redirect === true ) {
          $newuser = new Warecorp_User('id', $this->currentUser->getId());
          $path = $newuser->getUserPath('settings');
          $objResponse->addRedirect($path);
    }

    $this->view->edituser = $user;
    $this->view->email_confirm = $params['email_confirm'];
    if ($change_pass) {
        $this->view->old_pass = $params['old_pass'];
        $this->view->new_pass = $params['new_pass'];
        $this->view->new_pass_confirm = $params['new_pass_confirm'];
    }
    $this->view->form = $form;
    $Content = $this->view->getContents('users/settings.loginInformation.tpl');

    $objResponse->addClear( "AccountLoginInformation_Content", "innerHTML" );
    $objResponse->addAssign( "AccountLoginInformation_Content", "innerHTML", $Content );
