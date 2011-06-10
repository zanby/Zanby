<?php
$call_set_venue = false;
if (!empty($venueId)) {
    $venue = new Warecorp_Venue_Item($venueId);
} elseif (!empty($_SESSION['U_simple_venueId'])) {
    $venue = new Warecorp_Venue_Item($_SESSION['U_simple_venueId']);    
    if ($venue->getId() === null) {        
        unset($_SESSION['U_simple_venueId']);
        $call_set_venue = true;
    } else {
        $venueId = $_SESSION['U_simple_venueId'];
    }
} else {
    $call_set_venue = true;   
}
    
if ($call_set_venue === false) {
    $objResponse = new xajaxResponse();
    $objResponse->addClear('simple_venue_body', 'innerHTML');
    $objResponse->addScript("changevenueto('simple')");
    $_SESSION['U_simple_venueId'] = $venueId;

    $this->view->venue = $venue;

    $objResponse->addScript("setVenueId({$venueId})");

    $output = $this->view->getContents('users/calendar/venues.view.tpl');
    $objResponse->addAssign('simple_venue_body', 'innerHTML', $output);
} else {
    $objResponse = $this->setVenueAction();
    $objResponse->addScript("changevenueto('simple')");
    $objResponse->addScript("setVenueId('')");
}
