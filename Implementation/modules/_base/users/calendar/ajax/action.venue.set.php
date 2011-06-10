<?php
	$objResponse = new xajaxResponse();
	$objResponse->addClear('simple_venue_body', 'innerHTML');
	$objResponse->addScript("changeto('add')");

	$aoVenuesList = new Warecorp_Venue_List();
    $aoVenuesList->setOwnerId( $this->_page->_user->getId() );
    $aoVenuesList->setOwnerType( Warecorp_Venue_Enum_OwnerType::USER );
    $aoVenuesList->setType( Warecorp_Venue_Enum_VenueType::SIMPLE );
    $aoVenuesList->returnAsAssoc();
	
	$venuesSimpleList = $aoVenuesList->getList();
	$venuesSimpleList[0] = '[ CHOOSE VENUE ]';
	ksort($venuesSimpleList);

    $this->view->venuesSimpleList = $venuesSimpleList;
    unset($_SESSION['U_simple_venueId']);
    $objResponse->addScript("setVenueId('');");
    $output = $this->view->getContents('users/calendar/venues.index.tpl');
	$objResponse->addAssign('simple_venue_body', 'innerHTML', $output);
?>
