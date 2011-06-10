<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.send.message.php.xml');
    $objResponse = new xajaxResponse();

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
    
    if ( !Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentGroup, $this->_page->_user ) ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, you can not manage this event');
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));    
        return $objResponse;        
    }
    
    $form = new Warecorp_Form('form_message_to_guest', 'POST'); 
    $form->addRule( 'message', 'required', Warecorp::t("Field 'Message' is required" ));
    
    if ( !$handle ) {
        $linkUrl = "xajax_doEventSendMessage('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_message_to_guest')); return false;";
        
        $this->view->form = $form;
        $this->view->linkUrl = $linkUrl;
        
        $formParams = array();
        $formParams['event_invitations_from'] = $objEvent->getInvite()->getFrom();
        $this->view->formParams = $formParams;
    
        $Content = $this->view->getContents('groups/calendar/ajax/action.event.send.message.tpl');
        
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
            if ( $this->_page->_user->getEmail() == $handle['event_invitations_from']) {
                $from = clone $this->_page->_user;
            } elseif ( $this->currentGroup->getGroupEmail() == $handle['event_invitations_from'] ) {
                $from = clone $this->_page->_user;
                $from->setEmail($handle['event_invitations_from']);
            } else {
                $from = clone $this->_page->_user;
                $from->setEmail($handle['event_invitations_from']);
            }
            $objEvent->getInvite()->setSendFrom($from)->sendMessageToGuests($handle['to'], $handle['message'], array($this->_page->_user->getId()));
            $objResponse->addScript('popup_window.close();');
            $objResponse->showAjaxAlert(Warecorp::t('Message sent'));
        } else {
            $linkUrl = "xajax_doEventSendMessage('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_message_to_guest')); return false;";
            
            $this->view->form = $form;
            $this->view->linkUrl = $linkUrl;
        
            $formParams = array();
            $formParams['to'] = $handle['to'];
            $formParams['event_invitations_from'] = $handle['event_invitations_from'];
            $this->view->formParams = $formParams;
            
            $Content = $this->view->getContents('groups/calendar/ajax/action.event.send.message.tpl');
            
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t("Send a Message To Guest(s)"));
            $popup_window->content($Content);
            $popup_window->width(500)->height(350)->open($objResponse);

        }
    }
