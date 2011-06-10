<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.venue.edit.php.xml");
$venue = new Warecorp_Venue_Item($venueId);

if (!is_array($aParams)) $aParams = array();

if (sizeof($aParams) > 0){
	$this->params = array_merge ( $this->params, $aParams ) ;
    $_REQUEST [ '_wf__editVenue' ] = true ;
}

switch ($editedBlock){
	case 'a' : {
		$postAction = 'xajax_chooseSavedVenue();';
		break;
	}
	case 's' : {
	    $postAction = 'xajax_loadSavedVenues(getSearches());';
        break;
	}
    case 'f' : {
        $postAction = 'xajax_findaVenue(getFindSearches());';
        break;
    }	
	default: {
        $postAction = 'xajax_chooseSavedVenue();';
        break;
	}
}

$form = new Warecorp_Form ( 'editVenue', 'POST' ) ;

$form->addRule ( 'venue_name', 'required', Warecorp::t('Please fill venue name' ));
$form->addRule ( 'venue_name', 'notempty', Warecorp::t('Please fill venue name' ));
$form->addRule ( 'venue_category', 'nonzero', Warecorp::t('Please choose category' )) ;
$form->addRule ( 'venue_street_address1', 'required', Warecorp::t('Please fill address' ) );
$form->addRule ( 'venue_street_address1', 'notempty', Warecorp::t('Please fill address' ) );
$form->addRule ( 'venue_countryId', 'nonzero', Warecorp::t('Please choose country' ) );
$form->addRule ( 'venue_stateId', 'nonzero', Warecorp::t('Please choose state' ) );
$form->addRule ( 'venue_cityId', 'nonzero', Warecorp::t('Please choose city' ) );
$form->addRule ( 'venue_description', 'maxlength', Warecorp::t('Description too long'), array('max' => 750) );
//$form->addRule ( 'venue_zipcode1', 'required', 'Please fill zip code' ) ;
if ($aParams && $aParams['venue_category'] == 1) {
    $form->addRule ( 'venue_website', 'required', Warecorp::t('Please fill website' ) );
    $form->addRule ( 'venue_website', 'notempty', Warecorp::t('Please fill website' ) );
}
    // Common data
    $objResponse = new xajaxResponse ( ) ;

    //$this->view->postAction = $postAction;
    $this->view->fromWhat = $editedBlock;
    ///--end common data
    array_walk_recursive($aParams, 'trim');
if ($venue->getOwnerId() == $this->_page->_user->getId()) {    
    if ($form->validate ( $aParams )) {
    //        
    //    $objResponse->addClear ( $clearBlock, 'innerHTML' ) ;	
        
        $venue->setName( $aParams['venue_name'] );
        $venue->setOwnerType( Warecorp_Venue_Enum_OwnerType::USER );
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

        
        $this->view->venue = $venue;
        $objResponse->addScript($postAction);
    /*    if ($editedBlock == 's')  $objResponse->addScript('xajax_loadSavedVenues(getSearches());');
        elseif ($editedBlock == 'f')  $objResponse->addScript('xajax_findaVenue(getFindSearches());');
        else {
            $objResponse->addScript("changevenueto('simple')");
    	    $output = $this->view->getContents( $templateFile );
            $objResponse->addAssign ( $clearBlock, 'innerHTML', $output ) ;
        }*/
    } else {
	    $objResponse->addScript("changevenueto('saved_simple')");
        $objResponse->addClear ( 'saved_simple_venue_body', 'innerHTML' ) ;
        $city = Warecorp_Location_City::create($venue->getCityId());
        $state = $city->getState();
        $country = $state->getCountry();

	    if(sizeof($aParams) == 0){
            $aParams['venue_type']             = $venue->getType();
            $aParams['venue_name']             = $venue->getName();
            $aParams['venue_category']         = $venue->getCategoryId();
            $aParams['venue_cityId']           = $venue->getCityId();
            $aParams['venue_countryId']        = $country->id;
            $aParams['venue_stateId']          = $state->id;
            $aParams['venue_zipcode1']         = $venue->getZipcode();
            $aParams['venue_street_address1']  = $venue->getAddress1();
            $aParams['venue_street_address2']  = $venue->getAddress2();
            $aParams['venue_phone']            = $venue->getPhone();
            $aParams['venue_website']          = $venue->getWebsite();
            $aParams['venue_description']      = $venue->getDescription();
            $aParams['venue_private']          = $venue->getPrivate();
            $venue_tags = $venue->getTagsList();

	        foreach ($venue_tags as &$_tag) {
	            $_tag = $_tag->getPreparedTagName();
	        }

	        $aParams['venue_tags'] = implode(' ',$venue_tags);
	    }

        $aParams['venueId'] = $venueId;
        
	    $countries = Warecorp_Location::getCountriesListAssoc();
	    $states = $country->getStatesListAssoc();
	    $cities = $state->getCitiesListAssoc();
	    $zipCodes = $city->getZipcodesListAssoc();

	    $aoVenuesCategoriesList = new Warecorp_Venue_CategoryList();
	    $aoVenuesCategoriesList->returnAsAssoc();
        $aoVenuesCategoriesList->setType( Warecorp_Venue_Enum_VenueType::SIMPLE );
	    $venue_categories = $aoVenuesCategoriesList->getList();
	    $venue_categories[0] = "[ CHOOSE CATEGORY ]";
	    ksort($venue_categories);

        $this->view->form         = $form;
        $this->view->formErrors1   = count($form->getErrorMessages()) > 0 ? $form->getErrorMessages() : false;
        $this->view->header       = Warecorp::t("Saved Venues / Edit Venue");
        $this->view->countries    = $countries;
        $this->view->cities       = $cities;
        $this->view->states       = $states;
        $this->view->aData        = $aParams;
        $this->view->mode         = 'edit';
        $this->view->privacy      = array ( 0 => Warecorp_Enum_PrivacyType::PRIVACY_PUBLIC , 1 => Warecorp_Enum_PrivacyType::PRIVACY_PRIVATE );
        $this->view->categories   = $venue_categories;
        
        $output = $this->view->getContents ( 'users/calendar/venues.add.edit.tpl' ) ;
        $objResponse->addAssign ( 'saved_simple_venue_body', 'innerHTML', $output ) ;
    }
} else {
    $objResponse->addRedirect($this->_page->_user->getUserPath('profile'));
}
?>
