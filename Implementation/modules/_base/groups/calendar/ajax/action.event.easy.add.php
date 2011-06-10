<?php
    Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.easy.add.php.xml');

	$M_groups_calendar_ajax_action_event_easy_add_1 = Warecorp::t('Event Title is required');
	$M_groups_calendar_ajax_action_event_easy_add_2 = Warecorp::t('Choose event category');
	$M_groups_calendar_ajax_action_event_easy_add_3 = Warecorp::t("Easy Add Event");
	$M_groups_calendar_ajax_action_event_easy_add_4 = Warecorp::t('We are sorry, you can not add new event');

    $objResponse = new xajaxResponse();

    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('calendar.month.view');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        return $objResponse;
    }

    if ( !Warecorp_ICal_AccessManager_Factory::create()->canCreateEvent($this->currentGroup, $this->_page->_user) ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = $M_groups_calendar_ajax_action_event_easy_add_4;
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));
        return $objResponse;
    }

    //FIXME определить , какая таймзона является дефолтовой
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того,
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    /**
    * Check date
    */
    $dataIsExist = true;
    $dataIsExist = $dataIsExist && $year;
    $dataIsExist = $dataIsExist && $month;
    $dataIsExist = $dataIsExist && $day;
    if ( !$dataIsExist )  $viewMode = 'ROW'; else $viewMode = 'COPY';

    $year = ( floor($year) < 1970 ) ? 1970 : floor($year);
    $year = ( floor($year) > 2038 ) ? 2038 : floor($year);
    $month = ( floor($month) < 1 ) ? 1 : floor($month);
    $month = ( floor($month) > 12 ) ? 12 : floor($month);
    $oCDate = new Zend_Date(sprintf('%04d', $year).'-'.sprintf('%02d', $month).'-01', Zend_Date::ISO_8601);
    $day = ( floor($day) < 1 ) ? 1 : floor($day);
    $day = ( floor($day) > $oCDate->get(Zend_Date::MONTH_DAYS)) ?  $oCDate->get(Zend_Date::MONTH_DAYS) : floor($day);
    unset($oCDate);

    /**
    * Date Now : текущее время для текущего пользователя
    */
    $defaultTimeZone = date_default_timezone_get();
    date_default_timezone_set( $currentTimezone );
    $objNowDate = new Zend_Date();
    $strDate = sprintf('%04d', $year).'-'.sprintf('%02d', $month).'-'.sprintf('%02d', $day).'T'.$objNowDate->toString('HHmmss');
    $objDate = new Zend_Date($strDate, Zend_Date::ISO_8601);
    if ( $objDate->get(Zend_Date::MINUTE) > 0 && $objDate->get(Zend_Date::MINUTE) < 15 ) $objDate->setMinute(15);
    elseif ( $objDate->get(Zend_Date::MINUTE) > 15 && $objDate->get(Zend_Date::MINUTE) < 30 ) $objDate->setMinute(30);
    elseif ( $objDate->get(Zend_Date::MINUTE) > 30 && $objDate->get(Zend_Date::MINUTE) < 45 ) $objDate->setMinute(45);
    elseif ( $objDate->get(Zend_Date::MINUTE) > 45 ) {
        $objDate->addHour(1);
        $objDate->setMinute(0);
    }
    date_default_timezone_set($defaultTimeZone);

    $form = new Warecorp_Form('easyAddEvent', "POST");
    $form->addRule('event_title', 'required', $M_groups_calendar_ajax_action_event_easy_add_1);
    $form->addRule('event_category', 'nonzero', $M_groups_calendar_ajax_action_event_easy_add_2);

    $this->view->hours = Warecorp_ICal_Const::getHours();
    $this->view->minutes = Warecorp_ICal_Const::$minutesOptions;

    $categories = new Warecorp_ICal_Category_List();
    $categories->setPairsModeKey('category_id');
    $categories->setPairsModeValue('category_name');
    $categories->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS);
    $categories->setOrder('category_order DESC, category_name ASC');
    $categories = $categories->getList();
    $categories = array('0' => '------') + $categories;
    $this->view->categories = $categories;

    if ( !$handle ) {
        $this->view->month = $month;
        $this->view->day = $day;
        $this->view->year = $year;
        $this->view->form = $form;
        $this->view->objDate = $objDate;
		$this->view->event_category = Warecorp_Group_Category::getEventCategory($this->currentGroup->getCategoryId());

        $Content = $this->view->getContents('groups/calendar/ajax/action.event.easy.add.tpl');

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title($M_groups_calendar_ajax_action_event_easy_add_3);
        $popup_window->content($Content);
        $popup_window->width(370)->height(350)->open($objResponse);

        $Script = '';
        $Script .= '
            if ( !EasyAddApp.EasyAddInit ) {
                YAHOO.util.Event.addListener("event_dtstart_date_Year", "change", EasyAddApp.onDtstartDateChanged);
                YAHOO.util.Event.addListener("event_dtstart_date_Month", "change", EasyAddApp.onDtstartDateChanged);
                YAHOO.util.Event.addListener("event_dtstart_date_Day", "change", EasyAddApp.onDtstartDateChanged);
                EasyAddApp.EasyAddInit = true;
            }
        ';
        $objResponse->addScript($Script);
    }
    else {
        $_REQUEST['_wf__easyAddEvent'] = $handle['_wf__easyAddEvent'];
        if ( $form->validate($handle) ) {
            $objEvent = new Warecorp_ICal_Event();
            $objEvent->setTitle($handle['event_title']);
            $objEvent->setDescription('');

            $event_dtstart = $handle['event_dtstart'];
            $strDate = sprintf('%04d', $event_dtstart['date_Year']).'-'.sprintf('%02d', $event_dtstart['date_Month']).'-'.sprintf('%02d', $event_dtstart['date_Day']);
            if ( isset($handle['event_is_allday']) && $handle['event_is_allday'] == 1 ) {
                $objEvent->setAllDay(true);
                $strDateEnd = $strDate.'T235959';
                $strDate = $strDate.'T000000';
                $objEvent->setTimezone(null);
            } else {
                $objEvent->setAllDay(false);
                $strDate .= 'T'.sprintf('%02d', $handle['event_time_hour']).sprintf('%02d', $handle['event_time_minute']).'00';
                $defaultTimeZone = date_default_timezone_get();
                date_default_timezone_set( $currentTimezone );
                $objDateStart = new Zend_Date($strDate, Zend_Date::ISO_8601);
                $objDateEnd = clone $objDateStart;
                $objDateEnd->addHour(1);
                $strDateEnd = $objDateEnd->toString('yyyy-MM-ddTHHmmss');
                date_default_timezone_set($defaultTimeZone);
                $objEvent->setTimezone($currentTimezone);
            }
            $objEvent->setDtstart($strDate);
            $objEvent->setDtend($strDateEnd);

            /**
            * Event Creator and Owner :
            */
            $objEvent->setCreatorId($this->_page->_user->getId());
            $objEvent->setOwnerId($this->currentGroup->getId());
            $objEvent->setOwnerType(Warecorp_ICal_Enum_OwnerType::GROUP);

            /**
            * Event Privacy :
            */
            $objEvent->setPrivacy(Warecorp_ICal_Enum_Privacy::PRIVACY_PUBLIC);

            /**
            * Save Event Categories :
            */
            $objEventCategories = $objEvent->getCategories();
            $objEventCategories->add($handle['event_category']);

            /**
             * Set http context
             */
            $objEvent->setHttpContext(HTTP_CONTEXT);

            /**
             * Save Event
             */
            $objEvent->save();

           /**
            *   Send notification to host
            */
            $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $objEvent, "EVENT", "NEW", false );
            
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
            /** --- **/

            /**
            * Event Tags :
            */
            $objEvent->getTags()->addTags($handle['event_tags']);

            /**
            * Build Reminders Cache :
            */
            $cahce = new Warecorp_ICal_Reminder_Cache();
            $cahce->build($objEvent);

           /**
            * For "Easy add Event" invitation not need
            * Bugmine #2686
            *
            * Send Invitations and add Attendee
            */
            $objEventInvite = new Warecorp_ICal_Invitation();
            $objEventInvite->setEventId($objEvent->getId());
            $objEventInvite->setEvent($objEvent);
            $objEventInvite->setFrom('calendar@'.DOMAIN_FOR_EMAIL);
            $objEventInvite->setTo('');
            $objEventInvite->setSubject('');
            $objEventInvite->setMessage('');
            $objEventInvite->setAllowGuestToInvite(0);
            $objEventInvite->setDisplayListToGuest(0);
            /**
             * @see issue #10184
             */
            $objEventInvite->__save();

           /**
            * Добавляем в attendee создателя события
            * Add creator of event
            */
            if ( $objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::GROUP || $objEvent->getCreatorId() != $objEvent->getOwnerId() ) {
                $tmpAttendee = new Warecorp_ICal_Attendee();
                $tmpAttendee->setEventId($objEvent->getId());
                $tmpAttendee->setOwnerType(Warecorp_ICal_Enum_OwnerType::USER);
                $tmpAttendee->setOwnerId($objEvent->getCreatorId());
                $tmpAttendee->setAnswer('NONE');
                $tmpAttendee->setAnswerText('');
                $tmpAttendee->save();
            }

            /**
            * FIXME Сохранение ТЕГОВ
            */

            $objResponse->addScript('popup_window.close();');
            $objResponse->addScript('document.location.reload();');

        } else {
            $event_dtstart = $handle['event_dtstart'];
            $strDate = sprintf('%04d', $event_dtstart['date_Year']).'-'.sprintf('%02d', $event_dtstart['date_Month']).'-'.sprintf('%02d', $event_dtstart['date_Day']);
            if ( isset($handle['event_is_allday']) && $handle['event_is_allday'] == 1 ) {
                $strDate .= 'T000000';
            } else {
                $strDate .= 'T'.sprintf('%02d', $handle['event_time_hour']).sprintf('%02d', $handle['event_time_minute']).'00';
            }
            $defaultTimeZone = date_default_timezone_get();
            date_default_timezone_set( $currentTimezone );
            $objDate = new Zend_Date($strDate, Zend_Date::ISO_8601);
            date_default_timezone_set($defaultTimeZone);

            $this->view->month = $month;
            $this->view->day = $day;
            $this->view->year = $year;
            $this->view->form = $form;
            $this->view->objDate = $objDate;
            $this->view->event_title = $handle['event_title'];
            $this->view->event_tags = $handle['event_tags'];
            $this->view->event_category = $handle['event_category'];
            $this->view->event_is_allday = ( isset($handle['event_is_allday']) && $handle['event_is_allday'] == 1 ) ? 1 : 0;

            $Content = $this->view->getContents('groups/calendar/ajax/action.event.easy.add.tpl');

            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title($M_groups_calendar_ajax_action_event_easy_add_3);
            $popup_window->content($Content);
            $popup_window->width(370)->height(300)->open($objResponse);


            $Script = '';
            $Script .= '
                if ( !EasyAddApp.EasyAddInit ) {
                    YAHOO.util.Event.addListener("event_dtstart_date_Year", "change", EasyAddApp.onDtstartDateChanged);
                    YAHOO.util.Event.addListener("event_dtstart_date_Month", "change", EasyAddApp.onDtstartDateChanged);
                    YAHOO.util.Event.addListener("event_dtstart_date_Day", "change", EasyAddApp.onDtstartDateChanged);
                    EasyAddApp.EasyAddInit = true;
                }
            ';
            $objResponse->addScript($Script);
        }
    }

