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
 * @package Warecorp_List_Search
 * @author Vitaly Targonsky
 */

class BaseWarecorp_List_Search extends Warecorp_Search
{

    private $_resByType = null;
    private $order;
    private $direction;

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder( $order )
    {
        $this->order = $order;
        return $this;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function setDirection( $direction )
    {
        $this->direction = $direction;
        return $this;
    }


    /**
     * Constructor
     */

    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * Return url for pager
     *
     * @param unknown_type $params
     */

    static public function getPagerLink($params)
    {
        $link  = '/listssearch';
        $link .= empty($params['order']) ? "" : "/order/".$params['order'] ;
        $link .= empty($params['direction']) || !in_array($params['direction'], array('asc','desc')) ? "" : "/direction/".$params['direction'];
        $list_types = Warecorp_List_Item::getListTypesListAssoc();
        $link .= empty($params['filter']) || !isset($list_types[$params['filter']]) ? "" : "/filter/".$params['filter'];
        return $link;
    }

    /**
     * @return array
     * @author Vitaly Targonsky
     */
    public function searchByCriterions($params)
    {
        if (WITH_SPHINX){
            $cl = new Warecorp_Data_Search();
            // initialization
            $cl->init('list');
            $query = "";

            $cl->SetFilter( 'group_private', array( 0 ) );
            $cl->SetFilter( 'private', array( 0 ) );

            if (EI_FILTER_ENABLED){
                $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) ));
            }

            $this->setLocationFilter($cl, $params);

            if ( isset($params['list_type']) ) {
                if ( is_array($params['list_type']) )
                    $cl->setFilter('list_type_id', $params['list_type']);
                else
                    $cl->setFilter('list_type_id', array($params['list_type']));
            }

            if (is_array($this->keywords) && count($this->keywords)) {
                $query = implode(' ', $this->keywords );
                $order = "@weight desc";
            }
            elseif ( !empty($this->keywords) && is_string($this->keywords) ) {
                $query = $this->keywords;
                $order = "@weight desc";
            }
            else {
                $order = "record_count desc";
            }


            if ( isset($params['type']) ) {
                $cl->setFilter('list_type_id', array( $params['type'] ));
            }

            if ( null !== $this->order && null !== $this->direction ) {
                $cl->SetSort("{$this->getOrder()} {$this->getDirection()}");
            }
            else {
                $cl->SetSort($order);
            }

            $cl->Query($query);

            return $cl->getResultPairs();
        }
        else{
            $_fields = array('zli.id', 'zli.id');
            $query = $this->_db->select()
                     ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                     ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = zli.owner_id AND owner_type = 'group'")
                     ->where('zli.private = 0')
                     ->where('(zgi.private = 0 OR ISNULL(zgi.private))');

            if (is_array($this->keywords) && count($this->keywords)) {
                $query->join(array('vltu' => 'view_lists__tags_used'),'zli.id=vltu.list_id')
                      ->where('vltu.tag_name IN (?)', $this->keywords)
                      ->group('zli.id')
                      ->order('SUM(vltu.w) DESC');
            } else {
                $query->joinLeft(array('zlr' => 'zanby_lists__records'), 'zli.id = zlr.list_id')
                      ->group('zli.id')
                      ->order('COUNT(zlr.id) DESC');
            }

            if (!empty($params['type'])) {
                $query->where('zli.list_type_id = ?', $params['type']);
            }
            $query->order('zli.title ASC');
            return $this->_db->fetchPairs($query);
        }
    }

    public function applyFilter($params, $lists)
    {
        if ( sizeof($lists) == 0 ) return array();

        $_order = array(
            'title'     =>'zli.title',
            'created'   =>'zli.creation_date',
            'author'    =>'zua.login',
            'items'     =>'COUNT(zlr.id)'
        );
        $sql = $this->_db->select()
                         ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                         ->where('zli.id IN (?)', array_keys($lists));
                //         ->limitPage($params['page'], $size);
        if (!empty($params['filter'])) {
            $sql->where('zli.list_type_id = ?', $params['filter']);
        }

        if ( !empty($params['order']) && isset($_order[$params['order']]) ){

            if ($params['order'] == 'author') {
                $sql->joinInner(array('zua' => 'zanby_users__accounts'), 'zli.creator_id = zua.id');
            } elseif ($params['order'] =='items') {
                $sql->joinLeft(array('zlr' => 'zanby_lists__records'), 'zli.id = zlr.list_id');
                $sql->group('zli.id');
            }

            $params['direction']  = isset($params['direction']) && in_array($params['direction'], array('asc','desc')) ? $params['direction'] : "desc";

            $sql->order($_order[$params['order']].' '.$params['direction']);

        } else {
            $params['direction']  = isset($params['direction']) && in_array($params['direction'], array('asc','desc')) ? $params['direction'] : "desc";

            // if (is_array($listSearch->keywords) && count($listSearch->keywords)) {
            //     $sql->join(array('vltu' => 'view_lists__tags_used'),'zli.id=vltu.list_id')
            //           ->where('vltu.tag_name IN (?)', $listSearch->keywords)
            //           ->group('zli.id')
            //           ->order('SUM(vltu.w) DESC')
            //           ->order('zli.title ASC');
            // }
        }
        $result = $this->_db->fetchPairs($sql);
        return $result;
    }


    /**
     * Get all lists appropriate keywords ordered by weight
     *
     * @return array
     * @author Vitaly Targonsky
     */
    public function searchByKeywords()
    {
        if (is_array($this->keywords) && count($this->keywords)) {
            if (WITH_SPHINX){
                $cl = new Warecorp_Data_Search();
                // initialization
                $cl->init('list');
                $query = "";

                $order = "@weight desc";
                $query = implode(' ', $this->keywords );
                if (EI_FILTER_ENABLED){
                    $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) ));
                }

                $cl->SetSort($order);
                $cl->Query($query);
                $this->resByKeywords = $cl->getResultIW();
            }
            else{
                $_list = new Warecorp_List_Item();
                $sql = $this->_db->select()
                                 ->from(array('ztr' => 'zanby_tags__relations'), array("ztr.entity_id", 'w' => new Zend_Db_Expr("SUM(ztr.weight_user)")))
                                 ->join(array('ztd' => 'zanby_tags__dictionary'), 'ztd.id = ztr.tag_id')
                                 ->group(array('ztr.entity_type_id', 'ztr.entity_id'))
                                 ->where('ztr.entity_type_id = ?', $_list->EntityTypeId)
                                 ->where('ztd.name IN (?)', $this->keywords)
                                 ->order('w DESC');
                $this->resByKeywords = $this->_db->fetchPairs($sql);
            }

        } else {
            $this->resByKeywords = null;
        }
        return $this->resByKeywords;

    }

   /**
     * Get all lists ordered by weight
     *
     * @return  array
     * @author Vitaly Targonsky
     */
    public function getIntersection()
    {

        $lists=array();
        if (!is_null($this->resByKeywords) && !is_null($this->resByType)) {

            foreach ($this->resByKeywords as $key=>$w) {
                if (!isset($this->resByType[$key])) unset($this->resByKeywords[$key]);
            }

            $lists = $this->resByKeywords;

        } else { // empty keywords
            $lists = $this->resByType;
        }

        return $lists;
    }

    public function getResByType()
    {
        return $this->_resByType;
    }

    public function setResByType($newVal)
    {
        $this->_resByType = $newVal;
        return $this;
    }


    /**
     * Get all list with $type if $type == 0 return lists with any type
     *
     * @param int $type
     * @return array
     */
    public function searchByType($type = 0)
    {
        $sql = $this->_db->select()
                         ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                         ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = zli.owner_id AND owner_type = 'group'")
                         ->where('zli.private = 0')
                         ->where('(zgi.private = 0 OR ISNULL(zgi.private))');
                         if (!empty($type)) $sql->where('zli.list_type_id = ?', $type);
        if (empty($this->keywords)) { // default order: items count
            $sql->joinLeft(array('zlr' => 'zanby_lists__records'), 'zli.id = zlr.list_id')
                ->group('zli.id')
                ->order('COUNT(zli.id) DESC');
        }
        $this->resByType = $this->_db->fetchPairs($sql);

        return $this->resByType;
    }
    /**
     * Get all friend's lists
     *
     * @return array
     */
    public function searchByFriends(Warecorp_User $user)
    {

        $friends = $user->getFriendsList()->returnAsAssoc()->getList();
        $res = array();

        if (is_array($friends) && count($friends)) {
            $sql = $this->_db->select()
                             ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                             ->where('zli.owner_id IN (?)', $friends)
                             ->join(array('zua' => 'zanby_users__accounts'),'zli.owner_id = zua.id')
                             ->order(array('zua.login', 'zli.title'))
                             ->where('zli.owner_type = ?', 'user')
                             ->where('zli.private = 0');
            $res = $this->_db->fetchPairs($sql);
        }
        return $res;
    }
    /**
     * Return count of friends public lists
     *
     * @return ints
     */
    public function getFriendsListsCount(Warecorp_User $user)
    {
        $friends = $user->getFriendsList()->returnAsAssoc()->getList();
        $res = 0;
        if (count($friends)) {
            $sql = $this->_db->select()
                             ->from(array('zli' => 'zanby_lists__items'), array('COUNT(zli.id)'))
                             ->join(array('zua' => 'zanby_users__accounts'),'zli.owner_id = zua.id')
                             ->where('zli.owner_id IN (?)', array_keys($friends))
                             ->where('zli.owner_type = ?', 'user')
                             ->where('zli.private = 0');
            $res = $this->_db->fetchOne($sql);
        }
        return $res;

    }
    /**
     * Get all users's group's lists
     *
     * @return array
     */
    public function searchByGroups(Warecorp_User $user)
    {

        $groups = $user->getGroups()->returnAsAssoc()->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)->getList();
        $res = array();

        if (count($groups)) {
            $sql = $this->_db->select()
                             ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                             ->where('zli.owner_id IN (?)', array_keys($groups))
                             ->where('zli.owner_type= ?', 'group')
                             ->where('zli.private = 0');
            $res = $this->_db->fetchPairs($sql);
        }
        return $res;
    }
    /**
     * Return count of public lists in $user's groups
     *
     * @param Warecorp_User $user
     * @return unknown
     */
    public function getGroupsListsCount(Warecorp_User $user)
    {
        $groups = $user->getGroups()->returnAsAssoc()->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)->getList();
        $res = 0;

        if (count($groups)) {
            $sql = $this->_db->select()
                             ->from(array('zli' => 'zanby_lists__items'), array('COUNT(zli.id)'))
                             ->where('zli.owner_id IN (?)', array_keys($groups))
                             ->where('zli.owner_type= ?', 'group')
                             ->where('zli.private = 0');
            $res = $this->_db->fetchOne($sql);
        }
        return $res;
    }
    /**
     * Get all users's family's lists
     * @return array
     */
    public function searchByFamilies(Warecorp_User $user)
    {
        $sql = $this->_db->select()
                         ->distinct()
                         ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                         ->joinInner(array('vfu' => 'view_family__users'), 'zli.owner_id = vfu.family_id')
                         ->where('zli.owner_type = ?', 'group')
                         ->where('vfu.user_id = ?', $user->getId())
                         ->where('zli.private = 0');
        $res = $this->_db->fetchPairs($sql);
        return $res;
    }
    /**
     * Return count of public lists in $user's groups
     *
     * @param Warecorp_User $user
     * @return unknown
     */
    public function getFamiliesListsCount(Warecorp_User $user)
    {
        $sql = $this->_db->select()
                         ->distinct()
                         ->from(array('zli' => 'zanby_lists__items'), array('COUNT(zli.id)'))
                         ->joinInner(array('vfu' => 'view_family__users'), 'zli.owner_id = vfu.family_id')
                         ->where('zli.owner_type = ?', 'group')
                         ->where('vfu.user_id = ?', $user->getId())
                         ->where('zli.private = 0');
        $res = $this->_db->fetchOne($sql);
        return $res;
    }
    /**
     * Get all list that owner from the certain country
     *
     * @param int $type
     * @return array
     */
    public function searchByCountry($country = 0)
    {
        $res = array();
        $sql = $this->_db->select()
                         ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                         ->joinInner(array('zua' => 'zanby_users__accounts'), 'zli.owner_id = zua.id')
                         ->joinInner(array('zlc' => 'zanby_location__cities'), 'zlc.id = zua.city_id')
                         ->joinInner(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id')
                         ->where('zli.owner_type = ?', 'user')
                         ->where('zls.country_id = ?', $country)
                         ->where('zli.private = 0');

        $res = $this->_db->fetchPairs($sql);


        $sql = $this->_db->select()
                         ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                         ->joinInner(array('zgi' => 'zanby_groups__items'), 'zli.owner_id = zgi.id')
                         ->joinInner(array('zlc' => 'zanby_location__cities'), 'zlc.id = zgi.city_id')
                         ->joinInner(array('zls' => 'zanby_location__states'), 'zls.id = zlc.state_id')
                         ->where('zli.owner_type = ?', 'group')
                         ->where('zgi.private = 0')
                         ->where('zls.country_id = ?', $country)
                         ->where('zli.private = 0')
                         ;
        $res = $res + $this->_db->fetchPairs($sql);

        return $res;
    }
    /**
     * Get all list that owner from the certain city
     *
     * @param int $type
     * @return array
     */
    public function searchByCity($city = 0)
    {
        $res = array();
        $sql = $this->_db->select()
                         ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                         ->joinInner(array('zua' => 'zanby_users__accounts'), 'zli.owner_id = zua.id')
                         ->where('zli.owner_type = ?', 'user')
                         ->where('zua.city_id = ?', $city)
                         ->where('zli.private = 0')
                         ;

        $res = $this->_db->fetchPairs($sql);

        $sql = $this->_db->select()
                         ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                         ->joinInner(array('zgi' => 'zanby_groups__items'), 'zli.owner_id = zgi.id')
                         ->where('zli.owner_type = ?', 'group')
                         ->where('zgi.private = 0')
                         ->where('zgi.city_id = ?', $city)
                         ->where('zli.private = 0')
                         ;
        $res = $res + $this->_db->fetchPairs($sql);
        return $res;
    }
    /**
     * Get all list with $type if $type == 0 return lists with any type
     *
     * @param int $type
     * @return array
     */
    public function searchByGroupCategory($category = 0)
    {
        $res = array();
        $sql = $this->_db->select()
                         ->from(array('zli' => 'zanby_lists__items'), array('zli.id', 'zli.id'))
                         ->where('zli.owner_type = ?', 'group')
                         ->joinInner(array('zgi' => 'zanby_groups__items'), 'zli.owner_id = zgi.id')
                         ->where('zgi.category_id = ?', $category)
                         ->where('zgi.private = 0')
                         ->where('zli.private = 0');
        $res = $this->_db->fetchPairs($sql);
        return $res;
    }

    /**
     * Return array of Warecorp_Group_Category for lists search (with count of public lists for each category)
     * @param boolean $need_empty_category
     * @return array of Warecorp_Group_Category
     * @author Vitaly Targonsky
     */
    public static function getGroupCategoriesList($need_empty_category = false)
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select()->from(array('zgc' => 'zanby_groups__categories'), array('zgc.id'))
                     ->join(array('zgi' => 'zanby_groups__items'), 'zgc.id = zgi.category_id', array())
                     ->where('zgi.private = 0')
                     ->group('zgc.id');
        if ($need_empty_category) {
            $select->joinLeft(array('zli' => 'zanby_lists__items'), "zli.owner_id = zgi.id AND zli.owner_type = 'group' AND zli.private = 0", array('cnt' => new Zend_Db_Expr('COUNT(zli.id)')));
        } else {
            $select->join(array('zli' => 'zanby_lists__items'), "zli.owner_id = zgi.id AND zli.owner_type = 'group' AND zli.private = 0", array('cnt' => new Zend_Db_Expr('COUNT(zli.id)')));
        }
        $categories = $db->fetchAll($select);
        foreach ($categories as &$cat) {
            $_count = $cat['cnt'];
            $cat = new Warecorp_Group_Category($cat['id']);
            $cat->listsCount = $_count;
        }
        return $categories;
    }
    /**
     * Return array of id, title, count of public lists for each lists type
     *
     * @return array
     */
    public static function getListTypesList()
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select()
            ->from(array('zlt' => 'zanby_lists__type'), array('zlt.id', 'zlt.title'))
            ->joinLeft(array('zli' => 'zanby_lists__items'), "zlt.id = zli.list_type_id AND zli.private = 0", array('lists_count' => new Zend_Db_Expr('COUNT(zli.id)')))
             ->group('zlt.id');

        $types = $db->fetchAll($select);
        $select = $db->select()
                     ->from(array('zli' => 'zanby_lists__items'), array('id' => new Zend_Db_Expr('`0`'), 'title' => new Zend_Db_Expr('All List Types'), 'lists_count' => new Zend_Db_Expr('COUNT(zli.id)')))
                     ->where('zli.private = ?', 0);
        $types = array_merge($db->fetchAll($select),$types);
        return $types;
    }
    /**
     * return counts of lists for each country
     */
    public static function getTopCountries($limit=30)
    {
        $db = Zend_Registry::get("DB");

        $limit = ($limit) ? "LIMIT 0, ".floor($limit) : "";

        $sql = "SELECT lcnt.country_id, lcnt.country_name, SUM(lcnt.lists_cnt) as lists_cnt FROM
                    (select
                        `zlco`.`id` AS `country_id`,
                        `zlco`.`name` AS `country_name`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__countries` `zlco`
                    join `zanby_location__states` `zlst` on (`zlco`.`id` = `zlst`.`country_id`)
                    join `zanby_location__cities` `zlci` on (`zlst`.`id` = `zlci`.`state_id`)
                    join `zanby_groups__items` `zgi` on(`zlci`.`id` = `zgi`.`city_id`)
                    join `zanby_lists__items` `zli` on(`zgi`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'group') and (`zgi`.`private` = 0)
                    group by `zlco`.`id`
                    union
                    select
                        `zlco`.`id` AS `country_id`,
                        `zlco`.`name` AS `country_name`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__countries` `zlco`
                    join `zanby_location__states` `zlst` on (`zlco`.`id` = `zlst`.`country_id`)
                    join `zanby_location__cities` `zlci` on (`zlst`.`id` = `zlci`.`state_id`)
                    join `zanby_users__accounts` `zua` on (`zlci`.`id` = `zua`.`city_id`)
                    join `zanby_lists__items` `zli` on (`zua`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'user')
                    group by `zlco`.`id`) as lcnt
                GROUP BY lcnt.country_id
                ORDER BY lists_cnt DESC
                {$limit}
                ";

        $data = $db->query($sql);
        return $data->fetchAll();
    }

    /**
     * return counts of lists for each state in country
     */
    public static function getTopStates($country_id = null, $limit=30)
    {
        $db = Zend_Registry::get("DB");
        $_where = "";
        if ($country_id) {
            $_where = "and zlst.country_id = ".(floor($country_id));
        }
        $limit = ($limit) ? "LIMIT 0, ".floor($limit) : "";

        $sql = "SELECT lcnt.state_id, lcnt.state_name, SUM(lcnt.lists_cnt) as lists_cnt FROM
                    (select
                        `zlst`.`id` AS `state_id`,
                        `zlst`.`name` AS `state_name`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__states` `zlst`
                    join `zanby_location__cities` `zlci` on (`zlst`.`id` = `zlci`.`state_id`)
                    join `zanby_groups__items` `zgi` on(`zlci`.`id` = `zgi`.`city_id`)
                    join `zanby_lists__items` `zli` on(`zgi`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'group') and (`zgi`.`private` = 0) {$_where}
                    group by `zlst`.`id`
                    union
                    select
                        `zlst`.`id` AS `state_id`,
                        `zlst`.`name` AS `state_name`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__states` `zlst`
                    join `zanby_location__cities` `zlci` on (`zlst`.`id` = `zlci`.`state_id`)
                    join `zanby_users__accounts` `zua` on (`zlci`.`id` = `zua`.`city_id`)
                    join `zanby_lists__items` `zli` on (`zua`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'user') {$_where}
                    group by `zlst`.`id`) as lcnt
                GROUP BY lcnt.state_id
                ORDER BY lists_cnt DESC
                {$limit}
                ";

        $data = $db->query($sql);
        return $data->fetchAll();
    }
    /**
     * return counts of lists for each city in state
     */
    public static function getTopCities($state_id = null, $limit=30)
    {
        $db = Zend_Registry::get("DB");
        $_where = "";
        if ($state_id) {
            $_where = "and zlci.state_id = ".(floor($state_id));
        }
        $limit = ($limit) ? "LIMIT 0, ".floor($limit) : "";

        $sql = "SELECT lcnt.city_id, lcnt.city_name, SUM(lcnt.lists_cnt) as lists_cnt FROM
                    (select
                        `zlci`.`id` AS `city_id`,
                        `zlci`.`name` AS `city_name`,
                        `zlci`.`state_id` AS `state_id`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__cities` `zlci`
                    join `zanby_groups__items` `zgi` on(`zlci`.`id` = `zgi`.`city_id`)
                    join `zanby_lists__items` `zli` on(`zgi`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'group') and (`zgi`.`private` = 0) {$_where}
                    group by `zlci`.`id`
                    union
                    select
                        `zlci`.`id` AS `city_id`,
                        `zlci`.`name` AS `city_name`,
                        `zlci`.`state_id` AS `state_id`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__cities` `zlci`
                    join `zanby_users__accounts` `zua` on (`zlci`.`id` = `zua`.`city_id`)
                    join `zanby_lists__items` `zli` on (`zua`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'user') {$_where}
                    group by `zlci`.`id`) as lcnt
                GROUP BY lcnt.city_id
                ORDER BY lists_cnt DESC
                {$limit}
                ";

        $data = $db->query($sql);
        return $data->fetchAll();
    }
    /**
     * return counts of lists for each country
     */
    public static function getCountriesListsCounts()
    {
        $db = Zend_Registry::get("DB");

        $sql = "SELECT lcnt.id, SUM(lcnt.lists_cnt) as lists_cnt FROM
                    (select
                        `zlco`.`id` AS `id`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__countries` `zlco`
                    join `zanby_location__states` `zlst` on (`zlco`.`id` = `zlst`.`country_id`)
                    join `zanby_location__cities` `zlci` on (`zlst`.`id` = `zlci`.`state_id`)
                    join `zanby_groups__items` `zgi` on(`zlci`.`id` = `zgi`.`city_id`)
                    join `zanby_lists__items` `zli` on(`zgi`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'group') and (`zgi`.`private` = 0)
                    group by `zlco`.`id`
                    union
                    select
                        `zlco`.`id` AS `id`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__countries` `zlco`
                    join `zanby_location__states` `zlst` on (`zlco`.`id` = `zlst`.`country_id`)
                    join `zanby_location__cities` `zlci` on (`zlst`.`id` = `zlci`.`state_id`)
                    join `zanby_users__accounts` `zua` on (`zlci`.`id` = `zua`.`city_id`)
                    join `zanby_lists__items` `zli` on (`zua`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'user')
                    group by `zlco`.`id`) as lcnt
                GROUP BY lcnt.id";

        $data = $db->query($sql);
        return $data->fetchAll();
    }
    /**
     * return counts of lists for each state in country
     */
    public static function getStatesListsCounts($country_id = null)
    {
        $db = Zend_Registry::get("DB");
        $_where = "";
        if ($country_id) {
            $_where = "and zlst.country_id = ".(floor($country_id));
        }
        $sql = "SELECT lcnt.id, SUM(lcnt.lists_cnt) as lists_cnt FROM
                    (select
                        `zlst`.`id` AS `id`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__states` `zlst`
                    join `zanby_location__cities` `zlci` on (`zlst`.`id` = `zlci`.`state_id`)
                    join `zanby_groups__items` `zgi` on(`zlci`.`id` = `zgi`.`city_id`)
                    join `zanby_lists__items` `zli` on(`zgi`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'group') and (`zgi`.`private` = 0) {$_where}
                    group by `zlst`.`id`
                    union
                    select
                        `zlst`.`id` AS `id`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__states` `zlst`
                    join `zanby_location__cities` `zlci` on (`zlst`.`id` = `zlci`.`state_id`)
                    join `zanby_users__accounts` `zua` on (`zlci`.`id` = `zua`.`city_id`)
                    join `zanby_lists__items` `zli` on (`zua`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'user') {$_where}
                    group by `zlst`.`id`) as lcnt
                GROUP BY lcnt.id";

        $data = $db->query($sql);
        return $data->fetchAll();
    }
    /**
     * return counts of lists for each city in state
     */
    public static function getCitiesListsCounts($state_id = null)
    {
        $db = Zend_Registry::get("DB");
        $_where = "";
        if ($state_id) {
            $_where = "and zlci.state_id = ".(floor($state_id));
        }

        $sql = "SELECT lcnt.id, SUM(lcnt.lists_cnt) as lists_cnt FROM
                    (select
                        `zlci`.`id` AS `id`,
                        `zlci`.`state_id` AS `state_id`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__cities` `zlci`
                    join `zanby_groups__items` `zgi` on(`zlci`.`id` = `zgi`.`city_id`)
                    join `zanby_lists__items` `zli` on(`zgi`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'group') and (`zgi`.`private` = 0) {$_where}
                    group by `zlci`.`id`
                    union
                    select
                        `zlci`.`id` AS `id`,
                        `zlci`.`state_id` AS `state_id`,
                        count(`zli`.`id`) AS `lists_cnt`
                    from `zanby_location__cities` `zlci`
                    join `zanby_users__accounts` `zua` on (`zlci`.`id` = `zua`.`city_id`)
                    join `zanby_lists__items` `zli` on (`zua`.`id` = `zli`.`owner_id`)
                    where (`zli`.`private` = 0) and (`zli`.`owner_type` = _utf8'user') {$_where}
                    group by `zlci`.`id`) as lcnt
                GROUP BY lcnt.id";

        $data = $db->query($sql);
        return $data->fetchAll();
    }
}

