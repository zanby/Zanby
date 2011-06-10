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
 * @package Warecorp_Group_Invitation_List
 * @copyright  Copyright (c) 2007
 * @author Yury Zolotarsky
 */
class BaseWarecorp_Group_Invitation_List extends Warecorp_Abstract_List
{
	private $groupId;
	private $_folder;
	private $_status;
	private $_onlyIds;
	
	public function  __construct($groupId = null)
	{
		parent::__construct();
		if ($groupId !== null) $this->groupId = $groupId;
	}
	
	public function setFolder($folder = array())
	{
		$this->_folder = $folder;
		return $this;
	}
	
	public function getFolder()
	{
		return $this->_folder;
	}
	
	public function setOnlyIds($value = true)
	{
		$this->_onlyIds = $value;
		return $this;
	}
	
	public function getOnlyIds()
	{
		return $this->_onlyIds;
	}

	public function getGroups()
	{
		$onlyIds = $this->getOnlyIds();
		$this->setOnlyIds();
		$arr = $this->getList();
		$result = new Warecorp_Group_Invitation_GroupList($this->groupId, $arr);
		$this->setOnlyIds($onlyIds);
		return $result;
	}
	
	public function addInvitationItem($invitation = null)
	{
		if ($invitation instanceof Warecorp_Group_Invitation_Item) {
			$invitation->setOwnerId($this->groupId);
			$invitation->save();
		}
	}
	
	public function deleteInvitation($invitation)
	{
		if ($invitation !== null) {
			$where = $this->_db->quoteInto('id = ?', $invitation);
			$rows_affected = $this->_db->delete('zanby_groups__invitations', $where);					
		}			
	}
	
	public function deleteInvitations($invitations = array())
	{
		if (!is_array($invitations)) return false;
		foreach($invitations as $invitation) {
			$this->deleteInvitation($invitation);
		}
	}

	public function getList()
	{
       $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zgi.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zgi.name' : $this->getAssocValue();
            $query->from(array('zgi' => 'zanby_groups__invitations'), $fields);  
        } else {
            $query->from(array('zgi' => 'zanby_groups__invitations'), 'zgi.id');
        }
        if (!empty($this->groupId)) $query->where('zgi.group_owner_id = ?', $this->groupId);
        if (!empty($this->_folder)) $query->where('zgi.folder in (?)', $this->_folder);
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('zgi.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgi.id NOT IN (?)', $this->getExcludeIds());
        
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
            if ($this->_onlyIds) return empty($items)?array():$items;
            foreach ( $items as &$item ) $item = new Warecorp_Group_Invitation_Item($item);
        }
        return $items;		
	}
	
	public function getCount()
	{
        $query = $this->_db->select();
        $query->from(array('zgi' => 'zanby_groups__invitations'), new Zend_Db_Expr('COUNT(zgi.id)'));
        $query->where('zgi.group_owner_id = ?', $this->groupId);
        if (!empty($this->_folder)) $query->where('folder in (?)', $this->_folder);        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('zgi.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgi.id NOT IN (?)', $this->getExcludeIds());
        
/*        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }*/
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        return $this->_db->fetchOne($query);
	}
}
