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
 * @package Warecorp_Group_Search
 * @author Vitaly Targonsky
 */

class BaseWarecorp_Group_Search extends Warecorp_Search
{
    public $defaultOrder = null;
    public $paramsOrder = null;
    private $orders = null;
    private $_types;
    /**
     * Конструктор
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getPagerLink($params)
    {
        $_orders = $this->getOrders();
        $link  = $params['_url'];
        $link .= empty($params['filter']) ? "" : "/filter/".$params['filter'] ;
        $link .= empty($params['order']) || !isset($_orders[$params['order']]) ? "" : "/order/".$params['order'] ;
        $link .= empty($params['direction']) || !in_array($params['direction'], array('asc','desc')) ? "" : "/direction/".$params['direction'];
        return $link;

    }

    public function getPagerLinkGlobalSearch($params)
    {
        $_orders = $this->getOrders();
        $link  = $params['_url'];
        $link .= empty($params['filter']) ? "" : "/filter/".$params['filter'] ;
        $link .= empty($params['order']) || !isset($_orders[$params['order']]) ? "" : "/order/".$params['order'] ;
        $link .= empty($params['direction']) || !in_array($params['direction'], array('asc','desc')) ? "" : "/direction/".$params['direction'];
        return $link;

    }
    /**
     * get posible orders
     *
     * @return unknown
     */
    public function getOrders()
    {
        if ($this->orders === null) {
            $this->orders = array('name'=>'vgie.name', 'founded'=>'vgie.creation_date', 'members'=>'members_cnt');
            $user = Zend_Registry::get('User');
            if ($user->getId()) {
                $this->orders['proximityme']="SQRT((69.1*(vgie.longitude-(".(float)$user->getLongitude()."))*cos(vgie.latitude/57.3))*(69.1*(vgie.longitude-(".(float)$user->getLongitude()."))*cos(vgie.latitude/57.3))+(69.1*(vgie.latitude-(".(float)$user->getLatitude().")))*(69.1*(vgie.latitude-(".(float)$user->getLatitude()."))))";
            }
        }
        return $this->orders;
    }

    public function getOrdered($params, $size = 10)
    {
        $_orders = $this->getOrders();

        $sql = $this->_db->select()
                    ->from(array('vgie' => 'view_groups__items_extended'), array('vgie.id','vgie.id'))
                    ->limitPage($params['page'], $size);

        if (isset($_orders[$params['order']])) $sql->order($_orders[$params['order']].' '.$params['direction']);

        if (!empty($params['filter'])) $sql->where('vgie.category_id = ?', $params['filter']);
        if ($this->getIncludeIds()) $sql->where('vgie.id IN (?)', $this->getIncludeIds());
        if ($this->getExcludeIds()) $sql->where('vgie.id NOT IN (?)', $this->getExcludeIds());
        if ($this->getTypes()) $sql->where('vgie.type IN (?)', array($this->getTypes()));

        return $this->_db->fetchPairs($sql);

    }

    public function getCount($params)
    {
        $sql = $this->_db->select()
                    ->from(array('vgie' => 'view_groups__items_extended'), array('COUNT(vgie.id)'));
        if (!empty($params['filter'])) $sql->where('vgie.category_id = ?', $params['filter']);
        if ($this->getIncludeIds()) $sql->where('vgie.id IN (?)', $this->getIncludeIds());
        if ($this->getExcludeIds()) $sql->where('vgie.id NOT IN (?)', $this->getExcludeIds());
        if ($this->getTypes()) $sql->where('vgie.type IN (?)', array($this->getTypes()));

        return $this->_db->fetchOne($sql);

    }

    public function getFiltered($params)
    {
        $sql = $this->_db->select()->from(array('vgie' => 'view_groups__items_extended'), array('vgie.id','vgie.id'));
        if (!empty($params['filter'])) $sql->where('vgie.category_id = ?', $params['filter']);
        if ($this->getIncludeIds()) $sql->where('vgie.id IN (?)', $this->getIncludeIds());
        if ($this->getExcludeIds()) $sql->where('vgie.id NOT IN (?)', $this->getExcludeIds());
        if ($this->getTypes()) $sql->where('vgie.type IN (?)', array($this->getTypes()));

        return $this->_db->fetchPairs($sql);
    }
    /**
     * set list of groups which must be exluded, for invite search
     * add new record in block list
     * @param int|Warecorp_Group_Family
     */
    public function setExcluded($family)
    {
        if ($family instanceof Warecorp_Group_Family) $id = $family->getId();
        else $id = $family;
        // TODO: need realize for for invite search functionality
        $this->setExcludeIds($id);
        return $this;
    }

    /**
     * set group types
     * @param array|string|string_delimiter_by_; $newVal
     * @author Artem Sukharev
     */
    public function setTypes($newVal)
    {
        if ( is_array($newVal) ) {
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_GroupType::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect group type');
                }
            }
        } elseif ( strpos($newVal, ';') ) {
            $newVal = explode(';', $newVal);
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_GroupType::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect group type');
                }
            }
        } else {
            if ( !Warecorp_Group_Enum_GroupType::isIn($newVal) ) {
                throw new Zend_Exception('Incorrect group type');
            }
            $newVal = array($newVal);
        }
        $this->_types = $newVal;
        return $this;
    }

    /**
     * get group types
     * @return array
     * @author Artem Sukharev
     */
    public function getTypes()
    {
        if ( $this->_types === null ) $this->_types = array( Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE );  //Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY
        return $this->_types;
    }

   /**
     * Get all group ordered by weight
     *
     * @return  array
     * @author Vitaly Targonsky
     */
    public function getIntersection(){

        $groups=array();
        $this->paramsOrder = null;

        if (count($this->resByCriterions)) {
            foreach ($this->resByCriterions as &$_group) {
                if ($_group['weight'] > 0) {
                    if (!isset($groups[$_group['id']])) {
                        $groups[$_group['id']]['weight']=0;
                        $groups[$_group['id']]['members_cnt'] = $_group['members_cnt'];
                    }
                    $groups[$_group['id']]['weight'] += $_group['weight'];
                }
            }
        }

        if (count($this->resByZipCodes) && count($groups)) {
            foreach ($this->resByZipCodes as &$_group) {
                if ($_group['weight'] > 0) {
                    if (isset($groups[$_group['id']])) {
                        $groups[$_group['id']]['weight'] += $_group['weight'];
                        $groups[$_group['id']]['intersect'] = 1;
                    }
                }
            }
        }
        if (count($groups)) {
            foreach($groups as $key=>&$_group) {
                if (!isset($_group['intersect'])) unset($groups[$key]);
                else $_group = $key;
            }
        }

        uasort($groups, "Warecorp_Group_Search::compareWeigthMembersCnt");

        return $groups;
    }

    /**
     * method for user sorting by weigth desc, then register date desc
     *
     * @param array $e1
     * @param array $e2
     * @return int
     */
    public static function compareWeigthMembersCnt($e1, $e2)
    {
        if ($e1['weight'] < $e2['weight']) return 1;
        elseif ($e1['weight'] > $e2['weight']) return -1;
        elseif ($e1['members_cnt'] < $e2['members_cnt']) return 1;
        elseif ($e1['members_cnt'] > $e2['members_cnt']) return -1;
        else return 0;
    }
    /**
     * method for user sorting by weigth desc, then login asc
     *
     * @param array $e1
     * @param array $e2
     * @return int
     */
    public static function compareWeigthLogin($e1, $e2)
    {
        if ($e1['weight'] < $e2['weight']) return 1;
        elseif ($e1['weight'] > $e2['weight']) return -1;
        else return strcasecmp($e1['login'], $e2['login']);
    }


    /**
     * method for removing deleted groups from result
     *
     * @param array $groupList
     * @return array
     */
    protected function getActualGroupList( $groupList )
    {
        $sql = $this->_db->select()->from(array('vgie' => 'view_groups__items_extended'), array('vgie.id','vgie.id'));
        $groupsExistList = $this->_db->fetchPairs($sql);

        if  ( count($groupList) == 0 || count($groupsExistList) == 0 ) return array();

        foreach ( $groupList as $id => $value )
        {
            if ( empty($groupsExistList[$id]) ) unset($groupList[$id]);
        }
        unset($groupsExistList);
        return $groupList;
    }


    /**
     * Search by zipcodes
     *
     * @return array
     * @author Vitaly Targonsky
     */
    public function searchByZipCodes()
    {
        $this->resByZipCodes = array();
        if (is_array($this->zipcodes) && count($this->zipcodes)) {
            $_zips = array();

            if (WITH_SPHINX)
            {
                foreach ($this->zipcodes as &$zip) {
                    $query = "";
                    // create object Warecorp_Data_Search
                    $cl = new Warecorp_Data_Search();
                       // initialization
                    $cl->init('group');

                    // getting types as array with integer values and apply filter
                    if ($this->getTypes()){
                        $typesArray = array();
                        foreach ($this->getTypes() as $value)
                        {
                            switch ($value)
                            {
                                case 'simple': 		array_push($typesArray, 1); 	break;
                                case 'family': 		array_push($typesArray, 2); 	break;
                            }
                        }
                        $cl->SetFilter ( "type", $typesArray );
                    }
                    // set include and exclude filters if it's necessary
                    if ($this->getIncludeIds()) $cl->SetIDFilter ( $this->getIncludeIds() );
                    if ($this->getExcludeIds()) $cl->SetIDFilter ( $this->getExcludeIds(), true );

                    if (EI_FILTER_ENABLED){
                        $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) ));
                    }

                    // set geoanchor to current user coordinates
                    // it's necessary for creating geodistance order
                       $cl->SetFilterGeo('latitude', 'longitude', deg2rad((float)$zip['latitude']), deg2rad((float)$zip['longitude']), (defined('DISTANCE_OF_SEARCH')? DISTANCE_OF_SEARCH: 200.0 )*1000);

                    // set private filter
                    $cl->SetFilter ( 'private', array( 0 ));

                    if ($this->defaultOrder !== null) {
                        $cl->SetSort( $this->defaultOrder );
                    }

                    // send search query
                    $cl->Query( $query );

                    $this->resByZipCodes += $cl->getResultIWDMC();

                    unset($cl);
                }
            }
            else
            {
                foreach ($this->zipcodes as &$zip) {
                    $_zips[] = "ROUND(SQRT(POW((69.1*(vgie.longitude-(".(float)$zip['longitude']."))*cos(vgie.latitude/57.3)),2)+POW((69.1*(vgie.latitude-(".(float)$zip['latitude']."))),2)),1)";
                }
                $distance = "(".implode('+',$_zips).")/".count($this->zipcodes);

                $cfgWeight = Warecorp_Config_Loader::getInstance()->getCoreConfig('cfg.weight.xml')->{'group_distance'};

                $weight = "CASE \n";
                foreach ($cfgWeight as $w) {
                    $weight .= "WHEN ".$distance.($w->sign).($w->distance)." THEN ".($w->weight)."\n";
                }
                $weight .= "WHEN 1=1 THEN 0 \n END as weight";

                $sql = $this->_db->select()
                                 ->from(array('vgie' => 'view_groups__items_extended'), array('vgie.id', 'distance' => $distance, $weight, 'members_cnt'))
                                 ->where('vgie.longitude IS NOT NULL')
                                 ->where('vgie.latitude IS NOT NULL')
                                 ->where($distance ."<= ?", (defined('DISTANCE_OF_SEARCH')? DISTANCE_OF_SEARCH: 200.0 ))
                                 ->order('distance');
                if ($this->getTypes()) $sql->where('vgie.type IN (?)', array($this->getTypes()));
                if ($this->getIncludeIds()) $sql->where('vgie.id IN (?)', $this->getIncludeIds());
                if ($this->getExcludeIds()) $sql->where('vgie.id NOT IN (?)', $this->getExcludeIds());

                $this->resByZipCodes = $this->_db->fetchAll($sql);
            }
        }
        return $this->getActualGroupList($this->resByZipCodes);
    }

    /**
     * Search by category, country, state
     *
     * @return array
     * @author Vitaly Targonsky
     */
    public function searchByCriterions($params, $with_weight = false)
    {
        // if serching with sphinx
        if (WITH_SPHINX)
        {
            // create object Warecorp_Data_Search
            $cl = new Warecorp_Data_Search();
            // initialization
            $cl->init('group');
            $query = "";

            // getting types as array with integer values and apply filter
            if ($this->getTypes()){
                $typesArray = array();
                foreach ($this->getTypes() as $value)
                {
                    switch ($value)
                    {
                        case 'simple': 		array_push($typesArray, 1); 	break;
                        case 'family': 		array_push($typesArray, 2); 	break;
                    }
                }
                $cl->SetFilter ( "type", $typesArray );
            }
            // set include and exclude filters if it's necessary
            if ($this->getIncludeIds()) $cl->SetIDFilter ( $this->getIncludeIds() );
            if ($this->getExcludeIds()) $cl->SetIDFilter ( $this->getExcludeIds(), true );

            if (EI_FILTER_ENABLED){
                $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) ));
            }

            $user = Zend_Registry::get('User');
            if ($user->getId() && $user->getCityId() && empty($params['city'])) { // authenticated user
                $City = Warecorp_Location_City::create($user->getCityId());
                $City->setLatitudeLongitude();
                $latitude = deg2rad($City->getLatitude());
                $longitude = deg2rad($City->getLongitude());
                // set geoanchor to current user coordinates
                // it's necessary for creating geodistance order
                $cl->SetGeoAnchor('latitude', 'longitude', floatval($latitude), floatval($longitude) );
            }
            $this->setLocationFilter($cl, $params);
            $this->setBlockedUserFilter($cl);

            if ( isset($params['category']) ) {
                $cl->SetFilter ('category_id', array( $params['category'] ));
            }

            if ( !empty($this->keywords) ) {
                $query = implode(" ", $this->keywords);
            }

            if ( isset($params['zipcode']) ) {
                if (!is_array($params['zipcode'])) {
                    $zipcodes = array( crc32($params['zipcode']) );
                } else {
                    $zipcodes = array_map("crc32", $params['zipcode']);
                }
                $cl->SetFilter('zipcode_bin', $zipcodes);
            }

            // set private filter
            $cl->SetFilter ( 'private', array( 0 ));

            if ($this->defaultOrder !== null) {
                $cl->SetSort( $this->defaultOrder );
            }

            // send search query
            $cl->Query( $query );

            if ($with_weight) {
                // getting result with id, weight, distance and membres count
                $this->resByCriterions = $cl->getResultIWDMC();
            } else {
                // getting result as array [id] => id
                $this->resByCriterions = $cl->getResultPairs();
            }

            unset($cl);
        }
           else
        {
            // search without sphinx
            $_fields = array('vgie.id', 'vgie.id');
            if ($with_weight) {
                if (!empty($this->keywords)) {
                    $_fields[] = 'SUM(vgtu.w) AS weight';
                } else {
                    $_fields[] = '1 AS weight';
                }
                $_fields[] = 'members_cnt';
            }

            $sql = $this->_db->select()->distinct();
            $sql->joinLeft(array('zgm' => 'zanby_groups__members'), 'vgie.id = zgm.group_id AND zgm.is_approved <> 0')
                ->group('vgie.id');

            if ($this->getTypes()) $sql->where('vgie.type IN (?)', array($this->getTypes()));
            if ($this->getIncludeIds()) $sql->where('vgie.id IN (?)', $this->getIncludeIds());
            if ($this->getExcludeIds()) $sql->where('vgie.id NOT IN (?)', $this->getExcludeIds());

            $user = Zend_Registry::get('User');
            if ($user->getId() && $user->getCityId()) { // authenticated user
                $City = Warecorp_Location_City::create($user->getCityId());
                $City->setLatitudeLongitude();
                $latitude = $City->getLatitude();
                $longitude = $City->getLongitude();
                $distance = "ROUND(SQRT(POW((69.1*(vgie.longitude-(".(float)$longitude."))*cos(vgie.latitude/57.3)),2)+POW((69.1*(vgie.latitude-(".(float)$latitude."))),2)),1)";
                $_fields[] = $distance.' AS user_city_distance';
                $sql->where('vgie.longitude IS NOT NULL')
                    ->where('vgie.latitude IS NOT NULL');
            }

            if (!empty($params['city'])){
                $City = Warecorp_Location_City::create($params['city']);
                $City->setLatitudeLongitude();
                $latitude = $City->getLatitude();
                $longitude = $City->getLongitude();
                $distance = "ROUND(SQRT(POW((69.1*(vgie.longitude-(".(float)$longitude."))*cos(vgie.latitude/57.3)),2)+POW((69.1*(vgie.latitude-(".(float)$latitude."))),2)),1)";
                $_fields[] = $distance.' AS city_distance';
                $sql->where('vgie.longitude IS NOT NULL')
                    ->where('vgie.latitude IS NOT NULL')
                    ->where($distance.'<= ?', (defined('DISTANCE_OF_SEARCH')? DISTANCE_OF_SEARCH: 200.0 ));
            } elseif (!empty($params['state'])) {
                 $sql->join(array('zlci' => 'zanby_location__cities'), 'vgie.city_id = zlci.id')
                     ->where('zlci.state_id = ?', $params['state']);
            } elseif (!empty($params['country'])) {
                 $sql->join(array('zlci' => 'zanby_location__cities'), 'vgie.city_id = zlci.id')
                     ->join(array('zls' => 'zanby_location__states'), 'zlci.state_id = zls.id')
                     ->where('zls.country_id = ?', $params['country']);
            }
            if (!empty($params['category'])) {
                $sql->where('vgie.category_id = ?', $params['category']);
            }

            if (!empty($this->keywords)) {
                $sql->join(array('vgtu' => 'view_groups__tags_used'),'vgie.id=vgtu.group_id')
                    ->where('vgtu.tag_name IN (?)', $this->keywords)
                    ->group('vgie.id');
            }
            if ($this->defaultOrder !== null) {
                $sql->order($this->defaultOrder);
            }

            $sql->from(array('vgie' => 'view_groups__items_extended'), $_fields);

            if ($with_weight) {
                $this->resByCriterions = $this->_db->fetchAll($sql);
            } else {
                $this->resByCriterions = $this->_db->fetchPairs($sql);
            }

        }
        return $this->getActualGroupList($this->resByCriterions);
    }

    /**
     * Get categories list for location
     *
     * @param Warecorp_Location_Country $country
     * @param Warecorp_Location_State $state
     * @param Warecorp_Location_City $city
     */
    public function getCategoriesListAssoc($country = null, $state = null, $city = null)
    {
        $sql = $this->_db->select()
                    ->from(array('zgc' => 'zanby_groups__categories'), array('zgc.id', 'zgc.name'))
                    ->join(array('vgie' => 'view_groups__items_extended'), 'zgc.id = vgie.category_id')
                    ->where('vgie.private = 0');

        if (!empty($city->id)) {
            $sql->where('vgie.city_id = ?', $city->id);
        } elseif (!empty($state->id)) {
            $sql->join(array('zlc' => 'zanby_location__cities'), 'vgie.city_id = zlc.id')
                ->where('zlc.state_id = ?', $state->id);
        } elseif (!empty($country->id)) {
            $sql->join(array('zlc' => 'zanby_location__cities'), 'vgie.city_id = zlc.id')
                ->join(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id')
                ->where('zls.country_id = ?', $country->id);
        }

        if ($this->getTypes()) $sql->where('vgie.type IN (?)', array($this->getTypes()));
        if ($this->getIncludeIds()) $sql->where('vgie.id IN (?)', $this->getIncludeIds());
        if ($this->getExcludeIds()) $sql->where('vgie.id NOT IN (?)', $this->getExcludeIds());

        return $this->_db->fetchPairs($sql);

    }

    /**
     * set default order (Group Search Matrix)
     * @todo needs Group Search Matrix
     */
    public function setDefaultOrder($params = array())
    {
        if (WITH_SPHINX)
        {
            /*
            * @weight 		- weight of record which finded in sphinx indexes
            * @geodist 		- distance between coordinates of geoanchor and coordinates of group
            * creation_date - date of group creation
            * member_count 	- count of members at group
            * name 			- group name
            */
            $this->defaultOrder = 'creation_date DESC, name ASC';
            $user = Zend_Registry::get('User');
            if ($user->getId()) { // authenticated user
                if (!empty($this->keywords)) {
                    $this->defaultOrder = '@weight DESC, member_count DESC';
                } else {
                    if (!empty($params['city'])) {
                        if (is_array($params['city']) && count($params['city']) > 1) {
                            $this->defaultOrder = 'member_count DESC, name ASC';
                        } else {
                            $this->defaultOrder = '@geodist ASC, member_count DESC';
                        }
                    } else {
                        $this->defaultOrder = '@geodist ASC, member_count DESC';
                        $this->paramsOrder = array('order'=>'proximityme', 'direction'=>'asc');
                    }
                }
            } else { // anonimous
                if (!empty($this->keywords)) {
                    $this->defaultOrder = '@weight DESC, member_count DESC';
                } else {
                    if (!empty($params['city'])) {
                        if (is_array($params['city']) && count($params['city']) > 1) {
                            $this->defaultOrder = 'member_count DESC, name ASC';
                        } else {
                            $this->defaultOrder = '@geodist ASC, member_count DESC';
                        }
                    } else {
                        $this->defaultOrder = 'member_count DESC, name ASC';
                        $this->paramsOrder = array('order'=>'members', 'direction'=>'desc');
                    }
                }
            }
        }
        else
        {
            $this->defaultOrder = 'vgie.creation_date DESC, vgie.name ASC';
            $user = Zend_Registry::get('User');
            if ($user->getId()) { // authenticated user
                if (!empty($this->keywords)) {
                    $this->defaultOrder = 'SUM(vgtu.w) DESC, members_cnt DESC';
                } else {
                    if (!empty($params['city'])) {
                        $this->defaultOrder = 'city_distance ASC, members_cnt DESC';
                    } else {
                        $this->defaultOrder = 'user_city_distance ASC, members_cnt DESC';
                        $this->paramsOrder = array('order'=>'proximityme', 'direction'=>'asc');
                    }
                }
            } else { // anonimous
                if (!empty($this->keywords)) {
                    $this->defaultOrder = 'SUM(vgtu.w) DESC, members_cnt DESC';
                } else {
                    if (!empty($params['city'])) {
                        $this->defaultOrder = 'city_distance ASC, members_cnt DESC';
                    } else {
                        $this->defaultOrder = 'members_cnt DESC, vgie.name';
                        $this->paramsOrder = array('order'=>'members', 'direction'=>'desc');
                    }
                }
            }
        }
    }

    /** Get count of non-private and simple groups
     * @author Andrey Kondratiev
     *
     */
    public static function getDefaultCount() {
        //SELECT count(*) FROM `view_groups__search` where `private` = 0 and type in ('simple');

        $db = & Zend_Registry::get("DB");
        $sql = $db->select();
        $sql->from('view_groups__search',array('COUNT(id)'))
        ->where('private = ?', 0)
        ->where('type IN (?)', 'simple');

        return $db->fetchOne($sql);
    }

    /**
     * @todo delete this
     */
    public static function getAllTagsPreparedByLocation($cityFilter = "", $stateFilter = "", $countryFilter = "", $limit = 30)
    {
        throw new Zend_Exception('OBSOLETE FUNCTION USED: "getAllTagsPreparedByLocation". USE Warecorp_Group_Tag_List::getListByLocation');
    }
}
