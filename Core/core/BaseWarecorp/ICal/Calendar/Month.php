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

class BaseWarecorp_ICal_Calendar_Month
{
	private $month;
	private $year;
	private $arrWeeks;
	private $arrDays;
	
	private $cacheTime;
	
	public function __construct($intMonth, $intYear)
	{
		$this->cacheTime = Warecorp_Cache::LIFETIME_10DAYS;		// 10 days
		$this->setMonth($intMonth);
		$this->setYear($intYear);
	}
	
	public function getMonth()
	{
		return $this->month;
	}
	
	/**
	 * 1 or 2 DIGIT value
	 */
	public function setMonth($newVal)
	{
		$this->month = $newVal;
	}
	
	public function getYear()
	{
		return $this->year;
	}
	
	public function setYear($newVal)
	{
		$this->year = $newVal;
	}
	
	public function getMonthName()
	{
        return Warecorp_ICal_Const::$monthsOptions[floor($this->getMonth())];
        /*
		date_default_timezone_set('UTC');
		$objFirstDayDate    = new Zend_Date(sprintf('%04d', $this->getYear()).'-'.sprintf('%02d', $this->getMonth()).'-01', Zend_Date::ISO_8601, 'en_US');
		return $objFirstDayDate->get(Zend_Date::MONTH_NAME);        
        */
	}
	
	/**
	 * Ruturn Warecorp_ICal_Calendar_Week objects for mouths
	 * Cahced
	 */
	public function getWeeks()
	{
		/*
		date_default_timezone_set('UTC');
		
		$objFirstDayDate    = new Zend_Date(sprintf('%04d', $this->getYear()).'-'.sprintf('%02d', $this->getMonth()).'-01', Zend_Date::ISO_8601);        
		$daysInMonth        = $objFirstDayDate->get(Zend_Date::MONTH_DAYS);
		$objLastDayDate     = new Zend_Date(sprintf('%04d', $this->getYear()).'-'.sprintf('%02d', $this->getMonth()).'-'.sprintf('%02d', $daysInMonth), Zend_Date::ISO_8601);
		
		$objFirstWeekStartDate = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($objFirstDayDate, Warecorp_ICal_Calendar_Cfg::getWkst());
		$objLastWeekStartDate = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($objLastDayDate, Warecorp_ICal_Calendar_Cfg::getWkst());

		
		while ( $objFirstWeekStartDate->isEarlier($objLastWeekStartDate) || $objFirstWeekStartDate->equals($objLastWeekStartDate) ) {
			$intWeekNo = $objFirstWeekStartDate->get(Zend_Date::WEEK);
			$this->arrWeeks[$intWeekNo] = new Warecorp_ICal_Calendar_Week();
			$this->arrWeeks[$intWeekNo]->loadByStartDate($objFirstWeekStartDate);
			$objFirstWeekStartDate->add(1, Zend_Date::WEEK);
		}
		return $this->arrWeeks;
		*/

		$cache = Warecorp_Cache::getFileCache();
		$cahceKey = 'Warecorp_ICal_Calendar_Month__getWeeks__'.$this->getYear().$this->getMonth().Warecorp_ICal_Calendar_Cfg::getWkst();
		if ( !$arrWeeks = $cache->load($cahceKey) ) {
			date_default_timezone_set('UTC');
			
			$objFirstDayDate    = new Zend_Date(sprintf('%04d', $this->getYear()).'-'.sprintf('%02d', $this->getMonth()).'-01', Zend_Date::ISO_8601);        
			$daysInMonth        = $objFirstDayDate->get(Zend_Date::MONTH_DAYS);
			$objLastDayDate     = new Zend_Date(sprintf('%04d', $this->getYear()).'-'.sprintf('%02d', $this->getMonth()).'-'.sprintf('%02d', $daysInMonth), Zend_Date::ISO_8601);
			
			$objFirstWeekStartDate = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($objFirstDayDate, Warecorp_ICal_Calendar_Cfg::getWkst());
			$objLastWeekStartDate = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($objLastDayDate, Warecorp_ICal_Calendar_Cfg::getWkst());
	
			
			while ( $objFirstWeekStartDate->isEarlier($objLastWeekStartDate) || $objFirstWeekStartDate->equals($objLastWeekStartDate) ) {
				$intWeekNo = $objFirstWeekStartDate->get(Zend_Date::WEEK);
				$arrWeeks[$intWeekNo] = new Warecorp_ICal_Calendar_Week();
				$arrWeeks[$intWeekNo]->loadByStartDate($objFirstWeekStartDate);
				$objFirstWeekStartDate->add(1, Zend_Date::WEEK);
			}
			$cache->save($arrWeeks, $cacheKey, array(), $this->cacheTime);			
		}
		$this->arrWeeks = $arrWeeks;
		return $this->arrWeeks;
	}

    /**
    * 
    */
	public function getWeekdaysHeader($format = 'FULL')
	{
		switch ( $format ) {
			case 'FULL' :
				switch ( Warecorp_ICal_Calendar_Cfg::getWkst() ) {
					case 'SU' : return array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
					case 'MO' : return array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
				}
				break;
			case '2CHAR' : 
				switch ( Warecorp_ICal_Calendar_Cfg::getWkst() ) {
					case 'SU' : return array("Su", "Mo", "Tu", "We", "Th", "Fr", "Sa");
					case 'MO' : return array("Mo", "Tu", "We", "Th", "Fr", "Sa", "Su");
				}
				break;
			case '3CHAR' : 
				switch ( Warecorp_ICal_Calendar_Cfg::getWkst() ) {
					case 'SU' : return array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
					case 'MO' : return array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
				}
				break;
		}
	}
}
