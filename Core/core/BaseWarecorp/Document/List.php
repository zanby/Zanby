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
class BaseWarecorp_Document_List extends Warecorp_Abstract_List
{
    private $_folder            = null;
    private $_privacy           = null;
    private $_ownerType         = null;
    private $_owner             = null;
    private $_current_user      = null;
    private $_show_shared       = true;
    private $_fromAllFolders    = false;
    /**
     * @var int Family ID
     */
    private $_shared_all_family_children_only = null;
    /**
     * @var int Group ID
     */
    private $_shared_to_group;

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
    /**
     * @param int $familyId
     * @return Warecorp_Document_List
     */
    public function setSharedAllFamilyChildrenOnly( $familyId )
    {
        $this->_shared_all_family_children_only = $familyId;
        return $this;
    }
    /**
     * @return int|null
     */
    public function getSharedAllFamilyChildrenOnly()
    {
        return $this->_shared_all_family_children_only;
    }
    /**
     * @param Warecorp_Group_Base|int
     * @return Warecorp_Document_List
     */
    public function setSharedToGroup($group)
    {
        if ( is_numeric($group) )
            $this->_shared_to_group = $group;
        elseif ( $group instanceof Warecorp_Group_Base )
            $this->_shared_to_group = $group->getId();
        return $this;
    }
    /**
     * @return int Group ID
     */
    public function getSharedToGroup()
    {
        return $this->_shared_to_group;
    }
    /*
    *   Getters / Setters Methods
    *
    */
    public function getOwnerType() {
        return $this->_ownerType;
    }
    public function getOwner() {
        return $this->_owner;
    }
    public function getFolder() {
        return $this->_folder;
    }
    public function setFolder( $value ) {
        $this->_folder = $value;
        return $this;
    }
    public function getPrivacy() {
        if ( $this->_privacy === null ) return array(0,1);
        else return $this->_privacy;
    }
    public function setPrivacy( $value ) {
        if ( is_array($value) ) $this->_privacy = $value;
        return $this;
    }
    public function getCurrentUser() {
        if ( $this->_current_user === null ) throw new Zend_Exception("Current User is undefined");
        return $this->_current_user;
    }
    public function setCurrentUser( $value ) {
        if ( !($value instanceof Warecorp_User) ) throw new Zend_Exception("Value isn't instance of Warecorp_User");
        $this->_current_user = $value;
        return $this;
    }
    public function setShowShared($boolean)
    {
        $this->_show_shared = (bool) $boolean;
        return $this;
    }
    public function isShowShared()
    {
        return $this->_show_shared;
    }
    public function setFromAllFolders($boolean)
    {
        $this->_fromAllFolders = (bool) $boolean;
        return $this;
    }
    public function getFromAllFolders()
    {
        return $this->_fromAllFolders;
    }
    /*
    *
    *
    */
    public function getList() {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null )   ? 'vdl.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'vdl.original_name as originalName' : $this->getAssocValue();
            $query->from(array('vdl' => 'view_documents__list'), $fields);  
        } else {
            $query->from(array('vdl' => 'view_documents__list'), 'vdl.*');
        }

        if ( null === ($familyId = $this->getSharedAllFamilyChildrenOnly()) ) {

            $query->where('vdl.owner_type = ?', $this->getOwnerType());
            $query->where('vdl.owner_id = ?', $this->getOwner()->getId());

            if ( !$this->getFromAllFolders() ) {
                if ( is_null($this->getFolder()) ){
                    $_w = 'vdl.folder_id is null';
                    if ($this->isShowShared() === true) {
                        $_w = '('.$_w.' OR vdl.share = 1)';
                    }
                    $query->where($_w);
                } else {
                    $query->where('vdl.folder_id = ?', $this->getFolder());
                }
            }
            if ( $this->isShowShared() == false ) {
                $query->where('vdl.share = ?', 0);
            } elseif (is_null($this->getFolder())) {

            }

            if ( $this->getPrivacy() ) $query->where('vdl.private IN (?)', $this->getPrivacy());
            else $query->where('vdl.private is null');


            if ( $this->getWhere() ) $query->where($this->getWhere());
            if ( $this->getIncludeIds() ) $query->where('vdl.id IN (?)', $this->getIncludeIds());
            if ( $this->getExcludeIds() ) $query->where('vdl.id NOT IN (?)', $this->getExcludeIds());

            /** Exclude documents shared to family's groups, but not documents where current group is real owner */
            if ( $this->getOwner() instanceof Warecorp_Group_Family ) {
                $query->group('vdl.id');
            } else {
                if ( $this->getOwnerType() == 'group' ) {                
                    $query
                        ->join(array('zdi' => 'zanby_documents__items'), 'vdl.id = zdi.id', array())
                        ->joinLeft(array('zes' => 'zanby_entity__share'), '( zes.entity_type = 5 AND zes.entity_id = vdl.id)', array())
                        ->where("(`zes`.`family_id` is null")
                        ->orWhere("(vdl.owner_id = zdi.owner_id AND zdi.owner_type = _utf8'group')")
                        ->orWhere("(vdl.owner_id = zdi.owner_id AND zdi.owner_type = _utf8'user'))")
                        ->group('vdl.id');
                } else {
                    $query->join(array('zdi' => 'zanby_documents__items'), 'vdl.id = zdi.id', array());
                }
            }
        }
        else {
            /** Get only documents shared to current group from setted family */
            $groupId = $this->getSharedToGroup();
            if ( empty($groupId) )
                throw new Warecorp_Exception('You must set Group ID where documents are shared');

            $query
                ->joinLeft(array('zes' => 'zanby_entity__share'), '( zes.entity_type = 5 AND zes.entity_id = vdl.id)', array())
                ->where('vdl.owner_type = ?', 'group')
                ->where('vdl.owner_id = ?', $groupId)
                ->where('zes.family_id = ?', $familyId)
                ->where('vdl.share = ?', 1)
                ->group('vdl.id');
        }
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) $query->limitPage($this->getCurrentPage(), $this->getListSize());
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
//print $query;exit;
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchAll($query);
            foreach ( $items as &$item ) {
                $shared = $item['share'];
                $item = new Warecorp_Document_Item($item);
                $item->setShare($shared);
            }
        }
//print_r($items);exit;
    	return $items;
    }
    
    public function getCount()
    {
	    $query = $this->_db->select();
        $query->from(array('vdl' => 'view_documents__list'), new Zend_Db_Expr('count(id)'));
        $query->where('vdl.owner_type = ?', $this->getOwnerType());
        $query->where('vdl.owner_id = ?', $this->getOwner()->getId());

        if (!$this->getFromAllFolders()) {
            if (is_null($this->getFolder())){
                $_w = 'vdl.folder_id is null';
                if ($this->isShowShared() === true) {
                    $_w = '('.$_w.' OR vdl.share = 1)';
                }
                $query->where($_w);
            } else {
                $query->where('vdl.folder_id = ?', $this->getFolder());
            }
        }
        if ( $this->isShowShared() == false ) {
            $query->where('vdl.share = ?', 0);
        }
        $query->where('vdl.private IN (?)', $this->getPrivacy());

        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('vdl.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vdl.id NOT IN (?)', $this->getExcludeIds());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        
        return $this->_db->fetchOne($query);
    }
    
    public function isDocumentExistsByName($documentName)
    {
        $query = $this->_db->select();
        $query->from(array('vdl' => 'view_documents__list'), 'id');
        
        $query->where('vdl.owner_type = ?', $this->getOwnerType());
        $query->where('vdl.owner_id = ?', $this->getOwner()->getId());
        $query->where('vdl.original_name = ?', $documentName);
        
        if (!$this->getFromAllFolders()) {
            if (is_null($this->getFolder())){
                $_w = 'vdl.folder_id is null';
                if ($this->isShowShared() === true) $_w = '('.$_w.' OR vdl.share = 1)';
                $query->where($_w);
            } else {
                $query->where('vdl.folder_id = ?', $this->getFolder());
            }
        }
        if ( $this->isShowShared() == false ) {
            $query->where('vdl.share = ?', 0);
        }
        $query->where('vdl.private IN (?)', $this->getPrivacy());

        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('vdl.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vdl.id NOT IN (?)', $this->getExcludeIds());
                
        return $this->_db->fetchOne($query);
    }
}
