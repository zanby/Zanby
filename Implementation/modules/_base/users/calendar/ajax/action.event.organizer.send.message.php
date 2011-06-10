<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.organizer.send.message.php.xml");
    $objResponse = new xajaxResponse();

    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentUser->getUserPath('calendar.month.view');
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
    $objEvent = new Warecorp_ICal_Event($uid);
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));    
        return $objResponse;
    }
    
    $form = new Warecorp_Form('form_message_to_organizer', 'POST'); 
    $form->addRule( 'message', 'required', Warecorp::t('Field \'Message\' is required'));
    
    if ( !$handle ) {
        $linkUrl = "xajax_doEventOrganizerSendMessage('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_message_to_organizer')); return false;";
        
        $this->view->form = $form;
        $this->view->linkUrl = $linkUrl;
        $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create(); 
    
        $Content = $this->view->getContents('users/calendar/ajax/action.event.organizer.send.message.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Send a Message To Organizer"));
        $popup_window->content($Content);
        $popup_window->width(500)->height(350)->open($objResponse);

        $objResponse->addClear('message', 'value');

    } else {
        $_REQUEST['_wf__form_message_to_organizer'] = 1;
        /**
        * +-----------------------------------------------------------------------
        * | Handle Form Callback
        * +-----------------------------------------------------------------------
        */
        if ( $form->validate($handle) ) {
            $objEvent->getInvite()->setSendFrom($this->_page->_user)->sendMessageToOrganizer($handle['message']);
            $objResponse->addScript('popup_window.close();');
            $objResponse->showAjaxAlert(Warecorp::t('Message sent'));
        } else {
            $linkUrl = "xajax_doEventOrganizerSendMessage('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_message_to_organizer')); return false;";
            
            $this->view->form = $form;
            $this->view->linkUrl = $linkUrl;
            $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create(); 
        
            $Content = $this->view->getContents('users/calendar/ajax/action.event.organizer.send.message.tpl');
                        
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t("Send a Message To Organizer"));
            $popup_window->content($Content);
            $popup_window->width(500)->height(350)->open($objResponse);
            
            $objResponse->addClear('message', 'value');
        }
    }
