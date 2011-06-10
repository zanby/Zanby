<?php
Warecorp::addTranslation('/modules/groups/xajax/action.loadVenue.php.xml');

$objResponse = new xajaxResponse();

$newVenue = new Warecorp_Venue_Item($venueId);

$objResponse->addAssign("event[venue_name]", "value", $newVenue->name);
$objResponse->addAssign("event[venue_type1]", "selectedIndex", $newVenue->categoryId);
$objResponse->addAssign("event[venue_street_address1]", "value", $newVenue->address1);
$objResponse->addAssign("event[venue_street_address2]", "value", $newVenue->address2);
$objResponse->addAssign("event[venue_phone]", "value", $newVenue->phone);
$objResponse->addAssign("event[venue_website]", "value", $newVenue->website);
$objResponse->addAssign("event[venue_description]", "value", $newVenue->description);
$objResponse->addAssign("event[venue_private]", "selectedIndex", $newVenue->private);
$objResponse->addAssign("event[venue_saved]", "value", $venueId);


$objResponse->addScript("changeto('add');");