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

class BaseWarecorp_ICal_Calendar_Day
{
	private $date;
    private $strDate;
    private $year;
    private $month;
    private $day;
	
	public function setDate(Zend_Date $newVal)
	{
		$this->date = $newVal;
	}
	public function getDate()
	{
		return $this->date;
	}
	
	public function getYear()
	{
        if ( null === $this->year ) $this->year = $this->getDate()->get(Zend_Date::YEAR);
		return $this->year;
	}
	
	public function getMonth()
	{
        if ( null === $this->month ) $this->month = $this->getDate()->get(Zend_Date::MONTH_SHORT);
		return $this->month;
	}
	
	public function getDay()
	{
        if ( null === $this->day ) $this->day = $this->getDate()->get(Zend_Date::DAY_SHORT);
		return $this->day;
	}
	
	public function getDateAsString()
	{
        if ( null === $this->strDate ) $this->strDate = $this->getDate()->toString('yyyy-MM-dd');
		return $this->strDate;
	}
	
	public function __construct(Zend_Date $objDate)
	{
		$this->setDate(clone $objDate);
	}
	
	public function getDayHours()
	{
		$arrHours = array();
		for ( $i = Warecorp_ICal_Calendar_Cfg::getWeekHourStart(); $i <= Warecorp_ICal_Calendar_Cfg::getWeekHourEnd(); $i++ ) {
			$arrHours[sprintf('%02d', $i).'00'] = $i.':00';
		}
		return $arrHours;
	}
}
