<?php
Warecorp::addTranslation('/modules/groups/calendar/action.event.reply.request.php.xml');

    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();
    
    if ( null === $this->_page->_user->getId() ) {
        $this->_redirectToLogin();
    }
    $objRequest = $this->getRequest();
    
    if ( null === $objRequest->getParam('id', null) ) $this->_redirect('/');

    $request = new Warecorp_ICal_HostRequest($objRequest->getParam('id', null));
    if ( null === $request->getId() || $this->_page->_user->getId() != $request->getHostId() ) $this->_redirect('/');

    $objEvent = new Warecorp_ICal_Event($request->getEventId());
    if ( null === $objRequest->getParam('mode', null) ) {                
        $this->view->objEvent = $objEvent;
        $this->view->linkURL = $this->currentGroup->getGroupPath('calendar.event.apply.request/id/'.$request->getId().'/mode');
        $this->view->bodyContent = 'groups/calendar/action.event.apply.request.tpl';    
    } else {
        if ( $objRequest->getParam('mode') == 'accept' ) {
            $request->acceptRequest();
            $this->_redirect($objEvent->entityURL());
        } elseif ( $objRequest->getParam('mode') == 'decline' ) {
            $request->declineRequest();
            $this->_redirect($this->_page->_user->getUserPath('calendar.month.view'));
        } else {
            $this->view->objEvent = $objEvent;
            $this->view->linkURL = $this->currentGroup->getGroupPath('calendar.event.apply.request/id/'.$request->getId().'/mode');
            $this->view->bodyContent = 'groups/calendar/action.event.apply.request.tpl';            
        }
    }
    
    
