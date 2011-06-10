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
 * Warecorp FRAMEWORK
 *
 * @package    Warecorp_Document
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */

/**
 *
 *
 */
class BaseWarecorp_Document_FolderList extends Warecorp_Abstract_List
{
    private $_ownerType        = null;
    private $_owner            = null;
    private $_folder           = null;

    function __construct($owner)
    {
        parent::__construct();
        $this->_owner = $owner;
        if ( $owner instanceof Warecorp_User) {
            $this->_ownerType = 'user';
        } elseif ( $owner instanceof Warecorp_Group_Simple) {
            $this->_ownerType = 'group';
        } elseif ( $owner instanceof Warecorp_Group_Family) {
            $this->_ownerType = 'group';
        } else {
            throw new Zend_Exception("Owner Type is invalid");
        }
    }
    /*
    *   Getters / Setters Methods
    *
    */
    public function getOwner() {
        return $this->_owner;
    }
    public function getOwnerType() {
        return $this->_ownerType;
    }
    public function setFolder($newVal)
    {
        $this->_folder = $newVal;
        return $this;
    }
    public function getFolder()
    {
        return $this->_folder;
    }
    
    public function getList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zdf.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zdf.name' : $this->getAssocValue();
            $query->from(array('zdf' => 'zanby_documents__folders'), $fields);  
        } else {
            $query->from(array('zdf' => 'zanby_documents__folders'), 'zdf.id');
        }
        
        $query->where('zdf.owner_type = ?', $this->getOwnerType());
        $query->where('zdf.owner_id = ?', $this->getOwner()->getId());
        
        if ($this->getFolder() === null){
            $query->where('zdf.parent_folder_id is null');
        } else {
            $query->where('zdf.parent_folder_id = ?', $this->getFolder());
        }

        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('zdf.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zdf.id NOT IN (?)', $this->getExcludeIds());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = new Warecorp_Document_FolderItem($item);
        }
        
    	return $items;
    }
    public function getCount()
    {
	    $query = $this->_db->select();
        $query->from(array('zdf' => 'zanby_documents__folders'), new Zend_Db_Expr('count(zdf.id)'));
        
        $query->where('zdf.owner_type = ?', $this->getOwnerType());
        $query->where('zdf.owner_id = ?', $this->getOwner()->getId());
        
        if ($this->getFolder() === null){
            $query->where('zdf.parent_folder_id is null');
        } else {
            $query->where('zdf.parent_folder_id = ?', $this->getFolder());
        }

        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('zdf.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zdf.id NOT IN (?)', $this->getExcludeIds());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = Warecorp_Document_FolderItem($item);
        }
        
        return $this->_db->fetchOne($query);
    }
    
    /**
     * Enter description here...
     *
     * @param unknown_type $folderName
     * @return int , if folder exists return id of folder
     */
    public function isFolderExistsByName($folderName)
    {
        $query = $this->_db->select();
        $query->from(array('zdf' => 'zanby_documents__folders'), 'zdf.id');
        
        $query->where('zdf.owner_type = ?', $this->getOwnerType());
        $query->where('zdf.owner_id = ?', $this->getOwner()->getId());
        
        if ($this->getFolder() === null) $query->where('zdf.parent_folder_id is null');
        else $query->where('zdf.parent_folder_id = ?', $this->getFolder());
        $query->where('name = ?', $folderName);
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('zdf.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zdf.id NOT IN (?)', $this->getExcludeIds());
        
        return $this->_db->fetchOne($query);        
    }
}
