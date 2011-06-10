<?php

    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();
    
    /**
    * Register Ajax Functions
    */
    $this->_page->Xajax->registerUriFunction( "bookmarkit", "/ajax/bookmarkit/" );
    $this->_page->Xajax->registerUriFunction( "addbookmark", "/ajax/addbookmark/" );
    $this->_page->Xajax->registerUriFunction( "addToFriends", "/ajax/addToFriends/" );
    $this->_page->Xajax->registerUriFunction( "addToFriendsDo", "/ajax/addToFriendsDo/" );

    if ( isset($_SESSION['_calendar_']) && isset($_SESSION['_calendar_']['_confirmPage_']) ) {
        if ( isset($_SESSION['_calendar_']['_confirmPage_']['eventId']) ) {
            $objEvent = new Warecorp_ICal_Event($_SESSION['_calendar_']['_confirmPage_']['eventId']);  
            $this->view->objEvent = $objEvent;      
        }                
        if ( isset($_SESSION['_calendar_']['_confirmPage_']['confirmMessage']) ) {
            $this->view->confirmMessage = $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'];
        } else {
            $this->view->confirmMessage = null;
        }
        $this->view->confirmMode = $_SESSION['_calendar_']['_confirmPage_']['confirmMode'];
        if ( isset($_SESSION['_calendar_']['_confirmPage_']['confirmRedirectURL']) ) {
            $this->view->confirmRedirectURL = $_SESSION['_calendar_']['_confirmPage_']['confirmRedirectURL'];
        } else {
            $this->view->confirmRedirectURL = $this->currentUser->getUserPath('calendar.month.view');
        }
    }

    $this->view->bodyContent = 'users/calendar/action.confirm.tpl';     
    $this->view->friendsAssoc = $this->_page->_user->getId() ? $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array() ;
