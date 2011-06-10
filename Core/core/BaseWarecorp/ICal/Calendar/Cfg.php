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

class BaseWarecorp_ICal_Calendar_Cfg
{
    /**
     * @var Warecorp_ICal_Calendar_Cfg
     */
	static private $instance;
	static private $locale;
	static private $wkst;
	static private $weekHourStart = 0;
	static private $weekHourEnd = 23;
	
	/**
	 * @var boolean
	 * If true the tab Map View for events is enabled
	 */
	static private $enableMapView = true;
	
    /**
     * @var boolean default true
     * If true time for event will be displayed in original timezone for registered user
     * if $showOriginalTime = false && $showUserTime == false will be displayed user time only
     * if $showOriginalTime = true && $showUserTime == false will be displayed original time only
     * if $showOriginalTime = false && $showUserTime == true will be displayed user time only
     * if $showOriginalTime = true && $showUserTime == true will be displayed user time and original time
     * value is used for REGISTERED user ONLY
     * @see var $showUserTime
     * @author Artem Sukharev
     */
    static private $showOriginalTime = false;
    /**
     * @var boolean default true
     * If true time for event will be displayed in user timezone for registered user
     * if $showOriginalTime = false && $showUserTime == false will be displayed user time only
     * if $showOriginalTime = true && $showUserTime == false will be displayed original time only
     * if $showOriginalTime = false && $showUserTime == true will be displayed user time only
     * if $showOriginalTime = true && $showUserTime == true will be displayed user time and original time
     * value is used for REGISTERED user ONLY
     * @see var $showOriginalTime
     * @author Artem Sukharev
     */
    static private $showUserTime = true;
    /**
     * @var boolean
     * if true timezone abbreviation will be showed
     * value is used for REGISTERED user ONLY
     * value is used for DEFAULT TIME (time that shows as default) ONLY
     * if $showUserTime = true - DEFAULT TIME is time converted to user timezone
     * if $showUserTime = false - DEFAULT TIME is original time of event
     * @see var $showUserTime
     * @see var $showOriginalTime
     * @author Artem Sukharev
     */
    static private $showTimezoneAbbr = true;
    /**
     * @var boolean
     * If true Events will be sorted in User TZ,
     * else they will be sorted without use any TZ, use date and time only
     * @author Roman Gabrusenok
     */
    static private $dateSortInUserTimezone = true;

    /**
     * @return Warecorp_ICal_Calendar_Cfg
     */
    private function  __construct() {
        //  Singleton, Use self::getInstance()
    }

    public function  __clone() {
        throw new Warecorp_Exception('This class is Singleton, use Warecorp_ICal_Calendar_Cfg::getInstance() instead');
    }

    /**
     * @return void
     */
    static private function initialize()
    {
        self::getInstance();
    }
    
	/**
	 * @return Warecorp_ICal_Calendar_Cfg
	 */
	static public function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self();
			self::$wkst = 'MO';
			self::$locale = Zend_Registry::get('Zend_Locale');
			self::$weekHourStart = 0;
			self::$weekHourEnd = 23;

            $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
            $useEventTZ = (!empty($cfgSite->use_event_tz_from_venue) && $cfgSite->use_event_tz_from_venue != 0) ? true : false;
            if ( $useEventTZ ) {
                self::$showOriginalTime = true;
                self::$showUserTime = false;
                self::$dateSortInUserTimezone = false;
            } else {
                self::$showOriginalTime = false;
                self::$showUserTime = true;
                self::$dateSortInUserTimezone = true;
            }
            unset($cfgSite, $useEventTZ);
		}
		return self::$instance;
	}
	/**
	 * 
	 */
	static public function setWkst($newVal)
	{
        self::initialize();
		self::$wkst = $newVal;
	}
    /**
     * 
     */
	static public function getWkst()
	{
        self::initialize();
		if ( null === self::$wkst ) self::$wkst = 'MO';
		return self::$wkst;
	}
	/**
	 * 
	 */
	static public function setLocale($newVal)
	{
        self::initialize();
		self::$locale = $newVal;
	}
    /**
     * 
     */
	static public function getLocale()
	{
        self::initialize();
		if ( null === self::$locale ) self::$locale = 'en_US';
		return self::$locale;
	}
	/**
	 * Find locale by user
	 * user can be registered or anonymous
	 * @todo implement it 
	 */
	static public function findLocale(Warecorp_User $objUser)
	{
        self::initialize();
	}
	/**
	 * 
	 */
	static public function setWeekHourStart($newVal)
	{
        self::initialize();
		self::$weekHourStart = $newVal;
	}
    /**
     * 
     */
	static public function getWeekHourStart()
	{
        self::initialize();
		if ( null === self::$weekHourStart ) self::$weekHourStart = 0;
		return self::$weekHourStart;
	}
	/**
	 * 
	 */
	static public function setWeekHourEnd($newVal)
	{
        self::initialize();
		self::$weekHourEnd = $newVal;
	}
    /**
     * 
     */
	static public function getWeekHourEnd()
	{
        self::initialize();
		if ( null === self::$weekHourEnd ) self::$weekHourEnd = 23;
		return self::$weekHourEnd;
	}
	/**
	 * 
	 */
	static public function getShowOriginalTime()
	{
        self::initialize();
        if ( null === self::$showOriginalTime ) self::$showOriginalTime = true;
        return self::$showOriginalTime;
	}
    /**
     * 
     */
    static public function setShowOriginalTime($newVal)
    {
        self::initialize();
        self::$showOriginalTime = (boolean) $newVal;
    }
    /**
     * 
     */
    static public function getShowUserTime()
    {
        self::initialize();
        if ( null === self::$showUserTime ) self::$showUserTime = true;
        return self::$showUserTime;
    }
    /**
     * 
     */
    static public function setShowUserTime($newVal)
    {
        self::initialize();
        self::$showUserTime = (boolean) $newVal;
    }
    /**
     * 
     */
    static public function getShowTimezoneAbbr()
    {
        self::initialize();
        if ( null === self::$showTimezoneAbbr ) self::$showTimezoneAbbr = true;
        return self::$showTimezoneAbbr;
    }
    /**
     * 
     */
    static public function setShowTimezoneAbbr($newVal)
    {
        self::initialize();
        self::$showTimezoneAbbr = (boolean) $newVal;
    }
    /**
     * @param boolean $bool
     * @return void
     */
    static public function setDateSortInUserTimezone($bool)
    {
        self::initialize();
        self::$dateSortInUserTimezone = (bool) $bool;
    }
    /**
     * @return boolean
     */
    static public function getDateSortInUserTimezone()
    {
        self::initialize();
        return self::$dateSortInUserTimezone;
    }
    
    static public function isMapViewEnabled()
    {
        return (boolean) self::$enableMapView;
    }
}
