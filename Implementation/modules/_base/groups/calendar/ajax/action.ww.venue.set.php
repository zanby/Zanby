<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.ww.venue.set.php.xml');
	$objResponse = new xajaxResponse();
	$objResponse->addClear('worldwide_venue_body', 'innerHTML');
	
	$venueId = ( null != $venueId) ? $venueId : null;
	
    $aoVenuesList = new Warecorp_Venue_List();
    $aoVenuesList->setOwnerId( $this->currentGroup->getId() );
    $aoVenuesList->setType( Warecorp_Venue_Enum_VenueType::WORLDWIDE );
    $aoVenuesList->returnAsAssoc();
    
    $venuesWorldwideList = $aoVenuesList->getList();
    $venuesWorldwideList[0] = Warecorp::t('[ CHOOSE VENUE ]');
    ksort($venuesWorldwideList);
    unset($_SESSION['G_worldwide_venueId']);
    $objResponse->addScript("setVenueId('');");
	$this->view->venuesWorldwideList = $venuesWorldwideList;
    
    $output = $this->view->getContents('groups/calendar/ww.venues.index.tpl');
	$objResponse->addAssign('worldwide_venue_body', 'innerHTML', $output);
?>
