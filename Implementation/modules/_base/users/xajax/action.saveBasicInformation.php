<?php
    Warecorp::addTranslation("/modules/users/xajax/action.saveBasicInformation.php.xml");

    if ( $this->currentUser->getId() !== $this->_page->_user->getId() ) {
        $this->_redirect($this->currentUser->getUserPath('profile'));
    }
    $objResponse = new xajaxResponse();
    $this->_page->setTitle(Warecorp::t('Accounts settings'));

    /**
     * Apply default values
     */
    $params['countryId']          = ( !isset($params['countryId']) )            ? 0     : $params['countryId'];
    $params['city']               = ( !isset($params['city']) )                 ? 0     : trim($params['city']);
    $params['cityQuerySelected']  = ( !isset($params['cityQuerySelected']) )    ? ''    : trim($params['cityQuerySelected']);
    $params['cityAliasSelected']  = ( !isset($params['cityAliasSelected']) )    ? ''    : $params['cityAliasSelected'];
    $params['city_correct']       = ( !isset($params['city_correct']) )         ? 0     : $params['city_correct'];
    $params['zipcode']            = ( !isset($params['zipcode']) )              ? ''    : trim($params['zipcode']);
    $params['firstname']          = ( !isset($params['firstname']) )            ? null  : trim($params['firstname']);
    $params['lastname']           = ( !isset($params['lastname']) )             ? null  : trim($params['lastname']);
    $params['userlocale']        = ( !isset($params['userlocale']) )             ? 'en'  : trim($params['userlocale']);

    /**
     * FORM : Create form and form rules
     * Add Rules into Form object
     */
    $form = new Warecorp_Form('biForm', 'post', 'javasript:void(0);');
    if (isset($params['_wf__biForm'])) $_REQUEST['_wf__biForm'] = $params['_wf__biForm'];
    /**
     * First Name
     */
    $form->addRule('firstname',     'required',     Warecorp::t('Enter please First Name'));
    $form->addRule('firstname',     'maxlength',    Warecorp::t('First Name too long (max %s)', 50), array('max' => 50));
    $form->addRule('firstname',     'minlength',    Warecorp::t('First Name is too short (min %s)', 2), array('min' => 2));
    $form->addRule('firstname',     'callback',     Warecorp::t('First Name contains incorrect characters'),
        array(
            'func'   => 'Warecorp_Form_Validation::isInvalidName',
            'params' => array( 'firstname' => ((isset($params['lastname'])) ? $params['firstname'] : ''))
        )
    );
    /**
     * Last Name
     */
    $form->addRule('lastname',      'required',     Warecorp::t('Enter please Last Name'));
    $form->addRule('lastname',      'maxlength',    Warecorp::t('Last Name too long (max %s)', 50), array('max' => 50));
    $form->addRule('lastname',      'minlength',    Warecorp::t('Last Name is too short (min %s)', 2), array('min' => 2));
    $form->addRule('lastname',      'callback',     Warecorp::t('Last Name contains incorrect characters'),
        array(
            'func'   => 'Warecorp_Form_Validation::isInvalidName',
            'params' => array( 'lastname' => ((isset($params['lastname'])) ? $params['lastname'] : ''))
        )
    );
    /**
     * Birthday date
     */
    /*
    $form->addRule('birthday',      'callback',     Warecorp::t('Enter please Birthday Date'),
        array(
            'func' => 'Warecorp_Form_Validation::isDateRequered',
            'params' => array(
                'year'  => ((isset($params['birthday']['date_Year']))   ? $params['birthday']['date_Year']  : null),
                'month' => ((isset($params['birthday']['date_Month']))  ? $params['birthday']['date_Month'] : null),
                'day'   => ((isset($params['birthday']['date_Day']))    ? $params['birthday']['date_Day']   : null)
            )
        )
    );
    */
    if ( $params['birthday']['date_Year'] || $params['birthday']['date_Month'] || $params['birthday']['date_Day'] ) {
        $form->addRule('birthday',      'validdate',    Warecorp::t('Enter valid date, please'), isset($params['birthday']) ? array('day' => $params['birthday']['date_Day'], 'month' => $params['birthday']['date_Month'], 'year' => $params['birthday']['date_Year']): '');
        $form->addRule('birthday',      'callback',     Warecorp::t('Registration denied before 18 years old'), array('func' => 'Warecorp_Form_Validation::isAge18Valid', 'params' => isset($params['birthday']) ? $params['birthday'] : ''));
    }
    /**
     * Country
     */
    $form->addRule('countryId',     'nonzero',      Warecorp::t('Choose please Country'));
    //$form->addRule('userlocale',     'nonzero',      Warecorp::t('Choose please Locale'));
    /**
     * City
     */
    $form->addRule('city',          'callback',     Warecorp::t('Enter please City'),
        array(
            'func' => 'Warecorp_Form_Validation::isCityRequired',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'city' => ((isset($params['city'])) ? $params['city'] : null)
            )
        )
    );
    /**
     * City, Custom city
     */
    $lstCities          = null;     // list of aliases allowed for current city query
    $objUserCountry     = null;     // user choosed country object
    $objUserCity        = null;     // user choosed city object
    $strRECOGNIZEDCity  = '';       // string name of recognized place
    $needApproveCity    = false;    // need show city confirmation box
    if ( $params['countryId'] && $params['countryId'] != 1 && $params['countryId'] != 38 ) {
        $objUserCountry = Warecorp_Location_Country::create($params['countryId']);
        if ( '' == $params['city'] ) {
            $params['cityQuerySelected']  = '';
            $params['cityAliasSelected']  = '';
            $params['city_correct']       = 0;
        }
        /**
         * Ajax validation was processed
         */
        elseif ( $params['cityQuerySelected'] == $params['city'] ) { // ajax validation was completed correctly
            if ( $params['cityAliasSelected'] ) {   //  alias was changed correctly - certian city was selected
                $objUserCity = Warecorp_Location_City::create($params['cityAliasSelected']);
                if ( null === $objUserCity->id ) $objUserCity = null;
                elseif ( $objUserCity->getState()->getCountry()->id !== $objUserCountry->id ) $objUserCity = null;
                else $strRECOGNIZEDCity = $objUserCity->name.', '.$objUserCity->getState()->name.', '.$objUserCity->getState()->getCountry()->name;
            } elseif ( $params['city_correct'] ) {  //  user checked - city I entered is correct
                $strRECOGNIZEDCity = $params['city'].', '.$objUserCountry->name;
            }
            $lstCityAliasIds = Warecorp_Location_Alias_List::detectAliasByQueryString($params['city'], $objUserCountry->id);
            $lstCities = $objUserCountry->findByCityNameOrIds($params['cityQuerySelected'], $lstCityAliasIds, ($objUserCity) ? $objUserCity->id : null);
            $lstCitiesSize = sizeof($lstCities);
            if ( $lstCitiesSize == 0 && $objUserCity ) $needApproveCity = false;
            elseif ( $lstCitiesSize == 0 && !$objUserCity ) $needApproveCity = true;
            elseif ( $lstCitiesSize == 1 && !$params['cityAliasSelected'] ) {
                $objUserCity = $lstCities[0];
                $strRECOGNIZEDCity = $objUserCity->name.', '.$objUserCity->getState()->name.', '.$objUserCity->getState()->getCountry()->name;
                $params['cityAliasSelected'] = $objUserCity->id;
                $lstCities = null;
                $needApproveCity = false;
            } else $needApproveCity = true;
        }
        /**
         * Ajax validation was not processed, new city value was submited
         */
        elseif ( '' != trim($params['city'])) {
            $params['cityQuerySelected']  = $params['city'];
            $params['cityAliasSelected']  = '';
            $params['city_correct']       = 0;

            $lstCityAliasIds = Warecorp_Location_Alias_List::detectAliasByQueryString($params['cityQuerySelected'], $objUserCountry->id);
            $lstCities = $objUserCountry->findByCityNameOrIds($params['cityQuerySelected'], $lstCityAliasIds);
            $lstCitiesSize = sizeof($lstCities);
            if ( $lstCitiesSize == 0 ) $needApproveCity = true;
            elseif ( $lstCitiesSize == 1 ) {
                $objUserCity = $lstCities[0];
                $strRECOGNIZEDCity = $objUserCity->name.', '.$objUserCity->getState()->name.', '.$objUserCity->getState()->getCountry()->name;
                $params['cityAliasSelected'] = $objUserCity->id;
                $lstCities = null;
                $needApproveCity = false;
            } else $needApproveCity = true;
        }
    }
    $form->addRule('city',         'callback',      Warecorp::t('Please specify certain city.'),
        array(
            'func' => 'Warecorp_Form_Validation::isCityOrCustomValid',
            'params' => array(
                'countryId'         => $params['countryId'],
                'objUserCity'       => $objUserCity,
                'city_correct'      => $params['city_correct']
            )
        )
    );
    /**
     * Zip Code
     */
    $form->addRule('zipcode',       'callback',      Warecorp::t('Enter please Zip code'),
        array(
            'func' => 'Warecorp_Form_Validation::isZipcodeRequired',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'zipcode' => ((isset($params['zipcode'])) ? $params['zipcode'] : null)
            )
        )
    );
    $form->addRule('zipcode',       'callback',      Warecorp::t('Sorry, zip code was not recognized. Please, enter another one.'),
        array(
            'func' => 'Warecorp_Form_Validation::isCleanZipcodeInvalid',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'zipcode' => ((isset($params['zipcode'])) ? $params['zipcode'] : null)
            )
        )
    );
    $strRECOGNIZEDZip = false;
    if ( $params['countryId'] && ($params['countryId'] == 1 || $params['countryId'] == 38) ) {
        $objUserCountry = Warecorp_Location_Country::create($params['countryId']);
        if ( $objUserCountry->checkZipcode($params['zipcode']) ) $strRECOGNIZEDZip = true;
    }
    /**
     * Gender
     */
    $form->addRule('gender',        'required',     Warecorp::t('Choose please Gender'));
    /**
     * Timezone
     */
    $form->addRule('timezone',      'required',     Warecorp::t('Choose please Timezone'));
    /**
     * Handle : SAVE BASIC INFORMATION FORM PROCESSING
     */
    if ( $form->validate($params) ) {
        $user = $this->currentUser;

        $objUserCountry = Warecorp_Location_Country::create($params['countryId']);
        if ( $params['countryId'] == 1 || $params['countryId'] == 38 ) {
            $objZip = Warecorp_Location_Zipcode::createByZip($params['zipcode']);
            $user->setCityId($objZip->getCity()->id);
            $user->setZipcode($params['zipcode']);
        } else {
            if ( $objUserCity || $params['city_correct'] ) {
                if ( $params['city_correct'] ) {
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
                    if ( null === $objUserCity = Warecorp_Location_City::findByName($params['city'], $objUserState) ) {
                        $objUserCity = new Warecorp_Location_City();
                        $objUserCity->stateId   = $objUserState->id;
                        $objUserCity->name      = $params['city'];
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
        $user->setFirstname         ($params['firstname']);
        $user->setLocale            ($params['userlocale']);
        $user->setLastname          ($params['lastname']);
        $user->setGender            ($params['gender']);
        $user->setIsGenderPrivate   (isset($params['is_gender_private']) ? '1' : '0');
        $user->setIsBirthdayPrivate (1);
        $user->setTimezone          ($params['timezone']);
        if ( $params['birthday']['date_Year'] && $params['birthday']['date_Month'] && $params['birthday']['date_Day'] ) {
            $user->setBirthday      ($params['birthday']['date_Year'].'-'.$params['birthday']['date_Month'].'-'.$params['birthday']['date_Day']);
        } else {
        	$user->setBirthday      (new Zend_Db_Expr('NULL'));
        }
        $user->setIsBirthdayPrivate (isset($params['is_birthday_private']) ? '1' : '0');

        /**
         * get coords
         */
        $city = Warecorp_Location_City::create($user->getCityId());
        $user->setLatitude($city->getLatitude())->setLongitude($city->getLongitude());

        $user->save();
        $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));
        
        if ( FACEBOOK_USED ) {
            $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");     
            if ( !$cache->load('timer_'.$user->getId()) ) {
                $paramsFB = array(
                    'title' => htmlspecialchars($user->getLogin()), 
                    'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                );
                $action_links[] = array('text' => 'View User', 'href' => $this->currentUser->getUserPath('profile/'));
                $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_UPDATE_PROFILE, $paramsFB);    
                $result = Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);      
                if ( false === $result && '' != $js = Warecorp_Facebook_Feed::getJsResponse() ) $objResponse->addScript($js);         
                $cache->save('1', 'timer_'.$user->getId(), array(), 86400);    
            }
        }
        
        /**
         * Prepare data to reload page
         */
        if ( $user->getBirthday() instanceof Zend_Db_Expr ) $user->setBirthday(null);
        $needApproveCity = false;
        //return;
    } else {
        $user = $this->currentUser;
        $user->setFirstname         ($params['firstname']);
        $user->setLastname          ($params['lastname']);
        $user->setLocale            ($params['userlocale']);       
        $user->setGender            ($params['gender']);
        $user->setBirthday          ($params['birthday']['date_Year'].'-'.$params['birthday']['date_Month'].'-'.$params['birthday']['date_Day']);
        $user->setIsBirthdayPrivate (isset($params['is_birthday_private']) ? '1' : '0');
        $user->setIsGenderPrivate   (isset($params['is_gender_private']) ? '1' : '0');
        $user->setTimezone          ($params['timezone']);
    }

    /**
     * Create timezones list
     */
    $timezones      = new Warecorp_Location_Timezone();
    $genderArray    = array(
        'unselected' => Warecorp::t(' '),
        'male' => Warecorp::t('Male'),
        'female' => Warecorp::t('Female')
    );

    $this->view->visibility = true;
    $this->view->genderArray = $genderArray;
    $this->view->time_zones = $timezones->getZanbyTimezonesNamesAssoc();
    $this->view->countries = Warecorp_Location::getCountriesListAssoc(true);
    $this->view->countryId = $params["countryId"];
    $this->view->city = $params['city'];
    $this->view->zipcode = $params['zipcode'];
    $this->view->cityQuerySelected = $params['cityQuerySelected'];
    $this->view->cityAliasSelected = $params['cityAliasSelected'];
    $this->view->city_correct = $params["city_correct"];
    $this->view->lstCities = $lstCities;
    $this->view->strRECOGNIZEDCity = $strRECOGNIZEDCity;
    $this->view->strRECOGNIZEDZip = $strRECOGNIZEDZip;
    $this->view->needApproveCity = $needApproveCity;
    $this->view->edituser = $user;
    $this->view->form = $form;
    $this->view->user_locales= Warecorp_Date::getLocalesListAsArray();

    $Content = $this->view->getContents('users/settings.basicInformation.tpl');

    $objResponse->addClear("AccountBasicInformation_Content", "innerHTML");
    $objResponse->addAssign("AccountBasicInformation_Content", "innerHTML", $Content);
