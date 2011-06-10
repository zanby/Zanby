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
class BaseWarecorp_Location_City extends Warecorp_Data_Entity
{
    public $id;
    public $stateId;
    public $name;
    public $status;
    public $source;         // @author Artem Sukharev

    private $State = null;
    private $Latitude = null;
    private $Longitude = null;

    /**
    * Factory
    */
    public static function create($value)
    {

        if ( is_numeric($value) ) {
            $cache = Warecorp_Cache::getFileCache();
            if ( !$city = $cache->load('Warecorp_Location_City_'.$value) ) {
                $city = new Warecorp_Location_City($value);
                $cache->save($city, 'Warecorp_Location_City_'.$value, array(), Warecorp_Cache::LIFETIME_10DAYS);
            }
        } else {
            $city = new Warecorp_Location_City($value);
        }
        return $city;
    }

    /**
     * Constructor.
     *
     */
	public function __construct($value = null)
	{
        parent::__construct('zanby_location__cities', array(
            'id'        => 'id',
            'state_id'  => 'stateId',
            'name'      => 'name',
            'status'    => 'status',
            'source'    => 'source'        // @author Artem Sukharev
        ));
        $this->load($value);
	}
	/**
	 * @return Warecorp_Location_City
	 * @author Artem Sukharev
	 */
    static public function findByName($cityName, $state = null)
    {
        $dbConn = Zend_Registry::get('DB');
        $query = $dbConn->select()->from('zanby_location__cities', '*');
        $query->where('name = ?', $cityName);
        if ( null !== $state ) {
            if ( $state instanceof Warecorp_Location_State ) $query->where('state_id = ?', $state->id);
            else $query->where('state_id = ?', $state);
        }
        $result = $dbConn->fetchRow($query);
        if ( !$result ) return null;
        return Warecorp_Location_City::create($result);
    }
	/**
	 * Устанавливает State для City
	 * @return void
     * Cached
	 */
    public function setState()
    {
        $this->State = Warecorp_Location_State::create($this->stateId);
    }
    /**
     * Возвращает State для City
     * @return Warecorp_Location_State
     * Cached
     */
    public function getState()
    {
        if ( $this->State === null ) $this->setState();
        return $this->State;
    }
    /**
     *  @param float $lat City's Latitude
     *  @param float $long City's Longitude
     *  @param string|null $ccode Country's Code (Ex: US, GM)
     *  @return Warecorp_Location_City|null
     */
    static public function cityByCoord($lat, $long, $ccode=null)
    {
        $db = Zend_Registry::get('DB');
        $query = $db->select()
            ->from(array('zlc' => 'zanby_location__cities'), array("zlc.id", 'dist' => new Zend_Db_Expr("sqrt(pow(".$db->quote($lat)."-zlc.latitude, 2) + pow(".$db->quote($long)."-zlc.longitude, 2))")))
            ->order('dist ASC')
            ->limit(1, 0);
        if ( null !== $ccode ) {
            $query
                ->join(array('zls' => 'zanby_location__states'), 'zlc.state_id=zls.id', array())
                ->join(array('zlco' => 'zanby_location__countries'), 'zlco.id=zls.country_id', array())
                ->where('zlco.code = ?', strtoupper($ccode));
        }
        $result = $db->fetchPairs($query);
        if ( $result ) {
            list($id, $dist) = each($result);
            return Warecorp_Location_City::create($id);
        }
        else return null;
    }
	/**
	 * get zipcodes list for the city
	 * Cached
	 */
	public function getZipcodesList()
	{
        /*
		$sql = $this->_db->select()->from('zanby_location__zipcodes', 'id')->where('city_id=?', $this->id);
		$zipcodes = $this->_db->fetchCol($sql);
		foreach ($zipcodes as &$zip) $zip = new Warecorp_Location_Zipcode($zip);
		return $zipcodes;
        */
        $cache = Warecorp_Cache::getFileCache();
        if ( !$zipcodes = $cache->load('Warecorp_Location_City__getZipcodesList__cityid'.$this->id) ) {
            $sql = $this->_db
                ->select()
                ->from('zanby_location__zipcodes', 'id')
                ->where('city_id=?', (NULL === $this->id) ? new Zend_Db_Expr('NULL') : $this->id, 'INTEGER');
            $zipcodes = $this->_db->fetchCol($sql);
            foreach ($zipcodes as &$zip) $zip = Warecorp_Location_Zipcode::create($zip);
            $cache->save($zipcodes, 'Warecorp_Location_City__getZipcodesList__cityid'.$this->id, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        return $zipcodes;
	}
	/**
	 * get zipcodes list for the city
	 * Cached
	 */
	public function getZipcodesListAssoc()
	{
        /*
		$sql = $this->_db->select()->from('zanby_location__zipcodes', array('id', 'zipcode'))->where('city_id=?', $this->id)->order('zipcode');
		$zipcodes = $this->_db->fetchPairs($sql);
		return $zipcodes;
        */
        $cache = Warecorp_Cache::getFileCache();
        if ( !$zipcodes = $cache->load('Warecorp_Location_City__getZipcodesListAssoc__cityid'.$this->id) ) {
            $sql = $this->_db
                ->select()
                ->from('zanby_location__zipcodes', array('id', 'zipcode'))
                ->where('city_id=?', (NULL === $this->id) ? new Zend_Db_Expr('NULL') : $this->id, 'INTEGER')
                ->order('zipcode');
            $zipcodes = $this->_db->fetchPairs($sql);
            $cache->save($zipcodes, 'Warecorp_Location_City__getZipcodesListAssoc__cityid'.$this->id, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        return $zipcodes;
	}
	/**
	 * set Latitude Longitude
	 * @author Vitaly Targonsky
	 */
	public function setLatitudeLongitude()
	{
        $sql = $this->_db
            ->select()
            ->from(array('zlc' => 'zanby_location__cities'), array('longitude', 'latitude'))
            ->where('zlc.id = ?', (NULL === $this->id) ? new Zend_Db_Expr('NULL') : $this->id, 'INTEGER');
        $coord = $this->_db->fetchRow($sql);
        if ( empty($coord['longitude']) && empty($coord['latitude']) ) {
            $sql = $this->_db
                ->select()
                ->from(array('zlz' => 'zanby_location__zipcodes'), array('longitude', 'latitude'))
                ->where('zlz.city_id = ?', (NULL === $this->id) ? new Zend_Db_Expr('NULL') : $this->id, 'INTEGER');
            $coord = $this->_db->fetchRow($sql);
        }
	    $this->Latitude    = empty($coord['latitude']) ? 0 : $coord['latitude'];
	    $this->Longitude   = empty($coord['longitude']) ? 0 : $coord['longitude'];
	}
	/**
	 * get Latitude
	 * @author Vitaly Targonsky
	 */
	public function getLatitude()
	{
        if ( $this->Latitude === null ) $this->setLatitudeLongitude();
        return $this->Latitude;
	}
	/**
	 * get Longitude
	 * @author Vitaly Targonsky
	 */
	public function getLongitude()
	{
        if ( $this->Longitude === null ) $this->setLatitudeLongitude();
        return $this->Longitude;
	}

	/**
	 *
	 */
    public function getTimeZone()
    {
        $query = $this->_db->select()
            ->from(array('zlc' => 'zanby_location__cities'), array())
            ->join(array('zlt' => 'zanby_location__timezones'), 'zlc.timezone_id = zlt.id', array('zlt.tz_name'))
            ->where('zlc.id = ?', (NULL === $this->id) ? new Zend_Db_Expr('NULL') : $this->id, 'INTEGER');
        $result = $this->_db->fetchOne($query);
        if (empty($result)) return 'Europe/London'; else return $result;
    }

    /**
    */
    public function getTimezoneId()
    {
        if ( null !== $this->id) {
            $query = $this->_db->select()
                ->from('zanby_location__cities', 'timezone_id')
                ->where('id = ?', $this->id);
            return $this->_db->fetchOne($query);
        }
        return null;
    }

    public function updateCityInfo($latitude = null, $longitude = null, $olson_name = null)
    {
		$data = array();
		if ( $latitude )  $data['latitude']   = $latitude;
		if ( $longitude ) $data['longitude']  = $longitude;
		if ( $olson_name ) {
			$data['olson_name'] = $olson_name;

            $query = $this->_db->select()->from('zanby_location__timezones', 'id');
            $query->where('tz_name = ?', $olson_name);
            $result = $this->_db->fetchOne($query);
            if ( $result ) $data['timezone_id'] = $result;
			else {
                $query = $this->_db->select()->from('zanby_location__timezones_olson', 'timezone_id');
                $query->where('olson_name = ?', $olson_name);
		        $result = $this->_db->fetchOne($query);
		        if ( $result ) $data['timezone_id'] = $result;
			}
		}
		if ( sizeof($data) != 0 ) {
		  $where = $this->_db->quoteInto('id = ?', (NULL === $this->id) ? new Zend_Db_Expr('NULL') : $this->id, 'INTEGER');
		  $this->_db->update('zanby_location__cities', $data, $where);
		}

    	//$this->_db
    }
}
