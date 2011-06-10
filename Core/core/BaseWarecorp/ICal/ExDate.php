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

class BaseWarecorp_ICal_ExDate 
{
	private $DbConn;
	private $event;
	private $dates;
    private $fdates;
    private $pdates;
	
	/**
	 * 
	 */
	function __construct(Warecorp_ICal_Event $objEvent = null)
	{
		$this->DbConn = Zend_Registry::get('DB');
		if ( $this->DbConn === null ) throw new Warecorp_Exception('Database connection is not set.');
		
		if ( null !== $objEvent ) $this->setEvent($objEvent);
	}
	
	/**
	 *	if event has exdate with type THISANDFUTURE it means that it ended for this date
	 *  return value of this date as rrule until date
	 *  @author Artem Sukhare
	 */
	public function getUntilDate() {
		$query = $this->DbConn->select();
		$query->from('calendar_event_exdates', array('date'));
		$query->where('event_id = ?', $this->getEvent()->getId());
		$query->where('event_ref_id IS NULL');
		$query->where('type = ?', 'THISANDFUTURE');
		$results = $this->DbConn->fetchAll($query);
		if ( $results ) {
			$hash = array();
			foreach ( $results as $row ) $hash[$row['date']] = $row['date'];
			ksort($hash);
			$date = current($hash);
			return $date;
		}
		return null;
	}
	
	/**
	 * 
	 */
	public function setEvent(Warecorp_ICal_Event $newVal)
	{
		$this->event = $newVal;
	}
	
	/**
	 * 
	 */
	public function getEvent()
	{
		if ( null === $this->event ) throw new Warecorp_ICal_Exception('Event isn\'t set');
		return $this->event;
	}
	
	/**
	 * 
	 */
	public function loadByEvent(Warecorp_ICal_Event $event = null)
	{
		if ( null === $event ) $event = $this->getEvent();
		$query = $this->DbConn->select();
		$query->from('calendar_event_exdates', array('date', 'type'));
		$query->where('event_id = ?', $event->getId());
		$results = $this->DbConn->fetchAll($query);
		if ( $results ) {
			foreach ( $results as $row ) {
                if ( $row['type'] == 'THIS' ) $this->dates[$row['date']] = $row['date'];
                if ( $row['type'] == 'THISANDFUTURE' ) $this->fdates[$row['date']] = $row['date'];
                if ( $row['type'] == 'THISANDPRIOR' ) $this->pdates[$row['date']] = $row['date'];
            }        
		}
		return array();
	}
	
	/**
	 * 
	 */
	public function addExDate($strDate, $type = 'THIS', $refId = null)
	{
		$data = array();
		$data['event_id']       = $this->getEvent()->getId();
		$data['event_ref_id']   = ( null === $refId ) ? new Zend_Db_Expr('NULL') : $refId;
		$data['date']           = $strDate;
		$data['type']           = $type;
		$this->DbConn->insert('calendar_event_exdates', $data);
	}
	
	public function deleteAll()
	{
		$where = $this->DbConn->quoteInto('event_id = ?', $this->getEvent()->getId());
		$this->DbConn->delete('calendar_event_exdates', $where);
	}
	
	public function deleteTHISANDFUTURE() 
	{
		$where = $this->DbConn->quoteInto('event_id = ?', $this->getEvent()->getId());
		$where .= ' AND event_ref_id IS NULL';
		$where .= ' AND '.$this->DbConn->quoteInto('type = ?', 'THISANDFUTURE');
		$this->DbConn->delete('calendar_event_exdates', $where);
	}
	
	/**
	 * предпологаем, что дата приходит в таймзоне, в которой было созданно событие
	 * предпологаем, что exdata храниться в базе как дата из таймзоны, в которой
	 * было созданно событие. т.е и событие и exdata созданы в одной зоне
	 */
	public function isExDate(Zend_Date $checkedDate)
	{
		if ( isset($this->dates[$checkedDate->toString('yyyy-MM-dd').'T'.$checkedDate->toString('HHmmss')]) ) return true;
		else {
            if ( sizeof($this->fdates) ) {
                foreach ( $this->fdates as $date ) {
                    $tmpDate = new Zend_Date($date, Zend_Date::ISO_8601);
                    $tmpDateCheck = new Zend_Date($checkedDate->toString('yyyy-MM-dd').'T'.$checkedDate->toString('HHmmss'), Zend_Date::ISO_8601);
                    if ( $tmpDate->isEarlier($tmpDateCheck) || $tmpDate->equals($tmpDateCheck) ) return true;
                }
            }
        }
        return false;
	}
    
    public function deleteFutureExdates($strDate)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_exdates', array('*'));
        $query->where('event_id = ?', $this->getEvent()->getId());
        $query->where('date >= ?', $strDate);
        $results = $this->DbConn->fetchAll($query);
        if ( sizeof($results) != 0 ) {
            foreach ( $results as $row ) {
                if ( $row['event_ref_id'] ) {
                    $objTmpEvent = new Warecorp_ICal_Event($row['event_ref_id']);
                    $objTmpEvent->delete();
                }
            }
        }
        
        $where = $this->DbConn->quoteInto('event_id = ?', $this->getEvent()->getId());
        $where .= ' AND '.$this->DbConn->quoteInto('date >= ?', $strDate);
        $this->DbConn->delete('calendar_event_exdates', $where);
    }

    public function getAll()
    {
        return $this->DbConn->fetchCol(
            $this->DbConn->select()
            ->from('calendar_event_exdates', 'date')
            ->where('event_id = ?', $this->getEvent()->getId())
        );
    }
}
