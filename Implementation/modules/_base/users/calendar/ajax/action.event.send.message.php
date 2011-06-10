<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.send.message.php.xml");
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
    
    if ( !Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentUser, $this->_page->_user ) ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, you can not manage this event');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));    
        return $objResponse;        
    }
    
    $form = new Warecorp_Form('form_message_to_guest', 'POST'); 
    $form->addRule( 'message', 'required', Warecorp::t('Field \'Message\' is required' ));
    
    if ( !$handle ) {
        $linkUrl = "xajax_doEventSendMessage('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_message_to_guest')); return false;";
        
        $this->view->form = $form;
        $this->view->linkUrl = $linkUrl;
        
        $formParams = array();
        if ( 'calendar@'.DOMAIN_FOR_EMAIL == $objEvent->getInvite()->getFrom() ) {
            $formParams['from'] = 0;
        } else {
            $tmpUser = new Warecorp_User('email', $objEvent->getInvite()->getFrom());
            $formParams['from'] = $tmpUser->getId();
        }
        $this->view->formParams = $formParams;
        
    
        $Content = $this->view->getContents('users/calendar/ajax/action.event.send.message.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Send a Message To Guest(s)"));
        $popup_window->content($Content);
        $popup_window->width(500)->height(350)->open($objResponse);
        
        $objResponse->addClear('message', 'value');
        $objResponse->addScript('YAHOO.util.Dom.get("to_ALL").checked = true;');
        

    } else {
        $_REQUEST['_wf__form_message_to_guest'] = 1;
        /**
        * +-----------------------------------------------------------------------
        * | Handle Form Callback
        * +-----------------------------------------------------------------------
        */
        if ( $form->validate($handle) ) {
            if ( $handle['from'] == 0 ) {
                $objSender = clone $this->_page->_user;
                $objSender->setEmail('calendar@'.DOMAIN_FOR_EMAIL);
            } else {
                $objSender = clone $this->_page->_user;
            }
            $objEvent->getInvite()->setSendFrom($objSender)->sendMessageToGuests($handle['to'], $handle['message'], array($this->_page->_user->getId()));
            $objResponse->addScript('popup_window.close();');
            $objResponse->showAjaxAlert(Warecorp::t('Message sent'));
        } else {
            $linkUrl = "xajax_doEventSendMessage('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_message_to_guest')); return false;";
            
            $objResponse->addAssign("ajaxMessagePanelTitle", "innerHTML", Warecorp::t("Send a Message To Guest(s)"));
            $this->view->form = $form;
            $this->view->linkUrl = $linkUrl;
        
            $formParams = array();
            $formParams['to'] = $handle['to'];
            $formParams['from'] = $handle['from'];
            $this->view->formParams = $formParams;
            
            $Content = $this->view->getContents('users/calendar/ajax/action.event.send.message.tpl');
            $objResponse->addAssign("ajaxMessagePanelContent", "innerHTML", $Content);
        }
    }
