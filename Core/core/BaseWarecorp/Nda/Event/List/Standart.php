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

class BaseWarecorp_Nda_Event_List_Standart extends Warecorp_ICal_Event_List_Standard
{
    private $includeIds = array();

    public function getIncludeIds()
    {
        return $this->includeIds;
    }

    public function setIncludeIds($value)
    {
        if (!is_array($value) && null !== $value) $value = array($value);
        $this->includeIds = $value;
        return $this;
    }

   /**
    * return list of items
    */
    public function getList()
    {
        $event_gmt_start = new Zend_Db_Expr("convert_tz(`vcel`.`event_dtstart`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

        $event_gmt_dtend = new Zend_Db_Expr("convert_tz(`vcel`.`event_dtend`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

        $event_gmt_rrule_until = new Zend_Db_Expr("convert_tz(`vcel`.`rrule_until`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

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

        if ( null !== $this->getIncludeIds() ) {
            $query->where('vcel.event_id IN(?)', $this->getIncludeIds());
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
                    'ref_max_gmt_rrule_noended' => $ref_max_rrule_noended
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
                    'ref_max_gmt_rrule_noended' => $ref_max_rrule_noended
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
            $timeWhere = '1=1) HAVING NOT(('.$timeWhere.')';
            $query->where($timeWhere);
        } else {
            $query->from(array('vcel' => 'view_calendar_events__list'), array('event_id' => 'vcel.event_id', 'isset_rrule' => 'vcel.isset_rrule', 'event_gmt_dtstart' => $event_gmt_start));
            $query->where(' 1 = 2 ');
        }
        /**
        * @desc
        */
        $query->order('event_gmt_dtstart ASC');

        if ($this->getPage() && $this->getSize()) {
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

    public function getCount()
    {
        $event_gmt_start = new Zend_Db_Expr("convert_tz(`vcel`.`event_dtstart`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

        $event_gmt_dtend = new Zend_Db_Expr("convert_tz(`vcel`.`event_dtend`, IF (`vcel`.`event_timezone` IS NULL, ".$this->DbConn->quoteInto('?', $this->getTimezone()).", `vcel`.`event_timezone`) ,_utf8'GMT')");

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

        if ( null !== $this->getIncludeIds() ) {
            $query->where('vcel.event_id IN(?)', $this->getIncludeIds());
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
                    'ref_max_gmt_rrule_noended' => $ref_max_rrule_noended
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
                    'ref_max_gmt_rrule_noended' => $ref_max_rrule_noended
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
            $timeWhere = '1=1) HAVING NOT(('.$timeWhere.')';
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

        $query = preg_replace("/SELECT\s+DISTINCT/i", "SELECT SQL_CALC_FOUND_ROWS DISTINCT", $query);

        $this->DbConn->fetchAll($query);

        $result = $this->DbConn->fetchRow("SELECT FOUND_ROWS() as count");
        return $result['count'];
    }
}
