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
 * @package Warecorp_Photo_Gallery
 * @author Artem Sukharev
 * @version 1.0
 */
class BaseWarecorp_Photo_Gallery_Abstract extends Warecorp_Data_Entity
{
    /**
     * table name
     */
    public static $_dbTableName = 'zanby_galleries__items';
    
    /**
     * table name with sharing referances
     */
    public static $_dbShareTableName = 'zanby_galleries__sharing';
    
    /**
     * table name with sharing history
     */
    public static $_dbShareHistoryTableName = 'zanby_galleries__sharing_history';
    
    /**
     * table name with sharing referances
     */
    public static $_dbWatchingTableName = 'zanby_galleries__watching';

    /**
     * table name with sharing referances
     */
    public static $_dbUserViewsTableName = 'zanby_galleries__views';
    
    /**
     * table name with import referances
     */
    public static $_dbImportTableName = 'zanby_galleries__import';
    
    /**
     * view name
     */
    //public static $_dbViewName = 'view_groups__gallery_list';
    public static $_dbViewName = 'view_gallery__list';
    
    public static $_dbPublishTableName = 'zanby_galleries__publishing';
    
	/**
	 * id of gallery
	 */
	private $id;
	/**
	 * id of gallery owner (user or group id)
	 */
	private $ownerId;
	/**
	 * type of gallery owner (user or group)
	 */
	private $ownerType;
	/**
	 * referance of gallery owner
	 */
	private $owner;
	/**
	 * id of user created this gallery
	 */
	private $creatorId;
	/**
	 * reference of creater object
	 */
	private $creator;
	/**
	 * title of gallery
	 */
	private $title;
	/**
	 * description of gallery
	 */
	private $description;
	/**
	 * gallery creation date
	 */
	private $createDate;
	/**
	 * is gallary private or public
	 */
	private $private;
	/**
	 * date of last update photos of category
	 */
	private $updateDate;
	/**
	 * size of gallery in bytes
	 */
	private $size;
    
    private $isCreated;
    
    private $isPublished = 0;

	function __construct($galleryId = null)
	{
		parent::__construct(self::$_dbTableName);
		
        $this->addField('id');
        $this->addField('owner_type', 'ownerType');
        $this->addField('owner_id', 'ownerId');
        $this->addField('creator_id', 'creatorId');
        $this->addField('title');
        $this->addField('description');
        $this->addField('creation_date', 'createDate');
        $this->addField('private', 'private');
        $this->addField('update_date', 'updateDate');
        $this->addField('size');
        
        $this->addField('iscreated', 'isCreated');
        $this->addField('ispublished', 'isPublished');

		if ( $galleryId !== null ) {
			$this->pkColName = 'id';
			$this->loadByPk($galleryId);
		}
	}

	/**
	 * id of gallery
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * id of gallery
	 * @param newVal
	 * @return Warecorp_Photo_Gallery
	 * @author Artem Sukharev
	 */
	public function setId($newVal)
	{
		$this->id = $newVal;
		return $this;
	}
    
    /**
     * gallery created flag
     * 
     */
    public function getIsCreated()
    {
        return $this->isCreated;
    }

    /**
     * set gallery created flag
     * @param newVal
     * @return Warecorp_Photo_Gallery
     * @author Yury Zolotarsky
     */
    public function setIsCreated($newVal = 1)
    {
        if ($newVal == 0)
            $this->isCreated = $newVal;
        else
            $this->isCreated = 1;
        return $this;
    }    

	/**
	 * id of gallery owner (user or group id)
	 * @author Artem Sukharev
	 */
	public function getOwnerId()
	{
		return $this->ownerId;
	}

	/**
	 * id of gallery owner (user or group id)
	 * @param newVal
	 * @return Warecorp_Photo_Gallery
	 * @author Artem Sukharev
	 */
	public function setOwnerId($newVal)
	{
		$this->ownerId = $newVal;
		return $this;
	}

	/**
	 * type of gallery owner (user or group)
	 * @author Artem Sukharev
	 */
	public function getOwnerType()
	{
		return $this->ownerType;
	}

	/**
	 * type of gallery owner (user or group)
	 * @param newVal
	 * @return Warecorp_Photo_Gallery
	 * @author Artem Sukharev
	 */
	public function setOwnerType($newVal)
	{
		$this->ownerType = $newVal;
		return $this;
	}

	/**
	 * id of user created this gallery
	 * @author Artem Sukharev
	 */
	public function getCreatorId()
	{
		return $this->creatorId;
	}

	/**
	 * id of user created this gallery
	 * @param newVal
	 * @return Warecorp_Photo_Gallery
	 * @author Artem Sukharev
	 */
	public function setCreatorId($newVal)
	{
		$this->creatorId = $newVal;
		return $this;
	}

	/**
	 * reference to creater object
	 * @return Warecorp_User
	 * @author Artem Sukharev
	 */
	public function getCreator()
	{
		if ( $this->creator === null ) $this->creator = new Warecorp_User('id', $this->getCreatorId());
		return $this->creator;
	}

	/**
	 * title of gallery
	 * @author Artem Sukharev
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * title of gallery
	 * @param newVal
	 * @return Warecorp_Photo_Gallery
	 * @author Artem Sukharev
	 */
	public function setTitle($newVal)
	{
		$this->title = $newVal;
		return $this;
	}

	/**
	 * description of gallery
	 * @author Artem Sukharev
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * description of gallery
	 * @param newVal
	 * @return Warecorp_Photo_Gallery
	 * @author Artem Sukharev
	 */
	public function setDescription($newVal)
	{
		$this->description = $newVal;
		return $this;
	}

	/**
	 * gallery creation date
	 * @author Artem Sukharev
	 */
	public function getCreateDate()
	{
		return $this->createDate;
	}

	/**
	 * gallery creation date
	 * @param newVal
	 * @return Warecorp_Photo_Gallery
	 * @author Artem Sukharev
	 */
	public function setCreateDate($newVal)
	{
		$this->createDate = $newVal;
		return $this;
	}

	/**
	 * is gallary private or public
	 * @author Artem Sukharev
	 */
	public function getPrivate()
	{
		return $this->private;
	}

	/**
	 * is gallary private or public
	 * @param newVal
	 * @return Warecorp_Photo_Gallery
	 * @author Artem Sukharev
	 */
	public function setPrivate($newVal)
	{
		$this->private = $newVal;
		return $this;
	}
    
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function setIsPublished($newVal)
    {
        if ($newVal != 1) $newVal = 0;
        $this->isPublished = $newVal;
        return $this;
    }    

	/**
	 * referance of gallery owner
	 * @author Artem Sukharev
	 */
	public function getOwner()
	{
		if ( $this->owner === null ) {
            if ( $this->getOwnerType() == 'user' ) $this->owner = new Warecorp_User('id', $this->getOwnerId());
            else $this->owner = Warecorp_Group_Factory::loadById($this->getOwnerId());
		}
		return $this->owner;
	}

	/**
	 * date of last update photos of category
	 * @author Artem Sukharev
	 */
	public function getUpdateDate()
	{
		return $this->updateDate;
	}

	/**
	 * date of last update photos of category
	 * @param newVal
	 * @return Warecorp_Photo_Gallery
	 * @author Artem Sukharev
	 */
	public function setUpdateDate($newVal)
	{
		$this->updateDate = $newVal;
        if ( $this->updateDate == "0000-00-00 00:00:00" ) {
            $this->updateDate = $this->getCreateDate();
        }		
		return $this;
	}

	/**
	 * size of gallery in bytes
	 * @param string $unit - value from Warecorp_Photo_Enum_SizeUnit
	 * @author Artem Sukharev
	 */
	public function getSize($unit = Warecorp_Photo_Enum_SizeUnit::BYTE)
	{
        $size = 0;
        if (!$this->getId()) return $size;
        $photoList = $this->getPhotos()->getList();
        if (sizeof($photoList) != 0) {
            foreach ($photoList as $photo) {
                $size = $size + $photo->getSize(Warecorp_Photo_Enum_SizeUnit::BYTE);
            }
        }
        switch ($unit) {
            case Warecorp_Photo_Enum_SizeUnit::BYTE:
                return $size;
                break;
            case Warecorp_Photo_Enum_SizeUnit::KBYTE:
                return $size / 1024;
                break;
            case Warecorp_Photo_Enum_SizeUnit::MBYTE:
                return $size / 1024 / 1024;
                break;
        }
	}
	
	/**
	 * size of gallery in bytes
	 * @param newVal
	 * @return Warecorp_Photo_Gallery
	 * @author Artem Sukharev
	 */
	public function setSize($newVal)
	{
		$this->size = $newVal;
		return $this;
	}

    /**
     * return Poto_List object
     * @author Artem Sukharev
     */
    public function getPhotos()
    {
    	return Warecorp_Photo_List_Factory::load($this);
    }
    
	/**
     * check if gallery exists
     * @param galleryId
     * @author Artem Sukharev
     */
    public static function isGalleryExists($galleryId)
    {
    	$db = Zend_Registry::get('DB');
    	$query = $db->select();
    	$query->from(self::$_dbTableName, new Zend_Db_Expr('COUNT(*)'));
    	$query->where('id = ?', $galleryId);
    	return (boolean) $db->fetchOne($query);
    }
	
    /**
     * check if user watch this gallery
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Artem Sukharev
     */
    public function isWatched($user)
    {
    	if ( !($user instanceof Warecorp_User) ) $user = new Warecorp_User('id', $user);
    	    	
    	$query = $this->_db->select();
    	$query->from(self::$_dbWatchingTableName, new Zend_Db_Expr('COUNT(*)'));
    	$query->where('gallery_id = ?', $this->getId());
    	$query->where('user_id = ?', $user->getId());
    	
    	return (boolean) $this->_db->fetchOne($query);
    }
    
    /**
     * set gallery watching for user
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Artem Sukharev
     */
    public function watch($user)
    {
    	if ( !($user instanceof Warecorp_User) ) $user = new Warecorp_User('id', $user);
    	
    	if ( !$this->isWatched($user) ) {
    		if ( $this->isShared($user) ) $this->unshare($user);
	    	$data = array();
	    	$data['gallery_id']            = $this->getId();
	    	$data['user_id']               = $user->getId();
	    	$data['watching_start_date']   = new Zend_Db_Expr('NOW()');
	    	$this->_db->insert(self::$_dbWatchingTableName, $data);
	    	return true;
    	}
    	return false;
    }
    
    /**
     * unset gallery watching for user
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Artem Sukharev
     */
    public function stopWatch($user)
    {
        if ( !($user instanceof Warecorp_User) ) $user = new Warecorp_User('id', $user);
        
        if ( $this->isWatched($user) ) {
            $where = array();
            $where[] = $this->_db->quoteInto('user_id = ?', $user->getId());
            $where[] = $this->_db->quoteInto('gallery_id = ?', $this->getId());
            $where = join(' AND ', $where);
            $rows_affected = $this->_db->delete(self::$_dbWatchingTableName, $where);
            /**
             * remove import information
             */
            $where = array();
            $where[] = $this->_db->quoteInto('user_id = ?', $user->getId());
            $where[] = $this->_db->quoteInto('gallery_id = ?', $this->getId());
            $where[] = $this->_db->quoteInto('action_type = ?', Warecorp_Photo_Enum_ImportActionType::WATCH_GALLERY);
            $where = join(' AND ', $where);
            $rows_affected = $this->_db->delete(self::$_dbImportTableName, $where);
            return true;
        }
        return false;
    }
    
    /**
     * unset gallery watching for all users
     * @return boolean
     * @author Artem Sukharev
     */
    public function stopAllWatch()
    {
        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $rows_affected = $this->_db->delete(self::$_dbWatchingTableName, $where);
        /**
         * remove import information
         */
        $where = array();
        $where[] = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $where[] = $this->_db->quoteInto('action_type = ?', Warecorp_Photo_Enum_ImportActionType::WATCH_GALLERY);
        $where = join(' AND ', $where);
        $rows_affected = $this->_db->delete(self::$_dbImportTableName, $where);
        return true;
    }
    
    /**
     * check if gallery is shared in context
     * @param Warecorp_User|Warecorp_Group_Base $context
     * @return boolean
     * @author Artem Sukharev
     */
    public function isShared($context)
    {
    	if ( !($context instanceof Warecorp_User) && !($context instanceof Warecorp_Group_Base) ) {
    	   throw new Zend_Exception('Incorrect Context');
    	}
        
        if ($this->getOwnerId() == $context->getId() && $context->EntityTypeName == $this->getOwnerType() ) return false;
    	
        $query = $this->_db->select()
            ->from(self::$_dbViewName, new Zend_Db_Expr('COUNT(*)'))
            ->where('id = ?', $this->getId())
            ->where('owner_type = ?', $context->EntityTypeName)
            ->where('owner_id = ?', $context->getId())
            ->where('share = ?', 1);
        
        return (boolean) $this->_db->fetchOne($query);
    }
    
    public function isPublishingSource()
    {        
        $query = $this->_db->select();
        $query->from(self::$_dbPublishTableName, new Zend_Db_Expr('COUNT(*)'));
        $query->where('gallery_from = ?', $this->getId());
       
        return (boolean) $this->_db->fetchOne($query);                
    }    
    
    /**
     * get Groups that gallery shared with
     * @param Warecorp_User|Warecorp_Group_Base $context
     * @return boolean
     * @author Yury Zolotarsky
     */
    public function getGroupsSharedWith()
    {
        $query = $this->_db->select();
        $query->from(self::$_dbShareTableName, self::$_dbShareTableName.'.owner_id');
        $query->join(array('zgi' => 'zanby_groups__items'), self::$_dbShareTableName.'.owner_id=zgi.id');
        $query->where(self::$_dbShareTableName.'.gallery_id = ?', $this->getId());
        $query->where(self::$_dbShareTableName.'.owner_type = ?', 'group');
        $items = $this->_db->fetchCol($query);
        if (!is_array($items)) $items = array();
        foreach ($items as $id) {
			$group = Warecorp_Group_Factory::loadById($id);
			$result[$group->getId()] = $group->getName();
        }
        if (empty($result)) return array();
        return $result;
    }    

    /**
     * get Users that gallery shared with
     * @param Warecorp_User|Warecorp_Group_Base $context
     * @return boolean
     * @author Yury Zolotarsky
     */
    public function getUsersSharedWith()
    {
        $query = $this->_db->select();
        $query->from(self::$_dbShareTableName, array('owner_id'));
        $query->join(array('zua' => 'zanby_users__accounts'), self::$_dbShareTableName.'.owner_id = zua.id', array('login'));
        $query->where(self::$_dbShareTableName.'.gallery_id = ?', $this->getId());
        $query->where(self::$_dbShareTableName.'.owner_type = ?', 'user');
        
        $items = $this->_db->fetchPairs($query);
        if (!$items) return array();
        return $items;
    }    
    /**
     * share gallery in context
     * @param Warecorp_User|Warecorp_Group_Base $context
     * @param boolean $shareWithFamily
     * @return void
     * @author Artem Sukharev
     */
    public function share($context, $shareWithFamily = false)
    {
        if ( !($context instanceof Warecorp_User) && !($context instanceof Warecorp_Group_Base) ) {
           throw new Zend_Exception('Incorrect Context');
        }
        
        if ( $shareWithFamily ) {
            if ( !Warecorp_Share_Entity::isShareExists($context->getId(), $this->getId(), $this->EntityTypeId) ) {
                return Warecorp_Share_Entity::addShare($context->getId(), $this->getId(), $this->EntityTypeId);
            }
        }
        else {
    	    if ( !$this->isShared($context) ) {
                $data = array(
                    'gallery_id'    =>  $this->getId(),
                    'owner_id'      =>  $context->getId(),
                    'owner_type'    =>  $context->EntityTypeName,
                    'create_date'   =>  new Zend_Db_Expr('NOW()')
                );
    	        $this->_db->insert(self::$_dbShareTableName, $data);    	   
    	        return true;
    	    }
        }    	
    	return false;
    }
    
    /**
     * unshare gallery from context
     * @param Warecorp_User|Warecorp_Group_Base $context
     * @param boolean $shareWithFamily
     * @return boolean
     * @author Artem Sukharev
     */
    public function unshare($context, $shareWithFamily = false)
    {
        if ( !($context instanceof Warecorp_User) && !($context instanceof Warecorp_Group_Base) ) {
            throw new Zend_Exception('Incorrect Context');
        }

        if ( $shareWithFamily && ($context instanceof Warecorp_Group_Family) ) {
            if ( Warecorp_Share_Entity::isShareExists($context->getId(), $this->getId(), $this->EntityTypeId) ) {

                return Warecorp_Share_Entity::removeShare($context->getId(), $this->getId(), $this->EntityTypeId, true);

            }
        }
        elseif ( $this->isShared($context) ) {
            $where = array();
            $where[] = $this->_db->quoteInto('owner_id = ?', $context->getId());
            $where[] = $this->_db->quoteInto('owner_type = ?', $context->EntityTypeName);
            $where[] = $this->_db->quoteInto('gallery_id = ?', $this->getId());
            $where = join(' AND ', $where);
            $rows_affected = $this->_db->delete(self::$_dbShareTableName, $where);
            return true;
        }
        return false;
    }
    
    /**
     * get date of sharing
     * @param Warecorp_User|Warecorp_Group_Base $context
     * @return boolean
     * @author Artem Sukharev
     */
    public function getShareDate($context)
    {
        $query = $this->_db->select()
            ->from(self::$_dbShareTableName, 'create_date')
            ->where('gallery_id = ?', $this->getId())
            ->where('owner_type = ?', $context->EntityTypeName)
            ->where('owner_id = ?', $context->getId());
        $date = $this->_db->fetchOne($query);
        if ( !$date ) {
            $query = $this->_db->select()
                ->from(self::$_dbViewName, 'creation_date')
                ->where('id = ?', $this->getId())
                ->where('owner_type = ?', $context->EntityTypeName)
                ->where('owner_id = ?', $context->getId());
            $date = $this->_db->fetchOne($query);
        }
        
        return $date;
    }
    
    /**
     * set gallery as viewed for user, save last view date
     * @param Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User $user
     * @return boolean
     * @author Arte Sukharev     
     */
    public static function setGalleryViewed($gallery, $user)
    {
        if ( !($gallery instanceof Warecorp_Photo_Gallery_Abstract) ) {
           $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery);
           if ( $gallery->getId() === null ) throw new Zend_Exception('Incorrect gallery');
        }
        if ( !($user instanceof Warecorp_User) ) {
           $user = Warecorp_User('id', $user);         
        }
        /**
         * if user unregistered or not exists - not write
         */
        if ( $user->getId() === null ) return false;
        /**
         * if user is owner of gallery - not write
         */
        if ( $gallery->getOwnerType() == 'user' && $user->getId() == $gallery->getOwnerId() ) {
            //return false;
        }
        
        $db = Zend_Registry::get('DB');
        $query = $db->select();
        $query->from(self::$_dbUserViewsTableName, array('last_view_date'));
        $query->where('gallery_id = ?', $gallery->getId());
        $query->where('user_id = ?', $user->getId());
        $lastViewDate = $db->fetchOne($query);
        
        if ( !$lastViewDate ) {
	        $data = array();
	        $data['gallery_id']            = $gallery->getId();
	        $data['user_id']               = $user->getId();
	        $data['last_view_date']        = new Zend_Db_Expr('NOW()');
	        $rows_affected = $db->insert(self::$_dbUserViewsTableName, $data);
        } else {
            $data = array();
            $data['last_view_date']     = new Zend_Db_Expr('NOW()');
            $where = array();
            $where[] = $db->quoteInto('gallery_id = ?', $gallery->getId());
            $where[] = $db->quoteInto('user_id = ?', $user->getId());
            $where = join(' AND ', $where);
            $rows_affected = $db->update(self::$_dbUserViewsTableName, $data, $where);        	
        }
        return true;
    }
    
    /**
     * check if gallary was updated from last user view
     * @param Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User $user
     * @return boolean
     * @author Arte Sukharev
     */
    public static function isGalleryUpdated($gallery, $user)
    {
    	if ( !($gallery instanceof Warecorp_Photo_Gallery_Abstract) ) {
    	   $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery);
    	   if ( $gallery->getId() === null ) throw new Zend_Exception('Incorrect gallery');
    	}
    	if ( !($user instanceof Warecorp_User) ) {
    	   $user = Warecorp_User('id', $user);    	   
    	}
    	/**
    	 * if user unregistered or not exists return false
    	 */
    	if ( $user->getId() === null ) return false;
    	/**
    	 * if user is owner of gallery - return false
    	 */
        if ( $gallery->getOwnerType() == 'user' && $user->getId() == $gallery->getOwnerId() ) {
            return false;
        }

    	$db = Zend_Registry::get('DB');
    	$query = $db->select();
    	$query->from(self::$_dbUserViewsTableName, array('last_view_date'));
    	$query->where('gallery_id = ?', $gallery->getId());
    	$query->where('user_id = ?', $user->getId());
    	$lastViewDate = $db->fetchOne($query);
    	/**
    	 * if not exists record for this gallery and user - return true (gallery is updated)
    	 */
    	if ( !$lastViewDate ) return true;
    	    
        $data = new Zend_Date($gallery->getUpdateDate(), Zend_Date::ISO_8601);
        $data->setTimezone('UTC');
        $lastViewDate = new Zend_Date($lastViewDate, Zend_Date::ISO_8601);
        $lastViewDate->setTimezone('UTC');
        if ( $data->isLater($lastViewDate) ) {
            return true;
        }
    	
    	return false;
    }
    
    /**
     * copy current gallery in other gallery
     * @param Warecorp_Photo_Gallery_Abstract
     */
    public function copy($gallery)
    {
		if ( !($gallery instanceof Warecorp_Photo_Gallery_Abstract) ) return false;
    	
    	$photosList = $this->getPhotos()->getList();
    	if ( sizeof($photosList) != 0 ) {
            foreach ( $photosList as &$photo ) {
                $photo->copy($gallery);
    	    }
    	}
    }
    
    public function publish($gallery)
    {
        if ( !($gallery instanceof Warecorp_Photo_Gallery_Abstract) ) return false;
        
        $data = array();
        $data['gallery_from']            = $this->getId();
        $data['gallery_to']              = $gallery->getId();
        $this->_db->insert(self::$_dbPublishTableName, $data);
    }    
    
    /**
     * save import history
     * @param int|Warecorp_User $user
     * @param string $actionType
     * @param int $photoId
     * @return void
     * @author Artem Sukharev
     */
    public function saveImportHistory($user, $actionType, $relatedGalleryId, $photoId = null)
    {
        if ( $user instanceof Warecorp_User ) $user = $user->getId();         
        if ( !Warecorp_Photo_Enum_ImportActionType::isIn($actionType) ) throw new Zend_Exception('Incorrect action type');

        $data = array();
    	$data['user_id']               = $user;
    	$data['gallery_id']            = $this->getId();
    	$data['photo_id']              = $photoId;
    	$data['action_type']           = $actionType;
    	$data['action_date']           = new Zend_Db_Expr('NOW()');
    	$data['related_gallery_id']    = $relatedGalleryId;
    	$this->_db->insert(self::$_dbImportTableName, $data);
    }
    
    /**
     * return import history
     * @param int|Warecorp_User $user
     * @param int $photoId
     * @author Artem Sukharev
     */
    public function getImportHistory($user, $photoId = null)
    {
    	if ( $user instanceof Warecorp_User ) $user = $user->getId();
    	
    	$query = $this->_db->select();
    	$query->from(self::$_dbImportTableName, '*');
    	$query->where('user_id = ?', $user);
    	$query->where('gallery_id = ?', $this->getId());
    	if ( $photoId === null ) $query->where('photo_id IS NULL');
    	else {
    	   $query->where('(photo_id = ?', $photoId);
    	   $query->orwhere('photo_id IS NULL)');
    	}
    	$query->order(array('photo_id DESC', 'action_date DESC'));
    	$query->limitPage(1, 1);
    	$res = $this->_db->fetchAll($query);
    	if ( $res ) {
    	   $res = $res[0];
    	   if ( $res['related_gallery_id'] ) $res['related_gallery'] = Warecorp_Photo_Gallery_Factory::loadById($res['related_gallery_id']);
    	   return $res;
    	} else return null;
    }
    
    /**
     * save sharing history
     * @param Warecorp_User|int $user
     * @param string $strRecipient
     * @param enum $ownerType - user or group
     * @param array of int $ownerIds
     * @param int $photoId - default null
     * @return void
     * @author Artem Sukharev
     */
    public function saveShareHistory($user, $strRecipient, $ownerType, $ownerIds, $photoId = null )
    {
    	$data = array();
    	$data['gallery_id']    = $this->getId();
    	$data['photo_id']      = ($photoId === null) ? new Zend_Db_Expr('NULL') : floor($photoId);
    	$data['user_id']       = ($user instanceof Warecorp_User) ? $user->getId() : $user;
    	$data['recipients']    = $strRecipient;
    	$data['share_date']    = new Zend_Db_Expr('NOW()');
    	$data['owner_type']    = $ownerType;
    	$data['owner_ids']     = serialize($ownerIds);

    	$this->_db->insert(self::$_dbShareHistoryTableName, $data);
    }
    
    /**
     * check if gallery has share history
     * @return boolean
     * @author Artem Sukharev
     */
    public function isShareHistoryExists()
    {
    	$query = $this->_db->select();
    	$query->from(self::$_dbShareHistoryTableName, new Zend_Db_Expr('COUNT(gallery_id)'));
    	$query->where('gallery_id = ?', $this->getId());
    	return (boolean) $this->_db->fetchOne($query);
    }
    
    /**
     * return history of sharing
     * @param Warecorp_User|int $user
     * @param int $photoId - default null
     * @return array
     * @author Artem Sukharev
     */
    public function getShareHistory($user, $photoId = null)
    {
    	$query = $this->_db->select();
    	$query->from(self::$_dbShareHistoryTableName, '*');
    	$query->where('gallery_id = ?', $this->getId());
    	$query->where('user_id = ?', ($user instanceof Warecorp_User) ? $user->getId() : $user);
    	$query->order('share_date ASC');
    	$res = $this->_db->fetchAll($query);
    	return $res;
    }
    
    /**
     * return history of sharing by id
     * @param int $$historyId
     * @return array
     * @author Artem Sukharev
     */
    public function getShareHistoryById($historyId)
    {
        $query = $this->_db->select();
        $query->from(self::$_dbShareHistoryTableName, '*');
        $query->where('id = ?', $historyId);
        $res = $this->_db->fetchRow($query);
        return $res;
    }
    
    /**
     * delete history of sharing by id
     * @param int $$historyId
     * @return array
     * @author Artem Sukharev
     */
    public function deleteShareHistoryById($historyId)
    {
    	$where = $this->_db->quoteInto('id = ?', $historyId);
    	$this->_db->delete(self::$_dbShareHistoryTableName, $where);
    }
}
