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

class BaseWarecorp_ICal_Rrule
{
	private $DbConn;
	private $id;
	private $eventId;
	private $freq;
	private $until;
	private $count;
	private $interval;
	private $bySecond;
	private $byMinute;
	private $byHour;
	private $byDay;
	private $byDayRelative         = false;
	private $byDayClear;
	private $byMonthDay;
	private $byMonthDayRelative    = false;
	private $byYearDay;
	private $byYearDayRelative     = false;
	private $byWeekNo;
	private $byWeekNoRelative      = false;
	private $byMonth;
	private $bySetPos;
	private $wkst;	
	private $objEvent;

	/**
	 *
	 */
	public function __construct($rruleId = null, Warecorp_ICal_Event $objEvent = null)
	{
		$this->DbConn = Zend_Registry::get('DB');
		if ( $this->DbConn === null ) throw new Warecorp_Exception('Database connection is not set.');

		if ( null !== $rruleId ) {
			$this->loadById($rruleId);
			if ( null !== $objEvent ) $this->setEvent($objEvent);
			/** 
			 * TODO: this is hot fix for events that already exist
			 * it fixs until date if event has THISANDFUTURE exdate
			 * ( if user choosed cancel event for feature dates )
			 * IT MUST BE DELETED in feature - when all events will work fine
			 * @author Artem Sukharev
			 */
			$extUntilDate = $this->getEvent()->getExDates()->getUntilDate();
			if ( null !== $extUntilDate && $this->until != $extUntilDate ) {
				$this->until = $extUntilDate;
				$this->updateUntilDate($extUntilDate);
				$this->getEvent()->getExDates()->deleteTHISANDFUTURE();
			}
		}
	}

	/**
	 *
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setId($newVal)
	{
		$this->id = $newVal;
		return $this;
	}

	/**
	 *
	 */
	public function getEventId()
	{
		if ( null === $this->eventId ) throw new Warecorp_ICal_Exception('Event ID is not set');
		return $this->eventId;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setEventId($newVal)
	{
		$this->eventId = $newVal;
	}

	public function getEvent()
	{
		if ( null === $this->objEvent ) {
			$this->objEvent = new Warecorp_ICal_Event($this->getEventId());
		}
		return $this->objEvent;
	}
	
	public function setEvent(Warecorp_ICal_Event $objEvent)
	{
		$this->objEvent = $objEvent;
		return $this;
	}
	
	/**
	 *
	 */
	public function getFreq()
	{
		if ( null === $this->freq ) throw new Warecorp_ICal_Exception('Freq is not set.');
		return $this->freq;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setFreq($newVal)
	{
		$this->freq = $newVal;
	}

	/**
	 *
	 */
	public function getUntil()
	{
		if ( null !== $this->until ) {
            return $this->until;
            /*
			$defaultTimeZone = date_default_timezone_get();
			date_default_timezone_set('UTC');
			$until = new Zend_Date($this->until, Zend_Date::ISO_8601);
			date_default_timezone_set($defaultTimeZone);
			return $until;
            */
		}
		return null;
	}

	/**
	 *
	 */
	private function getUntilValue()
	{
		return $this->until;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setUntil($newVal)
	{
		$this->until = $newVal;
	}

	/**
	 *
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setCount($newVal)
	{
		$this->count = $newVal;
	}

	/**
	 *
	 */
	public function getInterval()
	{
		if ( null === $this->interval ) $this->interval = 1;
		return $this->interval;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setInterval($newVal)
	{
		$this->interval = $newVal;
	}

	/**
	 *
	 */
	public function getBySecond()
	{
		return $this->bySecond;
	}

	/**
	 * rfc 2445 : Valid values are 0 to 59
	 * @param newVal
	 */
	public function setBySecond($newVal)
	{
		if ( !is_array($newVal) ) {
			$this->bySecond = array();
			$split = explode(',', $newVal);
			foreach ( $split as $_value ) $this->bySecond[] = trim($_value);
		} else {
		  $this->bySecond = $newVal;
		}
	}

	/**
	 *
	 */
	public function getByMinute()
	{
		return $this->byMinute;
	}

	/**
	 * rfc 2445 : Valid values are 0 to 59
	 * @param newVal
	 */
	public function setByMinute($newVal)
	{
		if ( !is_array($newVal) ) {
			$this->byMinute = array();
			$split = explode(',', $newVal);
			foreach ( $split as $_value ) $this->byMinute[] = trim($_value);
		} else {
		  $this->byMinute = $newVal;
		}
	}

	/**
	 *
	 */
	public function getByHour()
	{
		return $this->byHour;
	}

	/**
	 * rfc 2445 : Valid values are 0 to 23
	 * @param newVal
	 */
	public function setByHour($newVal)
	{
		if ( !is_array($newVal) ) {
			$this->byHour = array();
			$split = explode(',', $newVal);
			foreach ( $split as $_value ) $this->byHour[] = trim($_value);
		} else {
		  $this->byHour = $newVal;
		}
	}

	/**
	 * rfc 2445: [[+|-]1DIGIT/2DIGIT ] SU|MO|TU|WE|TH|FR|SA
	 */
	public function getByDay()
	{
		return $this->byDay;
	}

	/**
	 *
	 */
	public function getByDayClear()
	{
		return $this->byDayClear;
	}

	/**
	 *
	 * rfc 2445: [[+|-]1DIGIT/2DIGIT ] SU|MO|TU|WE|TH|FR|SA
	 * @param newVal
	 */
	public function setByDay($newVal)
	{
		$this->byDay           = null;
		$this->byDayClear      = null;
		$this->byDayRelative   = false;
		if ( !is_array($newVal) ) {
			$this->byDay = array();
			$split = explode(',', $newVal);
			foreach ( $split as $_value ) {
				$this->byDay[] = trim($_value);
				if ( preg_match('/^[\-+]{0,1}\d{1,2}(.*?)$/', trim($_value), $match) ) {
					$this->byDayRelative = true;
					$this->byDayClear[] = $match[1];
				} else {
					$this->byDayClear[] = trim($_value);
				}
			}
		} else {
			foreach ( $newVal as $_value ) {
				if ( preg_match('/^[\-+]{0,1}\d{1,2}(.*?)$/', trim($_value), $match) ) {
					$this->byDayRelative = true;
					$this->byDayClear[] = $match[1];
				} else {
					$this->byDayClear[] = trim($_value);
				}
			}
			$this->byDay = $newVal;
		}
	}

	/**
	 *
	 */
	public function isDayRelative()
	{
		return (boolean) $this->byDayRelative;
	}

	/**
	 *
	 */
	public function getByMonthDay()
	{
		return $this->byMonthDay;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setByMonthDay($newVal)
	{
		if ( !is_array($newVal) ) {
			$this->byMonthDay = array();
			$split = explode(',', $newVal);
			foreach ( $split as $_value ) {
				$this->byMonthDay[] = trim($_value);
				if ( substr(trim($_value), 0, 1) == '-' ) $this->byMonthDayRelative = true;
			}
		} else {
			foreach ( $newVal as $_value ) {
				if ( substr(trim($_value), 0, 1) == '-' ) $this->byMonthDayRelative = true;
			}
			$this->byMonthDay = $newVal;
		}
	}

	/**
	 *
	 */
	public function isMonthDayRelative()
	{
		return (boolean) $this->byMonthDayRelative;
	}

	/**
	 *
	 */
	public function getByYearDay()
	{
		return $this->byYearDay;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setByYearDay($newVal)
	{
		if ( !is_array($newVal) ) {
			$this->byYearDay = array();
			$split = explode(',', $newVal);
			foreach ( $split as $_value ) {
				$this->byYearDay[] = trim($_value);
				if ( substr(trim($_value), 0, 1) == '-' ) $this->byYearDayRelative = true;
			}
		} else {
			foreach ( $newVal as $_value ) {
				if ( substr(trim($_value), 0, 1) == '-' ) $this->byYearDayRelative = true;
			}
			$this->byYearDay = $newVal;
		}
	}

	/**
	 *
	 */
	public function isYearDayRelative()
	{
		return (boolean) $this->byYearDayRelative;
	}

	/**
	 *
	 */
	public function getByWeekNo()
	{
		return $this->byWeekNo;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setByWeekNo($newVal)
	{
		if ( !is_array($newVal) ) {
			$this->byWeekNo = array();
			$split = explode(',', $newVal);
			foreach ( $split as $_value ) {
				$this->byWeekNo[] = trim($_value);
				if ( substr(trim($_value), 0, 1) == '-' ) $this->byWeekNoRelative = true;
			}
		} else {
			foreach ( $newVal as $_value ) {
				if ( substr(trim($_value), 0, 1) == '-' ) $this->byWeekNoRelative = true;
			}
			$this->byWeekNo = $newVal;
		}
	}

	/**
	 *
	 */
	public function isWeekNoRelative()
	{
		return (boolean) $this->byWeekNoRelative;
	}

	/**
	 * return array on numbers - one or two digit
	 * rfc 2445: BYMONTH - 1DIGIT / 2DIGIT ;1 to 12
	 */
	public function getByMonth()
	{
		return $this->byMonth;
	}

	/**
	 * rfc 2445: BYMONTH - 1DIGIT / 2DIGIT ;1 to 12
	 * @param array newVal
	 */
	public function setByMonth($newVal)
	{
		if ( !is_array($newVal) ) {
			$this->byMonth = array();
			$split = explode(',', $newVal);
			foreach ( $split as $_value ) $this->byMonth[] = trim($_value);
		} else {
		  $this->byMonth = $newVal;
		}
	}

	/**
	 *
	 */
	public function getBySetPos()
	{
		return $this->bySetPos;
	}

	/**
	 * @todo предпологаем, что BYSETPOS относиться к
	 *     BYDAY       - если существует
	 *     BYMONTHDAY  -
	 *     BYYEARDAY   -
	 *     BYWEEKNO    -
	 * @param newVal
	 */
	public function setBySetPos($newVal)
	{
		$this->bySetPos = null;
		if ( !is_array($newVal) ) {
			$this->bySetPos = array();
			$split = explode(',', $newVal);
			foreach ( $split as $_value ) $this->bySetPos[] = trim($_value);
		} else {
		  $this->bySetPos = $newVal;
		}
	}

	/**
	 * rfc 2445: WKST - The default value is MO.
	 */
	public function getWkst()
	{
		if ( null === $this->wkst ) $this->wkst = 'MO';
		return $this->wkst;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setWkst($newVal)
	{
		$this->wkst = $newVal;
	}

	/**
	 *
	 */
	public function loadById($rruleId)
	{
		$query = $this->DbConn->select();
		$query->from('calendar_event_rrules', array('*'));
		$query->where('rrule_id = ?', $rruleId);
		$data = $this->DbConn->fetchRow($query);
		if ( $data ) {
			$this->setId($data['rrule_id']);
			$this->setEventId($data['rrule_event_id']);
			$this->setFreq($data['rrule_freq']);
			$this->setInterval($data['rrule_interval']);
			if ( $data['rrule_count'] )         $this->setCount($data['rrule_count']);
			if ( $data['rrule_until'] )         $this->setUntil($data['rrule_until']);
			if ( $data['rrule_by_second'] )     $this->setBySecond($data['rrule_by_second']);
			if ( $data['rrule_by_minute'] )     $this->setByMinute($data['rrule_by_minute']);
			if ( $data['rrule_by_hour'] )       $this->setByHour($data['rrule_by_hour']);
			if ( $data['rrule_by_day'] )        $this->setByDay($data['rrule_by_day']);
			if ( $data['rrule_by_month_day'] )  $this->setByMonthDay($data['rrule_by_month_day']);
			if ( $data['rrule_by_year_day'] )   $this->setByYearDay($data['rrule_by_year_day']);
			if ( $data['rrule_by_week_no'] )    $this->setByWeekNo($data['rrule_by_week_no']);
			if ( $data['rrule_by_month'] )      $this->setByMonth($data['rrule_by_month']);
			if ( $data['rrule_by_set_pos'] )    $this->setBySetPos($data['rrule_by_set_pos']);
			if ( $data['rrule_wkst'] )          $this->setWkst($data['rrule_wkst']);
		}
	}
	/**
	 *
	 */
	public function save()
	{
		$data = array();
		$data['rrule_event_id']         = $this->getEventId();
		$data['rrule_freq']             = $this->getFreq();
		$data['rrule_until']            = ( null === $this->getUntilValue() )   ? new Zend_Db_Expr('NULL') : $this->getUntilValue();
        if ( null !== $this->getUntilValue() ) {
            preg_match_all('/^([0-9]{4}-[0-9]{2}-[0-9]{2})T([0-9]{2})([0-9]{2})([0-9]{2})$/i', $this->getUntilValue(), $mathes);
            $data['rrule_until_date'] = $mathes[1][0].' '.$mathes[2][0].':'.$mathes[3][0].':'.$mathes[4][0];
        }
		$data['rrule_interval']         = $this->getInterval();
		$data['rrule_count']            = ( null === $this->getCount() )        ? new Zend_Db_Expr('NULL') : $this->getCount();
		$data['rrule_by_second']        = ( null === $this->getBySecond() )     ? new Zend_Db_Expr('NULL') : join(',',$this->getBySecond());
		$data['rrule_by_minute']        = ( null === $this->getByMinute() )     ? new Zend_Db_Expr('NULL') : join(',',$this->getByMinute());
		$data['rrule_by_hour']          = ( null === $this->getByHour() )       ? new Zend_Db_Expr('NULL') : join(',',$this->getByHour());
		$data['rrule_by_day']           = ( null === $this->getByDay() )        ? new Zend_Db_Expr('NULL') : join(',',$this->getByDay());
		$data['rrule_by_month_day']     = ( null === $this->getByMonthDay() )   ? new Zend_Db_Expr('NULL') : join(',',$this->getByMonthDay());
		$data['rrule_by_year_day']      = ( null === $this->getByYearDay() )    ? new Zend_Db_Expr('NULL') : join(',',$this->getByYearDay());
		$data['rrule_by_week_no']       = ( null === $this->getByWeekNo() )     ? new Zend_Db_Expr('NULL') : join(',',$this->getByWeekNo());
		$data['rrule_by_month']         = ( null === $this->getByMonth() )      ? new Zend_Db_Expr('NULL') : join(',',$this->getByMonth());
		$data['rrule_by_set_pos']       = ( null === $this->getBySetPos() )     ? new Zend_Db_Expr('NULL') : join(',',$this->getBySetPos());
		$data['rrule_wkst']             = ( null === $this->getWkst() )         ? new Zend_Db_Expr('NULL') : $this->getWkst();

		if ( null === $this->getId() ) {
			$this->DbConn->insert('calendar_event_rrules', $data);
			$this->setId($this->DbConn->lastInsertId());
		} else {
			$where = $this->DbConn->quoteInto('rrule_id = ?', $this->getId());
			$this->DbConn->update('calendar_event_rrules', $data, $where);
		}
	}

	public function updateUntilDate($strUntilDate) 
	{
		$data = array();
		$data['rrule_until'] = $strUntilDate;
		preg_match_all('/^([0-9]{4}-[0-9]{2}-[0-9]{2})T([0-9]{2})([0-9]{2})([0-9]{2})$/i', $strUntilDate, $mathes);
		$data['rrule_until_date'] = $mathes[1][0].' '.$mathes[2][0].':'.$mathes[3][0].':'.$mathes[4][0];

		$where = $this->DbConn->quoteInto('rrule_id = ?', $this->getId());
		$this->DbConn->update('calendar_event_rrules', $data, $where);
	}
	
	public function delete()
	{
		$where = $this->DbConn->quoteInto('rrule_id = ?', $this->getId());
		$this->DbConn->delete('calendar_event_rrules', $where);
	}

	public function equals(Warecorp_ICal_Rrule $objRrule)
	{
		if ( $this->getId() != $objRrule->getId() )                     return false;
		if ( $this->getEventId() != $objRrule->getEventId() )           return false;
		if ( $this->getFreq() != $objRrule->getFreq() )                 return false;
		if ( $this->getUntil() != $objRrule->getUntil() )               return false;
		if ( $this->getCount() != $objRrule->getCount() )               return false;
		if ( $this->getInterval() != $objRrule->getInterval() )         return false;
		if ( $this->getBySecond() != $objRrule->getBySecond() )         return false;
		if ( $this->getByMinute() != $objRrule->getByMinute() )         return false;
		if ( $this->getByHour() != $objRrule->getByHour() )             return false;
		if ( $this->getByDay() != $objRrule->getByDay() )               return false;
		if ( $this->getByMonthDay() != $objRrule->getByMonthDay() )     return false;
		if ( $this->getByYearDay() != $objRrule->getByYearDay() )       return false;
		if ( $this->getByWeekNo() != $objRrule->getByWeekNo() )         return false;
		if ( $this->getByMonth() != $objRrule->getByMonth() )           return false;
		if ( $this->getBySetPos() != $objRrule->getBySetPos() )         return false;
		if ( $this->getWkst() != $objRrule->getWkst() )                 return false;

		return true;
	}

	static public function isEventRruleExist($eventId)
	{
		$DbConn = Zend_Registry::get('DB');
		if ( $DbConn === null ) throw new Warecorp_Exception('Database connection is not set.');

		$query = $DbConn->select();
		$query->from('calendar_event_rrules', array('rrule_id'));
		$query->where('rrule_event_id = ?', $eventId);
		$result = $DbConn->fetchOne($query);
		return ( $result ) ? $result : null;
	}

    public function setFromHttpRequest($objRequest)
    {

        $this->setFreq($objRequest->getParam('rrule_freq'));

        switch ( $objRequest->getParam('rrule_freq') ) {
            case Warecorp_ICal_Rrule_Enum_Freq::DAILY :
                switch ( $objRequest->getParam('rrule_daily_option') ) {
                    case 1 :
                        $this->setInterval($objRequest->getParam('rrule_daily_interval1'));
                        break;
                    case 2 :
                        $this->setInterval(1);
                        $this->setByDay('MO,TU,WE,TH,FR');
                        break;
                }
                break;
            case Warecorp_ICal_Rrule_Enum_Freq::WEEKLY :
                switch ( $objRequest->getParam('rrule_weekly_option') ) {
                    case 1 :
                        $this->setInterval($objRequest->getParam('rrule_weekly_interval1'));
                        /**
                         * if weekly repeating and user doesn't select days - empty
                         * TODO this value should be checked in actions
                         * IN THIS TIME USED CURRENT WEEKDAY (if no selected)
                         */
                        if ( $objRequest->getParam('rrule_weekly_byday1') ) {
                            $this->setByDay($objRequest->getParam('rrule_weekly_byday1'));
                        }
                        break;
                }
                break;
            case Warecorp_ICal_Rrule_Enum_Freq::MONTHLY :
                switch ( $objRequest->getParam('rrule_monthly_option') ) {
                    case 1 :
                        $this->setInterval($objRequest->getParam('rrule_monthly_interval1'));
                        $this->setByMonthDay($objRequest->getParam('rrule_monthly_bymonthday1'));
                        break;
                    case 2 :
                        $this->setInterval($objRequest->getParam('rrule_monthly_interval2'));
                        $this->setByDay($objRequest->getParam('rrule_monthly_byday2'));
                        $this->setBySetPos($objRequest->getParam('rrule_monthly_setpos2'));
                        break;
                    case 3 :
                        $this->setInterval($objRequest->getParam('rrule_monthly_interval3'));
                        $this->setByMonthDay($objRequest->getParam('rrule_monthly_bymonthday3'));
                        break;
                }
                break;
            case Warecorp_ICal_Rrule_Enum_Freq::YEARLY :
                switch ( $objRequest->getParam('rrule_yearly_option') ) {
                    case 1 :
                        $this->setByMonthDay($objRequest->getParam('rrule_yearly_bymonthday1'));
                        $this->setByMonth($objRequest->getParam('rrule_yearly_bymonth1'));
                        break;
                    case 2 :
                        $this->setFreq(Warecorp_ICal_Rrule_Enum_Freq::MONTHLY);
                        $this->setByDay($objRequest->getParam('rrule_yearly_byday2'));
                        $this->setByMonth($objRequest->getParam('rrule_yearly_bymonth2'));
                        $this->setBySetPos($objRequest->getParam('rrule_yearly_setpos2'));
                        break;
                }
                break;
        }
        /**
         * Rrule Until
         */
        switch ( $objRequest->getParam('rrule_until_option') ) {
            case 1 :
				$this->setCount(null);
				$this->setUntil(null);				
                break;
            case 2 :
                $this->setCount($objRequest->getParam('rrule_until_count'));
				$this->setUntil(null);
                break;
            case 3 :
				$this->setCount(null);
                $rrule_until_date = $objRequest->getParam('rrule_until_date');
                $strUntilDate = sprintf('%04d',$rrule_until_date['date_Year']).'-'.sprintf('%02d',$rrule_until_date['date_Month']).'-'.sprintf('%02d',$rrule_until_date['date_Day']).'T000000';
                $this->setUntil($strUntilDate);
                break;
        }

    }

    public function setHttpRequest(&$objRequest, $eventTimezone, $currentTimezone)
    {
        if ( null !== $this->getId() ) {
            $objRequest->setParam('rrule_freq', $this->getFreq());
            /**
            * Restore Rrule
            */
            if ( $this->getFreq() == Warecorp_ICal_Rrule_Enum_Freq::DAILY ) {
                if ( !$this->getByDay() || 'MO,TU,WE,TH,FR' != join(',', $this->getByDay()) ) $objRequest->setParam('rrule_daily_option', 1);
                else $objRequest->setParam('rrule_daily_option', 2);
                $objRequest->setParam('rrule_daily_interval1', $this->getInterval());
            }
            elseif ( $this->getFreq() == Warecorp_ICal_Rrule_Enum_Freq::WEEKLY ) {
                $objRequest->setParam('rrule_weekly_option', 1);
                $objRequest->setParam('rrule_weekly_interval1', $this->getInterval());
                $rrule_weekly_byday1 = $this->getByDay();
                if ( sizeof($rrule_weekly_byday1) != 0 ) {
                    foreach ( $rrule_weekly_byday1 as $w ) $rrule_weekly_byday1[$w] = $w;
                }
                $objRequest->setParam('rrule_weekly_byday1', $rrule_weekly_byday1);
            }
            elseif ( $this->getFreq() == Warecorp_ICal_Rrule_Enum_Freq::MONTHLY ) {
                if ( null !== $this->getByMonth() ) {
                    $this->setFreq(Warecorp_ICal_Rrule_Enum_Freq::YEARLY);
                    $objRequest->setParam('rrule_yearly_option', 2);
                    $rrule_yearly_byday2 = $this->getByDay();
                    $objRequest->setParam('rrule_yearly_byday2', $rrule_yearly_byday2[0]);
                    $rrule_yearly_bymonth2 = $this->getByMonth();
                    $objRequest->setParam('rrule_yearly_bymonth2', $rrule_yearly_bymonth2[0]);
                    $objRequest->setParam('rrule_yearly_setpos2', $this->getBySetPos());
                } elseif ( null !== $this->getByMonthDay() ) {
                    $rrule_monthly_bymonthday1 = $this->getByMonthDay();
                    if ($rrule_monthly_bymonthday1[0] === '+1' || $rrule_monthly_bymonthday1[0] === '-1') {
                        $objRequest->setParam('rrule_monthly_option', 3);
                        $objRequest->setParam('rrule_monthly_bymonthday3', $rrule_monthly_bymonthday1[0]);
                        $objRequest->setParam('rrule_monthly_interval3', $this->getInterval());
                    }
                    else {
                        $objRequest->setParam('rrule_monthly_option', 1);
                        $objRequest->setParam('rrule_monthly_bymonthday1', $rrule_monthly_bymonthday1[0]);
                        $objRequest->setParam('rrule_monthly_interval1', $this->getInterval());
                    }
                } else {
                    $objRequest->setParam('rrule_monthly_option', 2);
                    $rrule_monthly_byday2 = $this->getByDay();
                    $objRequest->setParam('rrule_monthly_byday2', $rrule_monthly_byday2[0]);
                    $objRequest->setParam('rrule_monthly_setpos2', $this->getBySetPos());
                    $objRequest->setParam('rrule_monthly_interval2', $this->getInterval());
                }
            }
            elseif ( $this->getFreq() == Warecorp_ICal_Rrule_Enum_Freq::YEARLY ) {
                if ( null !== $this->getByMonthDay() ) {
                    $objRequest->setParam('rrule_yearly_option', 1);
                    $rrule_yearly_bymonthday1 = $this->getByMonthDay();
                    $objRequest->setParam('rrule_yearly_bymonthday1', $rrule_yearly_bymonthday1[0]);
                    $objRequest->setParam('rrule_yearly_bymonth1', $this->getByMonth());
                }
            }
            /**
            * Restore Until Date
            */
            if ( null !== $this->getCount() ) {
                $objRequest->setParam('rrule_until_option', 2);
                $objRequest->setParam('rrule_until_count', $this->getCount());
            } elseif ( null !== $this->getUntil() ) {
                $objRequest->setParam('rrule_until_option', 3);
                // время в таймзоне события
                $strUntilDate = $this->getUntil();

                $defaultTimeZone = date_default_timezone_get();
                date_default_timezone_set( ( null !== $eventTimezone ) ? $eventTimezone : $currentTimezone );
                $objDefaultUntilDate = new Zend_Date($strUntilDate, Zend_Date::ISO_8601);
                $objDefaultUntilDate->setTimezone($currentTimezone);
                date_default_timezone_set($defaultTimeZone);
                $objRequest->setParam('rrule_until_date_obj', $objDefaultUntilDate);
            } else {
                $objRequest->setParam('rrule_until_option', 1);
            }
        }
    }
}
?>
