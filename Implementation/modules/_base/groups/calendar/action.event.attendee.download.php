<?php
Warecorp::addTranslation('/modules/groups/calendar/action.event.attendee.download.php.xml');

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
if ( false == Warecorp_ICal_AccessManager_Factory::create()->canViewEvent($objEvent, $objEvent->getOwner(), $this->_page->_user) ) {
    $this->view->errorMessage = Warecorp::t('Sorry, you can not view this event');
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

if ( $viewMode == 'ROW' ) {
    $objRef = new Warecorp_ICal_Event_List_Reference($objEvent);
    $rootId = $objRef->getRootId();
    $objOriginalEvent = clone $objEvent;
    $objEvent = new Warecorp_ICal_Event($rootId);

    /**
     *  если для события не было указана таймзона, считаем, что это текущая
     */
    if ( null === $objEvent->getTimezone() ) $objEvent->setTimezone($currentTimezone);

    $defaultTimeZone = date_default_timezone_get();
    date_default_timezone_set( $objEvent->getTimezone() );

    $objNowDate = new Zend_Date();
    $lstEventsObj = new Warecorp_ICal_Event_List();
    $lstEventsObj->setTimezone($objEvent->getTimezone());
    $strFirstDate = $lstEventsObj->findFirstEventDate($objEvent, $objNowDate);

    if ( null !== $strFirstDate ) {
        $oFirstDate = new Zend_Date($strFirstDate, Zend_Date::ISO_8601);
        $oFirstDate->addSecond($objEvent->getDurationSec());
        $objEvent->setDtstart($strFirstDate);
        $objEvent->setDtend($oFirstDate->toString('yyyy-MM-ddTHHmmss'));
    }

    $objEventDtstart = clone $objEvent->getDtstart();
    $objEventDtend = clone $objEvent->getDtend();
    date_default_timezone_set($defaultTimeZone);

    $dtStart = $objEvent->convertTZ($objEvent->getDtstart(), $currentTimezone);
    $this->_redirect($this->currentGroup->getGroupPath('calendar.event.attendee.download').'id/'.$objRequest->getParam('id', null).'/uid/'.$objRequest->getParam('uid', null).'/year/'.$dtStart->toString('yyyy').'/month/'.$dtStart->toString('MM').'/day/'.$dtStart->toString('dd').'/');
} else {
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
    $objEventDtstart        = $eventInfo['date_in_event_timezone'];
    $objEventDtend          = clone $objEventDtstart;
    $objEventDtend->addSecond($eventInfo['durationSec']);
    $durationSec            = $eventInfo['durationSec'];

    $objCopyEvent->setDtstart($objEventDtstart->toString('yyyy-MM-ddTHHmmss'));
    $objCopyEvent->setDtend($objEventDtend->toString('yyyy-MM-ddTHHmmss'));

    $defaultTimeZone = date_default_timezone_get();
    date_default_timezone_set( $currentTimezone );
    $objNowDate = new Zend_Date();
    date_default_timezone_set($defaultTimeZone);
    if ( $objNowDate->isLater($objEventDtstart) ) $objCopyEvent->setExpired(true);
    else $objCopyEvent->setExpired(false);
}

$allow = false;
if (Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentGroup, $this->_page->_user)) {
    $allow = true;
} else {
    $attendee = false;
    if (!empty($_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_']) && !empty($_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_'])) {
        $objAttendee = $_SESSION['_RSVP_'][$objEvent->getId()]['_attendee_'];
        if ($_SESSION['_RSVP_'][$objEvent->getId()]['_access_mode_'] == 'user') {
            $this->_page->_user->setEmail($objAttendee->getEmail());
            $attendee = (bool)$objCopyEvent->getAttendee()->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->findAttendee($this->_page->_user);
        } else {
            $attendee = (bool)$objCopyEvent->getAttendee()->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->findObjectsAttendee($objAttendee->getOwnerType(), $objAttendee->getOwnerId());
        }
    } elseif ($this->_page->_user->getId()) {
        $attendee = (bool)$objCopyEvent->getAttendee()->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->findAttendee($this->_page->_user);
    }
    if ($objCopyEvent->getInvite()->getDisplayListToGuest() && $attendee) {
        $allow = true;
    } else {
        if ($objCopyEvent->getOwnerType() == 'user') {
            if ($objCopyEvent->getOwnerId() == $this->_page->_user->getId()) {
                $allow = true;
            }
        } else {
            if ($objCopyEvent->getCreatorId() == $this->_page->_user->getId()) {
                $allow = true;
            }
        }
    }
}
if (!$allow) {
    $this->view->errorMessage = Warecorp::t('Sorry, you can not download the list of attendee');
    $this->view->bodyContent = 'groups/calendar/action.event.error.message.tpl';
    return;
}

$attendeeList = $objCopyEvent->getAttendee()->setFetchMode('object');

$attendeeList->setAnswerFilter('YES');
$attendeeYesItems = $attendeeList->getList();
$attendeeList->setAnswerFilter('NO');
$attendeeNoItems = $attendeeList->getList();
$attendeeList->setAnswerFilter('MAYBE');
$attendeeMaybeItems = $attendeeList->getList();
$attendeeList->setAnswerFilter('NONE');
$attendeeNoneItems = $attendeeList->getList();

$attendeeItems = array_merge($attendeeYesItems, $attendeeNoItems, $attendeeMaybeItems, $attendeeNoneItems);

header('Content-type: text/x-csv');
header('Content-Disposition: attachment; filename=attending.csv;');

echo "Name,Username,Response,Comment\n";
foreach ($attendeeItems as $attendee) {
    $str = '';
    if ($attendee->getOwnerType() == 'user') {
        $owner = $attendee->getOwner();
        if ($owner->getId()) {
            $str .= '"'. str_replace('"', '""', $owner->getFirstname(). ' '. $owner->getLastname()). '",';
            $str .= '"'. str_replace('"', '""', $owner->getLogin()). '",';
        } elseif ($attendee->getName()) {
            $str .= '"'. str_replace('"', '""', $attendee->getName()). '",';
            $str .= ',';
        } else {
            $str .= '"'. str_replace('"', '""', preg_replace("/@(.*?)$/mi", "", $owner->getEmail())). '",';
            $str .= ',';
        }
        switch ($attendee->getAnswer()) {
            case 'YES':
                $str .= 'Yes,';
                break;
            case 'NO':
                $str .= 'No,';
                break;
            case 'MAYBE':
                $str .= 'Maybe,';
                break;
            case 'NONE':
                $str .= '"Have not responded",';
                break;
            default:
                $str .= ',';
        }
        $str .= '"'. str_replace('"', '""', $attendee->getAnswerText()). "\"\n";
    } elseif ($attendee->getOwnerType() == 'fbuser') {
        $str .= '"'. str_replace('"', '""', $attendee->getName()). '",,';
        switch ($attendee->getAnswer()) {
            case 'YES':
                $str .= 'Yes,';
                break;
            case 'NO':
                $str .= 'No,';
                break;
            case 'MAYBE':
                $str .= 'Maybe,';
                break;
            case 'NONE':
                $str .= 'Have not responded,';
                break;
            default:
                $str .= ',';
        }
        $str .= '"'. str_replace('"', '""', $attendee->getAnswerText()). "\"\n";
    }
    echo $str;
}
exit;
