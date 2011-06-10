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
 */
class BaseWarecorp_Data_AttachmentFile extends Warecorp_Data_Entity
{
    public $id;
    public $originalName;
    public $mimeType;
    
    public $fileExt = null;
    private $MimeType = null;
	/**
	 * constructor
	 *
	 * @param integer $id
	 */
	public function __construct($id = false)
	{
		parent::__construct('zanby_attachments__files');

		$this->addField('id');
		$this->addField('original_name','originalName');
		$this->addField('mime_type','mimeType');

		if ($id !== false){
			$this->loadByPk($id);
            $this->fileExt = Warecorp_File_Item::getFileExt($this->originalName);
		}
	}
	/**
	 * Проверяет, существует ли attachment с указанными id
	 * @param string $id 
	 * @return int | false
	 * @author Vitaly Targonsky
	 */
    public static function isAttachmentFileExists($id)
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('zanby_attachments__files', 'id')
               ->where('id = ?', $id);
        $id = $db->fetchOne($select);
        //$file = file_exists(md5($id));
        return ( $id ) ? $id : false;
    }
    /**
     * Добавляет новую связь для attachment-file и энтити
     * @param int $entity_id
     * @param int $entity_type_id
     * @author Vitaly Targonsky
     */
    public function insertRelation($entity_id, $entity_type_id)
    {
        if ( $attach_relation_id = Warecorp_Data_AttachmentRelation::isRelationExists($this->id, $entity_type_id ,$entity_id) ) {
            $Rel = new Warecorp_Data_AttachmentRelation($attach_relation_id);
        } else {
            $Rel = new Warecorp_Data_AttachmentRelation();
        }
        $Rel->fileId = $this->id;
        $Rel->entityId = $entity_id;
        $Rel->entityTypeId = $entity_type_id;
        $Rel->save();
    }
	/**
	 * Возвращает attach-files для определенного entity
	 * @param unknown_type $entity_id
	 * @param unknown_type $entity_type_id
	 * @return array of Warecorp_Data_AttachmentFile
	 * @author Vitaly Targonsky
	 */
    public static function getAttachmentFilesByEntity($entity_id, $entity_type_id)
    {
        $files = array();
        $rels = Warecorp_Data_AttachmentRelation::getRelationsByEntity($entity_id, $entity_type_id);
        if ( sizeof($rels) != 0 ) {
            foreach ($rels as &$rel) {
                $files[] =& $rel->getAttachmentFile();
            }
        }
        return $files;
    }
    
    public function count()
    {
		$sql = $this->_db->select()->from('zanby_attachments__relations', new Zend_Db_Expr('COUNT(id)'))->where('file_id=?', $this->id);
		return $this->_db->fetchOne($sql);
    }
    
    public function delete()
    {
        if (file_exists(ATTACHMENT_DIR.md5($this->id).'.file')) unlink(ATTACHMENT_DIR.md5($this->id).'.file');
        parent::delete();
    }
    /**
	 * Set MimeType of document
	 * @return void
	 * @author Artem Sukharev // copied from Warecorp_Document_Item
	 */
    public function setMimeType()
    {
        $dom = new DomDocument();
        $dom->load(RESOURCES_DIR."cfg.mimetypes.xml");
        $xp = new domXPath($dom);
        $titles = $xp->query('/config/mimetypes/mimetype[exp="'.strtolower($this->fileExt).'"]/mime');
        if ( $titles->length != 0 ) {
            $this->MimeType = $titles->item(0)->textContent;
        } else {
            $this->MimeType = "application/octet-stream";
        }
    }
    /**
	 * Return MimeType of document
	 * @return string
	 * @author Artem Sukharev // copied from Warecorp_Document_Item
	 */
    public function getMimeType()
    {
        if ( $this->MimeType === null ) {
            $this->setMimeType();
        }
        return $this->MimeType;
    }
}
