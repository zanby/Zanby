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
 * @package Warecorp_Video_Gallery
 * @author Yury Zolotarsky
 * @version 1.0
 */
class BaseWarecorp_Video_Gallery_Abstract extends Warecorp_Data_Entity
{

    public static $_dbTableName = 'zanby_videogalleries__items';

    public static $_dbShareTableName = 'zanby_videogalleries__sharing';

    public static $_dbShareHistoryTableName = 'zanby_videogalleries__sharing_history';

    public static $_dbWatchingTableName = 'zanby_videogalleries__watching';

    public static $_dbUserViewsTableName = 'zanby_videogalleries__views';

    public static $_dbImportTableName = 'zanby_videogalleries__import';

    public static $_dbViewName = 'view_videogallery__list';

    public static $_dbPublishTableName = 'zanby_videogalleries__publishing';

    private $id;

    private $ownerId;

    private $ownerType;

    private $owner;

    private $creatorId;

    private $creator;

    private $title;

    private $description;

    private $createDate;

    private $private;

    private $updateDate;

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


    public function getId()
    {
        return $this->id;
    }

    public function setId($newVal)
    {
        $this->id = $newVal;
        return $this;
    }

    public function getIsCreated()
    {
        return $this->isCreated;
    }

    public function setIsCreated($newVal = 1)
    {
        if ($newVal == 0)
            $this->isCreated = $newVal;
        else
            $this->isCreated = 1;
        return $this;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function setOwnerId($newVal)
    {
        $this->ownerId = $newVal;
        return $this;
    }

    public function getOwnerType()
    {
        return $this->ownerType;
    }

    public function setOwnerType($newVal)
    {
        $this->ownerType = $newVal;
        return $this;
    }

    public function getCreatorId()
    {
        return $this->creatorId;
    }

    public function setCreatorId($newVal)
    {
        $this->creatorId = $newVal;
        return $this;
    }

    public function getCreator()
    {
        if ( $this->creator === null ) $this->creator = new Warecorp_User('id', $this->getCreatorId());
        return $this->creator;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($newVal)
    {
        $this->title = $newVal;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($newVal)
    {
        $this->description = $newVal;
        return $this;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function setCreateDate($newVal)
    {
        $this->createDate = $newVal;
        return $this;
    }

    public function getPrivate()
    {
        return $this->private;
    }

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

    public function getOwner()
    {
        if ( $this->owner === null ) {
            if ( $this->getOwnerType() == 'user' ) $this->owner = new Warecorp_User('id', $this->getOwnerId());
            else $this->owner = Warecorp_Group_Factory::loadById($this->getOwnerId());
        }
        return $this->owner;
    }

    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    public function setUpdateDate($newVal)
    {
        $this->updateDate = $newVal;
        if ( $this->updateDate == "0000-00-00 00:00:00" ) {
            $this->updateDate = $this->getCreateDate();
        }
        return $this;
    }

    public function getSize($unit = Warecorp_Video_Enum_SizeUnit::BYTE)
    {
        /*
        $size = 0;
        $videoList = $this->getVideos()->getList();
        if (sizeof($videoList) != 0) {
            foreach ($videoList as $video) {
                $size = $size + $video->getSize(Warecorp_Video_Enum_SizeUnit::BYTE);
            }
        }
        switch ($unit) {
            case Warecorp_Video_Enum_SizeUnit::BYTE:
                return $size;
                break;
            case Warecorp_Video_Enum_SizeUnit::KBYTE:
                return $size / 1024;
                break;
            case Warecorp_Video_Enum_SizeUnit::MBYTE:
                return $size / 1024 / 1024;
                break;
        }
        */
        return 0;
    }

    public function setSize($newVal)
    {
        $this->size = $newVal;
        return $this;
    }

    public function getVideos()
    {
        return Warecorp_Video_List_Factory::load($this);
    }

    public static function isGalleryExists($galleryId)
    {
        $db = Zend_Registry::get('DB');
        $query = $db->select();
        $query->from(self::$_dbTableName, new Zend_Db_Expr('COUNT(*)'));
        $query->where('id = ?', $galleryId);
        return (boolean) $db->fetchOne($query);
    }

    public function isWatched($user)
    {
        if ( !($user instanceof Warecorp_User) ) $user = new Warecorp_User('id', $user);

        $query = $this->_db->select();
        $query->from(self::$_dbWatchingTableName, new Zend_Db_Expr('COUNT(*)'));
        $query->where('gallery_id = ?', $this->getId());
        $query->where('user_id = ?', $user->getId());

        return (boolean) $this->_db->fetchOne($query);
    }

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
            $where[] = $this->_db->quoteInto('action_type = ?', Warecorp_Video_Enum_ImportActionType::WATCH_GALLERY);
            $where = join(' AND ', $where);
            $rows_affected = $this->_db->delete(self::$_dbImportTableName, $where);
            return true;
        }
        return false;
    }

    public function stopAllWatch()
    {
        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $rows_affected = $this->_db->delete(self::$_dbWatchingTableName, $where);
        /**
         * remove import information
         */
        $where = array();
        $where[] = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $where[] = $this->_db->quoteInto('action_type = ?', Warecorp_Video_Enum_ImportActionType::WATCH_GALLERY);
        $where = join(' AND ', $where);
        $rows_affected = $this->_db->delete(self::$_dbImportTableName, $where);
        return true;
    }

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

//         $items = $this->_db->fetchPairs($query);
//         if (!$items) return array();
//         return $items;
    }

    public function getUsersSharedWith()
    {
        $query = $this->_db->select();
        $query->from(self::$_dbShareTableName, array(self::$_dbShareTableName.'.owner_id', 'zua.login'));
        $query->join(array('zua' => 'zanby_users__accounts'), self::$_dbShareTableName.'.owner_id=zua.id');
        $query->where(self::$_dbShareTableName.'.gallery_id = ?', $this->getId());
        $query->where(self::$_dbShareTableName.'.owner_type = ?', 'user');

        $items = $this->_db->fetchPairs($query);
        if (!$items) return array();
        return $items;
    }

    /**
     * @param Warecorp_User|Warecorp_Group_Base
     * @param boolean $shareWithFamily
     * @return boolean
     * @throw Zend_Exception
     */
    public function share($context, $shareWithFamily = false)
    {
        if ( !($context instanceof Warecorp_User) && !($context instanceof Warecorp_Group_Base) ) {
           throw new Zend_Exception('Incorrect Context');
        }

        if ( $shareWithFamily && !Warecorp_Share_Entity::isShareExists($context->getId(), $this->getId(), $this->EntityTypeId)) {
                return Warecorp_Share_Entity::addShare($context->getId(), $this->getId(), $this->EntityTypeId);
        }
        else {
            if ( !$this->isShared($context) ) {
                $data = array();
                $data['gallery_id']     = $this->getId();
                $data['owner_id']       = $context->getId();
                $data['owner_type']     = $context->EntityTypeName;
                $data['create_date']    = new Zend_Db_Expr('NOW()');
                $this->_db->insert(self::$_dbShareTableName, $data);
                return true;
            }
        }
        return false;
    }

    /**
     * @param Warecorp_User|Warecor_Group_Base $context
     * @param boolean $$shareWithFamily
     * @return boolean
     * @throw Zend_Exception
     */
    public function unshare($context, $shareWithFamily = false)
    {
        if ( !($context instanceof Warecorp_User) && !($context instanceof Warecorp_Group_Base) ) {
           throw new Zend_Exception('Incorrect Context');
        }

        if ( $shareWithFamily ){
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

    public static function setGalleryViewed($gallery, $user)
    {
        if ( !($gallery instanceof Warecorp_Video_Gallery_Abstract) ) {
           $gallery = Warecorp_Video_Gallery_Factory::loadById($gallery);
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

    public static function isGalleryUpdated($gallery, $user)
    {
        if ( !($gallery instanceof Warecorp_Video_Gallery_Abstract) ) {
           $gallery = Warecorp_Video_Gallery_Factory::loadById($gallery);
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


    public function copy($gallery)
    {
        if ( !($gallery instanceof Warecorp_Video_Gallery_Abstract) ) return false;

        $videosList = $this->getVideos()->getList();
        if ( sizeof($videosList) != 0 ) {
            foreach ( $videosList as &$video ) {
                $video->copy($gallery);
            }
        }
    }

    public function publish($gallery)
    {
        if ( !($gallery instanceof Warecorp_Video_Gallery_Abstract) ) return false;

        $data = array();
        $data['gallery_from']            = $this->getId();
        $data['gallery_to']              = $gallery->getId();
        $this->_db->insert(self::$_dbPublishTableName, $data);
    }

    public function saveImportHistory($user, $actionType, $relatedGalleryId, $videoId = null)
    {
        if ( $user instanceof Warecorp_User ) $user = $user->getId();
        if ( !Warecorp_Video_Enum_ImportActionType::isIn($actionType) ) throw new Zend_Exception('Incorrect action type');

        $data = array();
        $data['user_id']               = $user;
        $data['gallery_id']            = $this->getId();
        $data['video_id']              = $videoId;
        $data['action_type']           = $actionType;
        $data['action_date']           = new Zend_Db_Expr('NOW()');
        $data['related_gallery_id']    = $relatedGalleryId;
        $this->_db->insert(self::$_dbImportTableName, $data);
    }

    public function getImportHistory($user, $videoId = null)
    {
        if ( $user instanceof Warecorp_User ) $user = $user->getId();

        $query = $this->_db->select();
        $query->from(self::$_dbImportTableName, '*');
        $query->where('user_id = ?', $user);
        $query->where('gallery_id = ?', $this->getId());
        if ( $videoId === null ) $query->where('video_id IS NULL');
        else {
           $query->where('(video_id = ?', $videoId);
           $query->orwhere('video_id IS NULL)');
        }
        $query->order(array('video_id DESC', 'action_date DESC'));
        $query->limitPage(1, 1);
        $res = $this->_db->fetchAll($query);
        if ( $res ) {
           $res = $res[0];
           if ( $res['related_gallery_id'] ) $res['related_gallery'] = Warecorp_Video_Gallery_Factory::loadById($res['related_gallery_id']);
           return $res;
        } else return null;
    }

    public function saveShareHistory($user, $strRecipient, $ownerType, $ownerIds, $videoId = null )
    {
        $data = array();
        $data['gallery_id']    = $this->getId();
        $data['video_id']      = ($videoId === null) ? new Zend_Db_Expr('NULL') : floor($videoId);
        $data['user_id']       = ($user instanceof Warecorp_User) ? $user->getId() : $user;
        $data['recipients']    = $strRecipient;
        $data['share_date']    = new Zend_Db_Expr('NOW()');
        $data['owner_type']    = $ownerType;
        $data['owner_ids']     = serialize($ownerIds);

        $this->_db->insert(self::$_dbShareHistoryTableName, $data);
    }

    public function isShareHistoryExists()
    {
        $query = $this->_db->select();
        $query->from(self::$_dbShareHistoryTableName, new Zend_Db_Expr('COUNT(gallery_id)'));
        $query->where('gallery_id = ?', $this->getId());
        return (boolean) $this->_db->fetchOne($query);
    }

    public function getShareHistory($user, $videoId = null)
    {
        $query = $this->_db->select();
        $query->from(self::$_dbShareHistoryTableName, '*');
        $query->where('gallery_id = ?', $this->getId());
        $query->where('user_id = ?', ($user instanceof Warecorp_User) ? $user->getId() : $user);
        $query->order('share_date ASC');
        $res = $this->_db->fetchAll($query);
        return $res;
    }

    public function getShareHistoryById($historyId)
    {
        $query = $this->_db->select();
        $query->from(self::$_dbShareHistoryTableName, '*');
        $query->where('id = ?', $historyId);
        $res = $this->_db->fetchRow($query);
        return $res;
    }

    public function deleteShareHistoryById($historyId)
    {
        $where = $this->_db->quoteInto('id = ?', $historyId);
        $this->_db->delete(self::$_dbShareHistoryTableName, $where);
    }

    public function getActiveVideoProcesses()
    {
        $query = $this->_db->select();
        $query->from(array('zvp' => Warecorp_Video_Process::$_dbTableName), array('zvv.title', 'zvp.status'));
        $query->join(array('zvv' => Warecorp_Video_Abstract::$_dbTableName), 'zvp.video_id = zvv.id');
        $query->join(array('zvi' => Warecorp_Video_Gallery_Abstract::$_dbTableName), 'zvv.gallery_id = zvi.id');
        $query->where('zvi.id = ?', $this->getId());
        $query->where('zvp.status <> ?', Warecorp_Video_Enum_ProcessStatus::COMPLETED);
        $res = $this->_db->fetchAll($query);
        return $res;
    }
}
