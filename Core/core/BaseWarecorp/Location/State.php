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
class BaseWarecorp_Location_State extends Warecorp_Data_Entity
{
    public $id;
    public $countryId;
    public $name;
    public $code;			// @author Dmitry Kostikov, add code field according with db
    public $status;
    public $source;         // @author Artem Sukharev
    public $isDefault;      // @author Artem Sukharev
    
    private $Country;
    
    /**
    * Factory  
    */
    public static function create($value)
    {
        if ( is_numeric($value) ) {
            $cache = Warecorp_Cache::getFileCache();
            if ( !$state = $cache->load('Warecorp_Location_State_'.$value) ) {
                $state = new Warecorp_Location_State($value);
                $cache->save($state, 'Warecorp_Location_State_'.$value, array(), Warecorp_Cache::LIFETIME_10DAYS);
            }
        } else {
            $state = new Warecorp_Location_State($value);
        }
        return $state;
    }

    /**
     * Constructor.
     *
     */
	public function __construct($value = null)
	{
        parent::__construct('zanby_location__states', array(
            'id'         => 'id',
            'country_id' => 'countryId',
            'name'       => 'name',
            'code'       => 'code',			// @author Dmitry Kostikov, add code field according with db
            'status'     => 'status',
            'source'     => 'source',       // @author Artem Sukharev
            'is_default' => 'isDefault',    // @author Artem Sukharev
        ));

        $this->load($value);
        
        if ( null !== $this->id ) {
            if ( trim($this->code) == '' ) $this->code = $this->name;
        }
	}
	/**
	 * @author Artem Sukharev
	 */
    static public function findByName($stateName, $country = null)
    {
        $dbConn = Zend_Registry::get('DB');
        $query = $dbConn->select()->from('zanby_location__states', '*');
        $query->where('name = ?', $stateName);
        if ( null !== $country ) {
            if ( $country instanceof Warecorp_Location_Country ) $query->where('country_id = ?', $country->id);
            else $query->where('country_id = ?', $country);
        }
        $result = $dbConn->fetchRow($query);
        if ( !$result ) return null;
        return Warecorp_Location_State::create($result);
    }    
    
    static public function findByCode($stateCode, $country = null)
    {
        $dbConn = Zend_Registry::get('DB');
        $query = $dbConn->select()->from('zanby_location__states', '*');
        $query->where('code = ?', $stateCode);
        if ( null !== $country ) {
            if ( $country instanceof Warecorp_Location_Country ) $query->where('country_id = ?', $country->id);
            else $query->where('country_id = ?', $country);
        }
        $result = $dbConn->fetchRow($query);
        if ( !$result ) return null;
        return Warecorp_Location_State::create($result);
    }    
    
	/**
	 * Устанавливает Country для State
	 * @return void
     * Cached
	 */
    public function setCountry()
    {
        $this->Country = Warecorp_Location_Country::create($this->countryId);
    }
    /**
     * Возвращает Country для State
     * @return Warecorp_Location_Country
     * Cached
     */
    public function getCountry()
    {
        if ( $this->Country === null ) $this->setCountry();
        return $this->Country;
    }
	/**
	 * Получить список городов для текущего штата
	 * @return array of Warecorp_Location_City
     * Cached
	 */
	public function getCitiesList()
	{
        /*
		$sql = $this->_db->select()->from('zanby_location__cities', '*')->where('state_id=?', $this->id);
		$cities = $this->_db->fetchAll($sql);
		foreach($cities as &$city) $sity = new Warecorp_Location_City($city);
		return $cities;
        */
        $cache = Warecorp_Cache::getFileCache();
        if ( !$cities = $cache->load('Warecorp_Location_State__getCitiesList__stateid'.$this->id) ) {
            $sql = $this->_db->select()->from('zanby_location__cities', 'id')->where('state_id=?', $this->id);
            $cities = $this->_db->fetchCol($sql);
            foreach($cities as &$city) $city = Warecorp_Location_City::create($city);
            $cache->save($cities, 'Warecorp_Location_State__getCitiesList__stateid'.$this->id, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        return $cities;
	}
	/**
	 * Получить список городов штата для <select>
	 * @return array - возвращает массив пар значений 'id', 'name'
     * Cached
	 */
	public function getCitiesListAssoc($default_value_exist = false)
	{   
        $cache = Warecorp_Cache::getFileCache();
        if ( !$cities = $cache->load('Warecorp_Location_State__getCitiesListAssoc__stateid'.$this->id) ) {
            $sql = $this->_db
                ->select()
                ->from('zanby_location__cities', array('id', 'name'))
                ->where('state_id=?', ( NULL === $this->id) ? new Zend_Db_Expr('NULL') : $this->id)
                ->order('name');
            $cities = $this->_db->fetchPairs($sql);
            $cache->save($cities, 'Warecorp_Location_State__getCitiesListAssoc__stateid'.$this->id, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }                                          
        if ($default_value_exist) $cities = array('0' => '[Select City]') + $cities;
        return $cities;
	}
	
	/**
	 * return array of cities with filter for WA
	 * function is used for ZCCF project to filter cities from WA
	 */
	public function getZCCFCitiesListAssoc()
	{
	    if ( $this->code == "WA" ) {// WA state	    
    	    $list = array("Algona", "Ames Lake", "Baring", "Beaux Arts Village", "Bitter Lake", "Bryn Mawr", "Burien", "Cascade", "Cedar Falls", "Clyde Hill", "Coal Creek", "Cottage Lake", "Covington", "Des Moines", "East Hill-Meridian", "Eastgate", "Fairwood", "Four Corners", "Highlands", "Hunts Point", "Inglewood", "Juanita", "Kingsgate", "Lake Forest Park", "Lake Marcel-Stillwater", "Lake Morton-Berrydale", "Lakeland North", "Lakeland South", "Lea Hill", "Maple Heights-Lake Desire", "Mirrormont", "Milton", "Newcastle", "Newport Hills", "Normandy Park", "Osceola", "Richmond Beach", "Riverton", "Riverbend", "Seatac", "Shoreline", "Shorewood", "Skyway", "Tanner", "Totem Lake", "Tukwila", "Union Hill-Novelty Hill", "White Center", "Yarrow Point", "West Lake Sammamish", "Preston");
    	    $query_users = $this->_db
    	       ->select()->from('zanby_users__accounts as t1', array('t1.city_id'))
    	       ->join('zanby_location__cities as t2', "t1.city_id = t2.id AND  state_id = '{$this->id}'", array('t2.name'))
    	       ->where('city_id IS NOT NULL');
            $query_groups = $this->_db
               ->select()->from('zanby_groups__items as t1', array('t1.city_id'))
               ->join('zanby_location__cities as t2', "t1.city_id = t2.id AND  state_id = '{$this->id}'", array('t2.name'))
               ->where('city_id IS NOT NULL');
            $query_venues = $this->_db
               ->select()->from('zanby_event__venues as t1', array('t1.city_id'))
               ->join('zanby_location__cities as t2', "t1.city_id = t2.id AND  state_id = '{$this->id}'", array('t2.name'))
               ->where('city_id IS NOT NULL');
            $query_list = $this->_db
               ->select()->from('zanby_location__cities', array('id as city_id', 'name'))
               ->where("state_id = '{$this->id}' AND name IN (?)", $list);
               $select = $this->_db->select()->union(array($query_users, $query_groups, $query_venues, $query_list))->order('name');
            //print $select->__toString();exit;
            $res = $this->_db->fetchPairs($select);
            return $res;
	    } else {
	       return $this->getCitiesListAssoc();
	    }
	}
	
    /**
     *  @author Alexander Komarovski 
     */
    public function getMaxMinCoordinates() {
        $cache = Warecorp_Cache::getFileCache();
        $result = $cache->load('getMaxMinCoordinates-State-'.$this->id);
        if (!$result) {
            
            if ( !$cfgStateCoordinates = $cache->load('cfg_state_maxmin_coordinates_xml') ) {
                $cfgStateCoordinates = new Zend_Config_Xml(CONFIG_DIR."cfg.location_maxmin_coordinates.xml", 'state');
                $cache->save($cfgStateCoordinates, 'cfg_state_maxmin_coordinates_xml', array(), Warecorp_Cache::LIFETIME_10DAYS);
            }

            $_key = "s_".$this->id;
            if (isset($cfgStateCoordinates->$_key) ) {
                return array (
                    'maxlongitude'  => $cfgStateCoordinates->$_key->maxlongitude,
                    'maxlatitude'   => $cfgStateCoordinates->$_key->maxlatitude,
                    'minlongitude'  => $cfgStateCoordinates->$_key->minlongitude,
                    'minlatitude'   => $cfgStateCoordinates->$_key->minlatitude,
                );  
            }
            
            $query = $this->_db->select()
            ->from(array('zlz' => 'zanby_location__zipcodes'), array('maxlongitude' => new Zend_Db_Expr('MAX(zlz.longitude)'), 'maxlatitude' => new Zend_Db_Expr('MAX(zlz.latitude)'), 'minlongitude' => new Zend_Db_Expr('MIN(zlz.longitude)'), 'minlatitude' => new Zend_Db_Expr('MIN(zlz.latitude)')))
            ->join(array('zlc' => 'zanby_location__cities'), 'zlc.id = zlz.city_id')
            ->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id')
            ->where('zls.id = ?', $this->id);
            
            $maxMinCoordinates = $this->_db->fetchRow($query);
            //print_r($maxMinCoordinates);die;
            //if (empty($maxMinCoordinates['maxlongitude']) && empty($maxMinCoordinates['minlongitude']) && empty($maxMinCoordinates['maxlatitude']) && empty($maxMinCoordinates['minlatitude'])) {
            //    $query = $this->_db->select()
	        //    ->from(array('zlz' => 'zanby_location__cities'), array('MAX(zlz.longitude) as maxlongitude','MAX(zlz.latitude) as maxlatitude','MIN(zlz.longitude) as minlongitude','MIN(zlz.latitude) as minlatitude'))
	        //    ->join(array('zls' => 'zanby_location__states'), 'zls.id = zlz.state_id')
	        //    ->where('zls.id = ?', $this->id);
            //    $maxMinCoordinates = $this->_db->fetchRow($query);
            //}
            $result = $maxMinCoordinates;
            $cache->save($result, 'getMaxMinCoordinates-State-'.$this->id, array(), 60*60*24*29);
        }
        return $result;
    }
}
