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
 * @package Warecorp_Photo_Search
 * @author Yury Zolotarsky
 */

class BaseWarecorp_Photo_Search extends Warecorp_Search
{

    private $resByWhoUploaded = null;
    private $resOfIntersection = null;
    private $res = null; //last search operation result
    private $whoUploaded;
    private $country;
    private $city;
    private $resByCountry = null;
    private $order = 4;
    private $page = 1;
    private $size = 20;
    private $whoUploadedParams;
    public static $age_requirement = 16;

    public static $sortList = 	array(
        'maxkey' => 4,
        'titles' => array(
            1 => 'All - Newest to oldest', //default for non keyword search must be permanent 1
            2 => 'All - Oldest to Newest',
            3 => 'All - by Name Alphabetical',
            4 => 'no sort',  //default for keywords search  must be permanent 4
        ),
        'sql' => array(
            1 => 'creation_date desc', //default for non keyword search must be permanent 1
            2 => 'creation_date asc',
            3 => 'title asc',
            4 => 'no sort'	//default for keywords search  must be permanent 4 and value 'no sort'
        )
        ,
        'indexer' => array(
            1 => 'creation_date desc', //default for non keyword search must be permanent 1
            2 => 'creation_date asc',
            3 => 'photo_title asc',
            4 => 'no sort'    //default for keywords search  must be permanent 4 and value 'no sort'
        )
    );

    public static $whoUploadedList = 	array(
        'maxkey' => 4,
        'titles' => array(
            1 => 'AnyOne',
            2 => 'Friends',
            3 => 'My Groups',
            4 => 'My Group Families'
        )
    );

    /**
     * Constructor
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
    }


    /**
     * return counts of photos for each country
     */
    public static function getTopCountries($limit=30)
    {
        $db = Zend_Registry::get("DB");

        $limit = ($limit) ? "LIMIT 0, ".floor($limit) : "";

        $sql = "select pid as id, pname as name, SUM(pcount) as photos_count from
                (select zlcs.id as pid, zlcs.name as pname, count(zlcs.id) as pcount from zanby_galleries__photos zgp
                    inner join zanby_galleries__items zgi on (zgp.gallery_id = zgi.id)
                    inner join zanby_users__accounts zua on (zua.id = zgi.owner_id)
                    inner join zanby_location__cities zlc on (zua.city_id = zlc.id)
                    inner join zanby_location__states zls on (zlc.state_id = zls.id)
                    inner join zanby_location__countries zlcs on (zls.country_id = zlcs.id)
                    where (zgi.owner_type = _utf8'user') and (zgi.private = 0) and (zgi.iscreated = 1) group by zlcs.id
                    union
                    select zlcs.id as pid, zlcs.name as pname, count(zlcs.id) as pcount from zanby_galleries__photos zgp
                    inner join zanby_galleries__items zgi on (zgp.gallery_id = zgi.id)
                    inner join zanby_groups__items zgri on (zgri.id = zgi.owner_id)
                    inner join zanby_location__cities zlc on (zgri.city_id = zlc.id)
                    inner join zanby_location__states zls on (zlc.state_id = zls.id)
                    inner join zanby_location__countries zlcs on (zls.country_id = zlcs.id)
                    where (zgi.owner_type = _utf8'group') and (zgri.private = 0) and (zgi.private = 0) and (zgi.iscreated = 1) group by zlcs.id) A
                    group by id
                    order by photos_count desc
                {$limit}
                ";

        $data = $db->query($sql);
        return $data->fetchAll();
    }

    /**
     * return count of photos for each city
     */
    public static function getTopCities($limit=30)
    {
        $db = Zend_Registry::get("DB");
        $limit = ($limit) ? "LIMIT 0, ".floor($limit) : "";

        $sql = "select pid as id, pname as name, SUM(pcount) as photos_count from
                (select zlc.id as pid, zlc.name as pname, count(zlc.id) as pcount from zanby_galleries__photos zgp
                inner join zanby_galleries__items zgi on (zgp.gallery_id = zgi.id)
                inner join zanby_users__accounts zua on (zua.id = zgi.owner_id)
                inner join zanby_location__cities zlc on (zua.city_id = zlc.id)
                where (zgi.owner_type = _utf8'user') and (zgi.private = 0) and (zgi.iscreated = 1) group by zlc.id
                union
                select zlc.id as pid, zlc.name as pname, count(zlc.id) as pcount from zanby_galleries__photos zgp
                inner join zanby_galleries__items zgi on (zgp.gallery_id = zgi.id)
                inner join zanby_groups__items zgri on (zgri.id = zgi.owner_id)
                inner join zanby_location__cities zlc on (zgri.city_id = zlc.id)
                where (zgi.owner_type = _utf8'group') and (zgri.private = 0) and (zgi.private = 0) and (zgi.iscreated = 1) group by zlc.id) A
                group by id
                order by photos_count desc
                {$limit}
                ";

        $data = $db->query($sql);
        return $data->fetchAll();
    }

    public function getCountriesPhotosCount()
    {
        $db = Zend_Registry::get("DB");

        $sql = "select pid as id, SUM(pcount) as photos_count from
                (select zlcs.id as pid, count(zlcs.id) as pcount from zanby_galleries__photos zgp
                    inner join zanby_galleries__items zgi on (zgp.gallery_id = zgi.id)
                    inner join zanby_users__accounts zua on (zua.id = zgi.owner_id)
                    inner join zanby_location__cities zlc on (zua.city_id = zlc.id)
                    inner join zanby_location__states zls on (zlc.state_id = zls.id)
                    inner join zanby_location__countries zlcs on (zls.country_id = zlcs.id)
                    where (zgi.owner_type = _utf8'user') and (zgi.private = 0) and (zgi.iscreated = 1) group by zlcs.id
                    union
                    select zlcs.id as pid, count(zlcs.id) as pcount from zanby_galleries__photos zgp
                    inner join zanby_galleries__items zgi on (zgp.gallery_id = zgi.id)
                    inner join zanby_groups__items zgri on (zgri.id = zgi.owner_id)
                    inner join zanby_location__cities zlc on (zgri.city_id = zlc.id)
                    inner join zanby_location__states zls on (zlc.state_id = zls.id)
                    inner join zanby_location__countries zlcs on (zls.country_id = zlcs.id)
                    where (zgi.owner_type = _utf8'group') and (zgri.private = 0) and (zgi.private = 0) and (zgi.iscreated = 1) group by zlcs.id) A
                    group by id";

        $data = $db->query($sql);
        $data = $data->fetchAll();
        $items = array();
        foreach($data as $d) {
            $items[$d['id']] = $d['photos_count'];
        }
        return $items;
    }

    public function getStatesPhotosCount($countryId)
    {
        $db = Zend_Registry::get("DB");

        $sql = "select pid as id, SUM(pcount) as photos_count from
                (select zls.id as pid, count(zls.id) as pcount from zanby_galleries__photos zgp
                    inner join zanby_galleries__items zgi on (zgp.gallery_id = zgi.id)
                    inner join zanby_users__accounts zua on (zua.id = zgi.owner_id)
                    inner join zanby_location__cities zlc on (zua.city_id = zlc.id)
                    inner join zanby_location__states zls on (zlc.state_id = zls.id)
                    where (zgi.owner_type = _utf8'user') and (zls.country_id = $countryId) and (zgi.iscreated = 1) group by zls.id
                    union
                    select zls.id as pid, count(zls.id) as pcount from zanby_galleries__photos zgp
                    inner join zanby_galleries__items zgi on (zgp.gallery_id = zgi.id)
                    inner join zanby_groups__items zgri on (zgri.id = zgi.owner_id)
                    inner join zanby_location__cities zlc on (zgri.city_id = zlc.id)
                    inner join zanby_location__states zls on (zlc.state_id = zls.id)
                    where (zgi.owner_type = _utf8'group') and (zls.country_id = $countryId) and (zgi.iscreated = 1) group by zls.id) A
                    group by id";

        $data = $db->query($sql);
        $data = $data->fetchAll();
        $items = array();
        foreach($data as $d) {
            $items[$d['id']] = $d['photos_count'];
        }
        return $items;
    }

    public function getCitiesPhotosCount($stateId)
    {
        $db = Zend_Registry::get("DB");

        $sql = "select pid as id, SUM(pcount) as photos_count from
                (select zlc.id as pid, count(zlc.id) as pcount from zanby_galleries__photos zgp
                    inner join zanby_galleries__items zgi on (zgp.gallery_id = zgi.id)
                    inner join zanby_users__accounts zua on (zua.id = zgi.owner_id)
                    inner join zanby_location__cities zlc on (zua.city_id = zlc.id)
                    where (zgi.owner_type = _utf8'user') and (zlc.state_id = $stateId) and (zgi.iscreated = 1) group by zlc.id
                    union
                    select zlc.id as pid, count(zlc.id) as pcount from zanby_galleries__photos zgp
                    inner join zanby_galleries__items zgi on (zgp.gallery_id = zgi.id)
                    inner join zanby_groups__items zgri on (zgri.id = zgi.owner_id)
                    inner join zanby_location__cities zlc on (zgri.city_id = zlc.id)
                    where (zgi.owner_type = _utf8'group') and (zlc.state_id = $stateId) and (zgi.iscreated = 1) group by zlc.id) A
                    group by id";

        $data = $db->query($sql);
        $data = $data->fetchAll();
        $items = array();
        foreach($data as $d) {
            $items[$d['id']] = $d['photos_count'];
        }
        return $items;
    }

    public static function getLatestPhotos($limit = 30)
    {
        $db = Zend_Registry::get("DB");
  //      $limit = ($limit) ? "LIMIT 0, ".floor($limit) : "";

        $sql = $db->select()
                ->from(array('zgp' => 'zanby_galleries__photos'), 'zgp.id')
                ->join(array('zgi' => 'zanby_galleries__items'), 'zgp.gallery_id = zgi.id')
                ->where('zgi.private = ?', 0)
                ->where('zgi.iscreated = ?', 1)
                ->order('zgp.creation_date desc')
                ->limit($limit);
/*        $sql = "select id from zanby_galleries__photos
                order by creation_date desc
                {$limit}
                ";
*/
        //$data = $db->query($sql);
        $data = $db->fetchCol($sql);
        $items = array();
        if (!empty($data)) {
            foreach($data as $item) {
                $items[] = Warecorp_Photo_Factory::loadById($item);
            }
        }
        return $items;
    }

    public static function getLatestPhotosImproved($limit = 30)
    {
        $db = Zend_Registry::get("DB");


        $sql = 'select photos.photo_id from
                (
                    select zgp.id as photo_id, zgp.creator_id, zgi.id as gallery_id, zgi.update_date from
                    (
                        select * from zanby_galleries__items
                        where (private = 0) and (iscreated = 1)
                        order by update_date desc
                    ) zgi
                    inner join
                    (
                        select * from zanby_galleries__photos order by RAND()
                    ) zgp
                    on (zgi.id = zgp.gallery_id)
                    group by zgi.id order by zgi.update_date desc
                ) photos group by photos.creator_id order by photos.update_date desc
                limit 0, '.$limit;

        $photos = $db->fetchCol($sql);

        /*        $users = array();
        $photos = array();
        $sqlgallery = $db->select()
                ->from(array('zgi' => 'zanby_galleries__items'), 'zgi.id')
                ->where('zgi.private = ?', 0)
                ->where('zgi.iscreated = ?', 1)
                ->order('zgi.update_date desc');
        while (($gallery = $db->query($sqlgallery)->fetch()) && count($photos) < $limit) {
            $sqlphoto = $db->select()
                    ->from(array('zgp' => 'zanby_galleries__photos'), array('zgp.id', 'zgp.creator_id'))
                    ->where('zgp.gallery_id = ?', $gallery['id'])
                    ->where('creator_id not in(?)', empty($users)?array('0'):$users)
                    ->order('RAND()')
                    ->limit(1);
            $photo = $db->query($sqlphoto)->fetch();
            if (!empty($photo)) {
                if (!in_array($photo['creator_id'], $users)) {
                    $photos[] = $photo['id'];
                    $users[] = $photo['creator_id'];
                }
            }
        } */

        $items = array();
        if (!empty($photos)) {
            foreach($photos as $item) {
                $items[] = Warecorp_Photo_Factory::loadById($item);
            }
        }
        return $items;
    }

    public function searchByKeywords()
    {
        // unused variable removed by Pianko
       // $_photo = new Warecorp_Photo_Standard();

        if (is_array($this->keywords) && count($this->keywords)) {
            if (WITH_SPHINX){
                $query = "";
                // create object Warecorp_Data_Search
                $cl = new Warecorp_Data_Search();

                // initialization
                $cl->init('photo');

                $cl->Query( implode(' ', $this->keywords ) );

                $cl->SetFilter ( 'gallery_private', array( 0 ) );

                if (EI_FILTER_ENABLED){
                    $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) ));
                }

                $this->resByKeywords = $cl->getResultPairs();
                $this->dontSort = true;
                unset($cl);
            }
            else{
    /*            $sql = $this->_db->select()
                                 ->from(array('ztr' => 'zanby_tags__relations'), array("ztr.entity_id", 'w' => new Zend_Db_Expr("SUM(ztr.weight_user)")))
                                 ->join(array('ztd' => 'zanby_tags__dictionary'), 'ztd.id = ztr.tag_id')
                                 ->group(array('ztr.entity_type_id', 'ztr.entity_id'))
                                 ->where('ztr.entity_type_id = ?', $_photo->EntityTypeId)
                                 ->where('ztd.name IN (?)', $this->keywords)
                                 ->order('w DESC');*/
    /*            $sql = $this->_db->select()
                                 ->from(array('ztr' => 'zanby_tags__relations'), array(new Zend_Db_Expr('distinct ztr.entity_id'), 'ztr.entity_id'))
                                 ->join(array('ztd' => 'zanby_tags__dictionary'), 'ztd.id = ztr.tag_id')
                                 ->group(array('ztr.entity_type_id', 'ztr.entity_id'))
                                 ->where('ztr.entity_type_id = ?', $_photo->EntityTypeId)
                                 ->where('(2=1');*/
                $sql = $this->_db->select()
                                 ->from(array('vptu' => 'view_photos__tags_used'), array(new Zend_Db_Expr('distinct vptu.photo_id'), 'vptu.photo_id'))
                                 ->where('(2=1');

                foreach($this->keywords	as $keyword) {
                    $sql = $sql->orWhere('vptu.tag_name like ?', $keyword);
                }
                $sql = $sql->orWhere('2=1)');
                $keysearch = $this->_db->fetchPairs($sql);
                $keys = array_keys($keysearch);
                $sql = $this->_db->select()
                            ->from(array('zgli' => 'zanby_galleries__items'), array())
                            ->join(array('zgp' => 'zanby_galleries__photos'), 'zgp.gallery_id = zgli.id', array('zgp.id', 'zgp.id'))
                            ->join(array('zua' => 'zanby_users__accounts'), 'zua.id = zgli.owner_id and zgli.owner_type = "user"', array())
                            ->where('zgli.private = ?', 0)
                            ->where('zgli.iscreated = ?', 1)
                            ->where('zgp.id in (?)', empty($keys)?false:$keys);
                $res1 = $this->_db->fetchPairs($sql);
                $sql = $this->_db->select()
                            ->from(array('zgli' => 'zanby_galleries__items'), array())
                            ->join(array('zgp' => 'zanby_galleries__photos'), 'zgp.gallery_id = zgli.id', array('zgp.id', 'zgp.id'))
                            ->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgli.owner_id and zgli.owner_type = "group"', array())
                            ->where('zgli.private = ?', 0)
                            ->where('zgli.iscreated = ?', 1)
                            ->where('zgi.private = ?', 0)
                            ->where('zgp.id in (?)', empty($keys)?false:$keys);
               $res2 = $this->_db->fetchPairs($sql);
               $res = $res1 + $res2;
               $this->resByKeywords = array_intersect_key($keysearch, $res);
               $this->dontSort = true;
            }
        } else {
            $this->resByKeywords = null;
        }
        $this->res = $this->resByKeywords;
        return $this->resByKeywords;
    }

    public function setWhoUploadedParams($params)
    {
        $this->whoUploadedParams = $params;
        return $this;
    }

    public function getWhoUploadedParams()
    {
        return $this->whoUploadedParams;
    }

    private function getWhoUploadedData()
    {
        switch (Warecorp_Photo_Search::$whoUploadedList['titles'][$this->whoUploaded]) {
            case 'Friends':
                $user = $this->whoUploadedParams['user'];
                $whoUploadedData['types'] = 'user';
                $whoUploadedData['ids'] = $user->getFriendsList()->returnAsAssoc()->getList();
                break;
            case 'My Groups':
                $user = $this->whoUploadedParams['user'];
                $whoUploadedData['types'] = 'group';
                $whoUploadedData['ids'] = array_keys($user->getGroups()->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)->returnAsAssoc()->getList());
                break;
            case 'My Group Families':
                $user = $this->whoUploadedParams['user'];
                $whoUploadedData['types'] = 'group';
                $whoUploadedData['ids'] = array_keys($user->getGroups()->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY)->returnAsAssoc()->getList());
                break;
        }
        return $whoUploadedData;
    }

    public function searchByWhoUploaded()
    {
        if ($this->whoUploaded != 1)
            $whoUploadedData = $this->getWhoUploadedData();

        if ((!empty($whoUploadedData['ids']) && !empty($whoUploadedData['types'])) || ($this->whoUploaded == 1)) {
            $sql = $this->_db->select()
                        ->from(array('vgl' => 'view_gallery__list'), array())
                        ->join(array('zgp' => 'zanby_galleries__photos'), 'zgp.gallery_id = vgl.id', array('zgp.id', 'zgp.id'))
                        ->where('vgl.private = ?', 0);
            if ($this->whoUploaded != 1) {
                $sql = $sql->where('vgl.owner_type in (?)', $whoUploadedData['types'])
                           ->where('vgl.owner_id in (?)', $whoUploadedData['ids']);
            }
            $this->resByWhoUploaded = $this->_db->fetchPairs($sql);
        } else {
            $this->resByWhoUploaded = null;
        }
        $this->res = $this->resByWhoUploaded;
        return $this->resByWhoUploaded;
    }

    function setKeywords($input, $forLike = false)
    {
        parent::setKeywords($input, ".");
        if ($forLike) {
            if (is_array($this->keywords) && count($this->keywords)) {
                foreach($this->keywords as &$keyword) {
                    $keyword = "%$keyword%";
                }
            }
        }
    }

    public function setWhoUploaded($whoUploaded)
    {
        $this->whoUploaded = $whoUploaded;
        return $this;
    }

    public function setCountry($countryId)
    {
        $this->country = $countryId;
        return $this;
    }

    public function searchByCountry()
    {
        if (!empty($this->country)) {
            $sql = $this->_db->select()
                ->from(array('zgli' => 'zanby_galleries__items'), array())
                ->join(array('zgp' => 'zanby_galleries__photos'), 'zgp.gallery_id = zgli.id', array('zgp.id', 'zgp.id'))
                ->join(array('zua' => 'zanby_users__accounts'), 'zua.id = zgli.owner_id and zgli.owner_type = "user"', array())
                ->join(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id', array())
                ->join(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id', array())
                ->join(array('zlcs' => 'zanby_location__countries'), 'zls.country_id = zlcs.id', array())
                ->where('zgli.private = ?', 0)
                ->where('zgli.iscreated = ?', 1)
                ->where('zlcs.id = ?', $this->country);
            $res1 = $this->_db->fetchPairs($sql);
            $sql = $this->_db->select()
                ->from(array('zgli' => 'zanby_galleries__items'), array())
                ->join(array('zgp' => 'zanby_galleries__photos'), 'zgp.gallery_id = zgli.id', array('zgp.id', 'zgp.id'))
                ->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgli.owner_id and zgli.owner_type = "group"', array())
                ->join(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id', array())
                ->join(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id', array())
                ->join(array('zlcs' => 'zanby_location__countries'), 'zls.country_id = zlcs.id', array())
                ->where('zgli.private = ?', 0)
                ->where('zgli.iscreated = ?', 1)
                ->where('zgi.private = ?', 0)
                ->where('zlcs.id = ?', $this->country);
            $res2 = $this->_db->fetchPairs($sql);
            $this->resByCountry = $res1 + $res2;
        } else {
            $this->resByCountry = null;
        }
        $this->res = $this->resByCountry;
        return $this->resByCountry;
    }

    public function setCity($cityId)
    {
        $this->city = $cityId;
        return $this;
    }

    public function searchByCity()
    {
        if (!empty($this->city)) {
            $sql = $this->_db->select()
                ->from(array('zgli' => 'zanby_galleries__items'), array())
                ->join(array('zgp' => 'zanby_galleries__photos'), 'zgp.gallery_id = zgli.id', array('zgp.id', 'zgp.id'))
                ->join(array('zua' => 'zanby_users__accounts'), 'zua.id = zgli.owner_id and zgli.owner_type = "user"', array())
                ->join(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id', array())
                ->where('zgli.iscreated = ?', 1)
                ->where('zgli.private = ?', 0)
                ->where('zlc.id = ?', $this->city);
            $res1 = $this->_db->fetchPairs($sql);
            $sql = $this->_db->select()
                ->from(array('zgli' => 'zanby_galleries__items'), array())
                ->join(array('zgp' => 'zanby_galleries__photos'), 'zgp.gallery_id = zgli.id', array('zgp.id', 'zgp.id'))
                ->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgli.owner_id and zgli.owner_type = "group"', array())
                ->join(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id', array())
                ->where('zgli.private = ?', 0)
                ->where('zgli.iscreated = ?', 1)
                ->where('zgi.private = ?', 0)
                ->where('zlc.id = ?', $this->city);
            $res2 = $this->_db->fetchPairs($sql);
            $this->resByCity = $res1 + $res2;
        } else {
            $this->resByCity = null;
        }
        $this->res = $this->resByCity;
        return $this->resByCity;
    }

    public function searchByCriterions( $params )
    {
        if ( WITH_SPHINX ) {
            // create object Warecorp_Data_Search
            $cl = new Warecorp_Data_Search();
            // initialization
            $cl->init('photo');
            $query = "";

            // set include and exclude filters if it's necessary
            if ( $this->getIncludeIds() ) $cl->SetIDFilter ( $this->getIncludeIds() );
            if ( $this->getExcludeIds() ) $cl->SetIDFilter ( $this->getExcludeIds(), true );

            if (EI_FILTER_ENABLED) {
                $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) ));
            }

            $this->setLocationFilter($cl, $params);
            $this->setBlockedUserFilter($cl);

            if (!empty($this->keywords)) {
                $query = implode(" ", $this->keywords);
            }

            // send search query
            $cl->Query( $query );

            $this->resByCriterions = $cl->getResultPairs();
            unset($cl);
            return $this->resByCriterions;
        }
        else {
            return null;
        }
    }

    public function getIntersection()
    {
        $this->resOfIntersection = array();
        if (!is_null($this->resByKeywords) && !is_null($this->resByWhoUploaded)) {
            foreach ($this->resByKeywords as $key=>$w) {
                if (isset($this->resByWhoUploaded[$key])) $this->resOfIntersection[$key] = $w;
            }
        } else { // empty keywords
            $this->resOfIntersection = $this->resByWhoUploaded;
        }
        $this->res = $this->resOfIntersection;
    }

    public function setOrder($sort)
    {
        $this->order = $sort;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setCurrentPage($page)
    {
        if (is_numeric($page))
            $this->page = ($page > 0)?$page:1;
        else $this->page = 1;
        return $this;
    }

    public function getCurrentPage()
    {
        return $this->page;
    }

    public function setListSize($size)
    {
        $this->size = $size;
        return $this;
    }

    public function getListSize()
    {
        return $this->size;
    }

    public function getSearchResult()
    {

        if (!is_null($this->res) && !empty($this->res)) {
            $keys = array_keys($this->res);
            $items = array();
            if (WITH_SPHINX){
                $query = "";
                // create object Warecorp_Data_Search
                $cl = new Warecorp_Data_Search();

                // initialization
                $cl->init('photo');

                if ( !empty($this->keywords) ){
                    $query = "*".implode('* *', $this->keywords )."*";
                    $query = str_replace('%','',$query);
                    //for search without wildcard
                    //$query = implode(' ', $this->keywords );
                }

                if (!empty($keys)){
                    //$cl->SetFilter ( "id", $this->getIncludeIds() );
                    $cl->SetFilter( "photo_id", $keys );
                }

                if (EI_FILTER_ENABLED){
                    $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) ));
                }

                $order = "";
                if (Warecorp_Photo_Search::$sortList['indexer'][$this->order] != 'no sort') {
                    $order .= Warecorp_Photo_Search::$sortList['indexer'][$this->order].', ';
                }

                if ( !empty($this->keywords) ){
                    $order .= '@weight asc';
                }

                if ($order != ""){
                    $cl->SetSort( $order );
                }

                $cl->Query( $query );

                $this->res = $cl->getResultPairs();
               // $this->res = $data;
               //$cl->SetLimit ( ($this->getCurrentPage() - 1) * $this->getListSize(), $this->getListSize());
               $data = array_slice ( $this->res, ($this->getCurrentPage() - 1) * $this->getListSize(), $this->getListSize() );

               // $data = $cl->getResultPairs();

                foreach ($data as $elem) {
                    $items[] = Warecorp_Photo_Factory::loadById($elem);
                }
                 unset($cl);
            }
            else{
                $_photo = new Warecorp_Photo_Standard();
                $weight = 'select SUM(ztr.weight_user) from zanby_tags__relations ztr
                            inner join zanby_tags__dictionary ztd on (ztd.id = ztr.tag_id)
                            where ('.$this->_db->quoteInto('ztr.entity_type_id = ?', $_photo->EntityTypeId).')
                            and (ztr.entity_id = zgp.id)';
                if (is_array($this->keywords) && count($this->keywords)) {
                    $weight .= ' and (2=1 or ';
                    foreach($this->keywords as $keyword) {
                        $weight .= $this->_db->quoteInto('ztd.name like ? or ', $keyword);
                    }
                    $weight .= '2=1)';
                }

                $sql = 'select zgp.id as id, ('.$weight.') as w
                        from zanby_galleries__photos zgp
                        where id in ('.$this->_db->quoteInto('?', empty($keys)?false:$keys).')
                        order by ';
                if (Warecorp_Photo_Search::$sortList['sql'][$this->order] != 'no sort') {
                    $sql .= Warecorp_Photo_Search::$sortList['sql'][$this->order].', ';
                }
                $sql .= 'w asc
                         limit '.(($this->getCurrentPage() - 1) * $this->getListSize()).', '.$this->getListSize();

                $data = $this->_db->query($sql);
                $data = $data->fetchAll();

    /*    		$sql = $this->_db->select()
                    ->from(array('zgp' => 'zanby_galleries__photos'), 'zgp.id')
                    ->where('id in (?)', empty($keys)?false:$keys);
                if (Warecorp_Photo_Search::$sortList['sql'][$this->order] != 'no sort') {
                    $sql = $sql->order(Warecorp_Photo_Search::$sortList['sql'][$this->order]);
                }
                $sql = $sql->limitPage($this->getCurrentPage(), $this->getListSize());
                $data = $this->_db->fetchCol($sql);*/
                foreach ($data as $elem) {
                    $items[] = Warecorp_Photo_Factory::loadById($elem['id']);
                }
            }
            return $items;
        }
        return array();
    }

    public function getCount()
    {
        if (!is_null($this->res)) {
            $keys = array_keys($this->res);
            $sql = $this->_db->select()
                ->from(array('zgp' => 'zanby_galleries__photos'), new Zend_Db_Expr('count(zgp.id)'))
                ->where('id in (?)', empty($keys)?false:$keys);
            $data = $this->_db->fetchOne($sql);
            return $data;
        }
        else return 0;
    }
}
