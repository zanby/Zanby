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

class BaseWarecorp_ICal_Alarm 
{
	private $action;
	private $trigger;
	private $summary;
	private $description;
	private $duration;
	private $repeat;
	private $attendee;
	private $attach;
	
	public function setAction($newVal)
	{
		if ( Warecorp_ICal_Alarm_Action::inEnum($this->action) ) $this->action = $newVal;
		return $this;
	}
	public function getAction()
	{
		return $this->action;
	}

	public function setTrigger($newVal)
	{
		$this->trigger = $newVal;
		return $this;
	}
	public function getTrigger()
	{
		return $this->trigger;
	}

	public function setSummary($newVal)
	{
		$this->summary = $newVal;
		return $this;
	}
	public function getSummary()
	{
		return $this->summary;
	}

	public function setDescription($newVal)
	{
		$this->description = $newVal;        
		return $this;
	}
	public function getDescription()            
	{
		return $this->description;
	}

	public function setDuration($newVal)
	{
		$this->duration = $newVal;
		return $this;
	}
	public function getDuration()
	{
		return $this->duration;
	}

	public function setRepeat($newVal)
	{
		$this->repeat = $newVal;
		return $this;
	}
	public function getRepeat()
	{
		return $this->repeat;
	}

	public function setAttendee($newVal)
	{
		$this->attendee = $newVal;
		return $this;
	}
	public function getAttendee()
	{
		return $this->attendee;
	}

	public function setAttach($newVal)
	{
		$this->attach = $newVal;
		return $this;
	}
	public function getAttach()
	{
		return $this->attach;
	}
}
