<?php
	Warecorp::addTranslation('/modules/adminarea/action.newmember.php.xml');
$form = new Warecorp_Form('udForm', 'POST', $this->admin->getAdminPath('newmembers'));
if ($form->isPostback()) {	
	$loginParams = array('login'=>$this->params['login'], 'excludeIds' => array());
	$emailParams = array('email'=>$this->params['email'], 'excludeIds' => array());
	$change_pass = false;
	if ($this->params["state"] == 0)    $this->params["state"] = '';
	if ($this->params["city"] == 0)     $this->params["city"] = '';
	if ($this->params["country"] == 0)     $this->params["country"] = '';
	
	$form->addRule('firstname',    'required',  Warecorp::t('Enter please First Name'));
	$form->addRule('firstname',    'maxlength', Warecorp::t('First Name too long (max 50)'), array('max' => 50));
	$form->addRule('firstname',    'regexp',    Warecorp::t('First Name must start with letter'), array('regexp' => "/^[a-zA-Z]{1}/"));
	$form->addRule('firstname',    'regexp',    Warecorp::t('First Name may consist of a-Z, 0-9, \', -, underscores, space, and dot (.)'), array('regexp' => "/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/"));
	
	$form->addRule('lastname',     'required',  Warecorp::t('Enter please Last Name'));
	$form->addRule('lastname',     'maxlength', Warecorp::t('Last Name too long (max 50)'), array('max' => 50));
	$form->addRule('lastname',     'regexp',    Warecorp::t('Last Name must start with letter'), array('regexp' => "/^[a-zA-Z]{1}/"));
	$form->addRule('lastname',     'regexp',    Warecorp::t('Last Name may consist of a-Z, 0-9, \', -, underscores, space, and dot (.)'), array('regexp' => "/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/"));
	
	$form->addRule('birthday',     'required',  Warecorp::t('Enter please Birthday Date'));
	$form->addRule('birthday',     'validdate', Warecorp::t('Enter valid date, please'), isset($this->params['birthday']) ? array('day' => $this->params['birthday']['date_Day'], 'month' => $this->params['birthday']['date_Month'], 'year' => $this->params['birthday']['date_Year']): '');
	$form->addRule('birthday',     'callback',  Warecorp::t('Registration denied before 13 years old'), array('func' => 'Warecorp_Form_Validation::isAgeValid', 'params' => isset($this->params['birthday']) ? $this->params['birthday'] : ''));
	$form->addRule('country',      'required',  Warecorp::t('Choose please Country'));
	$form->addRule('state',        'required',  Warecorp::t('Choose please State'));
	$form->addRule('city',         'required',  Warecorp::t('Choose please City'));
	$form->addRule('gender',       'required',  Warecorp::t('Choose please Gender'));
	$form->addRule('timezone',     'required',  Warecorp::t('Choose please Timezone'));
	$form->addRule('zipcode',      'maxlength', Warecorp::t('Zip code too long (max 8)'), array('max' => 8));
	$form->addRule('login',        'required',  Warecorp::t('Enter please User ID'));
	$form->addRule('login',        'callback',  Warecorp::t('User ID (login) already exist'), array('func' => 'Warecorp_Form_Validation::isNewLoginExist', 'params' => $loginParams));
	$form->addRule('login',        'maxlength', Warecorp::t('Login Name too long (max 50)'), array('max' => 50));
	$form->addRule('login',        'alphanumeric',  Warecorp::t('Use a-z 0-9 for username'));
	$form->addRule('new_pass',         'minlength', Warecorp::t('Minimum password length is six characters'), array('min' => 6));
	$form->addRule('new_pass',         'maxlength', Warecorp::t('New Password too long (max 50)'), array('max' => 50));	
	$form->addRule('email',        'required',  Warecorp::t('Enter please Email Address'));
	$form->addRule('email',        'email',     Warecorp::t('Enter please correct Email Address'));
	$form->addRule('email',        'callback',  Warecorp::t('Email address already exist'), array('func' => 'Warecorp_Form_Validation::isNewUserEmailExist', 'params' => $emailParams));
	$form->addRule('email',        'maxlength', Warecorp::t('Email too long (max 255)'), array('max' => 255));		

	$user->setZipcode(isset($this->params['zipcode']) ? $this->params['zipcode'] : '');
	$user->setCityId($this->params['city']);
	$user->setfirstname($this->params['firstname']);
	$user->setLastname($this->params['lastname']);
	$user->setGender($this->params['gender']);
	$user->setBirthday($this->params['birthday']['date_Year'].'-'.$this->params['birthday']['date_Month'].'-'.$this->params['birthday']['date_Day']);
	$user->setIsBirthdayPrivate(isset($this->params['is_birthday_private']) ? '1' : '0');
	$user->setIsGenderPrivate(isset($this->params['is_gender_private']) ? '1' : '0');
	$user->setTimezone($this->params['timezone']);
	$user->setLogin(trim($this->params['login']));
	$user->setEmail($this->params['email']);
	$user->setStatus($this->params['status']);	
	
	if ($form->validate($this->params)){
		$user->setPass(md5($this->params['new_pass']));
		$city = Warecorp_Location_City::create($this->params["city"]);
		$user->setLatitude($city->getLatitude());
		$user->setLongitude($city->getLongitude());    
		$user->save();  
		$this->_redirect($this->admin->getAdminPath('members/id/'.$user->getId().'/'));
	}	
}
$template = 'adminarea/member.tpl';
$this->view->form = $form;
$this->view->user = $user;
$this->view->bodyContent = $template;
