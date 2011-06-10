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
 * @package    Warecorp_Group_Simple
 * @copyright  Copyright (c) 2006
 */

class BaseWarecorp_Group_Tag_List extends Warecorp_List_Tags
{
    /**
     * set group id
     * @param int $groupId
     * @return Warecorp_Group_Avatar_List
     * @author Artem Sukharev
     */
    public function setGroupId($groupId)
    {
        $this->setOwnerId($groupId);
    }

    /**
     * geet group id
     * @return int groupId
     * @author Artem Sukharev
     */
    public function getGroupId()
    {
        return $this->getOwnerId();
    }

    /**
     * Constructor
     */
    public function __construct($groupId)
    {
        parent::__construct();
		//$this->setOwnerTypeId(2);
        $this->setOwnerId($groupId);
    }

	public function resetList()
	{
		parent::resetList();
		$this->setOwnerTypeId(2);
	}

    /**
     *  return list of all items from group, members, documents etc.
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getList()
    {
		if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
		{
			$tags = parent::getList();
		} else {
			$tags = $this->getGroupTagsList() +
				$this->getMembersTagsList() +
				$this->getDocumentsTagsList() +
				$this->getEventsTagsList() +
				$this->getPhotosTagsList() +
				$this->getListsTagsList();
		}
        return $tags;
    }

    /**
     * return number of all items from group, members, documents etc.
     * @return int count
     * @author Artem Sukharev
     */
    public function getCount()
    {
		if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
		{
			$count = parent::getCount();
		} else {
			$count = $this->getGroupTagsCount() +
				$this->getMembersTagsCount() +
				$this->getDocumentsTagsCount() +
				$this->getEventsTagsCount() +
				$this->getPhotosTagsCount() +
				$this->getListsTagsCount();
		}
        return (int) $count;
    }

    /**
     *  return list of all items from group
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getGroupTagsList()
    {
        $tmpGroup = new Warecorp_Group_Base();
        $EntityTypeId      = $tmpGroup->EntityTypeId;
        $EntityTypeName    = $tmpGroup->EntityTypeName;
        unset($tmpGroup);

        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'ztd.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'ztd.name' : $this->getAssocValue();
            $query->from(array('ztr' => 'zanby_tags__relations'), $fields);
        } else {
            $query->from(array('ztr' => 'zanby_tags__relations'), new Zend_Db_Expr('DISTINCT ztd.id'));
        }
        $query->joininner(array('ztd' => 'zanby_tags__dictionary'), 'ztr.tag_id = ztd.id');

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('entity_type_id = ?', $EntityTypeId);
        $query->where('entity_id = ?', $this->getGroupId());
        $query->where('ztr.status IN (?)', $this->getTagStatus());

        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else  $query->order('ztd.name');

		$items = $this->getTagListFromSQL($query, false);
        return $items;
    }

    /**
     *  return number of all items from group
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getGroupTagsCount()
    {
        $tmpGroup = new Warecorp_Group_Base();
        $EntityTypeId      = $tmpGroup->EntityTypeId;
        $EntityTypeName    = $tmpGroup->EntityTypeName;
        unset($tmpGroup);

        $group = Warecorp_Group_Factory::loadById($this->getGroupId());
        $members = $group->getMembers()->setAssocValue('zua.id')->returnAsAssoc()->getList();

        $query = $this->_db->select();
        $query->from(array('ztr' => 'zanby_tags__relations'), array());
        $query->joininner(array('ztd' => 'zanby_tags__dictionary'), 'ztr.tag_id = ztd.id', array(new Zend_Db_Expr('COUNT(DISTINCT ztd.id)')));

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('entity_type_id = ?', $EntityTypeId);
        $query->where('entity_id = ?', $this->getGroupId());
        $query->where('ztr.status IN (?)', $this->getTagStatus());

        return $this->_db->fetchOne($query);
    }

    /**
     *  return list of all items from group members
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getMembersTagsList()
    {
    	$tmpUser = new Warecorp_User();
    	$EntityTypeId      = $tmpUser->EntityTypeId;
    	$EntityTypeName    = $tmpUser->EntityTypeName;
    	unset($tmpUser);

    	$group = Warecorp_Group_Factory::loadById($this->getGroupId());
    	$members = $group->getMembers()->setAssocValue('zua.id')->returnAsAssoc()->getList();

        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'ztd.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'ztd.name' : $this->getAssocValue();
            $query->from(array('ztr' => 'zanby_tags__relations'), $fields);
        } else {
            $query->from(array('ztr' => 'zanby_tags__relations'), new Zend_Db_Expr('DISTINCT ztd.id'));
        }
        $query->joininner(array('ztd' => 'zanby_tags__dictionary'), 'ztr.tag_id = ztd.id');

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('entity_type_id = ?', $EntityTypeId);
        $query->where('entity_id IN (?)', $members);
        $query->where('ztr.status IN (?)', $this->getTagStatus());

        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else  $query->order('ztd.name');

		$items = $this->getTagListFromSQL($query, false);
        return $items;
    }

    /**
     *  return number of all items from group members
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getMembersTagsCount()
    {
        $tmpUser = new Warecorp_User();
        $EntityTypeId      = $tmpUser->EntityTypeId;
        $EntityTypeName    = $tmpUser->EntityTypeName;
        unset($tmpUser);

        $group = Warecorp_Group_Factory::loadById($this->getGroupId());
        $members = $group->getMembers()->setAssocValue('zua.id')->returnAsAssoc()->getList();

        $query = $this->_db->select();
        $query->from(array('ztr' => 'zanby_tags__relations'), array());
        $query->joininner(array('ztd' => 'zanby_tags__dictionary'), 'ztr.tag_id = ztd.id', array(new Zend_Db_Expr('COUNT(DISTINCT ztd.id)')));

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('entity_type_id = ?', $EntityTypeId);
        $query->where('entity_id IN (?)', $members);
        $query->where('ztr.status IN (?)', $this->getTagStatus());

        return $this->_db->fetchOne($query);
    }

    /**
     *  return list of all items from group documents
     *  @return array of objects
     *  @author Artem Sukharev
     *  @todo реализовать это
     */
    public function getDocumentsTagsList()
    {
        return array();
    }

    /**
     *  return number of all items from group documents
     *  @return array of objects
     *  @author Artem Sukharev
     *  @todo реализовать это
     */
    public function getDocumentsTagsCount()
    {
        return 0;
    }

    /**
     *  return list of all items from group events
     *  @return array of objects
     *  @author Artem Sukharev
     *  @todo реализовать это
     */
    public function getEventsTagsList()
    {
        return array();
    }

    /**
     *  return number of all items from group events
     *  @return array of objects
     *  @author Artem Sukharev
     *  @todo реализовать это
     */
    public function getEventsTagsCount()
    {
        return 0;
    }

    /**
     *  return list of all items from group photos
     *  @return array of objects
     *  @author Artem Sukharev
     *  @todo реализовать это
     */
    public function getPhotosTagsList()
    {
        return array();
    }

    /**
     *  return number of all items from group photos
     *  @return array of objects
     *  @author Artem Sukharev
     *  @todo реализовать это
     */
    public function getPhotosTagsCount()
    {
        return 0;
    }

    /**
     *  return list of all items from group lists
     *  @return array of objects
     *  @author Artem Sukharev
     *  @todo реализовать это
     */
    public function getListsTagsList()
    {
        return array();
    }

    /**
     *  return number of all items from group lists
     *  @return array of objects
     *  @author Artem Sukharev
     *  @todo реализовать это
     */
    public function getListsTagsCount()
    {
        return 0;
    }

    /**
     * return list of all items by location
     * @return int count
     * @author Yauhen Halauniou, Vitaly Targonsky, Konstantin Stepanov
     */
    public function getListByLocation()
    {
		if (defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
		{
			//$this->addFilter("entity_type", 6, true); // exclude events from tag cloud
            $this->addFilter("entity_type", 2);  //  get tags for groups Only
			return parent::getList("name", "@count");
		} else {
			echo 3;exit;
			if ( $this->getGroupId() !== null ) {
				$this->addWhere('group_id = ?', $this->getGroupId());
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
				$limit = "LIMIT ".(($this->getCurrentPage()-1) * $this->getListSize()) .", ". $this->getListSize();
			}
			$order = "";
			if ( $this->getOrder() !== null ) {
				$order = "ORDER BY ".$this->getOrder();
			}

			$query = "SELECT distinct {$fields} FROM (
	#get groups tags
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
		  IN (
				 SELECT tag_id FROM zanby_tags__relations
				 WHERE entity_type_id = 2 AND entity_id
				 IN (
							 SELECT group_id FROM `view_groups__locations` $where
					)
			)
	UNION
	#get documents tags
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
		  IN (
				 SELECT tag_id FROM zanby_tags__relations
				 WHERE entity_type_id = 5 AND entity_id
				 IN (
					   SELECT id FROM zanby_documents__items
					   WHERE owner_type = 'group' AND owner_id
					   IN (
							 SELECT group_id FROM `view_groups__locations` $where
						  )
					)
			 )
	UNION
	#get members tags
	SELECT {$fields} FROM view_tags__dictionary
	WHERE id
		  IN (
				 SELECT tag_id FROM zanby_tags__relations
				 WHERE entity_type_id = 1 AND entity_id
				 IN (
					   SELECT user_id FROM zanby_groups__members
					   WHERE group_id
					   IN (
							 SELECT group_id FROM `view_groups__locations` $where
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
						WHERE owner_type = 'group' AND owner_id
						IN (
							  SELECT group_id FROM `view_groups__locations` $where
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
							 WHERE owner_type = 'group' AND owner_id
							 IN  (
									SELECT group_id FROM `view_groups__locations` $where
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
						WHERE owner_type = 'group' AND owner_id
						IN (
							  SELECT group_id FROM `view_groups__locations` $where
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
							 WHERE owner_type = 'group' AND owner_id
							 IN  (
									SELECT group_id FROM `view_groups__locations` $where
								 )
						  )
					 )
		)
	)
	AS result {$order} {$limit}";

			$items = $this->getTagListFromSQL($query);
		}
        return $items;
    }

}
