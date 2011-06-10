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
 * @package Warecorp_Group_Invitation_GroupList
 * @copyright  Copyright (c) 2007
 * @author Yury Zolotarsky
 */
class BaseWarecorp_Group_Invitation_GroupList extends Warecorp_Group_List
{
	private $_status;
	private $_invitations;
	private $_groupId;
	private $_onlyGroups;
	
	public function  __construct($groupId = null, $invitations = false)
	{
		parent::__construct();
		$this->_groupId = $groupId;
		$this->setInvitationList($invitations);	
	}
	
	public function setStatus($status)
	{
		$this->_status = $status;
		return $this;
	}
	
	public function getStatus()
	{
		return $this->_status;
	}	

	public function setOnlyGroups($value = true)
	{
		$this->_onlyGroups = $value;
		return $this;
	}
	
	public function getOnlyGroups()
	{
		return $this->_onlyGroups;
	}

	public function setInvitationList($invitations = false)
	{
		$this->_invitations = empty($invitations)?false:$invitations;
		return $this;
	}
	
	public function getInvitationList()
	{
		return $this->_invitations;
	}	
	
	public function setGroupId($groupId)
	{
		$this->_groupId = $groupId;
		return $this;
	}
	
	public function getGroupId()
	{
		return $this->_groupId;
	}

	public function setDeclined($group)
	{
		$data['declined'] = 1;
		$where = $this->_db->quoteInto('invitation_id in (?)', $this->_invitations).
				 $this->_db->quoteInto(' and group_id = ?', $group);
		$this->_db->update('zanby_groups__invitations_items', $data, $where);		
	}
	
	public function addGroup($group = null)
	{
		if ($group !== null && is_numeric($this->_invitations)) {
			$data['invitation_id'] = $this->_invitations;
			$data['group_id'] = $group;
			$data['declined'] = false;
			$rows_affected = $this->_db->insert('zanby_groups__invitations_items', $data);
		}
	}
	
	public function deleteGroup($group = null)
	{
		if (is_array($this->_invitations)) {
			foreach($this->_invitations as $inv) {
				$groupList = new Warecorp_Group_Invitation_GroupList($this->_groupId, $inv);
				$groupList->deleteGroup($group);
			}			
		} elseif (is_numeric($this->_invitations) && $group !== null && $this->isExist($group)) {
			if ($this->getCount() == 1) {
				$invitation = new Warecorp_Group_Invitation_Item($this->_invitations);
				$invitation->delete();
			}				
			else {	
				$where = $this->_db->quoteInto('group_id = ?', $group).
						 $this->_db->quoteInto(' and invitation_id in (?)', $this->_invitations);
				$rows_affected = $this->_db->delete('zanby_groups__invitations_items', $where);			
			}
		}		
	}

	public function addGroups($groups = array())
	{
		if (!is_array($groups)) return false;
		foreach($groups as $group) {
			$this->addGroup($group);
		}
	}
	
	public function deleteGroups($groups = array())
	{
		if (!is_array($groups)) return false;
		foreach($groups as $group) {
			$this->deleteGroup($group);
		}		
	}
	
	public function getList()
    {		
    	$query = $this->_db->select()
    		->from(array('zgii' => 'zanby_groups__invitations_items'), array('group_id' => 'zgii.group_id', 'declined' => 'zgii.declined'))
    		->join(array('zgi' => 'zanby_groups__invitations'), 'zgii.invitation_id = zgi.id', array('creation_date' => 'zgi.creation_date'))
    		->join(array('zgit' => 'zanby_groups__items'), 'zgii.group_id = zgit.id',array('type'=>'zgit.type'))
    		->joinLeft(array('zgr' => 'zanby_groups__relations'),'zgr.parent_group_id = zgi.group_owner_id and zgr.child_group_id = zgii.group_id', array('zgr.status'));
		$query->where('zgi.id in (?)', $this->_invitations);
    	$query->where('zgi.group_owner_id = ?', $this->_groupId);
		if($this->_onlyGroups) {
			$groups = $this->_db->fetchCol($query);
			$this->setIncludeIds($groups);
			return parent::getList();
		}
		if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
    	$groups = $this->_db->fetchAll($query);
		$currentGroup = Warecorp_Group_Factory::loadById($this->_groupId, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
		foreach($groups as $group) {			
			$items[$group['group_id']]['group'] = Warecorp_Group_Factory::loadById($group['group_id'],$group['type']);
			if ($currentGroup->getGroups()->isGroupExistAndPending($group['group_id'])) {
				$items[$group['group_id']]['status'] = 'pending approval';
			} elseif ($currentGroup->getGroups()->isGroupInFamily($group['group_id'])) {
				$items[$group['group_id']]['status'] = 'approved';
			} elseif ($group['declined'] == 1) {
				$items[$group['group_id']]['status'] = 'declined';
			} else {
				$items[$group['group_id']]['status'] = 'has not responded';
			}
			$items[$group['group_id']]['creation_date'] = $group['creation_date'];
		}
		return empty($items)?array():$items;
    }	
    
	public function getCount()
	{
    	$query = $this->_db->select()
    		->from(array('zgii' => 'zanby_groups__invitations_items'), new Zend_Db_Expr('COUNT(zgii.group_id)'))
    		->join(array('zgi' => 'zanby_groups__invitations'), 'zgii.invitation_id = zgi.id');
    	if (!empty($this->_invitations)){
			$query->where('zgi.id in (?)', $this->_invitations);
    	}
    	$query->where('zgi.group_owner_id = ?', $this->_groupId);
		return $this->_db->fetchOne($query);		
	}
	
	public function isExist($group)
	{
    	$query = $this->_db->select()
    		->from(array('zgii' => 'zanby_groups__invitations_items'), new Zend_Db_Expr('COUNT(zgii.group_id)'))
    		->join(array('zgi' => 'zanby_groups__invitations'), 'zgii.invitation_id = zgi.id');
    	if (!empty($this->_invitations)){
			$query->where('zgi.id in (?)', $this->_invitations);
    	}
    	$query->where('zgi.group_owner_id = ?', $this->_groupId);
    	$query->where('zgii.group_id = ?', $group);
		return (boolean)$this->_db->fetchOne($query);				
	}
}
