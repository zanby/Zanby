<?php
    Warecorp::addTranslation("/modules/users/xajax/action.showBasicInformation.php.xml");
    
    $this->_page->setTitle(Warecorp::t('Accounts settings'));  

    /**
     * Register ajax functions
     * it should be done in main action
     */
    
    /**
     * Apply default values
     */ 
    $this->params['countryId']          = $this->currentUser->getCountry()->id;
    $this->params['city']               = $this->currentUser->getCity()->name;
    $this->params['cityQuerySelected']  = $this->currentUser->getCity()->name;
    $this->params['cityAliasSelected']  = $this->currentUser->getCity()->id;
    $this->params['city_correct']       = 0;
    $this->params['zipcode']            = $this->currentUser->getZipcode();
    $this->params['firstname']          = ( !isset($this->params['firstname']) )            ? null  : trim($this->params['firstname']);
    $this->params['lastname']           = ( !isset($this->params['lastname']) )             ? null  : trim($this->params['lastname']);
    
    $strRECOGNIZEDCity  = '';           // string name of recognized place
    $lstCities          = null;         // list of aliases allowed for current city query
    $needApproveCity    = false;        // need show city confirmation box
    $strRECOGNIZEDZip   = false;
    if ( $this->params['countryId'] && $this->params['countryId'] != 1 && $this->params['countryId'] != 38 ) {
        $strRECOGNIZEDCity = $this->currentUser->getCity()->name.', '.$this->currentUser->getCity()->getState()->name.', '.$this->currentUser->getCity()->getState()->getCountry()->name;        
        $lstCityAliasIds = Warecorp_Location_Alias_List::detectAliasByQueryString($this->currentUser->getCity()->name, $this->currentUser->getCountry()->id);
        $lstCities = $this->currentUser->getCountry()->findByCityNameOrIds($this->currentUser->getCity()->name, $lstCityAliasIds, $this->currentUser->getCity()->id);
        $lstCitiesSize = sizeof($lstCities);        
        if ( $lstCitiesSize == 0 ) {
        	$lstCities = null;
        	$needApproveCity = false;
        } else {
            $needApproveCity = false;
        }
    } elseif ( $this->params['countryId'] && ($this->params['countryId'] == 1 || $this->params['countryId'] == 38) ) {
        if ( $this->currentUser->getCountry()->checkZipcode($this->currentUser->getZipcode()) ) $strRECOGNIZEDZip = true;    	
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
        
    /**
     * FORM : Create form and form rules
     * Add Rules into Form object
     */
    $form = new Warecorp_Form('biForm', 'post', 'javasript:void(0);');

    /**
     * Assign var to Smarty
     */
    $this->view->visibility = true;
    $this->view->genderArray = $genderArray;    
    $this->view->time_zones = $timezones->getZanbyTimezonesNamesAssoc();
    $this->view->countries = Warecorp_Location::getCountriesListAssoc(true);    
    $this->view->countryId = $this->currentUser->getCountry()->id;
    $this->view->city = $this->currentUser->getCity()->name;
    $this->view->zipcode = $this->currentUser->getZipcode();
    $this->view->cityQuerySelected = $this->params['cityQuerySelected'];
    $this->view->cityAliasSelected = $this->params['cityAliasSelected'];
    $this->view->city_correct = $this->params["city_correct"];
    $this->view->lstCities = $lstCities;
    $this->view->strRECOGNIZEDCity = $strRECOGNIZEDCity;
    $this->view->strRECOGNIZEDZip = $strRECOGNIZEDZip;
    $this->view->needApproveCity = $needApproveCity;
    $this->view->edituser = $this->currentUser;
    $this->view->form = $form;
    
    $this->view->user_locales= Warecorp_Date::getLocalesListAsArray();      
    
    $Content = $this->view->getContents('users/settings.basicInformation.tpl');
    
    $objResponse = new xajaxResponse();
    $objResponse->addClear("AccountBasicInformation_Content", "innerHTML");
    $objResponse->addAssign("AccountBasicInformation_Content", "innerHTML", $Content);
    $objResponse->addScript('initCityAutocomplete();');
    $objResponse->addScript('initZipAutocomplete();');
    
