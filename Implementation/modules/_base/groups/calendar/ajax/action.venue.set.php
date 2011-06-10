<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.venue.set.php.xml');
	$objResponse = new xajaxResponse();
	$objResponse->addClear('simple_venue_body', 'innerHTML');
	$objResponse->addScript("changeto('add')");

	$aoVenuesList = new Warecorp_Venue_List();
    $aoVenuesList->setOwnerId( $this->currentGroup->getId() );
    $aoVenuesList->setOwnerType( Warecorp_Venue_Enum_OwnerType::GROUP );
    $aoVenuesList->setType( Warecorp_Venue_Enum_VenueType::SIMPLE );
    $aoVenuesList->returnAsAssoc();
	
	$venuesSimpleList = $aoVenuesList->getList();
	$venuesSimpleList[0] = Warecorp::t('[ CHOOSE VENUE ]');
	ksort($venuesSimpleList);
    unset($_SESSION['G_simple_venueId']);
    $objResponse->addScript("setVenueId('');");
	$this->view->venuesSimpleList = $venuesSimpleList;

    $output = $this->view->getContents('groups/calendar/venues.index.tpl');
	$objResponse->addAssign('simple_venue_body', 'innerHTML', $output);
?>
