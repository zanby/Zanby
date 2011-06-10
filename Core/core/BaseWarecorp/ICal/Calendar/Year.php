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

class BaseWarecorp_ICal_Calendar_Year
{
	private $year;
	private $months;
	private $showMonths;
	
	public function __construct($intYear)
	{
		$this->setYear($intYear);
	}
	
	public function getYear()
	{
		return $this->year;
	}
	
	public function setYear($newVal)
	{
		$this->year = $newVal;
		return $this;
	}
	
	public function getShowMonths()
	{
		if ( null === $this->showMonths ) return array(1,2,3,4,5,6,7,8,9,10,11,12);
		else {
			sort($this->showMonths);
			return $this->showMonths;
		}
		
	}
	
	public function setShowMonths($newVal)
	{
		if ( !is_array($newVal) ) {
			$split = explode(',', $newVal);
			foreach ( $split as $month ) {
				if ( $month >= 1 && $month <= 12 ) {
					$this->showMonths[] = $month;
				}
			}
		} else {
			$this->showMonths = $newVal;
		}
		return $this;
	}
	
	public function getMonths()
	{
		foreach ( $this->getShowMonths() as $month ) {
			$this->months[$month] = new Warecorp_ICal_Calendar_Month($month, $this->getYear());
		}
		return $this->months;
	}
	
}
