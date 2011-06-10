<?php
    Warecorp::addTranslation('/modules/info/action.support.php.xml');
    
    $this->params = $this->_getAllParams();
    $this->_page->setTitle(Warecorp::t('Support'));
    
    $form = new Warecorp_Form('support', 'post', '/'.$this->_page->Locale.'/info/support/');
    
    if ($this->_page->_user->getId()) {
        $user = $this->_page->_user;
    }
    
    $topics[SITE_NAME_AS_STRING.' question']                    = Warecorp::t('[Select Topic]');
    $topics['Registration']                                     = Warecorp::t('Registration');
    $topics['How Do I..']                                       = Warecorp::t('How Do I..');
    $topics['Report a Bug']                                     = Warecorp::t('Report a Bug');
    $topics['Billing']                                          = Warecorp::t('Billing');
    $topics['Technical Support']                                = Warecorp::t('Technical Support');
    $topics['Question for the '.SITE_NAME_AS_STRING.' staff']   = Warecorp::t('Question for the %s staff', SITE_NAME_AS_STRING);
    $topics['Other']                                            = Warecorp::t('Other');
    
    $this->view->topics = $topics;
    
    if (isset($this->params['first_name'])){
        $this->params['first_name'] = htmlentities($this->params['first_name']);
        $this->params['first_name'] = trim($this->params['first_name']);
    }
    if (isset($this->params['last_name'])) {
        $this->params['last_name']  = htmlentities($this->params['last_name']);
        $this->params['last_name']  = trim($this->params['last_name']);
    }
    if (isset($this->params['message'])) {
        $this->params['message']    = htmlentities($this->params['message']);
    }
    
    $form->addRule('first_name',    'required',    Warecorp::t('Enter please First Name'));
    $form->addRule('first_name',    'maxlength',   Warecorp::t('First Name is too long (max %s)', 100), array('max' => 100));
    $form->addRule('first_name',    'lettersonly', Warecorp::t('First Name can consists of letters only'));
    $form->addRule('last_name',     'required',    Warecorp::t('Enter please Last Name'));
    $form->addRule('last_name',     'maxlength',   Warecorp::t('Last Name is too long (max %s)', 100), array('max' => 100));
    $form->addRule('last_name',     'lettersonly', Warecorp::t('Last Name can consists of letters only'));
    $form->addRule('email',         'required',    Warecorp::t('Enter please Email Address'));
    $form->addRule('email',         'email',       Warecorp::t('Enter please correct Email Address'));
    $form->addRule('email',         'maxlength',   Warecorp::t('Email is too long (max %s)', 100), array('max' => 100));
    $form->addRule('phone',         'maxlength',   Warecorp::t('Last Name is too long (max %s)', 50), array('max' => 50));
    $form->addRule('message',       'required',    Warecorp::t('Please enter Message'));
    $form->addRule('message',       'maxlength',   Warecorp::t('Body of your message is too big (maximum %s letters)', 65535), array("max" => 65535));
    $form->addRule('topic',         'regexp',      Warecorp::t('Please select a topic'), array("regexp" => '|^[[:upper:]]+|'));
    if ( !empty($this->params['phone']) )
        $form->addRule('phone',   'regexp',  Warecorp::t('Enter please correct Phone'), array("regexp" => '/^(?:\(\d{2,3}\))?[-\s.]?(?:\d[-\s.]?){7,12}$|^\d{3}[-\s.]?\d{4}$|^\+?\d{1,3}[-\s.]?\(?\d{2,3}\)?[-\s.]?(?:\d[-\s.]?){7,12}$/'));
    
    $template = 'info/support.tpl';
    if ( $form->validate($this->params) ) {
        
        Warecorp::sendContactUs( $this->params );
    
        $template = 'info/support_completed.tpl';
    } else {
        if ( !empty($this->params['first_name']) ) $this->view->firstName = html_entity_decode($this->params['first_name']) ;
        if ( !empty($this->params['last_name']) )  $this->view->lastName = html_entity_decode($this->params['last_name']) ;
        if ( !empty($this->params['email']) )      $this->view->email = html_entity_decode($this->params['email']) ;
        if ( !empty($this->params['phone']) )      $this->view->phone = ($this->params['phone']) ;
        if ( !empty($this->params['message']) )    $this->view->message = html_entity_decode($this->params['message']) ;
        if ( !empty($this->params['phone']) )      $this->view->phone = html_entity_decode($this->params['phone']) ;
        if ($this->_page->_user->getId()) {
            if ( !isset($this->params['first_name']) ) $this->view->firstName = $user->getFirstName() ;
            if ( !isset($this->params['last_name']) )  $this->view->lastName = $user->getLastName() ;
            if ( !isset($this->params['email']) )      $this->view->email = $user->getEmail();
        }
    
    }
    $this->view->newuser = $this->params;
    $this->view->bodyContent = $template;
    $this->view->form = $form;
