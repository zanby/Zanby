<?php

    require(ENGINE_DIR.'/b2evo_captcha/b2evo_captcha.config.php');
    require_once(ENGINE_DIR.'/b2evo_captcha/b2evo_captcha.class.php');

    Warecorp::addTranslation('/modules/registration/action.index.php.xml');    
//var_dump($this->params);var_dump($_POST);var_dump($_REQUEST);var_dump($_SERVER);exit;
    $_template  = 'registration/index.tpl'; 
    $this->_page->setTitle('Registration');
    /**
     * If we are already logged on, go to the user page instead.
     */
    if ($this->_page->_user->getId() && !isset($this->params['act'])) $this->_redirect('/');	
	/**
	 * Register ajax functions
	 */
    $this->_page->Xajax->registerUriFunction("detectCountry",       "/ajax/detectCountry/");
    $this->_page->Xajax->registerUriFunction("loginavailable",      "/ajax/loginavailable/");
    $this->_page->Xajax->registerUriFunction("zipcodeavailable",    "/ajax/zipCodeAvailable/");
    $this->_page->Xajax->registerUriFunction("cityavailable",       "/ajax/cityAvailable/");
    $this->_page->Xajax->registerUriFunction("citychoosealias",     "/ajax/cityChooseAlias/");
    $this->_page->Xajax->registerUriFunction("citychoosecustom",    "/ajax/cityChooseCustom/");
    /**
     * 
     */
    $imgLoc         = (isset($_SESSION['imgLoc'])) ? $_SESSION['imgLoc'] : null;
    $captchaValues  = (isset($imgLoc) && isset($this->params['verify_code'])) ? array('key' => $imgLoc, 'userkey' => $this->params['verify_code']):'';
    /**
     * Apply default values
     */    
    $this->params['countryId']          = ( !isset($this->params['countryId']) )            ? 0     : $this->params['countryId'];
    $this->params['city']               = ( !isset($this->params['city']) )                 ? 0     : trim($this->params['city']);
    $this->params['cityQuerySelected']  = ( !isset($this->params['cityQuerySelected']) )    ? ''    : trim($this->params['cityQuerySelected']);
    $this->params['cityAliasSelected']  = ( !isset($this->params['cityAliasSelected']) )    ? ''    : $this->params['cityAliasSelected'];
    $this->params['city_correct']       = ( !isset($this->params['city_correct']) )         ? 0     : $this->params['city_correct'];
    $this->params['zipcode']            = ( !isset($this->params['zipcode']) )              ? ''    : trim($this->params['zipcode']);
    $this->params['pass']               = ( !isset($this->params['pass']) )                 ? null  : strtolower($this->params['pass']);
    $this->params['pass_confirm']       = ( !isset($this->params['pass_confirm']) )         ? null  : strtolower($this->params['pass_confirm']);
    $this->params['firstname']          = ( !isset($this->params['firstname']) )            ? null  : trim($this->params['firstname']);
    $this->params['lastname']           = ( !isset($this->params['lastname']) )             ? null  : trim($this->params['lastname']);
    $this->params['login']              = ( !isset($this->params['login']) )                ? null  : trim($this->params['login']);
    $this->params['email']              = ( !isset($this->params['email']) )                ? null  : trim($this->params['email']);
    $this->params['agree']              = ( !isset($this->params['agree']) )                ? 0     : 1;
    $this->params['age_agree']          = ( !isset($this->params['age_agree']) )            ? 0     : 1;

	/**
	 * if user came from anonymous event rsvp page and clicked 'I want to create account' we have entered email
	 * apply this email
	 * @see Group|User_Controller::calendarEventAttendeeSignupAction();
	 */
	if ( null === $this->params['email'] && !empty($_SESSION['register_user_after_rsvp']) ) {
		$this->params['email'] = $_SESSION['register_user_after_rsvp'];
		unset($_SESSION['register_user_after_rsvp']);
	}

    /**
     * FORM : Create form and form rules
     * Add Rules into Form object
     */
    $form = new Warecorp_Form('registrationForm', 'post', '/'.$this->_page->Locale.'/registration/index/');
    /**
     * First Name
     */
    $form->addRule('firstname',    'required',      Warecorp::t('Please enter First Name'));
    $form->addRule('firstname',    'maxlength',     Warecorp::t('First Name is too long (max %s)', 50), array('max' => 50));
    $form->addRule('firstname',    'minlength',     Warecorp::t('First Name is too short (min %s)', 2), array('min' => 2));
    $form->addRule('firstname',    'callback',      Warecorp::t('First Name contains incorrect characters'),
        array(
            'func'   => 'Warecorp_Form_Validation::isInvalidName',
            'params' => array( 'firstname' => ((isset($this->params['firstname'])) ? $this->params['firstname'] : ''))
        )
    );
    /**
     * Last Name
     */
    $form->addRule('lastname',     'required',      Warecorp::t('Please enter Last Name'));
    $form->addRule('lastname',     'maxlength',     Warecorp::t('Last Name is too long (max %s)', 50), array('max' => 50));
    $form->addRule('lastname',     'minlength',     Warecorp::t('Last Name is too short (min %s)', 2), array('min' => 2));
    $form->addRule('lastname',     'callback',      Warecorp::t('Last Name contains incorrect characters'),
        array(
            'func'   => 'Warecorp_Form_Validation::isInvalidName',
            'params' => array( 'lastname' => ((isset($this->params['lastname'])) ? $this->params['lastname'] : ''))
        )
    );
    /**
     * Country
     */
    $form->addRule('countryId',    'nonzero',       Warecorp::t('Please choose a Country'));
    /**
     * City
     */
    $form->addRule('city',         'callback',      Warecorp::t('Please enter City'),
        array(
            'func' => 'Warecorp_Form_Validation::isCityRequired',
            'params' => array(
                'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                'city' => ((isset($this->params['city'])) ? $this->params['city'] : null)
            )
        )
    );
    
    /**
     * +-------------------------------------------------------------
     * |
     * |    CHECK IF USER IS CONNECTED TO FACEBOOK AND RETRIEVE INFO
     * |
     * +-------------------------------------------------------------
     */
    $facebookId = null;
    if ( FACEBOOK_USED ) {        
        $facebookId = Warecorp_Facebook_Api::getFacebookId(); 
        if ( !empty($facebookId) && 'facebook' == $this->getRequest()->getParam('mode', null) ) {            
            $form->action = '/'.$this->_page->Locale.'/registration/index/mode/facebook';
            
            if ( Warecorp_Facebook_User::isFBAccountConnected($facebookId) ) {
                $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/facebook/confirmprofile/');
            }
            if ( !$form->isPostback() ) {
                $facebookInfo = Warecorp_Facebook_Api::getInstance()->api(array(
                    'method'=>'users.getInfo', 
                    'uids'=>$facebookId, 
                    'fields'=>'username,first_name,last_name,current_location,birthday,birthday_date,email_hashes,hometown_location,locale,name,pic,pic_with_logo,pic_big,pic_big_with_logo,pic_small,pic_small_with_logo,pic_square,pic_square_with_logo,sex,timezone,website'));
                if ( !empty($facebookInfo) ) {
                    $facebookInfo = $facebookInfo[0];
                    $this->params['firstname'] = $facebookInfo['first_name'];
                    $this->params['lastname'] = $facebookInfo['last_name'];
                    $this->params['login'] = Warecorp_Facebook_User::createUniqLogin($facebookInfo);
                    if ( !empty($facebookInfo['current_location']) ) {
                        $objCountry = Warecorp_Location_Country::findByName($facebookInfo['current_location']['country']);
                        if ( $objCountry ) {
                            $this->params['countryId'] = $objCountry->id;
                            if ( $this->params['countryId'] == 1 || $this->params['countryId'] == 38 ) {
                                if ( !Warecorp_Form_Validation::isCleanZipcodeInvalid(array(
                                    'countryId' => $this->params['countryId'],
                                    'zipcode' => $facebookInfo['current_location']['zip']
                                )) ) {
                                    $this->params['zipcode'] = $facebookInfo['current_location']['zip'];
                                }
                            } else {
                                $this->params['city'] = $facebookInfo['current_location']['city'];
                                $this->params['zipcode'] = $facebookInfo['current_location']['zip'];                                    
                                $objUserCity = Warecorp_Location_City::findByName($this->params['city']);
                                if ( null === $objUserCity->id ) {
                                    $this->params['cityQuerySelected'] = $this->params['city'];
                                    $this->params['city_correct'] = 1;
                                } else {                                    
                                }                                    
                            }                                
                        }
                    } elseif ( !empty($facebookInfo['hometown_location']) ) {
                        $objCountry = Warecorp_Location_Country::findByName($facebookInfo['hometown_location']['country']);
                        if ( $objCountry ) {
                            $this->params['countryId'] = $objCountry->id;
                            if ( $this->params['countryId'] == 1 || $this->params['countryId'] == 38 ) {
                            } else {
                                $this->params['city'] = $facebookInfo['hometown_location']['city'];                                    
                                $objUserCity = Warecorp_Location_City::findByName($this->params['city']);
                                if ( null === $objUserCity->id ) {
                                    $this->params['cityQuerySelected'] = $this->params['city'];
                                    $this->params['city_correct'] = 1;
                                } else {                                    
                                }                                    
                            }                                
                        }
                    }
                }  
            }                 
        } else $facebookId = null;        
    }
    /**
     * +-------------------------------------------------------------
     */

    /**
     * City, Custom city
     */
    $lstCities          = null;     // list of aliases allowed for current city query
    $objUserCountry     = null;     // user choosed country object
    $objUserCity        = null;     // user choosed city object
    $strRECOGNIZEDCity  = '';       // string name of recognized place
    $needApproveCity    = false;    // need show city confirmation box
    if ( $this->params['countryId'] && $this->params['countryId'] != 1 && $this->params['countryId'] != 38 ) {
    	$objUserCountry = Warecorp_Location_Country::create($this->params['countryId']);
	    if ( '' == $this->params['city'] ) {
	        $this->params['cityQuerySelected']  = '';
	        $this->params['cityAliasSelected']  = '';
	        $this->params['city_correct']       = 0;
	    } 
	    /**
	     * Ajax validation was processed
	     */
	    elseif ( $this->params['cityQuerySelected'] == $this->params['city'] ) { // ajax validation was completed correctly
	    	if ( $this->params['cityAliasSelected'] ) {   //  alias was changed correctly - certian city was selected 
		        $objUserCity = Warecorp_Location_City::create($this->params['cityAliasSelected']);
		        if ( null === $objUserCity->id ) $objUserCity = null;
		        elseif ( $objUserCity->getState()->getCountry()->id !== $objUserCountry->id ) $objUserCity = null;
		        else $strRECOGNIZEDCity = $objUserCity->name.', '.$objUserCity->getState()->name.', '.$objUserCity->getState()->getCountry()->name;
	    	} elseif ( $this->params['city_correct'] ) {  //  user checked - city I entered is correct
	    		$strRECOGNIZEDCity = $this->params['city'].', '.$objUserCountry->name;
	    	}
			$lstCityAliasIds = Warecorp_Location_Alias_List::detectAliasByQueryString($this->params['city'], $objUserCountry->id);
			$lstCities = $objUserCountry->findByCityNameOrIds($this->params['cityQuerySelected'], $lstCityAliasIds, ($objUserCity) ? $objUserCity->id : null);
			$lstCitiesSize = sizeof($lstCities);
			if ( $lstCitiesSize == 0 && $objUserCity ) $needApproveCity = false;
			elseif ( $lstCitiesSize == 0 && !$objUserCity ) $needApproveCity = true;
			elseif ( $lstCitiesSize == 1 && !$this->params['cityAliasSelected'] ) {
                $objUserCity = $lstCities[0];
                $strRECOGNIZEDCity = $objUserCity->name.', '.$objUserCity->getState()->name.', '.$objUserCity->getState()->getCountry()->name;				
				$this->params['cityAliasSelected'] = $objUserCity->id;
                $lstCities = null;
                $needApproveCity = false;
			} else $needApproveCity = true;  
	    } 
	    /**
	     * Ajax validation was not processed, new city value was submited
	     */
	    elseif ( '' != trim($this->params['city'])) {
            $this->params['cityQuerySelected']  = $this->params['city'];
            $this->params['cityAliasSelected']  = '';
            $this->params['city_correct']       = 0;                        
	    	
            $lstCityAliasIds = Warecorp_Location_Alias_List::detectAliasByQueryString($this->params['cityQuerySelected'], $objUserCountry->id);
            $lstCities = $objUserCountry->findByCityNameOrIds($this->params['cityQuerySelected'], $lstCityAliasIds);
            $lstCitiesSize = sizeof($lstCities);
            if ( $lstCitiesSize == 0 ) $needApproveCity = true;
            elseif ( $lstCitiesSize == 1 ) {
                $objUserCity = $lstCities[0];
                $strRECOGNIZEDCity = $objUserCity->name.', '.$objUserCity->getState()->name.', '.$objUserCity->getState()->getCountry()->name;              
                $this->params['cityAliasSelected'] = $objUserCity->id;
                $lstCities = null;
                $needApproveCity = false;
            } else $needApproveCity = true;           	        
	    }	    	
    }
    $form->addRule('city',         'callback',      Warecorp::t('Please specify certain city.'),
        array(
            'func' => 'Warecorp_Form_Validation::isCityOrCustomValid',
            'params' => array(
                'countryId'         => $this->params['countryId'],
                'objUserCity'       => $objUserCity,
		        'city_correct'      => $this->params['city_correct']
            )
        )
    );
    /**
     * Zip Code
     */
    $form->addRule('zipcode',      'callback',      Warecorp::t('Please enter Zip code'),
        array(
            'func' => 'Warecorp_Form_Validation::isZipcodeRequired',
            'params' => array(
                'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                'zipcode' => ((isset($this->params['zipcode'])) ? $this->params['zipcode'] : null)
            )
        )
    );
    $form->addRule('zipcode',      'callback',      Warecorp::t('Sorry, zip code was not recognized. Please, enter another one.'),
        array(
            'func' => 'Warecorp_Form_Validation::isCleanZipcodeInvalid',
            'params' => array(
                'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                'zipcode' => ((isset($this->params['zipcode'])) ? $this->params['zipcode'] : null)
            )
        )
    );
    $strRECOGNIZEDZip = false;
    if ( $this->params['countryId'] && ($this->params['countryId'] == 1 || $this->params['countryId'] == 38) ) {
        $objUserCountry = Warecorp_Location_Country::create($this->params['countryId']);
        if ( $objUserCountry->checkZipcode($this->params['zipcode']) ) $strRECOGNIZEDZip = true;
    }
    /**
     * Login
     */
    $form->addRule('login',        'required',      Warecorp::t('Please enter User Name'));
    $form->addRule('login',        'maxlength',     Warecorp::t('User Name is too long (max %s)', 50), array('max' => 50));
    $form->addRule('login',        'callback',      Warecorp::t('User Name already exists'), array('func' => 'Warecorp_Form_Validation::isLoginExist', 'params' => isset($this->params['login']) ? $this->params['login'] : ''));
    $form->addRule('login',        'alphanumeric',  Warecorp::t('Use a-z 0-9 for User Name'));
    $form->addRule('login',        'minlength',     Warecorp::t('Minimum User Name length is three characters'), array('min' => 3));
    /**
     * Password and Password Confirmation
     */
    $form->addRule('pass',         'required',      Warecorp::t('Please enter Password'));
    $form->addRule('pass_confirm', 'required',      Warecorp::t('Please enter Password Confirmation'));
    $form->addRule('pass',         'compare',       Warecorp::t('Password is not equal to Password Confirmation'), array('rule' => '==', 'value' => isset($this->params['pass_confirm'])?$this->params['pass_confirm']:''));
    $form->addRule('pass',         'minlength',     Warecorp::t('Minimum password length is six characters'), array('min' => 6));
    $form->addRule('pass',         'maxlength',     Warecorp::t('Password is too long (max %s)', 50), array('max' => 50));
    /**
     * Email
     */
    $form->addRule('email',        'required',      Warecorp::t('Please enter Email Address'));
    $form->addRule('email',        'email',         Warecorp::t('Please enter correct Email Address'));
    $form->addRule('email',        'callback',      Warecorp::t('Email address already exist'), array('func' => 'Warecorp_Form_Validation::isUserEmailExist', 'params' => isset($this->params['email']) ? $this->params['email'] : ''));
    $form->addRule('email',        'maxlength',     Warecorp::t('Email address is too long (max %s)', 255), array('max' => 255));
    /**
     * Captcha
     */
    if (REGISTRATION_CAPTCHA !='off') {
        if ( !(FACEBOOK_USED && $facebookId) ) {
            $form->addRule('verify_code',  'callback',  Warecorp::t('Enter valid verification code'), array('func' => 'Warecorp_Form_Validation::isCaptchaCodeNotValid','params' =>$captchaValues));
        }
    }
    /**
     * Terms of Service agreement
     */
    $form->addRule('agree',        'nonzero',   Warecorp::t('You must agree to terms of service'));
    /**
     * Age agreement
     */
    $form->addRule('age_agree',    'nonzero',   Warecorp::t('Sorry, you aren\'t over 18 years of age.'));
    
    /**
     * +-------------------------------------------------------------
     * |
     * |    Handle : REGISTRATION CONFIRMATION
     * | 
     * +-------------------------------------------------------------
     */
    if ( isset($this->params['code']) && $this->params['code'] ) {
        $user = new Warecorp_User('register_code', $this->params['code']);
        if (!$user->getId()) {
            $_template = 'registration/error.tpl';
            $this->view->error = Warecorp::t('Registration Code is invalid.');
        } elseif ( $user->getStatus() != Warecorp_User_Enum_UserStatus::USER_STATUS_PENDING && $user->getConfirmationStatus() != 0) {
            $_template = 'registration/error.tpl';
            $this->view->error = Warecorp::t('Sorry. But this account is already confirmed.');
        } else {
            if (!defined('WHO_APPROVE_USER_ACCOUNT')) {
                $user->pkColName = 'id';
                $user->setStatus('active');
                $user->setConfirmationStatus(1);
                $user->save();
                $user->authenticate();
                $this->_redirect(BASE_URL.'/'.$this->_page->Locale.'/registration/registrationcompleted/');
            } else { //activation by admin                
                if (!empty($this->params['act'])) {
                    if ($this->params['act'] == 'reject') {
                        $user->sendRejectedByAdmin();
                        $user->delete();
                        $approveResult = 0;
                    } else { //approve
                        $user->pkColName = 'id';
                        $user->setStatus('active');
                        $user->save();
                        $user->sendApprovedByAdmin();
                        $approveResult = 1;
                    }
                    $this->view->accountApproved = $approveResult;
                    $_template = 'registration/processed.tpl';
   
                } else {
                    $_template = 'registration/error.tpl';
                    $this->view->error = Warecorp::t("Approve/Reject action not defined");   
                }                
            }
        }
    }    
    /**
     * +-------------------------------------------------------------
     * |
     * |    Handle : REGISTRATION FORM PROCESSING
     * | 
     * +-------------------------------------------------------------
     */
    elseif ($form->validate($this->params)) {
        $user = new Warecorp_User();
        
        $objUserCountry = Warecorp_Location_Country::create($this->params['countryId']);
        if ( $this->params['countryId'] == 1 || $this->params['countryId'] == 38 ) {
        	$objZip = Warecorp_Location_Zipcode::createByZip($this->params['zipcode']);
            $user->setCityId($objZip->getCity()->id);
            $user->setZipcode($this->params['zipcode']);
        } else {        	
	        if ( $objUserCity || $this->params['city_correct'] ) {
	            if ( $this->params['city_correct'] ) {
	                if ( null === $objUserState = $objUserCountry->findDefaultState() ) {
	                    $objUserState = new Warecorp_Location_State();
	                    $objUserState->countryId = $objUserCountry->id;
	                    $objUserState->name = $objUserCountry->name;
	                    $objUserState->code = '';
	                    $objUserState->source = 'custom';
	                    $objUserState->isDefault = 1;
	                    $objUserState->save();
	                }      
	                /**
	                 * @todo if state is default seave all places with some name as one city
	                 */
	                if ( null === $objUserCity = Warecorp_Location_City::findByName($this->params['city'], $objUserState) ) {
                        $objUserCity = new Warecorp_Location_City();
	                    $objUserCity->stateId   = $objUserState->id;
	                    $objUserCity->name      = $this->params['city'];
	                    $objUserCity->source    = 'custom';
	                    $objUserCity->save();                   
	                    if ( $objUserCountry->latitude && $objUserCountry->longitude ) {
	                        $timezoneId = Warecorp_Location_Alias_List::runTimezoneDetect($objUserCountry->latitude, $objUserCountry->longitude);
	                        $objUserCity->updateCityInfo($objUserCountry->latitude, $objUserCountry->longitude, $timezoneId);    
	                    }
	                }         

	            }
	        }        	
            $user->setCityId($objUserCity->id);
            $user->setZipcode('');
        }

        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $user->setFirstname($this->params['firstname'])
            ->setLastname($this->params['lastname'])
            ->setIsBirthdayPrivate(1)
            ->setGender('unselected')
            ->setIsGenderPrivate(1)
            ->setLogin($this->params['login'])
            ->setPass(md5(strtolower($this->params['pass'])))
            ->setEmail($this->params['email'])
            ->setCalendarPrivacy(1)
            ->setContactMode(16)
            ->setStatus(Warecorp_User_Enum_UserStatus::USER_STATUS_PENDING)
            ->setRegisterDate(date('Y-m-d H:i:s'))
            ->setMembershipPlan('premium');        
        /**
         * This sets default timezone for user.
         */
        date_default_timezone_set($defaultTimezone);
        $user->setTimezoneFromCity();
        /**
         * get coords
         */
        $city = Warecorp_Location_City::create($user->getCityId());
        $user->setLatitude($city->getLatitude())->setLongitude($city->getLongitude());

        if (DIRECT_ACTIVATION == 'on') $user->setStatus(Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);

        $user->save();
        
        setcookie("zanby_username", NULL, time()-2592000, "/",'.'.BASE_HTTP_HOST);  //  2592000 = 60*60*24*30;
        setcookie("zanby_password", NULL, time()-2592000, "/",'.'.BASE_HTTP_HOST);

        /**
         * Setup default privacy options for user
         */        
        $privacy = $user->getPrivacy();
        $privacy->setCpAnyMembers       (0)
                ->setCpGroupOrganizers  (1)
                ->setCpMyGroupOrganizers(1)
                ->setCpMyGroupMembers   (1)
                ->setCpMyFriends        (1)
                ->setCpMyNetwork        (0);
        $privacy->save();
        
        /**
        * Events : Проверяем, если были attendee с таким email - делаем их для данного пользователя
        */
        Warecorp_ICal_Attendee_List::updateAttendeeForNewUser($user);
        Warecorp_User_Friend_Request_List::createRequestsForNewUser($user);

        unset($_SESSION['imgLoc']);
        $mail_confirm_url = $user->getRegisterCode();

        /**
        * Join user to Main family group automaticly if this group is defined
        */
        if ( defined('IMPLEMENTATION_GROUP_UID') && IMPLEMENTATION_GROUP_UID ) {
            $objMainGroup = Warecorp_Group_Factory::loadByGroupUID(IMPLEMENTATION_GROUP_UID,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
            if ( $objMainGroup && null !== $objMainGroup->getId() ) {
                $objMainGroup->getMembers()->addMember($user->getId(), 'member', 'approved');
                $objMainGroup->sendGroupJoinNewMember( $objMainGroup, $user, NULL, NULL, false );
            }
        }
        
        /**
         * +-----------------------------------------------------------------------------------------
         * | if application uses facebook connect and user is connected to facebook add association
         * +-----------------------------------------------------------------------------------------
         */
        if ( FACEBOOK_USED && null !== $facebookId ) {
            if ( Warecorp_Facebook_User::isFBAccountConnected($facebookId) ) {
                $facebookUser = new Warecorp_Facebook_User($facebookId);
                $facebookUser->setUserId($user->getId());
            } else {            
                $facebookUser = new Warecorp_Facebook_User();
                $facebookUser->setUserId($user->getId());
                $facebookUser->setFacebookId($facebookId);
            }
            $facebookUser->save();       
            $facebookInfo = Warecorp_Facebook_Api::getInstance()->api(array(
                'method'=>'users.getInfo', 
                'uids'=>$facebookId, 
                'fields'=>'username,first_name,last_name,current_location,birthday,birthday_date,email_hashes,hometown_location,locale,name,pic,pic_with_logo,pic_big,pic_big_with_logo,pic_small,pic_small_with_logo,pic_square,pic_square_with_logo,sex,timezone,website'));
            if ( !empty($facebookInfo[0]['birthday_date']) ) {
                $birthday_date = explode("\/", $facebookInfo[0]['birthday_date']);
                $birthday_date = $birthday_date[2].'-'.$birthday_date[0].'-'.$birthday_date[1];
                $user->setBirthday($birthday_date);
            }
            if ( !empty($facebookInfo[0]['sex']) && in_array($facebookInfo[0]['sex'], array('male', 'female')) ) {
                $user->setGender($facebookInfo[0]['sex']);
            }

            $user->setStatus(Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);
            $user->save();
            
            /* TODO: upload avatar from facebook */
            if ( !empty($facebookInfo[0]['pic_big']) ) {                   
                $new_avatar = new Warecorp_User_Avatar();
                $new_avatar->setUserId($user->getId());
                $new_avatar->setByDefault(1);
                $new_avatar->save(); 
            
                $fn = UPLOAD_BASE_PATH."/upload/user_avatars/".md5($user->getId().$new_avatar->getId())."_orig.jpg";                    
                $fp = fopen ($fn, 'w+');
                $ch = curl_init($facebookInfo[0]['pic_big']);
                curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);
            }

            /* TODO: login user automaticly */
            $user->authenticate();
            		
            /**
             * Send message with login details
             */
            $ConfirmationLink = (DIRECT_ACTIVATION == 'on') ? '' : Warecorp::getTinyUrl(BASE_URL.'/'.LOCALE.'/registration/index/code/'.$user->getRegisterCode().'/', HTTP_CONTEXT);
            $user->sendRegistrationFBUserNotification( strtolower($this->params['pass']), $ConfirmationLink );
                
            $_SESSION['_reg_user'] = array(
                'login'  => $this->params['login'],
                'email'  => $this->params['email'],
                'user'   => $user
            );
            $url = 'http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/registration/completed/mode/facebook/';
            
            if ( WP_SSO_ENABLED && Warecorp_Wordpress_SSO::isWordpressSiteEnabled() ) {
                $code = md5(uniqid(mt_rand(), true));
                $cache = Warecorp_Cache::getFileCache();
                $cache->save($user->getId(), 'SSO_'.$code, array(), Warecorp_Wordpress_SSO::LIFETIME);
                $this->_redirect(WP_SSO_URL.'?zssodoaction=signup&auth=1&key='.$code.'&ret='.urlencode($url));
            } else {
                $this->_redirect($url);
            }
        } else {
            /**
             * Send message
             */
            if (!defined('WHO_APPROVE_USER_ACCOUNT')) {
                
                $ConfirmationLink = (DIRECT_ACTIVATION == 'on') ? '': Warecorp::getTinyUrl(BASE_URL.'/'.LOCALE.'/registration/index/code/'.$user->getRegisterCode().'/', HTTP_CONTEXT);
                $user->sendRegistrationNotification( $ConfirmationLink );
        
                $_SESSION['_reg_user'] = array(
                'login'  => $this->params['login'],
                'email'  => $this->params['email']);
                $url = 'http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/registration/completed/';

                if ( WP_SSO_ENABLED && Warecorp_Wordpress_SSO::isWordpressSiteEnabled() ) {
                    $code = md5(uniqid(mt_rand(), true));
                    $cache = Warecorp_Cache::getFileCache();
                    $cache->save($user->getId(), 'SSO_'.$code, array(), Warecorp_Wordpress_SSO::LIFETIME);
                    $this->_redirect(WP_SSO_URL.'?zssodoaction=signup&key='.$code.'&ret='.urlencode($url));
                } else {
                    $this->_redirect($url);
                }
                
            } else { //activation by admin
                $user->sendRegistrationNotificationToAdmin();
        
                $_SESSION['_reg_user'] = array(
                'login'  => $this->params['login'],
                'email'  => $this->params['email']);
                $url = 'http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/registration/sentforapprove/';
                
                if ( WP_SSO_ENABLED && Warecorp_Wordpress_SSO::isWordpressSiteEnabled() ) {
                    $code = md5(uniqid(mt_rand(), true));
                    $cache = Warecorp_Cache::getFileCache();
                    $cache->save($user->getId(), 'SSO_'.$code, array(), Warecorp_Wordpress_SSO::LIFETIME);
                    $this->_redirect(WP_SSO_URL.'?zssodoaction=signup&key='.$code.'&ret='.urlencode($url));
                } else {
                    $this->_redirect($url);
                }
            }
        }
    }
    /**
     * +-------------------------------------------------------------
     * |
     * |    Handle : FORM VIEW
     * | 
     * +-------------------------------------------------------------
     */
    else {
        $this->view->form = $form;
    }

    /**
     * Create captcha
     */
    $captcha = new b2evo_captcha($CAPTCHA_CONFIG);
    $imgLoc = $captcha->get_b2evo_captcha();
    $_SESSION['imgLoc'] = $imgLoc;

    /**
     * Assign var to Smarty
     */
    $this->view->countries = Warecorp_Location::getCountriesListAssoc(true);   
    $this->view->countryId = $this->params["countryId"];
    $this->view->city = $this->params["city"];
    $this->view->zipcode = $this->params["zipcode"];
    $this->view->agree = $this->params['agree'];
    $this->view->age_agree = $this->params['age_agree'];
    $this->view->cityQuerySelected = $this->params['cityQuerySelected'];
    $this->view->cityAliasSelected = $this->params['cityAliasSelected'];
    $this->view->city_correct = $this->params["city_correct"];   
    $this->view->lstCities = $lstCities;
    $this->view->strRECOGNIZEDCity = $strRECOGNIZEDCity;
    $this->view->strRECOGNIZEDZip = $strRECOGNIZEDZip;
    $this->view->needApproveCity = $needApproveCity;
    $this->view->REGISTRATION_CAPTCHA = REGISTRATION_CAPTCHA;
    $this->view->verifyImage = $imgLoc;
    $this->view->genderArray = array('male' => 'Male', 'female'=>'Female', 'unselected'=>' ');
    $this->view->newuser = $this->params;
    $this->view->bodyContent = $_template;
    $this->view->facebookId = $facebookId;
