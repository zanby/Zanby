<?php
$objResponse = new xajaxResponse();
if ( null != $newName ) {
    $venue = new Warecorp_Venue_Item($venueId);

    $venue_tags  = $venue->getTagsList();

    //print_r ( $venue_tags );
    //exit;

    if ($venue_tags) {
        foreach ($venue_tags as &$_tag) {
            $_tag = $_tag->getPreparedTagName();
        }
        $tags = implode(' ',$venue_tags);
    }

	$copyVenue = clone($venue);
	
	$copyVenue->setId( null );
	$copyVenue->setName( $newName );
	
	if ($copyVenue->save()){
	    if (isset($tags)) $copyVenue->addTags($tags);
		$objResponse->addScript("popup_window.close();");
        if ($venue_type == 'simple') {
            $objResponse->addScript("xajax_loadSavedVenues(getSearches());");
        } elseif ($venue_type == 'worldwide') {
            $objResponse->addScript("xajax_loadSavedWWVenues(getWWSearches());");
        }
	}
}
?>