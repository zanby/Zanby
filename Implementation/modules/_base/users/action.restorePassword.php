<?php
Warecorp::addTranslation('/modules/users/action.restore.php.xml');

if ( $this->_page->_user->isAuthenticated() ) {
    $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/');
}

if (!isset($this->params['login'])||(!isset($this->params['code']))||(!$this->params['code'])) {
	$this->_redirect(BASE_URL);
}

$user = new Warecorp_User('login', $this->params['login']);
if ($user->getId() && $user->getRestorePasswordCode() == $this->params['code']) {
    $defaultTimeZone = date_default_timezone_get();
    date_default_timezone_set('UTC');        
	$generationDate = new Zend_Date($user->getRestoreRequestTime(), Zend_Date::ISO_8601);
	$currentDate = new Zend_Date();
	$currentDate->subDay(1);
    date_default_timezone_set($defaultTimeZone);                
	if ($generationDate->isEarlier($currentDate)) {
        $this->view->urlExpired = true;
        /* redirect user to password restore page for ZCCF project */
        if ( defined('HTTP_CONTEXT') && HTTP_CONTEXT == 'zccf' ) {
            $this->_redirect(BASE_URL.'/'.LOCALE.'/users/restore/');
            exit;
        }
	} else {
        $form = new Warecorp_Form('restore_password_form', 'POST');
        $form->addRule('pass', 'required', Warecorp::t('Please enter Password'));
        $form->addRule('pass_confirm', 'required', Warecorp::t('Please enter Password Confirmation'));
        $form->addRule('pass', 'compare', Warecorp::t('Password is not equal to Password Confirmation'), array('rule' => '==', 'value' => isset($this->params['pass_confirm'])?$this->params['pass_confirm']:''));
        $form->addRule('pass', 'minlength', Warecorp::t('Minimum password length is six characters'), array('min' => 6));
        $form->addRule('pass', 'maxlength', Warecorp::t('Password is too long (max %s)', 50), array('max' => 50));
        if ($form->validate($this->params)) {
        	$user->setPass(md5(strtolower($this->params['pass'])));
        	$user->setRestorePasswordCode(null);
        	$user->save();
        	$user->authenticate();
        	$this->_redirect(BASE_URL);
        }
        $this->view->restorePasswordForm = $form;
        $this->view->pass = isset($this->params['pass'])? $this->params['pass']: '';
        $this->view->pass_confirm = isset($this->params['pass_confirm'])? $this->params['pass_confirm']: '';
	}
} else {
	$this->view->incorrectUrl = true;
    /* redirect user to password restore page for ZCCF project */
    if ( defined('HTTP_CONTEXT') && HTTP_CONTEXT == 'zccf' ) {
        $this->_redirect(BASE_URL.'/'.LOCALE.'/users/restore/');
        exit;
    }
}
$this->view->bodyContent = 'users/restorePassword.tpl';
