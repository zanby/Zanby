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
 * @copyright  Copyright (c) 2007
 */

/**
 *
 *
 */
class BaseWarecorp_Location_Country extends Warecorp_Data_Entity
{
    public $id;
    public $name;
    public $code;
    public $source;         // @author Artem Sukharev
    public $latitude;
    public $longitude;
    public $continentId;    // @author Roman Gabrusenok

    /**
    * Factory
    */
    public static function create($value = null)
    {
        if ( is_numeric($value) ) {
            $cache = Warecorp_Cache::getFileCache();
            if ( !$country = $cache->load('Warecorp_Location_Country_'.$value) ) {
                if (Warecorp::checkHttpContext('zccf')) {
                    $country = new ZCCF_Location_Country($value);
                }else{
                    $country = new Warecorp_Location_Country($value);
                }
                
                $cache->save($country, 'Warecorp_Location_Country_'.$value, array(), Warecorp_Cache::LIFETIME_10DAYS);
            }
        } else {
            if (Warecorp::checkHttpContext('zccf')) {
                $country = new ZCCF_Location_Country($value);
            }else{
                $country = new Warecorp_Location_Country($value);
            }
            $country = new Warecorp_Location_Country($value);
        }
        return $country;
    }

    /**
     * Constructor.
     *
     */
	public function __construct($value = null)
	{
        parent::__construct('zanby_location__countries', array(
            'id'           => 'id',
            'name'         => 'name',
            'code'         => 'code',
            'source'       => 'source',       // @author Artem Sukharev
            'latitude'     => 'latitude',     // @author Artem Sukharev
            'longitude'    => 'longitude',    // @author Artem Sukharev
            'continent_id' => 'continentId'   // @author Roman Gabrusenok
        ));
        $this->load($value);
	}
    /**
     * @author Artem Sukharev
     */
	static public function findByName($countryName)
	{
		$dbConn = Zend_Registry::get('DB');
		$query = $dbConn->select()->from('zanby_location__countries', '*');
		$query->where('name = ?', $countryName);
		$result = $dbConn->fetchRow($query);
		if ( !$result ) return null;
		return Warecorp_Location_Country::create($result);
	}
	/**
	 * @author Artem Sukharev
	 */
    static public function findByCode($countryCode)
    {
        $dbConn = Zend_Registry::get('DB');
        $query = $dbConn->select()->from('zanby_location__countries', '*');
        $query->where('code = ?', $countryCode);
        $result = $dbConn->fetchRow($query);
        if ( !$result ) return null;
        return Warecorp_Location_Country::create($result);
    }
	/**
	 * @author Artem Sukharev
	 */
    public function findDefaultState()
    {
    	$query = $this->_db->select()->from('zanby_location__states', 'id');
    	$query->where('is_default = ?', 1);
    	$result = $this->_db->fetchOne($query);
    	if ( $result ) return Warecorp_Location_State::create($result);
    	else return null;
    }
    /**
	 * Получить список штатов для страны
	 * @return array of Warecorp_Location_State
     * Cached
	 */
	public function getStatesList()
	{
        /*
		$sql = $this->_db->select()->from('zanby_location__states', '*')->where('country_id=?', $this->id);
		$states = $this->_db->fetchAll($sql);
		foreach($states as &$state) $state = new Warecorp_Location_State($state);
		return $states;
        */
        $cache = Warecorp_Cache::getFileCache();
        if ( !$states = $cache->load('Warecorp_Location_Country__getStatesList__coutryid'.$this->id) ) {
            $sql = $this->_db->select()->from('zanby_location__states', 'id')->where('country_id=?', $this->id);
            $states = $this->_db->fetchCol($sql);
            foreach($states as &$state) $state = Warecorp_Location_State::create($state);
            $cache->save($states, 'Warecorp_Location_Country__getStatesList__coutryid'.$this->id, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        return $states;
	}
	/**
	 * Получить список штатов страны для <select>
	 * @return array - возвращает массив пар значений 'id', 'name'
     * Cached
	 */
	public function getStatesListAssoc($default_value_exist = false)
	{
        /*
		$sql = $this->_db->select()->from('zanby_location__states', array('id', 'name'))->where('country_id=?', $this->id)->order('name');
		if ($default_value_exist) {
		   $states[0] = "[Select State]";
		   $states += $this->_db->fetchPairs($sql);
		}
		else $states = $this->_db->fetchPairs($sql);
		return $states;
        */
        $cache = Warecorp_Cache::getFileCache();
        if ( !$states = $cache->load('Warecorp_Location_Country__getStatesListAssoc__coutryid'.$this->id) ) {
            $sql = $this->_db
                ->select()
                ->from('zanby_location__states', array('id', 'name'))
                ->where('country_id=?', ( NULL === $this->id) ? new Zend_Db_Expr('NULL') : $this->id)
                ->order('name');
            $states = $this->_db->fetchPairs($sql);
            $cache->save($states, 'Warecorp_Location_Country__getStatesListAssoc__coutryid'.$this->id, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        if ($default_value_exist) $states = array('0' => '[Select State]') + $states;
        return $states;
	}

	/**
	 * Получить список штатов страны для <select>
	 * @return array - возвращает массив пар значений 'id', 'name'
     * Cached
	 */
	public function getStatesListAssocWithCodes($default_value_exist = false)
	{
        $cache = Warecorp_Cache::getFileCache();
        if ( !$states = $cache->load('Warecorp_Location_Country__getStatesListAssocWithCodes__coutryid'.$this->id) ) {
            $sql = $this->_db->select()
                ->from('zanby_location__states', array("id", 'name' => new Zend_Db_Expr("IF(code <> '', code, name)")))
                ->where('country_id=?', $this->id)
                ->order('name');
            $states = $this->_db->fetchPairs($sql);
            $cache->save($states, 'Warecorp_Location_Country__getStatesListAssocWithCodes__coutryid'.$this->id, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        if ($default_value_exist) $states = array('0' => '[Select State]') + $states;
        return $states;
	}

	public function getStatesListAssocWithCodesAndNames($default_value_exist = false)
	{
        $cache = Warecorp_Cache::getFileCache();
        if ( !$states = $cache->load('Warecorp_Location_Country__getStatesListAssocWithCodesAndNames__coutryid'.$this->id) ) {
            $sql = $this->_db->select()
                ->from('zanby_location__states', array('id', 'name' => new Zend_Db_Expr("IF(code <> '', CONCAT(code,' - ',name), name)")))
                ->where('country_id=?', $this->id)
                ->order('name');
            $states = $this->_db->fetchPairs($sql);
            $cache->save($states, 'Warecorp_Location_Country__getStatesListAssocWithCodesAndNames__coutryid'.$this->id, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        if ($default_value_exist) $states = array('0' => '[Select State]') + $states;
        return $states;
	}

    /**
     * get list of cities of certain country for autocomplet
     * @return array of Warecorp_Location_City
     * Cached
     */
    public function getACCitiesList($letters)
    {
        /*
        $sql = $this->_db->select();
        $sql->from(array('zlc' => 'zanby_location__cities'), array('0' => new Zend_Db_Expr('CONCAT(zlc.name, ", ", zls.name)')));
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id');
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where("zlc.name LIKE '".preg_replace("/(_|%|'|\\\\)/", "\\\\\\1", $letters)."%'");
        $sql->order('`0` ASC');

        $cities = $this->_db->fetchAll($sql);
        return $cities;
        */
        $letters = preg_replace("/[^a-zA-Z0-9]/", "", $letters);
        $cache = Warecorp_Cache::getFileCache();
        if ( !$cities = $cache->load('Warecorp_Location_Country__getACCitiesList__countryid'.$this->id.'__'.$letters) ) {
            $sql = $this->_db->select();
            $sql->from(array('zlc' => 'zanby_location__cities'), array('`0`' => new Zend_Db_Expr('CONCAT(zlc.name, ", ", zls.name)')));
            $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id');
            $sql->where('zls.country_id = ?', $this->id);
            $sql->where("zlc.name LIKE '".$letters."%'");
            $sql->order(new Zend_Db_Expr('`0` ASC'));
            $cities = $this->_db->fetchAll($sql);
            $cache->save($cities, 'Warecorp_Location_Country__getACCitiesList__countryid'.$this->id.'__'.$letters, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        return $cities;
    }
    
    /**
     * get list of cities of certain country for autocomplet
     * @return array of Warecorp_Location_City
     * Cached
     */
    public function getCitiesListByCountry()
    {
        $cache = Warecorp_Cache::getFileCache();
        if ( !$cities = $cache->load('Warecorp_Location_Country__getCitiesListByCountry__countryid'.$this->id) ) {
            $sql = $this->_db->select();
            $sql->from(array('zlc' => 'zanby_location__cities'), array('0' => 'zlc.name'));
            $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id');
            $sql->where('zls.country_id = ?', $this->id);
            $sql->order(new Zend_Db_Expr('`0` ASC'));
            $cities = $this->_db->fetchAll($sql);
            $cache->save($cities, 'Warecorp_Location_Country__getCitiesListByCountry__countryid'.$this->id, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        return $cities;
    }

    /**
     * get list of cities of certain country for autocomplet
     * @return array of Warecorp_Location_City
     * Cached
     */
    public function getACCitiesListDrupal($letters)
    {
        $letters = preg_replace("/[^a-zA-Z0-9]/", "", $letters);
        $cache = Warecorp_Cache::getFileCache();
        if ( !$cities = $cache->load('Warecorp_Location_Country__getACCitiesListDrupal__countryid'.$this->id.'__'.$letters) ) {
            $sql = $this->_db->select()->distinct();
            $sql->from(array('zlc' => 'zanby_location__cities'), array('`0`' => new Zend_Db_Expr('CONCAT(zlc.name, ", ", zls.name)')));
            $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id');
            $sql->join(array('zldr' => 'zanby_location__drupal_relations'), 'zldr.state_id = zls.id');
            $sql->where('zls.country_id = ?', $this->id);
            $sql->where("zlc.name LIKE '".$letters."%'");
            $sql->order(new Zend_Db_Expr('`0` ASC'));
            $cities = $this->_db->fetchAll($sql);
            $cache->save($cities, 'Warecorp_Location_Country__getACCitiesListDrupal__countryid'.$this->id.'__'.$letters, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        return $cities;
    }

    /**
     *
     */
    public function checkCityFromAC($city)
    {
        $sql = $this->_db->select();
        $sql->from(array('zlc' => 'zanby_location__cities'), new Zend_Db_Expr('COUNT(zlc.id)'));
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id');
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where('CONCAT(zlc.name, ", ", zls.name) = ?', $city);

        $cities = $this->_db->fetchOne($sql);
        return (boolean) $cities;
    }

    /**
     * @return int count of city this current name in current country
     * @author Artem Sukharev
     */
    public function checkCity($city)
    {
    	/*
        $sql = $this->_db->select();
        $sql->from(array('zlc' => 'zanby_location__cities'), 'zlc.id');
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id');
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where('zlc.name = ?', $city);

        $cities = $this->_db->fetchOne($sql);
        return $cities;
        */
        $sql = $this->_db->select();
        $sql->from(array('zlc' => 'zanby_location__cities'), new Zend_Db_Expr('COUNT(zlc.id)'));
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id');
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where('zlc.name = ?', $city);

        $cities = $this->_db->fetchOne($sql);
        return $cities;
    }
    /**
     * @return array of Warecorp_Location_City with current name in current country
     * @author Artem Sukharev
     */
    public function findByCity($city)
    {
        $sql = $this->_db->select();
        $sql->from(array('zlc' => 'zanby_location__cities'), 'zlc.id');
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id');
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where('zlc.name = ?', $city);

        $cities = $this->_db->fetchCol($sql);
        if ( $cities ) {
            foreach ( $cities as &$item ) $item = Warecorp_Location_City::create($item);
        }
        return $cities;
    }
    /**
     * @return array of Warecorp_Location_City with current name in current country
     * @author Artem Sukharev
     */
    public function findByCityNameOrIds($cityName = null, $ids = null , $excludeIds = null)
    {
        $sql = $this->_db->select();
        $sql->from(array('zlc' => 'zanby_location__cities'), 'zlc.id');
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id');
        $sql->where('zls.country_id = ?', $this->id);
        if ( null !== $excludeIds ) $sql->where('zlc.id NOT IN (?)', $excludeIds);

        if ( null !== $cityName && null !== $ids && is_array($ids) && sizeof($ids) != 0 ) {
            $where = '(' . $this->_db->quoteInto('zlc.name = ?', $cityName);
            $where .= ' OR '.$this->_db->quoteInto('zlc.id IN (?)', $ids) .')';
            $sql->where($where);
        } elseif ( null !== $cityName ) {
            $sql->where('zlc.name = ?', $cityName);
        } elseif ( null !== $ids && is_array($ids) && sizeof($ids) != 0 ) {
            $sql->where('zlc.id IN (?)', $ids);
        }

        $cities = $this->_db->fetchCol($sql);
        if ( $cities ) {
            foreach ( $cities as &$item ) $item = Warecorp_Location_City::create($item);
        }
        return $cities;
    }
    /**
     *
     */
    public function getCityByACInfo($strInfo)
    {
        $sql = $this->_db->select();
        $sql->from(array('zlc' => 'zanby_location__cities'), array('city_id' => 'zlc.id'));
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id', array('state_id' => 'zls.id'));
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where('CONCAT(zlc.name, ", ", zls.name) = ?', $strInfo);

        $info = $this->_db->fetchRow($sql);
        return $info;
    }

    /**
     * get list of cities of certain country for autocomplet
     * @return array of Warecorp_Location_City
     * Cached
     */
    public function getACZipcodesList($letters)
    {
        /*
        $sql = $this->_db->select();
        $sql->from(array('zlz' => 'zanby_location__zipcodes'), array('0' => new Zend_Db_Expr('CONCAT(zlz.zipcode, ', ', zlc.name)')));
        $sql->join(array('zlc' => 'zanby_location__cities'), "zlc.id = zlz.city_id");
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id');
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where("zlz.zipcode LIKE '".preg_replace("/(_|%|'|\\\\)/", "\\\\\\1", $letters)."%'");
        $sql->order('`0` ASC');
        $cities = $this->_db->fetchAll($sql);
        return $cities;
        */
        $letters = preg_replace("/[^a-zA-Z0-9]/", "", $letters);
        $cache = Warecorp_Cache::getFileCache();
        if ( !$cities = $cache->load('Warecorp_Location_Country__getACZipcodesList__countryid'.$this->id.'__'.$letters) ) {
            $sql = $this->_db->select();
            $sql->from(array('zlz' => 'zanby_location__zipcodes'), array('`0`' => new Zend_Db_Expr('CONCAT(zlz.zipcode, ", ", zlc.name)')));
            $sql->join(array('zlc' => 'zanby_location__cities'), "zlc.id = zlz.city_id", array());
            $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id', array());
            $sql->where('zls.country_id = ?', $this->id);
            $sql->where("zlz.zipcode LIKE '".$letters."%'");
            $sql->order(new Zend_Db_Expr('`0` ASC'));
            $cities = $this->_db->fetchAll($sql);
            $cache->save($cities, 'Warecorp_Location_Country__getACZipcodesList__countryid'.$this->id.'__'.$letters, array(), Warecorp_Cache::LIFETIME_10DAYS);
        }
        return $cities;
    }

    /**
     *
     */
    public function checkZipcodeFromAC($zipcode)
    {
        $zipcode = explode(',', $zipcode);
        if (count($zipcode)<2) return false;
        $sql = $this->_db->select();
        $sql->from(array('zlz' => 'zanby_location__zipcodes'), new Zend_Db_Expr('COUNT(zlz.id)'));
        $sql->join(array('zlc' => 'zanby_location__cities'), 'zlc.id = zlz.city_id', array());
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id', array());
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where("CONCAT(zlz.zipcode, ', ', zlc.name) = ?", $zipcode[0].','.$zipcode[1]);
        $zipcodes = $this->_db->fetchOne($sql);
        return (boolean) $zipcodes;
    }

    /**
     *
     */
    public function checkZipcode($zipcode)
    {
        $sql = $this->_db->select();
        $sql->from(array('zlz' => 'zanby_location__zipcodes'), new Zend_Db_Expr('COUNT(zlz.id)'));
        $sql->join(array('zlc' => 'zanby_location__cities'), 'zlc.id = zlz.city_id', array());
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id', array());
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where("zlz.zipcode = ?", $zipcode);
        $zipcodes = $this->_db->fetchOne($sql);
        return (boolean) $zipcodes;
    }

    /**
     *
     */
    public function getZipcodeByACFullInfo($strInfo)
    {
        $sql = $this->_db->select();
        $sql->from(array('zlz' => 'zanby_location__zipcodes'), array('zipcode_id' => 'zlz.id', 'zlz.zipcode'));
        $sql->join(array('zlc' => 'zanby_location__cities'), 'zlc.id = zlz.city_id', array('city_id' => 'zlc.id'));
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id', array());
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where("CONCAT(zlz.zipcode, ', ', zlc.name) = ?", $strInfo);
        $info = $this->_db->fetchRow($sql);

        return $info;
    }

    /**
     *
     */
    public function getZipcodeByACInfo($strInfo)
    {
        $sql = $this->_db->select();
        $sql->from(array('zlz' => 'zanby_location__zipcodes'), array('zipcode_id' => 'zlz.id', 'zlz.zipcode'));
        $sql->join(array('zlc' => 'zanby_location__cities'), 'zlc.id = zlz.city_id', array('city_id' => 'zlc.id'));
        $sql->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id', array());
        $sql->where('zls.country_id = ?', $this->id);
        $sql->where("zlz.zipcode = ?", $strInfo);
        $info = $this->_db->fetchRow($sql);

        return $info;
    }

   /**
    * @author Roman Gabrusenok
    */
    public function getContinent()
    {
        return new Warecorp_Location_Continent('id', $this->continentId);
    }
    
    /**
     *  @author Alexander Komarovski 
     */
    public function getMaxMinCoordinates() {
        
    	$cache = Warecorp_Cache::getFileCache();
        if ( !$cfgCountryCoordinates = $cache->load('cfg_country_maxmin_coordinates_xml') ) {
            $cfgCountryCoordinates = new Zend_Config_Xml(CONFIG_DIR."cfg.location_maxmin_coordinates.xml", 'country');
            $cache->save($cfgCountryCoordinates, 'cfg_country_maxmin_coordinates_xml', array(), Warecorp_Cache::LIFETIME_10DAYS);
        }

        $_key = "c_".$this->id;
        if ( isset($cfgCountryCoordinates->$_key) ) {
            return array (
                'maxlongitude'  => $cfgCountryCoordinates->$_key->maxlongitude,
                'maxlatitude'   => $cfgCountryCoordinates->$_key->maxlatitude,
                'minlongitude'  => $cfgCountryCoordinates->$_key->minlongitude,
                'minlatitude'   => $cfgCountryCoordinates->$_key->minlatitude,
            );	
        }
        
        
        $query = $this->_db->select()
            ->from(
                array('zlz' => 'zanby_location__zipcodes'),
                array(
                    'maxlongitude' => new Zend_Db_Expr('MAX(zlz.longitude)'),
                    'maxlatitude' => new Zend_Db_Expr('MAX(zlz.latitude)'),
                    'minlongitude' => new Zend_Db_Expr('MIN(zlz.longitude)'),
                    'minlatitude' => new Zend_Db_Expr('MIN(zlz.latitude)')
                )
            )
        ->join(array('zlc' => 'zanby_location__cities'), 'zlc.id = zlz.city_id', array())
        ->join(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id', array())
        ->join(array('zlc2' => 'zanby_location__countries'), 'zlc2.id = zls.country_id', array())
        ->where('zlc2.id = ?', $this->id);
        
        return $this->_db->fetchRow($query);
    }
    
}
