<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.venue.delete.php.xml");
    $objResponse = new xajaxResponse();
    $venue = new Warecorp_Venue_Item($venueId);
    
    $this->view->venue = $venue;
    
    $Content = $this->view->getContents('users/calendar/venues.delete.popup.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Delete venue" ));
    $popup_window->content($Content);
    $popup_window->width(500)->height(350)->open($objResponse);
