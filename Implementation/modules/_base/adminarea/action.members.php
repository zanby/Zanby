<?php
    Warecorp::addTranslation('/modules/adminarea/action.members.php.xml');
    $this->_page->Xajax->registerUriFunction("changeCountry", "/ajax/changeCountry/");
    $this->_page->Xajax->registerUriFunction("changeState",   "/ajax/changeState/");
    $items_per_page = 10;
    $privilegesErr=false;
    $this->params['page'] = isset($this->params['page'])?$this->params['page']:1;
    
    if ( isset($this->params['ajax_mode']) ) {
        $objResponse = new xajaxResponse();
        switch ( $this->params['ajax_mode'] ) {
            case 'activate' :
                if ( isset($this->params['members']) && trim($this->params['members']) ) {
                    $members = explode(',', $this->params['members']);
                    if ( sizeof($members) ) {
                        foreach ( $members as $memberID ) {
                            $objUser = new Warecorp_User('id', $memberID);
                            if ( $objUser && $objUser->getId() && $objUser->getStatus() != 'deleted' ) {                                
                                $objUser->saveStatus('active');
                            }
                        }
                    }
                }
                $objResponse->addScript('document.location.reload();');                
                break;
            case 'block' :
                if ( isset($this->params['members']) && trim($this->params['members']) ) {
                    $members = explode(',', $this->params['members']);
                    if ( sizeof($members) ) {
                        foreach ( $members as $memberID ) {
                            $objUser = new Warecorp_User('id', $memberID);
                            if ( $objUser && $objUser->getId() && $objUser->getStatus() != 'deleted' ) {
                                $objUser->saveStatus('blocked');
                            }
                        }
                    }
                }
                $objResponse->addScript('document.location.reload();');                
                break;
            case 'delete' :
                if ( isset($this->params['members']) && trim($this->params['members']) ) {
                    $members = explode(',', $this->params['members']);
                    if ( sizeof($members) ) {
                        foreach ( $members as $memberID ) {
                            $objUser = new Warecorp_User('id', $memberID);
                            if ( $objUser && $objUser->getId() && $objUser->getStatus() != 'deleted' && $objUser->getId() != 1 ) {
                                $objUser->delete();
                            }
                        }
                    }
                }
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->close($objResponse);
                $objResponse->addScript('document.location.reload();');                
                break;
        }
        $objResponse->printXml($this->_page->Xajax->sEncoding); exit();
    }
    
    if ( isset($this->params['id']) ) {
        
        $this->_page->Xajax->registerUriFunction("detectCountry", "/ajax/detectCountry/");
        $this->_page->Xajax->registerUriFunction("autoCompleteCity", "/ajax/autoCompleteCity/");
        $this->_page->Xajax->registerUriFunction("autoCompleteZip", "/ajax/autoCompleteZip/");
                
    	$user = new Warecorp_User('id', $this->params['id']);
    	$userAdmin = new Warecorp_Admin();
    	$userAdmin->loadById($user->getId());
    	$form = new Warecorp_Form('udForm', 'POST', $this->admin->getAdminPath('members').'/id/'.$user->getId());
    	$template = 'adminarea/member.tpl';	
    	
    	if ( $form->isPostback() ) {
    		$loginParams = array('login'=>$this->params['login'], 'excludeIds' => array($user->getId()));
    		$emailParams = array('email'=>$this->params['email'], 'excludeIds' => array($user->getId()));
    		$change_pass = false;
    		
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
    		$form->addRule('gender',       'required',  Warecorp::t('Choose please Gender'));
    		
            $form->addRule('countryId',     'nonzero', Warecorp::t('Choose country'));
            $form->addRule('city',          'callback',  Warecorp::t('Enter please City'),
                array(
                    'func' => 'Warecorp_Form_Validation::isCityRequired',
                    'params' => array(
                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                        'city' => ((isset($this->params['city'])) ? $this->params['city'] : null)
                    )
                )
            );
            $form->addRule('city',          'callback',      Warecorp::t('City name is incorrect. Choose it from autocomplete list.'),
                array(
                    'func' => 'Warecorp_Form_Validation::isCityInvalid',
                    'params' => array(
                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                        'city' => ((isset($this->params['city'])) ? $this->params['city'] : null)
                    )
                )
            );
            $form->addRule('zipcode',       'callback',      Warecorp::t('Enter please Zip code'),
                array(
                    'func' => 'Warecorp_Form_Validation::isZipcodeRequired',
                    'params' => array(
                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                        'zipcode' => ((isset($this->params['zipcode'])) ? $this->params['zipcode'] : null)
                    )
                )
            );
            $form->addRule('zipcode',       'callback',      Warecorp::t('Zip code is incorrect. Choose it from autocomplete list.'),
                array(
                    'func' => 'Warecorp_Form_Validation::isZipcodeInvalid',
                    'params' => array(
                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                        'zipcode' => ((isset($this->params['zipcode'])) ? $this->params['zipcode'] : null)
                    )
                )
            );
    		
    		$form->addRule('timezone',     'required',  Warecorp::t('Choose please Timezone'));
    		$form->addRule('login',        'required',  Warecorp::t('Enter please User ID'));
    		$form->addRule('login',        'callback',  Warecorp::t('User ID (login) already exist'), array('func' => 'Warecorp_Form_Validation::isNewLoginExist', 'params' => $loginParams));
    		$form->addRule('login',        'maxlength', Warecorp::t('Login Name too long (max 50)'), array('max' => 50));
    		$form->addRule('login',        'alphanumeric',  Warecorp::t('Use a-z 0-9 for username'));
    		if (!(empty($this->params['new_pass']))) {
    			$form->addRule('new_pass',         'minlength', Warecorp::t('Minimum password length is six characters'), array('min' => 6));
    			$form->addRule('new_pass',         'maxlength', Warecorp::t('New Password too long (max 50)'), array('max' => 50));
    			$change_pass = true;
    		}
    		$form->addRule('email',        'required',  Warecorp::t('Enter please Email Address'));
    		$form->addRule('email',        'email',     Warecorp::t('Enter please correct Email Address'));
    		$form->addRule('email',        'callback',  Warecorp::t('Email address already exist'), array('func' => 'Warecorp_Form_Validation::isNewUserEmailExist', 'params' => $emailParams));
    		$form->addRule('email',        'maxlength', Warecorp::t('Email too long (max 255)'), array('max' => 255));		
        	
    		if( ($this->admin->getRole()=='superadmin' || $userAdmin->getStatus()!=='admin') ) {
        		$this->changeField('First_name',$user->getFirstname(),$this->params['firstname']);
        		$user->setfirstname($this->params['firstname']);
        		
        		$this->changeField('Last_name',$user->getLastname(),$this->params['lastname']);
        		$user->setLastname($this->params['lastname']);
        		
        		$this->changeField('Gender',$user->getGender(),$this->params['gender']);
        		$user->setGender($this->params['gender']);
        		
        		$this->changeField('Birth_day',$user->getBirthday(), date("Y-m-d",mktime(0,0,0,$this->params['birthday']['date_Month'],$this->params['birthday']['date_Day'],$this->params['birthday']['date_Year'])));
        		$user->setBirthday($this->params['birthday']['date_Year'].'-'.$this->params['birthday']['date_Month'].'-'.$this->params['birthday']['date_Day']);
    			
       			$this->changeField('Is_birthday_private',$user->getIsBirthdayPrivate()=='1'?'Yes':'No',isset($this->params['is_birthday_private']) ? 'Yes' : 'No');
        		$user->setIsBirthdayPrivate(isset($this->params['is_birthday_private']) ? '1' : '0');
    			
       			$this->changeField('Is_gender_private',$user->getIsGenderPrivate()=='1'?'Yes':'No',isset($this->params['is_gender_private']) ? 'Yes' : 'No');
       			$user->setIsGenderPrivate(isset($this->params['is_gender_private']) ? '1' : '0');
    			
        		$this->changeField('Time_zone',$user->getTimezone(),$this->params['timezone']);
    			$user->setTimezone($this->params['timezone']);
    			
        		$this->changeField('Login',$user->getLogin(),$this->params['login']);
    			$user->setLogin(trim($this->params['login']));
    
        		$this->changeField('Email',$user->getEmail(),$this->params['email']);
    			$user->setEmail($this->params['email']);
    			
        		$this->changeField('Status',$user->getStatus(),$this->params['status']);
    			$user->setStatus($this->params['status']);
        	}
    		if( $this->admin->getRole()=='superadmin' ){
        		$this->changeField('Admin_status',$userAdmin->getStatus(),$this->params['admin_status']);
    			$userAdmin->setStatus($this->params['admin_status']);
    			if($userAdmin->getStatus()=='admin'){
        			$this->changeField('Admin_role',$userAdmin->getRole(),isset($this->params['admin_role'])?$this->params['admin_role']:'simpleadmin');
    			}
    			$userAdmin->setRole(isset($this->params['admin_role'])?$this->params['admin_role']:'simpleadmin');	
    		} elseif( $this->params['admin_status']=='admin' ) {
    			$privilegesErr = true;
    		}
    		
    		if ( $form->validate($this->params) ){
    		    if ($change_pass) {
        			$this->changeField('Password','***','* **');
    		    	$user->setPass(md5($this->params['new_pass']));
        		}
        		    		
                //  Prepare location to save
                $oldcityname = $user->getCity()->name;
                $country = Warecorp_Location_Country::create($this->params['countryId']);
                if ( $this->params['countryId'] == 1 || $this->params['countryId'] == 38 ) {
                    if ( strpos($this->params['zipcode'], ",") ) $locationInfo = $country->getZipcodeByACFullInfo($this->params['zipcode']);
                    else $locationInfo = $country->getZipcodeByACInfo($this->params['zipcode']);
                    $user->setZipcode  ( $locationInfo['zipcode'] );
                    $user->setCityId   ( $locationInfo['city_id'] );
                } else {
                    $locationInfo = $country->getCityByACInfo($this->params['city']);
                    $user->setZipcode  ( '' );
                    $user->setCityId   ( $locationInfo['city_id'] );
                }
                $city = Warecorp_Location_City::create($locationInfo['city_id']);
                $user->setLatitude( $city->getLatitude() );
                $user->setLongitude( $city->getLongitude() );
                $this->changeField('City', $oldcityname, $city->name);
        		   
        		if( ($this->admin->getRole()=='superadmin' || $userAdmin->getStatus()!=='admin') ){
                    if( $user->getStatus() === Warecorp_User_Enum_UserStatus::USER_STATUS_DELETED ) {
        			    $user->delete();
                    } else {
                        $user->save();
                    }
        			if( $this->admin->getRole()=='superadmin' ) $userAdmin->saveRole();
    		        // save LOG
        			$this->appendLog('members',$this->params['id'],'edit'); 
        		} else $privilegesErr = true;
    
        		if( $privilegesErr ) {
        			$form->addCustomErrorMessage(Warecorp::t("You must have SuperAdmin's privilegies"));
        		}
    		}
    	} else {
            $this->params['countryId'] = $user->getCountry()->id;
            $this->params['city'] = $user->getCity()->name.', '.$user->getState()->name;
            $this->params['zipcode'] = $user->getZipcode().', '.$user->getCity()->name;	
    	}
    	
        $countries = Warecorp_Location::getCountriesListAssoc(true);
        $this->view->countries = $countries;
        
    	$this->view->user = $user;
    	$this->view->form = $form;
    	$this->view->useradmin = $userAdmin;
    	$this->view->memberID = $user->getId();
    	
        $this->view->countryId = $this->params['countryId'];
        $this->view->cityStr = $this->params['city'];
        $this->view->zipStr = $this->params['zipcode'];
        
//    	$country = $this->params['country'];
//    	$state = $this->params['state'];
//    	$city = $this->params['city'];		
    	
//    	$countries = Warecorp_Location::getCountriesListAssoc(true);
//    	$country = Warecorp_Location_Country::create($country);
//    	$states = $country->getStatesListAssoc(true);
//    	$state = Warecorp_Location_State::create($state);
//    	$cities = $state->getCitiesListAssoc(true);
    	$timezones = new Warecorp_Location_Timezone();
    	$this->view->time_zones = $timezones->getZanbyTimezonesNamesAssoc();
    	
//    	$this->view->countries = $countries;
//    	$this->view->states = $states;
//    	$this->view->cities = $cities;
    	$this->view->genderArray = array('male' => 'Male', 'female'=>'Female', 'unselected'=>' ');
    		
    	$this->view->statuses = Warecorp_User_Enum_UserStatus::getAllStatuses(true);	
    	$this->view->adminstatuses = array('user' => 'User', 'admin'=>'Admin');	
    	$this->view->adminroles = array('simpleadmin' => 'Simple Admin', 'groupadmin' => 'Group Admin','superadmin' => 'Super Admin');	
    	
//    	$this->view->country = $this->params['country'];
//    	$this->view->state =   $this->params['state'];
//    	$this->view->city =    $this->params['city'];	
    } else {

    	$form = new Warecorp_Form('sForm', 'POST', $this->admin->getAdminPath('members'));
    	$membersList = new Warecorp_User_List();
    	$search = ""; $order="";
    	if ( !empty($this->params['keyword']) ) {
    		$membersList->addWhere('zua.login like "%'.$this->params['keyword'].'%"');
    		$search ='/keyword/'.$this->params['keyword'];
    		$this->view->keyword = $this->params['keyword'];
    	}
    	$order = isset($this->params['order'])?$this->params['order']:'login';
    	$direction = isset($this->params['direction'])?$this->params['direction']:'asc';
    	$membersList->setOrder('zua.'.$order.' '.$direction);

    	$this->view->order = $order;
    	$this->view->direction = $direction;
    	$this->view->search = $search;
    	
    	$sort = '/order/'.$order.'/direction/'.$direction;
    	
    	$membersList->setStatus(Warecorp_User_Enum_UserStatus::getAllActiveStatuses())->setCurrentPage($this->params['page'])->setListSize($items_per_page);		
    	$url = $this->admin->getAdminPath('members').$search.$sort;
    	$P = new Warecorp_Common_PagingProduct($membersList->getCount(), $items_per_page, $url);	
    	$this->view->form = $form;
    	$this->view->paging = $P->makePaging(intval($this->params['page']));
    	$this->view->membersList = $membersList->getList();
    	$template = 'adminarea/members.tpl';
    }
        
    $this->view->bodyContent = $template;
