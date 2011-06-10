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
 * @author Vitaly Targonsky
 */
class BaseWarecorp_Data_AttachmentRelation extends Warecorp_Data_Entity
{
    public $id;
    public $fileId;
    public $entityTypeId;
    public $entityId;

    private $AttachmentFile = null;
	/**
	 * constructor
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct('zanby_attachments__relations');

		$this->addField('id');
		$this->addField('file_id', 'fileId');
		$this->addField('entity_type_id', 'entityTypeId');
		$this->addField('entity_id', 'entityId');

		if ($id !== false){
			$this->loadByPk($id);
		}
	}
	public function setAttachmentFile()
	{
	    $this->AttachmentFile = new Warecorp_Data_AttachmentFile($this->fileId);
	}
	public function getAttachmentFile()
	{
	    if ( $this->AttachmentFile === null ) {
	       $this->setAttachmentFile();
	    }
	    return $this->AttachmentFile;
	}
    /**
     * Проверяет, существует ли связь attach-file для entity
     * @param int $tag_id
     * @param int $entity_id
     * @param int $entity_type_id
     * @return bool
     * @author Vitaly Targonsky
     */
    public static function isRelationExists($file_id, $entity_id, $entity_type_id)
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('zanby_attachments__relations', 'id')
               ->where('file_id = ?', $file_id)
               ->where('entity_id = ?', $entity_id)
               ->where('entity_type_id = ?', $entity_type_id);
        $res = $db->fetchOne($select);
        return $res;
    }
    /**
     * Возвращает связи attach-file по entity
     * @param int $entity_id
     * @param int $entity_type_id
     * @return array of Warecorp_Data_TagRelation
     * @author Vitaly Targonsky
     */
    public static function getRelationsByEntity($entity_id, $entity_type_id)
    {
        $db = Zend_Registry::get('DB');
        $select = $db->select();
        $select->from('zanby_attachments__relations', 'id')
               ->where('entity_id = ?', $entity_id)
               ->where('entity_type_id = ?', $entity_type_id);
        $rels = $db->fetchCol($select);
        foreach($rels as &$rel) $rel = new Warecorp_Data_AttachmentRelation($rel);
        return $rels;
    }
    
    public function delete()
    {
        $file = $this->getAttachmentFile();
        parent::delete();
        if ($file->count() == 0) $file->delete();
    }
}
