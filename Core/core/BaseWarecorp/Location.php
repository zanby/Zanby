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
 * @package    Warecorp_Location
 * @copyright  Copyright (c) 2006
 */
class BaseWarecorp_Location
{
	/**
	 * Db connection object.
	 * @var object
	 */
    public $_db;

    /**
     * Constructor.
     *
     */
	public function __construct()
	{
	    $this->_db = Zend_Registry::get("DB");
	}

	/**
	 * get countries list
	 * @return array of objets
     * Cached
	 */
	public static function getCountriesList()
	{
        /*
        $db = Zend_Registry::get("DB");
        $sql = $db->select()->from('zanby_location__countries', 'id');
        $countries = $db->fetchCol($sql);
        foreach ($countries as &$country) $country = Warecorp_Location_Country::create($country);
        return $countries;
        */
        $cache = Warecorp_Cache::getFileCache();
        if ( !$countries = $cache->load('Warecorp_Location__getCountriesList') ) {
            $db = Zend_Registry::get("DB");
            $sql = $db->select()->from('zanby_location__countries', 'id');
            $countries = $db->fetchCol($sql);
            foreach ($countries as &$country) $country = Warecorp_Location_Country::create($country);
            $cache->save($countries, 'Warecorp_Location__getCountriesList', array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        return $countries;
	}

	/**
	 * get countries list
	 * @return array
     * Cached 
	 */
	public static function getCountriesListAssoc($default_value_exist = false)
	{    
        /* 
	    $db = Zend_Registry::get("DB");
		$sql = $db->select()->from('zanby_location__countries', array('id', 'name'))
		          ->order('sort_order')
		          ->order('name ASC');
		if ($default_value_exist) {
		   $countries[0] = "[Select Country]";
		   $countries += $db->fetchPairs($sql);
		}
		else $countries = $db->fetchPairs($sql);           
		return $countries;
        */
        $cache = Warecorp_Cache::getFileCache();
        if ( !$countries = $cache->load('Warecorp_Location__getCountriesListAssoc') ) {
            $db = Zend_Registry::get("DB");
            $sql = $db->select()->from('zanby_location__countries', array('id', 'name'))->order('sort_order')->order('name ASC');
            $countries = $db->fetchPairs($sql);           
            $cache->save($countries, 'Warecorp_Location__getCountriesListAssoc', array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        if ($default_value_exist) $countries = array('0' => '[Select Country]') + $countries;
        return $countries;
	}

    /**
    * @desc 
    */
	public static function getCountriesListByIds($ids)
	{
	    $db = Zend_Registry::get("DB");
		$sql = $db->select()->from('zanby_location__countries', 'id')->where('id IN (?)', $ids)->order('name ASC');
		$countries = $db->fetchCol($sql);
		foreach ($countries as &$country) $country = Warecorp_Location_Country::create($country);
		return $countries;
	}
	   
    /**
     * Return array of city hash with max created users
     * @param int $stateFilter - id of state
     * @param int $countryFilter - id of country
     * @param int $limit - count of items per page
     * @return array of Warecorp_Location_Sity
     * @author Halauniou
     */
    static function getUsersTopCitiesList($stateFilter = null, $countryFilter = null, $limit = 0){
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('view_users__top_locations',array('city_id', 'city_name', 'users_count'));

        if ( $stateFilter !== null )    $select->where("state_id = ?", $stateFilter);
        if ( $countryFilter !== null )  $select->where("country_id = ?", $countryFilter);
        if ( $limit != 0) $select->limit(floor($limit));

        $select->group('city_name');
        $select->order('users_count DESC');
        $cities = $db->fetchAll($select);
        return $cities;
    }

    /**
     * Return array of states hash with max created users
     * @param int $countryFilter - id of country
     * @param int $limit - count of items per page 
     * @return array of Warecorp_Location_State    
     * @author Halauniou
     */
    static function getUsersTopStatesList($countryFilter = null, $limit = 0){
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('view_users__top_locations',array(new Zend_Db_Expr('DISTINCT state_id'), 'state_name', 'users_count' => 'sum(users_count)'));

        if ( $countryFilter !== null ) $select->where("country_id = ?", $countryFilter);
        if ($limit != 0) $select->limit(floor($limit));

        $select->order('users_count DESC');
        $select->group('state_name');
        $states = $db->fetchAll($select);
        return $states;
    }

    /**
     * Return array of countries hash with max created users
     * @param int $limit
     * @return array of Warecorp_Location_Country
     * @author Halauniou
     */
    static function getUsersTopCountriesList($limit = 0){
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('view_users__top_locations',array(new Zend_Db_Expr('DISTINCT country_id'), 'country_name', 'users_count' => 'sum(users_count)'));
        if ($limit != 0) $select->limit(floor($limit));

        $select->order('users_count DESC');
        $select->group('country_name');

        $countries = $db->fetchAll($select);
        return $countries;
    }
    
    /**
     * Return array of city hash with max created groups
     * @param int $stateFilter - id of state
     * @param int $countryFilter - id of country
     * @param int $limit
     * @param boolean $publicOnly - true if count of only public groups is needed (added by Saharchuk Timofei)
     * @author Halauniou
     */
    static function getGroupTopCitiesList($stateFilter = "", $countryFilter = "", $limit = 0, $publicOnly = false){
        $db = Zend_Registry::get("DB");
        $select = $db->select();   
        if ($publicOnly) {
            // getting information from  zanby_location__cities and  zanby_groups__items tables
            $select->from(array('zlc' => 'zanby_location__cities'),array('city_id' => 'zlc.id', 'city_name' => 'zlc.name'))
                   ->join(array('zgi' => 'zanby_groups__items'), 'zlc.id=zgi.city_id', array('groups_count' => new Zend_Db_Expr('COUNT(zgi.id)')))
                   ->where("zgi.type    = 'simple'")
                   ->where("zgi.private = 0");
        } else {
            $select->from('view_groups__top_locations',array('city_id', 'city_name', 'groups_count'));
        }

        if ($stateFilter)  $select->where("state_id = ?", $stateFilter);
        if ($countryFilter) {
            //if $publicOnly - need to join zanby_location__states too 
            if ($publicOnly) {
                $select->join(array('zls' => 'zanby_location__states'), 'zlc.state_id=zls.id');
            }
            $select->where("country_id = ?", $countryFilter);
        }
        if ($limit != 0) $select->limit(floor($limit));
        //if $publicOnly - need to group result
        if ($publicOnly) {
            $select->group('zlc.id'); 
        }

        $select->order('groups_count DESC'); 
        $cities = $db->fetchAll($select);
        return $cities;
    }
    
    /**
     * Return array of states hash with max created groups
     * @param int $countryFilter - id of country
     * @param int $limit
     * @author Halauniou
     */
    static function getGroupTopStatesList($countryFilter = null, $limit = 0){
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('view_groups__top_locations',array(new Zend_Db_Expr('DISTINCT state_id'), 'state_name', 'groups_count' => 'sum(groups_count)'));

        if ($countryFilter) $select->where("country_id = ?", $countryFilter);
        if ($limit != 0) $select->limit(floor($limit));

        $select->order('groups_count DESC');
        $select->group('state_name');
        $states = $db->fetchAll($select);
        return $states;
    }
    
    /**
     * Return array of countries hash with max created groups
     * @param int $limit
     * @author Halauniou
     */
    static function getGroupTopCountriesList($limit = 0){
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('view_groups__top_locations',array(new Zend_Db_Expr('DISTINCT country_id'), 'country_name', 'group_count' => 'sum(groups_count)'));
        if ($limit != 0) $select->limit(floor($limit));

        $select->order('groups_count DESC');
        $select->group('country_name');

        $countries = $db->fetchAll($select);
        return $countries;
    }
    
    /**
     * Return array of cites hash with max created groups
     */
    static public function getTopCitiesForGroups()
    {
    	$db = Zend_Registry::get("DB");
        $query = $db->select();
        $query->from(array('zlc' => 'zanby_location__cities'), array('cid' => 'zlc.id'))
              ->join(array('zgi' => 'zanby_groups__items'), 'zlc.id = zgi.city_id')
              ->group('zlc.id')->order('COUNT(cid)')->limit(20);
        $cities = $db->fetchCol($query);
        foreach ($cities as &$city) $city = Warecorp_Location_City::create($city);
        return $cities;
    }
     
    /**
	 * get Count Users array for all countries
	 * @return array
	 * author: Zolotarsky Yury
	 */	
	public static function getCountUsersByCountries()
	{
	    $db = Zend_Registry::get("DB");
		$query = 'select * from (select A.id as id, (select count(B.id) from zanby_users__accounts B
		          where (B.city_id IN (select C.id from zanby_location__cities C where C.state_id
                  IN(select D.id from zanby_location__states D 
                  where D.country_id = A.id))) and (status = "active")) as count from zanby_location__countries A) ttt';// where (ttt.count > 0)';
		$countUsers = $db->fetchPairs($query);
		return $countUsers;		
	}
     
	/**
	 * get Count Users array for all states of country
	 * @param int country_id
	 * @return array
	 * author: Zolotarsky Yury
	 */	
    public static function getCountUsersByStates($country_id)
	{
	    $db = Zend_Registry::get("DB");
		$query = 'select A.id as id, (select count(B.id) from zanby_users__accounts B
		          where B.city_id IN (select C.id from zanby_location__cities C 
		          where C.state_id = A.id) and (status = "active")) as count from zanby_location__states A
		          where A.country_id = ?';
		$query = $db->quoteInto($query, $country_id,'INTEGER');
		$countUsers = $db->fetchPairs($query);		
		return $countUsers;			
	}
     
	/**
	 * get Count Users array for all cities of state
	 * @param int state_id
	 * @return array
	 * author: Zolotarsky Yury
	 */	
	public static function getCountUsersByCities($state_id)
	{
	    $db = Zend_Registry::get("DB");
		$query = 'select * from (select A.id as id, (select count(B.id) from zanby_users__accounts B
		          where (B.city_id = A.id) and (status = "active")) as count from zanby_location__cities A
		          where A.state_id = ?) cities where cities.count > 0';
		$query = $db->quoteInto($query, $state_id,'INTEGER');
		$countUsers = $db->fetchPairs($query);		
		return $countUsers;			
	}
	
    /**
	 * get Count Groups array for all countries
	 * @return array
	 * author: Zolotarsky Yury
	 */	
	public static function getCountGroupsByCountries()
	{
	    $db = Zend_Registry::get("DB");
		$query = 'select * from (select A.id as id, (select count(B.id) from zanby_groups__items B
		          where (B.city_id IN (select C.id from zanby_location__cities C where C.state_id
                  IN(select D.id from zanby_location__states D 
                  where D.country_id = A.id))) and (B.type = "simple")) as count from zanby_location__countries A) ttt';// where (ttt.count > 0)';
		$countGroups = $db->fetchPairs($query);
		return $countGroups;		
	}
     
	/**
	 * get Count Groups array for all states of country
	 * @param int country_id
	 * @return array
	 * author: Zolotarsky Yury
	 */	
    public static function getCountGroupsByStates($country_id)
	{
	    $db = Zend_Registry::get("DB");
		$query = 'select A.id as id, (select count(B.id) from zanby_groups__items B
		          where (B.city_id IN (select C.id from zanby_location__cities C 
		          where C.state_id = A.id)) and (B.type = "simple")) as count from zanby_location__states A
		          where A.country_id = ?';
		$query = $db->quoteInto($query, $country_id,'INTEGER');
		$countGroups = $db->fetchPairs($query);		
		return $countGroups;			
	}
     
	/**
	 * get Count Groups array for all cities of state
	 * @param int state_id
	 * @return array
	 * author: Zolotarsky Yury
	 */	
	public static function getCountGroupsByCities($state_id)
	{
	    $db = Zend_Registry::get("DB");
		$query = 'select * from (select A.id as id, (select count(B.id) from zanby_groups__items B
		          where (B.city_id = A.id) and (B.type = "simple")) as count from zanby_location__cities A
		          where A.state_id = ?) cities where cities.count > 0';
		$query = $db->quoteInto($query, $state_id,'INTEGER');
		$countGroups = $db->fetchPairs($query);		
		return $countGroups;			
	}
    
    /**
	 * get random(first in list) city id by country id
	 * @param int country_id
	 * @return city id
	 * author: Alexander Komarovski
	 */	
	public static function getRandomCityIdByCountryId($country_id)
	{
	    $db = Zend_Registry::get("DB");
		$query = 'select A.id from zanby_location__cities AS A
		          LEFT JOIN zanby_location__states AS B ON B.id=A.state_id
		          LEFT JOIN zanby_location__countries AS C ON C.id=B.country_id
		          where C.id = ?
                  LIMIT 1';
		$query = $db->quoteInto($query, $country_id,'INTEGER');
		$cityId = $db->fetchOne($query);		
		return $cityId;			
	}	

}
