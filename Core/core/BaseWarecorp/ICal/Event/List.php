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

/**
 * RECURRENCE-ID
 * SEQUENCE
 * RDATE
 * EXRULE
 * EXDATE
 *
 */
class BaseWarecorp_ICal_Event_List
{
	private $DbConn;
	private $timezone       = 'UTC';
	private $periodDtstart;
	private $periodDtend;
	private $useCache       = true;

    private $cahceValuesToAdd = array();            // Р·РЅР°С‡РµРЅРёСЏ РґР»СЏ СЃРѕС…СЂР°РЅРµРЅРёСЏ РЅРѕРІС‹С… Р·Р°РїРёСЃРµР№ РІ РєРµС€ ( see self::commitToCache)
    private $cahceValuesToUpdate = array();         // Р·РЅР°С‡РµРЅРёСЏ РґР»СЏ СЃРѕС…СЂР°РЅРµРЅРёСЏ РёР·РјРµРЅРµРЅРёР№ Р·Р°РїРёСЃРµР№ РІ РєРµС€ ( see self::commitToCache)
    private $cahceDateValuesToAdd = array();        // Р·РЅР°С‡РµРЅРёСЏ РґР°С‚ СЃРѕР±С‹С‚РёСЏ РґР»СЏ СЃРѕС…СЂР°РЅРµРЅРёСЏ РЅРѕРІС‹С… Р·Р°РїРёСЃРµР№ РІ РєРµС€ ( see self::commitToCache)

    protected static $eventsCache = array();          // РєРµС€ РѕР±СЉРµРєС‚РѕРІ СЃРѕР±С‹С‚РёР№, СЃРѕР·РґР°РЅРЅС‹С… С„СѓРЅРєС†РёРµР№ self::createEvent
    protected static $eventsExdateCache = array();    // РєРµС€ exdat РґР»СЏ СЃРѕР±С‹С‚РёР№
    protected static $eventsDatesCache = array();     // РєРµС€ РґР°С‚ РґР»СЏ СЃРѕР±С‹С‚РёСЏ РІ РѕРїСЂРµРґРµР»РµРЅРЅРѕР№ С‚Р°Р№РјР·РѕРЅРµ Рё РІСЂРµРјРµРЅРЅРѕРј РёРЅС‚РµСЂРІР°Р»Рµ, С…СЂР°РЅРёС‚СЊ master-array

    protected static $usedEventsIds = array();        // ids СЃРѕР±С‹С‚РёР№, РєРѕС‚РѕСЂС‹Рµ Р±С‹Р»Рё РѕР±СЂР°Р±РѕС‚Р°РЅРЅС‹ РІ СЂРµР·СѓР»СЊС‚Р°С‚Рµ РѕРґРЅРѕРіРѕ РІС‹Р·РѕРІР° С„СѓРЅРєС†РёРё buildRecurList

	function __construct($Connection = null)
	{
		$this->DbConn = Zend_Registry::get('DB');
		if ( $this->DbConn === null ) throw new Warecorp_Exception('Database connection is not set.');
	}

	/**
	 *
	 */
	public function getPeriodDtstart()
	{
		if ( null === $this->periodDtstart ) throw new Warecorp_ICal_Exception('Period start date is not set.');
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set($this->getTimezone());
		$periodDtstart = new Zend_Date($this->periodDtstart, Zend_Date::ISO_8601);
		date_default_timezone_set($defaultTimeZone);
		return $periodDtstart;
	}

	/**
	 *
	 * РІРєР»СЋС‡Р°РµС‚СЃСЏ РІ РІС‹Р±РѕСЂРєСѓ, С‚.Рµ. СЃРѕР±С‹С‚РёСЏ СЃ СЌС‚РёРј С‡РёСЃР»РѕРј Р±СѓРґСѓС‚ РїРѕРєР°Р·Р°РЅС‹
	 * @param newVal
	 */
	public function setPeriodDtstart($newVal)
	{
		$this->periodDtstart = $newVal;
	}

	/**
	 *
	 */
	public function getPeriodDtend()
	{
		if ( null === $this->periodDtend ) throw new Warecorp_ICal_Exception('Period end date is not set.');
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set($this->getTimezone());
		$periodDtend = new Zend_Date($this->periodDtend, Zend_Date::ISO_8601);
		date_default_timezone_set($defaultTimeZone);
		return $periodDtend;
	}

	/**
	 *
	 * РќР• РІРєР»СЋС‡Р°РµС‚СЃСЏ РІ РІС‹Р±РѕСЂРєСѓ, С‚.Рµ. СЃРѕР±С‹С‚РёСЏ СЃ СЌС‚РёРј С‡РёСЃР»РѕРј РќР• Р±СѓРґСѓС‚ РїРѕРєР°Р·Р°РЅС‹
	 * @param newVal
	 */
	public function setPeriodDtend($newVal)
	{
		$this->periodDtend = $newVal;
	}

	/**
	 *
	 */
	public function setTimezone($newVal)
	{
		$this->timezone = $newVal;
	}

	/**
	 *
	 */
	public function getTimezone()
	{
		return $this->timezone;
	}

	/**
	 *
	 */
	public function setUseCache($newVal)
	{
		$this->useCache = (boolean) $newVal;
	}

    /**
     * Finds an event for specified date in UTC timezone (!)
     * @param Warecorp_ICal_Event $objEvent
     * @param int $eventId
     * @param int $eventUid
     * @param int $year
     * @param int $month
     * @param int $day
     * @return Warecorp_ICal_Event
     */
    public function findEvent($objEvent, $eventId, $eventUid, $year, $month, $day)
    {
        $result = null;

        $timeCopy = clone $objEvent->getDtstart();
        $timeCopy->setTimezone('UTC');
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $objStartDate = new Zend_Date(sprintf('%04d', $year).'-'.sprintf('%02d', $month).'-'.sprintf('%02d', $day).'T000000', Zend_Date::ISO_8601);
        $objStartDate->setTime($timeCopy);
        $objStartDate->setTimezone($this->getTimezone());
        $objEndDate = clone $objStartDate;
        $objEndDate->add(1, Zend_Date::DAY);
        date_default_timezone_set($defaultTimeZone);

//        $defaultTimeZone = date_default_timezone_get();
//        date_default_timezone_set($this->getTimezone());
//
//        date_default_timezone_set($defaultTimeZone);

        /**
        * +-------------------------------------------------------------------
        * | РЎРѕР±С‹С‚РёРµ РЅРµ СЏРІР»СЏРµС‚СЃСЏ СЂР°РЅРµРµ СЃРѕР·РґР°РЅРЅС‹Рј РёСЃРєР»СЋС‡РµРЅРёРµРј
        * +-------------------------------------------------------------------
        */
        if ( $eventId == $eventUid ) {
            
            $this->setUseCache(false);
            $this->setPeriodDtstart($objStartDate->toString('yyyy-MM-ddTHHmmss'));
            $this->setPeriodDtend($objEndDate->toString('yyyy-MM-ddTHHmmss'));
            //var_dump($objStartDate->toString('yyyy-MM-ddTHHmmss'),$objEvent->getTimezone());exit;
            $arrEvents = $this->buildRecurList($objEvent);
            

            if ( sizeof($arrEvents) == 0 ) return null;

            reset($arrEvents);
            
            list($date, $val) = each($arrEvents);
            list($time, $val) = each($val);
            list($key, $arrCurrEvent) = each($val);
            if ( $time == 'allday' ) $time = '000000';
            else $time = str_replace(':', '', $time);

            /**
            * +-------------------------------------------------------------------
            * | С‚РµРєСѓС‰Р°СЏ РєРѕРїРёСЏ СЃРѕР±С‹С‚РёСЏ РЅРµ СЏРІР»СЏРµС‚СЃСЏ СЂР°РЅРµРµ СЃРѕР·РґР°РЅРЅС‹Рј РёСЃРєР»СЋС‡РµРЅРёРµРј
            * +-------------------------------------------------------------------
            */
            if ( $arrCurrEvent['id'] == $arrCurrEvent['uid'] ) {
                $objCopyEvent = $objEvent;
                $result['isException'] = false;
            }
            /**
            * +-------------------------------------------------------------------
            * | С‚РµРєСѓС‰Р°СЏ РєРѕРїРёСЏ СЃРѕР±С‹С‚РёСЏ СЏРІР»СЏРµС‚СЃСЏ СЂР°РЅРµРµ СЃРѕР·РґР°РЅРЅС‹Рј РёСЃРєР»СЋС‡РµРЅРёРµРј
            * +-------------------------------------------------------------------
            */
            else {
                $objCopyEvent = new Warecorp_ICal_Event($arrCurrEvent['id']);
                $result['isException'] = true;
            }

            $defaultTimeZone = date_default_timezone_get();
            date_default_timezone_set( $this->getTimezone() );
            $objEventTimezoneDate = new Zend_Date($date.'T'.$time, Zend_Date::ISO_8601);
            $objEventTimezoneDate->setTimezone( $objCopyEvent->getTimezone() ? $objCopyEvent->getTimezone() : $this->getTimezone() );
            date_default_timezone_set($defaultTimeZone);
            $result['date_in_event_timezone'] = $objEventTimezoneDate;
            $result['durationSec'] = $objCopyEvent->getDurationSec();
        }
        /**
        * +-------------------------------------------------------------------
        * | РЎРѕР±С‹С‚РёРµ СЏРІР»СЏРµС‚СЃСЏ СЂР°РЅРµРµ СЃРѕР·РґР°РЅРЅС‹Рј РёСЃРєР»СЋС‡РµРЅРёРµРј
        * +-------------------------------------------------------------------
        */
        else {
            $objCopyEvent = new Warecorp_ICal_Event($eventId);
            if ( null === $objCopyEvent->getId() ) return null;
            $result['isException'] = true;

            $originalEventTimezone = $objCopyEvent->getTimezone();
            if ( null === $objCopyEvent->getTimezone() ) $objCopyEvent->setTimezone($this->getTimezone());
            $objEventTimezoneDate = clone $objCopyEvent->getDtstart();
            $durationSec = $objCopyEvent->getDurationSec();
            $objCopyEvent->setTimezone($originalEventTimezone);
            $result['date_in_event_timezone'] = $objEventTimezoneDate;
            $result['durationSec'] = $durationSec;
        }

        $result['objEvent'] = $objCopyEvent;
        return $result;
    }

    /**
    * @param Warecorp_ICal_Event $objEvent
    * @param string $strDateFrom - date ISO_8601 in timezone $this->getTimezone()
    * @param string $strDateTo - date ISO_8601 in timezone $this->getTimezone()
    */
    public function findFirstEventDate(Warecorp_ICal_Event $objEvent, $strDateFrom = null, $strDateTo = null)
    {
        $returnDate = null;

        /**
         * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј С‚Р°Р№РјР·РѕРЅСѓ РґР»СЏ СЃРѕР±С‹С‚РёСЏ, РµСЃР»Рё РЅРµ Р±С‹Р»Р° СѓСЃС‚Р°РЅРѕРІР»РµРЅР° РїСЂРё СЃРѕР·РґР°РЅРёРё СЃРѕР±С‹С‚РёСЏ
         */
        $originalEventTimezone = $objEvent->getTimezone();
        if ( null === $objEvent->getTimezone() ) $objEvent->setTimezone($this->getTimezone());

        $dafaultTimezone = date_default_timezone_get();
        date_default_timezone_set($this->getTimezone());

        if ( null === $strDateFrom ) $objDateFrom = clone $objEvent->getDtstart();
        else $objDateFrom = new Zend_Date($strDateFrom, Zend_Date::ISO_8601);

        if ( null === $strDateTo ) $objDateTo = new Zend_Date('2038-01-01T000000', Zend_Date::ISO_8601);
        else $objDateTo = new Zend_Date($strDateTo, Zend_Date::ISO_8601);

        if ( null === $objEvent->getRrule() ) {           
           /**
            * FIXME РЅРµРІРµСЂРЅРѕРµ СЃСЂР°РІРЅРµРЅРёРµ РІ СЂР°Р·РЅС‹С… С‚Р°Р№РјР·РѕРЅР°С…
            */
            if ( !$objEvent->getDtend()->isEarlier($objDateFrom) && !$objEvent->getDtstart()->isLater($objDateTo) ) {
                $returnDate = clone $objEvent->getDtstart();
                $returnDate->setTimezone($this->getTimezone());
                return $returnDate->toString('yyyy-MM-ddTHHmmss');
            } else return null;
        }
        
        if ($objEvent->getRrule()->getUntil() !== null) {

        }

        $objStartDate = clone $objEvent->getDtstart();
        $objStartDate->setTimezone($this->getTimezone());
        if ( $objStartDate->isLater($objDateFrom) ) {
            if ( $objStartDate->isLater($objDateTo) ) return null;
            $objObservedDate = clone $objStartDate;
        } else {
            $objObservedDate = clone $objDateFrom;
        }

        $query = $this->DbConn->select();
        $query->from('calendar_event_cache_dates', array('MIN(date)'));
        $query->where('event_id  = ?', $objEvent->getId());
        $query->where('timezone  = ?', $this->getTimezone());
        $query->where('date     <= ?', $objDateTo->toString('yyyy-MM-dd HH:mm:ss'));   //  date - Date start of event
        $query->where('date_end >= ?', $objDateFrom->toString('yyyy-MM-dd HH:mm:ss')); //  date_end - Date end of event
        $result = $this->DbConn->fetchOne($query);
        if ( $result ) {
            preg_match("/^([0-9]{4}-[0-9]{2}-[0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/", $result, $matches);
            return $matches[1].'T'.$matches[2].$matches[3].$matches[4];
        }

        $flagFound = false;
        while( !$objObservedDate->isLater($objDateTo) && $flagFound == false ) {
            // FIXME РІРѕР·РјРѕР¶РЅС‹ РїСЂРѕР±Р»РµРјС‹ СЃ РёСЃРєР»СЋС‡РµРЅРёСЏРјРё
            if (isset($_until)) {
                if ($_until) if ($objObservedDate->isLater($_until)) return null;
            } else {
               $_until = $objEvent->getRrule()->getUntil();
               if (is_null($_until)) $_until = false;
               else {
                   $_until = new Zend_Date($_until, Zend_Date::ISO_8601);
                   if ($objObservedDate->isLater($_until)) return null;
               }
            }
            //
            $objObservedDtstart = clone $objObservedDate;
            $objObservedDtstart->setDay(1);
            $objObservedDtstart->setHour(0);
            $objObservedDtstart->setMinute(0);
            $objObservedDtstart->setSecond(0);

            $objObservedDtend = clone $objObservedDtstart;
            $objObservedDtend->add(1, Zend_Date::MONTH);

            $objObservedDtstart->sub(7, Zend_Date::DAY);
            $objObservedDtend->add(7, Zend_Date::DAY);

            $this->setPeriodDtstart($objObservedDtstart->toString('yyyy-MM-ddTHHmmss'));
            $this->setPeriodDtend($objObservedDtend->toString('yyyy-MM-ddTHHmmss'));
            $dates = $this->buildRecurList($objEvent);

            if ( sizeof($dates) != 0 ) {
                foreach ( $dates as $strDate => $date ) {
                    foreach ( $date as $time => $eventInfo ) {
                        if ( $time == 'allday' ) $tmpDate = new Zend_Date($strDate.'T000000', Zend_Date::ISO_8601);
                        else $tmpDate = new Zend_Date($strDate.'T'.str_replace(':','',$time), Zend_Date::ISO_8601);

                        if ( !$tmpDate->isEarlier($objDateFrom) ) {
                            if ( !$tmpDate->isLater($objDateTo) ) {
                                $returnDate = $tmpDate->toString('yyyy-MM-ddTHHmmss');
                                $flagFound = true;
                                break 2;
                            } else {
                                return null;
                            }
                        }
                    }
                }
            }
            $objObservedDate->add(1, Zend_Date::MONTH);
        }

        date_default_timezone_set($dafaultTimezone);
        /**
        * Р’РѕСЃСЃС‚Р°РЅР°РІР»РёРІР°РµРј РѕСЂРёРіРёРЅР°Р»СЊРЅСѓСЋ С‚Р°Р№РјР·РѕРЅСѓ РґР»СЏ СЃРѕР±С‹С‚РёСЏ
        */
        $objEvent->setTimezone($originalEventTimezone);

        return $returnDate;

    }

    /**
    * @desc
    * @param Warecorp_User $objUser
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param string $strDateFrom - date ISO_8601 in timezone $this->getTimezone(), if null - current date
    * @param string $strDateTo - date ISO_8601 in timezone $this->getTimezone()
    */
    public function findNextEventByObject(Warecorp_User $objUser, $objContext, $strDateFrom = null, $strDateTo = null)
    {
        //FIXME
        //return null;

        /**
        * Init Params
        */
        $objReturnEvent = null;

        $dafaultTimezone = date_default_timezone_get();
        date_default_timezone_set($this->getTimezone());

        if ( null === $strDateFrom ) {
            $objDateFrom = new Zend_Date();
            $strDateFrom = $objDateFrom->toString('yyyy-MM-ddTHHmmss');
        } else $objDateFrom = new Zend_Date($strDateFrom, Zend_Date::ISO_8601);

        if ( null === $strDateTo ) {
            $objDateTo = new Zend_Date('2038-01-01T000000', Zend_Date::ISO_8601);
            $strDateTo = '2038-01-01T000000';
        } else $objDateTo = new Zend_Date($strDateTo, Zend_Date::ISO_8601);

        /**
        * Make list of events
        */
        $objEvents = new Warecorp_ICal_Event_List_Standard();
        if ( $objContext instanceof Warecorp_User ) {
            $objEvents->setOwnerIdFilter($objContext->getId());
            $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);
        } elseif ( $objContext instanceof Warecorp_Group_Simple ) {
            $objEvents->setOwnerIdFilter($objContext->getId());
            $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
        } elseif ( $objContext instanceof Warecorp_Group_Family ) {
            $objEvents->setOwnerIdFilter($objContext->getId());
            $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
        }
        $objEventAccessManager = Warecorp_ICal_AccessManager_Factory::create();
        // privacy
        if ( $objEventAccessManager->canViewPublicEvents($objContext, $objUser) && $objEventAccessManager->canViewPrivateEvents($objContext, $objUser) ) {
            $objEvents->setPrivacyFilter(array(0,1));
        } elseif ( $objEventAccessManager->canViewPublicEvents($objContext, $objUser) ) {
            $objEvents->setPrivacyFilter(array(0));
        } elseif ( $objEventAccessManager->canViewPrivateEvents($objContext, $objUser) ) {
            $objEvents->setPrivacyFilter(array(1));
        } else {
            $objEvents->setPrivacyFilter(null);
        }
        // sharing
        if ( $objEventAccessManager->canViewSharedEvents($objContext, $objUser) ) {
            $objEvents->setSharingFilter(array(0,1));
        } else {
            $objEvents->setSharingFilter(array(0));
        }
        $objEvents->setShowCopyFilter(true);
        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();

        if ( sizeof($arrEvents) == 0 ) return null;


        $flagFound = false;
        $returnValues = null;
        $objObservedDate = clone $objDateFrom;

        foreach ( $arrEvents as $objCurrEvent ) {
            $date = $this->findFirstEventDate($objCurrEvent, $strDateFrom, $strDateTo);
            if ( null !== $date ) {
                if ( null === $returnValues ) {
                    $returnValues['date']       = $date;
                    $returnValues['objDate']    = new Zend_Date($date, Zend_Date::ISO_8601);
                    $returnValues['objEvent']   = $objCurrEvent;
                } else {
                    $tmpObjDate = new Zend_Date($date, Zend_Date::ISO_8601);
                    if ( $tmpObjDate->isEarlier($returnValues['objDate']) ) {
                        $returnValues['date']       = $date;
                        $returnValues['objDate']    = new Zend_Date($date, Zend_Date::ISO_8601);
                        $returnValues['objEvent']   = $objCurrEvent;
                    }
                }
            }
        }
        if ( null === $returnValues ) return null;

        $objReturnEvent = $returnValues['objEvent'];
        $objReturnEvent->setTimezone($this->getTimezone());
        $objReturnEvent->setDtstart($returnValues['date']);

        date_default_timezone_set($dafaultTimezone);
        return $objReturnEvent;
    }

    /**
     *
     * @param events
     */
    public function buildRecurList($events)
    {
        self::$usedEventsIds = array();
        $arrDates = $this->_buildRecurList($events);

        /**
        * Commit Changes to cache table
        */
        $this->commitToCache();

        /**
         * sort result by date
         */
        ksort($arrDates);
        foreach ( $arrDates as &$date ) ksort($date);

        return $arrDates;
    }

    /**
	 *
	 * @param events
	 */
	public function _buildRecurList($events)
	{
		$arrDates = array();
		if ( !is_array($events) && $events instanceof Warecorp_ICal_Event ) $events = array($events);

		if ( sizeof($events) != 0 ) {
			foreach( $events as &$event ) {
                if ( !in_array($event->getId(), self::$usedEventsIds) ) {
                    self::$usedEventsIds[] = $event->getId();

				    $arrEventDates = array();
				    /**
				     * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј С‚Р°Р№РјР·РѕРЅСѓ РґР»СЏ СЃРѕР±С‹С‚РёСЏ, РµСЃР»Рё РЅРµ Р±С‹Р»Р° СѓСЃС‚Р°РЅРѕРІР»РµРЅР° РїСЂРё СЃРѕР·РґР°РЅРёРё СЃРѕР±С‹С‚РёСЏ
				     */
                    $originalEventTimezone = $event->getTimezone();
				    if ( null === $event->getTimezone() ) $event->setTimezone($this->getTimezone());

				    /**
				     * current event is not recursive
				     */
				    if ( null === $event->getRrule() ) {
                        $objPeriodDtstart   = clone $this->getPeriodDtstart();
                        $objPeriodDtend     = clone $this->getPeriodDtend();
                        $objPeriodDtstart->setTimezone($event->getTimezone());
                        $objPeriodDtend->setTimezone($event->getTimezone());
                        
					    $objEventDate = clone $event->getDtstart();
                        if ( !$objEventDate->isEarlier($objPeriodDtstart) && !$objEventDate->isLater($objPeriodDtend) ) {
					        $this->addToDates($objEventDate, $objEventDate, $event, $arrDates, $arrEventDates);
                        }
				    }
				    /**
				     * current event is recursive
				     * check rrules for this event
				     */
				    else {
					    if ( true == $this->useCache && null !== ($arrEventDates = $this->getFromCache($event)) ) {
                            $arrEventDates = unserialize($arrEventDates);
                            $arrDates = $this->mergeMasterArrays($arrDates,$arrEventDates);
					        //$arrDates = array_merge_recursive($arrDates,$arrEventDates);
					    } else {
						    switch ( $event->getRrule()->getFreq() ) {
							    /**
							    * FREQ = DAILY
							    */
							    case Warecorp_ICal_Rrule_Enum_Freq::DAILY :
							       $this->checkDailyRecur($event, $arrDates, $arrEventDates);
							       break;
							    /**
							    * FREQ = WEEKLY
							    */
							    case Warecorp_ICal_Rrule_Enum_Freq::WEEKLY :
							       $this->checkWeeklyRecur($event, $arrDates, $arrEventDates);
							       break;
							    /**
							    * FREQ = MONTHLY
							    */
							    case Warecorp_ICal_Rrule_Enum_Freq::MONTHLY :
							       $this->checkMonthlyRecur($event, $arrDates, $arrEventDates);
							       break;
							    /**
							    * FREQ = YEARLY
							    */
							    case Warecorp_ICal_Rrule_Enum_Freq::YEARLY :
							       $this->checkYearlyRecur($event, $arrDates, $arrEventDates);
							       break;
							    default  :
							       break;
						    }
						    /**
						     *  build event excetions list  : Recurrences
						     */
						    $recurrencesList = $event->getRecurrences()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
						    if ( sizeof($recurrencesList) != 0 ) {
							    foreach ( $recurrencesList as &$recurrence ) {
                                    self::$usedEventsIds[] = $recurrence->getId();
								    /**
								     * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј С‚Р°Р№РјР·РѕРЅСѓ РґР»СЏ СЃРѕР±С‹С‚РёСЏ,
								     * РµСЃР»Рё РЅРµ Р±С‹Р»Р° СѓСЃС‚Р°РЅРѕРІР»РµРЅР° РїСЂРё СЃРѕР·РґР°РЅРёРё СЃРѕР±С‹С‚РёСЏ
								     */
								    if ( null === $recurrence->getTimezone() ) $recurrence->setTimezone($this->getTimezone());

                                    /**
                                    * recurrence РґР°С‚Р° РІ С‚Р°Р№РјР·РѕРЅРµ РѕСЃРЅРѕРІРЅРѕРіРѕ СЃРѕР±С‹С‚РёСЏ
                                    * РЅРѕРІР°СЏ РґР°С‚Р° РІ С‚Р°Р№РјР·РѕРЅРµ РёСЃРєР»СЋС‡РµРЅРёСЏ
                                    */
                                    $defaultTimeZone = date_default_timezone_get();
                                    date_default_timezone_set( $event->getTimezone() );
                                    $objRecurrDate = new Zend_Date($recurrence->getRecurrenceId(), Zend_Date::ISO_8601);
                                    $objRecurrDate->setTimezone($this->getTimezone());
                                    date_default_timezone_set( $defaultTimeZone );

                                    if ( $event->isAllDay() ) {
                                        if ( isset($arrDates[$objRecurrDate->toString('yyyy-MM-dd')]['allday']['id_'.$recurrence->getUid()]) ) {
                                            unset($arrDates[$objRecurrDate->toString('yyyy-MM-dd')]['allday']['id_'.$recurrence->getUid()]);
                                            unset($arrEventDates[$objRecurrDate->toString('yyyy-MM-dd')]['allday']['id_'.$recurrence->getUid()]);
                                            if ( sizeof($arrDates[$objRecurrDate->toString('yyyy-MM-dd')]['allday']) == 0 ) unset($arrDates[$objRecurrDate->toString('yyyy-MM-dd')]['allday']);
                                            if ( sizeof($arrEventDates[$objRecurrDate->toString('yyyy-MM-dd')]['allday']) == 0 ) unset($arrEventDates[$objRecurrDate->toString('yyyy-MM-dd')]['allday']);
                                            if ( sizeof($arrDates[$objRecurrDate->toString('yyyy-MM-dd')]) == 0 ) unset($arrDates[$objRecurrDate->toString('yyyy-MM-dd')]);
                                            if ( sizeof($arrEventDates[$objRecurrDate->toString('yyyy-MM-dd')]) == 0 ) unset($arrEventDates[$objRecurrDate->toString('yyyy-MM-dd')]);
                                            $objEventDate = clone $recurrence->getDtstart();
                                            $this->addToDates($objEventDate, $objEventDate, $recurrence, $arrDates, $arrEventDates);
                                        }
                                    } else {
                                        if ( isset($arrDates[$objRecurrDate->toString('yyyy-MM-dd')][$objRecurrDate->toString('HH:mm:ss')]['id_'.$recurrence->getUid()]) ) {
                                            unset($arrDates[$objRecurrDate->toString('yyyy-MM-dd')][$objRecurrDate->toString('HH:mm:ss')]['id_'.$recurrence->getUid()]);
                                            unset($arrEventDates[$objRecurrDate->toString('yyyy-MM-dd')][$objRecurrDate->toString('HH:mm:ss')]['id_'.$recurrence->getUid()]);
                                            if ( sizeof($arrDates[$objRecurrDate->toString('yyyy-MM-dd')][$objRecurrDate->toString('HH:mm:ss')]) == 0 ) unset($arrDates[$objRecurrDate->toString('yyyy-MM-dd')][$objRecurrDate->toString('HH:mm:ss')]);
                                            if ( sizeof($arrEventDates[$objRecurrDate->toString('yyyy-MM-dd')][$objRecurrDate->toString('HH:mm:ss')]) == 0 ) unset($arrEventDates[$objRecurrDate->toString('yyyy-MM-dd')][$objRecurrDate->toString('HH:mm:ss')]);
                                            if ( sizeof($arrDates[$objRecurrDate->toString('yyyy-MM-dd')]) == 0 ) unset($arrDates[$objRecurrDate->toString('yyyy-MM-dd')]);
                                            if ( sizeof($arrEventDates[$objRecurrDate->toString('yyyy-MM-dd')]) == 0 ) unset($arrEventDates[$objRecurrDate->toString('yyyy-MM-dd')]);
                                            $objEventDate = clone $recurrence->getDtstart();
                                            $this->addToDates($objEventDate, $objEventDate, $recurrence, $arrDates, $arrEventDates);
                                        }
                                    }

							    }
						    }
                            /**
                             *  build event excetions list  : References
                             */
                            $referenceListObj = new Warecorp_ICal_Event_List_Reference($event);
                            $referenceList = $referenceListObj->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
                            if ( sizeof($referenceList) != 0 ) {
                                foreach ( $referenceList as &$reference ) {
                                    $referenceDates = $this->_buildRecurList($reference);
                                    $arrEventDates = ( !$arrEventDates ) ? array() : $arrEventDates;
                                    $arrDates = ( !$arrDates ) ? array() : $arrDates;
                                    $arrEventDates = $this->mergeMasterArrays($arrEventDates,$referenceDates);
                                    $arrDates = $this->mergeMasterArrays($arrDates,$referenceDates);
                                    //$arrEventDates = array_merge_recursive($arrEventDates,$referenceDates);
                                    //$arrDates = array_merge_recursive($arrDates,$referenceDates);
                                }
                            }


						    if ( true == $this->useCache ) {
							    $this->saveToCache($event, $arrEventDates);
						    }
					    }
				    }
                    $event->setTimezone($originalEventTimezone);
                }
			}
            // commit
            //$this->commitToCache();
            /**
             * sort result by date
             */
            //ksort($arrDates);
            //foreach ( $arrDates as &$date ) ksort($date);
		}

		return $arrDates;
	}

	/**
	 *
	 */
	private function saveToCache(Warecorp_ICal_Event $event, $dates)
	{
		if ( !$dates ) $dates = array();

        $key = $event->getId().$this->getTimezone().$this->periodDtstart.$this->periodDtend;
		if ( !array_key_exists($key, self::$eventsDatesCache) ) {
            if ( null === $this->getFromCache($event, true) ) {
                self::$eventsDatesCache[$key] = true;
                $arrNewData = array();
                $arrNewData['event_id']       = $event->getId();
                $arrNewData['event_root_id']  = $event->getRootId();
                $arrNewData['timezone']       = $this->getTimezone();
                $arrNewData['period_start']   = $this->periodDtstart;
                $arrNewData['period_end']     = $this->periodDtend;
                $arrNewData['data']           = serialize($dates);
                $this->DbConn->insert('calendar_event_cache', $arrNewData);

                if ( sizeof($dates) != 0 ) {
                    foreach ( $dates as $date => $times ) {
                        foreach ( $times as $time => $events ) {
                            foreach ( $events as $eventStrID => $eventInfo ) {
                                $values = array();
                                $values[] = $this->DbConn->quoteInto('?',   $event->getId());                   // event_id
                                $values[] = $this->DbConn->quoteInto('?',   $event->getRootId());               // event_root_id
                                $values[] = $this->DbConn->quoteInto('?',   $this->getTimezone());              // timezone
                                $values[] = $this->DbConn->quoteInto('?',   $date.' '.$eventInfo['time']);      // date

                               /**
                                * Add new field to base date_end in table calendar_event_cache_dates,
                                * this is value for date_end
                                */
                                if ( $event->isAllDay() ) {
                                    $values[] = $this->DbConn->quoteInto('?', $date.' 23:59:59');
                                }
                                else {
                                    $buildEventEndDate = new Zend_Date($date.'T'.$eventInfo['time'], Zend_Date::ISO_8601);
                                    $buildEventEndDate->add($event->getDurationSec(), Zend_Date::SECOND);
                                    $values[] = $this->DbConn->quoteInto('?', $buildEventEndDate->toString('YYYY-MM-dd HH:mm:ss'));
                                }

                                $values = '('.join(',',$values).')';
                                $this->cahceDateValuesToAdd[] = $values;
                            }
                        }
                    }
                }
            }
		}
	}

    /**
    * @desc
    */
    private function commitToCache()
    {
        if ( sizeof($this->cahceDateValuesToAdd) ) {
            $query = "INSERT INTO calendar_event_cache_dates (event_id, event_root_id, timezone, date, date_end) VALUES ";
            $query .= join(',',$this->cahceDateValuesToAdd);
            $this->DbConn->beginTransaction();
            try {
                $this->DbConn->query($query);
                $this->DbConn->commit();
            } catch (Exception $e) {
                $this->DbConn->rollBack();
            }
            $this->cahceDateValuesToAdd = array();
        }
    }

	/**
	 *
	 */
	private function getFromCache(Warecorp_ICal_Event $event, $check = false)
	{
        /*
        $cache = Warecorp_Cache::getFileCache();
        $cacheID = 'Warecorp_ICal_Event_List_'.$event->getId().'_'.str_replace('/', '_', $this->getTimezone()).'_'.str_replace('-', '', $this->periodDtstart).'_'.str_replace('-', '', $this->periodDtend);
        if ( false == ($result = $cache->load($cacheID)) ) {
            $query = $this->DbConn->select();
            if ( true == $check ) $query->from('calendar_event_cache', array('event_id'));
            else $query->from('calendar_event_cache', array('data'));
            $query->where('event_id = ?', $event->getId());
            $query->where('timezone = ?', $this->getTimezone());
            $query->where('period_start = ?', $this->periodDtstart);
            $query->where('period_end = ?', $this->periodDtend);
            $result = $this->DbConn->fetchOne($query);
            if ( $result ) {
                $key = $event->getId().$this->getTimezone().$this->periodDtstart.$this->periodDtend;
                self::$eventsDatesCache[$key] = true;
                $cache->save($result, $cacheID, array('Warecorp_ICal_Event_'.$event->getId(), 'Warecorp_ICal_Event_'.$event->getRootId()));
                return $result;
            }
            return null;
        } else {
            return $result;
        }
        */
	    $query = $this->DbConn->select();
	    if ( true == $check ) $query->from('calendar_event_cache', array('event_id'));
	    else $query->from('calendar_event_cache', array('data'));
	    $query->where('event_id = ?', $event->getId());
	    $query->where('timezone = ?', $this->getTimezone());
	    $query->where('period_start = ?', $this->periodDtstart);
	    $query->where('period_end = ?', $this->periodDtend);
	    $result = $this->DbConn->fetchOne($query);
        if ( $result ) {
            $key = $event->getId().$this->getTimezone().$this->periodDtstart.$this->periodDtend;
            self::$eventsDatesCache[$key] = true;
            return $result;
        }
        return null;
	}

	/*
	+------------------------------------------------------------
	|
	|   FREQ CHECK FUNCTIONS
	|
	+------------------------------------------------------------
	*/

	/**
	 *
	 */
	private function checkDailyRecur($event, &$arrDates, &$arrEventDates)
	{
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј Р·РѕРЅСѓ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ РІ С‚Сѓ,
		 * РІ РєРѕС‚РѕСЂРѕР№ Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		 */
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set( $event->getTimezone() );

		$objRrule = $event->getRrule();

		/**
		* С‚.Рє. РґР°С‚Р° РЅР°С‡Р°Р»Р° Рё РєРѕРЅС†Р° СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РІ РєР°Р»РµРЅРґР°СЂРµ РїРµСЂРёРѕРґР°
		* СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅР° РІ С‚Р°Р№РјР·РѕРЅРµ, РІ РєРѕС‚РѕСЂРѕР№ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РїРµСЂРёРѕРґ, С‚Рѕ
		* РїРµСЂРµРІРѕРґРёРј РёС… РІ С‚Р°Р№РјР·РѕРЅСѓ, РІ РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРѕ СЃРѕР±С‹С‚РёРµ
		*/
		$objPeriodDtstart   = clone $this->getPeriodDtstart();
		$objPeriodDtend     = clone $this->getPeriodDtend();
		$objPeriodDtstart->setTimezone($event->getTimezone());
		$objPeriodDtend->setTimezone($event->getTimezone());

		/**
		 * РѕРїСЂРµРґРµР»СЏРµРј, РІС…РѕРґРёС‚ Р»Рё СЃРѕР±С‹С‚РёРµ РІ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјС‹Р№ РїРµСЂРёРѕРґ РІСЂРµРјРµРЅРё
		 * С‚.Рµ. РЅРµ РёСЃС‚РµРєР»Рѕ Р»Рё РѕРЅРѕ Рє РЅР°С‡Р°Р»Сѓ СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР° РІСЂРµРјРµРЅРё
		 */

		/**
		 * РїСЂРµРІРѕРґРёРј Util РІ Р·РѕРЅСѓ, РІ РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		 */
		if ( null !== $objRrule->getUntil() ) {
            $defaultTimeZone = date_default_timezone_get();
            date_default_timezone_set($event->getTimezone());
            $objEventUtilDate = new Zend_Date($objRrule->getUntil(), Zend_Date::ISO_8601);
            date_default_timezone_set($defaultTimeZone);
			//$objEventUtilDate = clone $objRrule->getUntil();
			//$objEventUtilDate->setTimezone($event->getTimezone());
		} else $objEventUtilDate = null;

		/**
		* СЃСЂР°РІРЅРёРІР°РµРј РґР°С‚С‹ РЅР°С‡Р°Р»Р° РїРµСЂРёРѕРґР° Рё РґР°С‚Сѓ util РµСЃР»Рё РѕРЅРё РµСЃС‚СЊ
		* СЃСЂР°РІРЅРµРЅРёРµ РїСЂРѕРёСЃС…РѕРґРёС‚ РІ РѕРґРЅРѕР№ С‚Р°Р№РјР·РѕРЅРµ, РІ С‚РѕР№, РІ РєРѕС‚РѕСЂРѕР№
		* Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		*/
		if ( null !== $objEventUtilDate && $objPeriodDtstart->isLater($objEventUtilDate) ) {
		   return;
		}

		/**
		 * РёРЅРёС†РёР°Р»РёР·Р°С†РёСЏ РѕС‚СЃСѓС‚СЃС‚РІСѓСЋС‰РёС… РїР°СЂР°РјРµС‚СЂРѕРІ BYxxx
		 */
		if ( null === $objRrule->getBySecond() )    $objRrule->setBySecond($event->getDtstart()->get(Zend_Date::SECOND));
		if ( null === $objRrule->getByMinute() )    $objRrule->setByMinute($event->getDtstart()->get(Zend_Date::MINUTE));
		if ( null === $objRrule->getByHour() )      $objRrule->setByHour($event->getDtstart()->get(Zend_Date::HOUR));

		/**
		 * РµСЃР»Рё СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕ count -
		 * РґРµР»Р°РµРј РЅР°С‡Р°Р»Рѕ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° - РЅР°С‡Р°Р»Рѕ РґРЅСЏ СЃРѕР±С‹С‚РёСЏ,
		 * РІСЂРµРјСЏ СѓСЃС‚Р°РЅР°РІР»РёРІР°РµС‚СЃСЏ РІ 00:00:00, СЌС‚Рѕ РЅР°РґРѕ, С‡С‚РѕР±С‹ РѕС‚СЃС‡РёС‚Р°С‚СЊ СЃРєРѕР»СЊРєРѕ СЃРѕР±С‹С‚РёР№ СЃР»СѓС‡РёР»РѕСЃСЊ
		 * РµСЃР»Рё РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕ count - С‚Рѕ
		 * РґРµР»Р°РµРј РЅР°С‡Р°Р»Рѕ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° - РЅР°С‡Р°Р»Рѕ РїРµСЂРёРѕРґР° РІ РєРѕС‚РѕСЂРѕРј СЃРјРѕС‚СЂРёРј РєР°Р»РµРЅРґР°СЂСЊ ($this->getPeriodDtstart)
		 */
		//FIXME
		if ( $objRrule->getCount() ) {
			$objObservedDate = clone $event->getDtstart();
			$objObservedDate->setTime('000000');
		} else {
			/**
			* PeriodDtstart СЂР°СЃСЃС‡РёС‚С‹РІР°РµС‚СЃСЏ РѕС‚ С‚Р°Р№РјР·РѕРЅС‹, РІ РєРѕС‚РѕСЂРѕР№ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РєР°Р»РµРЅРґР°СЂСЊ,
			* Р° РЅРµ С‚РѕР№, РІ РєРѕС‚РѕСЂР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ, РїРѕСЌС‚РѕРјСѓ РЅР°РґРѕ РµРіРѕ РїРµСЂРµРІРѕРґРёС‚СЊ РІ С‚Р°Р№РјР·РѕРЅСѓ, РІ
			* РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
			* @todo
			* РЅР°РґРѕ СЃРјРѕС‚СЂРµС‚СЊ, РµСЃР»Рё РґР°С‚Р° СЃРѕР·РґР°РЅРёСЏ СЃРѕР±С‹С‚РёСЏ Р±РѕР»СЊС€Рµ РґР°С‚С‹ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° -
			* СѓСЃС‚Р°РЅР°РІР»РёРІР°С‚СЊ РЅР°С‡Р°Р»Рѕ РєР°Рє РґР°С‚Р° РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ, С‡С‚РѕР±С‹ РёР·Р±РµР¶Р°С‚СЊ Р»РёС€РЅРёС… РёС‚РµСЂР°С†РёР№
			*/
			//FIXME РєР°Рє С‚Рѕ РЅРµ РїСЂР°РІРёР»СЊРЅРѕ, !!!!!
			//FIXME РЅР°РґРѕ РёРґС‚Рё СЃ РёРЅС‚РµСЂРІР°Р»РѕРј N РїРѕРєР° РЅРµ РґРѕСЃС‚РёРіРЅРµРј РґР°С‚С‹ getPeriodDtstart С‚СѓРїРѕ С†РёРєР»РѕРј Р±РµР· РѕР±СЂР°Р±РѕС‚РєРё С‡РµР± РїРѕРїР°СЃС‚СЊ РІ РєСЂР°С‚РЅС‹Р№ РґРµРЅСЊ.

            $objObservedDate = clone $event->getDtstart();
            $deltaSec = $objPeriodDtstart->get(Zend_Date::TIMESTAMP) - $objObservedDate->get(Zend_Date::TIMESTAMP);
            $daltaDays = $deltaSec / 60 / 60 / 24;
            $daltaIntervalDays = $daltaDays / $objRrule->getInterval();
            $objObservedDate->add($objRrule->getInterval()* floor($daltaIntervalDays), Zend_Date::DAY);
            /*
            $objObservedDate = clone $event->getDtstart();
            $objObservedDate->setTime('000000');
            while ( $objObservedDate->isEarlier($objPeriodDtstart) ) {
                $objObservedDate->add($objRrule->getInterval(), Zend_Date::DAY);
            }
            */

            /*
			$objObservedDate = clone $this->getPeriodDtstart();
			$objObservedDate->setTimezone($event->getTimezone());
			$objObservedDate->setTime('000000');

			$objEvDate    = clone $event->getDtstart();
			$objEvDate->setTime('000000');
			if ( $objEvDate->isLater($objObservedDate) )  {
				$objObservedDate = $objEvDate;
			}
            */
		}
		/**
		 * FIXME РЅР°РґРѕ СЂР°СЃСЃРјРѕС‚СЂРµС‚СЊ РІРѕР·РјРѕР¶РЅРѕСЃС‚СЊ СЃРѕРєСЂР°С‰РµРЅРёСЏ РєРѕР»РёС‡РµСЃС‚РІР°
		 * СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјС‹С… РґРЅРµР№
		 * РЅР°РїСЂРёРјРµСЂ, С‚Р°Рє РµСЃР»Рё РµСЃС‚СЊ BYMONTH - РјРѕР¶РЅРѕ РїСЂРѕС…РѕРґРёС‚СЊ РїРѕ РґРЅСЏРј С‚РѕР»СЊРєРѕ СЌС‚РёС… РјРµСЃСЏС†РµРІ
		 * Рё С‚.Рґ.
		 */

		/**
		 * Handle BYYEARDAY if defined
		 */
		if ( null !== $objRrule->getByYearDay() ) {

		}
		/**
		 * Handle BYWEEKNO if defined
		 */
		elseif ( null !== $objRrule->getByWeekNo() ) {

		}
		/**
		 * Handle BYMONTH if defined
		 */
		elseif ( null !== $objRrule->getByMonth() ) {

		}
		/**
		 * Handle BYMONTHDAY if defined
		 */
		elseif ( null !== $objRrule->getByMonthDay() ) {

		}
		/**
		 * Handle BYDAY if defined
		 */
		elseif ( null !== $objRrule->getByDay() ) {

		}

		/**
		 * РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° - Р·РЅР°С‡РµРЅРёРµ СЃ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ Рё РґРѕ РєРѕРЅС†Р° СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР°
		 * - РїРѕРєР° РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ, С‡РµРј РґР°С‚Р° РєРѕРЅС†Р° СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР° < Р� >
		 * - РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ, С‡РµРј Р·РЅР°С‡РµРЅРёРµ UNTIL (РµСЃР»Рё РѕРЅРѕ РµСЃС‚СЊ) < Р� >
		 * - РєРѕР»РёС‡РµСЃС‚РІРѕ РїСЂРѕРёР·РѕС€РµРґС€РёС… СЃРѕР±С‹С‚РёР№ РјРµРЅСЊС€Рµ, С‡РµРј COUNT (РµСЃР»Рё РѕРЅРѕ РµСЃС‚СЊ)
		 */
		$isObservedDateEarlierUntil = true;
		$countOfPastDatesPassed     = true;
		$countOfPastDates           = 0;

		while ( $objObservedDate->isEarlier($objPeriodDtend) && $isObservedDateEarlierUntil && $countOfPastDatesPassed ) {
			//print $objObservedDate->toString('yyyy-MM-ddTHHmmss').' - '.$objPeriodDtend->toString('yyyy-MM-ddTHHmmss')."<br>";
			/**
			 * РµСЃР»Рё РЅРµС‚ UNTIL РёР»Рё РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РґР°С‚С‹ UNTIL СЃРѕР±С‹С‚РёСЏ < Р� >
			 * РµСЃР»Рё РЅРµС‚ UNTIL РёР»Рё РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РґР°С‚С‹ UNTIL СЃРѕР±С‹С‚РёСЏ < Р� >
			 * РЅРµС‚ COUNT РёР»Рё COUNT РјРµРЅСЊС€Рµ РїСЂРѕРёР·РѕС€РµРґС€РёС… СЃРѕР±С‹С‚РёР№
			 */
			if (
				( null === $objEventUtilDate || $isObservedDateEarlierUntil = $objObservedDate->isEarlier($objEventUtilDate) ) && //  СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
				( null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount() ) && // РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ COUNT РёР»Рё COUNT РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
				( $this->checkByDay($objRrule, $objObservedDate) ) && // РґРµРЅСЊ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYDAY
				( $this->checkByMonthDay($objRrule, $objObservedDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYMONTHDAY РёР»Рё BYMONTHDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
				( $this->checkByYearDay($objRrule, $objObservedDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РІ РіРѕРґСѓ РїРѕРїР°РґР°РµС‚ РІ BYYEARDAY РёР»Рё BYYEARDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
				( $this->checkByWeekNo($objRrule, $objObservedDate) ) && // РЅРѕРјРµСЂ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYWEEKNO РёР»Рё BYWEEKNO РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
				( $this->checkByMonth($objRrule, $objObservedDate) ) // РЅРѕРјРµСЂ РјРµСЃСЏС†Р° РїРѕРїР°РґР°РµС‚ РІ BYMONTH РёР»Рё BYMONTH РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
			)
			{
				/**
				 * Handle BYSECOND, BYMINUTE, BYHOUR
				 */
				foreach ( $objRrule->getByHour() as $_hour ) {
					foreach ( $objRrule->getByMinute() as $_minute ) {
						foreach ( $objRrule->getBySecond() as $_second ) {
							/**
							* РґР°С‚Р° СЃРѕР·РґР°РµС‚СЃСЏ РІ С‚Р°Р№РјР·РѕРЅРµ, РІ РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
							*/
							$objTmpDate = new Zend_Date($objObservedDate->toString('yyyy-MM-dd').'T'.sprintf('%02d',$_hour).sprintf('%02d',$_minute).sprintf('%02d',$_second), Zend_Date::ISO_8601);
							if (
								( $objTmpDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
								( $objTmpDate->equals($event->getDtstart()) || $objTmpDate->isLater($event->getDtstart()) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
								( null === $objEventUtilDate || $objTmpDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
							)
							{

								if ( false == ($isExDate = $this->isExDates($objObservedDate, $objTmpDate, $event)) && ($objTmpDate->equals($objPeriodDtstart) || $objTmpDate->isLater($objPeriodDtstart)) ) {
									$this->addToDates($objObservedDate, $objTmpDate, $event, $arrDates, $arrEventDates);
								}
								if ( !$isExDate ) $countOfPastDates ++ ;
							}
						}
					}
				}
			}
			$objObservedDate->add($objRrule->getInterval(), Zend_Date::DAY);
		}
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј Р·РѕРЅСѓ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ, СЃРѕС…СЂР°РЅРµРЅРЅСѓСЋ СЂР°РЅРµРµ
		 */
		date_default_timezone_set($defaultTimeZone);
	}

	/**
	 *
	 */
	private function checkWeeklyRecur($event, &$arrDates, &$arrEventDates)
	{
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј Р·РѕРЅСѓ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ РІ С‚Сѓ, РІ РєРѕС‚РѕСЂРѕР№ Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		 */
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set( $event->getTimezone() );

		$objRrule = $event->getRrule();

		/**
		* С‚.Рє. РґР°С‚Р° РЅР°С‡Р°Р»Р° Рё РєРѕРЅС†Р° СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РІ РєР°Р»РµРЅРґР°СЂРµ РїРµСЂРёРѕРґР°
		* СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅР° РІ С‚Р°Р№РјР·РѕРЅРµ, РІ РєРѕС‚РѕСЂРѕР№ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РїРµСЂРёРѕРґ, С‚Рѕ
		* РїРµСЂРµРІРѕРґРёРј РёС… РІ С‚Р°Р№РјР·РѕРЅСѓ, РІ РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРѕ СЃРѕР±С‹С‚РёРµ
		*/
		$objPeriodDtstart   = clone $this->getPeriodDtstart();
		$objPeriodDtend     = clone $this->getPeriodDtend();
		$objPeriodDtstart->setTimezone($event->getTimezone());
		$objPeriodDtend->setTimezone($event->getTimezone());

		/**
		 * РѕРїСЂРµРґРµР»СЏРµРј, РІС…РѕРґРёС‚ Р»Рё СЃРѕР±С‹С‚РёРµ РІ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјС‹Р№ РїРµСЂРёРѕРґ РІСЂРµРјРµРЅРё
		 * С‚.Рµ. РЅРµ РёСЃС‚РµРєР»Рѕ Р»Рё РѕРЅРѕ Рє РЅР°С‡Р°Р»Сѓ СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР° РІСЂРµРјРµРЅРё
		 */

		/**
		 * РїСЂРµРІРѕРґРёРј Util РІ Р·РѕРЅСѓ, РІ РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		 */
        if ( null !== $objRrule->getUntil() ) {
            $defaultTimeZone = date_default_timezone_get();
            date_default_timezone_set($event->getTimezone());
            $objEventUtilDate = new Zend_Date($objRrule->getUntil(), Zend_Date::ISO_8601);
            date_default_timezone_set($defaultTimeZone);
            //$objEventUtilDate = clone $objRrule->getUntil();
            //$objEventUtilDate->setTimezone($event->getTimezone());
        } else $objEventUtilDate = null;

		/**
		* СЃСЂР°РІРЅРёРІР°РµРј РґР°С‚С‹ РЅР°С‡Р°Р»Р° РїРµСЂРёРѕРґР° Рё РґР°С‚Сѓ util РµСЃР»Рё РѕРЅРё РµСЃС‚СЊ
		* СЃСЂР°РІРЅРµРЅРёРµ РїСЂРѕРёСЃС…РѕРґРёС‚ РІ РѕРґРЅРѕР№ С‚Р°Р№РјР·РѕРЅРµ, РІ С‚РѕР№, РІ РєРѕС‚РѕСЂРѕР№
		* Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		*/
		if ( null !== $objEventUtilDate && $objPeriodDtstart->isLater($objEventUtilDate) ) {
		   return;
		}

		/**
		 * РёРЅРёС†РёР°Р»РёР·Р°С†РёСЏ РѕС‚СЃСѓС‚СЃС‚РІСѓСЋС‰РёС… РїР°СЂР°РјРµС‚СЂРѕРІ BYxxx
		 */
		if ( null === $objRrule->getByDay() )       $objRrule->setByDay(Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($event->getDtstart()->get(Zend_Date::WEEKDAY_DIGIT)));
		if ( null === $objRrule->getBySecond() )    $objRrule->setBySecond($event->getDtstart()->get(Zend_Date::SECOND));
		if ( null === $objRrule->getByMinute() )    $objRrule->setByMinute($event->getDtstart()->get(Zend_Date::MINUTE));
		if ( null === $objRrule->getByHour() )      $objRrule->setByHour($event->getDtstart()->get(Zend_Date::HOUR));

		/**
		 * РµСЃР»Рё СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ count :
		 * РґРµР»Р°РµРј РЅР°С‡Р°Р»Рѕ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° - РЅР°С‡Р°Р»Рѕ РЅРµРґРµР»Рё,
		 * РЅР° РєРѕС‚РѕСЂРѕРј РЅР°С…РѕРґРёС‚СЊСЃСЏ РґР°С‚Р° СЃРѕР·РґР°РЅРёСЏ СЃРѕР±С‹С‚РёСЏ
		 * РІСЂРµРјСЏ СѓСЃС‚Р°РЅР°РІР»РёРІР°РµС‚СЃСЏ РІ 00:00:00
		 * timezone РёР· СЃРѕР±С‹С‚РёСЏ
		 * РµСЃР»Рё РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕ count :
		 * РґРµР»Р°РµРј РЅР°С‡Р°Р»Рѕ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° - РЅР°С‡Р°Р»Рѕ РЅРµРґРµР»Рё,
		 * РЅР° РєРѕС‚РѕСЂРѕРј РЅР°С…РѕРґРёС‚СЊСЃСЏ РґР°С‚Р° СЂР°СЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° ($this->getPeriodDtstart)
		 * РІСЂРµРјСЏ СѓСЃС‚Р°РЅР°РІР»РёРІР°РµС‚СЃСЏ РІ 00:00:00
		 * timezone РёР· СЃРѕР±С‹С‚РёСЏ
		 *
		 */
		//FIXME;
		if ( $objRrule->getCount() ) {
			$objObservedDate = clone $event->getDtstart();
			$objObservedDate = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($objObservedDate, $objRrule->getWkst(), $event->getTimezone());
		} else {
			/**
			* PeriodDtstart СЂР°СЃСЃС‡РёС‚С‹РІР°РµС‚СЃСЏ РѕС‚ С‚Р°Р№РјР·РѕРЅС‹, РІ РєРѕС‚РѕСЂРѕР№ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РєР°Р»РµРЅРґР°СЂСЊ,
			* Р° РЅРµ С‚РѕР№, РІ РєРѕС‚РѕСЂР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ, РїРѕСЌС‚РѕРјСѓ РЅР°РґРѕ РµРіРѕ РїРµСЂРµРІРѕРґРёС‚СЊ РІ С‚Р°Р№РјР·РѕРЅСѓ, РІ
			* РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
			* @todo
			* РЅР°РґРѕ СЃРјРѕС‚СЂРµС‚СЊ, РµСЃР»Рё РґР°С‚Р° СЃРѕР·РґР°РЅРёСЏ СЃРѕР±С‹С‚РёСЏ Р±РѕР»СЊС€Рµ РґР°С‚С‹ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° -
			* СѓСЃС‚Р°РЅР°РІР»РёРІР°С‚СЊ РЅР°С‡Р°Р»Рѕ РєР°Рє РґР°С‚Р° РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ, С‡С‚РѕР±С‹ РёР·Р±РµР¶Р°С‚СЊ Р»РёС€РЅРёС… РёС‚РµСЂР°С†РёР№
			*/

            $objObservedDate = clone $event->getDtstart();
            $objObservedDate = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($objObservedDate, $objRrule->getWkst(), $event->getTimezone());
            while ( $objObservedDate->isEarlier($objPeriodDtstart) ) {
                $objObservedDate->add($objRrule->getInterval(), Zend_Date::WEEK);
            }
            $objObservedDate->sub($objRrule->getInterval(), Zend_Date::WEEK);

            /*
			$objObservedDate = clone $this->getPeriodDtstart();
			$objObservedDate->setTimezone($event->getTimezone());
			$objObservedDate = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($objObservedDate, $objRrule->getWkst(), $event->getTimezone());

			$objEvDate = clone $event->getDtstart();
			$objEvDate = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($objObservedDate, $objRrule->getWkst(), $event->getTimezone());

			if ( $objEvDate->isLater($objObservedDate) )  {
				$objObservedDate = $objEvDate;
			}
            */

		}
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј РґР°С‚Сѓ РЅР°С‡Р°Р»Рѕ СЃРѕР±С‹С‚РёСЏ
		 * РІСЂРµРјСЏ СѓСЃС‚Р°РЅР°РІР»РёРІР°РµС‚СЃСЏ РІ 00:00:00
		 */
		$objEventDate       = clone $event->getDtstart();
		$objEventDate->setTime('000000');
		/**
		 * РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° - Р·РЅР°С‡РµРЅРёРµ СЃ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ Рё РґРѕ РєРѕРЅС†Р° СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР°
		 * - РїРѕРєР° РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ, С‡РµРј РґР°С‚Р° РєРѕРЅС†Р° СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР° < Р� >
		 * - РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ, С‡РµРј Р·РЅР°С‡РµРЅРёРµ UNTIL (РµСЃР»Рё РѕРЅРѕ РµСЃС‚СЊ) < Р� >
		 * - РєРѕР»РёС‡РµСЃС‚РІРѕ РїСЂРѕРёР·РѕС€РµРґС€РёС… СЃРѕР±С‹С‚РёР№ РјРµРЅСЊС€Рµ, С‡РµРј COUNT (РµСЃР»Рё РѕРЅРѕ РµСЃС‚СЊ)
		 */
		$isObservedDateEarlierUntil = true;
		$countOfPastDatesPassed     = true;
		$countOfPastDates           = 0;
		while ( $objObservedDate->isEarlier($objPeriodDtend) && $isObservedDateEarlierUntil && $countOfPastDatesPassed ) {
			/**
			 * РµСЃР»Рё РЅРµС‚ UNTIL РёР»Рё РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РґР°С‚С‹ UNTIL СЃРѕР±С‹С‚РёСЏ < Р� >
			 * РµСЃР»Рё РЅРµС‚ UNTIL РёР»Рё РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РґР°С‚С‹ UNTIL СЃРѕР±С‹С‚РёСЏ < Р� >
			 * РЅРµС‚ COUNT РёР»Рё COUNT РјРµРЅСЊС€Рµ РїСЂРѕРёР·РѕС€РµРґС€РёС… СЃРѕР±С‹С‚РёР№
			 */
			if (
				(null === $objEventUtilDate || $isObservedDateEarlierUntil = $objObservedDate->isEarlier($objEventUtilDate)) &&
				(null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount())
			)
			{
				/**
				 * Handle BYDAY
				 */
				if ( null !== $objRrule->getByDay() ) {
					/**
					 * РїРѕР»СѓС‡Р°РµРј РґР°С‚Сѓ РЅР°С‡Р°Р»Р° Рё РєРѕРЅС†Р° С‚РµРєСѓС‰РµР№ РЅРµРґРµР»Рё
					 * РІ Р·Р°РІРёСЃРёРјРѕСЃС‚Рё РѕС‚ РїР°СЂР°РјРµС‚СЂР° Wkst
					 * РІ РєРѕС‚РѕСЂСѓСЋ РїРѕРїР°РґР°РµС‚ РЅР°Р±Р»СЋРґР°РµРјР°СЋ РґР°С‚Р°
					 */
					$observedWeekDtstart = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($objObservedDate, $objRrule->getWkst(), $event->getTimezone());
					/**
					 * @todo РІРѕРѕР±С‰Рµ РјРѕР¶РЅРѕ Р±СЂР°С‚СЊ РІ РєР°С‡РµСЃС‚РІРµ РЅР°С‡Р°Р»Р° РЅРµРґРµР»Рё $objObservedDate - С‚.Рє. СЌС‚Рѕ Рё РµСЃС‚СЊ РЅР°С‡Р°Р»Рѕ СЃР°РјРѕР№ РїРµСЂРІРѕР№ РЅРµРґРµР»Рё РґР»СЏ СЃРѕР±С‹С‚РёСЏ
					 */
					$observedWeekDtend = clone $observedWeekDtstart;
					$observedWeekDtend->add(1, Zend_Date::WEEK); // СЌС‚Р° РґР°С‚Р° СѓР¶Рµ РЅР° РґСЂСѓРіРѕР№ РЅРµРґРµР»Рµ

					/**
					 * @todo BYDAY РјРѕР¶РµС‚ СЃРѕРґРµСЂР¶Р°С‚СЊ [+-]N(WEEKDAY)
					 * РќРћ РґР»СЏ С‚РёРїР° DAILY Рё WEEKLY РјС‹ РЅРµ РїРѕРґРґРµСЂР¶РёРІР°РµРј [-]N(SU|MO|TU|WE|TH|FR|SA) С„РѕСЂРјР°С‚, С‚РѕР»СЊРєРѕ (SU|MO|TU|WE|TH|FR|SA)
					 * РїРѕСЌС‚РѕРјСѓ РЅР°РґРѕ РѕС‡РёС‰Р°С‚СЊ РµРіРѕ РѕС‚ СЌС‚РёС… СЃРёРјРІРѕР»РѕРІ
					 * Р­С‚Рѕ Р±СѓРґРµРј РґРµР»Р°С‚СЊ РІ С„СѓРЅРєС†РёРё Warecorp_ICal_Rrule::setByDay
					 */
					foreach ( $objRrule->getByDayClear() as $byDayCurrent ) {
						/**
						 * С‚.Рє. РІСЂРµРјСЏ РґР°С‚С‹ РЅР°С‡Р°Р»Р° РЅРµРґРµР»Рё РїСЂРёРІРµРґРµРЅРѕ Рє 00:00:00,
						 * Р° РґР°С‚Р° СЃРѕ СЃРјРµС‰РµРЅРёРµРј С„РѕСЂРјРёСЂСѓРµС‚СЃСЏ РѕС‚ РЅРµРµ, С‚Рѕ Рё
						 * РІСЂРµРјСЏ РґР°С‚С‹ СЃРјРµС‰РµРЅРёСЏ Р±СѓРґРµС‚ 00:00:00
						 */
						$lookDate = clone $observedWeekDtstart;
						Warecorp_ICal_Event_List::getOffsetByDay($lookDate, $byDayCurrent, 'MO');
						if (
							( $lookDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РєРѕРЅС†Р° РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
							( $lookDate->equals($objEventDate) || $lookDate->isLater($objEventDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
							( null === $objEventUtilDate || $lookDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
							( null === $objRrule->getCount() || $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
						)
						{
							/**
							 * @todo BYDAY РґР»СЏ С‚РёРїР° WEEKLY СЏРІР»СЏРµС‚СЃСЏ СЂР°СЃС€РёСЂСЏСЋС‰РёРј, Р° РЅРµ РѕРіСЂР°РЅРёС‡РёРІР°СЋС‰РёРј
							 * РїРѕСЌС‚РѕРјСѓ РґСѓРјР°СЋ РЅРµ РЅР°РґРѕ РїСЂРѕРІРµСЂСЏС‚СЊ checkByDay
							 */
							if (
								//( $this->checkByDay($objRrule, $lookDate) ) && // РґРµРЅСЊ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYDAY
								( $this->checkByMonthDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYMONTHDAY РёР»Рё BYMONTHDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( $this->checkByYearDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РІ РіРѕРґСѓ РїРѕРїР°РґР°РµС‚ РІ BYYEARDAY РёР»Рё BYYEARDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( $this->checkByWeekNo($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYWEEKNO РёР»Рё BYWEEKNO РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( $this->checkByMonth($objRrule, $lookDate) ) // РЅРѕРјРµСЂ РјРµСЃСЏС†Р° РїРѕРїР°РґР°РµС‚ РІ BYMONTH РёР»Рё BYMONTH РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
							)
							{
							   /**
								 * Handle BYSECOND, BYMINUTE, BYHOUR
								 */
								foreach ( $objRrule->getByHour() as $_hour ) {
									foreach ( $objRrule->getByMinute() as $_minute ) {
										foreach ( $objRrule->getBySecond() as $_second ) {
											$objTmpDate = new Zend_Date($lookDate->toString('yyyy-MM-dd').'T'.sprintf('%02d',$_hour).sprintf('%02d',$_minute).sprintf('%02d',$_second), Zend_Date::ISO_8601);
											if (
												( $objTmpDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
												( $objTmpDate->equals($event->getDtstart()) || $objTmpDate->isLater($event->getDtstart()) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
												( null === $objEventUtilDate || $objTmpDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
												( null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
											)
											{
												if ( false == ($isExDate = $this->isExDates($lookDate, $objTmpDate, $event)) && ($objTmpDate->equals($objPeriodDtstart) || $objTmpDate->isLater($objPeriodDtstart)) ) {
													$this->addToDates($lookDate, $objTmpDate, $event, $arrDates, $arrEventDates);
												}
												if ( !$isExDate ) $countOfPastDates ++ ;
											}
										}
									}
								}
							}
						}
					}
				}
			}
			$objObservedDate->add($objRrule->getInterval(), Zend_Date::WEEK);
		}
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј Р·РѕРЅСѓ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ, СЃРѕС…СЂР°РЅРµРЅРЅСѓСЋ СЂР°РЅРµРµ
		 */
		date_default_timezone_set($defaultTimeZone);
	}

	/**
	 *
	 */
	private function checkMonthlyRecur($event, &$arrDates, &$arrEventDates)
	{
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј Р·РѕРЅСѓ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ РІ С‚Сѓ, РІ РєРѕС‚РѕСЂРѕР№ Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		 */
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set( $event->getTimezone() );

		$objRrule = $event->getRrule();

		/**
		* С‚.Рє. РґР°С‚Р° РЅР°С‡Р°Р»Р° Рё РєРѕРЅС†Р° СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РІ РєР°Р»РµРЅРґР°СЂРµ РїРµСЂРёРѕРґР°
		* СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅР° РІ С‚Р°Р№РјР·РѕРЅРµ, РІ РєРѕС‚РѕСЂРѕР№ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РїРµСЂРёРѕРґ, С‚Рѕ
		* РїРµСЂРµРІРѕРґРёРј РёС… РІ С‚Р°Р№РјР·РѕРЅСѓ, РІ РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРѕ СЃРѕР±С‹С‚РёРµ
		*/
		$objPeriodDtstart   = clone $this->getPeriodDtstart();
		$objPeriodDtend     = clone $this->getPeriodDtend();
		$objPeriodDtstart->setTimezone($event->getTimezone());
		$objPeriodDtend->setTimezone($event->getTimezone());

		/**
		 * РѕРїСЂРµРґРµР»СЏРµРј, РІС…РѕРґРёС‚ Р»Рё СЃРѕР±С‹С‚РёРµ РІ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјС‹Р№ РїРµСЂРёРѕРґ РІСЂРµРјРµРЅРё
		 * С‚.Рµ. РЅРµ РёСЃС‚РµРєР»Рѕ Р»Рё РѕРЅРѕ Рє РЅР°С‡Р°Р»Сѓ СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР° РІСЂРµРјРµРЅРё
		 */

		/**
		 * РїСЂРµРІРѕРґРёРј Util РІ Р·РѕРЅСѓ, РІ РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		 */
        if ( null !== $objRrule->getUntil() ) {
            $defaultTimeZone = date_default_timezone_get();
            date_default_timezone_set($event->getTimezone());
            $objEventUtilDate = new Zend_Date($objRrule->getUntil(), Zend_Date::ISO_8601);
            date_default_timezone_set($defaultTimeZone);
            //$objEventUtilDate = clone $objRrule->getUntil();
            //$objEventUtilDate->setTimezone($event->getTimezone());
        } else $objEventUtilDate = null;

		/**
		* СЃСЂР°РІРЅРёРІР°РµРј РґР°С‚С‹ РЅР°С‡Р°Р»Р° РїРµСЂРёРѕРґР° Рё РґР°С‚Сѓ util РµСЃР»Рё РѕРЅРё РµСЃС‚СЊ
		* СЃСЂР°РІРЅРµРЅРёРµ РїСЂРѕРёСЃС…РѕРґРёС‚ РІ РѕРґРЅРѕР№ С‚Р°Р№РјР·РѕРЅРµ, РІ С‚РѕР№, РІ РєРѕС‚РѕСЂРѕР№
		* Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		*/
		if ( null !== $objEventUtilDate && $objPeriodDtstart->isLater($objEventUtilDate) ) {
		   return;
		}

		/**
		 * РёРЅРёС†РёР°Р»РёР·Р°С†РёСЏ РѕС‚СЃСѓС‚СЃС‚РІСѓСЋС‰РёС… РїР°СЂР°РјРµС‚СЂРѕРІ BYxxx, РєРѕС‚РѕСЂС‹Рµ СЏРІР»СЏСЋС‚СЃСЏ СЂР°СЃС€РёСЂСЏСЋС‰РёРјРё
		 * @todo BYWEEKNO - РїРѕ РёРґРµРµ С‚РѕР¶Рµ СЏРІР»СЏРµС‚СЃСЏ СЂР°СЃС€РёСЂСЏСЋС‰РёРј, СЃ РґСЂСѓРіРѕР№ СЃС‚РѕСЂРѕРЅС‹ weekno СЂР°СЃСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РєР°Рє
		 * РїРµСЂРёРѕРґ РІСЂРµРјРµРЅРё РіРѕРґР°, Р° РЅРµ РјРµСЃСЏС†Р°, РїРѕСЌС‚РѕРјСѓ РјРѕР¶РµС‚ Р±С‹С‚СЊ Рё РѕРіСЂР°РЅРёС‡РёРІР°СЋС‰РёРј (РїРѕСЃР»РµРґРЅРµРµ СЃРєРѕСЂРµРµ РІСЃРµРіРѕ)
		 * BYDAY - СЂР°СЃС€РёСЂСЏСЋС‰РµРµ СѓСЃР»РѕРІРёРµ
		 * BYMONTHDAY - СЂР°СЃС€РёСЂСЏСЋС‰РµРµ СѓСЃР»РѕРІРёРµ
		 */
		if ( null === $objRrule->getBySecond() )    $objRrule->setBySecond($event->getDtstart()->get(Zend_Date::SECOND));
		if ( null === $objRrule->getByMinute() )    $objRrule->setByMinute($event->getDtstart()->get(Zend_Date::MINUTE));
		if ( null === $objRrule->getByHour() )      $objRrule->setByHour($event->getDtstart()->get(Zend_Date::HOUR));

		/**
		 * РґРµР»Р°РµРј РЅР°С‡Р°Р»Рѕ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° - РЅР°С‡Р°Р»Рѕ РјРµСЃСЏС†Р°,
		 * РЅР° РєРѕС‚РѕСЂРѕРј РЅР°С…РѕРґРёС‚СЊСЃСЏ РґР°С‚Р° СЃРѕР±С‹С‚РёСЏ
		 * РІСЂРµРјСЏ СѓСЃС‚Р°РЅР°РІР»РёРІР°РµС‚СЃСЏ РІ 00:00:00
		 */
		//FIXME;
		if ( $objRrule->getCount() ) {
			$objObservedDate = clone $event->getDtstart();
			$objObservedDate->setDay('01');
			$objObservedDate->setTime('000000');
		} else {
			/**
			* PeriodDtstart СЂР°СЃСЃС‡РёС‚С‹РІР°РµС‚СЃСЏ РѕС‚ С‚Р°Р№РјР·РѕРЅС‹, РІ РєРѕС‚РѕСЂРѕР№ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РєР°Р»РµРЅРґР°СЂСЊ,
			* Р° РЅРµ С‚РѕР№, РІ РєРѕС‚РѕСЂР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ, РїРѕСЌС‚РѕРјСѓ РЅР°РґРѕ РµРіРѕ РїРµСЂРµРІРѕРґРёС‚СЊ РІ С‚Р°Р№РјР·РѕРЅСѓ, РІ
			* РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
			* @todo
			* РЅР°РґРѕ СЃРјРѕС‚СЂРµС‚СЊ, РµСЃР»Рё РґР°С‚Р° СЃРѕР·РґР°РЅРёСЏ СЃРѕР±С‹С‚РёСЏ Р±РѕР»СЊС€Рµ РґР°С‚С‹ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° -
			* СѓСЃС‚Р°РЅР°РІР»РёРІР°С‚СЊ РЅР°С‡Р°Р»Рѕ РєР°Рє РґР°С‚Р° РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ, С‡С‚РѕР±С‹ РёР·Р±РµР¶Р°С‚СЊ Р»РёС€РЅРёС… РёС‚РµСЂР°С†РёР№
			*/
            $objObservedDate = clone $event->getDtstart();
            $objObservedDate->setDay('01');
            $objObservedDate->setTime('000000');
            while ( $objObservedDate->isEarlier($objPeriodDtstart) ) {
                $objObservedDate->add($objRrule->getInterval(), Zend_Date::MONTH);
            }
            $objObservedDate->sub($objRrule->getInterval(), Zend_Date::MONTH);
            /*
			$objObservedDate = clone $this->getPeriodDtstart();
			$objObservedDate->setTimezone($event->getTimezone());
			$objObservedDate->setDay('01');
			$objObservedDate->setTime('000000');

			$objEvDate = clone $event->getDtstart();
			$objEvDate->setDay('01');
			$objEvDate->setTime('000000');

			if ( $objEvDate->isLater($objObservedDate) )  {
				$objObservedDate = $objEvDate;
			}
            */
		}

		$objEventDate       = clone $event->getDtstart();
		$objEventDate->setTime('000000');

		/**
		 * РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° - Р·РЅР°С‡РµРЅРёРµ СЃ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ Рё РґРѕ РєРѕРЅС†Р° СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР°
		 * - РїРѕРєР° РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ, С‡РµРј РґР°С‚Р° РєРѕРЅС†Р° СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР° < Р� >
		 * - РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ, С‡РµРј Р·РЅР°С‡РµРЅРёРµ UNTIL (РµСЃР»Рё РѕРЅРѕ РµСЃС‚СЊ) < Р� >
		 * - РєРѕР»РёС‡РµСЃС‚РІРѕ РїСЂРѕРёР·РѕС€РµРґС€РёС… СЃРѕР±С‹С‚РёР№ РјРµРЅСЊС€Рµ, С‡РµРј COUNT (РµСЃР»Рё РѕРЅРѕ РµСЃС‚СЊ)
		 */
		$isObservedDateEarlierUntil = true;
		$countOfPastDatesPassed     = true;
		$countOfPastDates           = 0;
		while ( $objObservedDate->isEarlier($objPeriodDtend) && $isObservedDateEarlierUntil && $countOfPastDatesPassed ) {
			/**
			 * РµСЃР»Рё РЅРµС‚ UNTIL РёР»Рё РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РґР°С‚С‹ UNTIL СЃРѕР±С‹С‚РёСЏ < Р� >
			 * РµСЃР»Рё РЅРµС‚ UNTIL РёР»Рё РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РґР°С‚С‹ UNTIL СЃРѕР±С‹С‚РёСЏ < Р� >
			 * РЅРµС‚ COUNT РёР»Рё COUNT РјРµРЅСЊС€Рµ РїСЂРѕРёР·РѕС€РµРґС€РёС… СЃРѕР±С‹С‚РёР№
			 */
			if (
				(null === $objEventUtilDate || $isObservedDateEarlierUntil = $objObservedDate->isEarlier($objEventUtilDate)) &&
				(null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount())
			)
			{
				$objStoredRrule = clone $objRrule;
				/**
				 * Handle BYMONTHDAY if defined
				 */
				if ( null !== $objRrule->getByMonthDay() ) {
					/**
					 * РµСЃР»Рё РЅРµ РѕРїСЂРµРґРµР»РµРЅРѕ BYDAY - Р±РµСЂРµРј РµРіРѕ РёР· СЃРѕР±С‹С‚РёСЏ
					 * @todo РІРѕРѕР±С‰Рµ СЃРїРѕСЂРЅС‹Р№ РІРѕРїСЂРѕСЃ, РЅРѕ С‚Р°Рє РѕРїРёСЃР°РЅРѕ РІ rfc 2445 РЅР°РґРѕ РµС‰Рµ СЂР°Р· РїСЂРѕРІРµСЂРёС‚СЊ
					 * rfc 2445 :
					 * Information, not contained in the rule, necessary to determine the
					 * various recurrence instance start time and dates are derived from the
					 * Start Time (DTSTART) entry attribute. For example,
					 * "FREQ=YEARLY;BYMONTH=1" doesn't specify a specific day within the
					 * month or a time. This information would be the same as what is
					 * specified for DTSTART.
					 */
					/*
					if ( null === $objRrule->getByDay() ) {
						$objRrule->setByDay(Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($event->getDtstart()->get(Zend_Date::WEEKDAY_DIGIT)));
					}
					*/
					/**
					 * foreach bymonthday value build date and check it
					 */
					foreach ( $objRrule->getByMonthDay() as $byMonthDayCurrent ) {
						if ( substr($byMonthDayCurrent, 0, 1) == '-' ) {
							$daysInMonth = $objObservedDate->get(Zend_Date::MONTH_DAYS);
							$lookDay = $daysInMonth + $byMonthDayCurrent + 1;
							$lookDate = clone $objObservedDate;
							$lookDate->setDay($lookDay);
						} else {
							$lookDate = clone $objObservedDate;
                            if ($lookDate->get(Zend_Date::MONTH_DAYS) >= $byMonthDayCurrent) $lookDate->setDay($byMonthDayCurrent);
                            else continue;
						}

						if (
							( $lookDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РєРѕРЅС†Р° РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
							( $lookDate->equals($objEventDate) || $lookDate->isLater($objEventDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
							( null === $objEventUtilDate || $lookDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
							( null === $objRrule->getCount() || $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
						)
						{
							/**
							 * @todo BYDAY Рё BYMONTHDAY РґР»СЏ С‚РёРїР° MONTHLY СЏРІР»СЏРµС‚СЃСЏ СЂР°СЃС€РёСЂСЏСЋС‰РёРј, Р° РЅРµ РѕРіСЂР°РЅРёС‡РёРІР°СЋС‰РёРј
							 * РїРѕСЌС‚РѕРјСѓ РґСѓРјР°СЋ РЅРµ РЅР°РґРѕ РїСЂРѕРІРµСЂСЏС‚СЊ checkByDay Рё checkByMonthDay
							 * @todo РЅР°РґРѕ Р»Рё РїСЂРѕРІРµСЂСЏС‚СЊ Р·РґРµСЃСЊ checkByDay ? С‚.Рє. СЃРјРѕС‚СЂРёРј РїРѕ BYMONTHDAY
							 */
							if (
								//( $this->checkByDay($objRrule, $lookDate) ) && // РґРµРЅСЊ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYDAY
								//( $this->checkByMonthDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYMONTHDAY РёР»Рё BYMONTHDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( $this->checkByYearDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РІ РіРѕРґСѓ РїРѕРїР°РґР°РµС‚ РІ BYYEARDAY РёР»Рё BYYEARDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( $this->checkByWeekNo($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYWEEKNO РёР»Рё BYWEEKNO РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( $this->checkByMonth($objRrule, $lookDate) ) // РЅРѕРјРµСЂ РјРµСЃСЏС†Р° РїРѕРїР°РґР°РµС‚ РІ BYMONTH РёР»Рё BYMONTH РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
							)
							{
							   /**
								 * Handle BYSECOND, BYMINUTE, BYHOUR
								 */
								foreach ( $objRrule->getByHour() as $_hour ) {
									foreach ( $objRrule->getByMinute() as $_minute ) {
										foreach ( $objRrule->getBySecond() as $_second ) {
											$objTmpDate = new Zend_Date($lookDate->toString('yyyy-MM-dd').'T'.sprintf('%02d',$_hour).sprintf('%02d',$_minute).sprintf('%02d',$_second), Zend_Date::ISO_8601);
											if (
												( $objTmpDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
												( $objTmpDate->equals($event->getDtstart()) || $objTmpDate->isLater($event->getDtstart()) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
												( null === $objEventUtilDate || $objTmpDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
												( null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
											)
											{
												if ( false == ($isExDate = $this->isExDates($lookDate, $objTmpDate, $event)) && ($objTmpDate->equals($objPeriodDtstart) || $objTmpDate->isLater($objPeriodDtstart)) ) {
													$this->addToDates($lookDate, $objTmpDate, $event, $arrDates, $arrEventDates);
												}
												if ( !$isExDate ) $countOfPastDates ++ ;
											}
										}
									}
								}
							}
						}
					}
				}
				/**
				 * Handle BYDAY if defined
				 */
				elseif ( null !== $objRrule->getByDay() ) {
					/**
					 * РµСЃР»Рё РЅРµ РѕРїСЂРµРґРµР»РµРЅРѕ BYMONTHDAY - Р±РµСЂРµРј РµРіРѕ РёР· СЃРѕР±С‹С‚РёСЏ
					 * @todo РІРѕРѕР±С‰Рµ СЃРїРѕСЂРЅС‹Р№ РІРѕРїСЂРѕСЃ, РЅРѕ С‚Р°Рє РѕРїРёСЃР°РЅРѕ РІ rfc 2445 РЅР°РґРѕ РµС‰Рµ СЂР°Р· РїСЂРѕРІРµСЂРёС‚СЊ
					 * rfc 2445 :
					 * Information, not contained in the rule, necessary to determine the
					 * various recurrence instance start time and dates are derived from the
					 * Start Time (DTSTART) entry attribute. For example,
					 * "FREQ=YEARLY;BYMONTH=1" doesn't specify a specific day within the
					 * month or a time. This information would be the same as what is
					 * specified for DTSTART.
					 */
					/*
					if ( null === $objRrule->getByMonthDay() ) {
						$objRrule->setByMonthDay($event->getDtstart()->get(Zend_Date::MONTH_SHORT));
					}
					*/
					$objDateMonthend = clone $objObservedDate;
					$objDateMonthend->add(1, Zend_Date::MONTH);
					/**
					 * foreach byday value build date and check it
					 */
					foreach ( $objRrule->getByDayClear() as $byDayCurrent ) {
						/**
						 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј РЅР°С‡Р°Р»СЊРЅСѓСЋ РґР°С‚Сѓ РєР°Рє РїРµСЂРІС‹Р№ РґРµРЅСЊ РЅРµРґРµР»Рё РјРµСЃСЏС†Р°
						 * С‚.Рµ. РїРµСЂРІС‹Р№ РїРѕРЅРµРґРµР»СЊРЅРёРє РёР»Рё РїРµСЂРІС‹Р№ РІС‚РѕСЂРЅРёРє Рё С‚.Рґ. РІ Р·Р°РІРёСЃРёРјРѕСЃС‚Рё РѕС‚ $byDayCurrent
						 */
						$lookDate = Warecorp_ICal_Event_List::getFirstWeekdayOfMonth($objObservedDate, $byDayCurrent, $event->getTimezone());
						$weekdaysCount = Warecorp_ICal_Event_List::getWeekdaysInMonth($objObservedDate, $byDayCurrent, $event->getTimezone());
						$weekDayIndex = 1;
						/**
						 * РїСЂРѕС…РѕРґРёРј РїРѕ РІСЃРµРј РґРЅСЏРј РЅРµРґРµР»Рё РјРµСЃСЏС†Р°
						 * 1-С‹Р№ РїРѕРЅРµРґРµР»СЊРЅРёРє, 2-РѕР№ РїРѕРЅРµРґРµР»СЊРЅРёРє Рё С‚.Рґ. РїРѕРєР° РЅРµ РґРѕСЃС‚РёРіРЅРµРј РєРѕРЅС†Р° РјРµСЃСЏС†Р°
						 */
						while ( $lookDate->isEarlier($objDateMonthend) ) {
							if (
								( $lookDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РєРѕРЅС†Р° РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
								( $lookDate->equals($objEventDate) || $lookDate->isLater($objEventDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
								( null === $objEventUtilDate || $lookDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( null === $objRrule->getCount() || $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
							)
							{
								/**
								 * @todo BYDAY Рё BYMONTHDAY РґР»СЏ С‚РёРїР° MONTHLY СЏРІР»СЏРµС‚СЃСЏ СЂР°СЃС€РёСЂСЏСЋС‰РёРј, Р° РЅРµ РѕРіСЂР°РЅРёС‡РёРІР°СЋС‰РёРј
								 * РїРѕСЌС‚РѕРјСѓ РґСѓРјР°СЋ РЅРµ РЅР°РґРѕ РїСЂРѕРІРµСЂСЏС‚СЊ checkByDay Рё checkByMonthDay
								 * @todo РЅР°РґРѕ Р»Рё РїСЂРѕРІРµСЂСЏС‚СЊ Р·РґРµСЃСЊ checkByMonthDay ? С‚.Рє. СЃРјРѕС‚СЂРёРј РїРѕ BYDAY
								 * @todo С‚.Рє РґР»СЏ BYDAY РјРѕР¶РµС‚ Р±С‹С‚СЊ СЃ -+, РЅР°РґРѕ РІР°Р»РёРґРёСЂРѕРІР°С‚СЊ
								 */
								if (
									( $this->checkByDay($objRrule, $lookDate, $weekDayIndex, $weekdaysCount) ) && // РґРµРЅСЊ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYDAY
									//( $this->checkByMonthDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYMONTHDAY РёР»Рё BYMONTHDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
									( $this->checkByYearDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РІ РіРѕРґСѓ РїРѕРїР°РґР°РµС‚ РІ BYYEARDAY РёР»Рё BYYEARDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
									( $this->checkByWeekNo($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYWEEKNO РёР»Рё BYWEEKNO РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
									( $this->checkByMonth($objRrule, $lookDate) ) // РЅРѕРјРµСЂ РјРµСЃСЏС†Р° РїРѕРїР°РґР°РµС‚ РІ BYMONTH РёР»Рё BYMONTH РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								)
								{
								   /**
									 * Handle BYSECOND, BYMINUTE, BYHOUR
									 */
									foreach ( $objRrule->getByHour() as $_hour ) {
										foreach ( $objRrule->getByMinute() as $_minute ) {
											foreach ( $objRrule->getBySecond() as $_second ) {
												$objTmpDate = new Zend_Date($lookDate->toString('yyyy-MM-dd').'T'.sprintf('%02d',$_hour).sprintf('%02d',$_minute).sprintf('%02d',$_second), Zend_Date::ISO_8601);
												if (
													( $objTmpDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
													( $objTmpDate->equals($event->getDtstart()) || $objTmpDate->isLater($event->getDtstart()) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
													( null === $objEventUtilDate || $objTmpDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
													( null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
												)
												{
													if ( false == ($isExDate = $this->isExDates($lookDate, $objTmpDate, $event)) && ($objTmpDate->equals($objPeriodDtstart) || $objTmpDate->isLater($objPeriodDtstart)) ) {
														$this->addToDates($lookDate, $objTmpDate, $event, $arrDates, $arrEventDates);
													}
													if ( !$isExDate ) $countOfPastDates ++ ;
												}
											}
										}
									}
								}
							}
							$lookDate->add(7, Zend_Date::DAY);
							$weekDayIndex ++;
						}

					}
				}
				/**
				 * BYMONTHDAY and BYDAY is not defined
				 * Handle DEFAULT
				 * РµСЃР»Рё РЅРµ Р·Р°РїРѕР»РЅРµРЅРѕ РЅРё BYMONTHDAY РЅРё BYDAY С‚Рѕ РїСЂРµРґРїРѕР»РѕРіР°РµРј
				 * С‡С‚Рѕ СЃРѕР±С‹С‚РёРµ РїРѕРІС‚РѕСЂСЏРµС‚СЃСЏ РµР¶РµРјРµСЃСЏС‡РЅРѕ РїРѕ РЅРѕРјРµСЂСѓ РґРЅСЏ
				 */
				else {
					/**
					 * РµСЃР»Рё РЅРµ РѕРїСЂРµРґРµР»РµРЅРѕ BYMONTHDAY - Р±РµСЂРµРј РµРіРѕ РёР· СЃРѕР±С‹С‚РёСЏ
					 * @todo РІРѕРѕР±С‰Рµ СЃРїРѕСЂРЅС‹Р№ РІРѕРїСЂРѕСЃ, РЅРѕ С‚Р°Рє РѕРїРёСЃР°РЅРѕ РІ rfc 2445 РЅР°РґРѕ РµС‰Рµ СЂР°Р· РїСЂРѕРІРµСЂРёС‚СЊ
					 * rfc 2445 :
					 * Information, not contained in the rule, necessary to determine the
					 * various recurrence instance start time and dates are derived from the
					 * Start Time (DTSTART) entry attribute. For example,
					 * "FREQ=YEARLY;BYMONTH=1" doesn't specify a specific day within the
					 * month or a time. This information would be the same as what is
					 * specified for DTSTART.
					 */
					if ( null === $objRrule->getByMonthDay() ) {
						$objRrule->setByMonthDay($event->getDtstart()->get(Zend_Date::MONTH_SHORT));
					}
					/*
					if ( null === $objRrule->getByDay() ) {
						$objRrule->setByDay(Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($event->getDtstart()->get(Zend_Date::WEEKDAY_DIGIT)));
					}
					*/
					/**
					 * foreach bymonthday value build date and check it
					 */
					foreach ( $objRrule->getByMonthDay() as $byMonthDayCurrent ) {
						if ( substr($byMonthDayCurrent, 0, 1) == '-' ) {
							$daysInMonth = $objObservedDate->get(Zend_Date::MONTH_DAYS);
							$lookDay = $daysInMonth + $byMonthDayCurrent + 1;
							$lookDate = clone $objObservedDate;
							$lookDate->setDay($lookDay);
						} else {
							$lookDate = clone $objObservedDate;
							$lookDate->setDay($byMonthDayCurrent);
						}
						if (
							( $lookDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РєРѕРЅС†Р° РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
							( $lookDate->equals($objEventDate) || $lookDate->isLater($objEventDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
							( null === $objEventUtilDate || $lookDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
							( null === $objRrule->getCount() || $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
						)
						{
							/**
							 * @todo BYDAY Рё BYMONTHDAY РґР»СЏ С‚РёРїР° MONTHLY СЏРІР»СЏРµС‚СЃСЏ СЂР°СЃС€РёСЂСЏСЋС‰РёРј, Р° РЅРµ РѕРіСЂР°РЅРёС‡РёРІР°СЋС‰РёРј
							 * РїРѕСЌС‚РѕРјСѓ РґСѓРјР°СЋ РЅРµ РЅР°РґРѕ РїСЂРѕРІРµСЂСЏС‚СЊ checkByDay Рё checkByMonthDay
							 * @todo РЅР°РґРѕ Р»Рё РїСЂРѕРІРµСЂСЏС‚СЊ Р·РґРµСЃСЊ checkByDay ? С‚.Рє. СЃРјРѕС‚СЂРёРј РїРѕ BYMONTHDAY
							 */
							if (
								//( $this->checkByDay($objRrule, $lookDate) ) && // РґРµРЅСЊ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYDAY
								//( $this->checkByMonthDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYMONTHDAY РёР»Рё BYMONTHDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( $this->checkByYearDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РІ РіРѕРґСѓ РїРѕРїР°РґР°РµС‚ РІ BYYEARDAY РёР»Рё BYYEARDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( $this->checkByWeekNo($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYWEEKNO РёР»Рё BYWEEKNO РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								( $this->checkByMonth($objRrule, $lookDate) ) // РЅРѕРјРµСЂ РјРµСЃСЏС†Р° РїРѕРїР°РґР°РµС‚ РІ BYMONTH РёР»Рё BYMONTH РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
							)
							{
							   /**
								 * Handle BYSECOND, BYMINUTE, BYHOUR
								 */
								foreach ( $objRrule->getByHour() as $_hour ) {
									foreach ( $objRrule->getByMinute() as $_minute ) {
										foreach ( $objRrule->getBySecond() as $_second ) {
											$objTmpDate = new Zend_Date($lookDate->toString('yyyy-MM-dd').'T'.sprintf('%02d',$_hour).sprintf('%02d',$_minute).sprintf('%02d',$_second), Zend_Date::ISO_8601);
											if (
												( $objTmpDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
												( $objTmpDate->equals($event->getDtstart()) || $objTmpDate->isLater($event->getDtstart()) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
												( null === $objEventUtilDate || $objTmpDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
												( null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
											)
											{
												if ( false == ($isExDate = $this->isExDates($lookDate, $objTmpDate, $event)) && ($objTmpDate->equals($objPeriodDtstart) || $objTmpDate->isLater($objPeriodDtstart)) ) {
													$this->addToDates($lookDate, $objTmpDate, $event, $arrDates, $arrEventDates);
												}
												if ( !$isExDate ) $countOfPastDates ++ ;
											}
										}
									}
								}
							}
						}
					}
				}
			}
			$objObservedDate->add($objRrule->getInterval(), Zend_Date::MONTH);
			$objRrule = clone $objStoredRrule;
		}
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј Р·РѕРЅСѓ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ, СЃРѕС…СЂР°РЅРµРЅРЅСѓСЋ СЂР°РЅРµРµ
		 */
		date_default_timezone_set($defaultTimeZone);
	}

	/**
	 *
	 */
	private function checkYearlyRecur($event, &$arrDates, &$arrEventDates)
	{
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј Р·РѕРЅСѓ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ РІ С‚Сѓ, РІ РєРѕС‚РѕСЂРѕР№ Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		 */
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set( $event->getTimezone() );

		$objRrule = $event->getRrule();

		/**
		* С‚.Рє. РґР°С‚Р° РЅР°С‡Р°Р»Р° Рё РєРѕРЅС†Р° СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РІ РєР°Р»РµРЅРґР°СЂРµ РїРµСЂРёРѕРґР°
		* СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅР° РІ С‚Р°Р№РјР·РѕРЅРµ, РІ РєРѕС‚РѕСЂРѕР№ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РїРµСЂРёРѕРґ, С‚Рѕ
		* РїРµСЂРµРІРѕРґРёРј РёС… РІ С‚Р°Р№РјР·РѕРЅСѓ, РІ РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРѕ СЃРѕР±С‹С‚РёРµ
		*/
		$objPeriodDtstart   = clone $this->getPeriodDtstart();
		$objPeriodDtend     = clone $this->getPeriodDtend();
		$objPeriodDtstart->setTimezone($event->getTimezone());
		$objPeriodDtend->setTimezone($event->getTimezone());

		/**
		 * РѕРїСЂРµРґРµР»СЏРµРј, РІС…РѕРґРёС‚ Р»Рё СЃРѕР±С‹С‚РёРµ РІ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјС‹Р№ РїРµСЂРёРѕРґ РІСЂРµРјРµРЅРё
		 * С‚.Рµ. РЅРµ РёСЃС‚РµРєР»Рѕ Р»Рё РѕРЅРѕ Рє РЅР°С‡Р°Р»Сѓ СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР° РІСЂРµРјРµРЅРё
		 */

		/**
		 * РїСЂРµРІРѕРґРёРј Util РІ Р·РѕРЅСѓ, РІ РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		 */
        if ( null !== $objRrule->getUntil() ) {
            $defaultTimeZone = date_default_timezone_get();
            date_default_timezone_set($event->getTimezone());
            $objEventUtilDate = new Zend_Date($objRrule->getUntil(), Zend_Date::ISO_8601);
            date_default_timezone_set($defaultTimeZone);
            //$objEventUtilDate = clone $objRrule->getUntil();
            //$objEventUtilDate->setTimezone($event->getTimezone());
        } else $objEventUtilDate = null;

		/**
		* СЃСЂР°РІРЅРёРІР°РµРј РґР°С‚С‹ РЅР°С‡Р°Р»Р° РїРµСЂРёРѕРґР° Рё РґР°С‚Сѓ util РµСЃР»Рё РѕРЅРё РµСЃС‚СЊ
		* СЃСЂР°РІРЅРµРЅРёРµ РїСЂРѕРёСЃС…РѕРґРёС‚ РІ РѕРґРЅРѕР№ С‚Р°Р№РјР·РѕРЅРµ, РІ С‚РѕР№, РІ РєРѕС‚РѕСЂРѕР№
		* Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		*/
		if ( null !== $objEventUtilDate && $objPeriodDtstart->isLater($objEventUtilDate) ) {
		   return;
		}

		/**
		 * РёРЅРёС†РёР°Р»РёР·Р°С†РёСЏ РѕС‚СЃСѓС‚СЃС‚РІСѓСЋС‰РёС… РїР°СЂР°РјРµС‚СЂРѕРІ BYxxx
		 * Р Р°СЃС€РёСЂСЏСЋС‰РёРµ РјРѕРґРёС„РёРєР°С‚РѕСЂС‹ :
		 *  BYSECOND
		 *  BYMINUTE
		 *  BYHOUR
		 *  BYDAY
		 *  BYMONTHDAY
		 *  BYMONTH
		 *  BYYEARDAY
		 *  BYWEEKNO
		 * rfc 2445 :
		 * Information, not contained in the rule, necessary to determine the
		 * various recurrence instance start time and dates are derived from the
		 * Start Time (DTSTART) entry attribute. For example,
		 * "FREQ=YEARLY;BYMONTH=1" doesn't specify a specific day within the
		 * month or a time. This information would be the same as what is
		 * specified for DTSTART.
		 *
		 * @todo РїСЂРµРґРїРѕР»РѕРіР°РµРј, РµСЃР»Рё BYMONTH РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ, С‚Рѕ Р±РµСЂРµРј РµРіРѕ РёР· РґР°С‚С‹ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
		 */
		if ( null === $objRrule->getByMonth() )     $objRrule->setByMonth($event->getDtstart()->get(Zend_Date::MONTH_SHORT));
		if ( null === $objRrule->getBySecond() )    $objRrule->setBySecond($event->getDtstart()->get(Zend_Date::SECOND));
		if ( null === $objRrule->getByMinute() )    $objRrule->setByMinute($event->getDtstart()->get(Zend_Date::MINUTE));
		if ( null === $objRrule->getByHour() )      $objRrule->setByHour($event->getDtstart()->get(Zend_Date::HOUR));

		/**
		 * РґРµР»Р°РµРј РЅР°С‡Р°Р»Рѕ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° - РЅР°С‡Р°Р»Рѕ РіРѕРґР°,
		 * РЅР° РєРѕС‚РѕСЂРѕРј РЅР°С…РѕРґРёС‚СЊСЃСЏ РґР°С‚Р° РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
		 * РІСЂРµРјСЏ СѓСЃС‚Р°РЅР°РІР»РёРІР°РµС‚СЃСЏ РІ 00:00:00
		 */
		//FIXME;
		if ( $objRrule->getCount() ) {
			$objObservedDate = clone $event->getDtstart();
			$objObservedDate->setMonth(1);
			$objObservedDate->setDay(1);
			$objObservedDate->setTime('000000');
		} else {
			/**
			* PeriodDtstart СЂР°СЃСЃС‡РёС‚С‹РІР°РµС‚СЃСЏ РѕС‚ С‚Р°Р№РјР·РѕРЅС‹, РІ РєРѕС‚РѕСЂРѕР№ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РєР°Р»РµРЅРґР°СЂСЊ,
			* Р° РЅРµ С‚РѕР№, РІ РєРѕС‚РѕСЂР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ, РїРѕСЌС‚РѕРјСѓ РЅР°РґРѕ РµРіРѕ РїРµСЂРµРІРѕРґРёС‚СЊ РІ С‚Р°Р№РјР·РѕРЅСѓ, РІ
			* РєРѕС‚РѕСЂРѕР№ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
			* @todo
			* РЅР°РґРѕ СЃРјРѕС‚СЂРµС‚СЊ, РµСЃР»Рё РґР°С‚Р° СЃРѕР·РґР°РЅРёСЏ СЃРѕР±С‹С‚РёСЏ Р±РѕР»СЊС€Рµ РґР°С‚С‹ СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° -
			* СѓСЃС‚Р°РЅР°РІР»РёРІР°С‚СЊ РЅР°С‡Р°Р»Рѕ РєР°Рє РґР°С‚Р° РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ, С‡С‚РѕР±С‹ РёР·Р±РµР¶Р°С‚СЊ Р»РёС€РЅРёС… РёС‚РµСЂР°С†РёР№
			*/
            $objObservedDate = clone $event->getDtstart();
            $objObservedDate->setMonth(1);
            $objObservedDate->setDay(1);
            $objObservedDate->setTime('000000');
            while ( $objObservedDate->isEarlier($objPeriodDtstart) ) {
                $objObservedDate->add($objRrule->getInterval(), Zend_Date::YEAR);
            }
            $objObservedDate->sub($objRrule->getInterval(), Zend_Date::YEAR);
            /*
			$objObservedDate = clone $this->getPeriodDtstart();
			$objObservedDate->setTimezone($event->getTimezone());
			$objObservedDate->setMonth(1);
			$objObservedDate->setDay(1);
			$objObservedDate->setTime('000000');

			$objEvDate = clone $event->getDtstart();
			$objEvDate->setMonth(1);
			$objEvDate->setDay(1);
			$objEvDate->setTime('000000');

			if ( $objEvDate->isLater($objObservedDate) )  {
				$objObservedDate = $objEvDate;
			}
            */
		}


		$objEventDate       = clone $event->getDtstart();
		$objEventDate->setTime('000000');

		/**
		 * РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° - Р·РЅР°С‡РµРЅРёРµ СЃ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ Рё РґРѕ РєРѕРЅС†Р° СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР°
		 * - РїРѕРєР° РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ, С‡РµРј РґР°С‚Р° РєРѕРЅС†Р° СѓРєР°Р·Р°РЅРЅРѕРіРѕ РїРµСЂРёРѕРґР° < Р� >
		 * - РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ, С‡РµРј Р·РЅР°С‡РµРЅРёРµ UNTIL (РµСЃР»Рё РѕРЅРѕ РµСЃС‚СЊ) < Р� >
		 * - РєРѕР»РёС‡РµСЃС‚РІРѕ РїСЂРѕРёР·РѕС€РµРґС€РёС… СЃРѕР±С‹С‚РёР№ РјРµРЅСЊС€Рµ, С‡РµРј COUNT (РµСЃР»Рё РѕРЅРѕ РµСЃС‚СЊ)
		 */
		$isObservedDateEarlierUntil = true;
		$countOfPastDatesPassed     = true;
		$countOfPastDates           = 0;
		while ( $objObservedDate->isEarlier($objPeriodDtend) && $isObservedDateEarlierUntil && $countOfPastDatesPassed ) {

			/**
			 * РµСЃР»Рё РЅРµС‚ UNTIL РёР»Рё РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РґР°С‚С‹ UNTIL СЃРѕР±С‹С‚РёСЏ < Р� >
			 * РµСЃР»Рё РЅРµС‚ UNTIL РёР»Рё РЅР°Р±Р»СЋРґР°РµРјР°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РґР°С‚С‹ UNTIL СЃРѕР±С‹С‚РёСЏ < Р� >
			 * РЅРµС‚ COUNT РёР»Рё COUNT РјРµРЅСЊС€Рµ РїСЂРѕРёР·РѕС€РµРґС€РёС… СЃРѕР±С‹С‚РёР№
			 */
			if (
				(null === $objEventUtilDate || $isObservedDateEarlierUntil = $objObservedDate->isEarlier($objEventUtilDate)) &&
				(null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount())
			)
			{
				$objStoredRrule = clone $objRrule;
				/**
				 * Handle BYMONTH if defined
				 */
				if ( null !== $objRrule->getByMonth() ) {
					/**
					 * foreach bymonth value - create date and check it
					 */
					foreach ( $objRrule->getByMonth() as $byMonthCurrent ) {
						/**
						 * Handle BYMONTHDAY if defined
						 */
						if ( null !== $objRrule->getByMonthDay() ) {
							$objByMonthDate = new Zend_Date($objObservedDate->get(Zend_Date::YEAR).'-'.sprintf('%02d', $byMonthCurrent).'-01', Zend_Date::ISO_8601);
							/**
							 * foreach bymonthday value - create date and check it
							 */
							foreach ( $objRrule->getByMonthDay() as $MonthDayCurrent ) {
								/**
								 * РѕРїСЂРµРґРµР»СЏРµРј СЃРјРµС‰РµРЅРёРµ, РѕР»РѕР¶РёС‚РµР»СЊРЅРѕРµ РёР»Рё РѕС‚СЂРёС†Р°С‚РµР»СЊРЅРѕРµ
								 * Рё С„РѕСЂРјРёСЂСѓРµРј РґР°С‚Сѓ
								 */
								if ( substr($MonthDayCurrent, 0, 1) == '-' ) {
									$lookDate = $objByMonthDate->get(Zend_Date::MONTH_DAYS) + $MonthDayCurrent + 1;
									$lookDate = $objObservedDate->get(Zend_Date::YEAR).'-'.sprintf('%02d', $byMonthCurrent).'-'.sprintf('%02d', $lookDate);
								} else {
                                    /**
                                    * РњРѕР¶РµРј РїРѕР»СѓС‡РёС‚СЊ РЅРµРІР°Р»РёРґРЅСѓСЋ РґР°С‚Сѓ, РЅР°РїСЂРёРјРµСЂ 29 С„РµРІСЂР°СЏР» РіРѕРґР°, РІ РєРѕС‚РѕСЂРѕРј 28 РґРЅРµР№ РІ С„РµРІСЂР°Р»Рµ
                                    */
									$lookDate = $objObservedDate->get(Zend_Date::YEAR).'-'.sprintf('%02d', $byMonthCurrent).'-'.sprintf('%02d', $MonthDayCurrent);
								}
								$lookDate = new Zend_Date($lookDate, Zend_Date::ISO_8601);
								if (
									( $lookDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РєРѕРЅС†Р° РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
									( $lookDate->equals($objEventDate) || $lookDate->isLater($objEventDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
									( null === $objEventUtilDate || $lookDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
									( null === $objRrule->getCount() || $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
								)
								{
									/**
									 * @todo РЅСѓР¶РЅРѕ РЅР°Р№С‚Рё РёРЅРґРµРєСЃ С‚РµРєСѓС‰РµРіРѕ РґРЅСЏ РЅРµРґРµР»Рё
									 * С‡С‚РѕР±С‹ РµРіРѕ РїРѕРґСЃС‚РІРёС‚СЊ РІ checkByDay
									 */
									if (
										( $this->checkByDay($objRrule, $lookDate) ) && // РґРµРЅСЊ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYDAY
										( $this->checkByYearDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РІ РіРѕРґСѓ РїРѕРїР°РґР°РµС‚ РІ BYYEARDAY РёР»Рё BYYEARDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
										( $this->checkByWeekNo($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYWEEKNO РёР»Рё BYWEEKNO РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
                                        ( $this->checkByMonthDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYMONTHDAY РёР»Рё BYMONTHDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
										( $this->checkByMonth($objRrule, $lookDate) ) // РЅРѕРјРµСЂ РјРµСЃСЏС†Р° РїРѕРїР°РґР°РµС‚ РІ BYMONTH РёР»Рё BYMONTH РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
									)
									{
									   /**
										 * Handle BYSECOND, BYMINUTE, BYHOUR
										 */
										foreach ( $objRrule->getByHour() as $_hour ) {
											foreach ( $objRrule->getByMinute() as $_minute ) {
												foreach ( $objRrule->getBySecond() as $_second ) {
													$objTmpDate = new Zend_Date($lookDate->toString('yyyy-MM-dd').'T'.sprintf('%02d',$_hour).sprintf('%02d',$_minute).sprintf('%02d',$_second), Zend_Date::ISO_8601);
													if (
														( $objTmpDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
														( $objTmpDate->equals($event->getDtstart()) || $objTmpDate->isLater($event->getDtstart()) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
														( null === $objEventUtilDate || $objTmpDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
														( null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
													)
													{
														if ( false == ($isExDate = $this->isExDates($lookDate, $objTmpDate, $event)) && ($objTmpDate->equals($objPeriodDtstart) || $objTmpDate->isLater($objPeriodDtstart)) ) {
															$this->addToDates($lookDate, $objTmpDate, $event, $arrDates, $arrEventDates);
														}
														if ( !$isExDate ) $countOfPastDates ++ ;
													}
												}
											}
										}
									}
								}

							}
						}
						/**
						 * Handle BYDAY if defined
						 * @todo РїСЂРµРґРїРѕР»РѕРіР°РµС‚СЃСЏ, РµСЃР»Рё СѓРєР°Р·Р°РЅРѕ СЃРјРµС‰РµРЅРёРµ, С‚Рѕ РѕРЅРѕ
						 * СЂР°СЃСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РєР°Рє СЃРјРµС‰РµРЅРёРµ РґРЅСЏ РЅРµРґРµР»Рё РІ Р“РћР”РЈ (Р° РЅРµ РІ РјРµСЃСЏС†Рµ) С‚.Рє. РёРЅС‚РµСЂРІР°Р» YEARLY
						 * С‚.Рµ. 2MO - 2-РѕР№ РїРѕРЅРµРґРµР»СЊРЅРёРє РіРѕРґР°
						 * РєР°СЃ 2445 Р–
						 * Each BYDAY value can also be preceded by a positive (+n) or negative
						 * (-n) integer. If present, this indicates the nth occurrence of the
						 * specific day within the MONTHLY or YEARLY RRULE. For example, within
						 * a MONTHLY rule, +1MO (or simply 1MO) represents the first Monday
						 * within the month, whereas -1MO represents the last Monday of the
						 * month. If an integer modifier is not present, it means all days of
						 * this type within the specified frequency. For example, within a
						 * MONTHLY rule, MO represents all Mondays within the month.
						 */
						elseif ( null !== $objRrule->getByDay() ) {
							foreach ( $objRrule->getByDayClear() as $byDayCurrent ) {
								$weekdaysInYear         = Warecorp_ICal_Event_List::getWeekdaysInYear($objObservedDate, $byDayCurrent);
								$objMonthStartDate      = new Zend_Date($objObservedDate->get(Zend_Date::YEAR).'-'.sprintf('%02d', $byMonthCurrent).'-01', Zend_Date::ISO_8601);
								$objMonthEndDate        = new Zend_Date($objObservedDate->get(Zend_Date::YEAR).'-'.sprintf('%02d', $byMonthCurrent).'-'.$objMonthStartDate->get(Zend_Date::MONTH_DAYS), Zend_Date::ISO_8601);
								$objFirstWeekdayInYear  = Warecorp_ICal_Event_List::getFirstWeekdayOfYear($objObservedDate, $byDayCurrent, $event->getTimezone());
								/**
								 * РґРµР»Р°РµРј РґР°С‚Сѓ РЅР°С‡Р°Р»Р° СЂР°СЃСЃРјР°С‚СЂРёРІР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР° - РїРµСЂРІС‹Р№ $byDayCurrent РјРµСЃСЏС†Р°
								 */
								$lookDate = Warecorp_ICal_Event_List::getFirstWeekdayOfMonth($objMonthStartDate, $byDayCurrent, $event->getTimezone());

								while ( $lookDate->isEarlier($objMonthEndDate) ) {
									if (
										( $lookDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РєРѕРЅС†Р° РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
										( $lookDate->equals($objEventDate) || $lookDate->isLater($objEventDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
										( null === $objEventUtilDate || $lookDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
										( null === $objRrule->getCount() || $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
									)
									{
										$byDayCurrentIndex = $lookDate->get() - $objFirstWeekdayInYear->get();
										$byDayCurrentIndex = $byDayCurrentIndex / ( 60 * 60 * 24 * 7 ) + 1;
										if (
											( $this->checkByDay($objRrule, $lookDate, $byDayCurrentIndex, $weekdaysInYear) ) && // РґРµРЅСЊ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYDAY
											( $this->checkByMonthDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYMONTHDAY РёР»Рё BYMONTHDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
											( $this->checkByYearDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РІ РіРѕРґСѓ РїРѕРїР°РґР°РµС‚ РІ BYYEARDAY РёР»Рё BYYEARDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
											( $this->checkByWeekNo($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYWEEKNO РёР»Рё BYWEEKNO РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
											( $this->checkByMonth($objRrule, $lookDate) ) // РЅРѕРјРµСЂ РјРµСЃСЏС†Р° РїРѕРїР°РґР°РµС‚ РІ BYMONTH РёР»Рё BYMONTH РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
										)
										{
										   /**
											 * Handle BYSECOND, BYMINUTE, BYHOUR
											 */
											foreach ( $objRrule->getByHour() as $_hour ) {
												foreach ( $objRrule->getByMinute() as $_minute ) {
													foreach ( $objRrule->getBySecond() as $_second ) {
														$objTmpDate = new Zend_Date($lookDate->toString('yyyy-MM-dd').'T'.sprintf('%02d',$_hour).sprintf('%02d',$_minute).sprintf('%02d',$_second), Zend_Date::ISO_8601);
														if (
															( $objTmpDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
															( $objTmpDate->equals($event->getDtstart()) || $objTmpDate->isLater($event->getDtstart()) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
															( null === $objEventUtilDate || $objTmpDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
															( null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
														)
														{
															if ( false == ($isExDate = $this->isExDates($lookDate, $objTmpDate, $event)) && ($objTmpDate->equals($objPeriodDtstart) || $objTmpDate->isLater($objPeriodDtstart)) ) {
																$this->addToDates($lookDate, $objTmpDate, $event, $arrDates, $arrEventDates);
															}
															if ( !$isExDate ) $countOfPastDates ++ ;
														}
													}
												}
											}
										}
									}
									$lookDate->add(1, Zend_Date::WEEK);
								}
							}
						}
						/**
						 * BYMONTH - defined
						 * BYMONTHDAY and BYDAY - not defined
						 * Handle DEFAULT
						 */
						else {
							/**
							 * @todo РµСЃР»Рё BYMONTHDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ, Р±РµСЂРµРј РµРіРѕ РёР· РґР°С‚С‹ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
							 */
							$objRrule->setByMonthDay($event->getDtstart()->get(Zend_Date::DAY_SHORT));

							$objByMonthDate = new Zend_Date($objObservedDate->get(Zend_Date::YEAR).'-'.sprintf('%02d', $byMonthCurrent).'-01', Zend_Date::ISO_8601);
							/**
							 * foreach bymonthday value - create date and check it
							 */
							foreach ( $objRrule->getByMonthDay() as $MonthDayCurrent ) {
								if ( substr($MonthDayCurrent, 0, 1) == '-' ) {
									$lookDate = $objByMonthDate->get(Zend_Date::MONTH_DAYS) + $MonthDayCurrent + 1;
									$lookDate = $objObservedDate->get(Zend_Date::YEAR).'-'.sprintf('%02d', $byMonthCurrent).'-'.sprintf('%02d', $lookDate);
								} else {
									$lookDate = $objObservedDate->get(Zend_Date::YEAR).'-'.sprintf('%02d', $byMonthCurrent).'-'.sprintf('%02d', $MonthDayCurrent);
								}
								$lookDate = new Zend_Date($lookDate, Zend_Date::ISO_8601);
								if (
									( $lookDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РєРѕРЅС†Р° РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
									( $lookDate->equals($objEventDate) || $lookDate->isLater($objEventDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
									( null === $objEventUtilDate || $lookDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
									( null === $objRrule->getCount() || $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
								)
								{
									if (
										( $this->checkByDay($objRrule, $lookDate) ) && // РґРµРЅСЊ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYDAY
										( $this->checkByYearDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РІ РіРѕРґСѓ РїРѕРїР°РґР°РµС‚ РІ BYYEARDAY РёР»Рё BYYEARDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
										( $this->checkByWeekNo($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYWEEKNO РёР»Рё BYWEEKNO РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
                                        ( $this->checkByMonthDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYMONTHDAY РёР»Рё BYMONTHDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
										( $this->checkByMonth($objRrule, $lookDate) ) // РЅРѕРјРµСЂ РјРµСЃСЏС†Р° РїРѕРїР°РґР°РµС‚ РІ BYMONTH РёР»Рё BYMONTH РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
									)
									{
									   /**
										 * Handle BYSECOND, BYMINUTE, BYHOUR
										 */
										foreach ( $objRrule->getByHour() as $_hour ) {
											foreach ( $objRrule->getByMinute() as $_minute ) {
												foreach ( $objRrule->getBySecond() as $_second ) {
													$objTmpDate = new Zend_Date($lookDate->toString('yyyy-MM-dd').'T'.sprintf('%02d',$_hour).sprintf('%02d',$_minute).sprintf('%02d',$_second), Zend_Date::ISO_8601);
													if (
														( $objTmpDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
														( $objTmpDate->equals($event->getDtstart()) || $objTmpDate->isLater($event->getDtstart()) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
														( null === $objEventUtilDate || $objTmpDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
														( null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
													)
													{
														if ( false == ($isExDate = $this->isExDates($lookDate, $objTmpDate, $event)) && ($objTmpDate->equals($objPeriodDtstart) || $objTmpDate->isLater($objPeriodDtstart)) ) {
															$this->addToDates($lookDate, $objTmpDate, $event, $arrDates, $arrEventDates);
														}
														if ( !$isExDate ) $countOfPastDates ++ ;
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

				/**
				 * Handle BYYEARDAY
				 */
				if ( null !== $objRrule->getByYearDay() ) {
					/**
					 * foreach byyearday value - create date adn check it
					 */
					foreach ( $objRrule->getByYearDay() as $byYearDayCurrent ) {
						if ( substr($byYearDayCurrent, 0, 1) == '-' ) {
							$byYearDayCurrent = Warecorp_ICal_Event_List::getDaysInYear($objObservedDate) + $byYearDayCurrent + 1;
						} else {
							$byYearDayCurrent --;
						}
						$lookDate = clone $objObservedDate;
						$lookDate->add($byYearDayCurrent, Zend_Date::DAY);
						if (
							( $lookDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РєРѕРЅС†Р° РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
							( $lookDate->equals($objEventDate) || $lookDate->isLater($objEventDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
							( null === $objEventUtilDate || $lookDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РЅРµРґРµР»Рё РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
							( null === $objRrule->getCount() || $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
						)
						{
							if (
								//( $this->checkByDay($objRrule, $lookDate) ) && // РґРµРЅСЊ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYDAY
								//( $this->checkByMonthDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYMONTHDAY РёР»Рё BYMONTHDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								//( $this->checkByYearDay($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РґРЅСЏ РІ РіРѕРґСѓ РїРѕРїР°РґР°РµС‚ РІ BYYEARDAY РёР»Рё BYYEARDAY РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								//( $this->checkByWeekNo($objRrule, $lookDate) ) && // РЅРѕРјРµСЂ РЅРµРґРµР»Рё РїРѕРїР°РґР°РµС‚ РІ BYWEEKNO РёР»Рё BYWEEKNO РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								//( $this->checkByMonth($objRrule, $lookDate) ) // РЅРѕРјРµСЂ РјРµСЃСЏС†Р° РїРѕРїР°РґР°РµС‚ РІ BYMONTH РёР»Рё BYMONTH РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
								true
							)
							{
							   /**
								 * Handle BYSECOND, BYMINUTE, BYHOUR
								 */
								foreach ( $objRrule->getByHour() as $_hour ) {
									foreach ( $objRrule->getByMinute() as $_minute ) {
										foreach ( $objRrule->getBySecond() as $_second ) {
											$objTmpDate = new Zend_Date($lookDate->toString('yyyy-MM-dd').'T'.sprintf('%02d',$_hour).sprintf('%02d',$_minute).sprintf('%02d',$_second), Zend_Date::ISO_8601);
											if (
												( $objTmpDate->isEarlier($objPeriodDtend) ) &&  // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РЅР°Р±Р»СЋРґР°РµРјРѕРіРѕ РїРµСЂРёРѕРґР°
												( $objTmpDate->equals($event->getDtstart()) || $objTmpDate->isLater($event->getDtstart()) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° Р±РѕР»СЊС€Рµ РёР»Рё СЂР°РІРЅР° РґР°С‚Рµ РЅР°С‡Р°Р»Р° СЃРѕР±С‹С‚РёСЏ
												( null === $objEventUtilDate || $objTmpDate->isEarlier($objEventUtilDate) ) && // С‚РµРєСѓС‰Р°СЏ РґР°С‚Р° РјРµРЅСЊС€Рµ РїРµСЂРёРѕРґР° UNTIL РёР»Рё UNTIL РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅ
												( null === $objRrule->getCount() || $countOfPastDatesPassed = $countOfPastDates < $objRrule->getCount() ) //  РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРІС‚РѕСЂРµРЅРёР№ РјРµРЅСЊС€Рµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРЅРѕРіРѕ РІ COUNT РёР»Рё COUNT РЅРµ РѕРїСЂРµРґРµР»РµРЅ
											)
											{
												if ( false == ($isExDate = $this->isExDates($lookDate, $objTmpDate, $event)) && ($objTmpDate->equals($objPeriodDtstart) || $objTmpDate->isLater($objPeriodDtstart)) ) {
													$this->addToDates($lookDate, $objTmpDate, $event, $arrDates, $arrEventDates);
												}
												if ( !$isExDate ) $countOfPastDates ++ ;
											}
										}
									}
								}
							}
						}

					}
				}
				/**
				 * Handle BYWEEKNO
				 */

			}
			$objObservedDate->add($objRrule->getInterval(), Zend_Date::YEAR);
			$objRrule = clone $objStoredRrule;
		}
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј Р·РѕРЅСѓ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ, СЃРѕС…СЂР°РЅРµРЅРЅСѓСЋ СЂР°РЅРµРµ
		 */
		date_default_timezone_set($defaultTimeZone);
	}

	/*
	+------------------------------------------------------------
	|
	|   BYxxx CHECK FUNCTIONS
	|
	+------------------------------------------------------------
	*/

	/**
	 * The BYDAY rule part specifies a COMMA character (US-ASCII decimal 44)
	 * separated list of days of the week; MO indicates Monday; TU indicates
	 * Tuesday; WE indicates Wednesday; TH indicates Thursday; FR indicates
	 * Friday; SA indicates Saturday; SU indicates Sunday.

	 * Each BYDAY value can also be preceded by a positive (+n) or negative
	 * (-n) integer. If present, this indicates the nth occurrence of the
	 * specific day within the MONTHLY or YEARLY RRULE. For example, within
	 * a MONTHLY rule, +1MO (or simply 1MO) represents the first Monday
	 * within the month, whereas -1MO represents the last Monday of the
	 * month. If an integer modifier is not present, it means all days of
	 * this type within the specified frequency. For example, within a
	 * MONTHLY rule, MO represents all Mondays within the month.
	*/
	private function checkByDay($objRrule, Zend_Date $checkedDate, $weekDayIndex = null, $weekdaysCount = null)
	{
		/**
		 * РµСЃР»Рё РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ BYDAY - РІРѕР·РІСЂР°С‰Р°РµРј true
		 */
		if ( null === $objRrule->getByDay() ) return true;

		/**
		 * РїРѕР»СѓС‡Р°РµРј РґРµРЅСЊ РЅРµРґРµР»Рё (SU|MO|TU|WE|TH|FR|SA) РІ С„РѕСЂРјР°С‚Рµ РїРѕ rfc 2445 РґР»СЏ РїСЂРѕРІРµСЂСЏРµРјРѕР№ РґР°С‚С‹
		 */
		$byDayNumber = $checkedDate->get(Zend_Date::WEEKDAY_DIGIT);
		$byDay2Chars = Warecorp_ICal_Event_List::convertWeekdayDigitTo2Chars($byDayNumber);

		switch ( $objRrule->getFreq() ) {
			case Warecorp_ICal_Rrule_Enum_Freq::DAILY :
			case Warecorp_ICal_Rrule_Enum_Freq::WEEKLY :
				/**
				 * РµСЃР»Рё СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ BYDAY - РїСЂРѕРІРµСЂСЏРµРј
				 * @todo РґР»СЏ С‚РёРїР° DAILY Рё WEEKLY РЅРµ РїРѕРґРґРµСЂР¶РёРІР°РµРј [-]N(SU|MO|TU|WE|TH|FR|SA) С„РѕСЂРјР°С‚, С‚РѕР»СЊРєРѕ (SU|MO|TU|WE|TH|FR|SA)
				 */
				if ( $objRrule->isDayRelative() ) {
					foreach ( $objRrule->getByDay() as $_day2chars ) {
						if ( preg_match('/^[\-+]{0,1}\d{1,2}(.*?)$/', $_day2chars, $match) ) {
							$_day2chars = $match[1];
						}
						if ( $_day2chars == $byDay2Chars ) return true;
					}
				} else {
					if ( in_array($byDay2Chars, $objRrule->getByDay()) ) return true;
				}
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::MONTHLY :
				/**
				 * РµСЃР»Рё СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ BYDAY - РїСЂРѕРІРµСЂСЏРµРј
				 * @todo РґР»СЏ С‚РёРїР° MONTHLY РїРѕРґРґРµСЂР¶РёРІР°РµРј [-]N(SU|MO|TU|WE|TH|FR|SA) С„РѕСЂРјР°С‚
				 */
				if ( $objRrule->isDayRelative() ) {
					foreach ( $objRrule->getByDay() as $_day2chars ) {
						if ( preg_match('/^([\-+]{0,1}\d{1,2})(.*?)$/', $_day2chars, $match) ) {
							$_number    = floor($match[1]);     // - СЃРјРµС‰РµРЅРёРµ
							$_day2chars = $match[2];            // - РґРµРЅСЊ РЅРµРґРµР»Рё
						}
						if ( $_day2chars == $byDay2Chars ) {
							if ( $_number >= 1 && $weekDayIndex == $_number ) return true;
							elseif ( $_number <= -1 && $weekDayIndex == ($weekdaysCount + $_number + 1) ) return true;
						}
					}
				}
				elseif ( null != $objRrule->getBySetPos() ) {
					foreach ( $objRrule->getByDay() as $_day2chars ) {
						if ( preg_match('/^([\-+]{0,1}\d{1,2})(.*?)$/', $_day2chars, $match) ) {
							$_day2chars = $match[2];            // - РґРµРЅСЊ РЅРµРґРµР»Рё
						}
						if ( $_day2chars == $byDay2Chars ) {
							foreach ( $objRrule->getBySetPos() as $bySetPosCurrent  ) {
								if ( $bySetPosCurrent >= 1 && $weekDayIndex == $bySetPosCurrent ) return true;
								elseif ( $bySetPosCurrent <= -1 && $weekDayIndex == ($weekdaysCount + $bySetPosCurrent + 1) ) return true;
							}
						}
					}
				}
				else {
					if ( in_array($byDay2Chars, $objRrule->getByDay()) ) return true;
				}
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::YEARLY :
				/**
				 * РµСЃР»Рё СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ BYDAY - РїСЂРѕРІРµСЂСЏРµРј
				 * @todo РґР»СЏ С‚РёРїР° MONTHLY РїРѕРґРґРµСЂР¶РёРІР°РµРј [-]N(SU|MO|TU|WE|TH|FR|SA) С„РѕСЂРјР°С‚
				 */
				if ( $objRrule->isDayRelative() ) {
					foreach ( $objRrule->getByDay() as $_day2chars ) {
						if ( preg_match('/^([\-+]{0,1}\d{1,2})(.*?)$/', $_day2chars, $match) ) {
							$_number    = floor($match[1]);     // - СЃРјРµС‰РµРЅРёРµ
							$_day2chars = $match[2];            // - РґРµРЅСЊ РЅРµРґРµР»Рё
						}
						if ( $_day2chars == $byDay2Chars ) {
							if ( $_number >= 1 && $weekDayIndex == $_number ) return true;
							elseif ( $_number <= -1 && $weekDayIndex == ($weekdaysCount + $_number + 1) ) return true;
						}
					}
				}
				elseif ( null != $objRrule->getBySetPos() ) {
					foreach ( $objRrule->getByDay() as $_day2chars ) {
						if ( preg_match('/^([\-+]{0,1}\d{1,2})(.*?)$/', $_day2chars, $match) ) {
							$_day2chars = $match[2];            // - РґРµРЅСЊ РЅРµРґРµР»Рё
						}
						if ( $_day2chars == $byDay2Chars ) {
							foreach ( $objRrule->getBySetPos() as $bySetPosCurrent  ) {
								if ( $bySetPosCurrent >= 1 && $weekDayIndex == $bySetPosCurrent ) return true;
								elseif ( $bySetPosCurrent <= -1 && $weekDayIndex == ($weekdaysCount + $bySetPosCurrent + 1) ) return true;
							}
						}
					}
				}
				else {
					if ( in_array($byDay2Chars, $objRrule->getByDay()) ) return true;
				}
				break;
		}
		return false;

	}

	/**
	 * The BYMONTHDAY rule part specifies a COMMA character (ASCII decimal
	 * 44) separated list of days of the month. Valid values are 1 to 31 or
	 * -31 to -1. For example, -10 represents the tenth to the last day of
	 * the month.
	*/
	private function checkByMonthDay($objRrule, $checkedDate)
	{
		/**
		 * РµСЃР»Рё РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ BYMONTHDAY - РІРѕР·РІСЂР°С‰Р°РµРј true
		 */
		if ( null === $objRrule->getByMonthDay() ) return true;
		/**
		 * РµСЃР»Рё СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ BYMONTHDAY - РїСЂРѕРІРµСЂСЏРµРј
		 */
		if ( $objRrule->isMonthDayRelative() ) {
			$daysInMonth = $checkedDate->get(Zend_Date::MONTH_DAYS);
			foreach ( $objRrule->getByMonthDay() as $_dayNo ) {
				if ( substr($_dayNo, 0, 1) == '-' ) {
					if ( ($daysInMonth + $_dayNo + 1) == $checkedDate->get(Zend_Date::DAY_SHORT) ) return true;
				} else {
					if ( $_dayNo == $checkedDate->get(Zend_Date::DAY_SHORT) ) return true;
				}
			}
		} else {
			if ( in_array($checkedDate->get(Zend_Date::DAY_SHORT),$objRrule->getByMonthDay()) ) return true;
		}
		return false;

		/*
		switch ( $objRrule->getFreq() ) {
			case Warecorp_ICal_Rrule_Enum_Freq::DAILY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::WEEKLY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::MONTHLY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::YEARLY :
				break;
		}
		return false;
		*/
	}

	/**
	 * The BYYEARDAY rule part specifies a COMMA character (US-ASCII decimal
	 * 44) separated list of days of the year. Valid values are 1 to 366 or
	 * -366 to -1. For example, -1 represents the last day of the year
	 * (December 31st) and -306 represents the 306th to the last day of the
	 * year (March 1st).
	*/
	private function checkByYearDay($objRrule, $checkedDate)
	{
		/**
		 * РµСЃР»Рё РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ BYYEARDAY - РІРѕР·РІСЂР°С‰Р°РµРј true
		 */
		if ( null === $objRrule->getByYearDay() ) return true;
		/**
		 * РµСЃР»Рё СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ BYYEARDAY - РїСЂРѕРІРµСЂСЏРµРј
		 */
		if ( $objRrule->isYearDayRelative() ) {
			$daysInYear = Warecorp_ICal_Event_List::getDaysInYear($checkedDate);
			foreach ( $objRrule->getByYearDay() as $_dayNo ) {
				if ( substr($_dayNo, 0, 1) == '-' ) {
					if ( ($daysInYear + $_dayNo + 1) == $checkedDate->get(Zend_Date::DAY_OF_YEAR) ) return true;
				} else {
					if ( $_dayNo == $checkedDate->get(Zend_Date::DAY_OF_YEAR) + 1 ) return true;
				}
			}
		} else {
			if ( in_array($checkedDate->get(Zend_Date::DAY_OF_YEAR) + 1 ,$objRrule->getByYearDay()) ) return true;
		}
		return false;

		/*
		switch ( $objRrule->getFreq() ) {
			case Warecorp_ICal_Rrule_Enum_Freq::DAILY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::WEEKLY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::MONTHLY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::YEARLY :
				break;
		}
		return false;
		*/
	}

	/**
	 * The BYWEEKNO rule part specifies a COMMA character (US-ASCII decimal
	 * 44) separated list of ordinals specifying weeks of the year. Valid
	 * values are 1 to 53 or -53 to -1. This corresponds to weeks according
	 * to week numbering as defined in [ISO 8601]. A week is defined as a
	 * seven day period, starting on the day of the week defined to be the
	 * week start (see WKST). Week number one of the calendar year is the
	 * first week which contains at least four (4) days in that calendar
	 * year. This rule part is only valid for YEARLY rules. For example, 3
	 * represents the third week of the year.
	 * Note: Assuming a Monday week start, week 53 can only occur when
	 * Thursday is January 1 or if it is a leap year and Wednesday is
	 * January 1.
	 */
	private function checkByWeekNo($objRrule, $checkedDate)
	{
		/**
		 * РµСЃР»Рё BYWEEKNO РЅРµ СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ - РІРѕР·РІСЂР°С‰Р°РµРј true
		 */
		if ( null === $objRrule->getByWeekNo() ) return true;
		/**
		 * РµСЃР»Рё BYWEEKNO СѓСЃС‚Р°РЅРѕРІР»РµРЅРѕ - РїСЂРѕРІРµСЂСЏРµРј
		 */
		if ( $objRrule->isWeekNoRelative() ) {
			$weeksInYear = Warecorp_ICal_Event_List::getWeeksInYear($checkedDate);
			foreach ( $objRrule->getByWeekNo() as $_weelNo ) {
				if ( substr($_weelNo, 0, 1) == '-' ) {
					if ( ($weeksInYear + $_weelNo + 1) == $checkedDate->get(Zend_Date::WEEK) ) return true;
				} else {
					if ( $_weelNo == $checkedDate->get(Zend_Date::WEEK) ) return true;
				}
			}
		}
		else {
			if ( in_array($checkedDate->get(Zend_Date::WEEK), $objRrule->getByWeekNo()) ) return true;
		}
		return false;

		/*
		switch ( $objRrule->getFreq() ) {
			case Warecorp_ICal_Rrule_Enum_Freq::DAILY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::WEEKLY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::MONTHLY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::YEARLY :
				break;
		}
		return false;
		*/
	}

	/**
	 * The BYMONTH rule part specifies a COMMA character (US-ASCII decimal
	 * 44) separated list of months of the year. Valid values are 1 to 12.
	 * Note : can not be negative
	 */
	private function checkByMonth($objRrule, $checkedDate)
	{
		if ( null === $objRrule->getByMonth() || in_array($checkedDate->get(Zend_Date::MONTH_SHORT), $objRrule->getByMonth()) ) {
			return true;
		}
		return false;

		/*
		switch ( $objRrule->getFreq() ) {
			case Warecorp_ICal_Rrule_Enum_Freq::DAILY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::WEEKLY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::MONTHLY :
				break;
			case Warecorp_ICal_Rrule_Enum_Freq::YEARLY :
				break;
		}
		return false;
		*/
	}

	/*
	+------------------------------------------------------------
	|
	|
	|
	+------------------------------------------------------------
	*/

	/**
	 *
	 */
	private function isExDates(Zend_Date $objDate, Zend_Date $objTime, Warecorp_ICal_Event $objEvent)
	{
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј Р·РѕРЅСѓ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ РІ С‚Сѓ, РІ РєРѕС‚РѕСЂРѕР№ Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		 * РїСЂРµРґРїРѕР»РѕРіР°РµРј, С‡С‚Рѕ exdata С…СЂР°РЅРёС‚СЊСЃСЏ РІ Р±Р°Р·Рµ РєР°Рє РґР°С‚Р° РёР· С‚Р°Р№РјР·РѕРЅС‹, РІ РєРѕС‚РѕСЂРѕР№
		 * Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ. С‚.Рµ Рё СЃРѕР±С‹С‚РёРµ Рё exdata СЃРѕР·РґР°РЅС‹ РІ РѕРґРЅРѕР№ Р·РѕРЅРµ
		 */
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set( $objEvent->getTimezone() );
		$objViewdDate = new Zend_Date($objDate->toString('yyyy-MM-dd').'T'.$objTime->toString('HHmmss'), Zend_Date::ISO_8601);
		date_default_timezone_set($defaultTimeZone);
        if ( isset(self::$eventsExdateCache[$objEvent->getId()]) )
            return self::$eventsExdateCache[$objEvent->getId()]->isExDate($objViewdDate);
		else {
            self::$eventsExdateCache[$objEvent->getId()] = $objEvent->getExDates();
            return self::$eventsExdateCache[$objEvent->getId()]->isExDate($objViewdDate);
        }
	}
	/**
	 *
	 */
	private function addToDates(Zend_Date $objDate, Zend_Date $objTime, Warecorp_ICal_Event $objEvent, &$arrDates, &$arrEventDates)
	{
		/**
		 * СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј Р·РѕРЅСѓ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ РІ С‚Сѓ, РІ РєРѕС‚РѕСЂРѕР№ Р±С‹Р»Рѕ СЃРѕР·РґР°РЅРЅРѕ СЃРѕР±С‹С‚РёРµ
		 */
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set( $objEvent->getTimezone() );

		/**
		 * РєРѕРЅРІРµСЂС‚РёРј РґР°С‚Сѓ РІ С‚Р°Р№РјР·РѕРЅСѓ, РІ РєРѕС‚РѕСЂРѕР№ РїСЂРѕСЃРјР°С‚СЂРёРІР°РµС‚СЃСЏ РєР°Р»РµРЅРґР°СЂСЊ
		 */
		$objViewdDate = new Zend_Date($objDate->toString('yyyy-MM-dd').'T'.$objTime->toString('HHmmss'), Zend_Date::ISO_8601);
		$objViewdDate->setTimezone($this->getTimezone());
        preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2})$/", $objViewdDate->toString('yyyy-MM-dd HH:mm:ss'), $matches);
        
		$eventInfo                          = array();
		/**
		 * event information in current timezone
		 */
		$eventInfo['title']                 = $objEvent->getTitle();
		$eventInfo['id']                    = $objEvent->getId();
		$eventInfo['uid']                   = $objEvent->getUid();
		$eventInfo['year']                  = $matches[1];
		$eventInfo['month']                 = $matches[2];
		$eventInfo['day']                   = $matches[3];
		$eventInfo['time']                  = $matches[4];
        $eventInfo['duration']              = $objEvent->getDurationSec();
        /**
         * event information in event timezone
         * if event timezone isn't defined in UTC
         */        
        $eventInfo['original']              = array();
        $eventInfo['original']['timezone']  = $objDate->toString('zzzz');
        $eventInfo['original']['year']      = $objDate->toString('yyyy');
        $eventInfo['original']['month']     = $objDate->toString('MM');
        $eventInfo['original']['day']       = $objDate->toString('dd');
        $eventInfo['original']['time']      = $objDate->toString('HH:mm:ss');
        $eventInfo['original']['duration']  = $objEvent->getDurationSec();
        $eventInfo['original']['key_date']  = $objDate->toString('yyyy-MM-dd');
        $eventInfo['original']['key_time']  = ( $objEvent->isAllDay() ) ? 'allday' : $objDate->toString('HH:mm:ss');
        $eventInfo['original']['key_id']    = 'id_'.$objEvent->getId();

		if ( $objEvent->isAllDay() ) {
			$arrDates[$objViewdDate->toString('yyyy-MM-dd')]['allday']['id_'.$objEvent->getId()] = $eventInfo;
			$arrEventDates[$objViewdDate->toString('yyyy-MM-dd')]['allday']['id_'.$objEvent->getId()] = $eventInfo;
		} else {
			$arrDates[$objViewdDate->toString('yyyy-MM-dd')][$objViewdDate->toString('HH:mm:ss')]['id_'.$objEvent->getId()] = $eventInfo;
			$arrEventDates[$objViewdDate->toString('yyyy-MM-dd')][$objViewdDate->toString('HH:mm:ss')]['id_'.$objEvent->getId()] = $eventInfo;
		}
		date_default_timezone_set($defaultTimeZone);
	}

	/*
	+------------------------------------------------------------
	|
	|   DATE FUNCTIONS
	|
	+------------------------------------------------------------
	*/


	/**
	 * return date of first date in the week by
	 * @return obj Zend_Date
	 */
	static public function getDateFirstDayOfWeek(Zend_Date $dateOfAnyDay, $Wkst = 'MO', $timezone = 'UTC')
	{
		//  FIXME РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІРёР»СЊРЅРѕСЃС‚СЊ РїРµСЂРµРІРѕРґР° РІСЂРµРјРµРЅРё СЃРІСЏР·Р°РЅРЅР°СЏ СЃ strtotime
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set($timezone);

		$objTmpDate = clone $dateOfAnyDay;
		$objTmpDate->sub(1, Zend_Date::WEEK);
		$time = mktime($objTmpDate->toString('HH'), $objTmpDate->toString('mm'), $objTmpDate->toString('ss'), $objTmpDate->toString('MM'), $objTmpDate->toString('dd'), $objTmpDate->toString('yyyy'));

		$unix_time = strtotime('next '.Warecorp_ICal_Event_List::convert2CharsWeekdayToFull($Wkst), $time);
		$return = new Zend_Date($unix_time, Zend_Date::TIMESTAMP);

		date_default_timezone_set($defaultTimeZone);
		return $return;
	}

	/**
	 * РІРѕР·РІСЂР°С‰Р°РµС‚ РїРµСЂРІС‹Р№ РґРµРЅСЊ РјРµСЃСЏС†Р° РїРѕ РґРЅСЋ РЅРµРґРµР»Рё
	 * РЅР°РїСЂРёРјРµСЂ : РїСЂРµРІС‹Р№ РїРѕРЅРµРґРµР»СЊРЅРёРє, РїРµСЂРІС‹Р№ РІС‚РѕСЂРЅРёРє Рё С‚.Рґ.
	 */
	static public function getFirstWeekdayOfMonth(Zend_Date $dateOfAnyDay, $weekDay2Chars, $timezone = 'UTC')
	{
		//  FIXME РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІРёР»СЊРЅРѕСЃС‚СЊ РїРµСЂРµРІРѕРґР° РІСЂРµРјРµРЅРё СЃРІСЏР·Р°РЅРЅР°СЏ СЃ strtotime
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set($timezone);

		$objTmpDate = clone $dateOfAnyDay;
		$objTmpDate->setDay(1);
		$objTmpDate->sub(1, Zend_Date::HOUR);
		$time = mktime($objTmpDate->toString('HH'), $objTmpDate->toString('mm'), $objTmpDate->toString('ss'), $objTmpDate->toString('MM'), $objTmpDate->toString('dd'), $objTmpDate->toString('yyyy'));

		$unix_time = strtotime('first '.Warecorp_ICal_Event_List::convert2CharsWeekdayToFull($weekDay2Chars), $time);
		$return = new Zend_Date($unix_time, Zend_Date::TIMESTAMP);

		date_default_timezone_set($defaultTimeZone);
		return $return;
	}

	/**
	 * РІРѕР·РІСЂР°С‰Р°РµС‚ РїРµСЂРІС‹Р№ РґРµРЅСЊ РіРѕРґР° РїРѕ РґРЅСЋ РЅРµРґРµР»Рё
	 * РЅР°РїСЂРёРјРµСЂ : РїСЂРµРІС‹Р№ РїРѕРЅРµРґРµР»СЊРЅРёРє РіРѕРґР°, РїРµСЂРІС‹Р№ РІС‚РѕСЂРЅРёРє РіРѕРґР° Рё С‚.Рґ.
	 */
	static public function getFirstWeekdayOfYear(Zend_Date $dateOfAnyDay, $weekDay2Chars, $timezone = 'UTC')
	{
		//  FIXME РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІРёР»СЊРЅРѕСЃС‚СЊ РїРµСЂРµРІРѕРґР° РІСЂРµРјРµРЅРё СЃРІСЏР·Р°РЅРЅР°СЏ СЃ strtotime
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set($timezone);

		$objTmpDate = clone $dateOfAnyDay;
		$objTmpDate->setDay(1);
		$objTmpDate->setMonth(1);
		$objTmpDate->sub(1, Zend_Date::HOUR);
		$time = mktime($objTmpDate->toString('HH'), $objTmpDate->toString('mm'), $objTmpDate->toString('ss'), $objTmpDate->toString('MM'), $objTmpDate->toString('dd'), $objTmpDate->toString('yyyy'));

		$unix_time = strtotime('first '.Warecorp_ICal_Event_List::convert2CharsWeekdayToFull($weekDay2Chars), $time);
		$return = new Zend_Date($unix_time, Zend_Date::TIMESTAMP);

		date_default_timezone_set($defaultTimeZone);
		return $return;
	}

	/**
	 * РІРѕР·РІСЂР°С‰Р°РµС‚ СЃРјРµС‰РµРЅРёРµ РґРЅСЏ РЅРµРґРµР»Рё РїРѕ РѕС‚РЅРѕС€РµРЅРёСЋ Рє РїРµСЂРІРѕРјСѓ РґРЅСЋ РЅРµРґРµР»Рё
	 */
	static public function getOffsetByDay(Zend_Date &$objWeekStartDate, $byDayValue, $Wkst = 'MO')
	{
		$arrWeekdaysOffsetMO = array("MO" => 0, "TU" => 1, "WE" => 2, "TH" => 3, "FR" => 4, "SA" => 5, "SU" => 6);
		$objWeekStartDate->add($arrWeekdaysOffsetMO[$byDayValue], Zend_Date::DAY);
	}

	/**
	 * return max number of week of the year
	 * rfc 2445 :
	 * Note: Assuming a Monday week start, week 53 can only occur when
	 * Thursday is January 1 or if it is a leap year and Wednesday is
	 * January 1.
	 * @return int
	 */
	static public function getWeeksInYear(Zend_Date $dateOfAnyDay, $timezone = 'UTC')
	{
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set($timezone);

		$objTmpDate = clone $dateOfAnyDay;
		$objTmpDate->setMonth(12);
		$objTmpDate->setDay(31);

		if ( $objTmpDate->get(Zend_Date::WEEK) == 53 ) $return = 53;
		else $return = 52;

		date_default_timezone_set($defaultTimeZone);
		return $return;
	}

	/**
	 * РІРѕР·РІСЂР°С‰Р°РµС‚ РєРѕР»РёС‡РµСЃС‚РІРѕ РґРЅРµР№ РЅРµРґРёР»Рё РІ РіРѕРґСѓ
	 * С‚.Рµ. РєРѕР»РёС‡РµСЃС‚РІРѕ РїРѕРЅРµРґРµР»СЊРЅРёРєРѕРІ РІ РіРѕРґСѓ, РІС‚РѕСЂРЅРёРєРѕРІ РІ РіРѕРґСѓ Рё С‚.Рґ.
	 */
	static public function getWeekdaysInYear(Zend_Date $dateOfAnyDay, $weekDay2Chars, $timezone = 'UTC')
	{
		//  FIXME РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІРёР»СЊРЅРѕСЃС‚СЊ РїРµСЂРµРІРѕРґР° РІСЂРµРјРµРЅРё СЃРІСЏР·Р°РЅРЅР°СЏ СЃ strtotime
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set($timezone);

		$objTmpDate = clone $dateOfAnyDay;
		$objTmpDate->setDay(1);
		$objTmpDate->setMonth(1);
		$objTmpDate->sub(1, Zend_Date::HOUR);
		$time = mktime($objTmpDate->toString('HH'), $objTmpDate->toString('mm'), $objTmpDate->toString('ss'), $objTmpDate->toString('MM'), $objTmpDate->toString('dd'), $objTmpDate->toString('yyyy'));
		$unix_time_first = strtotime('first '.Warecorp_ICal_Event_List::convert2CharsWeekdayToFull($weekDay2Chars), $time);

		$objTmpDate->add(1, Zend_Date::HOUR);
		$objTmpDate->add(1, Zend_Date::YEAR);
		$time = mktime($objTmpDate->toString('HH'), $objTmpDate->toString('mm'), $objTmpDate->toString('ss'), $objTmpDate->toString('MM'), $objTmpDate->toString('dd'), $objTmpDate->toString('yyyy'));
		$unix_time_last = strtotime('last '.Warecorp_ICal_Event_List::convert2CharsWeekdayToFull($weekDay2Chars), $time);


		$delta = $unix_time_last - $unix_time_first;
		$delta = $delta / ( 60 * 60 * 24 * 7 ) + 1;

		date_default_timezone_set($defaultTimeZone);
		return $delta;
	}

	/**
	 * return number of days of certain year
	 */
	static public function getDaysInYear(Zend_Date $dateOfAnyDay, $timezone = 'UTC')
	{
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set($timezone);

		$objTmpDate = clone $dateOfAnyDay;
		$objTmpDate->setMonth(12);
		$objTmpDate->setDay(31);

		date_default_timezone_set($defaultTimeZone);
		return $objTmpDate->get(Zend_Date::DAY_OF_YEAR);
	}

	/**
	 * РІРѕР·РІСЂР°С‰Р°РµС‚ РєРѕР»РёС‡РµСЃС‚РІРѕ РґРЅРµР№ РЅРµРґРµР»Рё РІ РјРµСЃСЏС†Рµ
	 * С‚.Рµ. СЃРєРѕР»СЊРєРѕ РїРѕРЅРµРґРµР»СЊРЅРёРєРѕРІ, РІС‚РѕСЂРЅРёРєРѕРІ Рё С‚.Рґ.
	 * NOTE: РёР·РјРµРЅСЏРµС‚ $dateOfAnyDay
	 */
	static public function getWeekdaysInMonth(Zend_Date $dateOfAnyDay, $weekDay2Chars, $timezone = 'UTC')
	{
		//  FIXME РїСЂРѕРІРµСЂРёС‚СЊ РїСЂР°РІРёР»СЊРЅРѕСЃС‚СЊ РїРµСЂРµРІРѕРґР° РІСЂРµРјРµРЅРё СЃРІСЏР·Р°РЅРЅР°СЏ СЃ strtotime
		$defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set($timezone);

		$objTmpDate = clone $dateOfAnyDay;
		$objTmpDate->setDay(1);
		$objTmpDate->sub(1, Zend_Date::HOUR);
		$time = mktime($objTmpDate->toString('HH'), $objTmpDate->toString('mm'), $objTmpDate->toString('ss'), $objTmpDate->toString('MM'), $objTmpDate->toString('dd'), $objTmpDate->toString('yyyy'));
		$unix_time_first = strtotime('first '.Warecorp_ICal_Event_List::convert2CharsWeekdayToFull($weekDay2Chars), $time);

		$objTmpDate->add(1, Zend_Date::HOUR);
		$objTmpDate->add(1, Zend_Date::MONTH);
		$time = mktime($objTmpDate->toString('HH'), $objTmpDate->toString('mm'), $objTmpDate->toString('ss'), $objTmpDate->toString('MM'), $objTmpDate->toString('dd'), $objTmpDate->toString('yyyy'));
		$unix_time_last = strtotime('last '.Warecorp_ICal_Event_List::convert2CharsWeekdayToFull($weekDay2Chars), $time);


		$delta = $unix_time_last - $unix_time_first;
		$delta = $delta / ( 60 * 60 * 24 * 7 ) + 1;

		date_default_timezone_set($defaultTimeZone);
		return $delta;
	}

	/**
	 *
	 */
	static public function convertWeekdayDigitTo2Chars($digit)
	{
		$arrWeekday2Chars = array("SU", "MO", "TU", "WE", "TH", "FR", "SA");
		return $arrWeekday2Chars[$digit];
	}

	/**
	 *
	 */
	static public function convert2CharsWeekdayToFull($chars)
	{
		$arr2CharsToFull = array("SU" => "Sunday", "MO" => "Monday", "TU" => "Tuesday", "WE" => "Wednesday", "TH" => "Thursday", "FR" => "Friday", "SA" => "Saturday");
		return $arr2CharsToFull[$chars];
	}

    static public function createEvent($eventInfo, $currentTimezone)
    {
        $objEvent = null;
        if ( isset(self::$eventsCache[$eventInfo['id']]) ) {
            $objEvent = clone self::$eventsCache[$eventInfo['id']];
        } else {
            $objEvent = new Warecorp_ICal_Event($eventInfo['id']);
            self::$eventsCache[$eventInfo['id']] = $objEvent;
        }
        $objEvent->setTimezone($currentTimezone);
        $strDate = $eventInfo['year'].'-'.$eventInfo['month'].'-'.$eventInfo['day'].'T'.str_replace(':','',$eventInfo['time']);
        $objEvent->setDtstart($strDate);

        return $objEvent;
    }

    private function mergeMasterArrays($mainArray, $secodaryArray)
    {
        if ( is_array($secodaryArray) && sizeof($secodaryArray) ) {
            foreach ( $secodaryArray as $date => &$dates ) {
                foreach ( $dates as $time => &$times ) {
                    foreach ( $times as $eventKey => &$eventInfo ) {
                        if ( !isset($mainArray[$date]) || !isset($mainArray[$date][$time]) || !isset($mainArray[$date][$time][$eventKey]) ) {
                            $mainArray[$date][$time][$eventKey] = $eventInfo;
                        }
                    }
                }
            }
        }
        return $mainArray;
    }
}
