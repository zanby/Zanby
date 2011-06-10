<?php
Warecorp::addTranslation("/modules/users/calendar/xajax/action.ww.venue.add.php.xml");
$objResponse = new xajaxResponse ( ) ;
$objResponse->addClear ( 'worldwide_venue_body', 'innerHTML' ) ;
$form = new Warecorp_Form ( 'addNewWWVenue', 'POST' ) ;

$form->addRule ( 'ww_venue_name', 'required', Warecorp::t('Please fill venue name' ));
$form->addRule ( 'ww_venue_name', 'maxlength', Warecorp::t('Venue name too long (max %s characters)', 100),array('max' => 100) );
$form->addRule ( 'ww_venue_name', 'notempty', Warecorp::t('Please fill venue name' ));
$form->addRule ( 'ww_venue_category', 'nonzero', Warecorp::t('Please choose category' )) ;
$form->addRule ( 'ww_venue_description', 'maxlength', Warecorp::t('Description too long'),array('max' => 750) );

if (isset ( $aParams ) && !empty ( $aParams )) {
    $this->params = array_merge ( $this->params, $aParams ) ;
    $_REQUEST [ '_wf__addNewWWVenue' ] = true ;
}   

if ($form->validate ( $aParams )) {
	$venue = new Warecorp_Venue_Item ( ) ;
	$venue->setName( $aParams['ww_venue_name'] );
	$venue->setOwnerType( Warecorp_Venue_Enum_OwnerType::USER );
    $venue->setOwnerId( $this->currentUser->getId() );
    $venue->setCategoryId( $aParams['ww_venue_category'] );
    $venue->setPhone( $aParams['ww_venue_phone'] );
    $venue->setWebsite( $aParams['ww_venue_website']);
    $venue->setDescription( $aParams['ww_venue_description'] );
    $venue->setPrivate( $aParams['ww_venue_private'] );
    $venue->setType( Warecorp_Venue_Enum_VenueType::WORLDWIDE );
    $venue->save();

    if (isset($aParams['ww_venue_tags'])){
        $venue->deleteTags();
        $venue->addTags($aParams['ww_venue_tags']);
    }
    $objResponse->addScript("setVenueId('{$venue->getId()}');");
    $_SESSION['U_worldwide_venueId'] = $venue->getId();
    $this->view->venue = $venue;
    $output = $this->view->getContents('users/calendar/ww.venues.view.tpl');
} else {
    $aoVenuesCategoriesList = new Warecorp_Venue_CategoryList();
    $aoVenuesCategoriesList->returnAsAssoc();
    $aoVenuesCategoriesList->setType( Warecorp_Venue_Enum_VenueType::WORLDWIDE );
    $venue_categories = $aoVenuesCategoriesList->getList();
    $venue_categories[0] = "[ CHOOSE CATEGORY ]";
    ksort($venue_categories);
    //
    $this->view->form         = $form;
    $this->view->formErrors1   = count($form->getErrorMessages()) > 0 ? $form->getErrorMessages() : false;
    $this->view->aData        = $aParams;
    $this->view->mode         = 'add';
    $this->view->privacy      = array ( 0 => Warecorp_Enum_PrivacyType::PRIVACY_PUBLIC , 1 => Warecorp_Enum_PrivacyType::PRIVACY_PRIVATE );
    $this->view->categories   = $venue_categories;
    
    $output = $this->view->getContents ( 'users/calendar/ww.venues.add.edit.tpl' ) ;
}

$objResponse->addAssign ( 'worldwide_venue_body', 'innerHTML', $output ) ;
?>
