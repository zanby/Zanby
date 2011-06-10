<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.venue.venue.copy.php.xml");
    $objResponse = new xajaxResponse();
    $venue = new Warecorp_Venue_Item($venueId);
                
    $this->view->venue = $venue;
    
    $Content = $this->view->getContents('users/calendar/venues.copy.popup.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Copy venue" ));
    $popup_window->content($Content);
    $popup_window->width(500)->height(350)->open($objResponse);
