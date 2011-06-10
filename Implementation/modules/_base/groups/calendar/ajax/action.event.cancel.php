<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.attendee.view.cancel.php.xml');
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
    $objEvent = new Warecorp_ICal_Event($id);
    if ( null === $objEvent->getId() ) {
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

    if ( !Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentGroup, $this->_page->_user ) ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, you can not manage this event');
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));
        return $objResponse;
    }

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
    date_default_timezone_set($defaultTimeZone);



    if ( !$handle ) {

        $popup_window = Warecorp_View_PopupWindow::getInstance();

        if ( $mode == 'ROW' ) {
            $popup_window->title(Warecorp::t("Cancel event"));
        } elseif ( $mode == 'COPY' ) {
            $popup_window->title(Warecorp::t("Cancel event for this date"));
        } elseif ( $mode == 'FUTURE' ) {
            $popup_window->title(Warecorp::t("Cancel event for all future dates"));
        }


        $this->view->mode = $mode;
        $this->view->event_id = $id;
        $this->view->uid = $uid;
        $this->view->month = $month;
        $this->view->day = $day;
        $this->view->year = $year;
        $this->view->view = $view;

        $Content = $this->view->getContents('groups/calendar/ajax/action.event.cancel.tpl');

        $popup_window->content($Content);
        $popup_window->width(400)->height(100)->open($objResponse);

    }
    else {
        if ( $mode == 'ROW' ) {
            /*
            $objRef = new Warecorp_ICal_Event_List_Reference($objEvent);
            $rootId = $objRef->getRootId();
            $objOriginalEvent = clone $objEvent;
            $objEvent = new Warecorp_ICal_Event($rootId);
            */

            $objOriginalEvent = clone $objEvent;
            $objEvent = $objEvent->getRootEvent();
            $objEvent->delete();
            $objEvent->clearCache();
            /**
            * Т.к удаляется все событие - не требуется создавать кешь ремайдеров
            */
        }
        elseif ( $mode == 'COPY' || $mode == 'FUTURE' ) {
            $objEventList = new Warecorp_ICal_Event_List();
            $objEventList->setTimezone( $currentTimezone );
            $eventInfo = $objEventList->findEvent($objEvent, $id, $uid, $year, $month, $day);

            if ( null === $eventInfo ) {
                $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
                $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
                $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
                $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));
                return $objResponse;
            }

            $objCopyEvent           = $eventInfo['objEvent'];
            $objDefaultStartDate    = $eventInfo['date_in_event_timezone'];
            $durationSec            = $eventInfo['durationSec'];

            /**
            * +-------------------------------------------------------------------
            * | текущая копия события является ранее созданным исключением
            * +-------------------------------------------------------------------
            */
            if ( $eventInfo['isException'] ) {
                if ( $mode == 'COPY' ) {
                    /**
                    * удаляем исключение для события
                    */
                    $RecurrenceId = $objCopyEvent->getRecurrenceId();
                    $objCopyEvent->delete();
                    /**
                    * добавляем ex дату для события
                    */
                    $objEvent->getExDates()->addExDate($RecurrenceId, 'THIS') ;
                } elseif ( $mode == 'FUTURE' ) {
                    /**
                    * удаляем исключение для события
                    */
                    $RecurrenceId = $objCopyEvent->getRecurrenceId();
                    $objCopyEvent->delete();
                    /**
                    * добавляем ex дату для события
                    */
                    $objEvent->getExDates()->addExDate($RecurrenceId, 'THIS') ;
                    $objEvent->getExDates()->addExDate($eventInfo['date_in_event_timezone']->toString('yyyy-MM-ddTHHmmss'), 'THISANDFUTURE') ;

                }
            }
            /**
            * +-------------------------------------------------------------------
            * | текущая копия события не является ранее созданным исключением
            * | создаем новое исключение для события
            * +-------------------------------------------------------------------
            */
            else {
                /**
                * добавляем ex дату для события
                */
				/*
                $type = ( $mode == 'COPY' ) ? 'THIS' : 'THISANDFUTURE';
                $objEvent->getExDates()->addExDate($eventInfo['date_in_event_timezone']->toString('yyyy-MM-ddTHHmmss'), $type);
				*/
				if ( $mode == 'COPY' ) {
					$objEvent->getExDates()->addExDate($eventInfo['date_in_event_timezone']->toString('yyyy-MM-ddTHHmmss'), 'THIS');
				} else {
					if ( $objRrule = $objEvent->getRrule() ) $objRrule->updateUntilDate($eventInfo['date_in_event_timezone']->toString('yyyy-MM-ddTHHmmss'));
				}
            }

            $objEvent->getRootEvent()->clearCache();

            /**
            * Build Reminders Cache :
            */
            $cache = new Warecorp_ICal_Reminder_Cache();
            $cache->build($objEvent->getRootEvent());
        }
        $objResponse->addScript('popup_window.close();');
        if ( $view == 'month' ) $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.month.view'));
        elseif ( $view == 'active' ) $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.list.view'));
        else $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.list.view/mode/expired'));
    }
