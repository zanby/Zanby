<?php
    Warecorp::addTranslation('/modules/info/action.contactus.php.xml');
    
    $this->_page->Xajax->registerUriFunction("changeCountry", "/ajax/changeCountry/");
    $this->_page->Xajax->registerUriFunction("changeState",   "/ajax/changeState/");
    
    $this->params = $this->_getAllParams();
    $this->_page->setTitle(Warecorp::t('Contact Us'));
    
    $form = new Warecorp_Form('contactUs', 'post', '/'.$this->_page->Locale.'/info/contactus/');
    
    $location = new Warecorp_Location();
    $countries = $location->getCountriesListAssoc(true);
    $this->view->countries = $countries;
    if ($this->_page->_user->getId()) {
        $user = $this->_page->_user;
        if ( !isset($this->params['country']) ) $this->params['country'] = $user->getCountry()->id;        
        if ( !isset($this->params['state']) ) $this->params['state'] = $user->getState()->id;
        if ( !isset($this->params['city']) ) $this->params['city'] = $user->getCity()->id;
    }
    if (!isset($this->params["country"])) {
        $this->params["country"] = 0;
        $states[] = Warecorp::t('[Select State]');
    } else {
        $country = Warecorp_Location_Country::create($this->params["country"]);
        $states = $country->getStatesListAssoc(true);
    }
    $this->view->states = $states;
    
    if (!isset($this->params["state"])) {
        $this->params["state"] = 0;
        $cities[] = Warecorp::t('[Select City]');
    } else {
        $state = Warecorp_Location_State::create($this->params["state"]);
        $cities = $state->getCitiesListAssoc(true);
    }
    $this->view->cities = $cities;
    
    if (!isset($this->params["city"]) || $this->params["city"] == '[Select City]') $this->params["city"] = 0;
    
    $this->view->country = $this->params["country"];
    $this->view->state = $this->params["state"];
    $this->view->city = $this->params["city"];
    
    $topics[SITE_NAME_AS_STRING.' question']                    = Warecorp::t('[Select Topic]');
    $topics['Group Registration']                               = Warecorp::t('Group Registration');
    $topics['Advertise with Zanby']                             = Warecorp::t('Advertise with ').SITE_NAME_AS_STRING;
    $topics['Business-to-Business Programs']                    = Warecorp::t('Business-to-Business Programs');
    $topics['Technical Problems']                               = Warecorp::t('Technical Problems');
    $topics['Press Inquiry']                                    = Warecorp::t('Press Inquiry');
    $topics['Question for the '.SITE_NAME_AS_STRING.' staff']   = Warecorp::t('Question for the %s staff', array(SITE_NAME_AS_STRING));
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
    if (isset($this->params['company'])) {
        $this->params['company']    = htmlentities($this->params['company']);
    }
    
    $form->addRule('first_name',    'required',     Warecorp::t('Enter please First Name'));
    $form->addRule('first_name',    'maxlength',    Warecorp::t('First Name too long (max %s)', 50), array('max' => 50));
    $form->addRule('last_name',     'required',     Warecorp::t('Enter please Last Name'));
    $form->addRule('last_name',     'maxlength',    Warecorp::t('Last Name too long (max %s)', 50), array('max' => 50));
    $form->addRule('email',         'required',     Warecorp::t('Enter please Email Address'));
    $form->addRule('email',         'email',        Warecorp::t('Enter please correct Email Address'));
    $form->addRule('email',         'maxlength',    Warecorp::t('Email too long (max %s)', 100), array('max' => 100));
    $form->addRule('company',       'maxlength',    Warecorp::t('Last Name too long (max %s)', 100), array('max' => 100));
    $form->addRule('phone',         'maxlength',    Warecorp::t('Last Name too long (max %s)', 50), array('max' => 50));
    $form->addRule('message',       'required',     Warecorp::t('Please enter Message'));
    $form->addRule('message',       'maxlength',    Warecorp::t('Body of your message is too big (maximum %s letters)', 65535), array("max" => 65535));
    if ( !empty($this->params['phone']) )    $form->addRule('phone',   'numeric',  Warecorp::t('Enter please correct Phone'));
    if ( !empty($this->params['country']) )  $form->addRule('country', 'compare',  Warecorp::t('Choose please Country'), array('rule' => '!=', 'value' => 0));
    if ( !empty($this->params['state']) )    $form->addRule('state',   'compare',  Warecorp::t('Choose please State'), array('rule' => '!=', 'value' => 0) );
    if ( !empty($this->params['city']) )     $form->addRule('city',    'compare',  Warecorp::t('Choose please City'), array('rule' => '!=', 'value' => 0));
    
    $template = 'info/contactus.tpl';
    if ( $form->validate($this->params) ) {
        
        Warecorp::sendContactUs( $this->params );
    
        $template = 'info/contactus_completed.tpl';
    } else {
        if ( !empty($this->params['first_name']) ) $this->view->firstName = html_entity_decode($this->params['first_name']) ;
        if ( !empty($this->params['last_name']) )  $this->view->lastName = html_entity_decode($this->params['last_name']) ;
        if ( !empty($this->params['email']) )      $this->view->email = html_entity_decode($this->params['email']) ;
        if ( !empty($this->params['company']) )    $this->view->company = html_entity_decode($this->params['company']) ;
        if ( !empty($this->params['phone']) )      $this->view->phone = ($this->params['phone']) ;
        if ( !empty($this->params['message']) )    $this->view->message = html_entity_decode($this->params['message']) ;
        if ( !empty($this->params['country']) )    $this->view->country = ($this->params['country']) ;
        if ( !empty($this->params['state']) )      $this->view->state = ($this->params['state']) ;
        if ( !empty($this->params['city']) )       $this->view->city = ($this->params['city']) ;
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
