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

class BaseWarecorp_ICal_Calendar_Week
{
	private $arrDays;
	private $objFirstDayDate;
	private $objLastDayDate;
	
	public function __construct()
	{
	}
	
	public function setFirstDay(Zend_Date $newVal)
	{
		$this->objFirstDayDate = $newVal;
	}
	
	public function getFirstDay()
	{
		$this->objFirstDayDate->setLocale('en_US');
		return $this->objFirstDayDate;
	}
	
	
	public function setLastDay(Zend_Date $newVal)
	{
		$this->objLastDayDate = $newVal;
	}   
	
	public function getLastDay()
	{
		$this->objLastDayDate->setLocale('en_US');
		return $this->objLastDayDate;
	}

	public function loadByStartDate(Zend_Date $objStartDate)
	{
		$this->setFirstDay(clone $objStartDate);
		$objLastDate = clone $objStartDate;
		$objLastDate->add(1, Zend_Date::WEEK);
		$objLastDate->sub(1, Zend_Date::DAY);
		$this->setLastDay($objLastDate);
	}
	
	/**
	 * Return Warecorp_ICal_Calendar_Day objects for week
	 * Cached
	 */
	public function getDays()
	{
		if ( null === $this->arrDays ) {
			$objFirstDate = clone $this->getFirstDay();
			$cache = Warecorp_Cache::getFileCache();
			$cahceKey = 'Warecorp_ICal_Calendar_Week__getDays_'.$objFirstDate->toString('yyyyMMdd');
			if ( !$arrDays = $cache->load($cahceKey) ) {
				$arrDays = array();
				while ( $objFirstDate->isEarlier($this->getLastDay()) || $objFirstDate->equals($this->getLastDay()) ) {
					$arrDays[] = new Warecorp_ICal_Calendar_Day($objFirstDate);
					$objFirstDate->add(1, Zend_Date::DAY);
				}
				$cache->save($arrDays, $cahceKey, array(), Warecorp_Cache::LIFETIME_10DAYS);				
			}
			$this->arrDays = $arrDays;			
		}
		return $this->arrDays;
	}
}
