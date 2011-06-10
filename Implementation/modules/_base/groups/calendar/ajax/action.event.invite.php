<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.invite.php.xml');
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
    $objEvent = new Warecorp_ICal_Event($uid);
    if ( null === $objEvent->getId() ) {
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

    if ( !empty($params) ) {
        if ( !empty($params['event_invitations_fbfriends']) ) {
            $this->getRequest()->setParam('event_invitations_fbfriends', $params['event_invitations_fbfriends']);
        }
    }

    $objInvite = $objEvent->getInvite();
    if (!$objInvite->getAllowGuestToInvite() && !Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentGroup, $this->_page->_user ) ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, you can not manage this event');
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));
        return $objResponse;
    }

    $form = new Warecorp_Form('form_invite_more_people', 'POST');
    if ( $handle ) {
        if ( $handle['external_popup'] == 0 ) {
            $form->addRule( 'event_invitations_emails', 'required', Warecorp::t("Field 'To' is required") );
        } elseif ( $handle['external_popup'] == 1 ) {
            if ( empty($handle['event_invitations_fbfriends']) && empty($handle['event_invitations_emails']) )
                $form->addCustomErrorMessage(Warecorp::t('You have to add recipients'));
        }
    }

    if ( !$handle ) {

        $linkUrl = "xajax_doEventInvite('".$objEvent->getId()."', '".$objEvent->getUid()."', xajax.getFormValues('form_invite_more_people')); return false;";

        $this->view->event_id = $id;
        $this->view->uid = $uid;
        $this->view->objEvent = $objEvent;
        $this->view->form = $form;
        $this->view->linkUrl = $linkUrl;

        $request = $this->getRequest();
        $formParams = array();
       /**
        * Subject and Message fields must be clean
        * Redmine bug #1488
        */
        if ($objInvite->getAllowGuestToInvite() || Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentGroup, $this->_page->_user) ) {
            $formParams['event_invitations_emails']     = '';
            $formParams['event_invitations_subject']    = ''; // $objEvent->getInvite()->getSubject();
            $formParams['event_invitations_message']    = ''; // $objEvent->getInvite()->getMessage();
            $formParams['event_invitations_from']       = $objEvent->getInvite()->getFrom();
        } else {
            $formParams['event_invitations_emails']     = '';
            $formParams['event_invitations_subject']    = ''; // $objEvent->getInvite()->getSubject();
            $formParams['event_invitations_message']    = ''; // $objEvent->getInvite()->getMessage();
            $formParams['event_invitations_from']       = $this->currentGroup->getGroupEmail();
        }
        /** **/

        $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();

        if ( FACEBOOK_USED && $request->has('event_invitations_fbfriends') ) {
            $formParams['external_popup'] = true;
            $formParams['event_invitations_fbfriends'] = $request->getParam('event_invitations_fbfriends');
            $friendsToInvite = Warecorp_Facebook_User::getInfo($formParams['event_invitations_fbfriends']);
            $formParams['event_invitations_fbfriends_tojson'] = Zend_Json_Encoder::encode($formParams['event_invitations_fbfriends']);
            $formParams['event_invitations_fbfriends'] = $friendsToInvite;
        }
        $this->view->formParams = $formParams;

        $Content = $this->view->getContents('groups/calendar/ajax/action.event.invite.tpl');

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Invite More People"));
        $popup_window->content($Content);
        $popup_window->width(450)->height(350)->open($objResponse);

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

        // $receivers = Warecorp_Mail_Template::validateRecipientsFormString($this->_page->_user, $handle['event_invitations_emails'] );
        // if ( !$receivers['isValid'] ) {
            // if ( sizeof($receivers['invalid']['userEmails']) != 0 ) {
                // $form->addCustomErrorMessage(
                    // Warecorp::t("Sorry, ") . join( ", ", $receivers['invalid']['userEmails'] ) .
                    // Warecorp::t("  is not a valid ") .
                    // Warecorp::t(" email").( ( sizeof( $receivers['invalid']['userEmails'] ) > 1 ) ? "s." : "." ));
            // }
            // if ( sizeof($receivers['invalid']['userNames']) != 0 ) {
                // $form->addCustomErrorMessage(
                    // Warecorp::t("Sorry, ") . join( ", ", $receivers['invalid']['userNames'] ) .
                    // Warecorp::t("  is not a valid ") . SITE_NAME_AS_STRING .
                    // Warecorp::t(" username").( ( sizeof( $receivers['invalid']['userNames'] ) > 1 ) ? "s." : "." ));
            // }
            // if ( sizeof($receivers['invalid']['groupEmails']) != 0 ) {
                // $form->addCustomErrorMessage(
                    // Warecorp::t("Sorry, ") . join( ", ", $receivers['invalid']['groupEmails'] ) .
                    // Warecorp::t("  is not a valid ") . SITE_NAME_AS_STRING .
                    // Warecorp::t(" group email").( ( sizeof( $receivers['invalid']['groupEmails'] ) > 1 ) ? "s." : "." ));
            // }
            // if ( sizeof($receivers['invalid']['groupNames']) != 0 ) {
                // $form->addCustomErrorMessage(
                    // Warecorp::t("Sorry, ") . join( ", ", $receivers['invalid']['groupNames'] ) .
                    // Warecorp::t("  is not a valid ") . SITE_NAME_AS_STRING .
                    // Warecorp::t(" group name").( ( sizeof( $receivers['invalid']['groupNames'] ) > 1 ) ? "s." : "." ));
            // }
            // if ( sizeof($receivers['invalid']['groupAccess']) != 0 ) {
                // $form->addCustomErrorMessage(
                    // Warecorp::t("Sorry, you can not intite ") . join( ", ", $receivers['invalid']['groupAccess'] ) .
                    // Warecorp::t(" group").( ( sizeof( $receivers['invalid']['groupAccess'] ) > 1 ) ? "s." : "." ));
            // }
            // if ( sizeof($receivers['invalid']['contactListNames']) != 0 ) {
                // $form->addCustomErrorMessage(
                    // Warecorp::t("Sorry, ") . join( ", ", $receivers['invalid']['contactListNames'] ) .
                    // Warecorp::t("  is not a valid ") .
                    // Warecorp::t(" mailing list name").( ( sizeof( $receivers['invalid']['contactListNames'] ) > 1 ) ? "s." : "." ));
            // }
        // }

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
            if ($objInvite->getAllowGuestToInvite() || Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentGroup, $this->_page->_user) ) {
                if ( $this->_page->_user->getEmail() == $handle['event_invitations_from']) {
                    $objEventInvite->setSendFrom($this->_page->_user);
                } elseif ( $this->currentGroup->getGroupEmail() == $handle['event_invitations_from'] ) {
                    $from = clone $this->_page->_user;
                    $from->setEmail($handle['event_invitations_from']);
                    $objEventInvite->setSendFrom($from);
                } else {
                    $from = clone $this->_page->_user;
                    $from->setEmail($handle['event_invitations_from']);
                    $objEventInvite->setSendFrom($from);
                }
            } else {
                $objEventInvite->setSendFrom($this->_page->_user);
            }
            $objEventInvite->setSubject($handle['event_invitations_subject']);
            $objEventInvite->setMessage($handle['event_invitations_message']);


            if ( !empty($handle['event_invitations_fbfriends']) ) { //  save externals fb users attendees
                $lstAttendee = $objEvent->getAttendee();
                foreach ( $handle['event_invitations_fbfriends'] as $fbuid ) {
                    $objUser = Warecorp_Facebook_User::loadUserByFacebookId($fbuid);
                    if ( $objUser && $objUser->getId() ) {
                        if ( !$objAttendee = $lstAttendee->findAttendee($objUser) ) {
                            $objAttendee = new Warecorp_ICal_Attendee();
                            $objAttendee->setEventId($lstAttendee->getEventId());
                            $objAttendee->setOwnerType('user');
                            $objAttendee->setOwnerId($objUser->getId());
                            $objAttendee->setAnswer('NONE');
                            $objAttendee->setAnswerText('');
                            $objAttendee->save();
                            
                            /**
                             * save user name into invitation to field
                             * it needs for editing event and its invitation
                             */                    
                            //$objInvite = $objEvent->getInvite();
                            
                            /**
                             * @see issue #10184
                             */
                            //$recipients = Warecorp_ICal_Invitation::prepareRecipientsFromString($this->_page->_user, $this->_page->_user->getLogin());
                            //$objInvite->mergeRecipients( $recipients );                                                                                    
                        }                              
                    } elseif ( !$objAttendee = $lstAttendee->findObjectsAttendee('fbuser', $fbuid) ) {
                        $objAttendee = new Warecorp_ICal_Attendee();
                        $objAttendee->setEventId($lstAttendee->getEventId());
                        $objAttendee->setOwnerType('fbuser');
                        $objAttendee->setOwnerId($fbuid);
                        $objAttendee->setAnswer('NONE');
                        $objAttendee->setAnswerText('');
                        $objAttendee->save();
                    }
                }
            }
            
            if ( !empty($handle['event_invitations_fbfriends']) ) {
                $objEventInvite->mergeRecipients($recipients, null, null, $handle['event_invitations_fbfriends']);
                $objEventInvite->__saveAttendeeChanges( 'ADD' );
            }

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
            $formParams['external_popup']               = $handle['external_popup'] + 0;
            $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();
            if ( FACEBOOK_USED ) {
                if ( !$formParams['external_popup'] ) {
                    $formParams['event_invitations_fbfriends'] = ( $this->getRequest()->has('event_invitations_fbfriends') ) ? $this->getRequest()->getParam('event_invitations_fbfriends') : $objEvent->getAttendee()->getObjectsIdsList('fbuser');
                } else {
                    $formParams['event_invitations_fbfriends'] = ( !empty($handle['event_invitations_fbfriends']) ) ? $handle['event_invitations_fbfriends'] : null;
                }
                if ( sizeof($formParams['event_invitations_fbfriends']) ) {
                    $friendsToInvite = Warecorp_Facebook_User::getInfo($formParams['event_invitations_fbfriends']);
                    $formParams['event_invitations_fbfriends_tojson'] = Zend_Json_Encoder::encode($formParams['event_invitations_fbfriends']);
                    $formParams['event_invitations_fbfriends'] = $friendsToInvite;
                }
            }
            $this->view->formParams = $formParams;

            $Content = $this->view->getContents('groups/calendar/ajax/action.event.invite.tpl');

            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t("Invite More People"));
            $popup_window->content($Content);
            $popup_window->open($objResponse);
        }
    }
