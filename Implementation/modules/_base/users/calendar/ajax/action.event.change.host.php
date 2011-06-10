<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.change.host.php.xml");
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

    $form = new Warecorp_Form('form_change_host', 'POST');
    $form->addRule( 'username', 'required', Warecorp::t('Field \'Username\' is required' ));

    if ( !$handle ) {

        $linkUrl = "xajax_doChangeHost('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_change_host')); return false;";

        $this->view->event_id = $id;
        $this->view->uid = $uid;
        $this->view->objEvent = $objEvent;
        $this->view->form = $form;
        $this->view->linkUrl = $linkUrl;

        $Content = $this->view->getContents('users/calendar/ajax/action.event.change.host.tpl');

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title("Change Event Host");
        $popup_window->content($Content);
        $popup_window->width(450)->height(260)->open($objResponse);
    }
    else {
        $_REQUEST['_wf__form_change_host'] = 1;

        /**
        * +-----------------------------------------------------------------------
        * | Handle Form Callback
        * +-----------------------------------------------------------------------
        */
        if ( $form->validate($handle) ) {
            $objUser = new Warecorp_User('login', $handle['username']);
            if ( null === $objUser->getId() ) {
                $form->addCustomErrorMessage(Warecorp::t('Sorry, \'%s\' is not a valid %s username', array($handle['username'], SITE_NAME_AS_STRING)));
            }
            if ($objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::GROUP)
            {
                $groupObject = Warecorp_Group_Factory::loadByID($objEvent->getOwnerId());
                if ($groupObject->getMembers()->isMemberExistsAndApproved($objUser->getId()) != true){
                    $form->addCustomErrorMessage(Warecorp::t('Sorry, \'%s\' is not a member of current group', array($handle['username'])));
                }
            }
            if (!$objEvent->getAttendee()->setDateFilter($objEvent->getDtstart()->toString('yyyy-MM-ddTHHmmss'))->findAttendee($objUser)) {
                $form->addCustomErrorMessage(Warecorp::t("Sorry, '%s' is not invited to event", array($handle['username'])));
            }
            if ( $form->isValid() ) {

                if ( !Warecorp_ICal_HostRequest::isRequestExists($objEvent->getRootId(), $objUser->getId()) ) {
                    $request = new Warecorp_ICal_HostRequest();
                    $request->setEventId($objEvent->getRootId());
                    $request->setHostId($objUser->getId());
                    $request->setStatus('pending');
                    $request->save();
                }
                $objResponse->addScript('popup_window.close();');
                $objResponse->addScript('document.location.reload();');
            }
        }
        if ( !$form->isValid() ) {
            $linkUrl = "xajax_doChangeHost('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_change_host')); return false;";

            $this->view->event_id = $id;
            $this->view->uid = $uid;
            $this->view->objEvent = $objEvent;
            $this->view->form = $form;
            $this->view->linkUrl = $linkUrl;
            $this->view->formParams = array('username' => $handle['username']);

            $Content = $this->view->getContents('users/calendar/ajax/action.event.change.host.tpl');
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t("Change Event Host"));
            $popup_window->content($Content);
            $popup_window->width(450)->height(350)->open($objResponse);
        }
    }
