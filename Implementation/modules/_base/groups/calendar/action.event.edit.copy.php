<?php
    Warecorp::addTranslation('/modules/groups/calendar/action.event.edit.copy.php.xml');

    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();
        
    $objEventList = new Warecorp_ICal_Event_List();
    $objEventList->setTimezone( $currentTimezone );
    $eventInfo = $objEventList->findEvent($objEvent, $objRequest->getParam('id'), $objRequest->getParam('uid'), $objRequest->getParam('year'), $objRequest->getParam('month'), $objRequest->getParam('day'));

    if ( null === $eventInfo ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $this->_redirect($this->currentGroup->getGroupPath('calendar.action.confirm'));    
    }
    
    $objCopyEvent           = $eventInfo['objEvent'];
    $objDefaultStartDate    = $eventInfo['date_in_event_timezone'];
    $durationSec            = $eventInfo['durationSec'];

    
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
        * | Категории для копии нельзя изменять, они беруться от основного события
        * +-------------------------------------------------------------------
        */
        /*
        if ( 0 == $objRequest->getParam('event_event_type_1', 0) && 0 == $objRequest->getParam('event_event_type_2', 0) && 0 == $objRequest->getParam('event_event_type_3', 0) ) {
            $form->addCustomErrorMessage('Warecorp::t(Select please at least one event type'));
        }
        */
        
        /**
        * +-------------------------------------------------------------------
        * | Validate Form Data
        * +-------------------------------------------------------------------
        */
        if ( $form->validate( $objRequest->getParams() ) ) {
            
            if ( 0 == $objRequest->getParam('event_duration_hour') && 0 == $objRequest->getParam('event_duration_minute') ) {
                $objRequest->setParam('event_duration_hour', 1);
            }

            $event_dtstart = $objRequest->getParam('event_dtstart');
            
            /**
            * +-------------------------------------------------------------------
            * | текущая копия события является ранее созданным исключением
            * +-------------------------------------------------------------------
            */           
            if ( $eventInfo['isException'] ) {
                
                if ( $objRequest->getParam('event_title') == $objEvent->getTitle() ) $objCopyEvent->setTitle(new Zend_Db_Expr('NULL'));
                else $objCopyEvent->setTitle($objRequest->getParam('event_title'));

                if ( $objRequest->getParam('event_description') == $objEvent->getDescription() ) $objCopyEvent->setDescription(new Zend_Db_Expr('NULL'));
                else $objCopyEvent->setDescription($objRequest->getParam('event_description'));

                /**
                * Event Picture save :
                */
                if ( null !== $objRequest->getParam('event_picture_id', null) && $objRequest->getParam('event_picture_id') ) {
                    $objCopyEvent->setPictureId($objRequest->getParam('event_picture_id'));
                } else {
                    $objCopyEvent->setPictureId(null);
                }
                
                /**
                * build event dates for save
                * определение дат начала и окончания события (в виде строки)
                */
                if ( $objRequest->getParam('event_is_allday', null) ) {
                    $objCopyEvent->setAllDay(true);
                    $strDtstart = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T000000';
                    $strDtend   = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T235959';
                } else {
                    $objEvent->setAllDay(false);
                    $strDtstart = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T'.sprintf('%02d',$objRequest->getParam('event_time_hour')).''.sprintf('%02d',$objRequest->getParam('event_time_minute')).'00';        
                    /**
                    * Определяем дату окончания события
                    * работаем в таймзоне события - если задано, либо в текущей таймзоне (таймзоне польователя)
                    */
                    $defaultTimeZone = date_default_timezone_get();
                    date_default_timezone_set( ($objRequest->getParam('event_timezone_mode')) ? $objRequest->getParam('event_timezone') : $currentTimezone );
                    $objDtstart = new Zend_Date($strDtstart, Zend_Date::ISO_8601);
                    $objDtstart->addHour($objRequest->getParam('event_duration_hour'));
                    $objDtstart->addMinute($objRequest->getParam('event_duration_minute'));
                    $strDtend = $objDtstart->toString('yyyy-MM-ddTHHmmss');
                    unset($objDtstart);
                    date_default_timezone_set($defaultTimeZone);
                }
                
                $objCopyEvent->setDtstart($strDtstart);
                $objCopyEvent->setDtend($strDtend);
                
                /**
                * Event Timezone :
                */
                if ( $objRequest->getParam('event_is_allday', null) ) {
                    $objCopyEvent->setTimezone(null);
                } else {
                    $objCopyEvent->setTimezone( ( $objRequest->getParam('event_timezone_mode') ) ? $objRequest->getParam('event_timezone') : $currentTimezone );
                }
                
                /**
                * Save Event Categories :
                * Категории для копии нельзя изменять, они берутся от основного события
                */

                /**
                * Event Reminders :
                */
                if ( 2 == $objRequest->getParam('event_reminder_mode') ) {
                    if ( $objRequest->getParam('event_reminder_1') ) {
                        $objReminder = new Warecorp_ICal_Reminder();
                        $objReminder->setDuration($objRequest->getParam('event_reminder_1'));
                        $objReminder->setEntireGuests( (null === $objRequest->getParam('event_reminder_to_guest_list', null)) ? 0 : 1 );
                        $objCopyEvent->getReminders()->add($objReminder);
                        
                    }
                    if ( $objRequest->getParam('event_reminder_2') ) {
                        $objReminder = new Warecorp_ICal_Reminder();
                        $objReminder->setDuration($objRequest->getParam('event_reminder_2'));
                        $objReminder->setEntireGuests( (null === $objRequest->getParam('event_reminder_to_guest_list', null)) ? 0 : 1 );
                        $objCopyEvent->getReminders()->add($objReminder);
                    }
                }
                
                /**
                * Save Event Documents
                */
                if ( null !== $objRequest->getParam('event_documents', null) ) {
                    foreach ( $objRequest->getParam('event_documents') as $docId ) {
                        $objDocument = new Warecorp_Document_Item($docId);
                        $objCopyEvent->getDocuments()->add($objDocument);
                    }
                }
                
                /**
                * Save Event Lists
                */
                if ( null !== $objRequest->getParam('event_lists', null) ) {
                    foreach ( $objRequest->getParam('event_lists') as $listId ) {
                        $objList = new Warecorp_List_Item($listId);
                        $objCopyEvent->getLists()->add($objList);
                    }
                }
            
                /**
                * Save Event Venues
                */
                if ( null !== $objRequest->getParam('event_venue_id', null) && 0 != floor($objRequest->getParam('event_venue_id')) ) {
                    $objVenue = new Warecorp_Venue_Item($objRequest->getParam('event_venue_id', null));
                    $objCopyEvent->getVenues()->add($objVenue);
                }
            
                /**
                 * Set http context
                 */
                $objCopyEvent->setHttpContext($objEvent->getRootEvent()->getHttpContext());
                
                /**
                 * 
                 */
                $objCopyEvent->save();

                /** Send notification to host **/
                $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $objCopyEvent, "EVENT", "CHANGES", false );
                
//                if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                    $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                    $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setSender($this->currentGroup);
//                    $mail->addRecipient($this->currentGroup->getHost());
//                    $mail->addParam('Group', $this->currentGroup);
//                    $mail->addParam('action', "CHANGES");
//                    $mail->addParam('section', "EVENT");
//                    $mail->addParam('chObject', $objCopyEvent);
//                    $mail->addParam('User', $this->_page->_user);
//                    $mail->addParam('isPlural', false);
//                    $mail->sendToPMB(true);
//                    $mail->send();
//                }
                /** --- **/

                $objEvent->getRootEvent()->clearCache();

                /**
                * Event Tags :
                * Теги для копии нельзя изменять, они беруться от основного события
                */
                
                /**
                * Build Reminders Cache :
                */
                $cache = new Warecorp_ICal_Reminder_Cache();
                $cache->build($objEvent->getRootEvent());
                
                /**
                * Send Invitations and add Attendee            
                */
                $objEventInvite = new Warecorp_ICal_Invitation();
                $objEventInvite->setAllowGroups(true);
                $objEventInvite->setEventId($objCopyEvent->getId());
                $objEventInvite->setEvent($objCopyEvent);
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
                    }        
                }                        
                $objEventInvite->setRecipients($recipients, $inviteGroups, $inviteLists, $inviteFBUsers);

                /**
                 * @see issue #10184
                 */
                if ( !$objEventInvite->__equals($objCopyEvent->getInvite()) ) {
                    /* Сохраняем attendee для исключения и отправляем приглашения пользователям на данное исключение */
                    $tmpInvite = new Warecorp_ICal_Invitation();
                    $tmpInvite->loadByEventId($objCopyEvent->getId());
                    if ( null !== $tmpInvite->getId() ) { $objEventInvite->setId($tmpInvite->getId()); }
                    $objEventInvite->__save();
                    $objEventInvite->__saveAttendeeCopyChanges();
                } else {
                    /* Отправляем уведомление об изменении копии события */
                    $objEventInvite->__sendAttendeeCopyChanged();
                }
            
                $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'EDIT';
                $_SESSION['_calendar_']['_confirmPage_']['eventId'] = $objEvent->getId();
                $this->_redirect($this->currentGroup->getGroupPath('calendar.action.confirm'));            
            } 
            /**
            * +-------------------------------------------------------------------
            * | текущая копия события не является ранее созданным исключением
            * | создаем новое исключение для события
            * +-------------------------------------------------------------------
            */           
            else {
                $objCopyEvent = new Warecorp_ICal_Event();
                
                if ( $objRequest->getParam('event_title') == $objEvent->getTitle() ) $objCopyEvent->setTitle(new Zend_Db_Expr('NULL'));
                else $objCopyEvent->setTitle($objRequest->getParam('event_title'));

                if ( $objRequest->getParam('event_description') == $objEvent->getDescription() ) $objCopyEvent->setDescription(new Zend_Db_Expr('NULL'));
                else $objCopyEvent->setDescription($objRequest->getParam('event_description'));

                /**
                * Event Picture save :
                */
                if ( null !== $objRequest->getParam('event_picture_id', null) && $objRequest->getParam('event_picture_id') ) {
                    $objCopyEvent->setPictureId($objRequest->getParam('event_picture_id'));
                } else {
                    $objCopyEvent->setPictureId(null);
                }

                /**
                * build event dates for save
                * определение дат начала и окончания события (в виде строки)
                */
                if ( $objRequest->getParam('event_is_allday', null) ) {
                    $objCopyEvent->setAllDay(true);
                    $strDtstart = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T000000';
                    $strDtend   = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T235959';
                } else {
                    $objEvent->setAllDay(false);
                    $strDtstart = sprintf('%04d',$event_dtstart['date_Year']).'-'.sprintf('%02d',$event_dtstart['date_Month']).'-'.sprintf('%02d',$event_dtstart['date_Day']).'T'.sprintf('%02d',$objRequest->getParam('event_time_hour')).''.sprintf('%02d',$objRequest->getParam('event_time_minute')).'00';        
                    /**
                    * Определяем дату окончания события
                    * работаем в таймзоне события - если задано, либо в текущей таймзоне (таймзоне польователя)
                    */
                    $defaultTimeZone = date_default_timezone_get();
                    date_default_timezone_set( ($objRequest->getParam('event_timezone_mode')) ? $objRequest->getParam('event_timezone') : $currentTimezone );
                    $objDtstart = new Zend_Date($strDtstart, Zend_Date::ISO_8601);
                    $objDtstart->addHour($objRequest->getParam('event_duration_hour'));
                    $objDtstart->addMinute($objRequest->getParam('event_duration_minute'));
                    $strDtend = $objDtstart->toString('yyyy-MM-ddTHHmmss');
                    unset($objDtstart);
                    date_default_timezone_set($defaultTimeZone);
                }
                
                $objCopyEvent->setDtstart($strDtstart);
                $objCopyEvent->setDtend($strDtend);
                
                /**
                * Event Timezone :
                */
                if ( $objRequest->getParam('event_is_allday', null) ) {
                    $objCopyEvent->setTimezone(null);
                } else {
                    $objCopyEvent->setTimezone( ( $objRequest->getParam('event_timezone_mode') ) ? $objRequest->getParam('event_timezone') : $currentTimezone );
                }
                
                /**
                * set owner
                */
                $objCopyEvent->setCreatorId($objEvent->getCreatorId());
                $objCopyEvent->setOwnerId($objEvent->getOwnerId());
                $objCopyEvent->setOwnerType($objEvent->getOwnerType());
                $objCopyEvent->setPrivacy($objEvent->getPrivacy());
                
                /**
                * Save Event Categories :
                * Категории для копии нельзя изменять, они беруться от основного события
                */
                
                /**
                * Event Reminders :
                */
                if ( 2 == $objRequest->getParam('event_reminder_mode') ) {
                    if ( $objRequest->getParam('event_reminder_1') ) {
                        $objReminder = new Warecorp_ICal_Reminder();
                        $objReminder->setDuration($objRequest->getParam('event_reminder_1'));
                        $objReminder->setEntireGuests( (null === $objRequest->getParam('event_reminder_to_guest_list', null)) ? 0 : 1 );
                        $objCopyEvent->getReminders()->add($objReminder);
                        
                    }
                    if ( $objRequest->getParam('event_reminder_2') ) {
                        $objReminder = new Warecorp_ICal_Reminder();
                        $objReminder->setDuration($objRequest->getParam('event_reminder_2'));
                        $objReminder->setEntireGuests( (null === $objRequest->getParam('event_reminder_to_guest_list', null)) ? 0 : 1 );
                        $objCopyEvent->getReminders()->add($objReminder);
                    }
                }
                
                /**
                * Save Event Documents
                */
                if ( null !== $objRequest->getParam('event_documents', null) ) {
                    foreach ( $objRequest->getParam('event_documents') as $docId ) {
                        $objDocument = new Warecorp_Document_Item($docId);
                        $objCopyEvent->getDocuments()->add($objDocument);
                    }
                }
            
                /**
                * Save Event Lists
                */
                if ( null !== $objRequest->getParam('event_lists', null) ) {
                    foreach ( $objRequest->getParam('event_lists') as $listId ) {
                        $objList = new Warecorp_List_Item($listId);
                        $objCopyEvent->getLists()->add($objList);
                    }
                }
                
                /**
                * Save Event Venues
                */
                if ( null !== $objRequest->getParam('event_venue_id', null) && 0 != floor($objRequest->getParam('event_venue_id')) ) {
                    $objVenue = new Warecorp_Venue_Item($objRequest->getParam('event_venue_id', null));
                    $objCopyEvent->getVenues()->add($objVenue);
                }
                
                /**
                * Build recurrence
                */
                $objCopyEvent->setUid($objEvent->getId());
                $objCopyEvent->setRootId($objEvent->getRootId());
                $objCopyEvent->setRecurrenceId($eventInfo['date_in_event_timezone']->toString('yyyy-MM-ddTHHmmss'));

                /**
                 * Set http context
                 */
                $objCopyEvent->setHttpContext($objEvent->getRootEvent()->getHttpContext());
                
                $objCopyEvent->save();

                /** Send notification to host **/
                $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $objEvent, "EVENT", "CHANGES", false );
                
//                if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                    $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                    $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setSender($this->currentGroup);
//                    $mail->addRecipient($this->currentGroup->getHost());
//                    $mail->addParam('Group', $this->currentGroup);
//                    $mail->addParam('action', "CHANGES");
//                    $mail->addParam('section', "EVENT");
//                    $mail->addParam('chObject', $objEvent);
//                    $mail->addParam('User', $this->_page->_user);
//                    $mail->addParam('isPlural', false);
//                    $mail->sendToPMB(true);
//                    $mail->send();
//                }
                /** --- **/

                $objEvent->getRootEvent()->clearCache();
                
                /**
                * Event Tags :
                * Теги для копии нельзя изменять, они беруться от основного события
                */
            
                /**
                * Build Reminders Cache :
                */
                $cache = new Warecorp_ICal_Reminder_Cache();
                $cache->build($objEvent->getRootEvent());
                
                /**
                * Send Invitations and add Attendee            
                */
                $objEventInvite = new Warecorp_ICal_Invitation();
                $objEventInvite->setAllowGroups(true);
                $objEventInvite->setEventId($objCopyEvent->getId());
                $objEventInvite->setEvent($objCopyEvent);
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
                    /**
                     * Сохраняем attendee для исключения и отправляем приглашения пользователям на данное исключение
                     * Save new invitation object, create new attendee for this new invitation object and send invitation to users
                     */
                    $objEventInvite->__save();
                    $objEventInvite->__saveAttendeeCopyCreated();
                } else {
                    /**
                     * Отправляем уведомление об изменении основного события и создании исключения в событии
                     * send notification to event guests and create exeption for event
                     */                    
                    $objEventInvite->__sendAttendeeCopyCreated();
                }
                            
                $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'EDIT';
                $_SESSION['_calendar_']['_confirmPage_']['eventId'] = $objEvent->getId();
                $this->_redirect($this->currentGroup->getGroupPath('calendar.action.confirm'));            
            }
        }
        
        /**
        * +-------------------------------------------------------------------
        * | Подготавливаем параметры, если форма не прошла валидацию
        * +-------------------------------------------------------------------
        */
        
        $objCopyEvent->setTitle($objRequest->getParam('event_title'));
        $objCopyEvent->setDescription($objRequest->getParam('event_description'));

        if ( null !== $objRequest->getParam('event_picture_id', null) && $objRequest->getParam('event_picture_id') ) {
            $objCopyEvent->setPictureId($objRequest->getParam('event_picture_id'));
        } else {
            $objCopyEvent->setPictureId(null);
        }
        
        /**
        * Event Start Date : Формируем дату начала события, береться из значений формы
        * сначала формируем строку, потом из нее в нужной таймзоне создаем объект
        */
        $event_dtstart = $objRequest->getParam('event_dtstart');        
        if ( !$objRequest->getParam('event_is_allday') ) $this->getRequest()->setParam('event_is_allday', 0);                
        if ( $objRequest->getParam('event_is_allday') ) {
            /**
            * Если событие на весь день - то дата устанавливается 
            * как текущая дата и время в локальной зоне
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
        * создаем объект даты в зоне пользователя, 
        * который просматривает страницу, если анонимный в UTC
        */
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set( $currentTimezone );
        $objDefaultStartDate = new Zend_Date($strDtstart, Zend_Date::ISO_8601);
        date_default_timezone_set($defaultTimeZone);     
        
        /**
        * Rrule Until Date : Формируем дату окончания репитера, берем из формы 
        * создаем время в зоне пользователя, который просматривает страницу, если анонимный в UTC 
        */
        $objDefaultUntilDate = clone $objDefaultStartDate; 
    }
    /**
    * +-----------------------------------------------------------------------
    * | Handle Form View
    * +-----------------------------------------------------------------------
    */
    else {        
        
        /**
        * Restore Owner
        */
        $objRequest->setParam('event_owner_id', $objCopyEvent->getOwnerId());

        /**
        * Restore Params
        */
        $objRequest->setParam('event_tags',                 $objCopyEvent->getTags()->getAsString());
        $objRequest->setParam('event_privacy',              $objCopyEvent->getPrivacy());
        $objRequest->setParam('event_timezone_mode',        ( $objCopyEvent->getTimezone() && $currentTimezone != $objCopyEvent->getTimezone() ) ? 1 : 0);
        $objRequest->setParam('event_timezone',             ( $objCopyEvent->getTimezone() ) ? $objCopyEvent->getTimezone() : $currentTimezone);
        $objRequest->setParam('event_is_allday',            ( $objCopyEvent->isAllDay() ) ? 1 : 0 );
        
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
        * Категории для копии нельзя изменять, они беруться от основного события 
        */
        
        /**
        * Restore Invitation
        */
        $objRequest->setParam('event_invitations_from', $objCopyEvent->getInvite()->getFrom());
        $objRequest->setParam('event_invitations_emails', $objCopyEvent->getInvite()->getTo());
        $objRequest->setParam('event_invitations_subject', $objCopyEvent->getInvite()->getSubject());
        $objRequest->setParam('event_invitations_message', $objCopyEvent->getInvite()->getMessage());
        $objRequest->setParam('event_allow_guests_invitation', $objCopyEvent->getInvite()->getAllowGuestToInvite());
        $objRequest->setParam('receive_no_rsvp_email', $objCopyEvent->getInvite()->getReceiveNoRsvpEmail());
        $objRequest->setParam('event_display_guests', $objCopyEvent->getInvite()->getDisplayListToGuest());
        $objRequest->setParam('is_anybody_join', $objCopyEvent->getInvite()->getIsAnybodyJoin());
        
        $objRequest->setParam('event_invitations_lists', $objCopyEvent->getAttendee()->getObjectsIdsList('list'));
        $arrGroups = $objCopyEvent->getAttendee()->getObjectsIdsList(Warecorp_ICal_Enum_OwnerType::GROUP);
        $objRequest->setParam('event_invitations_groups', $arrGroups);
        if ( in_array($this->currentGroup->getId(), $arrGroups) ) $objRequest->setParam('event_invite_entire_group', 1);
        
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
            $durationSec = (isset($durationSec)) ? $durationSec : $arrCurrEvent['duration'];
            $objRequest->setParam('event_duration_hour', floor($durationSec / 60 / 60));
            $objRequest->setParam('event_duration_minute', ($durationSec - floor($durationSec / 3600) * 3600 ) / 60);
        }
    }
