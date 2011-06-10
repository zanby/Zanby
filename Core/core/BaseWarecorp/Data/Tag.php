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
   * Warecorp Framework
   * @package Warecorp_Data
   * @author Dmitry Kostikov
   */
class BaseWarecorp_Data_Tag extends Warecorp_Data_Entity
{
    public $id;
    public $name;
    public $rating;

    /**
     * constructor
     * @param int $id - id of tag
     */
    public function __construct($tagId = false)
    {
        parent::__construct('zanby_tags__dictionary', 
			    array(
				'id'   => 'id',
				'name' => 'name'
				)
	    );
        $this->load($tagId);
    }

    /**
     * check if tag exist whith key and value
     * @param string $key - key for search id | name
     * @param string $value - value for search
     * @return int | false
     * @author Artem Sukharev
     */
    public static function isTagExists($key, $value)
    {
        $db = Zend_Registry::get("DB");
        $query = $db->select();
        $query->from('zanby_tags__dictionary', 'id');
	//$query->where($db->quoteInto($key." = ?", $value));
	$query->where($key." = ?", Warecorp_Data_Tag::normalizeValue( $value));
        $id = $db->fetchOne($query);
        return ( $id ) ? $id : false;
    }
    
    /**
     * add new relation for entity and tag
     * @param int $entity_id
     * @param int $entity_type_id
     * @param int $weight_group default 0
     * @param int $weight_user default 0
     * @param string $status - tag relation status
     * @author Artem Sukharev
     */
    public function insertRelation($entity_id, $entity_type_id, $weight_group = 0, $weight_user = 0, $status = 'user')
    {
        if ( $tag_relation_id = Warecorp_Data_TagRelation::isRelationExists($this->id, $entity_id, $entity_type_id, $status) ) {
            $Rel = new Warecorp_Data_TagRelation($tag_relation_id);
        } else {
            $Rel = new Warecorp_Data_TagRelation();
        }
        $Rel->tagId         = $this->id;
        $Rel->entityId      = $entity_id;
        $Rel->entityTypeId  = $entity_type_id;
        $Rel->weightGroup   = $weight_group;
        $Rel->weightUser    = $weight_user;
        $Rel->status        = $status;
        $Rel->save();
    }
    
    /**
     * get list of entites for current tag as array
     * @return array
     * @author Artem Sukharev
     */
    public function getTagEntities()
    {
        $query = $this->_db->select();
        $query->from('zanby_tags__relations', '*')
	    ->where('tag_id = ?', $this->id);
        $entities = $this->_db->fetchAll($sql);
        foreach ($entities as $entity){}
        return $entities;
    }
 
    /**
     * get list of entites for current tag as objects
     * @return array
     * @author Artem Sukharev
     * @see Warecorp_Data_Entity->__construct() там тоже эти типы и идешки
     */
    public function getTagEntitiesAsObj($tag_type = 'user')
    {
        if ( $tag_type == 'user' )          $tag_type = array('user');
        elseif ( $tag_type == 'system' )    $tag_type = array('system');
        elseif ( $tag_type == 'both' )      $tag_type = array('user', 'system');
        else $tag_type = array('user');

        $query = $this->_db->select();
        $query->from('zanby_tags__relations', '*');
        $query->where('tag_id = ?', $this->id);
        $query->where('status IN (?)', $tag_type);
        $entities = $this->_db->fetchAll($query);
        foreach ($entities as &$entity){
            switch ( $entity['entity_type_id'] ) {
	            case 1    :   $entity = new Warecorp_User('id', $entity['entity_id']);                  break;
	            case 2    :   $entity = Warecorp_Group_Factory::loadById($entity['entity_id'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);break;
	            case 3    :   $entity = new Warecorp_Photo_Gallery($entity['entity_id']);               break;
	            case 4    :   $entity = new Warecorp_Photo_Item($entity['entity_id']);                  break;
	            case 5    :   $entity = new Warecorp_Document_Item($entity['entity_id']);               break;
	            case 6    :   $entity = new Warecorp_ICal_Event($entity['entity_id']);                  break;
	            case 9    :   $entity = new Warecorp_Data_Comment($entity['entity_id']);                break;
	            case 17   :   $entity = new Warecorp_Data_Tag($entity['entity_id']);                    break;
	            case 15   :   $entity = new Warecorp_Mail_Template($entity['entity_id']);               break;
	            case 20   :   $entity = new Warecorp_List_Item($entity['entity_id']);                   break;
	            case 21   :   $entity = new Warecorp_List_Record($entity['entity_id']);                 break;
	            default   :   throw new Zend_Exception('Incorrect Tag Entity Type');
            }
        }
        return $entities;
    }

    /**
     * return count of current tag ralations
     * @return integer
     * @author Artem Sukharev
     */
    public function count()
    {
        $query = $this->_db->select();
        $query->from('zanby_tags__relations', new Zend_Db_Expr('COUNT(id)'));
        $query->where('tag_id = ?', $this->id);
        return $this->_db->fetchOne($sql);
    }

    /**
     * Возвращает теги для определенного entity
     * @param unknown_type $entity_id
     * @param unknown_type $entity_type_id
     * @return array of Warecorp_Data_Tag
     * @author Artem Sukharev
     */
    public static function getTagsByEntity($entity_id, $entity_type_id)
    {
        $tags = array();
        $rels = Warecorp_Data_TagRelation::getRelationsByEntity($entity_id, $entity_type_id);
        if ( sizeof($rels) != 0 ) {
            foreach ($rels as &$rel) {
                $tags[] =& $rel->getTag();
            }
        }
        return $tags;
    }


    /**
     * Returns prepared tags for requested entity
     * @param integer $entity_id
     * @param integer $entity_type_id
     * @return array of Warecorp_Data_Tag
     * @author Alexander Komarovski
     */
    public static function getPreparedTagsNamesByEntity($entity_id, $entity_type_id)
    {
        $tags = array();
        $rels = Warecorp_Data_TagRelation::getRelationsByEntity($entity_id, $entity_type_id);
        if ( sizeof($rels) != 0 ) {
            foreach ($rels as &$rel) {
                $tags[] =& $rel->getTag()->getPreparedTagName();
            }
        }
        return $tags;
    }

    /**
     * Подготавливает тег для использования в элементах формы
     * Теги, состоящие из нескольких слов при этом заключаются в " "
     * @return void
     * @author Artem Sukharev
     */
    public function getPreparedTagName()
    {
        if ( preg_match('/\s/', $this->name, $match) ) {
            return '"'.$this->name.'"';
        } else {
            return $this->name;
        }
    }

    /**
     * return tag object as string
     * @return string
     */
    public function toString()
    {
        return "";
    }

    /**
     * return hash of name-rating% for array of groups
     *
     * @return hash tagname=>rating
     * @author Halauniou
     */

    static function getTopTagsPrepared($limit = 30){

        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('view_tags__dictionary',array('name', 'rating'));

        $select->limit(floor($limit));

        $rated_tags = $db->fetchPairs($select);

        if (current($rated_tags) != 0) $coof = 100/current($rated_tags); else $coof = 0;
        foreach($rated_tags as &$tag){
            $tag = $coof*$tag;
        }
        return $rated_tags;
    }

    /**
     * save tag object
     * @return boolean
     */
    public function save()
    {
        if (isset($this->id)) {
            // изменяем существующую запись
            $result   = $this->_db->update('zanby_tags__dictionary', array('name' => Warecorp_Data_Tag::normalizeValue($this->name)), $this->_db->quoteInto('id=?', $this->id));
        } else {
            // вставляем новую запись, возвращаем получившийся id
            $result   = $this->_db->insert('zanby_tags__dictionary', array('name' => Warecorp_Data_Tag::normalizeValue($this->name)));
            $this->id = $this->_db->lastInsertId();
        }
        return true;
    }
    
    public static function getTagsByNamesAsAssoc($tagsNames)
    {
        if (!empty($tagsNames)) {
            $tagsNames = Warecorp_Data_Tag::normalizeValue( $tagsNames);
            $db = Zend_Registry::get('DB');
            $select = $db->select();
            $select->from(array('ztd' => 'zanby_tags__dictionary'), array('ztd.id', 'ztd.name'))
                   ->where('ztd.name in (?)', $tagsNames);
            $rels = $db->fetchPairs($select);
            return ($rels)?$rels:array();    
        } else return array();    
    }
    
    public static function insertTags($tagsNames)
    {
        if (empty($tagsNames)) return false;
        $db = Zend_Registry::get('DB');
        $query = "insert into zanby_tags__dictionary (name) values ";
        $data = "";$separator = "";
        foreach($tagsNames as $tag) {
            $data .= $separator;
            $data .= "('" . Warecorp_Data_Tag::normalizeValue( $tag) . "')";
            $separator = ",";
        }
        $query .= $data;
        $db->query($query);
        return true;
    }

    /* @todo This is really a bad hack, but we currenly have no idea
     * how to do this better.. ;(*/
    public static function normalizeValue($value) {
        $_regExEncoding = mb_regex_encoding();
        $_mbInEncoding  = mb_internal_encoding();
        mb_regex_encoding('UTF-8');
        mb_internal_encoding('UTF-8');
    	
        $restrict_chars = array( "'", "?", "!", "\\");
        if ( is_array( $value)) {
            foreach ( $value as &$v) {
                $v = mb_strtolower(str_replace( $restrict_chars, "", $v));
            }
        } else {
            $value = mb_strtolower(str_replace( $restrict_chars, "", $value));
        }
        mb_regex_encoding($_regExEncoding);
        mb_internal_encoding($_mbInEncoding);
        return $value;
    }
}
