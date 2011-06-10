<?php
    Warecorp::addTranslation('/modules/newfamilygroup/action.newfamilygroup.step1.php.xml');

    /**
     * Register ajax functions
     */
    $this->_page->Xajax->registerUriFunction("detectCountry", "/ajax/detectCountry/");
    $this->_page->Xajax->registerUriFunction("autoCompleteCity", "/ajax/autoCompleteCity/");
    $this->_page->Xajax->registerUriFunction("autoCompleteZip", "/ajax/autoCompleteZip/");

    $this->params['countryId'] = isset($this->params['countryId']) ? $this->params['countryId'] : null;

    $form = new Warecorp_Form('form_step1', 'POST', '/'.$this->_page->Locale.'/newfamilygroup/step1/');
    $form->addRule('categoryId',    'nonzero',      Warecorp::t('Choose category'));
    $form->addRule('countryId',     'nonzero',      Warecorp::t('Choose country'));
    $form->addRule('city',         'callback',      Warecorp::t('Enter please City'),
        array(
            'func' => 'Warecorp_Form_Validation::isCityRequired',
            'params' => array(
                'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                'city' => ((isset($this->params['city'])) ? $this->params['city'] : null)
            )
        )
    );
    $form->addRule('city',         'callback',      Warecorp::t('City name is incorrect. Choose it from autocomplete list.'),
        array(
            'func' => 'Warecorp_Form_Validation::isCityInvalid',
            'params' => array(
                'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                'city' => ((isset($this->params['city'])) ? $this->params['city'] : null)
            )
        )
    );
    $form->addRule('zipId',         'callback',      Warecorp::t('Enter please Zip code'),
        array(
            'func' => 'Warecorp_Form_Validation::isZipcodeRequired',
            'params' => array(
                'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                'zipcode' => ((isset($this->params['zipId'])) ? $this->params['zipId'] : null)
            )
        )
    );
    $form->addRule('zipId',         'callback',      Warecorp::t('Zip code is incorrect. Choose it from autocomplete list.'),
        array(
            'func' => 'Warecorp_Form_Validation::isZipcodeInvalid',
            'params' => array(
                'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                'zipcode' => ((isset($this->params['zipId'])) ? $this->params['zipId'] : null)
            )
        )
    );
    $form->addRule('name',          'required',     Warecorp::t('Enter Group Name'));
    $form->addRule('name',          'regexp',       Warecorp::t('Group Name may consist of a-Z, 0-9, \', -, underscores, space, and dot (.)'), array('regexp' => "/^[a-zA-Z0-9][a-zA-Z0-9_'\s\-\.]*$/"));
    $form->addRule('name',          'rangelength',  Warecorp::t('Enter correct Group Name (%s-%s characters)', array(3,100)), array('min' => 3, 'max' => 100));
    $form->addRule('name',          'callback',     Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>isset($params['name'])?$params['name']:null)));
    $form->addRule('description',   'required',     Warecorp::t('Enter Description'));
    $form->addRule('description',   'maxlength',    Warecorp::t('Enter correct Description (max %s characters)', 200), array('max' => 2000));
    $form->addRule('company',       'regexp',       Warecorp::t('Enter correct Company Name'), array('regexp' => '/^[A-Za-z0-9\s]*$/'));
    $form->addRule('company',       'rangelength',  Warecorp::t('Enter correct Company Name (%s-%s characters)', array(1,255)), array('min' => 0, 'max' => 255));
    $form->addRule('position',      'regexp',       Warecorp::t('Enter correct Position'), array('regexp' => '/^[A-Za-z0-9\s]*$/'));
    $form->addRule('position',      'rangelength',  Warecorp::t('Enter correct Position (max %s characters)', 255), array('min' => 0, 'max' => 255));
    $form->addRule('gemail',        'required',     Warecorp::t('Enter Group Email'));
    $form->addRule('gemail',        'maxlength',    Warecorp::t('Email too long (max %s)', 60), array('max' =>  61 + strlen(DOMAIN_FOR_GROUP_EMAIL)));
    $form->addRule('gemail',        'regexp',       Warecorp::t('Enter correct Group Address'), array('regexp' => '/^[A-Za-z0-9]{1}[A-Za-z0-9\-]+@'.str_replace('.','\.',DOMAIN_FOR_GROUP_EMAIL).'$/'));
    $form->addRule('gemail',        'callback',     Warecorp::t('Group Address already exist'),array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => isset($this->params['gemail'])?array('key' =>'group_path', 'value'=> $this->params['gemail']):null));
    $form->addRule('address1',      'required',     Warecorp::t('Enter Address1'));
    $form->addRule('address1',      'regexp',       Warecorp::t('Enter correct Address1'), array('regexp' => '/^[A-Za-z0-9\s\.,]*$/'));
    $form->addRule('address1',      'rangelength',  Warecorp::t('Enter correct Address1 (max %s characters)', 255), array('min' => 0, 'max' => 255));
    $form->addRule('address2',      'regexp',       Warecorp::t('Enter correct Address2'), array('regexp' => '/^[A-Za-z0-9\s\.,]*$/'));
    $form->addRule('address2',      'rangelength',  Warecorp::t('Enter correct Address2 (max %s characters)', 255), array('min' => 0, 'max' => 255));

    if (!empty($this->params['gemail'])) {
        $this->params['gemail'] .= '@'.DOMAIN_FOR_GROUP_EMAIL;
        $form->addRule('gemail',    'email',        Warecorp::t('Enter correct Group Email'));
    }

    if (!empty($this->params['name'])) {
        $this->params['name'] = trim($this->params['name']);
        $form->addRule('name', 'callback', Warecorp::t('Group name already used or invalid'), array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => array('key'=>'name', 'value'=>trim($this->params['name']))));
    }

    if (isset($this->params['hjoin']) && $this->params['hjoin'] == "2") {
        $form->addRule('jcode',   'required',     Warecorp::t('Enter Invitation Code'));
        if (!empty($this->params['jcode'])) {
            $this->params['jcode'] = trim($this->params['jcode']);
        }
    }

    if ( $form->validate($this->params) ) {
        $this->params['gemail']   = trim(str_replace('@'.DOMAIN_FOR_GROUP_EMAIL, '', $this->params['gemail']));

        /**
         * CHANGES LOCATIONS
         */
        $country = Warecorp_Location_Country::create($this->params['countryId']);
        if ( $this->params['countryId'] == 1 || $this->params['countryId'] == 38 ) {
            if ( strpos($this->params['zipId'], ",") ) $locationInfo = $country->getZipcodeByACFullInfo($this->params['zipId']);
            else $locationInfo = $country->getZipcodeByACInfo($this->params['zipId']);
            $zipcodeClear   = $locationInfo['zipcode'];
            $zipId          = $this->params['zipId'];
        } else {
            $locationInfo = $country->getCityByACInfo($this->params['city']);
            $zipcodeClear   = '';
            $zipId          = '';
        }
        $objCity    = Warecorp_Location_City::create($locationInfo['city_id']);
        $objState   = $objCity->getState();

        /* Save */
        $Group = new Warecorp_Group_Family();
        $Group->setCategoryId( $this->params['categoryId'] );
        $Group->setZipcode( $zipcodeClear );
        $Group->setCityId( $locationInfo['city_id'] );
        $Group->setName( trim($this->params['name']) );
        $Group->setPath( trim($this->params['gemail']) );
        $Group->setDescription( trim($this->params['description']) );
        $Group->setCreateDate( date("Y-m-d H:i:s", time()) );
        $Group->setJoinMode( $this->params['hjoin'] );
        $Group->setJoinCode( ($this->params['hjoin'] == 2) ? trim($this->params['jcode']) : null );
        $Group->setCompany( trim($this->params['company']) );
        $Group->setPosition( trim($this->params['position']) );
        $Group->setAddress1( trim($this->params['address1']) );
        $Group->setAddress2( trim($this->params['address2']) );

        /* set up type of family, it can be professional only */
        $Group->setPaymentType( 'business' );
        $Group->setPaymentPlan( null );
        $Group->setFamilySize( null );

        /* save new family */
        $Group->setGroupType(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
        $Group->save();

        /* save tags */
        $Group->addTags( trim($this->params['tags']) );

        /* add creator as host of family */
        $Group->getMembers()->addMember($this->_page->_user->getId(), 'host');

        /* update user membership plan, do he as complementary premium */
        $this->_page->_user->setMembershipPeriod( 'annualy' ); // tmp
        $this->_page->_user->setMembershipPlan( 'premium' );
        $this->_page->_user->setMembershipDowngrade( new Zend_Db_Expr('NULL') );
        $this->_page->_user->save();

        /* Set privileges "Manage Members" for Family to 0 - "Owner(s) Only" */
        $privileges = $Group->getPrivileges();
        $privileges->setManageMembers(0)->save();

        /**/
        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
        $cache->remove('all_mygroups_menu_account_tools_'.$this->_page->_user->getId());

        /* Send email to host - CREATE_NEW_GROUP_FAMILY_THANK */
        $Group = Warecorp_Group_Factory::loadById($Group->getId(),Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
        $Group->sendThank( $this->_page->_user );

        $_SESSION['newfamilygroup'] = $Group->getId();
        $this->_redirect('/'.LOCALE.'/newfamilygroup/success/');

    } elseif (isset($this->params['gemail'])) {
        $this->params['gemail'] = trim(str_replace('@'.DOMAIN_FOR_GROUP_EMAIL, '', $this->params['gemail']));
    }

    $group = array();
    $group['categoryId']    = (isset($this->params['categoryId'])) ? $this->params['categoryId'] : null;
    $group['countryId']     = (isset($this->params['countryId'])) ? $this->params['countryId'] : 0;
    $group['stateId']       = (isset($this->params['stateId'])) ? $this->params['stateId'] : 0;
    $group['cityId']        = (isset($this->params['cityId'])) ? $this->params['cityId'] : 0;
    $group['city']          = (isset($this->params['city'])) ? $this->params['city'] : '';
    $group['zipId']         = (isset($this->params['zipId'])) ? $this->params['zipId'] : null;
    $group['hjoin']         = (isset($this->params['hjoin'])) ? $this->params['hjoin'] : null;
    $group['jcode']         = (isset($this->params['jcode'])) ? $this->params['jcode'] : null;
    $group['name']          = (isset($this->params['name'])) ? $this->params['name'] : null;
    $group['gemail']        = (isset($this->params['gemail'])) ? $this->params['gemail'] : null;
    $group['description']   = (isset($this->params['description'])) ? $this->params['description'] : null;
    $group['company']       = (isset($this->params['company'])) ? $this->params['company'] : null;
    $group['position']      = (isset($this->params['position'])) ? $this->params['position'] : null;
    $group['address1']      = (isset($this->params['address1'])) ? $this->params['address1'] : null;
    $group['address2']      = (isset($this->params['address2'])) ? $this->params['address2'] : null;
    $group['tags']          = (isset($this->params['tags'])) ? $this->params['tags'] : null;

    $states = $cities = array();
    if ( $group['countryId'] !== null ) {
        $country = Warecorp_Location_Country::create($group['countryId']);
        $states = $country->getStatesListAssoc(true);
    }
    if ( $group['stateId'] !== null ) {
        $state = Warecorp_Location_State::create($group['stateId']);
        $cities = $state->getCitiesListAssoc(true);
    }

    $countries = Warecorp_Location::getCountriesListAssoc(true);
    $allCategoriesObj = new Warecorp_Group_Category_List();
    $allCategories = array('0' => '[Select Category]') + $allCategoriesObj->returnAsAssoc()->getList();

    $this->view->form = $form;
    $this->view->group = $group;
    $this->view->countries = $countries;
    $this->view->categories = $allCategories;
    $this->view->states = $states;
    $this->view->cities = $cities;
    $this->view->countryId = $group["countryId"];
    $this->view->bodyContent = 'newfamilygroup/step1.tpl';
