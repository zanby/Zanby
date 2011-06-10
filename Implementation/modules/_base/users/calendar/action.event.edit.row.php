<?php
    Warecorp::addTranslation("/modules/users/calendar/action.event.edit.row.php.xml");
    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();

    $objOriginalEvent = clone $objEvent;
    $objEvent = $objEvent->getRootEvent();

    /**
    * +-----------------------------------------------------------------------
    * | Handle Form Callback
    * +-----------------------------------------------------------------------
    */
    if ( $form->isPostback() ) {
        $objCopyEvent = $objEvent;
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

            $objEvent->setTitle($objRequest->getParam('event_title'));
            $objEvent->setDescription($objRequest->getParam('event_description'));

            /**
            * Event Picture save :
            */
            if ( null !== $objRequest->getParam('event_picture_id', null) && $objRequest->getParam('event_picture_id') ) {
                $objEvent->setPictureId($objRequest->getParam('event_picture_id'));
            } else {
                $objEvent->setPictureId(null);
            }

            $event_dtstart = $objRequest->getParam('event_dtstart');

            /**
            * Event Rrule Objec : Создаем объект rrule для события
            */
            $rruleIsChanged = false;
            if ( $objRequest->getParam('rrule_freq') && $objRequest->getParam('rrule_freq') != 'NONE' ) {
                /**
                * check Repeat Count value
                */
                if ( 0 == floor($objRequest->getParam('rrule_until_count',1)) ) $objRequest->setParam('rrule_until_count', 10);

                $objRrule = new Warecorp_ICal_Rrule();
                $objRrule->setFromHttpRequest($objRequest);
                /**
                * Проверяем, был ли изменен объект Rrule
                */
                if ( null !== ($objOldRrule = $objEvent->getRrule()) ) {
                    $objRrule->setId($objOldRrule->getId());
                    $objRrule->setEventId($objOldRrule->getEventId());
                    $objRrule->setWkst($objOldRrule->getWkst());
                    if ( !$objRrule->equals($objOldRrule) ) $rruleIsChanged = true;
                } else $rruleIsChanged = true;
                $objEvent->setRrule($objRrule);
            } else {
                if ( null !== ($objOldRrule = $objEvent->getRrule()) ) {
                    $objOldRrule->delete();
                    $rruleIsChanged = true;
                }
                $objEvent->setRruleToNULL();
            }

            /**
            * Event Start and End Dates : определение дат начала и окончания события (в виде строки)
            */
            if ( $objRequest->getParam('event_is_allday', null) ) {
                $objEvent->setAllDay(true);
                $strDtstart = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T000000';
                $strDtend   = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T235959';
            } else {
                $objEvent->setAllDay(false);
                $strDtstart = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T'.sprintf('%02d',$objRequest->getParam('event_time_hour')).''.sprintf('%02d',$objRequest->getParam('event_time_minute')).'00';
                /**
                * Определяем дату окончания события
                * создаем время в зоне пользователя, который просматривает страницу, если анонимный в UTC,
                * чтобы обеспечить сохранение так, как было введено в форме пользователем
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

            /**
            * Check if dates changed
            * Надо перевести старое значение даты события в текущую таймзону (т.к. $strDtstart - формировалось в текущей)
            * и посмотреть, изменилась ли она
            */
            $startDateChanged   = false;
            $endDateChanged     = false;
            $originalEventTimezone = $objEvent->getTimezone();
            if ( null === $objEvent->getTimezone() ) $objEvent->setTimezone($currentTimezone);
            $objOldDtstart = clone $objEvent->getDtstart();
            $objOldDtend = clone $objEvent->getDtend();
            $objEvent->setTimezone($originalEventTimezone);

            $oldTimezone = $objEvent->getTimezone();
            if ( $objRequest->getParam('event_is_allday', null) ) $newTimezone = null;
            else $newTimezone = ( $objRequest->getParam('event_timezone_mode', null) ) ? $objRequest->getParam('event_timezone') : $currentTimezone ;

            if ( $oldTimezone != $newTimezone ) {
                $startDateChanged   = true;
                $endDateChanged     = true;
            } else {
                if ( $objOldDtstart->toString('yyyy-MM-ddTHHmmss') != $strDtstart ) $startDateChanged   = true;
                if ( $objOldDtend->toString('yyyy-MM-ddTHHmmss') != $strDtend ) $endDateChanged   = true;
            }

            /**
            *  Event Start and End Dates : Устанавливаем новые значения дат
            */
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
            * Если было установлено рекурентное правило и
            * измены даты события или само рекурентное правило, то
            * удалить все exdata и recurrenceId для данного события
            */
            if ( $startDateChanged || $endDateChanged || $rruleIsChanged ) {
                $recurrences = $objEvent->getRecurrences()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
                if ( sizeof($recurrences) != 0 ) {
                    foreach ( $recurrences as &$recurrence ) $recurrence->delete();
                }
                $objEvent->getExDates()->deleteAll();
                $objRef = new Warecorp_ICal_Event_List_Reference($objEvent);
                $objRef->deleteAllReference();
            }

            /**
            * Event Privacy : устанавливаем приватность события
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

            $objEvent->save();
            $objEvent->getRootEvent()->clearCache();
            $objEvent->getReminders()->updateChilds();

            /**
            * Event Tags :
            */
            $objEvent->getTags()->deleteTags();
            $objEvent->getTags()->addTags($objRequest->getParam('event_tags',''));

            /**
            * Build Reminders Cache :
            */
            $cache = new Warecorp_ICal_Reminder_Cache();
            $cache->build($objEvent->getRootEvent());

            /**
            * Send Invitations and add Attendee
            */
            $objEventInvite = new Warecorp_ICal_Invitation();
            $objEventInvite->setEventId($objEvent->getId());
            $objEventInvite->setEvent($objEvent);

            if ( $objRequest->getParam('event_invitations_from', 0) ) {
                $objSender = new Warecorp_User('id', $objRequest->getParam('event_invitations_from'));
                $from = $objSender->getEmail();
            } else {
                $from = 'calendar@'.DOMAIN_FOR_EMAIL;
                $objSender = clone $this->_page->_user;
                $objSender->setEmail('calendar@'.DOMAIN_FOR_EMAIL);
            }
            $objEventInvite->setFrom($from);
            $objEventInvite->setSendFrom($objSender);
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
                }        
            }                        
            $objEventInvite->setRecipients($recipients, $inviteGroups, $inviteLists, $inviteFBUsers);

            /**
             * @see issue #10184
             */            
            if ( !$objEventInvite->__equals($objEvent->getInvite()) ) {
                $objEventInvite->setId($objEvent->getInvite()->getId());
                $objEventInvite->__save();
                $objEventInvite->__saveAttendeeChanges();
            }

            $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'EDIT';
            $_SESSION['_calendar_']['_confirmPage_']['eventId'] = $objEvent->getId();
            $this->_redirect($this->currentUser->getUserPath('calendar.action.confirm'));
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
        } else {
            $objEvent->setPictureId(null);
        }

        /**
        * Event Start Date : Формируем дату начала события, береться из значений формы
        * Формируем строку даты начала события (то, как было введено в поле) в формате ISO_8601
        */
        $event_dtstart = $objRequest->getParam('event_dtstart');
        if ( !$objRequest->getParam('event_is_allday') ) $this->getRequest()->setParam('event_is_allday', 0);

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
        /**
        * Event Start Date : создаем объект даты в зоне пользователя,
        * который просматривает страницу, если анонимный в UTC, чтобы обеспечить сохранение так,
        * как было введено в форме пользователем
        */
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set( $currentTimezone );
        $objDefaultStartDate = new Zend_Date($strDtstart, Zend_Date::ISO_8601);
        date_default_timezone_set($defaultTimeZone);

        /**
        * Rrule Until Date : Формируем дату окончания репитера, берем из формы
        * создаем время в зоне пользователя, который просматривает страницу, если анонимный в UTC,
        * чтобы обеспечить сохранение так, как было введено в форме пользователем
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

        $objCopyEvent = $objEvent;

        /**
        * Restore Params
        */
        $objRequest->setParam('event_tags',                 $objCopyEvent->getTags()->getAsString());
        $objRequest->setParam('event_privacy',              $objCopyEvent->getPrivacy());
        $objRequest->setParam('event_timezone_mode',        ( $objCopyEvent->getTimezone() && $currentTimezone != $objCopyEvent->getTimezone() ) ? 1 : 0);
        $objRequest->setParam('event_timezone',             ( $objCopyEvent->getTimezone() ) ? $objCopyEvent->getTimezone() : $currentTimezone);

        /**
        * Resore Reminders
        */
        $lstReminders = $objCopyEvent->getReminders()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        if ( sizeof($lstReminders) != 0 ) {
            $_index = 1;
            $event_reminder_to_guest_list = 0;
            foreach ( $lstReminders as &$objReminder ) {
                $objRequest->setParam('event_reminder_'.$_index, $objReminder->getDuration());
                if ( $objReminder->getEntireGuests() ) $event_reminder_to_guest_list = 1;
                $_index++;
            }
            $objRequest->setParam('event_reminder_mode', 2);
            $objRequest->setParam('event_reminder_to_guest_list', $event_reminder_to_guest_list);
        }

        /**
        * Restore Categories
        */
        $eventCategories = $objCopyEvent->getCategories()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();
        if ( sizeof($eventCategories) != 0 ) {
            $ind = 1;
            foreach ( $eventCategories as $value ) {
                $objRequest->setParam('event_event_type_'.$ind, $value);
                $ind++;
            }
        }

        /**
        * Restore Invitation
        */
        if ( 'calendar@'.DOMAIN_FOR_EMAIL == $objCopyEvent->getInvite()->getFrom() ) {
            $objRequest->setParam('event_invitations_from', 0);
        } else {
            $tmpUser = new Warecorp_User('email', $objCopyEvent->getInvite()->getFrom());
            if ( null === $tmpUser->getId() ) $objRequest->setParam('event_invitations_from', 0);
            else $objRequest->setParam('event_invitations_from', $tmpUser->getId());
        }
        $objRequest->setParam('event_invitations_emails', $objCopyEvent->getInvite()->getTo());
        $objRequest->setParam('event_invitations_subject', $objCopyEvent->getInvite()->getSubject());
        $objRequest->setParam('event_invitations_message', $objCopyEvent->getInvite()->getMessage());
        $objRequest->setParam('event_allow_guests_invitation', $objCopyEvent->getInvite()->getAllowGuestToInvite());
        $objRequest->setParam('receive_no_rsvp_email', $objCopyEvent->getInvite()->getReceiveNoRsvpEmail());
        $objRequest->setParam('event_display_guests', $objCopyEvent->getInvite()->getDisplayListToGuest());
        $objRequest->setParam('is_anybody_join', $objCopyEvent->getInvite()->getIsAnybodyJoin());

        $objRequest->setParam('event_invitations_lists', $objCopyEvent->getAttendee()->getObjectsIdsList('list'));
        $objRequest->setParam('event_invitations_groups', $objCopyEvent->getAttendee()->getObjectsIdsList(Warecorp_ICal_Enum_OwnerType::GROUP));

        /**
        * Restore Documents
        */
        $lstDocuments = $objCopyEvent->getDocuments()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();
        if ( sizeof($lstDocuments) != 0 ) {
            $objRequest->setParam('event_documents', $lstDocuments);
        }

        /**
        * Restore Lists
        */
        $lstLists = $objCopyEvent->getLists()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();
        if ( sizeof($lstLists) != 0 ) {
            $objRequest->setParam('event_lists', $lstLists);
        }

        /**
        * Restore Venue
        */
        $eventVenue = $objCopyEvent->getEventVenue();
        if ( null !== $eventVenue && null !== $eventVenue->getId() ) {
            if ($eventVenue->getType() == Warecorp_Venue_Enum_VenueType::SIMPLE) {
                $objRequest->setParam('event_venue_type', 'simple');
            } else {
                $objRequest->setParam('event_venue_type', 'worldwide');
            }
            $objRequest->setParam('event_venue_id', $eventVenue->getId());
        }

        /**
        * Event Date Start :
        * Если для события была указана таймзона - восстанавливаем дату в этой таймзоне,
        * если не была указана - в текущей таймзоне
        */
        $originalEventTimezone = $objCopyEvent->getTimezone();
        if ( null === $objCopyEvent->getTimezone() ) $objCopyEvent->setTimezone($currentTimezone);
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set( $objCopyEvent->getTimezone() );
        $durationSec = $objCopyEvent->getDurationSec();
        $objDefaultStartDate = clone $objCopyEvent->getDtstart();
        date_default_timezone_set($defaultTimeZone);
        $objCopyEvent->setTimezone($originalEventTimezone);

        $objRequest->setParam('event_is_allday', ( $objCopyEvent->isAllDay() ) ? 1 : 0 );
        if ( $objCopyEvent->isAllDay() ) {
            /**
            * Если событие на весь день - то дата устанавливается
            * как текущая дата и время в локальной зоне
            * при этом формируем кратность 15 минутам, т.к. такой формат у селектов на форме
            */
            $objTime = clone $objNowDate;
            if ( $objTime->get(Zend_Date::MINUTE) > 0 && $objTime->get(Zend_Date::MINUTE) < 15 ) $objTime->setMinute(15);
            elseif ( $objTime->get(Zend_Date::MINUTE) > 15 && $objTime->get(Zend_Date::MINUTE) < 30 ) $objTime->setMinute(30);
            elseif ( $objTime->get(Zend_Date::MINUTE) > 30 && $objTime->get(Zend_Date::MINUTE) < 45 ) $objTime->setMinute(45);
            elseif ( $objTime->get(Zend_Date::MINUTE) > 45 ) {
                $objTime->addHour(1);
                $objTime->setMinute(0);
            }
            $objDefaultStartDate->setHour($objTime->get(Zend_Date::HOUR));
            $objDefaultStartDate->setMinute($objTime->get(Zend_Date::MINUTE));
            $objRequest->setParam('event_duration_hour', 1);
            $objRequest->setParam('event_duration_minute', 0);
        } else {
            $durationSec = $objCopyEvent->getDurationSec();
            $objRequest->setParam('event_duration_hour', floor($durationSec / 60 / 60));
            $objRequest->setParam('event_duration_minute', ($durationSec - floor($durationSec / 3600) * 3600 ) / 60);
        }

        /**
        * Restore Rrule
        */
        if ( null !== $objEvent->getRrule() ) {
            $objEvent->getRrule()->setHttpRequest($objRequest, $objEvent->getTimezone(), $currentTimezone);
        }
    }
