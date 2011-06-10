<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.attendee.php.xml");

    $objResponse = new xajaxResponse();
    $form = new Warecorp_Form('rsvp_event_form');

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
    /**
    * Check Access By Code
    */
    if ( null === $this->_page->_user->getId() ) {
        /**
         * if user is anonymous and there isn't access code redirect it to login page
         */
        if ( empty($_SESSION['_RSVP_'][$objEvent->getId()]['_access_code_']) ) {
            $_SESSION['login_return_page'] = $this->currentUser->getUserPath('calendar.month.view');
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

        if ( !isset($params) || !is_array($params) || !sizeof($params) ) {
            $objResponse->addScript("params = new Array('garbage');");
        }

        $this->view->objEvent = $objEvent;
        $this->view->event_id = $id;
        $this->view->uid = $uid;
        $this->view->form = $form;
        $this->view->view = $view;
        $this->view->date = ( $date ) ? $date : 0;
        $this->view->userAttendee = $objAttendee;
        $Content = $this->view->getContents('users/calendar/ajax/action.event.attendee.tpl');

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("RSVP"));
        $popup_window->content($Content);
        $popup_window->width(500)->height(350)->open($objResponse);

    }
    else {
        if ( !isset($handle['attending_rsvp_way']) ) $handle['attending_rsvp_way'] = 'NONE';
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

            // if user unregistered - disable sharing processing
            if ( null !== $this->_page->_user->getId() ) {
                //  Share Event to user calendar
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

        if ( isset($params) && is_array($params) && sizeof($params) ) {

            //  only calendarsearch page we needed
            $isSearchEventPage = false;
            foreach ( $params as $k => $v ) {
                if ( stripos ($k, "calendarsearch") ) {
                    $isSearchEventPage = true;
                    break;
                }
            }

            if ( !$isSearchEventPage ) $objResponse->addScript('document.location.href = document.location.href;');
            else {
                $order     = ( isset($params['order']) && strlen($params['order']) ) ? "order/".$params['order']."/" : "";
                $derection = ( isset($params['direction']) && strlen($params['direction']) ) ? "direction/".$params['direction']."/" : "";
                $keywords  = ( isset($params['keywords']) && strlen($params['keywords']) ) ? "keywords/".$params['keywords']."/" : "";
                $where     = ( isset($params['where']) && strlen($params['where']) ) ? "where/".$params['where']."/" : "";
                $when      = ( isset($params['when']) && strlen($params['when']) ) ? "when/".$params['when']."/" : "";
                $page      = ( isset($params['page']) && strlen($params['page']) ) ? "page/".$params['page']."/" : "";

                $objResponse->addRedirect($this->_page->_user->getUserPath("calendarsearch").$order.$derection.$page.$keywords.$where.$when);
            }
        }
        else {
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
    }
