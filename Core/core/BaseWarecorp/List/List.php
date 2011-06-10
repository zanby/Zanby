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
 * @author Ivan Khmurchik
 * @version 1.0
 */
class BaseWarecorp_List_List extends Warecorp_Abstract_List
{
    /**
     * owner of contact list
     */
    private $_owner;
    private $_ownerType;
    private $_privacy;

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
        }
    }
    
    public function getPrivacy() 
    {
        if ( $this->_privacy === null ) return array(0,1);
        else return $this->_privacy;
    }
    public function setPrivacy( $value ) 
    {
        if ( !is_array($value) ) $value = array(0);
        $this->_privacy = $value;
        return $this;
    }
    
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('zli' => 'zanby_lists__items'), new Zend_Db_Expr('COUNT(zli.id)'));  
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zli.owner_type = ?', $this->getOwnerType());
        $query->where('zli.owner_id = ?', $this->getOwner()->getId());
        
        if ( $this->getIncludeIds() ) $query->where('zli.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zli.id NOT IN (?)', $this->getExcludeIds());
        
        return $this->_db->fetchOne($query);
    	
    }
    
    public function getList()
    {
            	
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zli.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zli.title' : $this->getAssocValue();
            $query->from(array('zli' => 'zanby_lists__items'), $fields);  
        } else {
            $query->from(array('zli' => 'zanby_lists__items'), 'zli.id');
        }
        if ( $this->getWhere() ) $query->where($this->getWhere());
        
        $query->where('zli.owner_type = ?', $this->getOwnerType());
        $query->where('zli.owner_id = ?', $this->getOwner()->getId());
        
        if ( $this->getIncludeIds() ) $query->where('zli.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zli.id NOT IN (?)', $this->getExcludeIds());
        
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
            foreach ( $items as &$item ) $item = new Warecorp_List_Item($item);
        }
        return $items;
    }
    

    public function getOwner()
    {
    	return $this->_owner;
    }
    
    public function getOwnerType()
    {
        return $this->_ownerType;
    }
    
    

    /**
	 * Return last created public list
	 * @param int $size
	 * @return array of Warecorp_List_Item
	 * @author Vitaly Targonsky
	 */
    public function getListsLast($size = null, $with_copies = true)
	{
        $select = $this->_db->select();
        $select->from(array('zli' => 'zanby_lists__items'), 'zli.id')
               ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = zli.owner_id AND owner_type = 'group'")
               ->joinLeft(array('zua' => 'zanby_users__accounts'), "zua.id = zli.owner_id AND owner_type = 'user'")
               ->where('zli.private = ?', 0)
               ->where('(zgi.private = 0 OR ISNULL(zgi.private))')
               ->where('(zua.status = ? OR ISNULL(zua.status))', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);
        if (!$with_copies) {
            $select->where('zli.title NOT LIKE "Copy of %"');
        }       
        $select->order('zli.creation_date DESC');

        if ($size !== null) {
            $select->limitPage(0, $size);
        }
    	$lists = $this->_db->fetchCol($select);
    	foreach ($lists as &$list) $list = new Warecorp_List_Item($list);
    	return $lists;
	}
    

    /**
	 * Возвращает количество листов
	 * @return int
	 * @author Artem Sukharev
	 */
    public function getListsCount()
	{
        $select = $this->_db->select()
                      ->from('view_lists__list', array('count' => new Zend_Db_Expr('count(id)')))
                      ->where('owner_type = ?', $this->_ownerType)
                      ->where('owner_id = ?', $this->_owner->getId());
        $list_count = $this->_db->fetchOne($select);
        return $list_count;
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
        $select = $this->_db->select()
                           ->from('view_lists__list', 'id')
                           ->where('owner_type = ?', $this->_ownerType)
                           ->where('owner_id = ?', $this->_owner->getId());
        if ($page !== null) {
            $select->limitPage($page, $size);
        }
    	$lists = $this->_db->fetchCol($select);
    	foreach ($lists as &$list) $list = new Warecorp_List_Item($list);
    	return $lists;
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
    public function getListsListByTypeSorted ($type = 0, $order = 1)
    {
        if (is_numeric($order)) {
            switch ($order) {
                case 1:
                    $this->setOrder('rank_count DESC');
                    break;
                case 2:
                    $this->setOrder('records_count DESC');
                    break;
                case 3:
                    $this->setOrder('last_update DESC');
                    break;
                default:
                    $this->setOrder('rank_count DESC');
                    break;
            }
        }
        $user = Zend_Registry::get('User');
        $objListAccessManager = Warecorp_List_AccessManager_Factory::create();
        if ($objListAccessManager->canViewPrivateLists($this->_owner, $user)) {
            return $this->setPrivacy(array(0 , 1))->getListsListByType($type, true, true);
        } else {
            return $this->setPrivacy(0)->getListsListByType($type, false, false);
        }
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
        $select = $this->_db->select();
        $fields = array('vli.id', 'vli.share', 'vli.watch');
        
        if ($with_shared) {
            $shared_in = array(0,1);
        } else {
            $shared_in = array(0);
        }
        if ($with_watched) {
            $watched_in = array(0,1);
            if ($this->_ownerType == 'user'){
                $select->joinLeft(array('zli' => 'zanby_lists__imported'), $this->_db->quoteInto("zli.target_list_id = vli.id AND zli.source_list_id = vli.id AND zli.import_type='watch' AND user_id =?", $this->_owner->getId()), array());
                $fields[] = 'zli.view_date';
            }
        } else {
            $watched_in = array(0);
        }

        $select ->from(array('vli' => 'view_lists__list'), $fields)
                ->where('vli.owner_type = ?', $this->_ownerType)
                ->where('vli.owner_id = ?', $this->_owner->getId())
                ->where('vli.share IN (?)', $shared_in)
                ->where('vli.watch IN (?)', $watched_in)
                ->order(array('vli.type_id', 'vli.share'));
        $res = array();
        
        if ($this->getOrder()) {
        	$select->order($this->getOrder());
        }
        $select->where('vli.private IN (?)', $this->getPrivacy());

        if ($type != 0) {
            $select->where('type_id = ?', $type);
            $lists = $this->_db->fetchAll($select);
            foreach ($lists as $list) {
                $_share = $list['share'];
                $_watch = $list['watch'];
                $_viewDate = isset($list['view_date']) ? $list['view_date'] : '';
                
                if (isset($processedList[$list['id']])) continue;
                $processedList[$list['id']] = 1;
                
                $list = new Warecorp_List_Item($list['id']);
                $list->setIsWatched($_watch)->setIsShared($_share)->setViewDate($_viewDate);
                $res[] = $list;
            }
        } else {
            $lists = $this->_db->fetchAll($select);
            foreach ($lists as $list) {
                $_share = $list['share'];
                $_watch = $list['watch'];
                $_viewDate = isset($list['view_date']) ? $list['view_date'] : '';

                if (isset($processedList[$list['id']])) continue;
                $processedList[$list['id']] = 1;

                
                $list = new Warecorp_List_Item($list['id']);
                $list->setIsWatched($_watch)->setIsShared($_share)->setViewDate($_viewDate);
                $res[$list->getListType()][] = $list;
            }
        }
    	return $res;
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
        if ($with_shared) {
            $shared_in = array(0,1);
        } else {
            $shared_in = array(0);
        }
        if ($with_watched) {
            $watched_in = array(0,1);
        } else {
            $watched_in = array(0);
        }

        $select = $this->_db->select()
                            ->from('view_lists__list', array('id', 'title'))
                            ->where('owner_type = ?', $this->_ownerType)
                            ->where('owner_id = ?', $this->_owner->getId())
                            ->where('share IN (?)', $shared_in)
                            ->where('watch IN (?)', $watched_in)
                            ->order('type_id');
        if ($type != 0) {
            $select->where('type_id = ?', $type);
        }

        if ($this->getOrder()) {
            $select->order($this->getOrder());
        }
        $select->where('private IN (?)', $this->getPrivacy());
        
        $res = $this->_db->fetchPairs($select);
        return $res;
    }
	
    /**
	 * Возвращает список всех тагов для листов.
	 * @param int $type
	 * @return array
	 * @author Vitaly Targonsky
	 */
    public function getAllListTags()
    {

        $list = new Warecorp_List_Item();
        $EntityTypeId = $list->EntityTypeId;
        unset($list);

		if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
		{
			$taglist = new Warecorp_List_Tags();

			$taglist->addFilter("entity_type", $EntityTypeId);
			$taglist->setOwnerTypeId($this->_ownerType);
			$taglist->setOwnerId($this->_owner->getId());
			$taglist->setTagStatus('user');
			$res = $taglist->getList();
		} else {

	//        $select = $this->_db->select()
	//            ->from(array('ztd' => 'zanby_tags__dictionary'), array('ztd.id', 'ztd.name', 'count' => new Zend_Db_Expr('COUNT(ztr.id)'))
	//            ->join(array('ztr' => 'zanby_tags__relations'), 'ztd.id = ztr.tag_id')
	//            ->join(array('zli' => 'zanby_lists__items'), 'ztr.entity_id = zli.id')
	//            ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = zli.owner_id AND owner_type = 'group'")
	//            ->where('(zgi.private = 0 OR ISNULL(zgi.private))')
	//            ->where('zli.private = ?', 0)
	//            ->where('ztr.entity_type_id = ?', $EntityTypeId)
	//            ->where('ztr.status = ?', 'user')
	//            ->group('ztd.id');

			$select = $this->_db->select()
				->from(array('ztd' => 'zanby_tags__dictionary'), array('ztd.id', 'ztd.name'))
				->join(array('ztr' => 'zanby_tags__relations'), 'ztd.id = ztr.tag_id', array('count' => new Zend_Db_Expr('COUNT(ztr.id)')))
				->join(array('vll' => 'view_lists__list'), 'ztr.entity_id = vll.id', array())
				->where('ztr.entity_type_id = ?', $EntityTypeId)
				->where('ztr.status = ?', 'user')
				->where('vll.owner_type = ?', $this->_ownerType)
				->where('vll.owner_id = ?', $this->_owner->getId())
				->where('vll.share IN (?)', $this->getPrivacy())
				->where('vll.watch IN (?)', $this->getPrivacy())
				->where('vll.private IN (?)', $this->getPrivacy())
				->group('ztd.id');
			$res = $this->_db->fetchAll($select);
		}
        return $res;
    }
	
    /**
	 * Unshare all lists from current group for artifact owner (group or user)
	 * @param int $group_id
	 * @author Artem Sukharev
	 * @todo Реализовать
	 */
	public function unshareAllListsFromGroup($group_id)
	{
        $this->_db->delete('zanby_documents__sharing',
        $this->_db->quoteInto('owner_id = ? ', 'group').
        $this->_db->quoteInto('AND owner_type = ? ', $group_id));
	}
	
	/**
	 * Unshare all lists from current user for artifact owner (group or user)
	 * @param int $user_id
	 * @author Artem Sukharev
	 * @todo Реализовать
	 */
	public function unshareAllListsFromUser($user_id)
	{
        $this->_db->delete('zanby_documents__sharing',
        $this->_db->quoteInto('owner_id = ? ', 'user').
        $this->_db->quoteInto('AND owner_type = ? ', $user_id));
	}

    /**
     * mailing list id
     * 
     * @param newVal
     */
    public function setListId($newVal)
    {
    	$this->_listId = $newVal;
    }

    /**
     * return system who will list for events
     * @return Warecorp_List_Item
     * @author Artem Sukharev
     */
    public function getSystemWhoWillList()
    {
        $http_context = ( defined('HTTP_CONTEXT') && HTTP_CONTEXT ) ? HTTP_CONTEXT : NULL;
        if ($http_context === NULL) return NULL;

        $select = $this->_db->select();
        $select ->from('zanby_lists__items', 'id')
                ->where('system_who_will_for = ?', $http_context);

        $listID = $this->_db->fetchOne($select);

        if ( $listID ) {
            $list = new Warecorp_List_Item($listID);
            return $list;
        } else {
            // @TODO: create system who will list
        }

        return NULL;
    }
}
