<?php
Warecorp::addTranslation('/modules/groups/calendar/action.event.docget.php.xml');
    if ( isset($this->params['docid']) && floor($this->params['docid']) != 0 && isset($this->params['id']) && floor($this->params['id']) != 0 ) {
        if (Warecorp_Document_Item::isDocumentExists($this->params['docid'])) {
            $Doc = new Warecorp_Document_Item($this->params['docid']);
            $objEvent = new Warecorp_ICal_Event($this->params['id']);
            $objAttendeeList = new Warecorp_ICal_Attendee_List($objEvent); 
            
            //Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentUser, $this->_page->_user)) ||
            //((( $objEvent->getPrivacy() == 0 ) && Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentUser, $this->_page->_user)))) &&
            
            if ( ( ( ( null !== $objAttendeeList->findAttendee($this->_page->_user) ) && $Doc->isPrivate()) || (!$Doc->isPrivate()) )  
                && Warecorp_ICal_AccessManager_Factory::create()->canViewEvent($objEvent, $this->currentGroup, $this->_page->_user) ) {
                header("Content-Type: " . $Doc->getMimeType());
                header("Content-Length: ". filesize(DOC_ROOT.$Doc->getFilePath()));
                header("Content-Disposition: attachment; filename=\"" . $Doc->getOriginalName() . "\"");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Cache-Control: must-revalidate");
                header("Content-Location: ".$Doc->getOriginalName());
                readfile(DOC_ROOT.$Doc->getFilePath());
                exit;
            }
        }
    }
    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();  
    $this->view->errorMessage = Warecorp::t('Sorry, you can not view this document');
    $this->view->backToEvent = true ;
    $this->view->eventId = $this->params['id'] ;
    $this->view->bodyContent = 'groups/calendar/action.event.error.message.tpl';
    return;
    //$this->_redirect($this->currentUser->getUserPath('documents'));
    
 
    
