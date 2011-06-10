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
 * Search class
 * @package Warecorp_Search
 * @author Dmitry Kostikov
 */

class BaseWarecorp_Search extends Warecorp_Data_Entity
{

    public $_db;
    public $keywords;
    public $entityList;
    public $resByKeywords;
    public $resByZipCodes;
    public $resByCriterions;
    public $resIntersection;
    public $resByCity;
    public $zipcodes;

     /**
     * ids of items for include
     */
    protected $_includeIds;

    /**
     * ids of items for exclude
     */
    protected $_excludeIds;

    public $id;
    public $name;
    public $EntityTypeId;
    public $userId;
    public $params;

    protected $_limitResults;
    protected $_offsetResults;

    /**
     * constructor
     */
    public function __construct($id = null)
    {
        //$this->_db = Zend_Registry::get("DB");
        parent::__construct('zanby_search__presets');

        $this->addField('id');
        $this->addField('name', 'name');
        $this->addField('entity_type_id', 'EntityTypeId');
        $this->addField('user_id', 'userId');
        $this->addField('params', 'params');
        if ($id !== null){
            $this->pkColName = 'id';
            $this->loadByPk($id);
            $this->params = unserialize($this->params);
        }

    }

    public function save()
    {
        $this->params = serialize($this->params);
        parent::save();
    }
    /**
     * clear input string and explode for keywords
     *
     * @param   string  $input - raw text
     * @param   string  $ignore - ignored chars
     * @return  array
     */
    static public function clearInput($input, $ignore = "")
    {
        $_regExEncoding = mb_regex_encoding();
        $_mbInEncoding  = mb_internal_encoding();
        mb_regex_encoding('UTF-8');
        mb_internal_encoding('UTF-8');
        $input = trim($input);
        if (defined("WITH_SPHINX") && WITH_SPHINX && !empty($ignore)) $input = preg_replace("/[$ignore]/", "", $input);
        $input = (mb_ereg_replace("[^[\w $ignore]]", ' ', $input));

        //$input = preg_replace("'[\s,]{1,}'si", " ", $input);
        $input_arr = preg_split('/\s{1,}/mi', $input);
        $input_arr = array_intersect_key($input_arr,array_unique(array_map('mb_strtolower',$input_arr))); // case insensitive unique
        $keywords = array();
        if ($input_arr) {
            foreach ($input_arr as $value){
                if (Warecorp_Common_Utf8::getStrlen($value) >= 2) {
                    if (Warecorp_Common_Utf8::getStrlen($value) > 100) {
                        $value = Warecorp_Common_Utf8::getSubstr($value, 0, 100);
                    }
                    $keywords[] = $value;
                }
            }
        }
        return (empty($keywords) ? false : $keywords);
    }

    /**
     * clear input string and explode for keywords
     *
     * @param string $input - raw text
     * @param string $ignore - ignored chars
     * @return  void
     */
    function setKeywords($input, $ignore = "")
    {
        $this->keywords = $this->clearInput($input, $ignore);
    }

    /**
     *
     */
    public function getSavedSearchesAssoc($user_id, $entity_type_id)
    {
        $sql = $this->_db->select()
                    ->from('zanby_search__presets', array("id", "name"))
                    ->where('user_id = ?', $user_id)
                    ->where('entity_type_id = ?', $entity_type_id);

        return $this->_db->fetchPairs($sql);

    }

    /**
     * set include Ids
     * @param array $newVal - ids of items
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function setIncludeIds($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_includeIds = $newVal;
        return $this;
    }

    /**
     * return include ids
     * @return array
     * @author Artem Sukharev
     */
    public function getIncludeIds()
    {
        return $this->_includeIds;
    }

    /**
     * set exclude Ids
     * @param array $newVal - ids of items
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function setExcludeIds($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_excludeIds = $newVal;
        return $this;
    }

    /**
     * return exclude ids
     * @return array
     * @author Artem Sukharev
     */
    public function getExcludeIds()
    {
        return $this->_excludeIds;
    }

    /**
     * Get zipcodes
     * @return array
     * @author Vitaly Targonsky
     */
    public function getZipCodes()
    {
        if ($this->zipcodes === null) {
            $this->setZipCodes();
        }
        return $this->zipcodes;
    }

    /**
     * Set search limits
     * @param int $offset from which get results
     * @return int $limit number of results to fetch
     * @author Konstantin Stepanov
     */
    public function setLimits($offset = 0, $limit = 0)
    {
    $this->_offsetResults = (int)$offset;
    $this->_limitResults = (int)$limit;
    }


    /**
     * Set zipcodes from keywords
     *
     * @return array
     * @author Vitaly Targonsky
     */
    public function setZipCodes()
    {
        $sql = $this->_db->select()
                         ->distinct()
                         ->from('zanby_location__zipcodes', array('zipcode', 'latitude', 'longitude'))
                         ->where('zipcode IN (?)', $this->keywords)
                         ->where('longitude IS NOT NULL')
                         ->where('latitude IS NOT NULL');
        $this->zipcodes = $this->_db->fetchAll($sql);

        if (count($this->zipcodes)) { // exclude zipcodes from keywords
            foreach ($this->zipcodes as &$_zip) {
                $key = array_search($_zip['zipcode'], $this->keywords);
                if ($key !== false) {
                    unset($this->keywords[$key]);
                }
            }
        }
        return $this;
    }

    /**
     * @todo delete this
     */
    public static function getAllTagsPreparedByLocation()
    {
        throw new Zend_Exception('OBSOLETE FUNCTION USED: "getAllTagsPreparedByLocation". USE Warecorp_Group_Tag_List::getListByLocation');
    }

    protected function setLocationFilter(Warecorp_Data_Search $cl, $params)
    {
        if ( isset($params['city']) ){
            if ( !is_array($params['city']) && $params['city'] === 0 ) {
                $cl->SetFilter ( "city_id", array(0) );
            }
            else {
                if (is_array($params['city']) && count($params['city']) > 1){
                    $cl->SetFilter ( "city_id", $params['city'] );
                } else {
                    if (is_array($params['city'])) {
                        $primaryCity = current($params['city']);
                    } else {
                        $primaryCity = $params['city'];
                    }
                    $City = Warecorp_Location_City::create($primaryCity);
                    $City->setLatitudeLongitude();
                    $latitude = deg2rad($City->getLatitude());
                    $longitude = deg2rad($City->getLongitude());
                    // set geo anchor to current city coordinates
                    // it's necessary for creating geodistance order
                    $cl->SetFilterGeo('latitude', 'longitude', floatval($latitude), floatval($longitude), (defined('DISTANCE_OF_SEARCH')? DISTANCE_OF_SEARCH: 200.0 )*1000 );
                }
            }
        }
        if ( isset($params['state']) ) {
            // set state filter
            $cl->SetFilter ( "state_id", array( $params['state'] ) );
        }
        if ( isset($params['country']) ) {
            // set country filter
            $cl->SetFilter ( "country_id", array( $params['country'] ) );
        }
        if ( isset($params['where']) ) {
            $location = &$params['where'];
            if ( isset($location['city']) && is_numeric($location['city']) )
            $cl->SetFilter('city_id', array($location['city']));
            if ( isset($location['state']) && is_numeric($location['state']) )
            $cl->SetFilter('state_id', array($location['state']));
            if ( isset($location['country']) && is_numeric($location['country']) )
            $cl->SetFilter('country_id', array($location['country']));
        }

    }

    /**
     * Set filter to exclude results for blocked user
     * @param Warecorp_Data_Search $cl
     * @param Warecorp_User $user (optional) user for whitch exclude results, if null then current user
     */
    protected function setBlockedUserFilter(Warecorp_Data_Search $cl, Warecorp_User $user = null)
    {
        if ($user === null) {
            $user = Zend_Registry::get('User');
        }
        if (!$user->getId()) {
            return;
        }

        $db = Zend_Registry::get('DB');
        $select = $db->select();
        $select->from('zanby_users__blocks', 'user_id')->where('blocked_user_id=?', $user->getId());
        $excludeIds = $db->fetchCol($select);

        if (count($excludeIds)) {
            $cl->SetFilter('owner_user_id', $excludeIds, true);
        }
    }
}
