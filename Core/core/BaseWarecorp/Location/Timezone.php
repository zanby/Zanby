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
class BaseWarecorp_Location_Timezone extends Warecorp_Data_Entity
{
    public $id;
    public $name;
    public $tz_name;
    public $zone;

    /**
     * Constructor.
     *
     */
	public function __construct()
	{
	    $this->_db = Zend_Registry::get("DB");
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
        $sql = $this->_db->select()
            ->from(array('z' => 'zanby_location__timezones'), array('z.tz_name', 'z.name')) 
            ->join(array('tn' => 'mysql.time_zone_name'), 'z.tz_name = tn.Name', array())
            ->joinLeft(array('tt2' => 'mysql.time_zone_transition'),'tt2.Time_zone_id = tn.Time_zone_id', array())
            ->joinLeft(array('tt' => 'mysql.time_zone_transition_type'), 'tt.Time_zone_id = tn.Time_zone_id AND tt.Transition_type_id = tt2.Transition_type_id', array('tt.offset'))
            ->where('tt2.Transition_time <= UNIX_TIMESTAMP(NOW())')
            ->order('z.id');
        $zanby_timezones = $this->_db->fetchAll($sql);
        $ret = array();
        foreach ($zanby_timezones as $k => &$v)
        {
            $minutes = ($v['offset'] % 3600) / 60;
            $hour = (int)(($v['offset'] / 3600));
            if ($hour >= 0)
                $hour = '+'.$hour;
            if ($minutes == 0)
                $time = $hour;
            else
                $time = $hour.':'.abs($minutes);
            $ret[$v['tz_name']] = trim($v['name']);
            //$ret[$v['tz_name']] = trim($v['name']." (".$time_zone['abbreviation'].") = GMT ".$time);
        }
        return $ret;
    }

    public function convertUTC2Zone($datetime, $zone = "UTC")
    {
        $select = $this->_db->select();
        if ( is_int($datetime) )
            $select->from('DUAL', array('now_time' => new Zend_Db_Expr('CONVERT_TZ("'.date('Y-m-d H:i:s', $datetime).'", "UTC", "'.$zone.'")')));
        else $select->from('DUAL', array('now_time' => new Zend_Db_Expr('CONVERT_TZ("'.$datetime.'", "UTC", "'.$zone.'")')));
        $time = $this->_db->fetchOne($select);
        return $time;
    }

    public function convertZone2UTC($datetime, $zone = "UTC")
    {
        $select = $this->_db->select();
        if ( is_int($datetime) )
            $select->from('DUAL', array('now_time' => new Zend_Db_Expr('CONVERT_TZ("'.date('Y-m-d H:i:s', $datetime).'", "'.$zone.'", "UTC")')));
        else $select->from('DUAL', array('now_time' => new Zend_Db_Expr('CONVERT_TZ("'.$datetime.'", "'.$zone.'", "UTC")')));
        $time = $this->_db->fetchOne($select);
        return $time;
    }

    public function convertTimezone($datetime, $zone, $zone2)
    {
        $select = $this->_db->select();
        if ( is_int($datetime) )
        $select->from('DUAL', array('now_time' => new Zend_Db_Expr('CONVERT_TZ("'.date('Y-m-d H:i:s', $datetime).'", "'.$zone.'", "'.$zone2.'")')));
        else $select->from('DUAL', array('now_time' => new Zend_Db_Expr('CONVERT_TZ("'.$datetime.'", "'.$zone.'", "'.$zone2.'")')));
        $time = $this->_db->fetchOne($select);
        return $time;
    }
}

