<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.unshare.php.xml");
    $objResponse = new xajaxResponse();

    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentUser->getUserPath('calendar.list.view');
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

    $objRootEvent = new Warecorp_ICal_Event($objEvent->getRootId());

    if ($this->currentUser->getId() == $this->_page->_user->getId() &&
        $objRootEvent->getSharing()->isShared($this->currentUser))
    {
        if ( !$handle ) {
                $this->view->id = $id;
                $this->view->uid = $uid;

                $Content = $this->view->getContents('users/calendar/ajax/action.event.client.unshare.tpl');

                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->title(Warecorp::t("Unshare Event"));
                $popup_window->content($Content);
                $popup_window->width(400)->height(100)->open($objResponse);
        }
        else {
            $objEvent->getSharing()->delete($this->_page->_user);

            $objResponse->addScript('popup_window.close();');
            $objResponse->addScript('document.location.reload();');

            $this->_page->showAjaxAlert(Warecorp::t('Event was unshared'));
            $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
            //$objResponse->addRedirect($this->_page->_user->getUserPath('calendar') );
        }
    }
