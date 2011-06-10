<?php

    Warecorp::addTranslation('/modules/groups/calendar/action.event.create.php.xml');

    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();

    if ( null === $this->_page->_user->getId() ) $this->_redirectToLogin();
    if ( false == Warecorp_ICal_AccessManager_Factory::create()->canCreateEvent($this->currentGroup, $this->_page->_user) ) {
        $this->view->errorMessage = Warecorp::t('Sorry, you can not create event');
        $this->view->bodyContent = 'groups/calendar/action.event.error.message.tpl';
        return ;
    }
    
    /**
    * Register Ajax Functions
    */
    $this->_page->Xajax->registerUriFunction( "bookmarkit", "/ajax/bookmarkit/" );
    $this->_page->Xajax->registerUriFunction( "addbookmark", "/ajax/addbookmark/" );
    $this->_page->Xajax->registerUriFunction( "addToFriends", "/ajax/addToFriends/" );
    $this->_page->Xajax->registerUriFunction( "addToFriendsDo", "/ajax/addToFriendsDo/" );
    $this->_page->Xajax->registerUriFunction( "addFromAddressbook", "/groups/addAddressFromAddressbook/" );
    $this->_page->Xajax->registerUriFunction( "addAddressToField", "/groups/addAddressToField/" );
    $this->_page->Xajax->registerUriFunction( "deleteAddressFromField", "/groups/deleteAddressFromField/" );

    $this->_page->Xajax->registerUriFunction( "doAttachPhoto", "/groups/calendarEventAttachPhoto/" );
    $this->_page->Xajax->registerUriFunction( "updateAttachPhoto", "/groups/calendarEventAttachPhotoUpdate/" );
    $this->_page->Xajax->registerUriFunction( "chooseAttachPhoto", "/groups/calendarEventAttachPhotoChoose/" );
    $this->_page->Xajax->registerUriFunction( "doAttachPhotoDelete", "/groups/calendarEventAttachPhotoDelete/" );

    $this->_page->Xajax->registerUriFunction( "doAttachDocument", "/groups/calendarEventAttachDocument/" );
    $this->_page->Xajax->registerUriFunction( "doAttachList", "/groups/calendarEventAttachList/" );

    $this->_page->Xajax->registerUriFunction( "doInviteEntireGroup", "/groups/calendarEventInviteEntireGroup/" );
    $this->_page->Xajax->registerUriFunction( "doInviteMembers", "/groups/calendarEventInviteMembers/" );
    // Venues
    $this->_page->Xajax->registerUriFunction( "changeCountry", "/ajax/changeCountry/" );
    $this->_page->Xajax->registerUriFunction( "changeState", "/ajax/changeState/" );
    $this->_page->Xajax->registerUriFunction( "chooseSavedVenue", "/groups/chooseSavedVenue/" );
    $this->_page->Xajax->registerUriFunction( "setVenue", "/groups/setVenue/" );
    $this->_page->Xajax->registerUriFunction( "addNewVenue", "/groups/addNewVenue/" );
    $this->_page->Xajax->registerUriFunction( "editVenue", "/groups/editVenue/" );
    $this->_page->Xajax->registerUriFunction( "loadSavedVenues", "/groups/loadSavedVenues/" );
    $this->_page->Xajax->registerUriFunction( "copyVenue", "/groups/copyVenue/" );
    $this->_page->Xajax->registerUriFunction( "copyVenueDo", "/groups/copyVenueDo/" );
    $this->_page->Xajax->registerUriFunction( "deleteVenue", "/groups/deleteVenue/" );
    $this->_page->Xajax->registerUriFunction( "deleteVenueDo", "/groups/deleteVenueDo/" );
    $this->_page->Xajax->registerUriFunction( "chooseSavedWWVenue", "/groups/chooseSavedWWVenue/" );
    $this->_page->Xajax->registerUriFunction( "editWWVenue", "/groups/editWWVenue/" );
    $this->_page->Xajax->registerUriFunction( "setWWVenue", "/groups/setWWVenue/" );
    $this->_page->Xajax->registerUriFunction( "addNewWWVenue", "/groups/addNewWWVenue/" );
    $this->_page->Xajax->registerUriFunction( "loadSavedWWVenues", "/groups/loadSavedWWVenues/" );
    $this->_page->Xajax->registerUriFunction( "copyWWVenue", "/groups/copyWWVenue/" );
    $this->_page->Xajax->registerUriFunction( "copyWWVenueDo", "/groups/copyWWVenueDo/" );
    $this->_page->Xajax->registerUriFunction( "deleteWWVenue", "/groups/deleteWWVenue/" );
    $this->_page->Xajax->registerUriFunction( "deleteWWVenueDo", "/groups/deleteWWVenueDo/" );
    $this->_page->Xajax->registerUriFunction( "findaVenue", "/groups/findaVenue/" );
    $this->_page->Xajax->registerUriFunction( "copyVenueFromSearch", "/groups/copyVenueFromSearch/" );

    if ( isset($_SESSION['_calendar_']) && isset($_SESSION['_calendar_']['_documents_']) ) {
        unset($_SESSION['_calendar_']['_documents_']);
    }
    if ( isset($_SESSION['_calendar_']) && isset($_SESSION['_calendar_']['_lists_']) ) {
        unset($_SESSION['_calendar_']['_lists_']);
    }

	$objRequest = $this->getRequest();

    //FIXME определить , какая таймзона является дефолтовой
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того,
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

	/**
	* Date Now : текущее время для текущего пользователя
	*/
	$defaultTimeZone = date_default_timezone_get();
	date_default_timezone_set( $currentTimezone );
	$objNowDate = new Zend_Date();
	date_default_timezone_set($defaultTimeZone);

	/**
	* Init Form Object
	*/
	$form = new Warecorp_Form('form_add_event', 'POST', $this->currentGroup->getGroupPath('calendar.event.create'));
	$form->addRule( 'event_title', 'required', Warecorp::t("Field 'Event Title' is required" ));
	$form->addRule( 'event_description', 'maxlength', Warecorp::t('Event Description too long (max %s symbols)', 2000), array('max' => 2000 ) );

	/**
	* Init Event Object
	*/
	$objEvent = new Warecorp_ICal_Event();

	/**
	* +-----------------------------------------------------------------------
	* | Handle Form Callback
	* +-----------------------------------------------------------------------
	*/
	if ( $form->isPostback() ) {

        /**
        * +-------------------------------------------------------------------
        * | Validate Invitations
        * +-------------------------------------------------------------------
        */

	    /**
	     * @see issue #10184
	     */
	    $recipients = Warecorp_ICal_Invitation::prepareRecipientsFromString($this->_page->_user, $objRequest->getParam('event_invitations_emails', ''));
	    Warecorp_ICal_Invitation::validateRecipients( $recipients, $form );
	    

        /**
        * +-------------------------------------------------------------------
        * | Validate Event Categories
        * +-------------------------------------------------------------------
        */
        if ( 0 == $objRequest->getParam('event_event_type_1', 0) && 0 == $objRequest->getParam('event_event_type_2', 0) && 0 == $objRequest->getParam('event_event_type_3', 0) ) {
            $form->addCustomErrorMessage(Warecorp::t('Select please at least one event type'));
        }

        /**
        * +-------------------------------------------------------------------
        * | Validate Start Date and Until Date
        * +-------------------------------------------------------------------
        */
        if ( $objRequest->getParam('rrule_freq') && $objRequest->getParam('rrule_freq') != 'NONE' && $objRequest->getParam('rrule_until_option') == 3 ) {
            $event_dtstart      = $objRequest->getParam('event_dtstart');
            $rrule_until_date   = $objRequest->getParam('rrule_until_date');

            $strDtstart     = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T000000';
            $strUntilDate   = sprintf('%04d',$rrule_until_date['date_Year']).'-'.sprintf('%02d',$rrule_until_date['date_Month']).'-'.sprintf('%02d',$rrule_until_date['date_Day']).'T000000';

            $objDtstart = new Zend_Date($strDtstart, Zend_Date::ISO_8601);
            $objUntilDate = new Zend_Date($strUntilDate, Zend_Date::ISO_8601);
            if ( !$objUntilDate->isLater($objDtstart) ) {
                $form->addCustomErrorMessage(Warecorp::t('Repeating until date must be later as event start date'));
            }
        }

        /** Redmine bug 3114 **/
        if ( !$objRequest->getParam('event_is_allday') ) {
            /** Redmine bug 1627 **/
            if ( 0 == $objRequest->getParam('event_duration_hour', 0) && 0 == $objRequest->getParam('event_duration_minute', 0) ) {
                //$objRequest->setParam('event_duration_hour', 1);
                $form->addCustomErrorMessage(Warecorp::t('Event must have duration %s minutes at least', 15));
            }
        }

		/**
		* +-------------------------------------------------------------------
		* | Validate Form Data
		* +-------------------------------------------------------------------
		*/
		if ( $form->validate( $objRequest->getParams() ) ) {

            $event_dtstart = $objRequest->getParam('event_dtstart');

            $objEvent->setTitle($objRequest->getParam('event_title'));
            $objEvent->setDescription($objRequest->getParam('event_description'));

            /**
            * Event Picture save :
            */
            if ( null !== $objRequest->getParam('event_picture_id', null) && $objRequest->getParam('event_picture_id') ) {
                $objEvent->setPictureId($objRequest->getParam('event_picture_id'));
            }

            /**
            * Event Rrule Object : Создаем объект rrule для события
            */
            if ( $objRequest->getParam('rrule_freq') && $objRequest->getParam('rrule_freq') != 'NONE' ) {
                /**
                * check Repeat Count value
                */
                if ( 0 == floor($objRequest->getParam('rrule_until_count',1)) ) $objRequest->setParam('rrule_until_count', 10);

                $objRrule = new Warecorp_ICal_Rrule();
                $objRrule->setFromHttpRequest($objRequest);
                $objEvent->setRrule($objRrule);
            }

			/**
			* build event dates for save
			*/
			if ( $objRequest->getParam('event_is_allday') ) {
				$objEvent->setAllDay(true);
				$strDtstart = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T000000';
				$strDtend   = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T235959';
            } else {
				$objEvent->setAllDay(false);
				$strDtstart = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T'.sprintf('%02d',$objRequest->getParam('event_time_hour')).''.sprintf('%02d',$objRequest->getParam('event_time_minute')).'00';
				/**
				* Определяем дату окончания события
				* работаем в текущей таймзоне для текущего пользователя
				*/
				$defaultTimeZone = date_default_timezone_get();
				date_default_timezone_set($currentTimezone);
				$objDtstart = new Zend_Date($strDtstart, Zend_Date::ISO_8601);
				$objDtstart->addHour($objRequest->getParam('event_duration_hour'));
				$objDtstart->addMinute($objRequest->getParam('event_duration_minute'));
				$strDtend = $objDtstart->toString('yyyy-MM-ddTHHmmss');
				unset($objDtstart);
				date_default_timezone_set($defaultTimeZone);
			}
			$objEvent->setDtstart($strDtstart);
			$objEvent->setDtend($strDtend);

            /**
            * Event Timezone :
            */
            if ( $objRequest->getParam('event_is_allday', null) ) {
                $objEvent->setTimezone(null);
            } else {
                $objEvent->setTimezone( ( $objRequest->getParam('event_timezone_mode') ) ? $objRequest->getParam('event_timezone') : $currentTimezone );
            }

			/**
			* Event Creator and Owner :
			*/
			$objEvent->setCreatorId($this->_page->_user->getId());
            $objEvent->setOwnerId($objRequest->getParam('event_owner_id', $this->currentGroup->getId()));
			$objEvent->setOwnerType(Warecorp_ICal_Enum_OwnerType::GROUP);

            /**
            * Event Privacy :
            */
			$objEvent->setPrivacy($objRequest->getParam('event_privacy', Warecorp_ICal_Enum_Privacy::PRIVACY_PUBLIC));

            /**
            * Save Event Categories :
            */
            $objEventCategories = $objEvent->getCategories();
            if ( 0 != $objRequest->getParam('event_event_type_1', 0) ) $objEventCategories->add($objRequest->getParam('event_event_type_1'));
            if ( 0 != $objRequest->getParam('event_event_type_2', 0) ) $objEventCategories->add($objRequest->getParam('event_event_type_2'));
            if ( 0 != $objRequest->getParam('event_event_type_3', 0) ) $objEventCategories->add($objRequest->getParam('event_event_type_3'));

            /**
            * Event Reminders :
            */
            if ( 2 == $objRequest->getParam('event_reminder_mode') ) {
                if ( $objRequest->getParam('event_reminder_1') ) {
                    $objReminder = new Warecorp_ICal_Reminder();
                    $objReminder->setDuration($objRequest->getParam('event_reminder_1'));
                    $objReminder->setEntireGuests( (null === $objRequest->getParam('event_reminder_to_guest_list', null)) ? 0 : 1 );
                    $objEvent->getReminders()->add($objReminder);
                }
                if ( $objRequest->getParam('event_reminder_2') ) {
                    $objReminder = new Warecorp_ICal_Reminder();
                    $objReminder->setDuration($objRequest->getParam('event_reminder_2'));
                    $objReminder->setEntireGuests( (null === $objRequest->getParam('event_reminder_to_guest_list', null)) ? 0 : 1 );
                    $objEvent->getReminders()->add($objReminder);
                }
            }

            /**
            * Save Event Documents
            */
            if ( null !== $objRequest->getParam('event_documents', null) ) {
                foreach ( $objRequest->getParam('event_documents') as $docId ) {
                    $objDocument = new Warecorp_Document_Item($docId);
                    $objEvent->getDocuments()->add($objDocument);
                }
            }

            /**
            * Save Event Lists
            */
            if ( null !== $objRequest->getParam('event_lists', null) ) {
                foreach ( $objRequest->getParam('event_lists') as $listId ) {
                    $objList = new Warecorp_List_Item($listId);
                    $objEvent->getLists()->add($objList);
                }
            }

            /**
            * Save Event Venues
            */
            if ( null !== $objRequest->getParam('event_venue_id', null) && 0 != floor($objRequest->getParam('event_venue_id')) ) {
                $objVenue = new Warecorp_Venue_Item($objRequest->getParam('event_venue_id', null));
                $objEvent->getVenues()->add($objVenue);
            }

            /**
             * Set http context
             */
            $objEvent->setHttpContext(HTTP_CONTEXT);

            /**
            * Save Event
            */
			$objEvent->save();

			/**
			 * Send notification to host
			 * if host has checked 'Notify on changes'
			 * @author Roman Gabrusenok
			 */
			$this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $objEvent, "EVENT", "NEW", false );


            //#12694
            /* SOAP: MailSrv */
            if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'CALENDAR_EVENT_CREATE_CONFIRMATION' ) ) {

                /* SOAP: MailSrv */
                $client = Warecorp::getMailServerClient();

                if ( $client ) {
                    $objRecipient = $this->_page->_user;
                    $recipient = new Warecorp_SOAP_Type_Recipient();
                    $recipient->setEmail( $objRecipient->getEmail() );
                    $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                    $recipient->setLocale( null );
                    $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                    $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                    $msrvRecipients->addRecipient($recipient);
                    $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();

                    $objSender = $this;

                    $campaignUID = $client->createCampaign();
                    $request = $client->setSender($campaignUID, $this->currentGroup->getGroupEmail(), $this->currentGroup->getName());
                    $request = $client->setTemplate($campaignUID, 'CALENDAR_EVENT_CREATE_CONFIRMATION', HTTP_CONTEXT); /* CALENDAR_EVENT_CREATE_CONFIRMATION */

                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'subject', $subject );
                    $params->addParam( 'message_plain', $message_plain );
                    $params->addParam( 'message_html', $message_html );
                    $request = $client->addParams($campaignUID, $params);
                    
                    $host_docs_html = '';
                    $host_docs_plain = '';

                    if ( $objEvent->getEventIsPartOfRound() ) {
                        $objRound = Warecorp_Round_Item::getCurrentRound($this->currentGroup->getId());
                        if ( $objRound->getRoundIsCurrent() && $objEvent->getEventIsPartOfRound() == $objRound->getRoundId() && $objRound->getRoundDocumentId() ) {
                            $objHostDocument = $objRound->getDocument();
                            $host_docs_html = '<a href="'.$this->currentGroup->getGroupPath('docget').'docid/'.$objHostDocument->getId().'">'.Warecorp::t('Download Host Documents').'</a> | '.$objHostDocument->getFileSize();
                            $host_docs_html = Warecorp::t('Download Host Documents').': '.$this->currentGroup->getGroupPath('docget').'docid/'.$objHostDocument->getId().' | '.$objHostDocument->getFileSize();
                        }
                    }
                    $params->addParam( 'host_docs_html', $host_docs_html );
                    $params->addParam( 'host_docs_plain', $host_docs_plain );

                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                }
            }
			
//            if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setSender($this->currentGroup);
//                $mail->addRecipient($this->currentGroup->getHost());
//                $mail->addParam('Group', $this->currentGroup);
//                $mail->addParam('action', "NEW");
//                $mail->addParam('section', "EVENT");
//                $mail->addParam('chObject', $objEvent);
//                $mail->addParam('User', $this->_page->_user);
//                $mail->addParam('isPlural', false);
//                $mail->sendToPMB(true);
//                $mail->send();
//            }

            /**
            * Event Tags :
            */
            $objEvent->getTags()->addTags($objRequest->getParam('event_tags',''));

            /**
            * Build Reminders Cache :
            */
            $cache = new Warecorp_ICal_Reminder_Cache();
            $cache->build($objEvent);

            /**
            * Send Invitations and add Attendee
            */
            $objEventInvite = new Warecorp_ICal_Invitation();
            $objEventInvite->setAllowGroups(true);
            $objEventInvite->setEventId($objEvent->getId());
            $objEventInvite->setEvent($objEvent);
            $objEventInvite->setFrom($objRequest->getParam('event_invitations_from', $this->currentGroup->getGroupEmail()));

            if ( $this->_page->_user->getEmail() == $objRequest->getParam('event_invitations_from', $this->currentGroup->getGroupEmail())) {
                $objEventInvite->setSendFrom($this->_page->_user);
            } elseif ( $this->currentGroup->getGroupEmail() == $objRequest->getParam('event_invitations_from', $this->currentGroup->getGroupEmail()) ) {
                $from = clone $this->_page->_user;
                $from->setEmail($objRequest->getParam('event_invitations_from', $this->currentGroup->getGroupEmail()));
                $objEventInvite->setSendFrom($from);
            } else {
                $from = clone $this->_page->_user;
                $from->setEmail($objRequest->getParam('event_invitations_from', $this->currentGroup->getGroupEmail()));
                $objEventInvite->setSendFrom($from);
            }

            $objEventInvite->setTo($objRequest->getParam('event_invitations_emails', ''));
            $objEventInvite->setSubject($objRequest->getParam('event_invitations_subject', ''));
            $objEventInvite->setMessage($objRequest->getParam('event_invitations_message', ''));
            $objEventInvite->setAllowGuestToInvite($objRequest->getParam('event_allow_guests_invitation', 0));
            $objEventInvite->setReceiveNoRsvpEmail($objRequest->getParam('receive_no_rsvp_email', 0));
            $objEventInvite->setDisplayListToGuest($objRequest->getParam('event_display_guests', 0));
            $objEventInvite->setIsAnybodyJoin($objRequest->getParam('is_anybody_join', 0));

            /**
             * @see issue #10184
             */
            $inviteLists = $objRequest->getParam('event_invitations_lists', null);
            $inviteGroups = $objRequest->getParam('event_invitations_groups', null);
            $inviteFBUsers = null;
            
            if ( FACEBOOK_USED ) {
                $facebookId = Warecorp_Facebook_Api::getFacebookId();        
                if ( !empty($facebookId) && null != $objRequest->getParam('event_invitations_fbfriends', null) && sizeof($objRequest->getParam('event_invitations_fbfriends')) != 0 ) {
                    $inviteFBUsers = $objRequest->getParam('event_invitations_fbfriends');
                    
                    /* Check are all fbIds correct FB Friends off current user (current FB Session) */
                    $friends = Warecorp_Facebook_Api::getInstance()->api(array('method'=>'friends.get','uid'=>$facebookId));
                    $inviteFBUsers = array_intersect($inviteFBUsers, $friends);
                }        
            }      
                              
            $objEventInvite->setRecipients($recipients, $inviteGroups, $inviteLists, $inviteFBUsers);
			
            /**
             * @see issue #10184
             */                    
            $objEventInvite->__save();
            $objEventInvite->__saveAttendee();

            $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'CREATE';
            $_SESSION['_calendar_']['_confirmPage_']['eventId'] = $objEvent->getId();
			$this->_redirect($this->currentGroup->getGroupPath('calendar.action.confirm'));
		}


        /**
        * +-------------------------------------------------------------------
        * | Подготавливаем параметры, если форма не прошла валидацию
        * +-------------------------------------------------------------------
        */

        $objEvent->setTitle($objRequest->getParam('event_title'));
        $objEvent->setDescription($objRequest->getParam('event_description'));

        if ( null !== $objRequest->getParam('event_picture_id', null) && $objRequest->getParam('event_picture_id') ) {
            $objEvent->setPictureId($objRequest->getParam('event_picture_id'));
        }

        /**
        * Event Start Date : Формируем дату начала события, береться из значений формы
        * сначала формируем строку, потом из нее в нужной таймзоне создаем объект
        */
        if ( !$objRequest->getParam('event_is_allday') ) $this->getRequest()->setParam('event_is_allday', 0);
        $event_dtstart = $objRequest->getParam('event_dtstart');
        if ( $objRequest->getParam('event_is_allday') ) {
            /**
            * Если событие на весь день - то дата устанавливается
            * как текущая дата и время в локальной зоне
            * при этом формируем кратность 15 минутам, т.к. такой формат у селектов на форме
            */
            $objDefaultStartDate = clone $objNowDate;
            if ( $objDefaultStartDate->get(Zend_Date::MINUTE) > 0 && $objDefaultStartDate->get(Zend_Date::MINUTE) < 15 ) $objDefaultStartDate->setMinute(15);
            elseif ( $objDefaultStartDate->get(Zend_Date::MINUTE) > 15 && $objDefaultStartDate->get(Zend_Date::MINUTE) < 30 ) $objDefaultStartDate->setMinute(30);
            elseif ( $objDefaultStartDate->get(Zend_Date::MINUTE) > 30 && $objDefaultStartDate->get(Zend_Date::MINUTE) < 45 ) $objDefaultStartDate->setMinute(45);
            elseif ( $objDefaultStartDate->get(Zend_Date::MINUTE) > 45 ) {
                $objDefaultStartDate->addHour(1);
                $objDefaultStartDate->setMinute(0);
            }
            $strDtstart = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T'.$objDefaultStartDate->toString('HHmm').'00';
        } else {
            $strDtstart = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T'.sprintf('%02d',$objRequest->getParam('event_time_hour')).''.sprintf('%02d',$objRequest->getParam('event_time_minute')).'00';
        }
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set( $currentTimezone );
        $objDefaultStartDate = new Zend_Date($strDtstart, Zend_Date::ISO_8601);
        date_default_timezone_set($defaultTimeZone);

        /**
        * Rrule Until Date : Формируем дату окончания репитера, берем из формы
        * создаем время в зоне пользователя, который просматривает страницу, если анонимный в UTC
        */
        $rrule_until_date = $objRequest->getParam('rrule_until_date');
        $strUntilDate = sprintf('%04d',$rrule_until_date['date_Year']).'-'.sprintf('%02d',$rrule_until_date['date_Month']).'-'.sprintf('%02d',$rrule_until_date['date_Day']);
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set( $currentTimezone );
        $objDefaultUntilDate = new Zend_Date($strUntilDate, Zend_Date::ISO_8601);
        date_default_timezone_set($defaultTimeZone);
	}
	/**
	* +-----------------------------------------------------------------------
	* | Handle Form View
	* +-----------------------------------------------------------------------
	*/
	else {
		/**
		* build event statr date
		* по умолчанию - текущая дата
		*/
		$objDefaultStartDate = clone $objNowDate;
		if ( $objDefaultStartDate->get(Zend_Date::MINUTE) > 0 && $objDefaultStartDate->get(Zend_Date::MINUTE) < 15 ) $objDefaultStartDate->setMinute(15);
		elseif ( $objDefaultStartDate->get(Zend_Date::MINUTE) > 15 && $objDefaultStartDate->get(Zend_Date::MINUTE) < 30 ) $objDefaultStartDate->setMinute(30);
		elseif ( $objDefaultStartDate->get(Zend_Date::MINUTE) > 30 && $objDefaultStartDate->get(Zend_Date::MINUTE) < 45 ) $objDefaultStartDate->setMinute(45);
		elseif ( $objDefaultStartDate->get(Zend_Date::MINUTE) > 45 ) {
			$objDefaultStartDate->addHour(1);
			$objDefaultStartDate->setMinute(0);
		}
		$objDefaultUntilDate = clone $objNowDate;
	}

	/**
	 * build timezones list
	 */
	$objTimezoneList = new Warecorp_ICal_Timezone_List();
	$objTimezoneList->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS);
	$objTimezoneList->setPairsModeKey('tz_name');
	$objTimezoneList->setPairsModeValue('name');
	$timezones = $objTimezoneList->getList();
	$this->view->timezones = $timezones;

	/**
	 * build options
	 */
	$this->view->weekdays = Warecorp_ICal_Const::$weekdaysOptions;
	$this->view->months = Warecorp_ICal_Const::$monthsOptions;
	$this->view->setpos = Warecorp_ICal_Const::$setposOptions;
	$this->view->every = Warecorp_ICal_Const::$everyOptions;
    $this->view->month_side = Warecorp_ICal_Const::$monthSideOptions;
    $this->view->minutes = Warecorp_ICal_Const::$minutesOptions;
    $this->view->dur_minutes = Warecorp_ICal_Const::$durMinutesOptions;
	$this->view->hours = Warecorp_ICal_Const::getHours();
	$this->view->dur_hours = Warecorp_ICal_Const::getHoursDur();

	/**
	* build event categories
	*/
    $categories = new Warecorp_ICal_Category_List();
    $categories->setPairsModeKey('category_id');
    $categories->setPairsModeValue('category_name');
    $categories->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS);
    $categories->setOrder('category_order DESC, category_name ASC');
    $categories = $categories->getList();
    $categories = array('0' => '------') + $categories;
    $this->view->event_types = $categories;

    $this->view->ReminderOptions1 = Warecorp_ICal_Const::$ReminderOptions1;
    $this->view->ReminderOptions2 = Warecorp_ICal_Const::$ReminderOptions2;

    /**
    * build venues options
    */
    $aoVenuesList = new Warecorp_Venue_List( );
    $aoVenuesList->setOwnerType( Warecorp_Venue_Enum_OwnerType::GROUP );
    $aoVenuesList->setOwnerId( $this->currentGroup->getId() );
    $aoVenuesList->returnAsAssoc();
    $aoVenuesList->setType( Warecorp_Venue_Enum_VenueType::WORLDWIDE );

    $venuesWorldwideList = $aoVenuesList->getList();
    $venuesWorldwideList[0] = Warecorp::t('[ CHOOSE VENUE ]');
    ksort( $venuesWorldwideList );

    $aoVenuesList->setType( Warecorp_Venue_Enum_VenueType::SIMPLE );
    $venuesSimpleList = $aoVenuesList->getList();
    $venuesSimpleList[0] = Warecorp::t('[ CHOOSE VENUE ]');
    ksort( $venuesSimpleList );

    $this->view->venuesWorldwideList = $venuesWorldwideList;
    $this->view->venuesSimpleList = $venuesSimpleList;

	/**
	 * init form params
	 */
	$formParams = array();

    $formParams['event_owner_id']               = ( $objRequest->getParam('event_owner_id') )                           ? $objRequest->getParam('event_owner_id')               : $this->currentGroup->getId();

    $formParams['rrule_freq']                   = ( $objRequest->getParam('rrule_freq') )                               ? $objRequest->getParam('rrule_freq')                   : 'NONE';

	$formParams['event_timezone_mode']          = ( $objRequest->getParam('event_timezone_mode') )                      ? $objRequest->getParam('event_timezone_mode')          : 0;
	$formParams['event_timezone']               = ( $objRequest->getParam('event_timezone') )                           ? $objRequest->getParam('event_timezone')               : $currentTimezone;

	$formParams['event_dtstart']                = $objDefaultStartDate;
	$formParams['event_dtstart_calSelected']    = $objDefaultStartDate->toString('MM/dd/yyyy');
	$formParams['event_dtstart_calPagedate']    = $objDefaultStartDate->toString('MM/yyyy');

    $formParams['event_duration_hour']          = ( null !== $objRequest->getParam('event_duration_hour', null) )       ? $objRequest->getParam('event_duration_hour')          : 1;
    $formParams['event_duration_minute']        = ( null !== $objRequest->getParam('event_duration_minute', null) )     ? $objRequest->getParam('event_duration_minute')        : 0;

	$formParams['event_is_allday']              = (null !== $objRequest->getParam('event_is_allday', null))             ? $objRequest->getParam('event_is_allday')              : 0;

	$formParams['rrule_daily_option']           = ( $objRequest->getParam('rrule_daily_option') )                       ? $objRequest->getParam('rrule_daily_option')           : 1;
	$formParams['rrule_daily_interval1']        = ( $objRequest->getParam('rrule_daily_interval1') )                    ? $objRequest->getParam('rrule_daily_interval1')        : 1;

	$formParams['rrule_weekly_option']          = ( $objRequest->getParam('rrule_weekly_option') )                      ? $objRequest->getParam('rrule_weekly_option')          : 1;
	$formParams['rrule_weekly_interval1']       = ( $objRequest->getParam('rrule_weekly_interval1') )                   ? $objRequest->getParam('rrule_weekly_interval1')       : 1;
	$formParams['rrule_weekly_byday1']          = ( $objRequest->getParam('rrule_weekly_byday1') )                      ? $objRequest->getParam('rrule_weekly_byday1')          : array(Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($objNowDate->get(Zend_Date::WEEKDAY_DIGIT)));
	if ( sizeof($formParams['rrule_weekly_byday1']) != 0 ) {
		foreach ( $formParams['rrule_weekly_byday1'] as $w ) $formParams['rrule_weekly_byday1'][$w] = $w;
	}

	$formParams['rrule_monthly_option']         = ( $objRequest->getParam('rrule_monthly_option') )                     ? $objRequest->getParam('rrule_monthly_option')         : 1;
	$formParams['rrule_monthly_bymonthday1']    = ( $objRequest->getParam('rrule_monthly_bymonthday1') )                ? $objRequest->getParam('rrule_monthly_bymonthday1')    : $objNowDate->get(Zend_Date::DAY_SHORT); // cur month day
	$formParams['rrule_monthly_interval1']      = ( $objRequest->getParam('rrule_monthly_interval1') )                  ? $objRequest->getParam('rrule_monthly_interval1')      : 1;
	$formParams['rrule_monthly_setpos2']        = ( $objRequest->getParam('rrule_monthly_setpos2') )                    ? $objRequest->getParam('rrule_monthly_setpos2')        : 1;
	$formParams['rrule_monthly_byday2']         = ( $objRequest->getParam('rrule_monthly_byday2') )                     ? $objRequest->getParam('rrule_monthly_byday2')         : Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($objNowDate->get(Zend_Date::WEEKDAY_DIGIT)); // cur weekday
	$formParams['rrule_monthly_interval2']      = ( $objRequest->getParam('rrule_monthly_interval2') )                  ? $objRequest->getParam('rrule_monthly_interval2')      : 1;
    $formParams['rrule_monthly_bymonthday3']    = ( $objRequest->getParam('rrule_monthly_bymonthday3') )                ? $objRequest->getParam('rrule_monthly_bymonthday3')    : 1;
    $formParams['rrule_monthly_interval3']      = ( $objRequest->getParam('rrule_monthly_interval3') )                  ? $objRequest->getParam('rrule_monthly_interval3')      : 1;

	$formParams['rrule_yearly_option']          = ( $objRequest->getParam('rrule_yearly_option') )                      ? $objRequest->getParam('rrule_yearly_option')          : 1;
	$formParams['rrule_yearly_bymonthday1']     = ( $objRequest->getParam('rrule_yearly_bymonthday1') )                 ? $objRequest->getParam('rrule_yearly_bymonthday1')     : $objNowDate->get(Zend_Date::DAY_SHORT);
	$formParams['rrule_yearly_bymonth1']        = ( $objRequest->getParam('rrule_yearly_bymonth1') )                    ? $objRequest->getParam('rrule_yearly_bymonth1')        : $objNowDate->get(Zend_Date::MONTH_SHORT); //cur month
	$formParams['rrule_yearly_setpos2']         = ( $objRequest->getParam('rrule_yearly_setpos2') )                     ? $objRequest->getParam('rrule_yearly_setpos2')         : 1;
	$formParams['rrule_yearly_byday2']          = ( $objRequest->getParam('rrule_yearly_byday2') )                      ? $objRequest->getParam('rrule_yearly_byday2')          : Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($objNowDate->get(Zend_Date::WEEKDAY_DIGIT)); // cur weekday
	$formParams['rrule_yearly_bymonth2']        = ( $objRequest->getParam('rrule_yearly_bymonth2') )                    ? $objRequest->getParam('rrule_yearly_bymonth2')        : $objNowDate->get(Zend_Date::MONTH_SHORT); // cur month

	$formParams['rrule_until_option']           = ( $objRequest->getParam('rrule_until_option') )                       ? $objRequest->getParam('rrule_until_option')           : 1;
	$formParams['rrule_until_count']            = ( $objRequest->getParam('rrule_until_count') )                        ? $objRequest->getParam('rrule_until_count')            : 10;

	$formParams['rrule_until_date']             = $objDefaultUntilDate;
	$formParams['rrule_until_date_calSelected'] = $objDefaultUntilDate->toString('MM/dd/yyyy');
	$formParams['rrule_until_date_calPagedate'] = $objDefaultUntilDate->toString('MM/yyyy');

    /**
    * @desc
    */
    $formParams['event_tags']                   = ( $objRequest->getParam('event_tags') )                               ? $objRequest->getParam('event_tags')                   : '';

    $formParams['event_event_type_1']           = ( $objRequest->getParam('event_event_type_1') )                       ? $objRequest->getParam('event_event_type_1')           : Warecorp_Group_Category::getEventCategory($this->currentGroup->getCategoryId());
    $formParams['event_event_type_2']           = ( $objRequest->getParam('event_event_type_2') )                       ? $objRequest->getParam('event_event_type_2')           : 0;
    $formParams['event_event_type_3']           = ( $objRequest->getParam('event_event_type_3') )                       ? $objRequest->getParam('event_event_type_3')           : 0;

    $formParams['event_privacy']                = ( $objRequest->getParam('event_privacy') )                            ? $objRequest->getParam('event_privacy')                : 0;

    $formParams['event_reminder_mode']          = ( $objRequest->getParam('event_reminder_mode') )                      ? $objRequest->getParam('event_reminder_mode')          : 1;
    $formParams['event_reminder_1']             = ( $objRequest->getParam('event_reminder_1') )                         ? $objRequest->getParam('event_reminder_1')             : 900;
    $formParams['event_reminder_2']             = ( $objRequest->getParam('event_reminder_2') )                         ? $objRequest->getParam('event_reminder_2')             : 0;
    $formParams['event_reminder_to_guest_list'] = ( null !== $objRequest->getParam('event_reminder_to_guest_list', null) ) ? $objRequest->getParam('event_reminder_to_guest_list') : 0;

    $formParams['event_invitations_from']       = ( $objRequest->getParam('event_invitations_from') )                   ? $objRequest->getParam('event_invitations_from')       : $this->currentGroup->getGroupEmail();
    $formParams['event_invitations_emails']     = ( $objRequest->getParam('event_invitations_emails') )                 ? $objRequest->getParam('event_invitations_emails')     : '';
    $formParams['event_invitations_subject']    = ( $objRequest->getParam('event_invitations_subject') )                ? $objRequest->getParam('event_invitations_subject')    : '';
    $formParams['event_invitations_message']    = ( $objRequest->getParam('event_invitations_message') )                ? $objRequest->getParam('event_invitations_message')    : '';
    $formParams['event_allow_guests_invitation']= ( $objRequest->getParam('event_allow_guests_invitation') )            ? $objRequest->getParam('event_allow_guests_invitation'): 0;
    $formParams['receive_no_rsvp_email']        = ( $objRequest->getParam('receive_no_rsvp_email') )                    ? $objRequest->getParam('receive_no_rsvp_email')        : 0;
    $formParams['event_display_guests']         = ( $objRequest->getParam('event_display_guests') )                     ? $objRequest->getParam('event_display_guests')         : 0;
    
    $is_anybody_join_default = ( defined('HTTP_CONTEXT') && HTTP_CONTEXT == 'zccf' ) ? 1 : 0;
    $formParams['is_anybody_join']              = ( null !== $objRequest->getParam('is_anybody_join', null) )           ? $objRequest->getParam('is_anybody_join')              : $is_anybody_join_default;
    
    $formParams['event_invite_entire_group']    = ( $objRequest->getParam('event_invite_entire_group') )                ? $objRequest->getParam('event_invite_entire_group')    : 0;
    $formParams['event_invitations_lists']      = ( null !== $objRequest->getParam('event_invitations_lists', null) )   ? $objRequest->getParam('event_invitations_lists')      : array();
    $formParams['event_invitations_groups']     = ( null !== $objRequest->getParam('event_invitations_groups', null) )  ? $objRequest->getParam('event_invitations_groups')     : array();
    if ( sizeof($formParams['event_invitations_lists']) != 0 ) {
        foreach( $formParams['event_invitations_lists'] as $index => &$item ) {
            if ( Warecorp_User_Addressbook_ContactList::isContactListExistById($item) ) $item = new Warecorp_User_Addressbook_ContactList(false, 'id', $item);
            else unset($formParams['event_invitations_lists'][$index]);
        }
    }
    if ( sizeof($formParams['event_invitations_groups']) != 0 ) {
        foreach( $formParams['event_invitations_groups'] as $index => &$item ) {
            $item = Warecorp_Group_Factory::loadById($item);
            if ( null === $item->getId() ) unset($formParams['event_invitations_groups'][$index]);
        }
    }

    /**
    * Restore Documents
    */
    $formParams['event_documents'] = array();
    if ( null !== $objRequest->getParam('event_documents', null) ) {
        $_SESSION['_calendar_']['_documents_'] = array();
        foreach ( $objRequest->getParam('event_documents') as $docId ) {
            $formParams['event_documents'][] = new Warecorp_Document_Item($docId);
            $_SESSION['_calendar_']['_documents_'][$docId] = $docId;
        }
    }

    /**
    * Restore Lists
    */
    $formParams['event_lists'] = array();
    if ( null !== $objRequest->getParam('event_lists', null) ) {
        $_SESSION['_calendar_']['_lists_'] = array();
        foreach ( $objRequest->getParam('event_lists') as $listId ) {
            $formParams['event_lists'][] = new Warecorp_List_Item($listId);
            $_SESSION['_calendar_']['_lists_'][$listId] = $listId;
        }
    }

    /**
    * Restore Venue
    */
    $formParams['venue_type']                   = ( null !== $objRequest->getParam('event_venue_type', null) )          ? $objRequest->getParam('event_venue_type')             : 'no';
    $formParams['venueId']                      = ( null !== $objRequest->getParam('event_venue_id', null) )            ? $objRequest->getParam('event_venue_id')               : null;
    $venue = new Warecorp_Venue_Item($formParams['venueId']);
    $this->view->venue = $venue;
    if ( $venue->getId() ) {
        if ($venue->getType() == Warecorp_Venue_Enum_VenueType::SIMPLE) $_SESSION['G_simple_venueId'] = $venue->getId();
        else $_SESSION['G_worldwide_venueId'] = $venue->getId();
    } else {
        $_SESSION['G_simple_venueId'] = null;
        $_SESSION['G_worldwide_venueId'] = null;
    }

    /**
    * Настройки закладок
    */
    $formParams['show_repeating_block']         = ( null !== $objRequest->getParam('show_repeating_block', null) )      ? $objRequest->getParam('show_repeating_block')         : 0;
    $formParams['show_reminder_block']          = ( null !== $objRequest->getParam('show_reminder_block', null) )       ? $objRequest->getParam('show_reminder_block')          : 0;
    $formParams['show_invitation_block']        = ( null !== $objRequest->getParam('show_invitation_block', null) )     ? $objRequest->getParam('show_invitation_block')        : 1;
    $formParams['show_privacy_block']           = ( null !== $objRequest->getParam('show_privacy_block', null) )        ? $objRequest->getParam('show_privacy_block')           : 0;
    $formParams['show_documents_block']         = ( null !== $objRequest->getParam('show_documents_block', null) )      ? $objRequest->getParam('show_documents_block')         : 0;
    $formParams['show_lists_block']             = ( null !== $objRequest->getParam('show_lists_block', null) )          ? $objRequest->getParam('show_lists_block')             : 0;
    $formParams['show_venues_block']            = ( null !== $objRequest->getParam('show_venues_block', null) )         ? $objRequest->getParam('show_venues_block')            : 0;

    if ( FACEBOOK_USED ) {
        $formParams['event_invitations_fbfriends'] = ( null !== $objRequest->getParam('event_invitations_fbfriends', null) ) ? $objRequest->getParam('event_invitations_fbfriends') : $objEvent->getAttendee()->getObjectsIdsList('fbuser');
        if ( sizeof($formParams['event_invitations_fbfriends']) != 0 ) {
            $friendsToInvite = Warecorp_Facebook_User::getInfo($formParams['event_invitations_fbfriends']);
            $formParams['event_invitations_fbfriends_tojson'] = Zend_Json_Encoder::encode($formParams['event_invitations_fbfriends']);
            $formParams['event_invitations_fbfriends'] = $friendsToInvite;            
        }

    }
    
	/**
	* Assign template vars
	*/
    $this->view->hostPrivilege = $this->currentGroup->getMembers()->isHost($this->_page->_user) || $this->currentGroup->getMembers()->isCoHost($this->_page->_user);
	$this->view->form = $form;
	$this->view->formParams = $formParams;
	$this->view->objEvent = $objEvent;
	$this->view->viewMode = 'ROW';
	$this->view->bodyContent = 'groups/calendar/action.event.create.tpl';
