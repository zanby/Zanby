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

class BaseWarecorp_List_Tags extends Warecorp_Abstract_List
{
    protected $_sphFilters;
    protected $_sphFiltersEx;
    protected $_tagStatus;

    protected $_ownerId = null;
    protected $_ownerTypeId = null;

    /**
    * set search filters using Sphinx if necessary
    * @param param name
    * @param mixed value to search
    * @return Warecorp_List_Tags
    * @author Konstantin Stepanov
    */
    public function addFilter($param, $value, $exclude = false)
    {
        if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
        {
            if ($value === null || count($value) == 0) $value = array( 0 );
            if (!is_array($value)) $value = array( $value );
            if ($exclude)
                $this->_sphFiltersEx[$param] = $value;
            else
                $this->_sphFilters[$param]   = $value;
        } else
        {
            $this->addWhere("$param ".($exclude? "<>": "=")." ?", $value);
        }
        return $this;
    }

    public function clearFilters()
    {
        if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
        {
            $this->_sphFilters   = null;
            $this->_sphFiltersEx = null;
        } else
        {
            parent::clearWhere();
        }
    }

    public function resetList()
    {
        $this->_sphFilters  = null;
        $this->_tagStatus   = null;
        $this->_ownerId     = null;
        $this->_ownerTypeId = null;
        $this->clearFilters();
        return parent::resetList();
    }

    /**
    * set owner type & id, can accept Warecorp_User or Warecorp_Group_Base class instance
    * @param mixed owner to set (either an object instance or integer)
    * @return Warecorp_List_Tags
    * @author Konstantin Stepanov
    */
    public function setOwner($owner)
    {
        if ($owner instanceof Warecorp_User || $owner instanceof Warecorp_Group_Base)
        {
            $this->_ownerTypeId = ($owner instanceof Warecorp_User)? 1: 2;
            $this->_ownerId = $owner->getId();
        } elseif (is_int($owner)) {
            $this->_ownerId = $owner;
        }
        return $this;
    }

    /**
    * set owner id
    * @param new owner id
    * @return Warecorp_List_Tags
    * @author Konstantin Stepanov
    */
    public function setOwnerId($ownerId)
    {
        $this->_ownerId = $ownerId;
        return $this;
    }

    /**
    * set owner type id (1 for user, 2 for group)
    * @param new owner id
    * @return Warecorp_List_Tags
    * @author Konstantin Stepanov
    */
    public function setOwnerTypeId($ownerTypeId)
    {
        if (is_string($ownerTypeId))
            $ownerTypeId = $ownerTypeId == 'user'? 1: 2;
        if ($ownerTypeId != 1 && $ownerTypeId != 2)
            throw new Warecorp_Exception("incorrect owner type: must be 'user' or 'group'");
        $this->_ownerTypeId = $ownerTypeId;
        return $this;
    }

    public function getOwnerId()
    {
        return $this->_ownerId;
    }

    public function getOwnerTypeId()
    {
        return $this->_ownerTypeId;
    }

    /**
    * set tag status
    * @param array $newValue
    * @return Warecorp_Group_Tag_List
    * @author Artem Sukharev
    * @todo systems tags should be removed someday
    */
    public function setTagStatus($newValue)
    {
        if ( is_array($newValue) ) {
            $this->_tagStatus = $newValue;
        } else {
            if ( in_array($newValue, array('user', 'system')) ) {
               $this->_tagStatus = array($newValue);
            }
        }
        return $this;
    }

    /**
    * get tag status
    * @return array
    * @author Artem Sukharev
    * @todo systems tags should be removed someday
    */
    public function getTagStatus()
    {
        if ( $this->_tagStatus === null ) return array('user');
        return $this->_tagStatus;
    }

    /**
    * get tag status as int
    * @return array
    * @author Artem Sukharev, Konstantin Stepanov
    * @todo systems tags should be removed someday
    */
    public function getIntTagStatus()
    {
        if ( $this->_tagStatus === null ) return array( 2 );
        return array_map(create_function('$a', 'return $a == "system"? 1: 2;'), $this->_tagStatus);
    }

    /**
    * set sort order with sphinx support
    * @param order by clause
    * @return void
    * @author Konstantin Stepanov
    */
    public function setOrder($orderby)
    {
        if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
            $orderby = str_replace("rating", "@count", $orderby);
        return parent::setOrder($orderby);
    }

    /**
    * construct Sphinx search object for tags search
    * @return Warecorp_Data_Search instance
    * @author Konstantin Stepanov
    * @todo systems tags should be removed someday
    */
    protected function buildSearchObject($isarray = false)
    {
        $cl = new Warecorp_Data_Search();
        $cl->init("tags");
        $cl->SetArrayResult($isarray);
        $cl->SetFilter("status", $this->getIntTagStatus());

        if ($this->_ownerTypeId !== null)
            $cl->SetFilter("owner_type", array( $this->_ownerTypeId ));
        if ($this->_ownerId !== null)
            $cl->SetFilter("owner_id", array( $this->_ownerId ));


        if ($this->getIncludeIds())
            $cl->SetFilter("owner_id", $this->getIncludeIds());
        if ($this->getExcludeIds())
            $cl->SetFilter("owner_id", $this->getExcludeIds(), true);
        if (EI_FILTER_ENABLED){
            $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) ));
        }

        if ($this->_sphFilters !== null && count($this->_sphFilters))
            foreach ($this->_sphFilters as $param => $value){
                $cl->SetFilter($param, $value);
               // print_r($value); echo "<br/>";
            }

        if ($this->_sphFiltersEx !== null && count($this->_sphFiltersEx))
            foreach ($this->_sphFiltersEx as $param => $value){
                $cl->SetFilter($param, $value, true);
            }

        $cl->SetGroupBy("tag_id", SPH_GROUPBY_ATTR, $this->getOrder() == null? "@group desc": $this->getOrder());
        if ($this->getCurrentPage() !== null && $this->getListSize() !== null)
            $cl->setLimit(($this->getCurrentPage() - 1) * $this->getListSize(), $this->getListSize());

        return $cl;
    }

    /**
    * normalize array values
    * @param tags by ref
    * @return void
    * @author Konstantin Stepanov, Alexander Komarovski
    */
    public static function normalizeArray(&$tags)
    {
        /*$max = count($array) ? max($array) : 0;
        if ($max != 0) $coof = 100/$max; else $coof = 0;
        foreach ($array as &$item) $item = $coof*$item
         */

        /* @author Alexander Komarovski */
        if (empty($tags)) return false;

        $_weightClassesCount = 5; //number of classes - might be moved to config
        $_weightClassesCnt = $_weightClassesCount;
        $_delta = $_weightClassesCnt / count($tags);
        if ($_delta>1) {
            $_delta = 1;
        }
        foreach ($tags as &$v) {
            $_weightClassesCnt = $_weightClassesCnt - $_delta;
            $v = ceil($_weightClassesCount - $_weightClassesCnt);
        }

        return true;
    }
    /**
    * shuffle array values
    * @param tags by ref
    * @return void
    * @author Alexander Komarovski
    */
    public static function shuffleTagsArray(&$tags)
    {
        $shuffledTags = array();
        while (is_array($tags) && count($tags) > 0) {
            $val = array_rand($tags);
            $shuffledTags[$val] = $tags[$val];
            unset($tags[$val]);
        }

        return $shuffledTags;
    }

    /**
    * get tag names from db using Sphinx search results
    * @param array with raw Sphinx search results
    * @return assoc array
    * @author Konstantin Stepanov
    * @author Dmitry Kamenka
    */
    protected function getTagNamesBySphinxResults($matches)
    {
        if (!count($matches)) return $matches;

        $ids = array();
        foreach ($matches as &$match) {
            $ids[] = $match['attrs']['tag_id'];
        }

        $query = $this->_db->select()
                    ->from("zanby_tags__dictionary", array('id', "name"))
                    ->where('id IN (?)', $ids);

        $tags = $this->_db->fetchPairs($query);

        foreach ( $matches as $id => &$match ) {
            if ( array_key_exists($match['attrs']['tag_id'], $tags) && !empty($tags[$match['attrs']['tag_id']]) ) {
                $match['attrs']['name'] = $tags[$match['attrs']['tag_id']];
            } else unset($matches[$id]);
        }

        return $matches;
    }

    /**
    * query Sphinx for tags and return matches
    * @param query string for Sphinx
    * @return array with matches
    * @author Konstantin Stepanov
    */
    protected function querySphinx($query = "")
    {
        $cl = $this->buildSearchObject();
        $cl->Query($query);
        $tags = $cl->getResultSphinx();
        unset($cl);
        if (array_key_exists('matches', $tags) && count($tags['matches']))
            return $tags['matches'];
        else
            return array();
    }

    /**
    * get list of tags
    * @return array of Warecorp_Data_Tag
    * @author Konstantin Stepanov
    */
    public function getList($defaultKey = "name", $defaultValue = "@count")
    {
        if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
        {
            $tags = $this->querySphinx();
            $result = array();
            if ($tags)
            {
                if ($this->isAsAssoc())
                {
                    $askey = $this->getAssocKey()? $this->getAssocKey(): (string)$defaultKey;
                    $asval = $this->getAssocValue()? $this->getAssocValue(): (string)$defaultValue;

                    if ($askey == "name" || $asval == "name")
                        $tags = $this->getTagNamesBySphinxResults($tags);

                    foreach ($tags as $tag)
                    {
                        $tag['attrs']['count'] = $tag['attrs']['@count'];
                        $result[$tag['attrs'][$askey]] = $tag['attrs'][$asval];
                    }

                    if ( $asval == "@count" && $this->getAssocKey() === null && $this->getAssocValue() === null ) {
                        self::normalizeArray($result);
                    }
                } else {
                    $tags = $this->getTagNamesBySphinxResults($tags);
                    foreach ($tags as $tag)
                    {
                        //$result[] = new Warecorp_Data_Tag($tag['attrs']['tag_id']);
                        $tag['attrs']['count'] = $tag['attrs']['@count'];
                        $result[] = $tag['attrs'];
                    }
                }
            }
            return $result;
        } else {
            throw new Warecorp_Exception("getting list without Sphinx is not supported");
        }
    }

    /**
    * the same as getList() but returns an array of objects
    * @return array of Warecorp_Data_Tag
    * @author Konstantin Stepanov
    */
    public function getObjList()
    {
        if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
        {
            $tags = $this->querySphinx();
            foreach ($tags as &$tag)
            {
                $tag = new Warecorp_Data_Tag($tag['attrs']['tag_id']);
            }
            return $tags;
        } else
        {
            throw new Warecorp_Exception("getting list without Sphinx is not supported");
        }
    }


    /**
    * get number of tags
    * @return number of found tags
    * @author Konstantin Stepanov
    */
    public function getCount()
    {
        if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
        {
            $cl = $this->buildSearchObject();
            $cl->Query();
            $tags = $cl->getResultSphinx();
            unset($cl);
            return $tags['total_found'];
        } else
        {
            throw new Warecorp_Exception("getting list count without Sphinx is not supported");
        }
    }

    /**
    * fetches data from DBMS using SQL query & post-processes it
    * @param SQL query string
    * @param bool, if true this method will normalize data if necessary
    * @return post processed array
    * @author Konstantin Stepanov
    */
    protected function getTagListFromSQL($sql, $normalize = true)
    {
        //echo $sql;exit;
        $items = array();
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($sql);
            if ( $normalize && $this->getAssocKey() === null && $this->getAssocValue() === null ) {
                self::normalizeArray($items);
            }
        } else {
            $items = $this->_db->fetchCol($sql);
            foreach ( $items as &$item ) $item = new Warecorp_Data_Tag($item);
        }
        return $items;
    }


}
