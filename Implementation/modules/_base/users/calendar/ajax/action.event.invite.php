<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.invite.php.xml");
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

    $objInvite = $objEvent->getInvite();
    if (!$objInvite->getAllowGuestToInvite() && !Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentUser, $this->_page->_user ) ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, you can not manage this event');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));
        return $objResponse;
    }

    $form = new Warecorp_Form('form_invite_more_people', 'POST');
    $form->addRule( 'event_invitations_emails', 'required', Warecorp::t('Field \'To\' is required' ));


    if ( !$handle ) {
        $linkUrl = "xajax_doEventInvite('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_invite_more_people')); return false;";

        $this->view->event_id = $id;
        $this->view->uid = $uid;
        $this->view->objEvent = $objEvent;
        $this->view->form = $form;
        $this->view->linkUrl = $linkUrl;

        $formParams = array();
        if ($objInvite->getAllowGuestToInvite() || Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentUser, $this->_page->_user) ) {
            $formParams['event_invitations_emails']     = '';
           /**
            * Subject and Message fields must be clean
            * Redmine bug #1488
            */
            $formParams['event_invitations_subject']    = ''; // $objEvent->getInvite()->getSubject();
            $formParams['event_invitations_message']    = ''; // $objEvent->getInvite()->getMessage();
            /** **/

            if ( 'calendar@'.DOMAIN_FOR_EMAIL == $objEvent->getInvite()->getFrom() ) {
                $formParams['event_invitations_from'] = 0;
            } else {
                $tmpUser = new Warecorp_User('email', $objEvent->getInvite()->getFrom());
                if ( null === $tmpUser->getId() ) $formParams['event_invitations_from'] = 0;
                else $formParams['event_invitations_from'] = $tmpUser->getId();
            }
        } else {
            $formParams['event_invitations_emails']     = '';
            $formParams['event_invitations_subject']    = $objEvent->getInvite()->getSubject();
            $formParams['event_invitations_message']    = $objEvent->getInvite()->getMessage();
            $formParams['event_invitations_from']       = $this->_page->_user->getId();
        }
        $this->view->formParams = $formParams;
        $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();

        $Content = $this->view->getContents('users/calendar/ajax/action.event.invite.tpl');

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Invite More People"));
        $popup_window->content($Content);
        $popup_window->width(450)->height(230)->open($objResponse);

    }
    else {
        $_REQUEST['_wf__form_invite_more_people'] = 1;
        /**
        * +-------------------------------------------------------------------
        * | Validate Invitations
        * +-------------------------------------------------------------------
        */
        /**
         * @see issue #10184
         */
        $recipients = Warecorp_ICal_Invitation::prepareRecipientsFromString($this->_page->_user, $handle['event_invitations_emails']);
        Warecorp_ICal_Invitation::validateRecipients( $recipients, $form );
        
//        $receivers = Warecorp_Mail_Template::validateRecipientsFormString($this->_page->_user, $handle['event_invitations_emails'] );
//        if ( !$receivers['isValid'] ) {
//            if ( sizeof($receivers['invalid']['userEmails']) != 0 ) {
//                $form->addCustomErrorMessage(
//                    ( sizeof( $receivers['invalid']['userEmails'] ) > 1 ) ?
//                    Warecorp::t("Sorry, %s  are not a valid emails", join( ", ", $receivers['invalid']['userEmails'] )) :
//                    Warecorp::t("Sorry, %s  is not a valid email", join( ", ", $receivers['invalid']['userEmails'] )));
//            }
//            if ( sizeof($receivers['invalid']['userNames']) != 0 ) {
//                $form->addCustomErrorMessage(
//                    ( sizeof( $receivers['invalid']['userNames'] ) > 1 ) ?
//                    Warecorp::t("Sorry, %s  are not a valid %s usernames", array(join( ", ", $receivers['invalid']['userNames'] ), SITE_NAME_AS_STRING)) :
//                    Warecorp::t("Sorry, %s  is not a valid %s username", array(join( ", ", $receivers['invalid']['userNames'] ), SITE_NAME_AS_STRING)));
//            }
//            if ( sizeof($receivers['invalid']['groupEmails']) != 0 ) {
//                $form->addCustomErrorMessage(
//                    ( sizeof( $receivers['invalid']['groupEmails'] ) > 1 ) ?
//                    Warecorp::t("Sorry, %s  are not a valid %s group emails", array(join( ", ", $receivers['invalid']['groupEmails'] ), SITE_NAME_AS_STRING)) :
//                    Warecorp::t("Sorry, %s  is not a valid %s group email", array(join( ", ", $receivers['invalid']['groupEmails'] ), SITE_NAME_AS_STRING)));
//            }
//            if ( sizeof($receivers['invalid']['groupNames']) != 0 ) {
//                $form->addCustomErrorMessage(
//                    ( sizeof( $receivers['invalid']['groupNames'] ) > 1 ) ?
//                    Warecorp::t("Sorry, %s  are not a valid %s group names", array(join( ", ", $receivers['invalid']['groupNames'] ), SITE_NAME_AS_STRING)) :
//                    Warecorp::t("Sorry, %s  is not a valid %s group name", array(join( ", ", $receivers['invalid']['groupNames'] ), SITE_NAME_AS_STRING)));
//            }
//            if ( sizeof($receivers['invalid']['groupAccess']) != 0 ) {
//                $form->addCustomErrorMessage(
//                    ( sizeof( $receivers['invalid']['groupAccess'] ) > 1 ) ?
//                    Warecorp::t("Sorry, you can not intite %s groups", join( ", ", $receivers['invalid']['groupAccess'] )) :
//                    Warecorp::t("Sorry, you can not intite %s group", join( ", ", $receivers['invalid']['groupAccess'] )));
//            }
//            if ( sizeof($receivers['invalid']['contactListNames']) != 0 ) {
//                $form->addCustomErrorMessage(
//                    ( sizeof( $receivers['invalid']['contactListNames'] ) > 1 ) ?
//                    Warecorp::t("Sorry, %s  is not a valid mailing list names", join( ", ", $receivers['invalid']['contactListNames'] )) :
//                    Warecorp::t("Sorry, %s  is not a valid mailing list name", join( ", ", $receivers['invalid']['contactListNames'] )));
//            }
//        }


        /**
        * +-----------------------------------------------------------------------
        * | Handle Form Callback
        * +-----------------------------------------------------------------------
        */
        if ( $form->validate($handle) ) {
            /**
            * Send Invitations and add Attendee
            */
            $objEventInvite = $objEvent->getInvite();
            if ( $objInvite->getAllowGuestToInvite() || Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentUser, $this->_page->_user) ) {                
                if ( $handle['event_invitations_from'] ) {
                    $from = new Warecorp_User('id', $handle['event_invitations_from']);
                    if ( $from->getId() == NULL ) { $from = $handle['event_invitations_from']; }
                } else {
                    $from = clone $this->_page->_user;
                    $from->setEmail('calendar@'.DOMAIN_FOR_EMAIL);
                }
                $objEventInvite->setSendFrom($from);
            } else {
                $objEventInvite->setSendFrom($this->_page->_user);
            }
            $objEventInvite->setSubject($handle['event_invitations_subject']);
            $objEventInvite->setMessage($handle['event_invitations_message']);
            
            /**
             * @see issue #10184
             */
            $objEventInvite->mergeRecipients($recipients);   
            $objEventInvite->__saveAttendeeChanges( 'ADD' );
            
            $objResponse->addScript('popup_window.close();');
            $objResponse->addScript('document.location.reload();');
        } else {
            $linkUrl = "xajax_doEventInvite('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_invite_more_people')); return false;";

            $this->view->event_id = $id;
            $this->view->uid = $uid;
            $this->view->objEvent = $objEvent;
            $this->view->form = $form;
            $this->view->linkUrl = $linkUrl;

            $formParams = array();
            $formParams['event_invitations_emails']     = $handle['event_invitations_emails'];
            $formParams['event_invitations_subject']    = $handle['event_invitations_subject'];
            $formParams['event_invitations_message']    = $handle['event_invitations_message'];
            $formParams['event_invitations_from']       = $handle['event_invitations_from'];

            $this->view->formParams = $formParams;
            $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();
            $Content = $this->view->getContents('users/calendar/ajax/action.event.invite.tpl');
            
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t("Invite More People"));
            $popup_window->content($Content)->open($objResponse);
        }
    }
