<?php
    Warecorp::addTranslation('/modules/groups/calendar/action.event.edit.php.xml');

    $this->view->Warecorp_ICal_AccessManager = Warecorp_ICal_AccessManager_Factory::create();

    if ( null === $this->_page->_user->getId() ) $this->_redirectToLogin();

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
    * Check event
    */
    if ( null === $objRequest->getParam('id', null) || null === $objRequest->getParam('uid', null) ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $this->_redirect($this->currentGroup->getGroupPath('calendar.action.confirm'));
    }
    $objEvent = new Warecorp_ICal_Event($objRequest->getParam('id'));
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $this->_redirect($this->currentGroup->getGroupPath('calendar.action.confirm'));
    }
    $objEvent = new Warecorp_ICal_Event($objRequest->getParam('uid'));
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $this->_redirect($this->currentGroup->getGroupPath('calendar.action.confirm'));
    }

    /**
    * Check Access
    */
    if ( false == Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentGroup, $this->_page->_user) ) {
        $this->view->errorMessage = Warecorp::t('Sorry, you can not edit this event');
        $this->view->bodyContent = 'groups/calendar/action.event.error.message.tpl';
        return ;
    }

    /**
    * Check date
    */
    $dataIsExist = true;
    $dataIsExist = $dataIsExist && $objRequest->getParam('year', null);
    $dataIsExist = $dataIsExist && $objRequest->getParam('month', null);
    $dataIsExist = $dataIsExist && $objRequest->getParam('day', null);
    if ( !$dataIsExist )  $viewMode = 'ROW'; else $viewMode = 'COPY';

    $objRequest->setParam('year', ( floor($objRequest->getParam('year')) < 1970 ) ? 1970 : floor($objRequest->getParam('year')) );
    $objRequest->setParam('year', ( floor($objRequest->getParam('year')) > 2038 ) ? 2038 : floor($objRequest->getParam('year')) );
    $objRequest->setParam('month', ( floor($objRequest->getParam('month')) < 1 ) ? 1 : floor($objRequest->getParam('month')) );
    $objRequest->setParam('month', ( floor($objRequest->getParam('month')) > 12 ) ? 12 : floor($objRequest->getParam('month')) );
    $oCDate = new Zend_Date(sprintf('%04d', $objRequest->getParam('year')).'-'.sprintf('%02d', $objRequest->getParam('month')).'-01', Zend_Date::ISO_8601);
    $objRequest->setParam('day', ( floor($objRequest->getParam('day')) < 1 ) ? 1 : floor($objRequest->getParam('day')) );
    $objRequest->setParam('day', ( floor($objRequest->getParam('day')) > $oCDate->get(Zend_Date::MONTH_DAYS)) ?  $oCDate->get(Zend_Date::MONTH_DAYS) : floor($objRequest->getParam('day')) );
    unset($oCDate);

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
    if ( $viewMode == 'ROW' ) {
        $formURL = $this->currentGroup->getGroupPath('calendar.event.edit/id/'.$objRequest->getParam('id').'/uid/'.$objRequest->getParam('uid'));
    } elseif ( 'future' == $objRequest->getParam('mode', null) ) {
        $formURL = $this->currentGroup->getGroupPath('calendar.event.edit/id/'.$objRequest->getParam('id').'/uid/'.$objRequest->getParam('uid').'/year/'.$objRequest->getParam('year').'/month/'.$objRequest->getParam('month').'/day/'.$objRequest->getParam('day').'/mode/future');
    } else {
        $formURL = $this->currentGroup->getGroupPath('calendar.event.edit/id/'.$objRequest->getParam('id').'/uid/'.$objRequest->getParam('uid').'/year/'.$objRequest->getParam('year').'/month/'.$objRequest->getParam('month').'/day/'.$objRequest->getParam('day'));
    }
    $form = new Warecorp_Form('form_add_event', 'POST', $formURL);
    $form->addRule( 'event_title', 'required', Warecorp::t("Field 'Event Title' is required") );
    $form->addRule( 'event_description', 'maxlength', Warecorp::t('Event Description too long (max %s symbols)',2000), array('max' => 2000 ) );


    if ( $viewMode == 'ROW' ) {
        require_once('action.event.edit.row.php');
    } elseif ( 'future' == $objRequest->getParam('mode', null) ) {
        $this->view->editFutureDates = true;
        require_once('action.event.edit.future.php');
    } else {
        require_once('action.event.edit.copy.php');
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
    $aoVenuesList->setOwnerType( Warecorp_Venue_Enum_OwnerType::USER );
    $aoVenuesList->setOwnerId( $this->_page->_user->getId() );
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
    //$formParams['rrule_weekly_byday1']          = ( $objRequest->getParam('rrule_weekly_byday1') )                      ? $objRequest->getParam('rrule_weekly_byday1')          : array(Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($objNowDate->get(Zend_Date::WEEKDAY_DIGIT)));
    $formParams['rrule_weekly_byday1']          = ( $objRequest->getParam('rrule_weekly_byday1') )                      ? $objRequest->getParam('rrule_weekly_byday1')          : array(Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($objDefaultStartDate->get(Zend_Date::WEEKDAY_DIGIT)));
    if ( sizeof($formParams['rrule_weekly_byday1']) != 0 ) {
        foreach ( $formParams['rrule_weekly_byday1'] as $w ) $formParams['rrule_weekly_byday1'][$w] = $w;
    }

    $formParams['rrule_monthly_option']         = ( $objRequest->getParam('rrule_monthly_option') )                     ? $objRequest->getParam('rrule_monthly_option')         : 1;
    //$formParams['rrule_monthly_bymonthday1']    = ( $objRequest->getParam('rrule_monthly_bymonthday1') )                ? $objRequest->getParam('rrule_monthly_bymonthday1')    : $objNowDate->get(Zend_Date::DAY_SHORT); // cur month day
    $formParams['rrule_monthly_bymonthday1']    = ( $objRequest->getParam('rrule_monthly_bymonthday1') )                ? $objRequest->getParam('rrule_monthly_bymonthday1')    : $objDefaultStartDate->get(Zend_Date::DAY_SHORT); // cur month day
    $formParams['rrule_monthly_interval1']      = ( $objRequest->getParam('rrule_monthly_interval1') )                  ? $objRequest->getParam('rrule_monthly_interval1')      : 1;
    $formParams['rrule_monthly_setpos2']        = ( $objRequest->getParam('rrule_monthly_setpos2') )                    ? $objRequest->getParam('rrule_monthly_setpos2')        : 1;
    //$formParams['rrule_monthly_byday2']         = ( $objRequest->getParam('rrule_monthly_byday2') )                     ? $objRequest->getParam('rrule_monthly_byday2')         : Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($objNowDate->get(Zend_Date::WEEKDAY_DIGIT)); // cur weekday
    $formParams['rrule_monthly_byday2']         = ( $objRequest->getParam('rrule_monthly_byday2') )                     ? $objRequest->getParam('rrule_monthly_byday2')         : Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($objDefaultStartDate->get(Zend_Date::WEEKDAY_DIGIT)); // cur weekday
    $formParams['rrule_monthly_interval2']      = ( $objRequest->getParam('rrule_monthly_interval2') )                  ? $objRequest->getParam('rrule_monthly_interval2')      : 1;
    $formParams['rrule_monthly_bymonthday3']    = ( $objRequest->getParam('rrule_monthly_bymonthday3') )                ? $objRequest->getParam('rrule_monthly_bymonthday3')    : 1;
    $formParams['rrule_monthly_interval3']      = ( $objRequest->getParam('rrule_monthly_interval3') )                  ? $objRequest->getParam('rrule_monthly_interval3')      : 1;

    $formParams['rrule_yearly_option']          = ( $objRequest->getParam('rrule_yearly_option') )                      ? $objRequest->getParam('rrule_yearly_option')          : 1;
    //$formParams['rrule_yearly_bymonthday1']     = ( $objRequest->getParam('rrule_yearly_bymonthday1') )                 ? $objRequest->getParam('rrule_yearly_bymonthday1')     : $objNowDate->get(Zend_Date::DAY_SHORT);
    //$formParams['rrule_yearly_bymonth1']        = ( $objRequest->getParam('rrule_yearly_bymonth1') )                    ? $objRequest->getParam('rrule_yearly_bymonth1')        : $objNowDate->get(Zend_Date::MONTH_SHORT); //cur month
    $formParams['rrule_yearly_bymonthday1']     = ( $objRequest->getParam('rrule_yearly_bymonthday1') )                 ? $objRequest->getParam('rrule_yearly_bymonthday1')     : $objDefaultStartDate->get(Zend_Date::DAY_SHORT);
    $formParams['rrule_yearly_bymonth1']        = ( $objRequest->getParam('rrule_yearly_bymonth1') )                    ? $objRequest->getParam('rrule_yearly_bymonth1')        : $objDefaultStartDate->get(Zend_Date::MONTH_SHORT); //cur month
    $formParams['rrule_yearly_setpos2']         = ( $objRequest->getParam('rrule_yearly_setpos2') )                     ? $objRequest->getParam('rrule_yearly_setpos2')         : 1;
    //$formParams['rrule_yearly_byday2']          = ( $objRequest->getParam('rrule_yearly_byday2') )                      ? $objRequest->getParam('rrule_yearly_byday2')          : Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($objNowDate->get(Zend_Date::WEEKDAY_DIGIT)); // cur weekday
    //$formParams['rrule_yearly_bymonth2']        = ( $objRequest->getParam('rrule_yearly_bymonth2') )                    ? $objRequest->getParam('rrule_yearly_bymonth2')        : $objNowDate->get(Zend_Date::MONTH_SHORT); // cur month
    $formParams['rrule_yearly_byday2']          = ( $objRequest->getParam('rrule_yearly_byday2') )                      ? $objRequest->getParam('rrule_yearly_byday2')          : Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($objDefaultStartDate->get(Zend_Date::WEEKDAY_DIGIT)); // cur weekday
    $formParams['rrule_yearly_bymonth2']        = ( $objRequest->getParam('rrule_yearly_bymonth2') )                    ? $objRequest->getParam('rrule_yearly_bymonth2')        : $objDefaultStartDate->get(Zend_Date::MONTH_SHORT); // cur month

    $formParams['rrule_until_option']           = ( $objRequest->getParam('rrule_until_option') )                       ? $objRequest->getParam('rrule_until_option')           : 1;
    $formParams['rrule_until_count']            = ( $objRequest->getParam('rrule_until_count') )                        ? $objRequest->getParam('rrule_until_count')            : 10;

    $formParams['rrule_until_date']             = ( $objRequest->getParam('rrule_until_date_obj') )                     ? $objRequest->getParam('rrule_until_date_obj')         : $objDefaultStartDate;
    $formParams['rrule_until_date_calSelected'] = $formParams['rrule_until_date']->toString('MM/dd/yyyy');
    $formParams['rrule_until_date_calPagedate'] = $formParams['rrule_until_date']->toString('MM/yyyy');

    /**
    * @desc
    */
    $formParams['event_tags']                   = ( $objRequest->getParam('event_tags') )                               ? $objRequest->getParam('event_tags')                   : '';

    $formParams['event_event_type_1']           = ( $objRequest->getParam('event_event_type_1') )                       ? $objRequest->getParam('event_event_type_1')           : 0;
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
    $formParams['is_anybody_join']              = ( null !== $objRequest->getParam('is_anybody_join', null) )           ? $objRequest->getParam('is_anybody_join')              : $objEvent->getInvite()->getIsAnybodyJoin();  
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
    $this->view->objCopyEvent = $objCopyEvent;
    $this->view->viewMode = $viewMode;
    $this->view->bodyContent = 'groups/calendar/action.event.edit.tpl';


