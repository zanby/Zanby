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
 * @package    Warecorp_Photo
 * @copyright  Copyright (c) 2006
 */

/**
 *
 *
 */
class BaseWarecorp_Document_FolderItem extends Warecorp_Data_Entity
{
    private $id;
    private $parentFolderId;
    private $ownerType;
    private $ownerId;
    private $creatorId;
    private $name;
    private $creationDate;
    private $updateDate;
    private $private;

    private $_owner;
    /**
     * Constructor.
     *
     */
    public function __construct($id = null)
    {
        parent::__construct('zanby_documents__folders');

        $this->addField('id');
        $this->addField('owner_type', 'ownerType');
        $this->addField('owner_id', 'ownerId');
        $this->addField('creator_id', 'creatorId');
        $this->addField('private');
        $this->addField('parent_folder_id', 'parentFolderId');
        $this->addField('name');
        $this->addField('creation_date', 'creationDate');
        $this->addField('update_date', 'updateDate');
        
        if ($id !== null){
            $this->pkColName = 'id';
            $this->loadByPk($id);
        }
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($newValue)
    {
        $this->id = $newValue;
        return $this;
    }
    public function getParentFolderId()
    {
        return $this->parentFolderId;
    }
    public function setParentFolderId($newValue)
    {
        $this->parentFolderId = $newValue;
        return $this;
    }
    public function getOwnerType()
    {
        return $this->ownerType;
    }
    public function setOwnerType($newValue)
    {
        $this->ownerType = $newValue;
        return $this;
    }
    public function getOwnerId()
    {
        return $this->ownerId;
    }
    public function setOwnerId($newValue)
    {
        $this->ownerId = $newValue;
        return $this;
    }
    public function getCreatorId()
    {
        return $this->creatorId;
    }
    public function setCreatorId($newValue)
    {
        $this->creatorId = $newValue;
        return $this;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($newValue)
    {
        $this->name = $newValue;
        return $this;
    }
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    public function setCreationDate($newVal)
    {
        $this->creationDate = $newVal;
        return $this;
    }
    public function getUpdateDate()
    {
        return $this->updateDate;
    }
    public function setUpdateDate($newVal)
    {
        $this->updateDate = $newVal;
        return $this;
    }
    public function getPrivate()
    {
        return $this->private;
    }
    public function setPrivate($newValue)
    {
        $this->private = $newValue;
        return $this;
    }
    
    public function getOwner() {
        if ( $this->_owner === null ) {
            if ( $this->ownerType == 'user' ) {
                $this->_owner = new Warecorp_User("id", $this->getOwnerId());
            } elseif ( $this->ownerType == 'group' ) {
                $this->_owner = Warecorp_Group_Factory::loadById($this->getOwnerId());
            }
        }
        return $this->_owner;
    }
    
    /**
     * check if this folder can be deleted
     *
     * @param int $id - folder id
     * @return 1 if deletable 0 if cant
     */
    public function isDeletable($id = null){
        if ($id === null){
            $id = $this->getId();
        }

        if ($id == "") return 0;

        $db = Zend_Registry::get("DB");
        //check nested folders
        $select = $db->select();
        $select->from('zanby_documents__items', 'id');
        $select->where('folder_id=?', $id);
        $res1 = $db->fetchOne($select);

        //check nested files
        $select = $db->select();
        $select->from('zanby_documents__folders', 'id');
        $select->where('parent_folder_id=?', $id);
        $res2 = $db->fetchOne($select);

        if ($res1 || $res2) return 0; else  return 1;
    }

    public function move($ownerId, $ownerType, $parentFolderId)
    {        
        if ( $ownerType == 'user' ) {
            $owner = new Warecorp_User("id", $ownerId);
        } elseif ( $ownerType == 'group' ) {
            $owner = Warecorp_Group_Factory::loadById($ownerId);
        }
        
        $folderList = new Warecorp_Document_FolderList($this->getOwner());
        $subFoldersList = $folderList->setFolder($this->getId())->getList();
        if ( sizeof($subFoldersList) != 0 ) {
            foreach ( $subFoldersList as $folder ) {
                $folder->move($owner->getId(), $ownerType, $this->getId());
            }
        }

        $list = new Warecorp_Document_List($this->getOwner());
        
        $filesList = $list->setFolder($this->getId())->setShowShared(false)->getList();
        
        if ( sizeof($filesList) != 0 ) {
            foreach ( $filesList as $file ) {
                $file->move($owner->getId(), $ownerType, $this->getId());
            }
        }
        $this->setOwnerId($owner->getId())
             ->setOwnerType($ownerType)
             ->setParentFolderId($parentFolderId);
        $this->save();

    }

    /**
     * @author Sergey Vaninsky 
     * return TRUE if param($FolderId) is parent or subparent of thisFolder
     *  maximal deep is 100
     */
    public function isSubParent($FolderId)
    {
    	$deep = 0;
    	$parentId=$this->getParentFolderId();
    	$flag=true;
    	while ($flag){
    		if($parentId===$FolderId) {
    			$result=true;
    			$flag=false;
    		}elseif($deep===100 || $parentId===null) {
    			$result=false;
    			$flag=false;
    		}else {
    			$next = new Warecorp_Document_FolderItem($parentId);
    			$parentId=$next->getParentFolderId();
    			$deep++;
    		}
    	}	
    	return $result;	
    }
    	
    	
    public function deleteFolderRecursively()
    {
        if ( $this->isDeletable() ) {
            $this->delete();
            return true;
        }
        
        /**
         * document can not be shared to folder it can be shared to owner only
         * in this case setShowShared must be false
         */
        /* delete documents from current folder */
            $documentsObj = new Warecorp_Document_List($this->getOwner());
            $documents = $documentsObj->setFolder($this->getId())->setShowShared(false)->getList();
            foreach($documents as $document) $document->delete();
                    
        if ( $this->isDeletable() ) {
            $this->delete();
            return true;
        }
        
        /* delete recursive subfolders and documents */
        $foldersObj = new Warecorp_Document_FolderList($this->getOwner());
        $folders = $foldersObj->setFolder($this->getId())->getList();        
        foreach( $folders as $folder ) $folder->deleteFolderRecursively();    

        if ( $this->isDeletable() ) {
            $this->delete();
            return true;
        }        
        
        return false;
    }
    /**
     * save item
     * @author Vitaly Targonsky 
     */
        public function save()
    {
        if ($this->getId()) {
            $this->setCreationDate(new Zend_Db_Expr('NOW()'));
            $this->setUpdateDate(new Zend_Db_Expr('NOW()'));
        } else {
            $this->setUpdateDate(new Zend_Db_Expr('NOW()'));
        }
        parent::save();
    }
}
