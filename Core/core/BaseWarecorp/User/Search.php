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
 * @package Warecorp_User_Search
 * @author Vitaly Targonsky
 */

class BaseWarecorp_User_Search extends Warecorp_Search
{

    private $orders = null;
    public $defaultOrder = null;
    public $paramsOrder = null;
    /**
     * Конструктор
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return url for pager
     */
    public function getPagerLink($params)
    {
        $_orders = $this->getOrders();
        $link  = "/".LOCALE."/users/search";
        $link .= empty($params['order']) || !isset($_orders[$params['order']]) ? "" : "/order/".$params['order'] ;
        $link .= empty($params['direction']) || !in_array($params['direction'], array('asc','desc')) ? "" : "/direction/".$params['direction'];
        return $link;

    }


    /**
     * Return url for pager
     */
    public function getPagerLinkGlobalSearch($params)
    {
        $_orders = $this->getOrders();
        $link  = "/".LOCALE."/search/members";
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
            $this->orders = array('joined'=>'zua.register_date','laston'=>'zua.last_access','name'=>'zua.login','photo'=>'zua.login');
            $user = Zend_Registry::get('User');
            if ($user->getId()) {
                $this->orders['proximityme']="SQRT((69.1*(zua.longitude-(".(float)$user->getLongitude()."))*cos(zua.latitude/57.3))*(69.1*(zua.longitude-(".(float)$user->getLongitude()."))*cos(zua.latitude/57.3))+(69.1*(zua.latitude-(".(float)$user->getLatitude().")))*(69.1*(zua.latitude-(".(float)$user->getLatitude()."))))";
            }
        }
        return $this->orders;
    }
    /**
     * Return ordered list of users
     *
     * @param array $params - hash of post and get
     * @param array $users - list of cached users
     * @param int $size
     * @return array
     */
    public function getOrdered($params, $users, $size = 10)
    {
        $_in = (count($users)) ? array_keys($users) : "";
        $_orders = $this->getOrders();
        $sql = $this->_db->select()
                    ->from(array('zua' => 'zanby_users__accounts'), array('zua.id','zua.id'))
                    ->where('zua.id IN (?)', $_in)
                    ->where('zua.status =?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE)
                    ->order($_orders[$params['order']].' '.$params['direction'])
                    ->limitPage($params['page'], $size);
        if ($this->getIncludeIds()) $sql->where('zua.id IN (?)', $this->getIncludeIds());
        if ($this->getExcludeIds()) $sql->where('zua.id NOT IN (?)', $this->getExcludeIds());
        return $this->_db->fetchPairs($sql);
    }
   /**
     * Get all users ordered by weight
     *
     * @return  array
     * @author Vitaly Targonsky
     */
    public function getIntersection(){

        $users=array();
        $this->paramsOrder = null;

        if (count($this->resByCriterions)) {
            foreach ($this->resByCriterions as &$_user) {
                if ($_user['weight'] > 0) {
                    if (!isset($users[$_user['id']])) {
                        $users[$_user['id']]['weight']=0;
                        $users[$_user['id']]['login'] = $_user['login'];
                        $users[$_user['id']]['register_date'] = $_user['register_date'];
                    }
                    $users[$_user['id']]['weight'] += $_user['weight'];
                }
            }
        }

        if (count($this->resByZipCodes) && count($users)) {
            foreach ($this->resByZipCodes as &$_user) {
                if ($_user['weight'] > 0) {
                    if (isset($users[$_user['id']])) {
                        $users[$_user['id']]['weight'] += $_user['weight'];
                        $users[$_user['id']]['intersect'] = 1;
                    }
                }
            }
        }
        if (count($users)) {
            foreach($users as $key=>&$_user) {
                if (!isset($_user['intersect'])) unset($users[$key]);
            }
        }

        if (strpos($this->defaultOrder, 'register_date')!==false) {
            uasort($users, "Warecorp_User_Search::compareWeigthRegdate");
        } else {
            uasort($users, "Warecorp_User_Search::compareWeigthLogin");
        }

        return $users;
    }
    /**
     * method for user sorting by weigth desc, then register date desc
     *
     * @param array $e1
     * @param array $e2
     * @return int
     */
    public static function compareWeigthRegdate($e1, $e2)
    {
        if ($e1['weight'] < $e2['weight']) return 1;
        elseif ($e1['weight'] > $e2['weight']) return -1;
        elseif ($e1['register_date'] < $e2['register_date']) return 1;
        elseif ($e1['register_date'] > $e2['register_date']) return -1;
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

            if (WITH_SPHINX){
                 foreach ($this->zipcodes as &$zip) {
                    $query = "";
                    // create object Warecorp_Data_Search
                    $cl = new Warecorp_Data_Search();
                       // initialization
                    $cl->init('user');

                    // set include and exclude filters if it's necessary
                    if ($this->getIncludeIds()) $cl->SetIDFilter ( $this->getIncludeIds() );
                    if ($this->getExcludeIds()) $cl->SetIDFilter ( $this->getExcludeIds(), true );

                    // set geoanchor to current user coordinates
                    // it's necessary for creating geodistance order
                    $cl->SetFilterGeo('latitude', 'longitude', deg2rad((float)$zip['latitude']), deg2rad((float)$zip['longitude']), (defined('DISTANCE_OF_SEARCH')? DISTANCE_OF_SEARCH: 200.0 )*1000);

                    // set private filter
                    $cl->SetFilter ( 'private', array( 0 ));

                    if ($this->defaultOrder !== null)
            {
                        $cl->SetSort( $this->defaultOrder );
                    }

            if ($this->_offsetResults !== null || $this->_limitResults !== null)
            {
            $cl->setLimits($this->_offsetResults, $this->_limitResults);
            }

                    // send search query
                    $cl->Query( $query, null, true );

                    $this->resByZipCodes += $cl->getResultIWDLRD();

                    unset($cl);
                }
            }
            else{

                foreach ($this->zipcodes as &$zip) {
                    $_zips[] = "ROUND(SQRT(POW((69.1*(zua.longitude-(".(float)$zip['longitude']."))*cos(zua.latitude/57.3)),2)+POW((69.1*(zua.latitude-(".(float)$zip['latitude']."))),2)),1)";
                }
                $distance = "(".implode('+',$_zips).")/".count($this->zipcodes);

                $cfgWeight = Warecorp_Config_Loader::getInstance()->getCoreConfig('cfg.weight.xml')->{'user_distance'};

                $weight = "CASE \n";
                foreach ($cfgWeight as $w) {
                    $weight .= "WHEN ".$distance.($w->sign).($w->distance)." THEN ".($w->weight)."\n";
                }
                $weight .= "WHEN 1=1 THEN 0 \n END as weight";

                $sql = $this->_db->select()
                                 ->from(array('zua' => 'zanby_users__accounts'), array('zua.id', 'distance' => $distance, $weight, 'zua.login', 'zua.register_date'))
                                 ->where('zua.longitude IS NOT NULL')
                                 ->where('zua.latitude IS NOT NULL')
                                 ->where($distance ."<= ?", (defined('DISTANCE_OF_SEARCH')? DISTANCE_OF_SEARCH: 200.0 ))
                                 ->where('zua.status =?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE)
                                 ->order(array('distance ASC', 'zua.login ASC'));
                if ($this->getIncludeIds()) $sql->where('zua.id IN (?)', $this->getIncludeIds());
                if ($this->getExcludeIds()) $sql->where('zua.id NOT IN (?)', $this->getExcludeIds());
                $this->resByZipCodes = $this->_db->fetchAll($sql);
            }
        }
        return $this->resByZipCodes;
    }

    /**
     * Search by criterions
     *
     * @return array
     * @author Vitaly Targonsky
     */
    public function searchByCriterions($params, $with_weight = false)
    {
        if (WITH_SPHINX){
            // create object Warecorp_Data_Search
            $cl = new Warecorp_Data_Search();
            // initialization
            $cl->init('user');
            $query = "";

            if ($this->getIncludeIds()) $cl->SetIDFilter ( $this->getIncludeIds() );
            if ($this->getExcludeIds()) $cl->SetIDFilter ( $this->getExcludeIds(), true );

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

            // @todo apply filter by category!
            if ( isset($params['category']) ) {
                $cl->SetFilter ( "category_list", array( intval($params['category']) ) );
            }

            if ( isset($params['age_from']) || !empty($params['age_to']) ) {
                $cl->SetFilter ( "birthday_private", array( 0 ) );
                $age_from = !empty($params['age_from'])? $params['age_from']: 0;
                $age_to = !empty($params['age_to'])? $params['age_to']: 1000;
                $cl->SetFilterRange('age', intval($age_from), intval($age_to) );
            }

            if ( isset($params['photo_only']) ) {
                $cl->SetFilter ( "photo_only", array( 1 ) );
            }

            if ( isset($params['gender']) ) {
                if ($params['gender'] == 'male'){
                    $genderIndex = 1;
                }
                elseif ($params['gender'] == 'female') {
                    $genderIndex = 2;
                }
                else {
                    $genderIndex = 10;
                }

                $cl->SetFilter ( "gender", array( intval($genderIndex) ) );
                $cl->SetFilter ( "gender_private", array( 0 ) );
            }

            if ($this->_limitResults !== null || $this->_offsetResults !== null)
            {
                $cl->setLimits($this->_offsetResults, $this->_limitResults);
            }

            if ($this->defaultOrder !== null) {
                $cl->SetSort( $this->defaultOrder );
            }

            if (!empty($this->keywords)) {
                // creating keywords string with space separator
                $query = implode(" ", $this->keywords);
            }

            // send search query
            $cl->Query( $query, null, true );

            if ($with_weight) {
                // getting result with id, weight, distance and membres count
                $this->resByCriterions = $cl->getResultIWDLRD();
            } else {
                // getting result as array [id] => id
                $this->resByCriterions = $cl->getResultPairs();
            }

            unset ($cl);
        }
        else{

            $_fields = array('zua.id', 'zua.id');
            if ($with_weight) {
                if (!empty($this->keywords)) {
                    $_fields[] = 'SUM(vutu.w) AS weight';
                } else {
                    $_fields[] = '1 AS weight';
                }
                $_fields[] = 'zua.login';
                $_fields[] = 'zua.register_date';
            }
            $sql = $this->_db->select()->distinct()
                             ->where('zua.status =?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);
            if ($this->getIncludeIds()) $sql->where('zua.id IN (?)', $this->getIncludeIds());
            if ($this->getExcludeIds()) $sql->where('zua.id NOT IN (?)', $this->getExcludeIds());

            $user = Zend_Registry::get('User');
            if ($user->getId() && $user->getCityId()) { // authenticated user
                $City = Warecorp_Location_City::create($user->getCityId());
                $City->setLatitudeLongitude();
                $latitude = $City->getLatitude();
                $longitude = $City->getLongitude();
                $distance = "ROUND(SQRT(POW((69.1*(zua.longitude-(".(float)$longitude."))*cos(zua.latitude/57.3)),2)+POW((69.1*(zua.latitude-(".(float)$latitude."))),2)),1)";
                $_fields[] = $distance.' AS user_city_distance';
                $sql->where('zua.longitude IS NOT NULL')
                    ->where('zua.latitude IS NOT NULL');
            }
            if (!empty($params['city'])){
                $City = Warecorp_Location_City::create($params['city']);
                $City->setLatitudeLongitude();
                $latitude = $City->getLatitude();
                $longitude = $City->getLongitude();
                $distance = "ROUND(SQRT(POW((69.1*(zua.longitude-(".(float)$longitude."))*cos(zua.latitude/57.3)),2)+POW((69.1*(zua.latitude-(".(float)$latitude."))),2)),1)";
                $_fields[] = $distance.' AS city_distance';
                $sql->where('zua.longitude IS NOT NULL')
                    ->where('zua.latitude IS NOT NULL')
                    ->where($distance.'<= ?', (defined('DISTANCE_OF_SEARCH')? DISTANCE_OF_SEARCH: 200.0 ));
            } elseif (!empty($params['state'])) {
                 $sql->join(array('zlci' => 'zanby_location__cities'), 'zua.city_id = zlci.id')
                     ->where('zlci.state_id = ?', $params['state']);
            } elseif (!empty($params['country'])) {
                 $sql->join(array('zlci' => 'zanby_location__cities'), 'zua.city_id = zlci.id')
                     ->join(array('zls' => 'zanby_location__states'), 'zlci.state_id = zls.id')
                     ->where('zls.country_id = ?', $params['country']);
            }
            if (!empty($params['category'])) {
                $sql->join(array('zgm' => 'zanby_groups__members'), 'zua.id = zgm.user_id')
                    ->join(array('zgi' => 'zanby_groups__items'), 'zgm.group_id = zgi.id')
                    ->where('zgi.category_id = ?', $params['category']);
            }
            if (!empty($params['age_from']) || !empty($params['age_to'])) {
                $sql->where('zua.birthday_private = 0');
            }
            if (!empty($params['age_from'])) {
                $sql->where('((YEAR(CURRENT_DATE)-YEAR(zua.birthday)) - (RIGHT(CURRENT_DATE,5)<RIGHT(zua.birthday,5))) >= ?', $params['age_from']);
            }
            if (!empty($params['age_to'])) {
                $sql->where('((YEAR(CURRENT_DATE)-YEAR(zua.birthday)) - (RIGHT(CURRENT_DATE,5)<RIGHT(zua.birthday,5))) <= ?', $params['age_to']);
            }
            if (!empty($params['photo_only'])) {
                $sql->join(array('zuav' => 'zanby_users__avatars'), 'zua.id = zuav.user_id')
                    ->where('zuav.bydefault = ?', '1');
            }
            if (!empty($params['gender'])) {
                $sql->where('zua.gender = ?', $params['gender'])
                    ->where('zua.gender_private = ?', 0);
            }
            if (!empty($this->keywords)) {
                $sql->join(array('vutu' => 'view_users__tags_used'),'zua.id=vutu.user_id')
                    ->where('vutu.tag_name IN (?)', $this->keywords)
                    ->group('zua.id');
            }
            if ($this->defaultOrder !== null) {
                $sql->order($this->defaultOrder);
            }

            $sql->from(array('zua' => 'zanby_users__accounts'), $_fields);

            if ($with_weight) {
                $this->resByCriterions = $this->_db->fetchAll($sql);
            } else {
                $this->resByCriterions = $this->_db->fetchPairs($sql);
            }
        }
        return $this->resByCriterions;

    }
    /**
     * Searches for user by given email address
     *
     * @param string $userEmail
     * @return int id of found user or null if not found
     */
    public function searchByEmail($userEmail)
    {
        if ( !empty($userEmail) ) {
            $query = $this->_db->select();
            $query->from(array('zua' => 'zanby_users__accounts'), array('zua.id'));
            $query->where('zua.email = ?', $userEmail);
            $query->where('zua.status = ?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);

            $result = $this->_db->fetchOne($query);
            $this->resByEmail = $result ? intval($result) : null;

            return $this->resByEmail;
        }
        else return null;
    }

    /**
     * Search by group category
     *
     * @param int $category_id
     * @return array
     */

    public function searchByCategory($category_id)
    {
        $result = array();

        if (!empty($category_id) ) {
            $sql = $this->_db->select()
                             ->from(array('zua' => 'zanby_users__accounts'), array('zua.id', 'zua.id'))
                             ->join(array('zgm' => 'zanby_groups__members'), 'zua.id = zgm.user_id')
                             ->join(array('zgi' => 'zanby_groups__items'), 'zgm.group_id = zgi.id')
                             ->where('zua.status =?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE)
                             ->where('zgi.category_id = ?', $category_id);

            if ($this->getIncludeIds()) $sql->where('zua.id IN (?)', $this->getIncludeIds());
            if ($this->getExcludeIds()) $sql->where('zua.id NOT IN (?)', $this->getExcludeIds());
            $result = $this->_db->fetchPairs($sql);
        }
        return $result;
    }

    /**
     * Search by user tags
     *
     * @param Warecorp_User $user
     */
    public function searchByUserTags($user)
    {
        $result = array();
        $sql = $this->_db->select()
                    ->distinct()
                    ->from(array('ztr' => 'zanby_tags__relations'), array('id' => 'ztr.tag_id'))
                    ->where('ztr.entity_type_id =?', $user->EntityTypeId)
                    ->where('ztr.entity_id = ?', $user->getId())
                    ->where('ztr.status = ?','user');
        $tags = $this->_db->fetchCol($sql);
        if (count($tags)) {
            $sql = $this->_db->select()
                        ->distinct()
                        ->from(array('ztr' => 'zanby_tags__relations'), array('id' => 'ztr.entity_id', 'ztr.entity_id'))
                        ->join(array('zua' => 'zanby_users__accounts'), 'ztr.entity_id = zua.id')
                        ->where('ztr.entity_type_id =?', $user->EntityTypeId)
                        ->where('ztr.tag_id IN(?)', $tags)
                        ->where('zua.status =?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE)
                        ->where('ztr.status = ?','user');

            if ($this->getIncludeIds()) $sql->where('zua.id IN (?)', $this->getIncludeIds());
            if ($this->getExcludeIds()) $sql->where('zua.id NOT IN (?)', $this->getExcludeIds());
            $result = $this->_db->fetchPairs($sql);
        }
        return $result;
    }
    /**
     * Search by user friends tags
     * @param Warecorp_User $user
     */
    public function searchByFriendsTags(Warecorp_User $user)
    {

        $result = array();
        $friends = $user->getFriendsList()->returnAsAssoc()->getList();
        $sql = $this->_db->select()
                    ->distinct()
                    ->from(array('ztr' => 'zanby_tags__relations'), array('id' => 'ztr.tag_id'))
                    ->where('ztr.entity_type_id =?', $user->EntityTypeId)
                    ->where('ztr.entity_id IN (?)', $friends ? $friends : false)
                    ->where('ztr.status = ?','user');
        $tags = $this->_db->fetchCol($sql);
        if (count($tags)) {
            $sql = $this->_db->select()
                        ->distinct()
                        ->from(array('ztr' => 'zanby_tags__relations'), array('id' => 'ztr.entity_id', 'ztr.entity_id'))
                        ->join(array('zua' => 'zanby_users__accounts'), 'ztr.entity_id = zua.id')
                        ->where('ztr.entity_type_id =?', $user->EntityTypeId)
                        ->where('ztr.tag_id IN(?)', $tags)
                        ->where('zua.status =?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE)
                        ->where('ztr.status = ?','user');

            if ($this->getIncludeIds()) $sql->where('zua.id IN (?)', $this->getIncludeIds());
            if ($this->getExcludeIds()) $sql->where('zua.id NOT IN (?)', $this->getExcludeIds());
            $result = $this->_db->fetchPairs($sql);
        }
        return $result;
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
                    ->distinct()
                    ->from(array('zgc' => 'zanby_groups__categories'), array('zgc.id', 'zgc.name'))
                    ->join(array('zgi' => 'zanby_groups__items'), 'zgc.id = zgi.category_id')
                    ->join(array('zgm' => 'zanby_groups__members'), 'zgi.id = zgm.group_id')
                    ->join(array('zua' => 'zanby_users__accounts'), 'zgm.user_id = zua.id')
                    ->where('zua.status =?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE)
                    ->where('zgi.private = 0');

        if ($this->getIncludeIds()) $sql->where('zua.id IN (?)', $this->getIncludeIds());
        if ($this->getExcludeIds()) $sql->where('zua.id NOT IN (?)', $this->getExcludeIds());

        if (!empty($city->id)) {
            $sql->where('zua.city_id = ?', $city->id);
        } elseif (!empty($state->id)) {
            $sql->join(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id')
                ->where('zlc.state_id = ?', $state->id);
        } elseif (!empty($country->id)) {
            $sql->join(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id')
                ->join(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id')
                ->where('zls.country_id = ?', $country->id);
        }

        return $this->_db->fetchPairs($sql);

    }
    /**
     * set default order (Member Search Matrix)
     *
     */
    public function setDefaultOrder($params = array())
    {
        if (WITH_SPHINX)
        {
            $this->defaultOrder = ' register_date DESC, login ASC';
            $user = Zend_Registry::get('User');
            if ($user->getId()) { // authenticated user
                if (!empty($this->keywords)) {
                    $this->defaultOrder = '@weight DESC, login ASC';
                } else {
                    if (!empty($params['city']) || !empty($params['state']) || !empty($params['category'])) {
                        if (!empty($params['city'])) {
                            if (is_array($params['city']) && count($params['city']) > 1) {
                                $this->defaultOrder = 'register_date DESC, login ASC';
                            } else {
                                $this->defaultOrder = '@geodist ASC, login ASC';
                            }
                        } else {
                            $this->defaultOrder = '@geodist ASC, login ASC';
                            $this->paramsOrder = array('order'=>'proximityme', 'direction'=>'asc');
                        }
                    } else {
                        $this->defaultOrder = 'register_date DESC, login ASC';
                        $this->paramsOrder = array('order'=>'joined', 'direction'=>'desc');
                    }
                }
            } else { // anonimous
                if (!empty($this->keywords)) {
                    $this->defaultOrder = '@weight DESC, ';
                    if (!empty($params['city']) || !empty($params['state'])) {
                        $this->defaultOrder .= 'login ASC';
                    } else {
                        $this->defaultOrder .= 'register_date DESC';
                    }
                } else {
                    if (!empty($params['city'])) {
                        if (is_array($params['city']) && count($params['city']) > 1) {
                            $this->defaultOrder = 'register_date DESC, login ASC';
                        } else {
                            $this->defaultOrder = '@geodist ASC, ';
                        }
                    } elseif (!empty($params['state'])) {
                        $this->defaultOrder = 'login ASC';
                    } else {
                        $this->defaultOrder = 'register_date DESC';
                    }
                }
            }

            if ($this->defaultOrder == 'login ASC') {
                $this->paramsOrder = array('order'=>'name', 'direction'=>'asc');
            } elseif ($this->defaultOrder == 'register_date DESC') {
                $this->paramsOrder = array('order'=>'joined', 'direction'=>'desc');
            }
        }
        else{
            $this->defaultOrder = 'zua.register_date DESC, zua.login ASC';
            $user = Zend_Registry::get('User');
            if ($user->getId()) { // authenticated user
                if (!empty($this->keywords)) {
                    $this->defaultOrder = 'SUM(vutu.w) DESC, zua.login ASC';
                } else {
                    if (!empty($params['city']) || !empty($params['state']) || !empty($params['category'])) {
                        if (!empty($params['city'])) {
                            $this->defaultOrder = 'city_distance ASC, zua.login ASC';
                        } else {
                            $this->defaultOrder = 'user_city_distance ASC, zua.login ASC';
                            $this->paramsOrder = array('order'=>'proximityme', 'direction'=>'asc');
                        }
                    } else {
                        $this->defaultOrder = 'zua.register_date DESC, zua.login ASC';
                        $this->paramsOrder = array('order'=>'joined', 'direction'=>'desc');
                    }
                }
            } else { // anonimous
                if (!empty($this->keywords)) {
                    $this->defaultOrder = 'SUM(vutu.w) DESC, ';
                    if (!empty($params['city']) || !empty($params['state'])) {
                        $this->defaultOrder .= 'zua.login ASC';
                    } else {
                        $this->defaultOrder .= 'zua.register_date DESC';
                    }
                } else {
                    if (!empty($params['city'])) {
                        $this->defaultOrder = 'city_distance ASC, ';
                    } else {
                        $this->defaultOrder = '';
                    }
                    if (!empty($params['state'])) {
                        $this->defaultOrder .= 'zua.login ASC';
                    } else {
                        $this->defaultOrder .= 'zua.register_date DESC';
                    }
                }
            }

            if ($this->defaultOrder == 'zua.login ASC') {
                $this->paramsOrder = array('order'=>'name', 'direction'=>'asc');
            } elseif ($this->defaultOrder == 'zua.register_date DESC') {
                $this->paramsOrder = array('order'=>'joined', 'direction'=>'desc');
            }
        }


    }
    /**
     * @todo delete this
     */
    public static function getAllTagsPreparedByLocation($cityFilter = "", $stateFilter = "", $countryFilter = "", $limit = 30)
    {
        throw new Zend_Exception('OBSOLETE FUNCTION USED: "getAllTagsPreparedByLocation". USE Warecorp_User_Tag_List::getListByLocation');
    }
    /**
     * set exclude and include ids in accordance with privacy settings
     * @author Vitaly Targonsky
     */
    public function setExcludeAndIncludeIds()
    {
        $user = Zend_Registry::get('User');
        if (!$user->getId()) { // anonimous
            $sql = $this->_db->select()
                        ->from(array('zup' => 'zanby_users__privacy'), 'user_id')
                        ->join(array('zua' => 'zanby_users__accounts'), 'zua.id = zup.user_id')
                        ->where('zua.status =?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE)
                        ->where('zup.sr_anyone =?', 1);
        } else {

            $this->setExcludeIds($user->getPrivacy()->getBlockList()->setAssocValue('id')->returnAsAssoc()->getInvertList());

            $sql = $this->_db->select()
                        ->from(array('zup' => 'zanby_users__privacy'), 'user_id')
                        ->join(array('zua' => 'zanby_users__accounts'), 'zua.id = zup.user_id')
                        ->where('zua.status =?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);
            $where = array();
            $where[] = $this->_db->quoteInto('zup.sr_anyone = ?', 1);
            $where[] = $this->_db->quoteInto('zup.sr_any_members = ?', 1);
            $where[] = $this->_db->quoteInto("(zup.sr_group_organizers = 1 AND ? IN (select distinct zgm.user_id from zanby_groups__members zgm where zgm.status in ('host','cohost')))", $user->getId());
            $_in = Warecorp_Group_Members_Abstract::getAllGroupMembersByUserAndRole($user, 'host;cohost');
            if ($_in) $where[] = $this->_db->quoteInto("(zup.sr_my_group_organizers = 1 AND zup.user_id IN (?))", $_in);

            $_in = Warecorp_Group_Members_Abstract::getAllGroupMembersByUserAndRole($user, 'host;cohost;member');
            if ($_in) $where[] = $this->_db->quoteInto("(zup.sr_my_group_members = 1 AND zup.user_id IN (?))", $_in);

            $_in = $user->getFriendsList()->returnAsAssoc()->getList();
            if ($_in) $where[] = $this->_db->quoteInto("(zup.sr_my_friends = 1 AND zup.user_id IN (?))", $_in);

            $_in = $user->getFriendsOfFriendsList()->returnAsAssoc()->getList();
            if ($_in) $where[] = $this->_db->quoteInto("(zup.sr_my_network = 1 AND zup.user_id IN (?))", $_in);

            $addrBookList = new Warecorp_User_Addressbook_List();
            $_in = $addrBookList->returnAsAssoc()
                                ->addWhere('vai.entity_id = ?', $user->getId())
                                ->addWhere('vai.entity_type = ?', Warecorp_User_Addressbook_eType::USER)
                                ->setAssocKey('vai.owner_id')
                                ->setAssocValue('vai.owner_id')
                                ->getList();

            if ($_in) $where[] = $this->_db->quoteInto("(zup.sr_my_address_book = 1 AND zup.user_id IN (?))", $_in);

            $sql->where('('.implode(' OR ', $where).')', null);
        }
        $this->setIncludeIds($this->_db->fetchCol($sql));

        return $this;
    }
}
