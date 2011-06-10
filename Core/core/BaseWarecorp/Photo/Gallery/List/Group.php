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
 * @package Warecorp_Photo_Gallery_List
 * @author Artem Sukharev
 * @version 1.0
 */
class BaseWarecorp_Photo_Gallery_List_Group extends Warecorp_Photo_Gallery_List_Abstract
{
	/**
	 * id of group
	 */
	private $groupId;
	private $isCustomCondition;
	private $withComments;

    /**
     * Constructor
     * @author Artem Sukharev
     */
	function __construct($groupId)
	{
		$this->setGroupId($groupId);
		$this->isCustomCondition = false;
		parent::__construct();
	}
	
    public function setWithComments($value = null)
    {
		if ($value === null) {
			$this->withComments = $this->getUserId();
			return $this;
		}
		$this->withComments = $value;
    	return $this;
    }
    
    public function getWithComments()
    {
    	return $this->withComments;
    }

    public function setCustomCondition($value = true)
    {
    	$this->isCustomCondition = $value;
    	return $this;
    }

    public function getCustomCondition()
    {
    	return $this->isCustomCondition;
    }

	/**
	 * return list of all items
	 * @return array of objects
	 * @author Artem Sukharev
     * @todo getPrivacy
	 */
	public function getList()
	{
        $query = $this->_db->select()->distinct();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'view.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'view.title' : $this->getAssocValue();
            $query->from(array('view' => Warecorp_Photo_Gallery_Abstract::$_dbViewName), $fields);  
        } else {
            $query->from(array('view' => Warecorp_Photo_Gallery_Abstract::$_dbViewName), 'view.id');
        }
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ($this->withComments !== null) {
        	$tempquery = $this->_db->select()->distinct();
        	$tempquery->from(array('zuc' => 'zanby_users__comments'), 'zgp.gallery_id')
        			->join(array('zgp' => 'zanby_galleries__photos'), 'zuc.entity_id = zgp.id and zuc.entity_type_id = 4')
        			->where('zuc.user_id = ?', $this->withComments);
			$commentsGalleries = $this->_db->fetchCol($tempquery);
			if (!empty($commentsGalleries))	$this->setIncludeIds($commentsGalleries);
				else return array();
        }
		if (!$this->isCustomCondition) {
	        $query->where('view.owner_type = ?', 'group');
	        $query->where('view.owner_id = ?', $this->getGroupId());
        }
        
        $query->where('view.share IN (?)', $this->getSharingMode());
        $query->where('view.watch IN (?)', $this->getWatchingMode()); 
        $query->where('view.private IN (?)', $this->getPrivacy());
        
        if ( $this->getIncludeIds() ) $query->where('view.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('view.id NOT IN (?)', $this->getExcludeIds());
        
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
            $items = array_unique($items);
            foreach ( $items as &$item ) $item = Warecorp_Photo_Gallery_Factory::loadById($item);
        }
        return $items;
	}

	/**
	 * return number of all items
	 * @return int count
	 * @author Artem Sukharev
     * @todo getPrivacy
	 */
	public function getCount()
	{
        $query = $this->_db->select()->distinct();
        $query->from(array('view' => Warecorp_Photo_Gallery_Abstract::$_dbViewName), 'id');
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ($this->withComments !== null) {
        	$tempquery = $this->_db->select()->distinct();
        	$tempquery->from(array('zuc' => 'zanby_users__comments'), 'zgp.gallery_id')
        			->join(array('zgp' => 'zanby_galleries__photos'), 'zuc.entity_id = zgp.id and zuc.entity_type_id = 4')
        			->where('zuc.user_id = ?', $this->withComments);
			$commentsGalleries = $this->_db->fetchCol($tempquery);
			if (!empty($commentsGalleries))	$this->setIncludeIds($commentsGalleries);
				else return 0;
        }
		if (!$this->isCustomCondition) {
	        $query->where('view.owner_type = ?', 'group');
	        $query->where('view.owner_id = ?', $this->getGroupId());
		}
        $query->where('view.share IN (?)', $this->getSharingMode());
        $query->where('view.watch IN (?)', $this->getWatchingMode());
        $query->where('view.private IN (?)', $this->getPrivacy());
             
        if ( $this->getIncludeIds() ) $query->where('view.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('view.id NOT IN (?)', $this->getExcludeIds());
        return sizeof(array_unique($this->_db->fetchCol($query)));
	}

	/**
	 * get id of group
	 * @author Artem Sukharev
	 */
	public function getGroupId()
	{
		return $this->groupId;
	}

	/**
	 * set id of group
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setGroupId($newVal)
	{
		$this->groupId = $newVal;
	}

	/**
	 * return total size of galleries
	 * @param string $unit - value from Warecorp_Photo_Enum_SizeUnit
	 * @return double
	 * @author Artem Sukharev
	 */
    public function getTotalSize($unit = Warecorp_Photo_Enum_SizeUnit::BYTE)
    {
        $size = 0;
        $galleries = $this->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
                          ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
                          ->getList();
        if ( sizeof($galleries) != 0 ) {
            foreach ( $galleries as $gallery ) {
                $size = $size + $gallery->getSize(Warecorp_Photo_Enum_SizeUnit::BYTE);
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
    
    public function getAllPhotosTags()
    {
        /* DON`T REMOVE $select under */
    /*	$select = $this->_db->select()
            ->from(array('ztd' => 'zanby_tags__dictionary'), array('ztd.id', 'ztd.name', 'count' => new Zend_Db_Expr('COUNT(ztr.id)')))
            ->join(array('ztr' => 'zanby_tags__relations'), 'ztd.id = ztr.tag_id')
            ->join(array('zgp' => 'zanby_galleries__photos'), 'zgp.id = ztr.entity_id')
            ->join(array('zgi' => 'zanby_galleries__items'), 'zgp.gallery_id = zgi.id')
            ->where('ztr.entity_type_id = ?', 4)
            ->where('zgi.owner_id = ?', $this->groupId)
            ->where('zgi.owner_type = ?', 'group')
            ->where('ztr.status = ?', 'user')
            ->group('ztd.id');
        $res = $this->_db->fetchAll($select);
    */


        /* NEED TEST */
        $sql1 = $this->_db->select()
            ->from(array('ztd' => 'zanby_tags__dictionary'), array('ztd.id',  'ztd.name'))
            ->joinLeft(array('ztr' => 'zanby_tags__relations'), 'ztd.id = ztr.tag_id', array())
            ->joinLeft(array('zgp' => 'zanby_galleries__photos'), 'zgp.id = ztr.entity_id', array())
            ->joinLeft(array('zgi' => 'zanby_galleries__items'), 'zgp.gallery_id = zgi.id', array())
            ->joinLeft(array('zgs' => 'zanby_galleries__sharing'), 'zgs.gallery_id = zgi.id', array())
            ->where('ztr.entity_type_id = ?', 4)
            ->where("((zgi.owner_id = " . $this->_db->quote($this->groupId, 'INTEGER') ." AND zgi.owner_type = 'group')
                    OR
                    (zgs.owner_id = " . $this->_db->quote($this->groupId, 'INTEGER') ." AND zgs.owner_type = 'group'))")
            ->where("ztr.status = ?", 'user')
            ->group(array('ztd.id', 'zgp.id'));

        if (is_array($this->getPrivacy()) && !array_search(1, $this->getPrivacy())) {
            $sql1->where("zgi.private = ?", 0);
        }

        $sql2 = $this->_db->select()
            ->from(array("sub" => new Zend_Db_Expr("(".$sql1.")")), array('id', 'name', 'count' => new Zend_Db_Expr('COUNT(id)')))
            ->group('id');

        $res = $this->_db->fetchAll($sql2);
        /* END BLOCK */

        return $res;
    }    
    
    public function getCountOfNewPhotosForUser($user)
    {
    	$db = Zend_Registry::get('DB');

        $query = $db->select();
        $query->from(array('vgl' => 'view_gallery__list'), array('number' => new Zend_Db_Expr('count(*)')));
        $query->join(array('zgp' => 'zanby_galleries__photos'), 'vgl.id = zgp.gallery_id');
        $query->joinLeft(array('zgv' => 'zanby_galleries__views'), $db->quoteInto('(zgv.gallery_id = vgl.id and zgv.user_id = ?)', $user->getId()));
        $query->where('vgl.owner_type = ?', 'group');
        $query->where('vgl.owner_id = ?', $this->getGroupId());
        $query->where('zgp.creation_date > if(zgv.last_view_date is null, 0,zgv.last_view_date)');
/*        dump($query->__toString());
        exit*/;
        return $db->fetchOne($query);
    }
    
    public function getListOwners()
    {
        $query = $this->_db->select();
        $query->from(array('view' => Warecorp_Photo_Gallery_Abstract::$_dbViewName), array('id' => 'view.id', 'owner_id' => 'view.owner_id', 'owner_type' => 'view.owner_type'));

        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ($this->withComments !== null) {
        	$tempquery = $this->_db->select()->distinct();
        	$tempquery->from(array('zuc' => 'zanby_users__comments'), array())
        			->join(array('zgp' => 'zanby_galleries__photos'), 'zuc.entity_id = zgp.id and zuc.entity_type_id = 4', array('zgp.gallery_id'))
        			->where('zuc.user_id = ?', $this->withComments);
			$commentsGalleries = $this->_db->fetchCol($tempquery);
			if (!empty($commentsGalleries))	$this->setIncludeIds($commentsGalleries);
				else return array();
        }
		if (!$this->isCustomCondition) {
	        $query->where('view.owner_type = ?', 'group');
	        $query->where('view.owner_id = ?', $this->getGroupId());
        }

        $query->where('view.share IN (?)', $this->getSharingMode());
        $query->where('view.watch IN (?)', $this->getWatchingMode());
        $query->where('view.private IN (?)', $this->getPrivacy());

        if ( $this->getIncludeIds() ) $query->where('view.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('view.id NOT IN (?)', $this->getExcludeIds());

        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        $items = $this->_db->fetchAll($query);
        $result = array();
        foreach ($items as $item) {
			if (!isset($result[$item['id']])) $result[$item['id']] = array();
			$result[$item['id']][] = array('owner_type' => $item['owner_type'], 'owner_id' => $item['owner_id']);
        }
        return $result;
	}

}
