<?php
Warecorp::addTranslation('/modules/groups/blog/xajax/action.confirm.php.xml');
    
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
        /**
         * display round document (zccf, zccf-base, zccf-alt)
         */
        if ( $objEvent->getEventIsPartOfRound() ) {
            $objRound = Warecorp_Round_Item::getCurrentRound($this->currentGroup->getId());
            if ( $objRound->getRoundIsCurrent() && $objEvent->getEventIsPartOfRound() == $objRound->getRoundId() && $objRound->getRoundDocumentId() ) {
                $this->view->objHostDocument = $objRound->getDocument();
                $this->view->denyAutoRedirect = true;
            }
        }
    }
    if ( isset($_SESSION['_calendar_']['_confirmPage_']['confirmMessage']) ) {
        $this->view->confirmMessage = $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'];
    } else {
        $this->view->confirmMessage = null;
    }
    if ( isset($_SESSION['_calendar_']['_confirmPage_']['created_group']) ) {
        $this->view->created_group = 1;
        unset($_SESSION['_calendar_']['_confirmPage_']['created_group']);
    } else {
        $this->view->created_group = 0;
    }
    $this->view->confirmMode = $_SESSION['_calendar_']['_confirmPage_']['confirmMode'];
    if ( isset($_SESSION['_calendar_']['_confirmPage_']['confirmRedirectURL']) ) {
        $this->view->confirmRedirectURL = $_SESSION['_calendar_']['_confirmPage_']['confirmRedirectURL'];
    } else {
        $this->view->confirmRedirectURL = $this->currentGroup->getGroupPath('calendar.month.view');
    }
}

$this->view->bodyContent = 'groups/calendar/action.confirm.tpl'; 
