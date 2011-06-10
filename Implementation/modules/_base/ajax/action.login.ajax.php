<?php
    Warecorp::addTranslation("/modules/users/action.login.ajax.php.xml");
    $objResponse = new xajaxResponse();

    if (!empty($this->params['password'])) $this->params['password'] = strtolower($this->params['password']);
    $userParams = isset($this->params['login']) && isset($this->params['password'])
                  ? array('name'   => $this->params['login'],
                          'pass'   => $this->params['password'],
                          'status' => Warecorp_User_Enum_UserStatus::USER_STATUS_PENDING)
                  : '';

    $form = new Warecorp_Form('loginForm', 'post', '/'.$this->_page->Locale.'/users/login/');
    $form->addRule('login',     'required',     Warecorp::t('Username is required'));
    $form->addRule('password',  'required',     Warecorp::t('Password is required'));
    if (!empty($userParams) && !Warecorp_Form_Validation::isUserExist($userParams)) {
        $form->addCustomErrorMessage(Warecorp::t('Account exists but not activated. Please activate account'));
    } else {
        if (!empty($userParams)) $userParams['status'] = Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE;
        $form->addRule('password', 'callback', Warecorp::t('Incorrect username or password'), array('func' => 'Warecorp_Form_Validation::isUserExist', 'params' => $userParams));
    }
    $_REQUEST['_wf__loginForm'] = 1;

    if ( $form->validate($this->params) ) {
        $form_data = $this->params;
        $user = Zend_Registry::get("User");
        $user = new Warecorp_User('login', $form_data['login']);
        if (isset($this->params["rememberme"])){
            setcookie("zanby_username", $user->getLogin(), time()+2592000, "/",'.'.BASE_HTTP_HOST); //  2592000 = 60*60*24*30
            setcookie("zanby_password", md5($user->getPass()), time()+2592000, "/",'.'.BASE_HTTP_HOST);
        }
        $user->authenticate();

        if (defined('USERS_LOG') && USERS_LOG) {
            Warecorp_Log_User::addEntry('login',Warecorp_Log_User::SUCCESS,array('user_id'=>$user->getId()));
        }

        //  Wordpress SSO script to login user to wordpress
        if ( $script = Warecorp_Wordpress_SSO::getJsResponse( 'document.location.reload();' ) ) {
            $objResponse->addScript( $script );
        } else {
            $objResponse->addScript('document.location.reload();');
        }
    } else {
        if (defined('USERS_LOG') && USERS_LOG) {
            $user = new Warecorp_User('login', $this->params['login']);
            if ($user->getId()) {
                Warecorp_Log_User::addEntry('login',Warecorp_Log_User::FAILURE,array('user_id'=>$user->getId(),'message'=>  implode(', ', $form->getErrorMessages())));
            }
        }

        $objResponse->addAssign('err_login', 'innerHTML', '');
        $objResponse->addAssign('err_password', 'innerHTML', '');
        $output_errors = $form->getErrorMessages(null, true);
        $custom = $form->getCustomErrorMessages();
        if ( !empty($output_errors['login']) || !empty($output_errors['password']) ) {
            if ( !empty($output_errors['login']) ) $objResponse->addAssign('err_login', 'innerHTML', join('', $output_errors['login']));
            if ( !empty($output_errors['password']) ) $objResponse->addAssign('err_password', 'innerHTML', join('', $output_errors['password']));
        } elseif ( !empty($custom) ) {
            if ( empty($output_errors['password']) ) $objResponse->addAssign('err_password', 'innerHTML', join('', $custom));
        }
    }
