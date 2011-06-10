<?php
    /**
     * ZCCF Wordpres SignIn/SignUp process
     * @author Artem Sukharev
     */
    $wploginMode = (boolean)$this->getRequest()->getParam('wpl', false);
    $wpprofileMode = (boolean)$this->getRequest()->getParam('wpp', false);

    // Wordpress signin clicked
    $allowUseWPSSO = WP_SSO_ENABLED && Warecorp_Wordpress_SSO::isWordpressSiteEnabled();

    if ( $allowUseWPSSO && $wploginMode ) {
        if ( $this->_page->_user->isAuthenticated() ) {
            $url = $this->_page->_user->getUserPath('profile');
            $this->_redirect($url);
            exit;
        } else {
            $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
            $this->_redirectToLogin($url);
            exit;
        }
    }
    //  Wordpress user profile ckicked
    elseif ( $allowUseWPSSO && $wpprofileMode ) {
        // if user is auth'd go to user profile page
        if ( $this->_page->_user->isAuthenticated() ) {
            $this->_redirect($this->_page->_user->getUserPath('profile'));
            exit;
        }
        //  user is'n auth'd store reffered page and go to login page
        else {
            $this->_redirectToLogin('_USER_PROFILE_');
            exit;
        }
    }
    /**
     * END: ZCCF Wordpres SignIn/SignUp process
     */

    Warecorp::addTranslation("/modules/users/action.login.php.xml");

    if ( $this->_page->_user->isAuthenticated() ) {
        if ( USE_USER_PATH ) $this->_redirect($this->_page->_user->getUserPath('profile'));
        else $this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/users/profile/');
    }

    if (!empty($this->params['password'])) $this->params['password'] = strtolower($this->params['password']);
    $userParams = isset($this->params['login']) && isset($this->params['password'])
                  ? array('name'   => $this->params['login'],
                          'pass'   => $this->params['password'],
                          'status' => Warecorp_User_Enum_UserStatus::USER_STATUS_PENDING)
                  : '';
                  
    $form = new Warecorp_Form('loginForm', 'post', '/'.$this->_page->Locale.'/users/login/');
    $form->addRule('login','required', Warecorp::t('Enter please Username'));
    $form->addRule('password','required', Warecorp::t('Enter please Password'));
    if (!empty($userParams) && !Warecorp_Form_Validation::isUserExist($userParams)) {
        $form->addCustomErrorMessage(Warecorp::t('Account exists but not activated. Please activate account'));
    } else {
        if (!empty($userParams)) $userParams['status'] = Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE;
        $form->addRule('login', 'callback', Warecorp::t('Incorrect username or password'), array('func' => 'Warecorp_Form_Validation::isUserExist', 'params' => $userParams));
    }

    $login_message = '';
    if (isset($_SESSION['_restore_message']) && $_SESSION['_restore_message']) {
        $login_message = $_SESSION['_restore_message'];
        unset($_SESSION['_restore_message']);
    }

    $checker = true;
    if (!isset($this->params['login'])) {
    	$this->params['login'] = '';
    	$checker = false;
    }
    /**
     * +-----------------------------------------------------
     * |
     * |    FORM HANDLER
     * |
     * +-----------------------------------------------------
     */
    if ( $form->validate($this->params) ) {
        $form_data = $this->params;
        $user = Zend_Registry::get("User");
        $user = new Warecorp_User('login', $form_data['login']);
        if (isset($this->params["rememberme"])){
            setcookie("zanby_username", $user->getLogin(), time()+2592000, "/",'.'.BASE_HTTP_HOST); //  60*60*24*30 = 2592000
            setcookie("zanby_password", md5($user->getPass()), time()+2592000, "/",'.'.BASE_HTTP_HOST);
        }
        $user->authenticate();

        if (defined('USERS_LOG') && USERS_LOG) {
            Warecorp_Log_User::addEntry('login',Warecorp_Log_User::SUCCESS,array('user_id'=>$user->getId()));
        }

        $url = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/';
        if ( isset($_SESSION['login_return_page']) ) {
            /**
             * Constants to redirect after login
             * @author Artem Sukharev
             */
            $loginReturnPageKeywords = array(
                "_USER_PROFILE_" => $user->getUserPath('profile')
            );
            if ( array_key_exists($_SESSION['login_return_page'], $loginReturnPageKeywords) ) {
                $_SESSION['login_return_page'] = $loginReturnPageKeywords[$_SESSION['login_return_page']];
            }

            $url = $_SESSION['login_return_page'];
            unset($_SESSION['login_return_page']);
            $parsed_url = parse_url($url);
            if ( $allowUseWPSSO ) {
                $wp_parsed_url = parse_url(WP_SSO_URL);
                $condition=(strstr($parsed_url['host'], BASE_HTTP_HOST) === BASE_HTTP_HOST || strstr($parsed_url['host'], $wp_parsed_url['host']) === $wp_parsed_url['host']);
            } else {
            $condition=(strstr($parsed_url['host'], BASE_HTTP_HOST) === BASE_HTTP_HOST);
        }
            if (!$condition) $url = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/';
        }
        
        if ( $allowUseWPSSO ) {
            $code = md5(uniqid(mt_rand(), true));
            $cache = Warecorp_Cache::getFileCache();
            $cache->save($user->getId(), 'SSO_'.$code, array(), Warecorp_Wordpress_SSO::LIFETIME);
            if ( isset($this->params["rememberme"]) ){
                $this->_redirect(WP_SSO_URL.'?zssodoaction=signin&rememberme=1&key='.$code.'&ret='.urlencode($url));
            } else {
                $this->_redirect(WP_SSO_URL.'?zssodoaction=signin&key='.$code.'&ret='.urlencode($url));
            }
        } else {
            $this->_redirect($url);
        }
        
    }else{
        if (defined('USERS_LOG') && USERS_LOG) {
            $user = new Warecorp_User('login', $this->params['login']);
            if ($user->getId()) {
                Warecorp_Log_User::addEntry('login',Warecorp_Log_User::FAILURE,array('user_id'=>$user->getId(),'message'=>  implode(', ', $form->getErrorMessages())));
            }
        }
    }

    /* Check return url */
    $_SESSION['login_return_page'] = isset($_SESSION['login_return_page'])?$_SESSION['login_return_page']:(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null);
    if (isset($this->params["rememberme"])) $this->view->isChecked = '';
    elseif(!isset($this->params["rememberme"]) && $checker == true) $this->view->isChecked = 'off';
    else $this->view->isChecked = '';

    $this->_page->setTitle(Warecorp::t('Log In'));
    $this->view->hideBottomMenu = true;
    $this->view->login_message = $login_message;
    $this->view->form = $form;
    $this->view->login = $this->params['login'];
    $this->view->bodyContent = 'users/login.tpl';
	$this->view->isRightBlockHidden = true;
	
	//---------------
	$formOpenID = new Warecorp_Form('loginOpenIDForm', 'post', '/'.$this->_page->Locale.'/users/login.openid/');
	$this->view->formOpenID = $formOpenID;
