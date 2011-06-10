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

class BaseWarecorp_Location_Alias
{
	private $dbConn;
	private $id;
	private $entity_id;
	private $entity_type;
	private $address;
	private $name;
	private $accuracy;
	private $country;
	private $countryCode;
	private $state;
	private $city;
	private $lat;
	private $long;
	private $north;
	private $south;
	private $east;
	private $west;
	private $timezone;
	
	const TYPE_CITY = 'city';
	
	public function __construct($id = null)
	{
		$this->dbConn = Zend_Registry::get('DB');
		if ( null !== $id ) {
            if ( is_array($id) ) {
                $this->loadData($id);
            } else {
            	$this->load($id);
            }
		}
	}
	
    /**
     * @return unknown
     */
    public function getAddress() {
        return $this->address;
    }
    
    /**
     * @param unknown_type $address
     */
    public function setAddress($address) {
        $this->address = $address;
    }
    
    /**
     * @return unknown
     */
    public function getEntityId() {
        return $this->entity_id;
    }
    
    /**
     * @param unknown_type $entity_id
     */
    public function setEntityId($entity_id) {
        $this->entity_id = $entity_id;
    }
    
    /**
     * @return unknown
     */
    public function getEntityType() {
        return $this->entity_type;
    }
    
    /**
     * @param unknown_type $entity_type
     */
    public function setEntityType($entity_type) {
        $this->entity_type = $entity_type;
    }
    
    /**
     * @return unknown
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->id = $id;
    }
	/**
	 * @return unknown
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param unknown_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	/**
	 * @return unknown
	 */
	public function getAccuracy() {
		return $this->accuracy;
	}
	
	/**
	 * @param unknown_type $accuracy
	 */
	public function setAccuracy($accuracy) {
		$this->accuracy = $accuracy;
	}
	/**
	 * @return unknown
	 */
	public function getCity() {
		return $this->city;
	}
	
	/**
	 * @param unknown_type $city
	 */
	public function setCity($city) {
		$this->city = trim($city);
	}
	
	/**
	 * @return unknown
	 */
	public function getCountry() {
		return $this->country;
	}
	
	/**
	 * @param unknown_type $country
	 */
	public function setCountry($country) {
		$this->country = trim($country);
	}
	
	/**
	 * @return unknown
	 */
	public function getCountryCode() {
		return $this->countryCode;
	}
	
	/**
	 * @param unknown_type $countryCode
	 */
	public function setCountryCode($countryCode) {
		$this->countryCode = trim($countryCode);
	}
	
	/**
	 * @return unknown
	 */
	public function getLat() {
		return $this->lat;
	}
	
	/**
	 * @param unknown_type $lat
	 */
	public function setLat($lat) {
		$this->lat = $lat;
	}
	
	/**
	 * @return unknown
	 */
	public function getLong() {
		return $this->long;
	}
	
	/**
	 * @param unknown_type $long
	 */
	public function setLong($long) {
		$this->long = $long;
	}
	
	/**
	 * @return unknown
	 */
	public function getState() {
		return $this->state;
	}
	
	/**
	 * @param unknown_type $state
	 */
	public function setState($state) {
		$this->state = trim($state);
	}
	/**
	 * @return unknown
	 */
	public function getTimezone() {
		return $this->timezone;
	}
	
	/**
	 * @param unknown_type $timezone
	 */
	public function setTimezone($timezone) {
		$this->timezone = $timezone;
	}
	/**
	 * @return unknown
	 */
	public function getEast() {
		return $this->east;
	}
	
	/**
	 * @param unknown_type $east
	 */
	public function setEast($east) {
		$this->east = $east;
	}
	
	/**
	 * @return unknown
	 */
	public function getNorth() {
		return $this->north;
	}
	
	/**
	 * @param unknown_type $north
	 */
	public function setNorth($north) {
		$this->north = $north;
	}
	
	/**
	 * @return unknown
	 */
	public function getSouth() {
		return $this->south;
	}
	
	/**
	 * @param unknown_type $south
	 */
	public function setSouth($south) {
		$this->south = $south;
	}
	
	/**
	 * @return unknown
	 */
	public function getWest() {
		return $this->west;
	}
	
	/**
	 * @param unknown_type $west
	 */
	public function setWest($west) {
		$this->west = $west;
	}

    /**
     *
     */
    public function getDisplayName()
    {
    	return $this->getCity().', '.$this->getState().', '.$this->getCountry();
    }
    
    /**
     *
     */
    public function load($aliasId)
    {
    	$query = $this->dbConn->select()->from('zanby_location__alias', '*');
    	$query->where('id = ?', $aliasId);
    	$result = $this->dbConn->fetchRow($query);
    	if ( $result ) $this->loadData($result);
    }
    
    /**
     *
     */
	public function loadData($data)
	{
        if ( !is_array($data) ) throw new Zend_Exception('Incorrect data format');
        $this->setId($data['id']);
        $this->setEntityType($data['alias_entity_type']);
        $this->setEntityId($data['alias_entity_id']);
        $this->setName($data['alias_name']);
        $this->setAddress($data['alias_address']);
        $this->setAccuracy($data['alias_accuracy']);
        $this->setCountry($data['alias_country']);
        $this->setCountryCode($data['alias_countrycode']);
        $this->setState($data['alias_state']);
        $this->setCity($data['alias_city']);
        $this->setLong($data['alias_long']);
        $this->setLat($data['alias_lat']);
        $this->setTimezone($data['alias_timezone']);
        $this->setNorth($data['alias_north']);
        $this->setSouth($data['alias_south']);
        $this->setEast($data['alias_east']);
        $this->setWest($data['alias_west']);
        
	}
	
    /**
     *
     */
	public function save()
	{
		$data = array();
		$data['alias_entity_type']    = $this->getEntityType();
		$data['alias_entity_id']      = $this->getEntityId();
		$data['alias_name']           = $this->getName();
		$data['alias_address']        = $this->getAddress();
		$data['alias_accuracy']       = $this->getAccuracy();
		$data['alias_country']        = $this->getCountry();
		$data['alias_countryCode']    = $this->getCountryCode();
		$data['alias_state']          = $this->getState();
		$data['alias_city']           = $this->getCity();
		$data['alias_long']           = $this->getLong();
		$data['alias_lat']            = $this->getLat();
		$data['alias_timezone']       = $this->getTimezone();
		$data['alias_north']          = $this->getNorth();
		$data['alias_south']          = $this->getSouth();
		$data['alias_east']           = $this->getEast();
		$data['alias_west']           = $this->getWest();
		$this->dbConn->insert('zanby_location__alias', $data);
		$this->setId($this->dbConn->lastInsertId());
	}

    /**
     *
     */
	static public function prepareQueryString($queryString)
	{
		$queryString = trim($queryString);
		return $queryString;
	}
	
    /**
     * @param string $queryString
     * @param Warecorp_Location_Country $country
     */
	static public function findByName($queryString, $country = null, $type = 'city') 
	{
        $queryString = self::prepareQueryString($queryString);	   	
		$dbConn = Zend_Registry::get( 'DB' );
		$query = $dbConn->select()->from( 'zanby_location__alias', '*' );
		$query->where( 'alias_name = ?', $queryString );
        if ( $country ) $query->where( 'alias_countryCode = ?', $country->code );
        if ( $type )    $query->where( 'alias_entity_type = ?', $type );
		if ( $result = $dbConn->fetchAll($query) ) {
			foreach ( $result as &$_result ) {
                $_result = new Warecorp_Location_Alias($_result);
			}
			return $result;
		} else return null;
	}
	
    /**
     * @param string $queryString
     */
	static public function checkByName($queryString) 
	{
		$queryString = self::prepareQueryString($queryString);
		$dbConn = Zend_Registry::get( 'DB' );
		$query = $dbConn->select()->from( 'zanby_location__alias', new Zend_Db_Expr('COUNT(id)') );
		$query->where( 'alias_name = ?', $queryString );
		if ( $dbConn->fetchOne($query) ) return true;
		else return false;
	}

    /**
     * @param string $queryString
     * @param Warecorp_Location_Country $country
     */
    static public function checkByNameAndCountry($queryString, $country) 
    {
        $queryString = self::prepareQueryString($queryString);
        $dbConn = Zend_Registry::get( 'DB' );
        $query = $dbConn->select()->from( 'zanby_location__alias', new Zend_Db_Expr('COUNT(id)') );
        $query->where( 'alias_name = ?', $queryString );
        $query->where( 'alias_countryCode = ?', $country->code );
        if ( $dbConn->fetchOne($query) ) return true;
        else return false;
    }
}
