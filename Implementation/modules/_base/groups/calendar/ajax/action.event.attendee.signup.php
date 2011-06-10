<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.attendee.signup.php.xml");

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
     * if don't allowed to join anyone who hasn't attendee
     */
    if ( !$objEvent->getInvite()->getIsAnybodyJoin() ) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.list.view'));
        return $objResponse;
    }

    /**
     * Check attendee for registered user
     */
    if ( $this->_page->_user->getId() ) {
        //$lstAttendee = new Warecorp_ICal_Attendee_List($objEvent);
        $lstAttendee = $objEvent->getAttendee();
        if ( !$objAttendee = $lstAttendee->findAttendee($this->_page->_user) ) {
            $objAttendee = new Warecorp_ICal_Attendee();
            //$objAttendee->setEventId($objEvent->getId());
            $objAttendee->setEventId($lstAttendee->getEventId());
            $objAttendee->setOwnerType('user');
            $objAttendee->setOwnerId($this->_page->_user->getId());
            $objAttendee->setAnswer('NONE');
            $objAttendee->setAnswerText('');
            $objAttendee->save();

            /**
             * save user name into invitation to field
             * it needs for editing event and its invitation
             */
            $objInvite = $objEvent->getInvite();

            /**
             * @see issue #10184
             */
            $recipients = Warecorp_ICal_Invitation::prepareRecipientsFromString($this->_page->_user, $this->_page->_user->getLogin());
            $objInvite->mergeRecipients( $recipients );
        }
        $objResponse = $this->calendarEventAttendeeAction($id, $uid, $view);
        return $objResponse;
    }

    /**
     * if application knowns already user that wants to rsvp
     * (there are access code for this event detected from any last request)
     * just show rsvp dialog
     */
    if ( !empty($_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']) ) {
        /**
         * Validate attendee object
         */
        $objAttendee = $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_'];
        $objAttendee = new Warecorp_ICal_Attendee($objAttendee->getId());
        if ( $objAttendee->getId() ) {
            $objResponse = $this->calendarEventAttendeeAction($id, $uid, $view);
            return $objResponse;
        }
    }

    /**
     * access code hasn't been found BUT
     * if user has active FB session
     * 1) check if there is account with some FB session - ?
     * 2) check if atendee exists for this FB session
     * 3) if don't exist, create attendee for FB session
     */
    if ( FACEBOOK_USED ) {
        if ( $facebookId = Warecorp_Facebook_Api::getFacebookId() ) {
            /**
             * There is user that linked to active facebook session
             * TODO :
             */
            $facebookUser = new Warecorp_Facebook_User($facebookId);
            if ( $facebookUser->getId() ) {
                $objUser = new Warecorp_User('id', $facebookUser->getUserId());
                $objUser->authenticate();

                $this->_page->_user =& $objUser;
                $this->view->user = $objUser;
                Zend_Registry::set("User", $objUser);

                //$lstAttendee = new Warecorp_ICal_Attendee_List($objEvent);
                $lstAttendee = $objEvent->getAttendee();
                if ( !$objAttendee = $lstAttendee->findAttendee($this->_page->_user) ) {
                    $objAttendee = new Warecorp_ICal_Attendee();
                    //$objAttendee->setEventId($objEvent->getId());
                    $objAttendee->setEventId($lstAttendee->getEventId());
                    $objAttendee->setOwnerType('user');
                    $objAttendee->setOwnerId($this->_page->_user->getId());
                    $objAttendee->setAnswer('NONE');
                    $objAttendee->setAnswerText('');
                    $objAttendee->save();

                    /**
                     * save user name into invitation to field
                     * it needs for editing event and its invitation
                     */
                    $objInvite = $objEvent->getInvite();

                    /**
                     * @see issue #10184
                     */
                    $recipients = Warecorp_ICal_Invitation::prepareRecipientsFromString($this->_page->_user, $this->_page->_user->getLogin());
                    $objInvite->mergeRecipients( $recipients );
                }
                $objResponse = $this->calendarEventAttendeeAction($id, $uid, $view);
                return $objResponse;
            }

            //$lstAttendee = new Warecorp_ICal_Attendee_List($objEvent);
            $lstAttendee = $objEvent->getAttendee();
            if ( !$objAttendee = $lstAttendee->findObjectsAttendee('fbuser', $facebookId) ) {
                $objAttendee = new Warecorp_ICal_Attendee();
                //$objAttendee->setEventId($objEvent->getId());
                $objAttendee->setEventId($lstAttendee->getEventId());
                $objAttendee->setOwnerType('fbuser');
                $objAttendee->setOwnerId($facebookId);
                $objAttendee->setAnswer('NONE');
                $objAttendee->setAnswerText('');
                $objAttendee->save();
            }
            $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_']       = $objAttendee;
            $_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']    = $objAttendee->getAccessCode();
            $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_']    = $objAttendee->getOwnerType();

            $objResponse = $this->calendarEventAttendeeAction($id, $uid, $view);
            return $objResponse;
        }
    }

    /**
     * attendee for usregistered user by email
     */
    if ( !$handle ) {
        /**
         * if user clicks on Sign In it must redirect he/she to event page after login
         */
        if ( $view == 'view' ) $_SESSION['login_return_page'] = $objEvent->entityURL();
        elseif ( $view == 'list' ) $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('calendar.list.view');
        elseif ( $view == 'index_view' ) $_SESSION['login_return_page'] = BASE_URL.'/'.LOCALE.'/index/event';

        $this->view->objEvent = $objEvent;
        $this->view->event_id = $id;
        $this->view->uid = $uid;
        $this->view->form = $form;
        $this->view->view = $view;
        $this->view->handle = array('register' => 1);
        $Content = $this->view->getContents('groups/calendar/ajax/action.event.attendee.signup.tpl');

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("RSVP"));
        $popup_window->content($Content);
        $popup_window->width(500)->height(350)->open($objResponse);
        //$objResponse->addScript("$(function(){ FB.XFBML.Host.parseDomElement(document.getElementById('fbConnectButtonPlaceholder')); })");
    } else {
        $form->addRule('email',        'required',      Warecorp::t('Please enter Email Address'));
        $form->addRule('email',        'email',         Warecorp::t('Please enter correct Email Address'));
        $form->addRule('email',        'maxlength',     Warecorp::t('Email address is too long (max %s)', 255), array('max' => 255));
        $form->addRule('firstName',    'required',      Warecorp::t('Please enter First Name'));
        $form->addRule('lastName',     'required',      Warecorp::t('Please enter Last Name'));

        $_REQUEST['_wf__rsvp_event_form'] = 1;
        if ( $form->validate($handle) ) {
            $objUser = new Warecorp_User('email', $handle['email']);
            /**
             * Aplication user has been recognized by email
             * Show popup message to offer to restore password
             */
            if ( $objUser->getId() !== null ) {
                /**
                 * if user clicks on Sign In it must redirect he/she to event page after login
                 */
                if ( $view == 'view' ) $_SESSION['login_return_page'] = $objEvent->entityURL();
                elseif ( $view == 'list' ) $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('calendar.list.view');
                elseif ( $view == 'index_view' ) $_SESSION['login_return_page'] = BASE_URL.'/'.LOCALE.'/index/event';

                $this->view->mode = 'user';
                $Content = $this->view->getContents('groups/calendar/ajax/action.event.attendee.restorepassword.tpl');
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->title(Warecorp::t("RSVP"));
                $popup_window->content($Content);
                $popup_window->width(500)->height(350)->open($objResponse);
            }
            /**
             * An email that doesn't belong to any app user is entered
             * Check if attendee with entered email exists already
             * 1) if yes - update this atendee
             * 2) create new attendee for event and save vote
             */
            else {
                //$lstAttendee = new Warecorp_ICal_Attendee_List($objEvent);
                $lstAttendee = $objEvent->getAttendee();
                $objAttendee = $lstAttendee->findAttendeeByEmail($handle['email']);
                if ( null !== $objAttendee ) {
                } else {
                    $objAttendee = new Warecorp_ICal_Attendee();
                    //$objAttendee->setEventId($objEvent->getId());
                    $objAttendee->setEventId($lstAttendee->getEventId());
                    $objAttendee->setOwnerType(Warecorp_ICal_Enum_OwnerType::USER);
                    $objAttendee->setEmail($handle['email']);
                    $objAttendee->setAnswer('NONE');
                    $objAttendee->setAnswerText('');
                    $objAttendee->setName($handle['firstName'] . ' ' . $handle['lastName']);
                    $objAttendee->save();

                    /**
                     * save new e-mail addres into invitation to field
                     * it needs for editing event and its invitation
                     */
                    $objInvite = $objEvent->getInvite();

                    /**
                     * @see issue #10184
                     */
                    $recipients = Warecorp_ICal_Invitation::prepareRecipientsFromString($this->_page->_user, $handle['email']);
                    $objInvite->mergeRecipients( $recipients );
                }
                $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_']       = $objAttendee;
                $_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']    = $objAttendee->getAccessCode();
                $_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_']    = $objAttendee->getOwnerType();

                /**
                 * if user choosed 'I want to create account' set flag to then redirect to registration process
                 */
                if ( !empty($handle['register']) ) $_SESSION['register_user_after_rsvp'] = $handle['email'];
                else unset($_SESSION['register_user_after_rsvp']);

                /**
                 * Use the action method to display attandee dialog
                 */
                $objResponse = $this->calendarEventAttendeeAction($id, $uid, $view);
            }
        } else {
            $this->view->objEvent = $objEvent;
            $this->view->event_id = $id;
            $this->view->uid = $uid;
            $this->view->form = $form;
            $this->view->view = $view;
            $this->view->handle = $handle;
            $Content = $this->view->getContents('groups/calendar/ajax/action.event.attendee.signup.tpl');

            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t("RSVP"));
            $popup_window->content($Content);
            $popup_window->width(500)->height(350)->reload($objResponse);

        }
    }
