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
 * @package    Warecorp_User_Avatar
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */
class BaseWarecorp_Group_Avatar_List extends Warecorp_Abstract_List 
{
	/**
	 * group id
	 */
	private $_groupId;
	
	/**
	 * set group id
	 * @param int $groupId
	 * @return Warecorp_Group_Avatar_List
	 * @author Artem Sukharev
	 */
	public function setGroupId($groupId)
	{
		$this->_groupId = $groupId;
	}
	
    /**
     * geet group id
     * @return int groupId
     * @author Artem Sukharev
     */
	public function getGroupId()
	{
		return $this->_groupId; 
	}
	
    /**
     * Constructor
     */
	public function __construct($groupId = null)
    {
        parent::__construct();
    	if ( $groupId !== null ) $this->setGroupId($groupId);
    }
	
    /**
     *  return list of all items
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zga.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zga.bydefault' : $this->getAssocValue();
            $query->from(array('zga' => 'zanby_groups__avatars'), $fields);  
        } else {
            $query->from(array('zga' => 'zanby_groups__avatars'), 'zga.id');
        }
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getGroupId() !== null ) {
            $query->where('zga.group_id = ?', $this->getGroupId());
        }
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
            $group = Warecorp_Group_Factory::loadById($this->getGroupId());
            
            if ($group->getAvatar()->getId() == 0) $items[0] = 1; else $items[0] = 0;            
        } else {
            $items = $this->_db->fetchCol($query);
			$default = new Warecorp_Group_Avatar(0);
            $default->setGroupId($this->getGroupId());

            $items1 = array($default);
            foreach ( $items as $key=>$item ) {            	
            	$items1 = $items1 + array($key + 1 => new Warecorp_Group_Avatar($item));
            }
            $items = $items1;
        }
        return $items;
    }
    
    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev
     */
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('zga' => 'zanby_groups__avatars'), new Zend_Db_Expr('COUNT(zga.id)'));
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getGroupId() !== null ) {
            $query->where('zga.group_id = ?', $this->getGroupId());
        }
        return $this->_db->fetchOne($query);
    }
}
