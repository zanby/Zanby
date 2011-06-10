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
 * @package    Warecorp_User
 * @copyright  Copyright (c) 2006
 */

class BaseWarecorp_User_Tag_List extends Warecorp_List_Tags
{
    private $_entityTypeId = false;

    /**
     * set user id
     * @param int $userId
     * @return Warecorp_Group_Avatar_List
     * @author Artem Sukharev
     */
    public function setUserId($userId)
    {
        $this->setOwnerId($userId);
    }

    /**
     * geet user id
     * @return int userId
     * @author Artem Sukharev
     */
    public function getUserId()
    {
        return $this->getOwnerId();
    }

    /**
     * set what kind of tags list we wanna get
     * @return $this
     * @author Yury Zolotarsky
     */
    public function setEntityTypeId($entityTypeId = false)
    {
    	$this->_entityTypeId = $entityTypeId;
    	return $this;
    }

    /**
     * get kind of tags list we gonna get
     * @return int entity_type_id
     * @author Yury Zolotarsky
     */
    public function getEntityTypeId()
    {
    	return $this->_entityTypeId;
    }

	public function resetList()
	{
		parent::resetList();
		$this->setOwnerTypeId(1);
	}

    /**
     * Constructor
     */
    public function __construct($userId = null)
    {
        parent::__construct();
        $this->setOwnerId($userId);
		$this->setOwnerTypeId(1);
    }

    /**
     *  return list of all items by rating
     *  @return array of objects
     *  @author Yury Zolotarsky
     */
    public function getPhotoTagsByRating()
    {
		$items = array();

		if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
		{
			$this->addFilter('entity_type', 4);
			$items = parent::getList("tag_id", "@count");
		} else {
			if ( $this->getUserId() !== null ) {
				$this->addWhere('user_id = ?', $this->getUserId());
			}
			$where = $this->getWhere();
			if ($where) $where = 'where '.$where;
			$fields = array();
			if ( $this->isAsAssoc() ) {
				$fields[] = ( $this->getAssocKey() === null ) ? 'id' : $this->getAssocKey();
				$fields[] = ( $this->getAssocValue() === null ) ? 'rating' : $this->getAssocValue();
			} else {
				$fields[] = 'id';
			}
			$fields = implode(', ', $fields);
			$limit = "";
			if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
				$limit = "LIMIT ".($this->getCurrentPage()-1) .", ". $this->getListSize();
			}
			$order = "";
			if ( $this->getOrder() !== null ) {
				$order = "ORDER BY ".$this->getOrder();
			}

			$sql = "SELECT distinct {$fields} FROM (
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
	IN (
		   SELECT tag_id FROM zanby_tags__relations
		   WHERE entity_type_id = 4 AND entity_id
				 IN (
					   SELECT id from zanby_galleries__photos
					   WHERE gallery_id
					   IN (
							 SELECT id FROM zanby_galleries__items
							 WHERE (owner_type = 'user') and (creator_id = ".$this->getUserId().")
						  )
					 )
		)
	)
	AS result {$order} {$limit}";

			$items = $this->getTagListFromSQL($sql);
		}
        return $items;
    }

    /**
     *  return list of all items by rating
     *  @return array of objects
     *  @author Yury Zolotarsky
     */

    public function getVideoTagsByRating()
    {
		if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
		{
			$this->addFilter('entity_type', 37);
			return parent::getList("tag_id", "@count");
		} else {
			if ( $this->getUserId() !== null ) {
				$this->addWhere('user_id = ?', $this->getUserId());
			}
			$where = $this->getWhere();
			if ($where) $where = 'where '.$where;
			$fields = array();
			if ( $this->isAsAssoc() ) {
				$fields[] = ( $this->getAssocKey() === null ) ? 'id' : $this->getAssocKey();
				$fields[] = ( $this->getAssocValue() === null ) ? 'rating' : $this->getAssocValue();
			} else {
				$fields[] = 'id';
			}
			$fields = implode(', ', $fields);
			$limit = "";
			if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
				$limit = "LIMIT ".($this->getCurrentPage()-1) .", ". $this->getListSize();
			}
			$order = "";
			if ( $this->getOrder() !== null ) {
				$order = "ORDER BY ".$this->getOrder();
			}

			$sql = "SELECT distinct {$fields} FROM (
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
	IN (
		   SELECT tag_id FROM zanby_tags__relations
		   WHERE entity_type_id = 37 AND entity_id
				 IN (
					   SELECT id from zanby_videogalleries__videos
					   WHERE gallery_id
					   IN (
							 SELECT id FROM zanby_videogalleries__items
							 WHERE (owner_type = 'user') and (creator_id = ".$this->getUserId().")
						  )
					 )
		)
	)
	AS result {$order} {$limit}";

			$items = $this->getTagListFromSQL($sql);
		}
        return $items;
    }


    public function getListTags()
    {
	$items = null;
	if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
	{
	    $this->addFilter("entity_type", 20);
	    $items = parent::getList("tag_id", "name");

	} else {
	    $query = $this->_db->select();
	    $query->join(array('ztr' => 'zanby_tags__relations'), 'vuel.entity_type_id = ztr.entity_type_id AND vuel.entity_id = ztr.entity_id');
	    $query->join(array('ztd' => 'zanby_tags__dictionary'), 'ztr.tag_id = ztd.id');
	    $query->where('ztr.status = ?', 'user')
		->where('ztr.entity_type_id = ?', 20);
	    $query->distinct();
	    if ( $this->isAsAssoc() ) {
		$fields = array();
		$fields[] = ( $this->getAssocKey() === null ) ? 'ztd.id' : $this->getAssocKey();
		$fields[] = ( $this->getAssocValue() === null ) ? 'ztd.name' : $this->getAssocValue();
		$query->from(array('vuel' => 'view_users__entity_list'), $fields);
	    } else {
		$query->from(array('vuel' => 'view_users__entity_list'), 'ztd.id');
	    }
	    if ( $this->getWhere() ) $query->where($this->getWhere());
	    if ( $this->getUserId() !== null ) {
		$query->where('vuel.user_id = ?', $this->getUserId());
	    }
	    if ( $this->_entityTypeId != false ) {
		$query->where('vuel.entity_type_id = ?', $this->_entityTypeId);
	    }
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
		foreach ( $items as &$item ) $item = new Warecorp_Data_Tag($item);
	    }
	}
	return $items;
    }

    public function getList()
    {
		if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
		{
            if ($this->_entityTypeId != false ) {
                $this->addFilter('entity_type', $this->_entityTypeId);
            }
			return parent::getList("tag_id", "name");
		} else {

            throw new Zend_Exception('<b>WITH_SPHINX_TAGS</b> parameter in cfg.site.xml must be set to <b>on</b>');

            /*
			$query = $this->_db->select();
			$query->join(array('ztr' => 'zanby_tags__relations'), 'vuel.entity_type_id=ztr.entity_type_id AND vuel.entity_id=ztr.entity_id');
			$query->join(array('ztd' => 'zanby_tags__dictionary'), 'ztr.tag_id = ztd.id');
			$query->where('ztr.status = ?', 'user');
			$query->distinct();
			if ( $this->isAsAssoc() ) {
				$fields = array();
				$fields[] = ( $this->getAssocKey() === null ) ? 'ztd.id' : $this->getAssocKey();
				$fields[] = ( $this->getAssocValue() === null ) ? 'ztd.name' : $this->getAssocValue();
				$query->from(array('vuel' => 'view_users__entity_list'), $fields);
			} else {
				$query->from(array('vuel' => 'view_users__entity_list'), 'ztd.id');
			}
			if ( $this->getWhere() ) $query->where($this->getWhere());
			if ( $this->getUserId() !== null ) {
				$query->where('vuel.user_id = ?', $this->getUserId());
			}
			if ( $this->_entityTypeId != false ) {
				$query->where('vuel.entity_type_id = ?', $this->_entityTypeId);
			}
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
                foreach ( $items as &$item ) $item = new Warecorp_Data_Tag($item);
            }
			return $items;

            */
		}
    }

    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function getCount()
    {
		if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
		{
			return parent::getCount();
		} else
		{
			$query = $this->_db->select();
			$query->from(array('vuel' => 'view_users__entity_list'), new Zend_Db_Expr('COUNT(DISTINCT ztd.id)'));
			$query->join(array('ztr' => 'zanby_tags__relations'), 'vuel.entity_type_id=ztr.entity_type_id AND vuel.entity_id=ztr.entity_id');
			$query->where('ztr.status = ?', 'user');
			$query->join(array('ztd' => 'zanby_tags__dictionary'), 'ztr.tag_id = ztd.id');
			if ( $this->getWhere() ) $query->where($this->getWhere());
			if ( $this->getUserId() !== null ) {
				$query->where('ztr.user_id = ?', $this->getUserId());
			}
			if ( $this->_entityTypeId != false ) {
				$query->where('vuel.entity_type_id = ?', $this->_entityTypeId);
			}
			return $this->_db->fetchOne($query);
		}
    }


	private function getUserIdsByFilters()
	{
		$cl = new Warecorp_Data_Search();
		$cl->init("user");
		foreach ($this->_sphFilters as $param => $value)
		{
			$cl->SetFilter($param, $value);
		}
		$cl->Query();
		$userIds = $cl->getResultPairs();
		return $userIds;
	}

    /**
    * return list of all items by location
    * @return int count
    * @author Yauhen Halauniou, Vitaly Targonsky
    */
    public function getListByLocation()
    {
		if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
		{
            $this->addFilter("entity_type", 1);  //  only users tags
			return parent::getList("name", "@count");
		} else {
			if ( $this->getUserId() !== null ) {
				$this->addWhere('user_id = ?', $this->getUserId());
			}
			$where = $this->getWhere();
			if ($where) $where = 'where '.$where;
			$fields = array();
			if ( $this->isAsAssoc() ) {
				$fields[] = ( $this->getAssocKey() === null ) ? 'name' : $this->getAssocKey();
				$fields[] = ( $this->getAssocValue() === null ) ? 'rating' : $this->getAssocValue();
			} else {
				$fields[] = 'id';
			}
			$fields = implode(', ', $fields);
			$limit = "";
			if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
				$limit = "LIMIT ".($this->getCurrentPage()-1) .", ". $this->getListSize();
			}
			$order = "";
			if ( $this->getOrder() !== null ) {
				$order = "ORDER BY ".$this->getOrder();
			}

			$sql = "SELECT distinct {$fields} FROM (
#get documents tags
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
		  IN (
				 SELECT tag_id FROM zanby_tags__relations
				 WHERE entity_type_id = 5 AND entity_id
				 IN (
					   SELECT id FROM zanby_documents__items
					   WHERE owner_type = 'user' AND owner_id
					   IN (
							 SELECT id FROM `view_users__locations` $where
						  )
					)
			 )
	UNION
#get users tags
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
		  IN (
				 SELECT tag_id FROM zanby_tags__relations
				 WHERE entity_type_id = 1 AND entity_id
				 IN (
					   SELECT id FROM zanby_users__accounts

					   WHERE status = 'active' AND id
					   IN (
							 SELECT id FROM `view_users__locations` $where
						  )
					)
			)
	UNION
#get groups tags
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
		  IN (
				 SELECT tag_id FROM zanby_tags__relations
				 WHERE entity_type_id = 2 AND entity_id
				 IN (
					   SELECT group_id FROM zanby_groups__members
					   WHERE user_id
					   IN (
							 SELECT id FROM `view_users__locations` $where
						  )
					)
			)
	UNION
#get events tags
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
		  IN (
				 SELECT tag_id FROM zanby_tags__relations
				 WHERE entity_type_id = 6 AND entity_id
				 IN (
						SELECT id FROM zanby_event__items
						WHERE owner_type = 'user' AND owner_id
						IN (
							  SELECT id FROM `view_users__locations` $where
						   )
					)
			 )
	UNION
#get photos tags
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
	IN (
		   SELECT tag_id FROM zanby_tags__relations
		   WHERE entity_type_id = 4 AND entity_id
				 IN (
					   SELECT id from zanby_galleries__photos
					   WHERE gallery_id
					   IN (
							 SELECT id FROM zanby_galleries__items
							 WHERE owner_type = 'user' AND owner_id
							 IN  (
									SELECT id FROM `view_users__locations` $where
								 )
						  )
					 )
		)
	UNION
#get lists tags
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
		  IN (
				 SELECT tag_id FROM zanby_tags__relations
				 WHERE entity_type_id = 20 AND entity_id
				 IN (
						SELECT id FROM zanby_lists__items
						WHERE owner_type = 'user' AND owner_id
						IN (
							  SELECT id FROM `view_users__locations` $where
						   )
					)
			 )
	UNION
#get lists items tags
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
	IN (
		   SELECT tag_id FROM zanby_tags__relations
		   WHERE entity_type_id = 21 AND entity_id
				 IN (
					   SELECT id from zanby_lists__records
					   WHERE list_id
					   IN (
							 SELECT id FROM zanby_lists__items
							 WHERE owner_type = 'user' AND owner_id
							 IN  (
									SELECT id FROM `view_users__locations` $where
								 )
						  )
					 )
		)
	)
	AS result {$order} {$limit}";

			$items = $this->getTagListFromSQL($sql);
			return $items;
		}
	}
}
