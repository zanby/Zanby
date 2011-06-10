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
 * @package    Warecorp_Group_Privileges_ToolUserList
 * @copyright  Copyright (c) 2007
 * @author Yury Zolotarsky
 */
class BaseWarecorp_Group_Privileges_ToolUserList extends Warecorp_Abstract_List 
{
	/**
	 * User id
	 */
	private $_groupId;
	private $_toolType;
	

	public function setToolType($toolType)
	{
		$this->_toolType = $toolType;
		return $this;
	}
	
	public function getToolType()
	{
		if ( $this->_toolType === null ) throw new Zend_Exception('tooltype not set');
		return $this->_toolType;
	}
	
	
	public function setGroupId($groupId)
	{
		$this->_groupId = $groupId;
		return $this;
	}
	
	public function getGroupId()
	{
		if ( $this->_groupId === null ) throw new Zend_Exception('Group ID not set');
		return $this->_groupId;
	}
	
	/**
	 * Constructor
	 */
	public function __construct($groupId = null, $toolType = null)
	{
		parent::__construct();
		if ( $groupId !== null ) $this->setGroupId($groupId);
		if ( $toolType !== null ) $this->setToolType($toolType);
	}
    
    /**
     * return number of all items
     * @return int count
     * @author Yury Zolotarsky
     */
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('zgpu' => 'zanby_groups__privileges_users'), new Zend_Db_Expr('COUNT(*)'))
                ->where('zgpu.group_id = ?', $this->getGroupId())
                ->where('zgpu.tool_type = ?', $this->getToolType());  
        return $this->_db->fetchOne($query);
    }	

	/**
     *  return list of all items
     *  @return array of objects
     *  @author Yury Zolotarsky
     */    
	public function getList()
    {
    	if ( $this->isAsAssoc() ) {
    		$query = $this->_db->select()->distinct();
        	$query->from(array('zgpu' => 'zanby_groups__privileges_users'), array('id' => 'zua.id', 'name' => 'zua.login'))
        			->joinInner(array('zgm' => 'zanby_groups__members'), 'zgpu.member_id = zgm.id')
           	    	->joinInner(array('zua' => 'zanby_users__accounts'), 'zgm.user_id = zua.id')
            	    ->where('zgpu.group_id = ?', $this->getGroupId())
                	->where('zgpu.tool_type = ?', $this->getToolType());			
    	} else {
    		$query = $this->_db->select();
        	$query->from(array('zgpu' => 'zanby_groups__privileges_users'), array())
        			->joinInner(array('zgm' => 'zanby_groups__members'), 'zgpu.member_id = zgm.id', 'zgm.id')
           	    	->joinInner(array('zua' => 'zanby_users__accounts'), 'zgm.user_id = zua.id', 'zua.id')
            	    ->where('zgpu.group_id = ?', $this->getGroupId())
                	->where('zgpu.tool_type = ?', $this->getToolType());
    	}

        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        
        if ( $this->isAsAssoc() ) {
			$members = $this->_db->fetchPairs($query);
        	$items = $members;
			foreach($items as $key=>$item) {
				if (!$this->getGroup()->getMembers()->isMemberExistsAndApproved($key)) {
					unset($members[$key]);
				}
			}
			return $members;
        } else {
        	$items = $this->_db->fetchPairs($query);
        	foreach($items as $key=>$item) {
				if ($this->getGroup()->getMembers()->isMemberExistsAndApproved($item)) {
					$members[$key] = new Warecorp_User('id', $item);
				}        		
        	}
        	return $members;        	
        }
    }
        
    /**
     * add new record in Tool's list
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Yury Zolotarsky
     */
    public function add($user)
    {    	
    	if (!($user instanceof Warecorp_User)) $user = new Warecorp_User('id',$user);
    	$prop['group_id']          = $this->getGroupId();
    	$prop['tool_type']         = $this->getToolType();
    	
    	$memberIds = $this->getGroup()->getMembers()->getMemberId($user);

    	$result = true;
    	foreach($memberIds as $member) {
    		$prop['member_id'] = $member['member'];
    		$prop['parent_group'] = $member['parent'];    		
    		$result = $result && (boolean)$this->_db->insert('zanby_groups__privileges_users', $prop);
    	}
    	return $result;
    }
    
    public function getGroup() 
    {
    	return Warecorp_Group_Factory::loadById($this->_groupId);
    }
    /**
     * remove record from Tool's list
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Yury Zolotarsky
     */
    public function remove($user)
    {
    	if ( $user instanceof Warecorp_User ) $user = $user->getId();
    	$membersIds = $this->getGroup()->getMembers()->getMemberId($user); 
    	$members = array();
    	foreach($membersIds as $member) {
    		array_push($members, $member['member']);
    	}    	
    	$res = $this->_db->delete('zanby_groups__privileges_users', 
    	       $this->_db->quoteInto('member_id in (?)', $members?$members:false).
    	       $this->_db->quoteInto('AND group_id = ?', $this->getGroupId()).
    	       $this->_db->quoteInto('AND tool_type = ?', $this->getToolType())
    	       );
    	return (boolean) $res;
    }
    
    /**
     * check is exist user in Tool's list
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Yury Zolotarsky
     */
    public function isExist($user)
    {
    	if ( $user instanceof Warecorp_User ) $user = $user->getId();
    	if (!$this->getGroup()->getMembers()->isMemberExistsAndApproved($user)) return false;
    	$membersIds = $this->getGroup()->getMembers()->getMemberId($user);    	
    	$members = array();
    	foreach($membersIds as $member) {
    		array_push($members, $member['member']);
    	}
        $query = $this->_db->select();
        $query->from(array('zgpu' => 'zanby_groups__privileges_users'), 'zgpu.member_id')
    			->joinInner(array('zgm' => 'zanby_groups__members'), 'zgpu.member_id = zgm.id')
       	    	->joinInner(array('zua' => 'zanby_users__accounts'), 'zgm.user_id = zua.id')
              	->where('zgpu.member_id in (?)', $members?$members:false)
              	->where('zgpu.group_id = ?', $this->getGroupId())
              	->where('zgpu.tool_type = ?', $this->getToolType());
        return (boolean) $this->_db->fetchOne($query);
    }

}
