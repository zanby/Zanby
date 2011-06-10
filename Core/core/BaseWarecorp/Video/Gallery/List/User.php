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
 * @package Warecorp_Video_Gallery_List
 * @author Yury Zolotarsky
 * @version 1.0
 */
class BaseWarecorp_Video_Gallery_List_User extends Warecorp_Video_Gallery_List_Abstract
{
	/**
	 * id of user
	 */
	private $userId;
	private $isCustomCondition;
	private $withComments;

    /**
     * Constructor
     * @author Artem Sukharev
     */
    function __construct($userId)
    {
    	$this->setUserId($userId);
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

	public function getList()
	{
		$query = $this->_db->select()->distinct();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'view.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'view.title' : $this->getAssocValue();
            $query->from(array('view' => Warecorp_Video_Gallery_Abstract::$_dbViewName), $fields);  
        } else {
            $query->from(array('view' => Warecorp_Video_Gallery_Abstract::$_dbViewName), 'view.id');
        }
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ($this->withComments !== null) {
        	$tempquery = $this->_db->select()->distinct();
        	$tempquery->from(array('zuc' => 'zanby_users__comments'), array())
        			->join(array('zgp' => 'zanby_videogalleries__videos'), 'zuc.entity_id = zgp.id and zuc.entity_type_id = 37', array('zgp.gallery_id'))
        			->where('zuc.user_id = ?', $this->withComments);
			$commentsGalleries = $this->_db->fetchCol($tempquery);
			if (!empty($commentsGalleries))	$this->setIncludeIds($commentsGalleries); 
				else return array();
        }
        
        if (!$this->isCustomCondition) {
	        $query->where('view.owner_type = ?', 'user');
	        $query->where('view.owner_id = ?', $this->getUserId());
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
            foreach ( $items as &$item ) $item = Warecorp_Video_Gallery_Factory::loadById($item);
        }

        return $items;
	}
	

	public function getCount()
	{
        $query = $this->_db->select();
        $query->from(array('view' => Warecorp_Video_Gallery_Abstract::$_dbViewName), new Zend_Db_Expr('COUNT(DISTINCT view.id)'));
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ($this->withComments !== null) {
        	$tempquery = $this->_db->select()->distinct();
        	$tempquery->from(array('zuc' => 'zanby_users__comments'), array())
        			->join(array('zgp' => 'zanby_videogalleries__videos'), 'zuc.entity_id = zgp.id and zuc.entity_type_id = 37', array('zgp.gallery_id'))
        			->where('zuc.user_id = ?', $this->withComments);
        	$commentsGalleries = $this->_db->fetchCol($tempquery);        	
			if (!empty($commentsGalleries))	$this->setIncludeIds($commentsGalleries); 
				else return 0;
        }
        if (!$this->isCustomCondition) {
	        $query->where('view.owner_type = ?', 'user');
	        $query->where('view.owner_id = ?', $this->getUserId());
        }
        $query->where('view.share IN (?)', $this->getSharingMode());
        $query->where('view.watch IN (?)', $this->getWatchingMode());
        $query->where('view.private IN (?)', $this->getPrivacy());   
        if ( $this->getIncludeIds() ) $query->where('view.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('view.id NOT IN (?)', $this->getExcludeIds());
        return $this->_db->fetchOne($query);
	}

	
	public function getUserId()
	{
		return $this->userId;
	}

	
	public function setUserId($newVal)
	{
		$this->userId = $newVal;
	}

	
	public function getTotalSize($unit = Warecorp_Video_Enum_SizeUnit::BYTE)
	{
		$size = 0;
		$galleries = $this->setSharingMode(Warecorp_Video_Enum_SharingMode::OWN)
		                  ->setWatchingMode(Warecorp_Video_Enum_WatchingMode::OWN)
		                  ->getList();
		if ( sizeof($galleries) != 0 ) {
            foreach ( $galleries as $gallery ) {
            	$size = $size + $gallery->getSize(Warecorp_Video_Enum_SizeUnit::BYTE);
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
	}
	
    public function getAllVideosTags()
    {		
    	$video = new Warecorp_Video_Standard();

    	/* don`t remove $select under */
/*        $select = $this->_db->select()
            ->from(array('ztd' => 'zanby_tags__dictionary'), array('ztd.id', 'ztd.name', 'count' => new Zend_Db_Expr('COUNT(ztr.id)')))
            ->joinLeft(array('ztr' => 'zanby_tags__relations'), 'ztd.id = ztr.tag_id')
            ->joinLeft(array('zgp' => 'zanby_videogalleries__videos'), 'zgp.id = ztr.entity_id')
            ->joinLeft(array('zgi' => 'zanby_videogalleries__items'), 'zgp.gallery_id = zgi.id')
            ->joinLeft(array('zgs' => 'zanby_videogalleries__sharing'), 'zgs.gallery_id = zgi.id')
            ->where('ztr.entity_type_id = ?', $video->EntityTypeId)
            ->where("(zgi.owner_id = '{$this->userId}' AND zgi.owner_type = 'user')")
//            ->where('((zgi.owner_id = ?', $this->userId)
//            ->where('zgi.owner_type = ?)', 'user')
//            ->orWhere('(zgi.creator_id = ?))', $this->userId)
            ->where('ztr.status = ?', 'user')
            ->group('ztd.id');

            */

        /* NEED TEST */
        $sql1 = $this->_db->select()
            ->from(array('ztd' => 'zanby_tags__dictionary'), array('ztd.id',  'ztd.name'))
            ->joinLeft(array('ztr' => 'zanby_tags__relations'), 'ztd.id = ztr.tag_id', array())
            ->joinLeft(array('zgp' => 'zanby_videogalleries__videos'), 'zgp.id = ztr.entity_id', array())
            ->joinLeft(array('zgi' => 'zanby_videogalleries__items'), 'zgp.gallery_id = zgi.id', array())
            ->joinLeft(array('zgs' => 'zanby_videogalleries__sharing'), 'zgs.gallery_id = zgi.id', array())
            ->joinLeft(array('zgw' => 'zanby_videogalleries__watching'), 'zgw.gallery_id = zgi.id', array())
            ->where('ztr.entity_type_id = ?', $video->EntityTypeId)
            ->where("((zgi.owner_id = " . $this->_db->quote($this->userId, 'INTEGER') ." AND zgi.owner_type = 'user')
                    OR
                    (zgs.owner_id = " . $this->_db->quote($this->userId, 'INTEGER') ." AND zgs.owner_type = 'user')
                    OR
                    (zgw.user_id = " . $this->_db->quote($this->userId, 'INTEGER') ."))")
            ->where("ztr.status = 'user'")
            ->group(array("ztd.id", "zgp.id"));

        if (is_array($this->getPrivacy()) && !array_search(1, $this->getPrivacy())) {
            $sql1->where("zgi.private = ?", 0);
        }

        $sql2 = $this->_db->select()
            ->from(array("sub" => new Zend_Db_Expr("(".$sql1.")")), array('id', 'name', 'count' => new Zend_Db_Expr('COUNT(id)')))
            ->group('id');

        $res = $this->_db->fetchAll($sql2);

        return $res;
    }
}
