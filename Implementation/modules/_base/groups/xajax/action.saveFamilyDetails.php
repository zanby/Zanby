<?php
Warecorp::addTranslation('/modules/groups/xajax/action.saveFamilyDetails.php.xml');

    $objResponse = new xajaxResponse();
    $error = false;
    $groupId = isset($params["groupId"])?floor($params["groupId"]):0;
    if (!$groupId || $this->currentGroup->getId() != $groupId){
       $error = true;
    }
    if (isset($params['_wf__fdForm'])) $_REQUEST['_wf__fdForm'] = $params['_wf__fdForm'];

    $Group = new Warecorp_Group_Family("id", $groupId);

    /**
     * create from and form rules
     */
    $form = new Warecorp_Form('fdForm', 'POST', 'javasript:void(0);');
    $form->addRule('categoryId', 'nonzero', Warecorp::t('Choose category'));
    $form->addRule('countryId', 'nonzero', Warecorp::t('Choose country'));
    $form->addRule('city',          'callback',      Warecorp::t('Enter please City'),
        array(
            'func' => 'Warecorp_Form_Validation::isCityRequired',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'city' => ((isset($params['city'])) ? $params['city'] : null)
            )
        )
    );
    $form->addRule('city',          'callback',      Warecorp::t('City name is incorrect. Choose it from autocomplete list.'),
        array(
            'func' => 'Warecorp_Form_Validation::isCityInvalid',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'city' => ((isset($params['city'])) ? $params['city'] : null)
            )
        )
    );
    $form->addRule('zipcode',       'callback',      Warecorp::t('Enter please Zip code'),
        array(
            'func' => 'Warecorp_Form_Validation::isZipcodeRequired',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'zipcode' => ((isset($params['zipcode'])) ? $params['zipcode'] : null)
            )
        )
    );
    $form->addRule('zipcode',       'callback',      Warecorp::t('Zip code is incorrect. Choose it from autocomplete list.'),
        array(
            'func' => 'Warecorp_Form_Validation::isZipcodeInvalid',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'zipcode' => ((isset($params['zipcode'])) ? $params['zipcode'] : null)
            )
        )
    );
    $form->addRule('gname',             'callback',     Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>isset($params['gname'])?$params['gname']:null, 'excludeIds' => $params["groupId"])));
    $form->addRule('gname',             'required',     Warecorp::t('Enter Group Name'));
    $form->addRule('gname',             'regexp',       Warecorp::t('Enter correct Group Name'), array('regexp' => "/^[a-zA-Z0-9]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/"));
    $form->addRule('gname',             'rangelength',  Warecorp::t('Enter correct Group Name (%s-%s characters)', array(3,100)), array('min' => 3, 'max' => 100));

    $form->addRule('gemail',            'required',     Warecorp::t('Enter Group Email'));
    $form->addRule('gemail',            'maxlength',    Warecorp::t('Email too long (max %s)',60), array('max' =>  61 + strlen(DOMAIN_FOR_GROUP_EMAIL)));
    $form->addRule('gemail',            'regexp',       Warecorp::t('Enter correct Group Address'), array('regexp' => '/^[A-Za-z0-9]{1}[A-Za-z0-9\-]+@'.str_replace('.','\.',DOMAIN_FOR_GROUP_EMAIL).'$/'));
    $form->addRule('gemail',            'callback',     Warecorp::t('Group Address already exist'),array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => isset($params['gemail'])?array('key' =>'group_path', 'value'=> $params['gemail'], 'exclude'=>$Group->getPath()):null));


    $form->addRule('description',       'required',     Warecorp::t('Enter Description'));
    $form->addRule('description',       'maxlength',    Warecorp::t('Enter correct Description'), array('max' => 2000));

    $form->addRule('company',           'regexp',       Warecorp::t('Enter correct Company Name'), array('regexp' => '/^[A-Za-z0-9\s]*$/'));
    $form->addRule('company',           'rangelength',  Warecorp::t('Enter correct Company Name'), array('min' => 0, 'max' => 255));

    $form->addRule('position',          'regexp',       Warecorp::t('Enter correct Position'), array('regexp' => '/^[A-Za-z0-9\s]*$/'));
    $form->addRule('position',          'rangelength',  Warecorp::t('Enter correct Position'), array('min' => 0, 'max' => 255));

    $form->addRule('address1',          'required',     Warecorp::t('Enter Address1'));
    $form->addRule('address1',          'regexp',       Warecorp::t('Enter correct Address1'), array('regexp' => '/^[A-Za-z0-9\s\.]*$/'));
    $form->addRule('address1',          'rangelength',  Warecorp::t('Enter correct Address1'), array('min' => 0, 'max' => 255));

    $form->addRule('address2',          'regexp',       Warecorp::t('Enter correct Address2'), array('regexp' => '/^[A-Za-z0-9\s\.]*$/'));
    $form->addRule('address2',          'rangelength',  Warecorp::t('Enter correct Address2'), array('min' => 0, 'max' => 255));

    if ($Group->getPath() != trim($params['gemail'])) $redirect = true;
    else $redirect = false;

    $recivedGemail = $params['gemail'];

    /**
     * add optional form rules
     */
    if (!empty($params['gemail'])) {
        $params['gemail'] .= '@'.DOMAIN_FOR_GROUP_EMAIL;
        $form->addRule('gemail',    'email',        Warecorp::t('Enter correct Group Email'));
    }

    if (!empty($params['gname'])) {
        $name = preg_replace("/\s{1,}/","-", strtolower(trim($params['gname'])));
        $form->addRule('gname',         'callback', Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>$params['gname'], 'excludeIds' => $params["groupId"])));
        //$form->addRule('gname', 'callback', 'Group name already used or invalid', array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => array('key'=>'name', 'value'=>trim($this->params['gname']))));
        //$form->addRule('name', 'callback', 'Group name already used or invalid', array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => array('key'=>'group_path', 'value'=>$name)));
    }

    if (isset($params['hjoin']) && $params['hjoin'] == "2") {
        $form->addRule('jcode',   'required',     Warecorp::t('Enter Invitation Code'));
        if (!empty($params['jcode'])) {
            $params['jcode'] = trim($params['jcode']);
        }
    }
    $flag = false;
    $autocompletScript = null;
    /**
     * process form
     */
    if ( $form->validate($params) ) {
        $Group->setCategoryId   ( $params['categoryId'] );

        $Group->setName         ( $params['gname'] );
        $Group->setPath         ( $recivedGemail);
        $Group->setCompany      ( $params['company'] );
        $Group->setPosition     ( $params['position'] );
        $Group->setAddress1     ( $params['address1'] );
        $Group->setAddress2     ( $params['address2'] );
        $Group->setDescription  ( $params['description'] );
        $Group->setJoinMode     ( $params['hjoin'] );
        $Group->setJoinCode     ( ($params['hjoin'] == 2) ? $params['jcode'] : null );
        if ($Group->getProfile() !== null) {
                $Group->getProfile()->setIsFeature( $params['is_feature'] );
        }
        $country = Warecorp_Location_Country::create($params['countryId']);
        if ( $params['countryId'] == 1 || $params['countryId'] == 38 ) {
            if ( strpos($params['zipcode'], ",") ) $locationInfo = $country->getZipcodeByACFullInfo($params['zipcode']);
            else $locationInfo = $country->getZipcodeByACInfo($params['zipcode']);
            $Group->setZipcode  ( $locationInfo['zipcode'] );
            $Group->setCityId   ( $locationInfo['city_id'] );
        } else {
            $locationInfo = $country->getCityByACInfo($params['city']);
            $Group->setZipcode  ( '' );
            $Group->setCityId   ( $locationInfo['city_id'] );
        }

        $Group->save();
        $Group->deleteTags();
        $Group->addTags($params['tags']);
        $flag = true;
        $this->view->visibility = false;
    } else {
        $Group->setCategoryId   ( $params['categoryId'] );
        $Group->setName         ( $params['gname'] );
        $Group->setPath         ( $recivedGemail);
        $Group->setCompany      ( $params['company'] );
        $Group->setPosition     ( $params['position'] );
        $Group->setAddress1     ( $params['address1'] );
        $Group->setAddress2     ( $params['address2'] );
        $Group->setDescription  ( $params['description'] );
        $Group->setJoinMode     ( $params['hjoin'] );
        $Group->setJoinCode     ( ($params['hjoin'] == 2) ? $params['jcode'] : null );
        if ($Group->getProfile() !== null) {
            $Group->getProfile()->setIsFeature( $params['is_feature'] );
        }

        $autocompletScript = 'initCityAutocomplete();initZipAutocomplete();';
        $this->view->cityStr = $params['city'];
        $this->view->zipStr = $params['zipcode'];
        $this->view->visibility = true;
        $redirect = false;
    }
    /**
     * create countries list
     */
    $countries = Warecorp_Location::getCountriesListAssoc(true);
    /**
     * create categories
     */
    $allCategoriesObj = new Warecorp_Group_Category_List();
    $allCategories = $allCategoriesObj->returnAsAssoc()->getList();

    $this->view->countryId = $params['countryId'];
    $this->view->countries = $countries;
    $this->view->categories = $allCategories;
    $this->view->tags = $params['tags'];
    $this->view->form = $form;
    $this->view->currentGroup = $Group;

    if ($redirect === true) {
        $newGroup = new Warecorp_Group_Family("id", $groupId);
        $objResponse->addRedirect($newGroup->getGroupPath('settings'));
    }
    if ($error === true) $objResponse->addRedirect(BASE_URL);

    $Content = $this->view->getContents('groups/settings.familydetails.tpl');
    $objResponse->addClear( "GroupSettingsGroupDetails_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsGroupDetails_Content", "innerHTML", $Content );
    if ($flag == true) $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));

    if ( $autocompletScript ) $objResponse->addScript($autocompletScript);

