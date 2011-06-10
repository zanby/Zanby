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

class BaseWarecorp_ICal_Event_List_Standard extends Warecorp_ICal_List_Abstract
{
    private $timezone;
    private $filterOwnerId;
    private $filterOwnerType;
    private $filterPrivacy;
    private $filterSharing;
    private $filterCategory;
    private $filterCurrentEvent = true;
    private $filterExpiredEvent = true;
    private $filterStartDate = null;
    private $filterShowCopy = false;
    private $withVenueOnly = false;
    private $withoutRRules = false;
    private $filterIncludeIds;
    private $filterPartOfRound;
    private $filterPartOfNonRound;
    private $filterStartDateRangeStart;
    private $filterStartDateRangeEnd;

    public function setTimezone($newValue)
    {
        $this->timezone = $newValue;
        return $this;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setOwnerIdFilter($newValue)
    {
        if ( !is_array($newValue) ) $newValue = array($newValue);
        $this->filterOwnerId = $newValue;
        return $this;
    }

    public function getOwnerIdFilter()
    {
        return $this->filterOwnerId;
    }

    public function setOwnerTypeFilter($newValue)
    {
        if ( !is_array($newValue) ) $newValue = array($newValue);
        $this->filterOwnerType = $newValue;
        return $this;
    }

    public function getOwnerTypeFilter()
    {
        return $this->filterOwnerType;
    }

    public function setPrivacyFilter($newValue)
    {
        if ( !is_array($newValue) && null !== $newValue ) $newValue = array($newValue);
        $this->filterPrivacy = $newValue;
        return $this;
    }

    public function getPrivacyFilter()
    {
        return $this->filterPrivacy;
    }

    public function setSharingFilter($newValue)
    {
        if ( !is_array($newValue) ) $newValue = array($newValue);
        $this->filterSharing = $newValue;
        return $this;
    }

    public function getSharingFilter()
    {
        return $this->filterSharing;
    }

    public function setShowCopyFilter($newValue)
    {
        $this->filterShowCopy = (boolean) $newValue;
        return $this;
    }

    public function getShowCopyFilter()
    {
        return (boolean) $this->filterShowCopy;
    }

    public function setCurrentEventFilter($newValue)
    {
        $this->filterCurrentEvent = $newValue;
        return $this;
    }

    public function getCurrentEventFilter()
    {
        return $this->filterCurrentEvent;
    }

    public function setExpiredEventFilter($newValue)
    {
        $this->filterExpiredEvent = $newValue;
        return $this;
    }

    public function getExpiredEventFilter()
    {
        return $this->filterExpiredEvent;
    }

    public function setStartDateFilter($newValue)
    {
        if ($newValue instanceof Zend_Date) {
            $this->filterStartDate = $newValue;
        }else{
            $this->filterStartDate = new Zend_Date($newValue);
        }
        
        return $this;
    }

    public function getStartDateFilter()
    {
        return $this->filterStartDate;
    }

    public function setCategoryFilter($newValue)
    {
        $this->filterCategory = $newValue;
        return $this;
    }

    public function getCategoryFilter()
    {
        return $this->filterCategory;
    }

    public function setWithVenueOnly( $value )
    {
        $this->withVenueOnly = (boolean) $value;
        return $this;
    }
    
    public function getWithVenueOnly()
    {
        return (boolean) $this->withVenueOnly;
    }

    public function setWithoutRepeatingsFilter( $value )
    {
        $this->withoutRRules = (boolean) $value;
        return $this;
    }

    public function getWithoutRepeatingsFilter()
    {
        return (boolean) $this->withoutRRules;
    }

    public  function setFilterIncludeIds($ids)
    {
        $this->filterIncludeIds = $ids;
    }

    public function getFilterIncludeIds()
    {
        if ( null === $this->filterIncludeIds ) return NULL;
        if ( is_array($this->filterIncludeIds) ) {
            if ( sizeof($this->filterIncludeIds) == 0 ) return NULL;
            else return $this->filterIncludeIds;
        } else {
            return array($this->filterIncludeIds);
        }
    }

    public  function setFilterPartOfRound($roundIds)
    {
        $this->filterPartOfRound = $roundIds;
    }

    public function getFilterPartOfRound()
    {
        if ( null === $this->filterPartOfRound ) return NULL;
        if ( is_array($this->filterPartOfRound) ) {
            if ( sizeof($this->filterPartOfRound) == 0 ) return NULL;
            else return $this->filterPartOfRound;
        } else {
            return array($this->filterPartOfRound);
        }
    }

    public  function setFilterPartOfNonRound($value)
    {
        $this->filterPartOfNonRound = $value;
    }

    public function getFilterPartOfNonRound()
    {
        if ( null === $this->filterPartOfNonRound ) return NULL;
        return $this->filterPartOfNonRound;
    }

    /**
     *
     * @param Zend_Date $objDateStart
     * @param Zend_Date $objDateEnd
     * @param string $timezone
     * @return Warecorp_ICal_Event_List_Standard
     */
    public function setFilterStartDateRange($objDateStart, $objDateEnd, $timezone = 'UTC') {
        if ($objDateStart instanceof Zend_Date) {
            $this->filterStartDateRangeStart = $objDateStart;
        }else{
            $tz = date_default_timezone_get();
            date_default_timezone_set($timezone);
            $this->filterStartDateRangeStart = new Zend_Date($objDateStart, 'MM/dd/yyyy');
            date_default_timezone_set($tz);

        }
        if ($objDateEnd instanceof Zend_Date) {
            $this->filterStartDateRangeEnd = $objDateEnd;
        }else{
            $tz = date_default_timezone_get();
            date_default_timezone_set($timezone);
            $this->filterStartDateRangeEnd = new Zend_Date($objDateEnd, 'MM/dd/yyyy');
            date_default_timezone_set($tz);
        }
        return $this;
    }

    public function getFilterStartDateRange() {
        return array($this->filterStartDateRangeStart, $this->filterStartDateRangeEnd);
    }

    public function isFilterStartDateRange() {
        return (null !== $this->filterStartDateRangeStart && null !== $this->filterStartDateRangeEnd);
    }

    /**
	 * Constructor
	 * @param Zend_Db_Table_Abstract $Connection - database connection object
	 */
	public function __construct()
	{
		parent::__construct();
        $this->setOrder('event_gmt_dtstart ASC');
	}
	/**
	 * return number of items
	 */
	public function getCount()
	{
		/*
		$query = $this->DbConn->select();

		$query->from('calendar_events', array('CNT' => new Zend_Db_Expr('COUNT(*)')));
		$result = $this->DbConn->fetchOne($query);

		return $result;
		*/
	}

	/**
	 * return list of items
	 */
	public function getList()
	{
        $event_gmt_start = new Zend_Db_Expr("
            convert_tz(`vcel`.`event_dtstart`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

        $event_gmt_dtend = new Zend_Db_Expr("
            convert_tz(`vcel`.`event_dtend`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

        $event_gmt_rrule_until = new Zend_Db_Expr("
            convert_tz(`vcel`.`rrule_until`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

        $recurrence_max_gmt_dtend = new Zend_Db_Expr("
            (SELECT
            MAX(convert_tz(`ce1`.`event_dtend_date`, IF (`ce1`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `ce1`.`event_timezone`) ,_utf8'GMT'))
            FROM `calendar_events` `ce1`
            WHERE
                 `ce1`.`event_recurrence_id` IS NOT NULL AND
                 `ce1`.`event_root_id` = `vcel`.`event_id`
            GROUP BY `ce1`.`event_root_id`)");

        $recurrence_max_gmt_dtstart = new Zend_Db_Expr("
            (SELECT
            MAX(convert_tz(`ce1`.`event_dtstart_date`, IF (`ce1`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `ce1`.`event_timezone`) ,_utf8'GMT'))
            FROM `calendar_events` `ce1`
            WHERE
                 `ce1`.`event_recurrence_id` IS NOT NULL AND
                 `ce1`.`event_root_id` = `vcel`.`event_id`
            GROUP BY `ce1`.`event_root_id`)");

        $ref_max_gmt_dtend = new Zend_Db_Expr("
            (SELECT
            MAX(convert_tz(`ce2`.`event_dtend_date`, IF (`ce2`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `ce2`.`event_timezone`) ,_utf8'GMT'))
            FROM `calendar_events` `ce2`
            WHERE
                 `ce2`.`event_ref_id` IS NOT NULL AND
                 `ce2`.`event_root_id` = `vcel`.`event_id`
            GROUP BY `ce2`.`event_root_id`)");

        $ref_max_gmt_dtstart = new Zend_Db_Expr("
            (SELECT
            MAX(convert_tz(`ce2`.`event_dtstart_date`, IF (`ce2`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `ce2`.`event_timezone`) ,_utf8'GMT'))
            FROM `calendar_events` `ce2`
            WHERE
                 `ce2`.`event_ref_id` IS NOT NULL AND
                 `ce2`.`event_root_id` = `vcel`.`event_id`
            GROUP BY `ce2`.`event_root_id`)");

        $ref_max_gmt_rrule_until = new Zend_Db_Expr("
            (SELECT
            MAX(convert_tz(`rr1`.`rrule_until_date`, IF (`ce3`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `ce3`.`event_timezone`) ,_utf8'GMT'))
            FROM `calendar_events` `ce3`
            INNER JOIN `calendar_event_rrules` `rr1` ON `rr1`.`rrule_event_id` = `ce3`.`event_id`
            WHERE
                 `ce3`.`event_ref_id` IS NOT NULL AND
                 `ce3`.`event_root_id` = `vcel`.`event_id`
            GROUP BY `ce3`.`event_root_id`)");

        $ref_max_gmt_rrule_noended = new Zend_Db_Expr("
            (SELECT
                COUNT(`rr1`.`rrule_id`)
                FROM `calendar_events` `ce3`
                INNER JOIN `calendar_event_rrules` `rr1` ON `rr1`.`rrule_event_id` = `ce3`.`event_id`
                WHERE
                `ce3`.`event_ref_id` IS NOT NULL AND
                `ce3`.`event_root_id` = `vcel`.`event_id` AND
                `rr1`.`rrule_until` IS NULL
            )");
        // FIXME НЕ УЧИТЫВАЮТСЯ EXDATES

		$query = $this->DbConn->select()->distinct(true);

		if ( $this->getPage() !== null && $this->getSize() !== null ) {
			$query->limitPage($this->getPage(), $this->getSize());
		}

        if ( null !== $this->getFilterIncludeIds() ) {
            $query->where('vcel.event_id IN (?)', $this->getFilterIncludeIds());            
        }

        /**
         * zccf filter
         */
        if ( null !== $this->getFilterPartOfRound() && $this->getFilterPartOfNonRound() ) {
            $query->where('(vcel.event_is_part_of_round = 0 OR vcel.event_is_part_of_round IN (?))', $this->getFilterPartOfRound());
        } elseif ( null !== $this->getFilterPartOfRound() ) {
            $query->where('vcel.event_is_part_of_round IN (?)', $this->getFilterPartOfRound());
        } elseif ( null !== $this->getFilterPartOfNonRound() ) {
            if ( $this->getFilterPartOfNonRound() ) {
                $query->where('vcel.event_is_part_of_round = 0');
            } else {
                $query->where('1=0');
            }
        }

        if ( null !== $this->getOwnerIdFilter() ) {
            $query->where('vcel.event_owner_id IN (?)', $this->getOwnerIdFilter());
        }
        if ( null !== $this->getOwnerTypeFilter() ) {
            $query->where('vcel.event_owner_type IN (?)', $this->getOwnerTypeFilter());
        }
        if ( null !== $this->getPrivacyFilter() ) {
            $query->where('vcel.event_privacy IN (?)', $this->getPrivacyFilter());
        }

        if ( null !== $this->getSharingFilter() ) {
            $query->where('vcel.share IN (?)', $this->getSharingFilter());
        }
        if ( false == $this->getShowCopyFilter() ) {
            $query->where('vcel.event_ref_id IS NULL');
            $query->where('vcel.event_recurrence_id IS NULL');
        }

        if ( null !== $this->getCategoryFilter() ) {
            $query->joinLeft(array('cec' => 'calendar_event_categories'), 'cec.event_id = vcel.event_id');
            $query->where('cec.category_id IN (?)', $this->getCategoryFilter());
        }

        if ( $this->getWithVenueOnly() ) {
            $query->joinLeft(array('cev' => 'calendar_event_venues'), 'cev.event_id = vcel.event_id', array('cev.venue_id'));
            $query->joinInner(array('zev' => 'zanby_event__venues'), 'zev.id = cev.venue_id', array('zev.type'));
            $query->where('(cev.venue_id IS NOT NULL AND zev.type = ?)', 'simple');
        }
        
        /**
        * @desc
        */
        if ( $this->isFilterStartDateRange() ) {
            $objUtcStart = clone $this->filterStartDateRangeStart;
            $objUtcStart->setTimezone('UTC');
            $objUtcEnd = clone $this->filterStartDateRangeEnd;
            $objUtcEnd->setTimezone('UTC');

            $query->from(
                array('vcel' => 'view_calendar_events__list'),
                array(
                    'event_id'                  => 'vcel.event_id',
                    'isset_rrule'               => 'vcel.isset_rrule',
                    'event_gmt_dtstart'         => $event_gmt_start,
                    'event_gmt_dtend'           => $event_gmt_dtend,
                    'rrule_gmt_until'           => $event_gmt_rrule_until,
                    'recurrence_max_gmt_dtstart'=> $recurrence_max_gmt_dtstart,
                    'recurrence_max_gmt_dtend'  => $recurrence_max_gmt_dtend,
                    'ref_max_gmt_dtstart'       => $ref_max_gmt_dtstart,
                    'ref_max_gmt_dtend'         => $ref_max_gmt_dtend,
                    'ref_max_rrule_gmt_until'   => $ref_max_gmt_rrule_until,
                    'ref_max_gmt_rrule_noended' => $ref_max_gmt_rrule_noended
                )
            );
            $timeWhere = $this->DbConn->quoteInto('((event_gmt_dtstart >= ?)', $objUtcStart->toString('yyyy-MM-dd HH:mm:ss'));
            $timeWhere .= $this->DbConn->quoteInto(' AND (event_gmt_dtstart <= ?))', $objUtcEnd->toString('yyyy-MM-dd HH:mm:ss'));
            $timeWhere = '1=1) HAVING(('.$timeWhere.')';
            $query->where($timeWhere);
        } elseif ($this->getStartDateFilter()) {
            $query->from(
                array('vcel' => 'view_calendar_events__list'),
                array(
                    'event_id'                  => 'vcel.event_id',
                    'isset_rrule'               => 'vcel.isset_rrule',
                    'event_gmt_dtstart'         => $event_gmt_start,
                    'event_gmt_dtend'           => $event_gmt_dtend,
                    'rrule_gmt_until'           => $event_gmt_rrule_until,
                    'recurrence_max_gmt_dtstart'=> $recurrence_max_gmt_dtstart,
                    'recurrence_max_gmt_dtend'  => $recurrence_max_gmt_dtend,
                    'ref_max_gmt_dtstart'       => $ref_max_gmt_dtstart,
                    'ref_max_gmt_dtend'         => $ref_max_gmt_dtend,
                    'ref_max_rrule_gmt_until'   => $ref_max_gmt_rrule_until,
                    'ref_max_gmt_rrule_noended' => $ref_max_gmt_rrule_noended
                )
            );
            $objUtcTime = clone $this->getStartDateFilter();
            $objUtcTime->setTimezone('UTC');
            $timeWhere = $this->DbConn->quoteInto('(event_gmt_dtstart >= ?)', $objUtcTime->toString('yyyy-MM-dd HH:mm:ss'));
            if (!$this->getWithoutRepeatingsFilter()) {
                $timeWhere .= ' OR (isset_rrule = 1 AND (rrule_gmt_until IS NULL OR '.$this->DbConn->quoteInto('rrule_gmt_until >= ?', $objUtcTime->toString('yyyy-MM-dd HH:mm:ss')).'))';
                $timeWhere .= ' OR (recurrence_max_gmt_dtstart IS NOT NULL AND '.$this->DbConn->quoteInto('recurrence_max_gmt_dtstart >= ?', $objUtcTime->toString('yyyy-MM-dd HH:mm:ss')).')';
                $timeWhere .= ' OR (ref_max_gmt_dtstart IS NOT NULL AND '.$this->DbConn->quoteInto('ref_max_gmt_dtstart >= ?', $objUtcTime->toString('yyyy-MM-dd HH:mm:ss')).')';
                $timeWhere .= ' OR (ref_max_gmt_rrule_noended > 0 OR (ref_max_rrule_gmt_until IS NOT NULL AND '.$this->DbConn->quoteInto('ref_max_rrule_gmt_until >= ?', $objUtcTime->toString('yyyy-MM-dd HH:mm:ss')).'))';
            }

            $timeWhere = '1=1) HAVING(('.$timeWhere.')';
            $query->where($timeWhere);
            //echo $query;exit;

        } elseif ( $this->getCurrentEventFilter() && $this->getExpiredEventFilter() ) {
            $query->from(
                array('vcel' => 'view_calendar_events__list'), 
                array(
                    'event_id' => 'vcel.event_id',
                    'isset_rrule' => 'vcel.isset_rrule',
                    'event_gmt_dtstart' => $event_gmt_start
                )
            );
        } elseif ( $this->getCurrentEventFilter() ) {
            if ($this->getWithoutRepeatingsFilter()) {
                $query->from(
                    array('vcel' => 'view_calendar_events__list'),
                    array(
                        'event_id'                  => 'vcel.event_id',
                        'isset_rrule'               => 'vcel.isset_rrule',
                        'event_gmt_dtstart'         => $event_gmt_start,
                        'event_gmt_dtend'           => $event_gmt_dtend
                    )
                );
            }else{
                $query->from(
                    array('vcel' => 'view_calendar_events__list'),
                    array(
                        'event_id'                  => 'vcel.event_id',
                        'isset_rrule'               => 'vcel.isset_rrule',
                        'event_gmt_dtstart'         => $event_gmt_start,
                        'event_gmt_dtend'           => $event_gmt_dtend,
                        'rrule_gmt_until'           => $event_gmt_rrule_until,
                        'recurrence_max_gmt_dtend'  => $recurrence_max_gmt_dtend,
                        'ref_max_gmt_dtend'         => $ref_max_gmt_dtend,
                        'ref_max_rrule_gmt_until'   => $ref_max_gmt_rrule_until,
                        'ref_max_gmt_rrule_noended' => $ref_max_gmt_rrule_noended
                    )
                );
            }
            $defaultTimezone = date_default_timezone_get();
            date_default_timezone_set('UTC');
            $objUtcNow = new Zend_Date();
            date_default_timezone_set($defaultTimezone);
            $timeWhere = $this->DbConn->quoteInto('(event_gmt_dtend >= ?)', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss'));
            if ($this->getWithoutRepeatingsFilter()) {
                //$timeWhere = $this->DbConn->quoteInto('(event_gmt_dtstart >= ?)', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss'));
                $timeWhere .= ' OR (isset_rrule = 1 AND (rrule_gmt_until IS NULL OR '.$this->DbConn->quoteInto('rrule_gmt_until >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).'))';
                $timeWhere .= ' OR (recurrence_max_gmt_dtend IS NOT NULL AND '.$this->DbConn->quoteInto('recurrence_max_gmt_dtend >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).')';
                $timeWhere .= ' OR (ref_max_gmt_dtend IS NOT NULL AND '.$this->DbConn->quoteInto('ref_max_gmt_dtend >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).')';
                $timeWhere .= ' OR (ref_max_gmt_rrule_noended > 0 OR (ref_max_rrule_gmt_until IS NOT NULL AND '.$this->DbConn->quoteInto('ref_max_rrule_gmt_until >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).'))';
            }
            $timeWhere = '1=1) HAVING(('.$timeWhere.')';
            $query->where($timeWhere);

        } elseif ( $this->getExpiredEventFilter() ) {
            if ($this->getWithoutRepeatingsFilter()) {
                $query->from(
                    array('vcel' => 'view_calendar_events__list'),
                    array(
                        'event_id'                  => 'vcel.event_id',
                        'isset_rrule'               => 'vcel.isset_rrule',
                        'event_gmt_dtstart'         => $event_gmt_start,
                        'event_gmt_dtend'           => $event_gmt_dtend
                    )
                );
            }else{
                $query->from(
                    array('vcel' => 'view_calendar_events__list'),
                    array(
                        'event_id'                  => 'vcel.event_id',
                        'isset_rrule'               => 'vcel.isset_rrule',
                        'event_gmt_dtstart'         => $event_gmt_start,
                        'event_gmt_dtend'           => $event_gmt_dtend,
                        'rrule_gmt_until'           => $event_gmt_rrule_until,
                        'recurrence_max_gmt_dtend'  => $recurrence_max_gmt_dtend,
                        'ref_max_gmt_dtend'         => $ref_max_gmt_dtend,
                        'ref_max_rrule_gmt_until'   => $ref_max_gmt_rrule_until,
                        'ref_max_gmt_rrule_noended' => $ref_max_gmt_rrule_noended
                    )
                );
            }

            $defaultTimezone = date_default_timezone_get();
            date_default_timezone_set('UTC');
            $objUtcNow = new Zend_Date();
            date_default_timezone_set($defaultTimezone);
            $timeWhere = $this->DbConn->quoteInto('(event_gmt_dtend >= ?)', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss'));
            if ($this->getWithoutRepeatingsFilter()) {
                $timeWhere .= ' OR (isset_rrule = 1 AND (rrule_gmt_until IS NULL OR '.$this->DbConn->quoteInto('rrule_gmt_until >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).'))';
                $timeWhere .= ' OR (recurrence_max_gmt_dtend IS NOT NULL AND '.$this->DbConn->quoteInto('recurrence_max_gmt_dtend >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).')';
                $timeWhere .= ' OR (ref_max_gmt_dtend IS NOT NULL AND '.$this->DbConn->quoteInto('ref_max_gmt_dtend >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).')';
                $timeWhere .= ' OR (ref_max_gmt_rrule_noended > 0 OR (ref_max_rrule_gmt_until IS NOT NULL AND '.$this->DbConn->quoteInto('ref_max_rrule_gmt_until >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).'))';
            }
            $timeWhere = '1=1) HAVING NOT (('.$timeWhere.')';
            $query->where($timeWhere);

        } else {
            $query->from(
                array('vcel' => 'view_calendar_events__list'),
                array(
                    'event_id' => 'vcel.event_id',
                    'isset_rrule' => 'vcel.isset_rrule',
                    'event_gmt_dtstart' => $event_gmt_start
                )
            );
            $query->where(' 1 = 2 ');
        }

        $query->order($this->getOrder());

        if ( $this->getPage() !== null && $this->getSize() !== null ) {
            $query->limitPage($this->getPage(), $this->getSize());
        }

		if ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::OBJECT ) {
			$result = $this->DbConn->fetchAll($query);
			if ( sizeof($result) != 0 ) {
				foreach ( $result as &$event ) {
                    $event = new Warecorp_ICal_Event($event['event_id']);
                    if ( null != $this->getTimezone() ) {
                        if ( null === $event->getTimezone() ) $event->setTimezone($this->getTimezone());
                    }
                }
			}
		} elseif ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::ASSOC ) {
            $result = $this->DbConn->fetchAll($query);
            $newResult = array();
            if ( sizeof($result) != 0 ) {
                foreach ( $result as &$event ) {
                    $newResult[] = array('event_id' => $event['event_id']);
                }
            }
            $result = $newResult;
		} elseif ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::PAIRS ) {
            $result = $this->DbConn->fetchAll($query);
            $newResult = array();
            if ( sizeof($result) != 0 ) {
                foreach ( $result as &$event ) {
                    $newResult[$event['event_id']] = $event['event_id'];
                }
            }
            $result = $newResult;
		}
		return $result;
	}

    /**
     * return list of items
     */
    public function getListByUser(Warecorp_User $objUser)
    {
        $event_gmt_start = new Zend_Db_Expr("
            convert_tz(`vcel`.`event_dtstart`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

        $event_gmt_dtend = new Zend_Db_Expr("
            convert_tz(`vcel`.`event_dtend`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

        $event_gmt_rrule_until = new Zend_Db_Expr("
            convert_tz(`vcel`.`rrule_until`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

        $recurrence_max_gmt_dtend = new Zend_Db_Expr("
            (SELECT
            MAX(convert_tz(`ce1`.`event_dtend_date`, IF (`ce1`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `ce1`.`event_timezone`) ,_utf8'GMT'))
            FROM `calendar_events` `ce1`
            WHERE
                 `ce1`.`event_recurrence_id` IS NOT NULL AND
                 `ce1`.`event_root_id` = `vcel`.`event_id`
            GROUP BY `ce1`.`event_root_id`)");

        $ref_max_gmt_dtend = new Zend_Db_Expr("
            (SELECT
            MAX(convert_tz(`ce2`.`event_dtend_date`, IF (`ce2`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `ce2`.`event_timezone`) ,_utf8'GMT'))
            FROM `calendar_events` `ce2`
            WHERE
                 `ce2`.`event_ref_id` IS NOT NULL AND
                 `ce2`.`event_root_id` = `vcel`.`event_id`
            GROUP BY `ce2`.`event_root_id`)");

        $ref_max_gmt_rrule_until = new Zend_Db_Expr("
            (SELECT
            MAX(convert_tz(`rr1`.`rrule_until_date`, IF (`ce3`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `ce3`.`event_timezone`) ,_utf8'GMT'))
            FROM `calendar_events` `ce3`
            INNER JOIN `calendar_event_rrules` `rr1` ON `rr1`.`rrule_event_id` = `ce3`.`event_id`
            WHERE
                 `ce3`.`event_ref_id` IS NOT NULL AND
                 `ce3`.`event_root_id` = `vcel`.`event_id`
            GROUP BY `ce3`.`event_root_id`)");

        $ref_max_gmt_rrule_noended = new Zend_Db_Expr("
            (SELECT
                COUNT(`rr1`.`rrule_id`)
                FROM `calendar_events` `ce3`
                INNER JOIN `calendar_event_rrules` `rr1` ON `rr1`.`rrule_event_id` = `ce3`.`event_id`
                WHERE
                `ce3`.`event_ref_id` IS NOT NULL AND
                `ce3`.`event_root_id` = `vcel`.`event_id` AND
                `rr1`.`rrule_until` IS NULL
            )");
        // FIXME НЕ УЧИТЫВАЮТСЯ EXDATES

        $query = $this->DbConn->select()->distinct(true);


        if ( $this->getPage() !== null && $this->getSize() !== null ) {
            $query->limitPage($this->getPage(), $this->getSize());
        }

        $userWhere = '';
        $userWhere .= '((' . $this->DbConn->quoteInto('vcel.event_owner_id = ?', $objUser->getId());
        $userWhere .= ' AND ' . $this->DbConn->quoteInto('vcel.event_owner_type = ?', 'user').')';
        $userWhere .= ' OR (' . $this->DbConn->quoteInto('vcel.event_creator_id = ?', $objUser->getId());
        $userWhere .= ' AND ' . $this->DbConn->quoteInto('vcel.event_owner_type = ?', 'group').'';
        $userWhere .= ' AND ' . $this->DbConn->quoteInto('vcel.share = ?', 0).'))';
        $query->where($userWhere);

        if ( false == $this->getShowCopyFilter() ) {
            $query->where('vcel.event_ref_id IS NULL');
            $query->where('vcel.event_recurrence_id IS NULL');
        }

        if ( $this->getWithVenueOnly() ) {
            $query->joinLeft(array('cev' => 'calendar_event_venues'), 'cev.event_id = vcel.event_id', array('cev.venue_id'));
            $query->joinInner(array('zev' => 'zanby_event__venues'), 'zev.id = cev.venue_id', array('zev.type'));
            $query->where('(cev.venue_id IS NOT NULL AND zev.type = ?)', 'simple');
        }
        
        /**
        * @desc
        */
        if ( $this->getCurrentEventFilter() && $this->getExpiredEventFilter() ) {
            $query->from(
                array('vcel' => 'view_calendar_events__list'),
                array(
                    'event_id' => 'vcel.event_id',
                    'isset_rrule' => 'vcel.isset_rrule',
                    'event_gmt_dtstart' => $event_gmt_start
                )
            );
        } elseif ( $this->getCurrentEventFilter() ) {
            $query->from(
                array('vcel' => 'view_calendar_events__list'),
                array(
                    'event_id'                  => 'vcel.event_id',
                    'isset_rrule'               => 'vcel.isset_rrule',
                    'event_gmt_dtstart'         => $event_gmt_start,
                    'event_gmt_dtend'           => $event_gmt_dtend,
                    'rrule_gmt_until'           => $event_gmt_rrule_until,
                    'recurrence_max_gmt_dtend'  => $recurrence_max_gmt_dtend,
                    'ref_max_gmt_dtend'         => $ref_max_gmt_dtend,
                    'ref_max_rrule_gmt_until'   => $ref_max_gmt_rrule_until,
                    'ref_max_gmt_rrule_noended' => $ref_max_gmt_rrule_noended
                )
            );
            $defaultTimezone = date_default_timezone_get();
            date_default_timezone_set('UTC');
            $objUtcNow = new Zend_Date();
            date_default_timezone_set($defaultTimezone);
            $timeWhere = $this->DbConn->quoteInto('(event_gmt_dtend >= ?)', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss'));
            //$timeWhere = $this->DbConn->quoteInto('(event_gmt_dtstart >= ?)', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss'));
            $timeWhere .= ' OR (isset_rrule = 1 AND (rrule_gmt_until IS NULL OR '.$this->DbConn->quoteInto('rrule_gmt_until >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).'))';
            $timeWhere .= ' OR (recurrence_max_gmt_dtend IS NOT NULL AND '.$this->DbConn->quoteInto('recurrence_max_gmt_dtend >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).')';
            $timeWhere .= ' OR (ref_max_gmt_dtend IS NOT NULL AND '.$this->DbConn->quoteInto('ref_max_gmt_dtend >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).')';
            $timeWhere .= ' OR (ref_max_gmt_rrule_noended > 0 OR (ref_max_rrule_gmt_until IS NOT NULL AND '.$this->DbConn->quoteInto('ref_max_rrule_gmt_until >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).'))';
            $timeWhere = '1=1) HAVING(('.$timeWhere.')';
            $query->where($timeWhere);
        } elseif ( $this->getExpiredEventFilter() ) {
            $query->from(
                array('vcel' => 'view_calendar_events__list'),
                array(
                    'event_id'                  => 'vcel.event_id',
                    'isset_rrule'               => 'vcel.isset_rrule',
                    'event_gmt_dtstart'         => $event_gmt_start,
                    'event_gmt_dtend'           => $event_gmt_dtend,
                    'rrule_gmt_until'           => $event_gmt_rrule_until,
                    'recurrence_max_gmt_dtend'  => $recurrence_max_gmt_dtend,
                    'ref_max_gmt_dtend'         => $ref_max_gmt_dtend,
                    'ref_max_rrule_gmt_until'   => $ref_max_gmt_rrule_until,
                    'ref_max_gmt_rrule_noended' => $ref_max_gmt_rrule_noended
                )
            );
            $defaultTimezone = date_default_timezone_get();
            date_default_timezone_set('UTC');
            $objUtcNow = new Zend_Date();
            date_default_timezone_set($defaultTimezone);
            $timeWhere = $this->DbConn->quoteInto('(event_gmt_dtend >= ?)', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss'));
            //$timeWhere = $this->DbConn->quoteInto('(event_gmt_dtstart >= ?)', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss'));
            $timeWhere .= ' OR (isset_rrule = 1 AND (rrule_gmt_until IS NULL OR '.$this->DbConn->quoteInto('rrule_gmt_until >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).'))';
            $timeWhere .= ' OR (recurrence_max_gmt_dtend IS NOT NULL AND '.$this->DbConn->quoteInto('recurrence_max_gmt_dtend >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).')';
            $timeWhere .= ' OR (ref_max_gmt_dtend IS NOT NULL AND '.$this->DbConn->quoteInto('ref_max_gmt_dtend >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).')';
            $timeWhere .= ' OR (ref_max_gmt_rrule_noended > 0 OR (ref_max_rrule_gmt_until IS NOT NULL AND '.$this->DbConn->quoteInto('ref_max_rrule_gmt_until >= ?', $objUtcNow->toString('yyyy-MM-dd HH:mm:ss')).'))';
            $timeWhere = '1=1) HAVING NOT (('.$timeWhere.')';
            $query->where($timeWhere);
        } else {
            $query->from(
                array('vcel' => 'view_calendar_events__list'), 
                array(
                    'event_id' => 'vcel.event_id',
                    'isset_rrule' => 'vcel.isset_rrule',
                    'event_gmt_dtstart' => $event_gmt_start
                )
            );
            $query->where(' 1 = 2 ');
        }
        /**
        * @desc
        */
        $query->order('event_gmt_dtstart ASC');

        $result = $this->DbConn->fetchAll($query);
        
        if ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::OBJECT ) {
            if ( sizeof($result) != 0 ) {
                foreach ( $result as &$event ) {
                    $event = new Warecorp_ICal_Event($event['event_id']);
                    if ( null != $this->getTimezone() ) {
                        if ( null === $event->getTimezone() ) $event->setTimezone($this->getTimezone());
                    }
                }
            }
        } elseif ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::ASSOC ) {
            if ( sizeof($result) != 0 ) {
                $newResult = array();
                if ( sizeof($result) != 0 ) {
                    foreach ( $result as &$event ) {
                        $newResult[] = array('event_id' => $event['event_id']);
                    }
                }
                $result = $newResult;
            }
        } elseif ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::PAIRS ) {
            if ( sizeof($result) != 0 ) {
                $newResult = array();
                if ( sizeof($result) != 0 ) {
                    foreach ( $result as &$event ) {
                        $newResult[$event['event_id']] = $event['event_id'];
                    }
                }
                $result = $newResult;
            }
        }
        return $result;
    }

    /**
    * @desc
    */
    public static function getListByRootId($rootId)
    {
        $DbConn = Zend_Registry::get('DB');
        $query = $DbConn->select();
        $query->from('calendar_events', array('event_id'));
        $query->where('event_root_id = ?', $rootId);
        $query->where('(event_ref_id IS NOT NULL OR event_recurrence_id IS NOT NULL)');
        $result = $DbConn->fetchCol($query);
        if ( sizeof($result) != 0 ) {
            foreach ( $result as &$item ) $item = new Warecorp_ICal_Event($item);
        }
        return $result;
    }

    /**
    * @desc
    */
    public static function getRefsListByRootId($rootId)
    {
        $DbConn = Zend_Registry::get('DB');
        $query = $DbConn->select();
        $query->from('calendar_events', array('event_id'));
        $query->where('event_root_id = ?', $rootId);
        $query->where('(event_ref_id IS NOT NULL)');
        $result = $DbConn->fetchCol($query);
        if ( sizeof($result) != 0 ) {
            foreach ( $result as &$item ) $item = new Warecorp_ICal_Event($item);
        }
        return $result;
    }

    /**
    * @desc
    */
    public static function getRecurrenceListByRootId($rootId)
    {
        $DbConn = Zend_Registry::get('DB');
        $query = $DbConn->select();
        $query->from('calendar_events', array('event_id'));
        $query->where('event_root_id = ?', $rootId);
        $query->where('(event_recurrence_id IS NOT NULL)');
        $result = $DbConn->fetchCol($query);
        if ( sizeof($result) != 0 ) {
            foreach ( $result as &$item ) $item = new Warecorp_ICal_Event($item);
        }
        return $result;
    }
}
