<?php

    Warecorp::addTranslation('/modules/registration/action.confirm.php.xml');
    
    $this->params['email']  = isset($this->params['email']) ? $this->params['email'] : '';
    $this->params['name']   = isset($this->params['name'])  ? $this->params['name']  : '';
    $this->params['pass']   = isset($this->params['pass'])  ? $this->params['pass']  : '';
    
    $form = new Warecorp_Form('confirmregistrationForm', 'post', '/'.$this->_page->Locale.'/registration/confirm/');
    $form->addRule('name',      'required',     Warecorp::t('Enter please Username'));
    $form->addRule('pass',      'required',     Warecorp::t('Enter please Password'));
    if (!empty($this->params['email'])) {
        $form->addRule('email', 'email',        Warecorp::t('Enter please correct Email Address'));
    }
    if (!empty($this->params['name']) && !empty($this->params['pass'])){
        $this->params['status'] = array(Warecorp_User_Enum_UserStatus::USER_STATUS_PENDING, Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);
        $form->addRule('hash', 'callback', Warecorp::t('Sorry. Unrecognized username or password.'), array('func' => 'Warecorp_Form_Validation::isUserExist', 'params' => $this->params));
    }
    
    $data = array();
    
    if ($form->validate($this->params)) {
        $user = new Warecorp_User('login', $this->params['name']);
        if ($user->getStatus() != 'pending') {
            $form->addRule('_confirm_error',  'required',  Warecorp::t('Sorry. But this account is already confirmed.'));
            $form->validate($this->params);
            $data = $this->params;
        } else {
            if (!empty($this->params['email'])) {
                $user->pkColName = 'id';
                $user->setEmail(trim($this->params['email']));
            }
    
            //  Send message
            $mail = new Warecorp_Mail_Template('template_key', 'USER_REGISTER');
            $sender_object = new Warecorp_User();
            $mail->setSender($sender_object);
            $mail->addParam('ConfirmationLink', Warecorp::getTinyUrl(BASE_URL.'/'.LOCALE.'/registration/index/code/'.$user->getRegisterCode().'/', HTTP_CONTEXT));
            $mail->addRecipient($user);
            $mail->send();
    
            $_SESSION['_reg_user'] = array(
                                           'login'  => $this->params['name'],
                                           'email'  => !empty($this->params['email']) ? $this->params['email'] : 'Your email',
                                          );
            $this->_redirect(BASE_URL.'/'.$this->_page->Locale.'/registration/confirmcompleted/');
        }
    } else {
        $data = $this->params;
    }
    
    $this->_page->setTitle(Warecorp::t('Remind Confirmation Code'));
    $this->view->form          = $form;
    $this->view->data          = $data;
    $this->view->bodyContent   = 'registration/confirm.tpl';
