<?php
	$objResponse = new xajaxResponse();
	$objResponse->addClear('worldwide_venue_body', 'innerHTML');
	
	$venueId = ( null != $venueId) ? $venueId : null;
	
    $aoVenuesList = new Warecorp_Venue_List();
    $aoVenuesList->setOwnerId( $this->_page->_user->getId() );
    $aoVenuesList->setType( 'worldwide' );
    $aoVenuesList->returnAsAssoc();
    
    $venuesWorldwideList = $aoVenuesList->getList();
    $venuesWorldwideList[0] = '[ CHOOSE VENUE ]';
    ksort($venuesWorldwideList);
    unset($_SESSION['U_worldwide_venueId']);
    $objResponse->addScript("setVenueId('');");
    $this->view->venuesWorldwideList = $venuesWorldwideList;
    
    $output = $this->view->getContents('users/calendar/ww.venues.index.tpl');
	$objResponse->addAssign('worldwide_venue_body', 'innerHTML', $output);
?>
