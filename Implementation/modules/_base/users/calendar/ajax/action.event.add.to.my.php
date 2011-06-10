<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.add.to.my.php.xml");
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

    /** Editor Roman Gabrusenok **/
    //$objEvent->copy($this->_page->_user);
    //$objResponse->showAjaxAlert(Warecorp::t('Event was added'));

    $objInvite = $objEvent->getInvite();
    $receivers = $objInvite->getTo();

    if (!$objEvent->getSharing()->isShared($this->_page->_user) && preg_match("/^".$this->_page->_user->getLogin()."$/i", $receivers)) {
        $objEvent->getSharing()->add($this->_page->_user);
        $objResponse->showAjaxAlert(Warecorp::t('Event was added'));
        $objResponse->addRedirect($objEvent->entityURL());
    }
    elseif ($objEvent->getSharing()->isShared($this->_page->_user) && preg_match("/^".$this->_page->_user->getLogin()."$/i", $receivers)) {
        $objResponse->addRedirect($objEvent->entityURL());
    }

    $receivers = rtrim($receivers, " ,") . ", " . $this->_page->_user->getLogin();

    $receivers = Warecorp_Mail_Template::validateRecipientsFormString(new Warecorp_User('id', $objEvent->getCreatorId()),  $receivers);

    if ($objEvent->getPrivacy() == Warecorp_ICal_Enum_Privacy::PRIVACY_PUBLIC) {
        if ($objInvite->getAllowGuestToInvite()) {
            /**
             * @see issue #10184
             */
            $objAttendee = new Warecorp_ICal_Attendee();
            $objAttendee->setEventId($objEvent->getAttendee()->getEventId());
            $objAttendee->setOwnerType(Warecorp_ICal_Enum_OwnerType::USER);
            $objAttendee->setOwnerId($this->_page->_user->getId());
            $objAttendee->setEmail(new Zend_Db_Expr('NULL'));            
            $objAttendee->setAnswer('NONE');
            $objAttendee->setAnswerText('');
            $objAttendee->setPhone('');
            $objAttendee->save();
            /**
             * @see issue #10184
             */
            $recipients = Warecorp_ICal_Invitation::prepareRecipientsFromString($this->_page->_user, $this->_page->_user->getLogin());
            $objInvite->mergeRecipients( $recipients );
        } else {
            $objEvent->getSharing()->add($this->_page->_user);
        }
        $objResponse->showAjaxAlert(Warecorp::t('Event was added'));
        $objResponse->addRedirect($objEvent->entityURL());
    } else {
        $objResponse->showAjaxAlert(Warecorp::t('Access Denied'));
    }
    /** **/