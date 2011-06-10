<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.venue.copy.php.xml');
    $objResponse = new xajaxResponse();
    $venue = new Warecorp_Venue_Item($venueId);
                
    $this->view->venue = $venue;
    $Content = $this->view->getContents('groups/calendar/venues.copy.popup.tpl');

    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Copy venue"));
    $popup_window->content($Content);
    $popup_window->width(500)->height(350)->open($objResponse);
