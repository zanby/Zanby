<?php

require_once WARECORP_DIR.'Console/OptionalProgressBar.php';

// @todo these function should be methods in Warecorp_Location class - Konstantin Stepanov
function getStateIdByCountryIdAndName($country_id, $state)
{
    $db = Zend_Registry::get("DB");
    $query = "select id from zanby_location__states where country_id = ? and (name = ? or code = ?)";
    $stateId = $db->fetchOne($query, array((int)$country_id, $state, $state));
    return $stateId;
}

function getCityIdByStateIdAndName($state_id, $city)
{
    $db = Zend_Registry::get("DB");
    $query = "select id from zanby_location__cities where state_id = ? and name = ?";
    $cityId = $db->fetchOne($query, array((int)$state_id, $city));
    return $cityId;
}

function getCityIdByCountryIdAndName($country_id, $city)
{
    $db = Zend_Registry::get("DB");
    $query = "select cities.id from zanby_location__cities cities, zanby_location__states states
	where cities.state_id = states.id and states.country_id = ? and cities.name = ? limit 1";
    $cityId = $db->fetchOne($query, array((int)$country_id, $city));
    return $cityId;
}

function getAnyCityIdByCountryId($country_id)
{
    $db = Zend_Registry::get("DB");
    $query = "select cities.id from zanby_location__cities cities, zanby_location__states states
	where cities.state_id = states.id and states.country_id = ? limit 1";
    $cityId = $db->fetchOne($query, array((int)$country_id));
    return $cityId;
}

function importMembersParseCSVForErrors( $imp, $params = array())
{
    if ( !( $imp instanceof Warecorp_Import_Members)) {
            throw new Zend_Exception( 'First argument should be instance of Warecorp_Import_Members.');
        }
    if ( !is_array( $params)) {
        throw new Zend_Exception('Params must be an array.');
    }

    if ( isset( $params['progressbar'])) {
        $bar = new OptionalProgressBar( $params['progressbar'],
                                        'Parsing CSV file: [%bar%] %percent% ETA: %estimate%',
                                        '=>', '-', $params['width'], $params['progressbar_size']);
        $bar_percentage = 0;
    }
    $imp->setJoinCol(isset($params['is_join_col'])?true:false);
    $imp->open5();
    $imp->getHead();
    while (!$imp->inEOF()){
        if ( isset( $params['progressbar'])) {
            $bar->update( $bar_percentage++);
        }
        $loadstr = $imp->readNext();
        $onestr = $imp->getOneStr();
        if(strlen($onestr)<2) continue;
        $imp->incRowNum();
        $imp->parseStr(true);
        //
        //email;first_name;last_name;login;password;gender;birthday;country;state;city;zip
        //
        // undefined fields filled by default values
        
        // If country id is not set in csv file but country name is present, we are trying to get country id from country name
        //@author Komarovski
        if(!$imp->getStrField('country_id')){

            if ($imp->getStrField('country')) {
                $_countrytmp = new Warecorp_Location_Country;
                $_countrytmp->pkColName = 'name';
                $_countrytmp->loadByPk($imp->getStrField('country'));
                $_countrytmp->pkColName = 'id';

                if (!empty($_countrytmp->id)) {
                    $imp->setStrField('country_id',$_countrytmp->id);
                }
            }
        }
        ////

        if(!$imp->getStrField('country_id')){
            $imp->setStrField('country_id',$params['country']);
        }

        if (!$imp->getStrField('state_id'))
        {
            if ($imp->getStrField("state"))
            {
                $_stateidtmp = getStateIdByCountryIdAndName($imp->getStrField("country_id"), $imp->getStrField("state"));
                $imp->setStrField("state_id", $_stateidtmp);
            }
        }
		 
        if (!$imp->getStrField('city_id'))
        {
            $_cityidtmp = 0;

            if ($imp->getStrField("city"))
            {
                if ($imp->getStrField("state_id"))
                    $_cityidtmp = getCityIdByStateIdAndName($imp->getStrField("state_id"), $imp->getStrField("city"));

                if (!$_cityidtmp)
                    $_cityidtmp = getCityIdByCountryIdAndName($imp->getStrField("country_id"), $imp->getStrField("city"));
            }

            if (!$_cityidtmp)
                $_cityidtmp = getAnyCityIdByCountryId($imp->getStrField("country_id"));

            $imp->setStrField("city_id", $_cityidtmp);
        }

/*
  if ($imp->getStrField('email') == "ben.wertz@gmx.de")
  {
  echo("<pre style='text-align: left;'>");
  echo("country_id: ".$imp->getStrField("country_id")."<br />\n");
  echo("state_id: ".$imp->getStrField("state_id")."<br />\n");
  echo("city_id: ".$imp->getStrField("city_id")."<br />\n");
  echo("country: ".$imp->getStrField("country")."<br />\n");
  echo("state: ".$imp->getStrField("state")."<br />\n");
  echo("city: ".$imp->getStrField("city")."<br />\n");
  echo("</pre>");
  }
*/

        // If city id is not set in csv file and country is not USA or Canada, we are trying to get random city it, because we can't identify city by zipcode for non-USA or non-Canada countries
        //@author Komarovski
        /* if(!$imp->getStrField('city_id') && $imp->getStrField('country_id') != 1 && $imp->getStrField('country_id') != 38 && $imp->getStrField('city')){

           $_cityidtmp = new Warecorp_Location_City($imp->getStrField('country_id'));
           if (!empty($_cityidtmp)) {
           $imp->setStrField('city_id',$_cityidtmp);
           }
           } */
        ////

        if(!$imp->getStrField('city_id')){
            $imp->setStrField('city_id',$params['city']);
        }

        $imp->setStrField('is_gender_private',isset($params['is_gender_private'])?'1':'0');
        $imp->setStrField('is_birthday_private',isset($params['is_birthday_private'])?'1':'0');

        if(!$imp->getStrField('zip')){
            $imp->setStrField('zip',isset($params['zipcode'])?$params['zipcode']:'');
        } else {
        	
        	//$_zipcode = Warecorp_Location_Zipcode::createByZip($imp->getStrField('zip'));
        	//if (!$_zipcode->id)	$imp->addErr('zip');
        }
        
        
        // e-mail is important !
        $email = $imp->getStrField('email');
        $mail_ok=false;
        if(!$email || empty($email)) {
            $imp->addErr('email');
        } elseif (strlen($email)>255) {
            $imp->addErr('email');
        } else {
            $regex = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/';
            if (preg_match($regex, $email)) {
                if (function_exists('checkdnsrr')) {
                    $tokens = explode('@', $email);
                    //	                	if (!(checkdnsrr($tokens[1], 'MX') || checkdnsrr($tokens[1], 'A'))) $imp->addErr('email');
                    //                        else $mail_ok=true;
                    $mail_ok=true;
                    //vsv
                }
            } else $imp->addErr('email');
            $imp->setOnlyJoin(false);
            if($mail_ok) {    /* mail_ok */
                //                    if($imp->isJoinCol() && !$imp->isEmailExist($email)) {
                //                        $imp->writeMx($email);
                //                    }
                if(Warecorp_Form_Validation::isUserEmailExist($email) || $imp->isEmailExist($email)) {
                    if(!$imp->isJoinCol() || $imp->isEmailExist($email)){
                        $imp->addErr('email', 'E-mail already exist');
                    } else {
                        $imp->setOnlyJoin(true);
                    }
                } else {
                    // email correct
                    // Login = first part of email
                    // todo: if Login busy - add numbers
                    // Warecorp_Form_Validation::isNewLoginExist() ****
                    if(!$imp->getStrField('login')) {
                        $_at = strpos($email,'@');
                        if($_at!==false) {
                            $login = substr($email,0,$_at);
                            $login = str_replace('.','',$login);
                            $login = str_replace('_','',$login);
                            $login = str_replace('-','',$login);
                            //							$imp->setStrField('login',$login);
                            if(!preg_match('/^[a-zA-Z0-9]+$/', $login)) {
                                $imp->addErr('email');
                            } else {
                                $imp->setStrField('login', $login);
                            }
                        }
                    } elseif (strlen($imp->getStrField('login'))>50 || !preg_match('/^[a-zA-Z0-9]+$/', $imp->getStrField('login'))) {
                        $imp->addErr('login');
                    }
                    $imp->freeLogin();

                    //first & last names get from login if not set
                    if(!$imp->getStrField('first_name')) {
                        $imp->setStrField('first_name', $imp->getStrField('login'));

                    } elseif (strlen($imp->getStrField('first_name'))>50 || !preg_match("/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/", $imp->getStrField('first_name'))) {
                        $imp->addErr('first_name');
                    }

                    if(!$imp->getStrField('last_name')) {
                        $imp->setStrField('last_name', $imp->getStrField('first_name'));

                    } elseif (strlen($imp->getStrField('last_name'))>50 || !preg_match("/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/", $imp->getStrField('last_name'))) {
                        $imp->addErr('first_name');
                    }
                }
            } /* mail_ok */
        }

        // check Birth Date

        $_birthdate = new Zend_Date();
        //		    $_birthdate->setTimezone($params['timezone']);
        if(!$imp->getStrField('birthday')) {
            $imp->setStrField('birthday', '1980-01-01');
        } else {
            $yyyymmdd = explode('-',$imp->getStrField('birthday'));
            $_errdate = true;
            if(count($yyyymmdd)===3 && preg_match("/[0-9\-]{10}/", $imp->getStrField('birthday'))) {
                if(checkdate($yyyymmdd[1],$yyyymmdd[2],$yyyymmdd[0])) {
                    $_birthdate->set($imp->getStrField('birthday'),Zend_Date::ISO_8601);
                    if (substr($_birthdate->getIso(),0,10)===$imp->getStrField('birthday')) {
                        // date checked OK
                        $_errdate = false;
                    }
                }
            }
            if($_errdate) {
                $imp->addErr('birthday');
            }
        }

        $imp->setStrField('is_birthday_private', isset($params['is_birthday_private']) ? '1' : '0');
        $imp->setStrField('is_gender_private', isset($params['is_gender_private']) ? '1' : '0');
        // Random password
        if(!$imp->getStrField('password')) {
            $length = 10;
            $allowable_characters = 'abcdefghijklmnopqrstuvwxyz23456789';
            $len = strlen($allowable_characters);
            mt_srand((double)microtime() * 1000000);
            $pass = '';
            for ($i = 0; $i < $length; $i++) {
                $pass .= $allowable_characters[mt_rand(0, $len - 1)];
            }
            $imp->setStrField('password', $pass);
        }
        if(!$imp->getStrField('gender')) {
            $imp->setStrField('gender', 'unselected');
        } elseif (!in_array($imp->getStrField('gender'), array('male','female','unselected') )) {
            $imp->addErr('gender');
        }
        

        if($imp->isJoinCol() && !$imp->isErr() && $mail_ok && !$imp->isEmailExist($email)) {
            $imp->writeMx($email);
        }
        $imp->writeRes();
    }
    if ( isset( $params['progressbar'])) {
        $bar->erase( true);
    }
}

function importMembersAddUsers( $imp, $params)
{
    if ( !( $imp instanceof Warecorp_Import_Members)) {
            throw new Zend_Exception( 'First argument should be instance of Warecorp_Import_Members.');
        }

    if ( !is_array( $params)) {
        throw new Zend_Exception('Params must be an array.');
    }

    if ( isset( $params['progressbar'])) {
        $bar = new OptionalProgressBar( $params['progressbar'],
                                        'Importing users: [%bar%] %percent% ETA: %estimate%',
                                        '=>', '-', $params['width'], $params['progressbar_size']);
        $bar_percentage = 0;
    }

    $imp->openRes();
    $imp->getHeadRes();
    $imported_time = date('Y-m-d H:i:s');
    while (!$imp->resEOF()){
        if ( isset( $params['progressbar'])) {
            $bar->update( $bar_percentage++);
        }
        $loadstr = $imp->readNextRes();
        $onestr = $imp->getOneStr();
        if(strlen($onestr)<2) continue;
        $imp->incRowNum();
        $imp->parseStr();

        if(!$imp->isLoginExist($imp->getStrField('login'))) {
            
            $user = new Warecorp_User();
            $country = Warecorp_Location_Country::create($imp->getStrField('country_id'));

            if ( $imp->getStrField('country_id') == 1 || $imp->getStrField('country_id') == 38 || $imp->getStrField('congressional_district')) {
                //MSQ FOR USA ONLY
            	if ($imp->getStrField('congressional_district')) {
            		$imp->setStrField('country_id', 1);
                    $country = Warecorp_Location_Country::create($imp->getStrField('country_id'));
                }
            	$locationInfo = $country->getZipcodeByACInfo($imp->getStrField('zip'));
                //print_r($locationInfo);die;
                if ($locationInfo && isset($locationInfo['zipcode']) && isset($locationInfo['city_id']))
                {
                    $user->setZipcode($locationInfo['zipcode']);
                    $user->setCityId($locationInfo['city_id']);
                } else {
                    $user->setZipcode($imp->getStrField('zip')? $imp->getStrField('zip'): '');
                    $user->setCityId($imp->getStrField('city_id'));
                }
            } else {
                //Komarovski
                $user->setCityId($imp->getStrField('city_id'));
                if ($imp->getStrField('zip')) {
                    $user->setZipcode($imp->getStrField('zip'));
                } else {
                    $user->setZipcode('');
                }
            }
            $user->setFirstname($imp->getStrField('first_name'))
                ->setLastname($imp->getStrField('last_name'))
                ->setBirthday($imp->getStrField('birthday'))
                ->setIsBirthdayPrivate($imp->getStrField('is_birthday_private'))
                ->setGender($imp->getStrField('gender'))
                ->setIsGenderPrivate($imp->getStrField('is_gender_private'))
                //->setAdultFilter(0)
                ->setLogin($imp->getStrField('login'))
                ->setPass(md5(strtolower($imp->getStrField('password'))))
                ->setEmail($imp->getStrField('email'))
                ->setCalendarPrivacy(1)
                ->setContactMode(16)
                ->setRegisterDate(date('Y-m-d H:i:s'))
                ->setImportedUser($imported_time)
                ->setMembershipPlan('premium');

            //                    ->setImportedUser(1)
            if ($imp->getStrField('phone')) $user->getProfile()->setPhone($imp->getStrField('phone'));
            if ($imp->getStrField('congressional_district')) {
            	$_district_number = intval(substr($imp->getStrField('congressional_district'), 2));
            	$_state_abbr = substr($imp->getStrField('congressional_district'), 0, 2);
            	
                $user->getProfile()->setDistrictState($_state_abbr)->setDistrictNumber($_district_number);	
            }
            
            $user->setTimezoneFromCity();
            $city = Warecorp_Location_City::create($imp->getStrField('city_id'));
            $user->setLatitude($city->getLatitude())
                ->setLongitude($city->getLongitude());
            // USER save

            if($params['activate_now']==0) {
                // activate now
                $user->setStatus(Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);

                //MSQ		        	$user->save();
                //print_r($user);die; 
                $user->save();

                if( $params['send_notifications'] == 1 ) {
                    $user->sendUserImportedNotification();
                }

            } else {
                // todo delete elseif
                // send confirmation mail
                $user->setStatus(Warecorp_User_Enum_UserStatus::USER_STATUS_PENDING);
                //print_r($user->getCityId().' ');continue;die; 
                $user->save();
                $mail_confirm_url = $user->getRegisterCode();
                //  Send message
                $mail = new Warecorp_Mail_Template('template_key', 'USER_REGISTER');
                $sender_object = new Warecorp_User();
                $mail->setSender($sender_object);
                $mail->addRecipient($user);
                $mail->send();

            }

            /**
             * Events : Проверяем, если были attendee с таким email - делаем их для данного пользователя
             */
            //		        Warecorp_ICal_Attendee_List::updateAttendeeForNewUser($user);
            //		        Warecorp_User_Friend_Request_List::createRequestsForNewUser($user);
            //JOIN TO GROUPS
        }
        // exist user
    }
    $imp->closeRes();
    if ( isset( $params['progressbar'])) {
        $bar->erase(true);
    }
}

function importMembersJoinGroups( $imp, $params = array())
{
    if ( !( $imp instanceof Warecorp_Import_Members)) {
            throw new Zend_Exception( 'First argument should be instance of Warecorp_Import_Members.');
        }

    if ( !is_array( $params)) {
        throw new Zend_Exception( 'Params must be an array.');
    }

    if ( isset( $params['progressbar'])) {
        $bar = new OptionalProgressBar( $params['progressbar'],
                                        'Joining groups by users: [%bar%] %percent% ETA: %estimate%',
                                        '=>', '-', $params['width'], $params['progressbar_size']);
        $bar_percentage = 0;
    }

    $imp->openMx();
    $imp->getHeadMx();
    $all_added = 0;
    while (!$imp->mxEOF()) {
        if ( isset( $params['progressbar'])) {
            $bar->update( $bar_percentage++);
        }
        $loadstr = $imp->readNextMx();
        $onestr = $imp->getOneStr();
        if(strlen($onestr)<2) continue;
        $imp->parseMx();

        if(isset($params['group_names'])) {
            $groups = explode(',',$params['group_names']);
            $groups_ok = array();
            $all_added++;
            if($imp->addeduser->getId()>0) {
                foreach($groups as $value) {
                    $value = trim($value);
                    if($imp->isGroupSimple($value)) {
                        $groups_ok[] = $value;
                        $imp->joinToGroup($value);
                    }
                }
            }
        }

    }
    $imp->closeMx();

    if ( isset( $params['progressbar'])) {
        $bar->erase( true);
    }
    
    return $all_added;
}