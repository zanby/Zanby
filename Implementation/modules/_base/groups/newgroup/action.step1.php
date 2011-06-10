<?php
    Warecorp::addTranslation('/modules/groups/newgroup/action.step1.php.xml');
    
    if ( !($this->currentGroup instanceof Warecorp_Group_Family) ) { $this->_redirect($this->currentGroup->getGroupPath('summary')); }
    
    $membersList = $this->currentGroup->getGroups()->setTypes(array('simple', 'family'));
    $isHostPrivileges = ($membersList->isCoowner($this->_page->_user)) || ($this->currentGroup->getMembers()->isHost($this->_page->_user));
    if ( !$isHostPrivileges ) $this->_redirect($this->currentGroup->getGroupPath('members'));
    $showPending = $isHostPrivileges && ($this->currentGroup->getJoinMode() == 1);
    
    unset($_SESSION['newgroupMember']);
    /**
     * 
     */     
    if ( empty($_SESSION['newgroupMember']) ){
        $_SESSION['newgroupMember'] = array();
        $_SESSION['newgroupMember']["tempData"] = array();
        $_SESSION['newgroupMember']["tempData"]["lastStep"] = 0;
    }
    
    $newGroup = &$_SESSION['newgroupMember'];
    
    /**
     * Register ajax functions
     */    
    $this->_page->Xajax->registerUriFunction("detectCountry", "/ajax/detectCountry/");
    $this->_page->Xajax->registerUriFunction("autoCompleteCity", "/ajax/autoCompleteCity/");
    $this->_page->Xajax->registerUriFunction("autoCompleteZip", "/ajax/autoCompleteZip/");
        
    $form = new Warecorp_Form('form_step1', 'POST', $this->currentGroup->getGroupPath('membersAddStep1'));
    $form->addRule('categoryId', 'nonzero', Warecorp::t('Choose category'));
    $form->addRule('countryId', 'nonzero', Warecorp::t('Choose country'));
    $form->addRule('city', 'callback', Warecorp::t('Enter please City'), 
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
    $form->addRule('zipcode',      'callback',      Warecorp::t('Enter please Zip code'), 
        array(
            'func' => 'Warecorp_Form_Validation::isZipcodeRequired', 
            'params' => array(
                'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                'zipcode' => ((isset($this->params['zipcode'])) ? $this->params['zipcode'] : null)
            )
        )
    );
    $form->addRule('zipcode',      'callback',      Warecorp::t('Zip code is incorrect. Choose it from autocomplete list.'), 
        array(
            'func' => 'Warecorp_Form_Validation::isZipcodeInvalid', 
            'params' => array(
                'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                'zipcode' => ((isset($this->params['zipcode'])) ? $this->params['zipcode'] : null)
            )
        )
    );
    
    $this->params['countryId'] = (isset($this->params['countryId'])) ? $this->params['countryId'] : ((isset($newGroup['countryId'])) ? $newGroup['countryId'] : 0);
    
    if ( $form->validate($this->params) ) {
        $newGroup['step1']          = true;
        $newGroup['categoryId']     = $this->params['categoryId'];
        $newGroup['countryId']      = $this->params['countryId'];
        
        $country = Warecorp_Location_Country::create($this->params['countryId']);
        if ( $this->params['countryId'] == 1 || $this->params['countryId'] == 38 ) {
            if ( strpos($this->params['zipcode'], ",") ) $locationInfo = $country->getZipcodeByACFullInfo($this->params['zipcode']);
            else $locationInfo = $country->getZipcodeByACInfo($this->params['zipcode']);
            $newGroup['zipcodeClear']   = $locationInfo['zipcode'];
            $newGroup['zipcode']        = $this->params['zipcode'];
        } else {
            $locationInfo = $country->getCityByACInfo($this->params['city']);
            $newGroup['zipcodeClear']   = '';
            $newGroup['zipcode']        = '';
        }        
        $objCity    = Warecorp_Location_City::create($locationInfo['city_id']);
        $objState   = $objCity->getState();        
        $newGroup['stateId']        = $objState->id;
        $newGroup['cityId']         = $locationInfo['city_id'];
        $newGroup['city']           = $this->params['city'];
        
        if ( $newGroup["tempData"]["lastStep"] < 1 ) $newGroup["tempData"]["lastStep"] = 1;
        $this->_redirect($this->currentGroup->getGroupPath('membersAddStep'.($newGroup["tempData"]["lastStep"]+1)));
    }
    
    $group = array();
    
    $country = Warecorp_Location_Country::create($this->params["countryId"]);
    $group['categoryId']    = (isset($this->params['categoryId'])) ? $this->params['categoryId'] : ((isset($newGroup['categoryId'])) ? $newGroup['categoryId'] : null);
    $group['countryId']     = (isset($this->params['countryId'])) ? $this->params['countryId'] : ((isset($newGroup['countryId'])) ? $newGroup['countryId'] : 0);
    $group['stateId']       = (isset($this->params['stateId'])) ? $this->params['stateId'] : ((isset($newGroup['stateId'])) ? $newGroup['stateId'] : 0);
    $group['cityId']        = (isset($this->params['cityId'])) ? $this->params['cityId'] : ((isset($newGroup['cityId'])) ? $newGroup['cityId'] : 0);
    $group['city']          = (isset($this->params['city'])) ? $this->params['city'] : ((isset($newGroup['city'])) ? $newGroup['city'] : '');
    $group['zipcode']       = (isset($this->params['zipcode'])) ? $this->params['zipcode'] : ((isset($newGroup['zipcode'])) ? $newGroup['zipcode'] : null);
    
    $this->view->countryId = $group['countryId'];
    
    $newGroup['acctyp'] = isset($newGroup['acctyp'])?$newGroup['acctyp']:'0';
    
    $this->view->step = '1';
    $this->view->stepscount = '3';    
    $countries = Warecorp_Location::getCountriesListAssoc(true);
    $allCategoriesObj = new Warecorp_Group_Category_List();
    $allCategories = $allCategoriesObj->returnAsAssoc()->getList();
    
    $this->view->form = $form;
    $this->view->group = $group;
    $this->view->countries = $countries;
    $this->view->categories = $allCategories;
    $this->view->city = $group["city"];
    $this->view->zipcode = $group["zipcode"];
    
    /**
     * 
     */    
    $this->view->showPending = isset($showPending)?$showPending:null;
    $this->view->bodyContent = 'groups/newgroup/step1.tpl';
    
    
