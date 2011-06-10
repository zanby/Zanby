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
 *
 * @package    Warecorp_Location
 * @copyright  Copyright (c) 2006
 */

/**
 *
 *
 */
class BaseWarecorp_Location_Timezone_Item extends Warecorp_Data_Entity
{
    private $id;
    private $name;
    private $tz_name;
    private $zone;

    /**
     * Constructor.
     *
     */
    public function __construct( $id = null )
    {
        parent::__construct('zanby_location__timezones',
                            array('id' => 'id' , 
                                  'name' => 'name',
                                  'tz_name' => 'tz_name',
                                  'zone' => 'zone',
                                  'private' => 'private')
                            );
        if ( null  !== $id) {
            $this->loadByPk( $id );
        }
    }
    /**
     * return timezone id
     * 
     * @return int
     * @author Eugene Kirdzei
     */
    public function getId ()
    {
        return $this->id;
    }
    /**
     * Set id
     * 
     * @param int $id
     * @author Eugene Kirdzei
     */
    public function setId ($id)
    {
        $this->id = $id;
        return $id;
    }
    
    /**
     * Return timezone output name 
     * 
     * @return string
     * @author Eugene Kirdzei
     */
    public function getName ()
    {
        return $this->name;
    }
    /**
     * Set timezone output name
     * @param string $name
     * @author Eugene Kirdzei 
     */
    public function setName ($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * Return timezone name
     * 
     * @return string
     * @author Eugene Kirdzei
     */
    public function getTz_name ()
    {
        return $this->tz_name;
    }
    /**
     * Set timezone name
     * 
     * @param string $tz_name
     * @author Eugene Kirdzei
     */
    public function setTz_name ($tz_name)
    {
        $this->tz_name = $tz_name;
        return $this;
    }
    /**
     * Return zone abbreviation
     * 
     * @return string
     * @author Eugene Kirdzei
     */
    public function getZone ()
    {
        return $this->zone;
    }
    
    /**
     * Set zone abbreviation
     * @param string $zone
     * @author Eugene Kirdzei
     */
    public function setZone ($zone)
    {
        $this->zone = $zone;
        return $this;
    }

    /**
     * Get time zone data by name
     *
     * @param string $name
     * @return array
     * @author Ivan Meleshko
     * @todo in PDO (AND)
     */
    public function getTimezoneByName($name)
    {
        $select = $this->_db->select()
                       ->from(array('tn' => 'mysql.time_zone_name'), '*')
                       ->joinLeft(array('tt2' => 'mysql.time_zone_transition'),'tt2.Time_zone_id = tn.Time_zone_id')
                       ->joinLeft(array('tt' => 'mysql.time_zone_transition_type'), 'tt.Time_zone_id = tn.Time_zone_id AND tt.Transition_type_id = tt2.Transition_type_id')
                       ->where('tn.Name = ?', $name)
                       ->where('tt2.Transition_time <= UNIX_TIMESTAMP(NOW())')
                       ->order('tt2.Transition_time DESC')
                       ->limit(1);
        $result = $this->_db->fetchRow($select);
        return $result;
    }

    /**
     * Get specific zanby timezones
     *
     * @return array
     * @author Ivan Meleshko
     */
    public function getZanbyTimezonesAssoc()
    {
        $sql = $this->_db->select()->from('zanby_location__timezones', array('id', 'tz_name', 'name'));
        $timezones = $this->_db->fetchAll($sql);
        return $timezones;
    }

    /**
     * Get specific zanby timezones names (for forms)
     *
     * @return array
     * @author Ivan Meleshko
     */
    public function getZanbyTimezonesNamesAssoc()
    {
        $zanby_timezones = $this->getZanbyTimezonesAssoc();
        $ret = array();
        foreach ($zanby_timezones as $k => &$v)
        {
            $time_zone = $this->getTimezoneByName($v['tz_name']);
            $minutes = ($time_zone['offset'] % 3600) / 60;
            $hour = (int)(($time_zone['offset'] / 3600));
            if ($hour >= 0)
                $hour = '+'.$hour;
            if ($minutes == 0)
                $time = $hour;
            else
                $time = $hour.':'.abs($minutes);
            $ret[$v['tz_name']] = trim($v['name']." (".$time_zone['abbreviation'].") = GMT ".$time);
        }
        return $ret;
    }

    public function convertUTC2Zone($datetime, $zone = "America/New_York")
    {
        $select = $this->_db->select();
        $select->from('DUAL', array('now_time' => new Zend_Db_Expr('CONVERT_TZ("'.$datetime.'", "UTC", "'.$zone.'")')));
        $time = $this->_db->fetchOne($select);
        return $time;
    }

    public function convertZone2UTC($datetime, $zone = "America/New_York")
    {
        $select = $this->_db->select();
        $select->from('DUAL', array('now_time' => new Zend_Db_Expr('CONVERT_TZ("'.$datetime.'", "'.$zone.'", "UTC")')));
        $time = $this->_db->fetchOne($select);
        return $time;
    }

    public function convertTimezone($datetime, $zone, $zone2)
    {
        $select = $this->_db->select();
        $select->from('DUAL', array('now_time' => new Zend_Db_Expr('CONVERT_TZ("'.$datetime.'", "'.$zone.'", "'.$zone2.'")')));
        $time = $this->_db->fetchOne($select);
        return $time;
    }
}

