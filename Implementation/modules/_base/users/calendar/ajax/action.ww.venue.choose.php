<?php
$call_set_venue = false;
if (!empty($venueId)) {
    $venue = new Warecorp_Venue_Item($venueId);
} elseif (!empty($_SESSION['U_worldwide_venueId'])) {
    $venue = new Warecorp_Venue_Item($_SESSION['U_worldwide_venueId']);
    if ($venue->getId() === null) {
        unset($_SESSION['U_worldwide_venueId']);
        $call_set_venue = true;
    } else {
        $venueId = $_SESSION['U_worldwide_venueId'];
    }
} else {
    $call_set_venue = true;
}

if ($call_set_venue === false) {
    $objResponse = new xajaxResponse();
    $objResponse->addClear('worldwide_venue_body', 'innerHTML');
    $objResponse->addScript("changevenueto('worldwide')");
    $_SESSION['U_worldwide_venueId'] = $venueId;

    $this->view->venue = $venue;

    $objResponse->addScript("setVenueId({$venueId})");
    $objResponse->addScript('document.getElementById("vt_2").checked = true;');

    $output = $this->view->getContents('users/calendar/ww.venues.view.tpl');
    $objResponse->addAssign('worldwide_venue_body', 'innerHTML', $output);
} else {
    $objResponse = $this->setWWVenueAction();
    $objResponse->addScript("changevenueto('worldwide')");
    $objResponse->addScript("setVenueId('')");
}
