<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.copy.php.xml');
    $objResponse = new xajaxResponse();
    $form = new Warecorp_Form('form_copy_event');
    
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('calendar.month.view');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        return $objResponse;
    }
     
    //FIXME определить , какая таймзона является дефолтовой 
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того, 
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    /**
    * Check event
    */
    if ( !$id || !$uid ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));    
        return $objResponse;
    }
    $objEvent = new Warecorp_ICal_Event($id);
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));    
        return $objResponse;
    }
    $objEvent = new Warecorp_ICal_Event($uid);
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));    
        return $objResponse;
    }
    
    if ( !$handle ) {
        $this->view->objEvent = $objEvent; 
        $this->view->form = $form; 
        $Content = $this->view->getContents('groups/calendar/ajax/action.event.copy.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Copy Event"));
        $popup_window->content($Content);
        $popup_window->width(270)->height(350)->open($objResponse);

    }     
    else {
        if ( trim($handle['event_name']) == '' ) {
            $this->view->objEvent = $objEvent; 
            $this->view->form = $form; 
            $this->view->errors = array(Warecorp::t('Event Title is required'));
            $Content = $this->view->getContents('groups/calendar/ajax/action.event.copy.tpl');
            
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t("Copy Event"));
            $popup_window->content($Content);
            $popup_window->width(270)->height(350)->open($objResponse);

        } else {
            $_SESSION['_calendar_']['_copy_event_']['_event_name_'] = $handle['event_name'];
            $objResponse->addScript('popup_window.close();');
            $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.event.copy.do/id/'.$objEvent->getId().'/uid/'.$objEvent->getUid().''));
        }
    }      
