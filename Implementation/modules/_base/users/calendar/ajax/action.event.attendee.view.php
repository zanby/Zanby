<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.attendee.view.php.xml");
    $objResponse = new xajaxResponse();

    //FIXME определить , какая таймзона является дефолтовой 
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того, 
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';  
    
    /*
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentUser->getUserPath('calendar.month.view');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        return $objResponse;
    }
    */
    
    /**
    * Check event
    */
    if ( !$id || !$uid ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));
        return $objResponse;
    }
    $objEvent = new Warecorp_ICal_Event($uid);
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));
        return $objResponse;
    }
    $objEvent = new Warecorp_ICal_Event($id);
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));
        return $objResponse;
    }

    /**
     * Find the event first date
     */
    $lstEventsObj = new Warecorp_ICal_Event_List();
    $lstEventsObj->setTimezone($currentTimezone);
    $tz = date_default_timezone_get();
    date_default_timezone_set($currentTimezone);
    $objNowDate = new Zend_Date();
    date_default_timezone_set($tz);            
	$strFirstDate = $lstEventsObj->findFirstEventDate($objEvent, $objNowDate);
	if ( null !== $strFirstDate ) {
	    $objEvent->setTimezone($currentTimezone);
	    $objEvent->setDtstart($strFirstDate);
	}

    $this->view->objEvent = $objEvent;
    $this->view->currentTimezone = $currentTimezone;
    $Content = $this->view->getContents('users/calendar/ajax/action.event.attendee.view.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Attendance Details"));
    $popup_window->content($Content);
    $popup_window->width(550)->height(350)->open($objResponse);
