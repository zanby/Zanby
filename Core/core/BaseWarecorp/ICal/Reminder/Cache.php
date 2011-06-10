<?php
/**
*   Zanby Enterprise Group Family System
*
*    Copyright (C) 2005-2011 Zanby LLC. (http://www.zanby.com)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*    To contact Zanby LLC, send email to info@zanby.com.  Our mailing
*    address is:
*
*            Zanby LLC
*            3611 Farmington Road
*            Minnetonka, MN 55305
*
* @category   Zanby
* @package    Zanby
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/

class BaseWarecorp_ICal_Reminder_Cache
{
    private $DbConn;
    private $date;
    private $objUTCDateNow;
    private $timezones;
    private $maxSupportDays = 20;
    private $maxTinyUrlsInArray = 100;
        
    /**
    * @param strinf ISO_8601 date UTC
    */
    public function setDate($newValue)
    {
        if ( !is_string($newValue) ) throw new Warecorp_ICal_Exception('Date must be a string in ISO_8601 format');
        $this->date = $newValue;
        return $this;
    }
    
    public function getDate()
    {
        if ( null === $this->date ) throw new Warecorp_ICal_Exception('Date is not set');
        return $this->date;
    }
    
    public function __construct()
    {
        $this->DbConn = Zend_Registry::get('DB');
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $this->objUTCDateNow = new Zend_Date();
        //FIXME убрать это, это только для тестирования 
        //$this->objUTCDateNow->subDay(5);
        date_default_timezone_set($defaultTimezone);
        
        $lstTimezones = new Warecorp_ICal_Timezone_List();
        $lstTimezones->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS);
        $lstTimezones->setPairsModeKey('id');
        $lstTimezones->setPairsModeValue('tz_name');
        $this->timezones = $lstTimezones->getList();
    }
    
    /**
    * @todo !!!!!!!!!!
    * При построении по событию будут учтены все исключения на дату (recurrenceId IS NOT NULL)
    * и НЕ будут учтены все исключения на будующие даты (RefID IS NOT NULL) - поэтому для них надо строить отдельно
    * или придумать, чтобы они автоматически брались
    */
    public function build($lstEvents, $isCronScript = false)
    {        
        if ( $lstEvents instanceof Warecorp_ICal_Event ) $lstEvents = array($lstEvents);
        elseif ( !is_array($lstEvents) ) throw new Warecorp_ICal_Exception('Incorrect events list format');
        elseif ( sizeof($lstEvents) == 0 ) return;

        $objDateToCache = clone $this->objUTCDateNow;
        //FIXME Надо определить, как часто должен запускаться скрипт
        $objDateToCache->sub(1, Zend_Date::DAY);       
        
        foreach ( $lstEvents as &$objEvent ) {
            if ( $objEvent instanceof Warecorp_ICal_Event ) {
                $lastCached = $this->getLastCacheDate($objEvent);
                if (null !== $lastCached ) {
                    $defaultTimezone = date_default_timezone_get();
                    date_default_timezone_set('UTC');
                    $objLastCache = new Zend_Date($lastCached, Zend_Date::ISO_8601);
                    date_default_timezone_set($defaultTimezone); 
                    if ( $objLastCache->isEarlier($objDateToCache) ) {
                        $resurrences = Warecorp_ICal_Event_List_Standard::getRefsListByRootId($objEvent->getId());
                        if ( sizeof($resurrences) != 0 ) $_lstEvents = array_merge(array($objEvent),$resurrences);
                        else $_lstEvents = $objEvent;                      
                        $this->_buildEvent($_lstEvents, $isCronScript);
                    }
                } else {
                    $resurrences = Warecorp_ICal_Event_List_Standard::getRefsListByRootId($objEvent->getId());
                    if ( sizeof($resurrences) != 0 ) $_lstEvents = array_merge(array($objEvent),$resurrences);
                    else $_lstEvents = $objEvent;    
                    $this->_buildEvent($_lstEvents, $isCronScript);
                }
            }            
        }
    }
    
    private function _buildEvent($objEvent, $isCronScript)
    {
        
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        
        $objEventList = new Warecorp_ICal_Event_List();
        if ( $isCronScript ) {
            $periodStartDate = clone $this->objUTCDateNow;
            $periodStartDate->setDay(1);
            $periodStartDate->setHour(0);        
            $periodStartDate->setMinute(0);
            $periodStartDate->setSecond(0);
            $periodStartEnd = clone $periodStartDate;        
            $periodStartEnd->add(1, Zend_Date::MONTH);
            // тест, чтобы показывались скрытые события
            $periodStartDate->sub(7, Zend_Date::DAY);
            $periodStartEnd->add(7, Zend_Date::DAY);
        } else {
            $objEventList->setUseCache(false);
            $periodStartDate = clone $this->objUTCDateNow;
            $periodStartDate->setHour(0);        
            $periodStartDate->setMinute(0);
            $periodStartDate->setSecond(0);
            $periodStartEnd = clone $periodStartDate;        
            $periodStartEnd->add($this->maxSupportDays, Zend_Date::DAY);
        }
        
        $objEventList->setTimeZone('UTC');
        $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
        $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));        
        $dates = $objEventList->buildRecurList($objEvent);
        //print_r($dates);exit;
        if ( sizeof($dates) != 0 ) {
            foreach ( $dates as $strDate => &$times ) {
                if ( sizeof($times) != 0 ) {
                    foreach ( $times as $strTime => &$events ) {                      
                        foreach ( $events as &$event ) {
                            $objCurrEvent = new Warecorp_ICal_Event($event['id']);
                            if ( 0 != $objCurrEvent->getReminders()->getCount() ) {
                                $lstReminders = $objCurrEvent->getReminders()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
                                if ( $objCurrEvent->isAllDay() ) {
                                    $strEventDate = $strDate.'T000000';                                
                                    foreach ( $this->timezones as  $_ind => $timezone ) {
                                        date_default_timezone_set($timezone); 
                                        $objEventDate = new Zend_Date($strEventDate, Zend_Date::ISO_8601);
                                        $objEventDate->setTimezone('UTC'); 
                                        if ( !$objEventDate->isEarlier($this->objUTCDateNow) ) {
                                            foreach ( $lstReminders as &$objReminder ) {
                                                $objCurrEventDate = clone $objEventDate;
                                                $objCurrEventDate->sub($objReminder->getDuration(), Zend_Date::SECOND);
                                                // если эта дата больше чем сейчас - тогда заностить
                                                if ( !$objCurrEventDate->isEarlier($this->objUTCDateNow) ) {
                                                    $this->insert($objCurrEvent, new Zend_Date($strEventDate, Zend_Date::ISO_8601), $objCurrEventDate, $objReminder, $timezone);
                                                }
                                            }
                                        }   
                                    } 
                                    date_default_timezone_set('UTC');   
                                } 
                                else {                            
                                    $objEventDate = new Zend_Date($strDate.'T'.str_replace(":","",$strTime), Zend_Date::ISO_8601);
                                    if ( !$objEventDate->isEarlier($this->objUTCDateNow) ) {                                        
                                        foreach ( $lstReminders as &$objReminder ) {
                                            $objCurrEventDate = clone $objEventDate;
                                            $objCurrEventDate->sub($objReminder->getDuration(), Zend_Date::SECOND);
                                            // если эта дата больше чем сейчас - тогда заностить
                                            if ( !$objCurrEventDate->isEarlier($this->objUTCDateNow) ) { 
                                                $this->insert($objCurrEvent, $objEventDate, $objCurrEventDate, $objReminder);
                                            }
                                        }
                                    }  
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if ( $isCronScript ) {
            $periodStartDate = clone $periodStartEnd;
            $periodStartDate->setDay(1);
            $periodStartDate->setHour(0);        
            $periodStartDate->setMinute(0);
            $periodStartDate->setSecond(0);

            $periodStartEnd = clone $periodStartDate;        
            $periodStartEnd->add(1, Zend_Date::MONTH);
        
            // тест, чтобы показывались скрытые события
            $periodStartDate->sub(7, Zend_Date::DAY);
            $periodStartEnd->add(7, Zend_Date::DAY);

            $objEventList = new Warecorp_ICal_Event_List();
            $objEventList->setTimeZone('UTC');
            $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
            $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));        
            $dates = $objEventList->buildRecurList($objEvent);
            
            if ( sizeof($dates) != 0 ) {
                foreach ( $dates as $strDate => &$times ) {
                    if ( sizeof($times) != 0 ) {
                        foreach ( $times as $strTime => &$events ) {                      
                            foreach ( $events as &$event ) {
                                $objCurrEvent = new Warecorp_ICal_Event($event['id']);
                                if ( 0 != $objCurrEvent->getReminders()->getCount() ) {
                                    $lstReminders = $objCurrEvent->getReminders()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
                                    
                                    if ( $objCurrEvent->isAllDay() ) {
                                        $strEventDate = $strDate.'T000000';                                
                                        foreach ( $this->timezones as $_id => $timezone ) {
                                            date_default_timezone_set($timezone); 
                                            $objEventDate = new Zend_Date($strEventDate, Zend_Date::ISO_8601);
                                            $objEventDate->setTimezone('UTC'); 
                                            if ( !$objEventDate->isEarlier($this->objUTCDateNow) ) {
                                                foreach ( $lstReminders as &$objReminder ) {
                                                    $objCurrEventDate = clone $objEventDate;
                                                    $objCurrEventDate->sub($objReminder->getDuration(), Zend_Date::SECOND);
                                                    // если эта дата больше чем сейчас - тогда заностить
                                                    if ( !$objCurrEventDate->isEarlier($this->objUTCDateNow) ) { 
                                                        $this->insert($objCurrEvent, new Zend_Date($strEventDate, Zend_Date::ISO_8601), $objCurrEventDate, $objReminder, $timezone);
                                                    }
                                                }
                                            }   
                                        } 
                                        date_default_timezone_set('UTC');   
                                    } 
                                    else {                            
                                        $objEventDate = new Zend_Date($strDate.'T'.str_replace(":","",$strTime), Zend_Date::ISO_8601);
                                        if ( !$objEventDate->isEarlier($this->objUTCDateNow) ) {                                        
                                            foreach ( $lstReminders as &$objReminder ) {
                                                $objCurrEventDate = clone $objEventDate;
                                                $objCurrEventDate->sub($objReminder->getDuration(), Zend_Date::SECOND);
                                                // если эта дата больше чем сейчас - тогда заностить
                                                if ( !$objCurrEventDate->isEarlier($this->objUTCDateNow) ) { 
                                                    $this->insert($objCurrEvent, $objEventDate, $objCurrEventDate, $objReminder);
                                                }
                                            }
                                        }  
                                    }
                                    
                                }
                            }
                        }
                    }
                }
            }
        }
        date_default_timezone_set($defaultTimezone);
        
        
    }
    
    private function insert(Warecorp_ICal_Event $objEvent, Zend_Date $objEventDate,  Zend_Date $objDate, Warecorp_ICal_Reminder $objReminder, $timezone = null)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_reminder_cache', array('CNT' => new Zend_Db_Expr('COUNT(*)')));
        $query->where('cache_event_id = ?', $objEvent->getId());
        $query->where('cache_gmt_date = ?', $objDate->toString('yyyy-MM-dd HH:mm:ss'));
        if ( null === $timezone ) $query->where('cache_timezone IS NULL');
        else $query->where('cache_timezone = ?', $timezone);

        $result = $this->DbConn->fetchOne($query);
        if ( !$result ) {
            $data = array();
            $data['cache_event_id']         = $objEvent->getId();
            $data['cache_event_uid']        = $objEvent->getUid();
            $data['cache_event_root_id']    = $objEvent->getRootId();
            $data['cache_reminder_id']      = $objReminder->getId();
            $data['cache_event_gmt_date']   = $objEventDate->toString('yyyy-MM-dd HH:mm:ss');
            $data['cache_gmt_date']         = $objDate->toString('yyyy-MM-dd HH:mm:ss');
            $data['cache_created']          = $this->objUTCDateNow->toString('yyyy-MM-dd HH:mm:ss');
            $data['cache_timezone']         = ( $timezone ) ? $timezone : new Zend_Db_Expr('NULL');
            $this->DbConn->insert('calendar_event_reminder_cache', $data);
        }
    }
    
    private function getLastCacheDate(Warecorp_ICal_Event $objEvent)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_reminder_cache', array('cache_created' => new Zend_Db_Expr('MAX(cache_created)')));
        $query->where('cache_event_root_id = ?', $objEvent->getId());
        $result = $this->DbConn->fetchOne($query);
        if ( $result ) {
            return $result;    
        }
        return null;
    }
    
    public function deliver()
    {
        /* SOAP: MailSrv */   
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $objDateNow = new Zend_Date();
        date_default_timezone_set($defaultTimezone);
        
        $query = $this->DbConn->select();
        $query->from('calendar_event_reminder_cache', array('*'));
        $query->where('cache_gmt_date <= ?', $objDateNow->toString('yyyy-MM-dd HH:mm:ss'));
        $result = $this->DbConn->fetchAll($query);
        
        $where = $this->DbConn->quoteInto('cache_gmt_date <= ?', $objDateNow->toString('yyyy-MM-dd HH:mm:ss'));
        $this->DbConn->delete('calendar_event_reminder_cache', $where);
        
        $objEventList = new Warecorp_ICal_Event_List();
        $objEventList->setTimezone('UTC');

        if ( sizeof($result) != 0 ) {
            foreach ( $result as &$item ) {
                // надо находить конкретную копию события на эту дату, чтобы добавить реальную дату в письмо
                $objEvent = new Warecorp_ICal_Event($item['cache_event_uid']);
                $tmpDate = new Zend_Date($item['cache_event_gmt_date'], Zend_Date::ISO_8601); 
                $eventInfo = $objEventList->findEvent($objEvent, $item['cache_event_id'], $item['cache_event_uid'], $tmpDate->get(Zend_Date::YEAR), $tmpDate->get(Zend_Date::MONTH), $tmpDate->get(Zend_Date::DAY));

                if ( null !== $eventInfo ) {
                    $objEvent = $eventInfo['objEvent'];
                    $evDate = clone $eventInfo['date_in_event_timezone'];
                    $objEvent->setDtstart($evDate->toString('yyyy-MM-ddTHHmmss'));
                    $evDate->add($eventInfo['durationSec'], Zend_Date::SECOND);
                    $objEvent->setDtend($evDate->toString('yyyy-MM-ddTHHmmss'));
                    
                    if ( null !== $objEvent->getId() ) {
                        $objReminder = new Warecorp_ICal_Reminder($item['cache_reminder_id']);

                        /* SOAP: MailSrv */
                        if ( Warecorp::isMailServerUsed() ) {
                            if ( $objReminder->getEntireGuests() ) {
                                $attendee = $objEvent->getAttendee()->setAnswerFilter(array('NONE', 'YES', 'MAYBE'))->getList();
                                if ( sizeof($attendee) != 0 ) {
                                    foreach ( $attendee as $objAttendee ) {
                                        if ( $objAttendee->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) {
                                            if ( null !== $objAttendee->getOwnerId() ) {
                                                $objAttendeeUser = new Warecorp_User('id', $objAttendee->getOwnerId());
                                            } else {
                                                $objAttendeeUser = new Warecorp_User();
                                                $objAttendeeUser->setLogin('Guest');
                                                $objAttendeeUser->setFirstname('Guest');
                                                $objAttendeeUser->setEmail($objAttendee->getEmail());
                                                $objAttendeeUser->setTimezone( ($objEvent->getTimezone()) ? $objEvent->getTimezone() : 'Europe/London' ); // GMT
                                            }
                                            if ( null === $item['cache_timezone'] ) { 
                                                $this->addReminder($msrvRecipients, $objAttendeeUser, $objEvent, $objAttendee->getAccessCode());
                                                $pmbRecipients[] = $objAttendeeUser->getId() ? $objAttendeeUser->getId() : $objAttendeeUser->getEmail();
                                            } elseif ( $item['cache_timezone'] == $objAttendeeUser->getTimezone() ) { 
                                                $this->addReminder($msrvRecipients, $objAttendeeUser, $objEvent, $objAttendee->getAccessCode());
                                                $pmbRecipients[] = $objAttendeeUser->getId() ? $objAttendeeUser->getId() : $objAttendeeUser->getEmail(); 
                                            }                                            
                                        }
                                    }
                                }
                            } else {
                                $objCreator = $objEvent->getCreator();
                                if ( null === $item['cache_timezone'] ) { 
                                    $this->addReminder($msrvRecipients, $objCreator, $objEvent);
                                    $pmbRecipients[] = $objCreator->getId() ? $objCreator->getId() : $objCreator->getEmail(); 
                                } elseif ( $item['cache_timezone'] == $objCreator->getTimezone() ) { 
                                    $this->addReminder($msrvRecipients, $objCreator, $objEvent); 
                                    $pmbRecipients[] = $objCreator->getId() ? $objCreator->getId() : $objCreator->getEmail();
                                }
                            }                             
                            try { 
                                $this->createMailCampaign($msrvRecipients, 'REMINDER', $objEvent, $pmbRecipients); 
                                $msrvSended = true;
                            } catch ( Exception $e ) { $msrvSended = false; }
                        }
                    }
                }
            }
        }
    }
    
    private function addReminder(Warecorp_SOAP_Type_Recipients &$recipients, Warecorp_User $objRecipient, Warecorp_ICal_Event $objEvent, $accessCode = null) 
    {
        static $tinyUrls = array();
        /**
        * Set Event Timezone
        */
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
        $useEventTzFromVenue = empty($cfgSite->use_event_tz_from_venue) || (int)$cfgSite->use_event_tz_from_venue == 0;
        if ( $useEventTzFromVenue ) {
            $originalEventTimezone = $objEvent->getTimezone();
            if ( null === $originalEventTimezone || $originalEventTimezone instanceof Zend_Db_Expr ) {
                if ( $objRecipient->getTimezone() ) { $objEvent->setTimezone($objRecipient->getTimezone()); } 
                else { $objEvent->getCreator()->getTimezone(); }
            }
        }
        $eventDtstart = $useEventTzFromVenue 
            ? $objEvent->getDtstart() 
            : $objEvent->convertTZ($objEvent->getDtstart(), $objRecipient->getTimezone());
        /**
         * Get URL to event page
         */
        switch ( $objEvent->getOwnerType() ) {
            case Warecorp_ICal_Enum_OwnerType::GROUP :
                $url = $objEvent->entityURL();
                $url = $accessCode ? $url.'code' : $url;
                if ( ! isset($tinyUrls[$url]) ) { $tinyUrls[$url] = Warecorp::getTinyUrl($url, HTTP_CONTEXT); }                
                break;
            case Warecorp_ICal_Enum_OwnerType::USER :
                $url = $objEvent->entityURL();
                $url = $accessCode ? $url.'code' : $url;
                if ( ! isset($tinyUrls[$url]) ) { $tinyUrls[$url] = Warecorp::getTinyUrl($url, HTTP_CONTEXT); }
                break;
        }
        
		$recipient = new Warecorp_SOAP_Type_Recipient();
		$recipient->setEmail( $objRecipient->getEmail() );
		$recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
		$recipient->setLocale( null );
        $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
		$recipient->addParam( 'event_date', $eventDtstart->toString("yyyy-MM-dd") );
		$recipient->addParam( 'event_time', $objEvent->isAllDay() ? 'All Day' : $eventDtstart->toString("h:mm").' '.$eventDtstart->get(Zend_Date::MERIDIEM).' '.( $objEvent->isTimezoneExists() ? $eventDtstart->get(Zend_Date::TIMEZONE) : '' ) );
		$recipient->addParam( 'event_url', $accessCode ? rtrim($tinyUrls[$url], ' /').'/'.$accessCode.'/' : $tinyUrls[$url] );
		$recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
		$recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
		$recipients->addRecipient($recipient);
        
        /* return event timezone */
        if ( $useEventTzFromVenue ) { $objEvent->setTimezone($originalEventTimezone); }
        if ( sizeof($tinyUrls) > $this->maxTinyUrlsInArray ) { $tinyUrls = array(); }
    }
	
    private function sendReminder(Warecorp_User $objRecipient, Warecorp_ICal_Event $objEvent)
    {
        /** @var array of TinyUrls: array('fullUrl' => 'tinyUrl') **/
        static $tinyUrls = array();

        $originalEventTimezone = $objEvent->getTimezone();
        if ( null === $originalEventTimezone || $originalEventTimezone instanceof Zend_Db_Expr ) {
            $objEvent->setTimezone($objRecipient->getTimezone());
        }

        //  Send message
        $mail = new Warecorp_Mail_Template('template_key', 'CALENDAR_REMINDER_NOTIFICATION');
        $mail->setHttpContext($objEvent->getHttpContext());
        $mail->setSender($objEvent->getCreator());
        $mail->addRecipient($objRecipient);
        $mail->addParam('objEvent', $objEvent);
        switch ( $objEvent->getOwnerType() ) {
            case Warecorp_ICal_Enum_OwnerType::GROUP :
                $url = $objEvent->entityURL();
                if ( ! isset($tinyUrls[$url]) ) {
                    $tinyUrls[$url] = Warecorp::getTinyUrl($url, $objEvent->getHttpContext());
                }
                $mail->addParam('EventPageURL', $tinyUrls[$url]);
                break;
            case Warecorp_ICal_Enum_OwnerType::USER :
                $url = $objEvent->entityURL();
                if ( ! isset($tinyUrls[$url]) ) {
                    $tinyUrls[$url] = Warecorp::getTinyUrl($url, $objEvent->getHttpContext());
                }
                $mail->addParam('EventPageURL', $tinyUrls[$url]);
                break;
        }
        if ( sizeof($tinyUrls) > $this->maxTinyUrlsInArray ) {
            $tinyUrls = array();
        }

        //$mail->sendToPMB ( true ) ;
        $mail->sendToEmail (true) ;
        $mail->send();
        
        //  Send message end
        $objEvent->setTimezone($originalEventTimezone);
    }
    
    /**
     * +----------------------------------------------------------------------
     * |
     * |    MAIL SRV CAMPAIGNS
     * |
     * +---------------------------------------------------------------------- 
     */
    
    protected function createMailCampaign(Warecorp_SOAP_Type_Recipients $recipients, $campaign, Warecorp_ICal_Event $objEvent, $pmbRecipients = array(), $addParams = array())
    {
        /* SOAP: MailSrv */       
        try { $client = Warecorp::getMailServerClient(); }
        catch ( Exception $e ) { $client = null; }   

        if ( $client && sizeof($recipients->getRecipients()) != 0 ) {
            switch ( $campaign ) {
                case 'REMINDER' :
                    /* email to invited users */
                    try {
                        $campaignUID = $client->createCampaign();                        
                        if ( $objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) $objSender = $objEvent->getOwner();
                        else $objSender = $objEvent->getCreator();
                        $request = $client->setSender($campaignUID, $objSender->getEmail(), $objSender->getFirstname().' '.$objSender->getLastname());
                        $request = $client->setTemplate($campaignUID, 'CALENDAR_REMINDER_NOTIFICATION', HTTP_CONTEXT); /* CALENDAR_REMINDER_NOTIFICATION */
						
                        /* add callback to mailsrv campaign to sent PMB message */
                        $objCallback = new Warecorp_SOAP_Type_Callback();
                        $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                        $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                        $objCallback->setAction( 'callbackAddPMBMessage' );
                        $callbackUID = $client->addCallback($campaignUID, $objCallback);
            
                        $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                        $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                        $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                        $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                        $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                        $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                        unset( $pmbRecipients ); 
            
						/* add params */
						$params = new Warecorp_SOAP_Type_Params();
                        /* Params customization depend on implementation */
                        if (Warecorp::checkHttpContext('zccf')) {
                            /*if ( $this->getSubject() ) {    // If event creator set own subject messge, set it as Email subject
                                $client->setSubject($campaignUID, $this->getSubject());
                            }*/
                            $params->addParam( 'event_sender_login',
                                ( $objSender->getFirstname() && $objSender->getLastname() ) ?
                                    $objSender->getFirstname() .' ' . $objSender->getLastname() :
                                ( $objSender->getLogin() ) ?
                                    $objSender->getLogin() :
                                    'member'
                            );
                        }else{
                            $params->addParam( 'event_sender_login', $objSender->getLogin() ? $objSender->getLogin() : 'member' );
                        }
                        //$params->addParam( 'event_sender_login', $objSender->getLogin() ? $objSender->getLogin() : 'member' );
						$params->loadDefaultCampaignParams();
						$params->addParam( 'event_title', $objEvent->getTitle() );
						$params->addParam( 'event_owner_login', $objEvent->getOwnerType() == "user" ? $objEvent->getOwner()->getLogin() : $objEvent->getCreator()->getLogin() );
						$params->addParam( 'event_owner_full_name', $objEvent->getOwnerType() == "user" ? $objEvent->getOwner()->getFirstname().' '.$objEvent->getOwner()->getLastname() : $objEvent->getCreator()->getFirstname().' '.$objEvent->getCreator()->getLastname() );
						$params->addParam( 'event_venue', $objEvent->getEventVenue() ? $objEvent->getEventVenue()->getName() : '' );
						$params->addParam( 'event_description', $objEvent->getDescription() );
                        if ( sizeof($addParams) != 0 ) foreach ( $addParams as $key => $value ) $params->addParam( $key, $value );
						$request = $client->addParams($campaignUID, $params);
						
                        $request = $client->addRecipients($campaignUID, $recipients);
                        $request = $client->startCampaign($campaignUID);
                    } catch ( Exception $e ) { throw $e; }                    
                    break;
            }
        }        
    }
}
