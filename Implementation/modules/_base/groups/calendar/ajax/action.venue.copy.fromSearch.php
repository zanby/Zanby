<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.venue.copy.fromSearch.php.xml');
// Common data
$objResponse = new xajaxResponse ( );

if (! empty ( $aParams )) {
	$venue = new Warecorp_Venue_Item ( $aParams );

	    $venue_tags  = $venue->getTagsList();
    if ($venue_tags) {
        foreach ($venue_tags as &$_tag) {
            $_tag = $_tag->getPreparedTagName();
        }
        $tags = implode(' ',$venue_tags);
    }

	$copyVenue = clone ($venue);
	
	$copyVenue->setId ( null );
	$copyVenue->setOwnerId ( $this->currentGroup->getId () );
	$copyVenue->setOwnerType ( Warecorp_Venue_Enum_OwnerType::GROUP );
	
	if ($copyVenue->save ()) {
        if (isset($tags)) $copyVenue->addTags($tags);
		$objResponse->showAjaxAlert( Warecorp::t('Venue is Saved!') );
	} else {
		$objResponse->showAjaxAlert( Warecorp::t("Venue isn't Saved!") );
	}
}
//$output = $this->view->getContents ( 'users/calendar/venues.add.edit.tpl' ) ;
//$objResponse->addAssign ( 'saved_simple_venue_body', 'innerHTML', $output ) ;
?>