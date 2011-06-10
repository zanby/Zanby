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

/**
 * Warecorp FRAMEWORK
 * @package Warecorp_Video_Gallery_List
 * @author Yury Zolotarsky
 * @version 1.0
 */
abstract class BaseWarecorp_Video_Gallery_List_Abstract extends Warecorp_Abstract_List
{
	/**
	 * set privacy for select
	 */
	private $privacy;
	
	/**
	 * show shared, own or both
	 */
	private $sharingMode;

	/**
	 * show watched, onw or both
	 */
    private $watchingMode;

    /**
     * Constructor
     * @author Artem Sukharev
     */
    function __construct()
    {
    	parent::__construct();
    }

	/**
	 * set privacy for select
	 * @author Artem Sukharev
	 */
	public function getPrivacy()
	{
		if ( $this->privacy === null ) $this->privacy = array(0,1);
		return $this->privacy;
	}

	/**
	 * set privacy for select
	 * @author Artem Sukharev
	 * @param newVal    newVal
	 */
	public function setPrivacy($newVal)
	{
		$this->privacy = $newVal;
		return $this;
	}

	public function getSharingMode()
	{
		if ( $this->sharingMode === null ) 
            $this->sharingMode = array(
                Warecorp_Video_Enum_SharingMode::translate(Warecorp_Video_Enum_SharingMode::OWN), 
		        Warecorp_Video_Enum_SharingMode::translate(Warecorp_Video_Enum_SharingMode::SHARED)
		    );
		return $this->sharingMode;
	}

	public function setSharingMode($newVal)
	{
        if ( is_array($newVal) ) {
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Video_Enum_SharingMode::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect mode');
                }
            }
        } elseif ( strpos($newVal, ';') ) {
            $newVal = explode(';', $newVal);
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Video_Enum_SharingMode::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect mode');
                }
            }
        } elseif ( $newVal == Warecorp_Video_Enum_SharingMode::BOTH ) {
            $newVal = array(Warecorp_Video_Enum_SharingMode::OWN, Warecorp_Video_Enum_SharingMode::SHARED);
        } else {
            if ( !Warecorp_Video_Enum_SharingMode::isIn($newVal) ) {
                throw new Zend_Exception('Incorrect mode');
            }
            $newVal = array($newVal);
        }
        foreach ($newVal as &$_value) {
            $_value = Warecorp_Video_Enum_SharingMode::translate($_value);
        }
        $this->sharingMode = $newVal;
        return $this;
	}
	
    public function getWatchingMode()
    {
        if ( $this->watchingMode === null ) 
            $this->watchingMode = array(
                Warecorp_Video_Enum_WatchingMode::translate(Warecorp_Video_Enum_WatchingMode::OWN), 
                Warecorp_Video_Enum_WatchingMode::translate(Warecorp_Video_Enum_WatchingMode::WATCHED)
            );
        return $this->watchingMode;
    }

    public function setWatchingMode($newVal)
    {
        if ( is_array($newVal) ) {
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Video_Enum_WatchingMode::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect mode');
                }
            }
        } elseif ( strpos($newVal, ';') ) {
            $newVal = explode(';', $newVal);
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Video_Enum_WatchingMode::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect mode');
                }
            }
        } elseif ( $newVal == Warecorp_Video_Enum_WatchingMode::BOTH ) {
            $newVal = array(Warecorp_Video_Enum_WatchingMode::OWN, Warecorp_Video_Enum_WatchingMode::WATCHED);
        } else {
            if ( !Warecorp_Video_Enum_WatchingMode::isIn($newVal) ) {
                throw new Zend_Exception('Incorrect mode');
            }
            $newVal = array($newVal);
        }
        foreach ($newVal as &$_value) {
            $_value = Warecorp_Video_Enum_WatchingMode::translate($_value);
        }
        $this->watchingMode = $newVal;
        return $this;
    }
	
	
    abstract public function getTotalSize($unit = Warecorp_Video_Enum_SizeUnit::BYTE);


    static public function deleteSemiCreatedGalleries()
    {
       return false;
    }
}
?>
