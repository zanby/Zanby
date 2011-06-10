<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.remove.guest.php.xml");
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
    
    if ( !Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentUser, $this->_page->_user ) ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, you can not manage this event');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));    
        return $objResponse;        
    }
    
    $form = new Warecorp_Form('form_remove_guest', 'POST');
    
    if ( !$handle ) {        
        $linkUrl = "xajax_doEventRemoveGuest('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_remove_guest')); return false;";
                
        $this->view->event_id = $id;
        $this->view->uid = $uid;
        $this->view->objEvent = $objEvent;
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
        $Content = $this->view->getContents('users/calendar/ajax/action.event.remove.guest.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Remove Guest(s)"));
        $popup_window->content($Content);
        $popup_window->width(600)->height(350)->open($objResponse);
    }     
    else {            
        $_REQUEST['_wf__form_remove_guest'] = 1;

        /**
        * +-----------------------------------------------------------------------
        * | Handle Form Callback
        * +-----------------------------------------------------------------------
        */
        if ( $form->validate($handle) ) {
            $strCustomMessage = (isset($handle['message']) && trim($handle['message']) != '') ? trim($handle['message']) : null;
            if ( isset($handle['attendee']) && sizeof($handle['attendee']) != 0 ) {
                if ( $handle['from'] == 0 ) {
                    $objSender = clone $this->_page->_user;
                    $objSender->setEmail('calendar@'.DOMAIN_FOR_EMAIL);
                } else {
                    $objSender = clone $this->_page->_user;
                }
                
                /**
                 * @see issue #10184
                 */
                $objEvent->getInvite()->setSendFrom( $objSender )->setCustomMessage( $strCustomMessage )->__removeAttendees( $this->_page->_user, $handle['attendee'] );                    
            }      
            $objResponse->addScript('popup_window.close();');
            $objResponse->addScript('document.location.reload();');
        }
    }
