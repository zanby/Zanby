<?php
    Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.attendee.php.xml');

    $objResponse = new xajaxResponse();
    $form = new Warecorp_Form('rsvp_event_form');

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

    /**
    * Check Access By Code
    */
    if ( null === $this->_page->_user->getId() ) {
        /**
         * if user is anonymous and there isn't access code redirect it to login page
         */
        if ( empty($_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']) ) {
            $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('calendar.month.view');
            $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            return $objResponse;
        }
        /**
         * user is anonymous but he/she has access code to event
         */
        else {
            $objAttendee = $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_'];
            /**
             * Validate attendee object
             */
            $objAttendee = $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_'];
            $objAttendee = new Warecorp_ICal_Attendee($objAttendee->getId());
            if ( $objAttendee->getId() ) {
                $this->_page->_user->setEmail($objAttendee->getEmail());
            } else {
                unset($_SESSION['_RSVP_'][$objEvent->getId()]);
                $objResponse->addRedirect($objEvent->entityURL());
                return $objResponse;
            }
        }
    }

    $objAttendee = null;
    if ( $date ) $objEvent->getAttendee()->setDateFilter($date);
    if ( empty($_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_']) || $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_'] == 'user' ) {
        $objAttendee = $objEvent->getAttendee()->findAttendee($this->_page->_user);
    } else {
        $objAttendee = $objEvent->getAttendee()->findObjectsAttendee($_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_']->getOwnerType(), $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_']->getOwnerId());
    }
    if (!$objAttendee) {
        $objResponse->addRedirect($objEvent->entityURL());
        return $objResponse;
    }

    if ( !$handle ) {
        $this->view->objEvent = $objEvent;
        $this->view->event_id = $id;
        $this->view->uid = $uid;
        $this->view->form = $form;
        $this->view->view = $view;
        $this->view->date = ( $date ) ? $date : 0;
        $this->view->userAttendee = $objAttendee;
        $Content = $this->view->getContents('groups/calendar/ajax/action.event.attendee.tpl');

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t('RSVP'));
        $popup_window->content($Content);
        $popup_window->width(500)->height(350)->open($objResponse);

    }
    else {
        if ( !isset($handle['attending_rsvp_way']) ) $handle['attending_rsvp_way'] = 'NONE';

        /* Event has a RSVP limitation */
        if ( $handle['attending_rsvp_way'] === 'YES' && $objAttendee->getAnswer() !== 'YES' ) {
            if ( $objEvent->getMaxRsvp() && $objEvent->getMaxRsvp() <= $objEvent->getAttendee()->setAnswerFilter('YES')->getCount() ) {

                $form->addCustomErrorMessage(Warecorp::t('Sorry, you have not RSVPed this event. Event full.'));
                $objAttendee->setAnswerText($handle['attending_rsvp_message']);
                $objAttendee->setAnswer($handle['attending_rsvp_way']);

                $this->view->objEvent = $objEvent;
                $this->view->event_id = $id;
                $this->view->uid = $uid;
                $this->view->form = $form;
                $this->view->view = $view;
                $this->view->date = ( $date ) ? $date : 0;
                $this->view->userAttendee = $objAttendee;
                $Content = $this->view->getContents('groups/calendar/ajax/action.event.attendee.tpl');

                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->title(Warecorp::t('RSVP'));
                $popup_window->content($Content);
                $popup_window->width(500)->height(350)->open($objResponse);
                return;
            }
        }
        /* Event has a RSVP limitation */

        if ( $handle['attending_rsvp_way'] != 'NONE' ) {
            if ( isset($handle['attendee_mode']) && $handle['attendee_mode'] == 2 ) {
                if ( $date && null == $objAttendee->getDate() ) {
                    $objNewAttendee = clone $objAttendee;
                    $objNewAttendee->setId(null);
                    $objNewAttendee->setDate($date);
                    $objNewAttendee->setAnswer($handle['attending_rsvp_way']);
                    $objNewAttendee->setAnswerText($handle['attending_rsvp_message']);
                    $objNewAttendee->save();
                } else {
                    $objAttendee->setAnswer($handle['attending_rsvp_way']);
                    $objAttendee->setAnswerText($handle['attending_rsvp_message']);
                    $objAttendee->save();
                }
            } else {
                $objAttendee->setAnswer($handle['attending_rsvp_way']);
                $objAttendee->setAnswerText($handle['attending_rsvp_message']);
                $objAttendee->save();
            }

            $objEvent->getInvite()->sentRSVPNotification( $this->_page->_user, $objAttendee );

            //#12573 ZCCF survey notification
            $round = Warecorp_Round_Item::getCurrentRound($this->currentGroup);
            if ($round->getRoundId()) {

                //Save participation
                if ($objAttendee->getAnswer() == 'YES') {
                    $round->saveParticipation($this->_page->_user);
                }else{
                    //Check if user has any other events RSVPd
                    // get events
                    $objEvents = new Warecorp_ICal_Event_List_Standard();
                    //$objEvents->setTimezone($currentTimezone);
                    //$objEvents->setOwnerIdFilter($this->currentGroup->getId());
                    //$objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                    $objEvents->setPrivacyFilter(array(0,1));
                    $objEvents->setSharingFilter(array(0,1));
                    $objEvents->setFilterPartOfRound($round->getRoundId());
                    $objEvents->setFilterPartOfNonRound(0);
                    $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();

                    $participate = false;

                    if (!empty($arrEvents) ) {
                        //find round with partipications
                        foreach ($arrEvents as $eventId) {
                            $attendee = new Warecorp_ICal_Attendee_List($eventId);
                            
                            $attende = $attendee->setAnswerFilter('YES')->setFetchMode('object')->findAttendee($this->_page->_user);
                            if ($attende && $attende->getId()) {
                                $participate = true;
                                break;
                            }
                        }
                    }
                    if (!$participate) {
                        $round->saveParticipation($this->_page->_user,0);
                    }
                }


                $answer = Warecorp_Round_Survey_Answer::getAnswerByRoundAndUser($round->getRoundId(),$this->_page->_user->getId());
                if ($answer && $answer->getSurveyCompleteDate() !== null) {
                    /* SOAP: MailSrv */
                    if (Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered('CALENDAR_SURVEY_RSVP_NOTIFICATION') ) {
                        //LOAD BY CONFIG FILE
                        $cfgInstance    = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');
                        $RecipientEmail = isset($cfgInstance->rsvp_survey_notification_email) ? $cfgInstance->rsvp_survey_notification_email : null;


                        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
                        $pmbRecipients = array();

                        $data = '<a href="'.Warecorp::getTinyUrl($objEvent->entityURL(),HTTP_CONTEXT).'">'.$objEvent->getTitle().'</a><br />';

                        $recipient = new Warecorp_SOAP_Type_Recipient();
                        $recipient->setEmail( $RecipientEmail );
                        $recipient->setName( null );
                        $recipient->setLocale( null );
                        $recipient->addParam( 'real_name', $this->_page->_user->getFirstname().' '.$this->_page->_user->getLastname());
                        $recipient->addParam( 'email_address', $this->_page->_user->getEmail());
                        $recipient->addParam( 'events_html',$data);

                        $data_plain = $objEvent->getTitle().' ( '.Warecorp::getTinyUrl($objEvent->entityURL(),HTTP_CONTEXT).' )';
                        
                        $recipient->addParam('events_plain',$data_plain);

                        $msrvRecipients->addRecipient($recipient);

                        $client = Warecorp::getMailServerClient();
                        $campaignUID = $client->createCampaign();
                        $request = $client->setSender($campaignUID, $this->currentGroup->getGroupEmail(), $this->currentGroup->getName());
                        $request = $client->setTemplate($campaignUID, 'CALENDAR_SURVEY_RSVP_NOTIFICATION', HTTP_CONTEXT);

                        /* add params */
                        $params = new Warecorp_SOAP_Type_Params();
                        $params->loadDefaultCampaignParams();
                        $client->addParams($campaignUID, $params);

                        /* add headers */
                        $client->addHeader($campaignUID, 'Sender', '"'.$this->currentGroup->getName().'" <'.$this->currentGroup->getGroupEmail().'>');
                        $client->addHeader($campaignUID, 'Reply-To', '"'.$this->currentGroup->getName().'" <'.$this->currentGroup->getGroupEmail().'>');
                        //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                        //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');

                        $request = $client->addRecipients($campaignUID, $msrvRecipients);
                        $request = $client->startCampaign($campaignUID);
                    }
                }
            }
            //  Share Event to user calendar
            if ( null !== $this->_page->_user->getId() ){
                if ( $handle['attending_rsvp_way'] == 'NO' ) {
                    if ( $objEvent->getSharing()->isShared($this->_page->_user) ) {
                        $objEvent->getSharing()->delete($this->_page->_user);
                    }
                } else {
                    if ( !$objEvent->getSharing()->isShared($this->_page->_user) ) {
                        $objEvent->getSharing()->add($this->_page->_user);
                    }
                }
            }

            /**
             * Post feed to facbook wall
             * @var unknown_type
             */
            if ( FACEBOOK_USED ) {
                $url = $objEvent->entityURL().'m/fb';

                $params = array(
                    'title' => htmlspecialchars($objEvent->getTitle()),
                    'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                );
                $action_links[] = array('text' => 'View Event', 'href' => $url);
                $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_RSVP_EVENT, $params);
                if ( $handle['attending_rsvp_message'] ) $objMessage['message'] .= "\n" . htmlspecialchars($handle['attending_rsvp_message']);
                Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);
            }
        }
        $objResponse->addScript('popup_window.close();');
        /**
         * User has choosed 'I want to create account' in last request
         */
        if ( null === $this->_page->_user->getId() && !empty($_SESSION['register_user_after_rsvp']) ) {
            $_SESSION['login_return_page'] = $objEvent->entityURL();
            $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/registration/index/');
        } else {
            $objResponse->addScript('document.location.reload();');
        }
    }
