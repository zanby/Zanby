<?php
Warecorp::addTranslation("/modules/users/calendar/xajax/action.venue.add.php.xml");
$form = new Warecorp_Form ( 'addNewVenue', 'POST' ) ;

$form->addRule ( 'venue_name', 'required', Warecorp::t('Please fill venue name' ));
$form->addRule ( 'venue_name', 'maxlength', Warecorp::t('Venue name too long (max %s characters)', 100), array('max' => 100) );
$form->addRule ( 'venue_name', 'notempty', Warecorp::t('Please fill venue name' ));
$form->addRule ( 'venue_category', 'nonzero', Warecorp::t('Please choose category' ) );
$form->addRule ( 'venue_street_address1', 'required', Warecorp::t('Please fill address' ) );
$form->addRule ( 'venue_street_address1', 'notempty', Warecorp::t('Please fill address' ) );
$form->addRule ( 'venue_countryId', 'nonzero', Warecorp::t('Please choose country' ) );
$form->addRule ( 'venue_stateId', 'nonzero', Warecorp::t('Please choose state' ) );
$form->addRule ( 'venue_cityId', 'nonzero', Warecorp::t('Please choose city' ) );
$form->addRule ( 'venue_description', 'maxlength', Warecorp::t('Description too long'),array('max' => 750) );
//$form->addRule ( 'venue_zipcode1', 'required', 'Please fill zip code' ) ;
if ($aParams && $aParams['venue_category'] == 1) {
	$form->addRule ( 'venue_website', 'required', Warecorp::t('Please fill website' ) );
	$form->addRule ( 'venue_website', 'notempty', Warecorp::t('Please fill website' ) );
}

if (isset ( $aParams ) && !empty ( $aParams )) {
    $this->params = array_merge ( $this->params, $aParams ) ;
	$_REQUEST [ '_wf__addNewVenue' ] = true ;
}	
array_walk_recursive($aParams, 'trim');

$objResponse = new xajaxResponse ( ) ;
$objResponse->addClear ( 'simple_venue_body', 'innerHTML' ) ;

if ($form->validate ( $aParams )) {
	$venue = new Warecorp_Venue_Item ( ) ;
    $venue->setName( $aParams['venue_name'] );
    $venue->setOwnerType(Warecorp_Venue_Enum_OwnerType::USER);
    $venue->setOwnerId( $this->currentUser->getId() );
    $venue->setCategoryId( $aParams['venue_category'] );
    $venue->setCityId( $aParams['venue_cityId'] );
    $venue->setZipcode( $aParams['venue_zipcode1'] );
    $venue->setAddress1( $aParams['venue_street_address1'] );
    $venue->setAddress2( $aParams['venue_street_address2'] );
    $venue->setPhone( $aParams['venue_phone'] );
    $venue->setWebsite( $aParams['venue_website']);
    $venue->setDescription( $aParams['venue_description'] );
    $venue->setPrivate( $aParams['venue_private'] );
    $venue->setType( Warecorp_Venue_Enum_VenueType::SIMPLE );
    $venue->save();

    if (isset($aParams['venue_tags'])){
    	$venue->deleteTags();
        $venue->addTags($aParams['venue_tags']);
    }
    $objResponse->addScript("setVenueId('{$venue->getId()}');");
    $_SESSION['U_simple_venueId'] = $venue->getId();
    $this->view->venue = $venue;
    
    $output = $this->view->getContents('users/calendar/venues.view.tpl');
} else {
	$countries = Warecorp_Location::getCountriesListAssoc (true) ;
	
	if (!isset($aParams['venue_countryId'])) {
		if ( $this->currentUser->getCity() ) {
			$aParams['venue_countryId'] = $this->currentUser->getCity()->getState()->getCountry()->id;
		} else {
			$aParams['venue_countryId'] = 0;
		}
	}
    $country = Warecorp_Location_Country::create($aParams['venue_countryId']);
	$states = $country->getStatesListAssoc(true);
	
	if (!isset($aParams['venue_stateId'])) {
		if ( $this->currentUser->getCity() ) {
			$aParams['venue_stateId'] = $this->currentUser->getCity()->getState()->id;
		} else {
			$aParams['venue_stateId'] = 0;
		}
	}
    $state = Warecorp_Location_State::create($aParams['venue_stateId']);
    $cities = $state->getCitiesListAssoc(true);
	if (!isset($aParams['venue_cityId'])) {
		if ( $this->currentUser->getCity() ) {
			$aParams['venue_cityId'] = $this->currentUser->getCityId();
		} else {
			$aParams['venue_cityId'] = 0;
		}
	}
		
	if (!isset($aParams['venue_zipcode1'])) 
			$aParams['venue_zipcode1'] = $this->currentUser->getZipcode();
    
    $aoVenuesCategoriesList = new Warecorp_Venue_CategoryList();
    $aoVenuesCategoriesList->returnAsAssoc();
    $aoVenuesCategoriesList->setType( Warecorp_Venue_Enum_VenueType::SIMPLE );
    $venue_categories = $aoVenuesCategoriesList->getList();
    $venue_categories[0] = "[ CHOOSE CATEGORY ]";
    ksort($venue_categories);
    
    $this->view->form         = $form;
    $this->view->formErrors1   = count($form->getErrorMessages()) > 0 ? $form->getErrorMessages() : false;
    $this->view->header       = Warecorp::t("Add Venue");
    $this->view->countries    = $countries;
    $this->view->cities       = $cities;
    $this->view->states       = $states;
    $this->view->aData        = $aParams;
    $this->view->mode         = 'add';
    $this->view->privacy      = array ( 0 => Warecorp_Enum_PrivacyType::PRIVACY_PUBLIC , 1 => Warecorp_Enum_PrivacyType::PRIVACY_PRIVATE ) ;
    $this->view->categories   = $venue_categories;
	
	$output = $this->view->getContents ( 'users/calendar/venues.add.edit.tpl' ) ;
}

$objResponse->addAssign ( 'simple_venue_body', 'innerHTML', $output ) ;
