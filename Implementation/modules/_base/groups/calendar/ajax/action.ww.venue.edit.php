<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.ww.venue.edit.php.xml');

$venue = new Warecorp_Venue_Item($venueId);

if (!is_array($aParams)) $aParams = array();

if (sizeof($aParams) > 0){
	$this->params = array_merge ( $this->params, $aParams ) ;
    $_REQUEST [ '_wf__editWWVenue' ] = true ;
}

switch ($editedBlock){
    case 'wa' : {
        $postAction = 'xajax_chooseSavedWWVenue();';
        break;
    }
    case 'ws' : {
        $postAction = 'xajax_loadSavedWWVenues(getWWSearches());';
        break;
    }
    default: {
        $postAction = 'xajax_chooseSavedWWVenue();';
        break;
    }
}

$form = new Warecorp_Form ( 'editWWVenue', 'POST' ) ;

$form->addRule ( 'ww_venue_name', 'required', Warecorp::t('Please fill venue name') );
$form->addRule ( 'ww_venue_name', 'notempty', Warecorp::t('Please fill venue name') );
$form->addRule ( 'ww_venue_category', 'nonzero', Warecorp::t('Please choose category') ) ;
$form->addRule ( 'ww_venue_description', 'maxlength', Warecorp::t('Description too long'),array('max' => 750) );

$objResponse = new xajaxResponse ( ) ;
$this->view->fromWhat = $editedBlock; 
//$objResponse->addClear ( 'worldwide_venue_body', 'innerHTML' ) ;
if ($venue->getOwnerId() == $this->currentGroup->getId() && $venue->getOwnerType() == 'group') { 
    if ($form->validate ( $aParams )) {
        $venue->setName( $aParams['ww_venue_name'] );
        $venue->setOwnerId( $this->currentGroup->getId() );
        $venue->setCategoryId( $aParams['ww_venue_category'] );
        $venue->setPhone( $aParams['ww_venue_phone'] );
        $venue->setWebsite( $aParams['ww_venue_website']);
        $venue->setDescription( $aParams['ww_venue_description'] );
        $venue->setPrivate( $aParams['ww_venue_private'] );
        //$venue->savedName = $aParams['venue_name'];
        $venue->setOwnerType( Warecorp_Venue_Enum_OwnerType::GROUP );
        $venue->setType( Warecorp_Venue_Enum_VenueType::WORLDWIDE );
        //$venue->status = 'saved';
        $venue->save();
        if (isset($aParams['ww_venue_tags'])){
          $venue->deleteTags();
          $venue->addTags($aParams['ww_venue_tags']);
        }
        $objResponse->addScript($postAction);

        //$this->view->venue = $venue;
        //$output = $this->view->getContents('groups/calendar/ww.venues.view.tpl'); 
    } else {
        $objResponse->addScript("changevenueto('worldwide')");
        $objResponse->addClear ( 'worldwide_venue_body', 'innerHTML' ) ;     
	    if(sizeof($aParams) == 0){
		    $aParams['ww_venue_type']     = $venue->getType();
            $aParams['ww_venue_name'] = $venue->getName();
            $aParams['ww_venue_category'] = $venue->getCategoryId();
            $aParams['ww_venue_phone'] = $venue->getPhone();
            $aParams['ww_venue_website'] = $venue->getWebsite();
            $aParams['ww_venue_description'] = $venue->getDescription();
            $aParams['ww_venue_private'] = $venue->getPrivate();
	        $venue_tags = $venue->getTagsList();
	        
	        foreach ($venue_tags as &$_tag) {
	            $_tag = $_tag->getPreparedTagName();
	        }
	        $aParams['ww_venue_tags'] = implode(' ',$venue_tags);
	    } 
        $aParams['venueId'] = $venueId;
	    
        $aoVenuesCategoriesList = new Warecorp_Venue_CategoryList();
        $aoVenuesCategoriesList->returnAsAssoc();
        $aoVenuesCategoriesList->setType( Warecorp_Venue_Enum_VenueType::WORLDWIDE );
        $venue_categories = $aoVenuesCategoriesList->getList();
        $venue_categories[0] = Warecorp::t("[ CHOOSE CATEGORY ]");
        ksort($venue_categories);
	    
        $this->view->form         = $form ;
        $this->view->formErrors1  = count($form->getErrorMessages()) > 0 ? $form->getErrorMessages() : false; 
        $this->view->aData        = $aParams;
        $this->view->mode         = 'edit';
        $this->view->privacy      = array(0 => Warecorp_Enum_PrivacyType::PRIVACY_PUBLIC, 1 => Warecorp_Enum_PrivacyType::PRIVACY_PRIVATE);
        $this->view->categories   = $venue_categories;

        $output = $this->view->getContents ( 'groups/calendar/ww.venues.add.edit.tpl' ) ;
        $objResponse->addAssign ( 'worldwide_venue_body', 'innerHTML', $output ) ;
    }
} else {
    $objResponse->addRedirect($this->currentGroup->getGroupPath('summary'));
}
?>
