<?php
	$objResponse = new xajaxResponse();
    $venue = new Warecorp_Venue_Item($venueId);
	if ($venue->delete()){
		$objResponse->addScript ( "popup_window.close();" );
        if ($venue_type == 'simple') {
            $objResponse->addScript ( "xajax_loadSavedVenues(getSearches());");
        } elseif ($venue_type == 'worldwide') {
            $objResponse->addScript ( "xajax_loadSavedWWVenues(getWWSearches());");            
        }

	}
?>