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
 * @package    Warecorp
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */

/**
 *
 *
 */
class BaseWarecorp_Artifacts
{
    /**
     * Db connection object.
     *
     * @var object
     */
    public $_db;
    public $owner;
    public $owner_type;
    /**
     * Конструктор
     * @author Artem Sukharev
     */
    public function __construct($owner)
    {
        $this->_db = Zend_Registry::get("DB");
        $this->owner = $owner;
        if ( $owner instanceof Warecorp_User) {
            $this->owner_type = 'user';
        } elseif ( $owner instanceof Warecorp_Group_Simple) {
            $this->owner_type = 'group';
        } elseif ( $owner instanceof Warecorp_Group_Family) {
            $this->owner_type = 'group';
        }
    }
    
    /**
     * Unshare all artifacts from current group for artifact owner (group or user)
     * @param int $group_id
     * @author Artem Sukharev
     * @todo аншарить все артифакты, сделано только для документов
     */
    public function unshareAllArtifactsFromGroup($group_id)
    {
        $this->unshareAllDocumentsFromGroup($group_id);
        //$this->unshareAllGalleriesFromGroup($group_id);
        $this->unshareAllListsFromGroup($group_id);
        $this->unshareAllEventsFromGroup($group_id);
    }
    
    /**
     * Unshare all artifacts from current group for artifact owner (group or user)
     * @param int $group_id
     * @author Artem Sukharev
     * @todo аншарить все артифакты, сделано только для документов
     */
    public function unshareAllArtifactsFromUser($user_id)
    {
        $this->unshareAllDocumentsFromUser($user_id);
        $this->unshareAllGalleriesFromUser($user_id);
        $this->unshareAllListsFromUser($user_id);
        $this->unshareAllEventsFromUser($user_id);
    }
    
    //----------------------------------------------------
    //
    //  DOCUMENTS
    //
    //----------------------------------------------------
    /**
     *
     */
    public function createDocumentList()
    {
        return new Warecorp_Document_List($this->owner);;
    }
    
    /**
     *
     */
    public function createDocumentTree() {
        return new Warecorp_Document_Tree($this->owner);
    }
    
    /**
     * Return count of documents
     * @param int $excludeAdult - filter adult marked documents, or not
     * @package Warecorp_User $currentUser - current logined user (use for exclude adult)
     * @param array $privacy - privacy of document, array(1)|array(0)|array(0,1) == null
     * @return int
     * @author Artem Sukharev
     * @deprecated @see createDocumentList and Warecorp_Document_List::getCount()
     */
    public function getDocumentsCount($excludeAdult = 0, $currentUser = null, $folder = null, $privacy = null)
    {
        throw new Zend_Exception("Method 'getDocumentsCount' is deprecated. Use createDocumentList and Warecorp_Document_List::getCount()");
        /*
        $select = $this->_db->select();
        $select->from('view_documents__list', array('count' => new Zend_Db_Expr('count(id)')));
        $select->where('owner_type = ?', $this->owner_type);
        $select->where('owner_id = ?', $this->owner->id);

        if ($excludeAdult != 0 && $currentUser->adultFilter !=0 ){
            $select->where('((adult = 0) OR (adult = 1 AND creator_id = ?))', $currentUser->id);
        }

        $privacy = ($privacy === null) ? array(0,1) : $privacy;
        $select->where('private IN (?)', $privacy);

        $doc_count = $this->_db->fetchOne($select);
        return $doc_count;
        */
    }
    
    /**
     * Return list of documents
     * @param int $page
     * @param int $size
     * @param int $excludeAdult - filter adult marked documents, or not
     * @param Warecorp_User $currentUser - current logined user (use for exclude adult)
     * @param array $privacy - privacy of document, array(1)|array(0)|array(0,1) == null
     * @return array of Warecorp_Document_Item
     * @author Artem Sukharev
     * @deprecated @see createDocumentList and Warecorp_Document_List::getList()
     */
    public function getDocumentsList($page = null, $size = 50, $excludeAdult = 0, $currentUser = null, $folder = null, $privacy = null)
    {
        throw new Zend_Exception("Method 'getDocumentsList' is deprecated. Use createDocumentList and Warecorp_Document_List::getList()");
        /*
        $select = $this->_db->select();
        $select->from('view_documents__list', 'id');
        $select->where('owner_type = ?', $this->owner_type);
        $select->where('owner_id = ?', $this->owner->id);
        $select->order('original_name');

        if ($excludeAdult != 0 && $currentUser->adultFilter != 0){
            $select->where('((adult = 0) OR (adult = 1 AND creator_id = ?))', $currentUser->id);
        }

        if (is_null($folder)){
            $select->where('folder_id is null');
        } else {
            $select->where('folder_id = ?', $folder);
        }

        $privacy = ($privacy === null) ? array(0,1) : $privacy;
        $select->where('private IN (?)', $privacy);

        if ($page !== null) {
            $select->limitPage($page, $size);
        }

        $docs = $this->_db->fetchCol($select);
        foreach ($docs as &$doc) $doc = new Warecorp_Document_Item($doc);
        return $docs;
        */
    }

    /**
     * Return list of folders
     *
     * @return Warecorp_Document_FolderList
     * @author  Vitaly Targonsky
     */

    public function getDocumentsFoldersList()
    {
        return new Warecorp_Document_FolderList($this->owner);
    }
    
    /**
     * Return assoc array of values 'id', 'original_name as originalName'
     * @return array
     * @author Artem Sukharev
     * @deprecated @see createDocumentList and Warecorp_Document_List::getListAssoc()
     */
    public function getDocumentsListAssoc()
    {
        throw new Zend_Exception("Method 'getDocumentsListAssoc' is deprecated. Use createDocumentList and Warecorp_Document_List::getListAssoc()");
        /*
        $select = $this->_db->select()
                            ->from('zanby_documents__items', array('id', 'originalName' => 'original_name'))
                            ->where('owner_type = ?', $this->owner_type)
                            ->where('owner_id = ?', $this->owner->id);
        $docs = $this->_db->fetchPairs($select);

        return $docs;
        */
    }

    /**
     * Return documents shared to current group for artifact owner (group or user)
     * @param int $group_id
     * @return array of Warecorp_Document_Item
     * @author Artem Sukharev
     */
    public function getDocumentsListSharedToGroup($group_id)
    {
        $select = $this->_db->select();
        $select->from(array('zds' => 'zanby_documents__sharing'), 'zds.document_id')
               ->joininner(array('zdi' => 'zanby_documents__items'), 'zdi.id = zds.document_id')
               ->where('zds.owner_type =?', 'group')
               ->where('zds.owner_id =?', $group_id)
               ->where('zdi.owner_type =?', $this->owner_type)
               ->where('zdi.owner_id =?', $this->owner->getId());
        $docs = $this->_db->fetchCol($select);
        foreach ($docs as &$doc) $doc = new Warecorp_Document_Item($doc);
        return $docs;
    }
    
    /**
     * Return documents shared to current user for artifact owner (group or user)
     * @param int $user_id
     * @return array of Warecorp_Document_Item
     * @author Artem Sukharev
     */
    public function getDocumentsListSharedToUser($user_id)
    {
        $select = $this->_db->select();
        $select->from(array('zds' => 'zanby_documents__sharing'), 'zds.document_id')
               ->joininner(array('zdi' => 'zanby_documents__items'), 'zdi.id = zds.document_id')
               ->where('zds.owner_type =?', 'user')
               ->where('zds.owner_id =?', $user_id)
               ->where('zdi.owner_type =?', $this->owner_type)
               ->where('zdi.owner_id =?', $this->owner->getId());
        $docs = $this->_db->fetchCol($select);
        foreach ($docs as &$doc) $doc = new Warecorp_Document_Item($doc);
        return $docs;
    }
    
    /**
     * Unshare all documents from current group for artifact owner (group or user)
     * @param int $group_id
     * @author Artem Sukharev
     */
    public function unshareAllDocumentsFromGroup($group_id)
    {
        $docs = $this->getDocumentsListSharedToGroup($group_id);
        foreach ($docs as &$doc) {
            $doc->unshareDocument('group', $group_id);
        }
    }
    
    /**
     * Unshare all documents from current user for artifact owner (group or user)
     * @param int $user_id
     * @author Artem Sukharev
     */
    public function unshareAllDocumentsFromUser($user_id)
    {
        $docs = $this->getDocumentsListSharedToUser($user_id);
        foreach ($docs as &$doc) {
            $doc->unshareDocument('user', $user_id);
        }
    }
    //----------------------------------------------------
    //
    //  LISTS
    //
    //----------------------------------------------------
    /**
     * Return last created public list
     * @param int $size
     * @return array of Warecorp_List_Item
     * @author Vitaly Targonsky
     */
    public function getListsLast($size = null)
    {
        throw new Warecorp_Exception('OBSOLETE USED. Use class Warecorp_List_List');
//        
//        $select = $this->_db->select()
//                      ->from('zanby_lists__items', 'id')
//                      ->where('private = ?', 0)
//                      ->order('creation_date DESC');
//
//        if ($size !== null) {
//            $select->limitPage(0, $size);
//        }
//
//        $lists = $this->_db->fetchCol($select);
//        foreach ($lists as &$list) $list = new Warecorp_List_Item($list);
//        return $lists;
    }

    /**
     * Возвращает количество листов
     * @return int
     * @author Artem Sukharev
     */
    public function getListsCount()
    {
        throw new Warecorp_Exception('OBSOLETE USED. Use class Warecorp_List_List');
//        $select = $this->_db->select()
//                      ->from('view_lists__list', array('count' => new Zend_Db_Expr('count(id)')))
//                      ->where('owner_type = ?', $this->owner_type)
//                      ->where('owner_id = ?', $this->owner->getId());
//        $list_count = $this->_db->fetchOne($select);
//        return $list_count;
    }
    
    /**
     * Возвращает список листов
     * @param int $page
     * @param int $size
     * @return array of Warecorp_List_Item
     * @author Artem Sukharev
     */
    public function getListsList($page = null, $size = 50)
    {
        throw new Warecorp_Exception('OBSOLETE USED. Use class Warecorp_List_List');
//        $select = $this->_db->select()
//                           ->from('view_lists__list', 'id')
//                           ->where('owner_type = ?', $this->owner_type)
//                           ->where('owner_id = ?', $this->owner->getId());
//        if ($page !== null) {
//            $select->limitPage($page, $size);
//        }
//        $lists = $this->_db->fetchCol($select);
//        foreach ($lists as &$list) $list = new Warecorp_List_Item($list);
//        return $lists;
    }

    /**
     * @author Komarovski
     *
     * for content object Lists
     * $order:
     * 1 - Most Ranked
     * 2 - Most items to least
     * 3 - Newerst to olders
     */
    public function getListsListByTypeSorted($type = 0, $order = 1)
    {
        throw new Warecorp_Exception('OBSOLETE USED. Use class Warecorp_List_List');
//        return $this->getListsListByType($type);
    }

    /**
     * Return list of the lists, grouped by type
     * @param int $type
     * @param bool $with_shared
     * @return array of Warecorp_List_Item
     * @author Vitaly Targonsky
     */
    public function getListsListByType($type = 0, $with_shared = true, $with_watched = false)
    {
        throw new Warecorp_Exception('OBSOLETE USED. Use class Warecorp_List_List');
//        $select = $this->_db->select();
//        $fields = array('vli.id', 'vli.share', 'vli.watch');
//        
//        if ($with_shared) {
//            $shared_in = array(0,1);
//        } else {
//            $shared_in = array(0);
//        }
//        if ($with_watched) {
//            $watched_in = array(0,1);
//            if ($this->owner_type == 'user'){
//                $select->joinLeft(array('zli' => 'zanby_lists__imported'), 
//                                 $this->_db->quoteInto("zli.target_list_id = vli.id AND zli.source_list_id = vli.id AND zli.import_type='watch' AND user_id =?",$this->owner->getId())
//                                 );
//                $fields[] = 'zli.view_date';
//            }
//        } else {
//            $watched_in = array(0);
//        }
//
//        $select ->from(array('vli' => 'view_lists__list'), $fields)
//                ->where('vli.owner_type = ?', $this->owner_type)
//                ->where('vli.owner_id = ?', $this->owner->getId())
//                ->where('vli.share IN (?)', $shared_in)
//                ->where('vli.watch IN (?)', $watched_in)
//                ->order('vli.type_id');
//        $res = array();
//        
//        if ($type != 0) {
//            $select->where('type_id = ?', $type);
//            $lists = $this->_db->fetchAll($select);
//            foreach ($lists as $list) {
//                $_share = $list['share'];
//                $_watch = $list['watch'];
//                $_viewDate = isset($list['view_date']) ? $list['view_date'] : '';
//                $list = new Warecorp_List_Item($list['id']);
//                $list->setIsWatched($_watch)->setIsShared($_share)->setViewDate($_viewDate);
//                $res[] = $list;
//            }
//        } else {
//            $lists = $this->_db->fetchAll($select);
//            foreach ($lists as $list) {
//                $_share = $list['share'];
//                $_watch = $list['watch'];
//                $_viewDate = isset($list['view_date']) ? $list['view_date'] : '';
//                $list = new Warecorp_List_Item($list['id']);
//                $list->setIsWatched($_watch)->setIsShared($_share)->setViewDate($_viewDate);
//                $res[$list->getListType()][] = $list;
//            }
//        }
//        return $res;
    }
    
    /**
     * return assoc array id=>title
     * @param int $type
     * @param bool $with_shared
     * @return array
     * @author Vitaly Targonsky
     */
    public function getListsListByTypeAssoc($type = 0, $with_shared = true, $with_watched = false)
    {
        throw new Warecorp_Exception('OBSOLETE USED. Use class Warecorp_List_List');
//        if ($with_shared) {
//            $shared_in = array(0,1);
//        } else {
//            $shared_in = array(0);
//        }
//        if ($with_watched) {
//            $watched_in = array(0,1);
//        } else {
//            $watched_in = array(0);
//        }
//
//        $select = $this->_db->select()
//                            ->from('view_lists__list', array('id', 'title'))
//                            ->where('owner_type = ?', $this->owner_type)
//                            ->where('owner_id = ?', $this->owner->getId())
//                            ->where('share IN (?)', $shared_in)
//                            ->where('watch IN (?)', $watched_in)
//                            ->order('type_id');
//        if ($type != 0) {
//            $select->where('type_id = ?', $type);
//        }
//
//        $res = $this->_db->fetchPairs($select);
//        return $res;
    }
    
    /**
     * Возвращает список всех тагов для листов.
     * @param int $type
     * @return array
     * @author Vitaly Targonsky
     */
    public function getAllListTags()
    {
        throw new Warecorp_Exception('OBSOLETE USED. Use class Warecorp_List_List');
//        $list = new Warecorp_List_Item();
//        $EntityTypeId = $list->EntityTypeId;
//        unset($list);
//
//        $select = $this->_db->select()
//            ->from(array('ztd' => 'zanby_tags__dictionary'), array('ztd.id', 'ztd.name', 'count' => new Zend_Db_Expr('COUNT(ztr.id)')))
//            ->join(array('ztr' => 'zanby_tags__relations'), 'ztd.id = ztr.tag_id')
//            ->join(array('zli' => 'zanby_lists__items'), 'ztr.entity_id = zli.id')
//            ->where('zli.private = ?', 0)
//            ->where('ztr.entity_type_id = ?', $EntityTypeId)
//            ->where('ztr.status = ?', 'user')
//            ->group('ztd.id');
//        $res = $this->_db->fetchAll($select);
//        return $res;
    }
    
    
    /**
     * Return lists shared to current group for artifact owner (group or user)
     * @param int $group_id
     * @return array of Warecorp_List_Item
     * @author Vitaly Targonsky
     */
    public function getListsListSharedToGroup($group_id)
    {
        $select = $this->_db->select();
        $select->from(array('zls' => 'zanby_lists__sharing'), 'zls.list_id')
               ->joininner(array('zli' => 'zanby_lists__items'), 'zli.id = zls.list_id')
               ->where('zls.owner_type =?', 'group')
               ->where('zls.owner_id =?', $group_id)
               ->where('zli.owner_type =?', $this->owner_type)
               ->where('zli.owner_id =?', $this->owner->getId());
        $lists = $this->_db->fetchCol($select);
        foreach ($lists as &$list) $list = new Warecorp_List_Item($list);
        return $lists;
    }
    
    /**
     * Return lists shared to current user for artifact owner (group or user)
     * @param int $user_id
     * @return array of Warecorp_List_Item
     * @author Vitaly Targonsky
     */
    public function getListsListSharedToUser($user_id)
    {
        $select = $this->_db->select();
        $select->from(array('zls' => 'zanby_lists__sharing'), 'zls.list_id')
               ->joininner(array('zli' => 'zanby_lists__items'), 'zli.id = zls.list_id')
               ->where('zls.owner_type =?', 'user')
               ->where('zls.owner_id =?', $user_id)
               ->where('zli.owner_type =?', $this->owner_type)
               ->where('zli.owner_id =?', $this->owner->getId());
        $lists = $this->_db->fetchCol($select);
        foreach ($lists as &$list) $list = new Warecorp_List_Item($list);
        return $lists;
    }
    /**
     * Unshare all lists from current group for artifact owner (group or user)
     * @param int $group_id
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function unshareAllListsFromGroup($group_id)
    {
        $lists = $this->getListsListSharedToGroup($group_id);
        foreach ($lists as &$list) {
            $list->unshareList('group', $group_id);
        }
    }
    
    /**
     * Unshare all lists from current user for artifact owner (group or user)
     * @param int $user_id
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function unshareAllListsFromUser($user_id)
    {
        $lists = $this->getListsListSharedToUser($user_id);
        foreach ($lists as &$list) {
            $list->unshareList('user', $user_id);
        }
    }
    //----------------------------------------------------
    //
    //  GALLERIES
    //
    //----------------------------------------------------
    public function getPhotosLast($size = null)
    {
        throw new Zend_Exception('This method is depricated');
        /*
        $select = $this->_db->select()
                      ->from(array('zgp' => 'zanby_galleries__photos'),'zgp.id')
                      ->joininner(array('zgi' => 'zanby_galleries__items'), 'zgi.id = zgp.gallery_id')
                      ->where('zgi.private = ?', 0)
                      ->order('zgp.creation_date  DESC');

        if ($size !== null) {
            $select->limitPage(0, $size);
        }
        $photos = $this->_db->fetchCol($select);
        foreach ($photos as &$photo) $photo = new Warecorp_Photo_Item($photo);
        return $photos;
        */
    }
    
    /**
     * Return count of all galleries
     * @param $withoutShared -
     * @return int
     * @author Artem Sukharev
     */
    public function getGalleriesCount($withoutShared = false)
    {
        throw new Zend_Exception('This method is depricated');
        /*
        $select = $this->_db->select();
        $select->from('view_groups__gallery_list', array('count' => new Zend_Db_Expr('count(id)')));
        $select->where('owner_id = ?', $this->owner->getId());
        $select->where('owner_type =?', $this->owner_type);
        if ($withoutShared == true){
            $select ->where('share = 0');
        }

        $galleries_count = $this->_db->fetchOne($select);

        return $galleries_count;
        */
    }
    
    /**
     * Return list of gallery objects for user or group
     * @param int $page - if null - return all galleries
     * @param int $size
     * @return array of Warecorp_Photo_Gallery objects
     * @author Halauniou Yauhen
     */
    public function getGalleriesList($page = null, $size = 50, $withoutShared = false)
    {
        throw new Zend_Exception('This method is depricated');
        /*
        $select = $this->_db->select()
                       ->from('view_groups__gallery_list', 'id')
                       ->where('owner_id = ?', $this->owner->getId())
                       ->where('owner_type = ?', $this->owner_type);
        if ($withoutShared === true){
            $select->where('share = 0');
        }

        if ( $page !== null ) {
            $select->limitPage($page, $size);
        }
        $galleries = $this->_db->fetchCol($select);
        foreach ($galleries as &$gallery) $gallery = new Warecorp_Photo_Gallery($gallery);
        return $galleries;
        */
    }


    /**
     * return assoc of all galleries (id-title)
     * @return array of (ID, Title)
     * @author Halauniou Yauhen
     */
    public function getGalleriesListAssoc($withoutShared = false)
    {
        throw new Zend_Exception('This method is depricated');
        /*
        $select = $this->_db->select();
        $select->from('view_groups__gallery_list', array('id', 'title'));
        $select->where('owner_id = ?', $this->owner->getId());
        $select->where('owner_type = ?', $this->owner_type);
        if ($withoutShared == true){
            $select ->where('share = 0');
        }
        $galleries = $this->_db->fetchPairs($select);

        return $galleries;
        */
    }

     /**
     * return assoc of all shared galleries (id-title) for groups only
     * @return array of (ID, Title)
     * @author Halauniou Yauhen
     */

    public function getGroupSharedGalleriesListAssoc()
    {
        throw new Zend_Exception('This method is depricated');
        /*
        $select = $this->_db->select()
                       ->from('view_groups__gallery_list', array('id', 'title'))
                       ->where('owner_id = ?', $this->owner->getId())
                       ->where('owner_type = "group"')
                       ->where('share = 1');
        $galleries = $this->_db->fetchPairs($select);

        return $galleries;
        */
    }

     /**
     * return assoc of all shared galleries (id-title) for users only
     * @return array of (ID, Title)
     * @author Halauniou Yauhen
     */

    public function getUserSharedGalleriesListAssoc()
    {
        throw new Zend_Exception('This method is depricated');
        /*
        $select = $this->_db->select()
                       ->from('view_groups__gallery_list', array('id', 'title'))
                       ->where('owner_id = ?', $this->owner->getId())
                       ->where('owner_type = "user"')
                       ->where('share = 1');
        $galleries = $this->_db->fetchPairs($select);

        return $galleries;
        */
    }

    /**
     * Return size of all galleries for current owner
     *
     * @param string $unit (byte, kb, mb)
     * @return int size
     * @author Halauniou Yauhen
     */
    public function getGalleriesSize($unit = "byte")
    {
        throw new Zend_Exception('This method is depricated');
        /*
        $select = $this->_db->select()
                           ->from('view_groups__gallery_list', 'id')
                           ->where('owner_id = ?', $this->owner->getId())
                           ->where('owner_type = ?', $this->owner_type);

        $galleries = $this->_db->fetchAll($select);
        $size = 0;

        foreach ($galleries as &$gallery)
        {
            $gallery = new Warecorp_Photo_Gallery($gallery);
            $size += $gallery->getGallerySize();
        }
        unset ($galleries);

        if ($unit == "kb")    $size = $size/1024;
        if ($unit == "mb")    $size = $size/1024/1024;

        return floor($size);
        */
    }
    
    /**
     * Return galleries shared to current group for artifact owner (group or user)
     * @param int $group_id
     * @return array of Warecorp_Photo_Gallery
     * @author Artem Sukharev
     */
    public function getGalleriesListSharedToGroup($group_id)
    {
        throw new Zend_Exception('This method is depricated');
        /*
        $select = $this->_db->select();
        $select->from(array('zgs' => 'zanby_galleries__sharing'), 'zgs.gallery_id')
               ->joininner(array('zgi' => 'zanby_galleries__items'), 'zgi.id = zgs.gallery_id')
               ->where('zgs.owner_type =?', 'group')
               ->where('zgs.owner_id =?', $group_id)
               ->where('zgi.owner_type =?', $this->owner_type)
               ->where('zgi.owner_id =?', $this->owner->getId());
        $galleries = $this->_db->fetchCol($select);
        foreach ($galleries as &$gallery) $gallery = new Warecorp_Photo_Gallery($gallery);
        return $galleries;
        */
    }
    
    /**
     * Return galleries shared to current user for artifact owner (group or user)
     * @param int $user_id
     * @return array of Warecorp_Document_Item
     * @author Artem Sukharev
     */
    public function getGalleriesListSharedToUser($user_id)
    {
        throw new Zend_Exception('This method is depricated');
        /*
        $select = $this->_db->select();
        $select->from(array('zgs' => 'zanby_galleries__sharing'), 'zgs.gallery_id')
               ->joininner(array('zgi' => 'zanby_galleries__items'), 'zgi.id = zgs.gallery_id')
               ->where('zgs.owner_type =?', 'user')
               ->where('zgs.owner_id =?', $user_id)
               ->where('zgi.owner_type =?', $this->owner_type)
               ->where('zgi.owner_id =?', $this->owner->getId());
        $galleries = $this->_db->fetchCol($select);
        foreach ($galleries as &$gallery) $gallery = new Warecorp_Photo_Gallery($gallery);
        return $galleries;
        */
    }
    
    /**
     * Unshare all galleries from current group for artifact owner (group or user)
     * @param int $group_id
     * @author Artem Sukharev
     */
    public function unshareAllGalleriesFromGroup($group_id)
    {
        $galleries = $this->getGalleriesListSharedToGroup($group_id);
        foreach ($galleries as &$gallery) {
            $gallery->unshareGalleryFromGroup($group_id);
        }
    }
    
    /**
     * Unshare all galleries from current user for artifact owner (group or user)
     * @param int $user_id
     * @author Artem Sukharev
     */
    public function unshareAllGalleriesFromUser($user_id)
    {
        $galleries = $this->getGalleriesListSharedToUser($user_id);
        foreach ($galleries as &$gallery) {
            $gallery->unshareGalleryFromUser($user_id);
        }
    }

    //----------------------------------------------------
    //
    //  EVENTS
    //
    //----------------------------------------------------

    /**
     * List user events
     * @author Ivan Meleshko
     */
    public function getEventsListByUser($user_id, $start_date = null)
    {
        $sql = $this->_db->select();
        $sql->from(array('eu' => 'zanby_event__users'), array('id', 'owner_type', 'owner_id', 'creator_id', 'title', 'notes', 'event_type', 'dtstart', 'dtend', 'timezone', 'rrule', 'private'))
            ->joininner(array('ei' => 'zanby_event__items'), 'ei.id = eu.event_id')
            ->where('eu.user_id = ?', $user_id);

        $events = $this->_db->fetchAll($sql);
        foreach ($events as &$event) $event = new Warecorp_Event_Item($event, $user_id);
        return $events;
    }


    /**
     * Return count of events
     * @package Warecorp_User $currentUser - current logined user (use for exclude adult)
     * @param array $privacy - privacy of document, array(1)|array(0)|array(0,1) == null
     * @return int
     * @author Ivan Meleshko
     * @
     */
    public function getEventsCount($currentUser = null, $privacy = null)
    {
        $select = $this->_db->select();
        $select->from('zanby_event__items', array('count' => new Zend_Db_Expr('count(id)')));
        $select->where('owner_type = ?', $this->owner_type);
        $select->where('owner_id = ?', $this->owner->getId());

        $privacy = ($privacy === null) ? array(0,1) : $privacy;
        $select->where('private IN (?)', $privacy);

        $event_count = $this->_db->fetchOne($select);
        return $event_count;
    }



    /**
     * Return list of events
      * @param int $currentUser - currentUser info
      * @param array $type - privacy of document, array(1)|array(0)|array(0,1) == null
     * @param array $privacy - privacy of document, array(1)|array(0)|array(0,1) == null
     * @return array of Warecorp_Event_Item
     * @author Ivan Meleshko
     * @todo OR DISTINCT
     */
    public function getEventsList ( $currentUser, $type = null, $shared = null, $privacy = null )
    {
        $eventsList = new Warecorp_Event_List( );
        $eventsList->returnAsAssoc( false )->setOwnerType( 'user' )->setOwnerId( $currentUser->getId() )->setOwner( $currentUser );
        if ( $type !== null ) {
            if ( true == (boolean) $type )
                $eventsList->setGetExpiried( true );
        }
        if ( $shared !== null ) {
            if ( $shared )
                $eventsList->setIsShared( true );
        }
        $privacy = ( $privacy === null ) ? array(
            0 , 
            1) : $privacy;
        $eventsList->setPrivacy( $privacy );
        return $eventsList->getList();
    }

    /**
     * Return events shared to current group for artifact owner (group or user)
     * @param int $group_id
     * @return array of Warecorp_Event_Item
     * @author Ivan Meleshko
     */
    public function getEventsListSharedToGroup($group_id)
    {
        $select = $this->_db->select();
        $select->from(array('zes' => 'zanby_event__sharing'), 'zes.event_id')
               ->joininner(array('zei' => 'zanby_event__items'), 'zei.id = zes.event_id')
               ->where('zes.owner_type =?', 'group')
               ->where('zes.owner_id =?', $group_id)
               ->where('zei.owner_type =?', $this->owner_type)
               ->where('zei.owner_id =?', $this->owner->getId());
        $events = $this->_db->fetchCol($select);
        foreach ($events as &$event) $event = new Warecorp_Event_Item($event);
        return $events;
    }



    /**
     * Return event shared to current user for artifact owner (group or user)
     * @param int $user_id
     * @return array of Warecorp_Event_Item
     * @author Ivan Meleshko
     */
    public function getEventsListSharedToUser($user_id)
    {
        $select = $this->_db->select();
        $select->from(array('zes' => 'zanby_event__sharing'), 'zes.event_id')
               ->joininner(array('zei' => 'zanby_event__items'), 'zei.id = zes.event_id')
               ->where('zes.owner_type =?', 'user')
               ->where('zes.owner_id =?', $user_id)
               ->where('zei.owner_type =?', $this->owner_type)
               ->where('zei.owner_id =?', $this->owner->getId());
        $events = $this->_db->fetchCol($select);
        foreach ($events as &$event) $event = new Warecorp_Event_Item($event);
        return $events;
    }

    /**
     * Return events by input ids
     * @param array $events
     * @return array of Warecorp_Event_Item
     * @author Eugene Kirdzei
     */
    public function getEventsListByIds($events = array())
    {
        if (is_array($events)) {
           foreach ($events as &$event) $event = new Warecorp_Event_Item($event);
        }
        return $events;
    }

    /**
     * Unshare all events from current group for artifact owner (group or user)
     * @param int $group_id
     * @author Artem Sukharev
     * @todo Реализовать
     */
    public function unshareAllEventsFromGroup($group_id)
    {
    }
    
    /**
     * Unshare all events from current user for artifact owner (group or user)
     * @param int $user_id
     * @author Artem Sukharev
     * @todo Реализовать
     */
    public function unshareAllEventsFromUser($user_id)
    {
    }

    //
    //  Message Board
    //
    //----------------------------------------------------

    /**
     * get list of boards
     * @return array of objects
     */
    public function getBoardsList($page = 1, $size = 10)
    {
        $where = $this->_db->quoteInto('group_id=?', $this->owner->getId());
        $sql = $this->_db->select()->from('zanby_forum__items', 'id')->where($where)->limitPage($page, $size);
        $boards = $this->_db->fetchCol($sql);
        foreach($boards as &$board){
            $board = new Warecorp_Forum_Item($board);
            $board->setGroup($this->owner);
        }
        return $boards;
    }

    /**
     * get boards number
     * @return int
     */
    public function getBoardsNum()
    {
        $where = $this->_db->quoteInto('forum_id=?', $this->owner->getId());
        $sql = $this->_db->select()->from('zanby_forum__items', new Zend_Db_Expr('COUNT(id)'))->where($where);
        return $this->_db->fetchOne($sql);
    }

}
