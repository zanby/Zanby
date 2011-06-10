<?php
    Warecorp::addTranslation("/modules/search/xajax/event.add.to.my.php.xml");
    $objResponse = new xajaxResponse () ;
    
    /* check params */
    if ( empty($this->params['id']) || empty($this->params['uid']) ) {
        Warecorp_Access::redirectToLoginXajax($objResponse);
    }    
    $this->params['handle'] = empty($this->params['handle']) ? false : $this->params['handle'];    

    /* check user */
    if ( null === $this->_page->_user->getId() ) {
        Warecorp_Access::redirectToLoginXajax($this->_page->Xajax, BASE_URL.'/'.LOCALE.'/search/events/preset/new/');
    }
        
    //FIXME определить , какая таймзона является дефолтовой
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того,
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    /**
    * Check event
    */
    $objEvent = new Warecorp_ICal_Event($this->params['id']);
    if ( null === $objEvent->getId() ) {
        $objResponse->showAjaxAlert(Warecorp::t('We are sorry, event was not found'));
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    $objEvent = new Warecorp_ICal_Event($this->params['uid']);
    if ( null === $objEvent->getId() ) {
        $objResponse->showAjaxAlert(Warecorp::t('We are sorry, event was not found'));    
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;               
    }
    
    /** Editor Roman Gabrusenok **/
    //$objEvent->copy($this->_page->_user);
    //$objResponse->showAjaxAlert(Warecorp::t('Event was added'));

    /**
     * @see issue #10184
     */
    $objAttendee = $objEvent->getAttendee()->findAttendee( $this->_page->_user );
    if ( null !== $objAttendee ) {
        if ( !$objEvent->getSharing()->isShared($this->_page->_user) ) {
            $objEvent->getSharing()->add($this->_page->_user);
            $objResponse->showAjaxAlert(Warecorp::t('Event was added'));            
        }
    }
    if ( $objEvent->getPrivacy() == Warecorp_ICal_Enum_Privacy::PRIVACY_PUBLIC ) {
        $objInvite = $objEvent->getInvite();
        if ( $objInvite->getAllowGuestToInvite() ) {
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
    } else {
        $objResponse->showAjaxAlert(Warecorp::t('Access Denied'));
    }
    
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;     
