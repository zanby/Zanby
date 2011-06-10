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
 * @author Artem Sukharev
 */
class BaseWarecorp_Data_TagRelation extends Warecorp_Data_Entity
{
    public $id;
    public $tagId;
    public $entityTypeId;
    public $entityId;
    public $weightGroup;
    public $weightUser;
    public $status;

    private $Tag = null;
	/**
	 * constructor
	 * @param integer $id
	 */
	public function __construct($value = null)
	{
		parent::__construct('zanby_tags__relations', array(
    		'id'              => 'id',
    		'tag_id'          => 'tagId',
    		'entity_type_id'  => 'entityTypeId',
    		'entity_id'       => 'entityId',
    		'weight_group'    => 'weightGroup',
    		'weight_user'     => 'weightUser',
    		'status'          => 'status'));
		$this->load($value);
	}
    
    /**
     * getter
     * @return int
     */
    public function getId() {
        return $this->id;
    }
	
	/**
	 * get tag object for current relation
	 * @return Warecorp_Data_Tag
	 */
	public function getTag()
	{
	    if ( $this->Tag === null ) $this->Tag = new Warecorp_Data_Tag($this->tagId);
	    return $this->Tag;
	}
	
    /**
     * check if exists tag relation for entity 
     * @param int $tag_id
     * @param int $entity_id
     * @param int $entity_type_id
     * @return bool
     * @author Artem Sukharev
     */
    public static function isRelationExists($tag_id, $entity_id, $entity_type_id, $status = 'user')
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('zanby_tags__relations', 'id')
               ->where('tag_id = ?', $tag_id)
               ->where('entity_id = ?', $entity_id)
               ->where('entity_type_id = ?', $entity_type_id)
               ->where('status = ?', $status);
        $res = $db->fetchOne($select);
        return $res;
    }
    
    /**
     * return array of relations for entity
     * @param int $entity_id
     * @param int $entity_type_id
     * @return array of Warecorp_Data_TagRelation
     * @author Artem Sukharev
     */
    public static function getRelationsByEntity($entity_id, $entity_type_id, $status = 'user')
    {
        $db = Zend_Registry::get('DB');
        $select = $db->select();
        $select->from('zanby_tags__relations', '*')
               ->where('entity_id = ?', $entity_id)
               ->where('entity_type_id = ?', $entity_type_id)
               ->where('status = ?', $status);
        $rels = $db->fetchAll($select);
        foreach($rels as &$rel) $rel = new Warecorp_Data_TagRelation($rel);
        return $rels;
    }
    
    public static function getEntitysTagsAsString($entity_type_id, $entity_id, $status = 'user')
    {
        $db = Zend_Registry::get('DB');
        #$query = "SELECT ztr.id, concat(ztd.name, '_', ztr.weight_user, '_', ztr.weight_group) as tag FROM zanby_tags__dictionary ztd JOIN zanby_tags__relations ztr ON ztd.id = ztr.tag_id WHERE ztr.entity_id = '4' AND ztr.entity_type_id = '1' AND ztr.status = 'system'";
        $select = $db->select();
        $select->from(array('ztd' => 'zanby_tags__dictionary'), array('ztr.id', 'tag' => new Zend_Db_Expr('CONCAT(ztd.name,"_",ztr.weight_user,"_",ztr.weight_group)')))
               ->join(array('ztr' => 'zanby_tags__relations'), 'ztd.id = ztr.tag_id')
               ->where('ztr.entity_id = ?', $entity_id)
               ->where('ztr.entity_type_id = ?', $entity_type_id)
               ->where('ztr.status = ?', $status);        
        $rels = $db->fetchPairs($select);
        return ($rels)?$rels:array();           
    }
    
    public static function deleteTagsRelations($relationsToDelete)
    {
        if (!empty($relationsToDelete)) {
            $db = Zend_Registry::get('DB');
            $db->delete('zanby_tags__relations', $db->quoteInto('id in (?)',$relationsToDelete));
        }
    }
    
    public static function insertTagsForEntity($relationsToInsert, $entity_type_id, $entity_id, $status = 'user')
    {
        /*
        if (empty($relationsToInsert)) return;

        $db = Zend_Registry::get('DB');
        $query = "insert into zanby_tags__relations(tag_id, entity_type_id, entity_id, weight_group, weight_user, status) values ";
        $data = ''; $separator = '';
        foreach ($relationsToInsert as $value) {
            $weight = $value['weight'];
            $data .= $separator;
            if (!empty($value['id']))
                $data .= '('.$value['id'].','.$entity_type_id.','.$entity_id.','.$weight->group.','.$weight->user.',"'.$status.'")';
            $separator = ',';
        }
        $query .= $data;
        $db->beginTransaction();
        try {
            $db->query($query);
            $db->commit(); 
        } catch(Exception $ex) {
            $db->rollback();
        } 
        */
        
        if (empty($relationsToInsert)) return;

        $db = Zend_Registry::get('DB');
        $query = "insert into zanby_tags__relations(tag_id, entity_type_id, entity_id, weight_group, weight_user, status) values ";
        $arrData = array(); 
        foreach ($relationsToInsert as $value) {
            $weight = $value['weight'];
            if ( !empty($value['id']) ) {
                $arrData[] = '('.$value['id'].','.$entity_type_id.','.$entity_id.','.$weight->group.','.$weight->user.',"'.$status.'")';
            }
        }
        $query .= join(',', $arrData);
        $db->beginTransaction();
        try {
            $db->query($query);
            $db->commit(); 
        } catch(Exception $ex) {
            $db->rollback();
            //$query = "ROLLBACK (".$ex->getMessage().") : " . $query;
        }
        //return $query; 
    }    
}
