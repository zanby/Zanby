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
 * Zanby group class.
 * @package    Warecorp_Group_Members
 * @copyright  Copyright (c) 2007
 * @author Artem Sukharev
 */
abstract class BaseWarecorp_Group_Members_Abstract extends Warecorp_Abstract_List 
{
    /**
     * object of group
     */
	protected $_group;
    /**
     * object of group
     */
	protected $_groupId;
	
	/**
	 * members status for select
	 */
	protected $_membersStatus;

	/**
	 * members role for select
	 */
    protected $_membersRole;
	   
	/**
	 * set group id for members object
	 * @param int $newVal
	 * @return Warecorp_Group_Members_Abstract
	 * @author Artem Sukharev
	 */
	public function setGroupId($newVal)
	{
		$this->_groupId = $newVal;
		return $this;
	}
	
	/**
	 * return group id for members object
	 * @return int
     * @throws Zend_Exception
	 * @author Artem Sukharev
	 */
	public function getGroupId()
	{
		if ( $this->_groupId === null ) throw new Zend_Exception('Group Id not set');
		return $this->_groupId;
	}
	
    /**
     * set member status for members object
     * @param array|string|string_delimiter_by_; $newVal
     * @return Warecorp_Group_Members_Abstract
     * @author Artem Sukharev
     */
	public function setMembersStatus($newVal)
	{
		if ( is_array($newVal) ) {
            foreach ($newVal as &$_value) {
            	$_value = trim($_value);
            	if ( !Warecorp_Group_Enum_MemberStatus::isIn($_value) ) {
            	   throw new Zend_Exception('Incorrect member status');
            	}
            }
		} elseif ( strpos($newVal, ';') ) {
			$newVal = explode(';', $newVal);
		    foreach ($newVal as &$_value) {
		    	$_value = trim($_value);
                if ( !Warecorp_Group_Enum_MemberStatus::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect member status');
                }
            }
		} elseif ( $newVal == Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_BOTH ) {
            $newVal = array(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED, Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_PENDING);
		} else {
            if ( !Warecorp_Group_Enum_MemberStatus::isIn($newVal) ) {
                throw new Zend_Exception('Incorrect member status');
            }
            $newVal = array($newVal);
		}
		foreach ($newVal as &$_value) {
            $_value = Warecorp_Group_Enum_MemberStatus::translate($_value);
		}
		$this->_membersStatus = $newVal;
		return $this;
	}
	
    /**
     * return member status for members object
     * @return array
     * @author Artem Sukharev
     */
    public function getMembersStatus()
    {
    	if ( $this->_membersStatus === null ) $this->setMembersStatus(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED);
        return $this->_membersStatus;
    }
    
    /**
     * set member role for members object
     * @param array|string|string_delimiter_by_; $newVal
     * @return Warecorp_Group_Members_Abstract
     * @author Artem Sukharev
     */
    public function setMembersRole($newVal)
    {
    	if ( is_array($newVal) ) {
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_MemberRole::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect member role');
                }
            }
    	} elseif ( strpos($newVal, ';') ) {
            $newVal = explode(';', $newVal);
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_MemberRole::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect member role');
                }
            }
    	} else {
            if ( !Warecorp_Group_Enum_MemberRole::isIn($newVal) ) {
                throw new Zend_Exception('Incorrect member role');
            }
            $newVal = array($newVal);
    	}
    	$this->_membersRole = $newVal;
        return $this;
    }
    
    /**
     * return member role for members object
     * @return array
     * @author Artem Sukharev
     */
    public function getMembersRole()
    {
    	if ( $this->_membersRole === null ) $this->_membersRole = array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST);
        return $this->_membersRole;
    }
    
    /**
     * Constructor
     * @param int|Warecorp_Group_Base group
     * @return void
     * @author Artem Sukharev
     * @author Vitaly Targonsky
     */
    public function  __construct($group)
    {
        parent::__construct();
        if ($group instanceof Warecorp_Group_Base) {
            $this->_group =$group;
            $this->setGroupId($group->getId());
        } else {
            $this->setGroupId($group);
        }
    }
    /**
     * Return group
     * @return Warecorp_Group_Base
     * @author Vitaly Targonsky
     */
    abstract public function getGroup();
    
    /**
     * Проверяет, является ли пользователь членом группы
     * @param int $userId
     * @return bool
     * @author Artem Sukharev
     */
    abstract public function isMemberExists($userId);
    
    /**
     * Проверяет, является ли пользователь членом группы и подтвержден т.е. активен
     * @param int $userId
     * @return bool
     * @author Artem Sukharev
     * @todo добавить проверку статуса
     */
    abstract public function isMemberExistsAndApproved($userId);
    
    /**
     * Проверяет, является ли пользователь членом группы и неподтвержденным т.е. неактивен
     * @param int $userId
     * @return bool
     * @author Artem Sukharev
     * @todo добавить проверку статуса
     */
    abstract public function isMemberExistsAndPending($userId);
    
    /**
     * Returns list of group's members for requested country
     * @param int|Warecorp_Location_Country $country
     * @retrun array of pairs|Warecorp_User
     * @author Alexander Komarovski
     * @author Artem Sukharev
     */
    abstract public function getListByCountry($country);
    
    /**
     * Returns number of group's members for requested country
     * @param int|Warecorp_Location_Country $country
     * @retrun int
     * @author Artem Sukharev
     */
    abstract public function getCountByCountry($country);
    
    /**
     * Returns list of group's members for requested state
     * @param int|Warecorp_Location_State $state
     * @retrun array of pairs|Warecorp_User
     * @author Alexander Komarovski
     * @author Artem Sukharev
     */
    abstract public function getListByState($state);
    
    /**
     * Returns number of group's members for requested state
     * @param int|Warecorp_Location_State $state
     * @retrun int
     * @author Artem Sukharev
     */
    abstract public function getCountByState($state);
    
    /**
     * Returns number of group's members for requested state
     * @param int|Warecorp_User $user
     * @retrun date
     * @author Yury Zolotarsky
     */
    abstract public function getJoinDate($user);    

    /**
     * Change Host of Group
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     */    
	abstract public function changeHost($userId);    
	
    /**
     * get id from members table
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     */
    abstract public function getMemberId($user);	

    /**
     * get id from members table for all members
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     */
    abstract public function getAllMembersId();	    
	/**
     * Add new member to group
     * @param int $user_id
     * @param string $role - host | cohost | member
     * @param string $status - approved, pending
     * @return bool
     * @author Artem Sukharev
     */
    public function addMember( $userId, $role = "member", $status = 'approved' )
    {
        if ( Warecorp_Group_Enum_MemberRole::isIn($role) && Warecorp_Group_Enum_MemberStatus::isIn($status) ) {
            $property = array();
            $objNow = new Zend_Date();
            $objNow->setTimezone('UTC');
            $property['user_id']        = $userId;
            $property['group_id']       = $this->getGroupID();
            $property['creation_date']  = $objNow->toString(Zend_Date::ISO_8601);
            $property['status']         = $role;
            $property['is_approved']    = Warecorp_Group_Enum_MemberStatus::translate($status);
            $result = $this->_db->insert('zanby_groups__members', $property);
            //privileges
            if ($status == 'approved') {
				$gid = $this->_db->quote($this->getGroupId());
				$query = 'insert into zanby_groups__privileges_users
								select distinct zfr.family_id, zfr.child_parent_id, zgpu.tool_type, zgm2.id
								from zanby_family__relations zfr join zanby_groups__privileges_users zgpu on
								(zfr.family_id = zgpu.group_id) join zanby_groups__members zgm1 on
								(zgpu.member_id = zgm1.id) join zanby_groups__members zgm2 on
								(zgm1.user_id = zgm2.user_id)
								where (zfr.child_id = '.$gid.') and ((zgm2.group_id = '.$gid.') 
								or (zgm2.group_id in (select child_id from zanby_family__relations where family_id = '.$gid.'))) 
								and (zgm2.user_id = '.$userId.')';	
				$this->_db->query($query);         	
            }
            return true;
        } else {
            throw new Zend_Exception('Incorrect parameters');
        }
        return false;
    }
    
    /**
     * Set member status to approve
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     */   
    public function approveMember($user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;
        
        $data = array('is_approved' => Warecorp_Group_Enum_MemberStatus::translate('approved'));
        $where = $this->_db->quoteInto('user_id = ?', $userId).
                 $this->_db->quoteInto(' AND group_id = ?', $this->getGroupId());
        $this->_db->update('zanby_groups__members', $data, $where);  
    }
    
    /**
     * 
     * @return unknown_type
     */
    public function getMemberRole()
    {
    	if ( $user instanceof Warecorp_User ) $userId = $user->getId();
    	else $userId = $user;
        
    }
    /**
     * DEPRECATED. Use Warecorp_User::getGroupRole
     * @param int|Warecorp_User $user
     * @return bool
     * @author Pavel Shutin
     */
    public function isHost($user)
    {
    	if ( !($user instanceof Warecorp_User) ) $user = new Warecorp_User('id',$user);

        return $user->getGroupRole($this->getGroup()) === 'host';
    }


    /**
     * DEPRECATED. Use Warecorp_User::getGroupRole
     * @param int|Warecorp_User $user
     * @return bool
     * @author Pavel Shutin
     */
    public function isCohost($user)
    {
    	if ( !($user instanceof Warecorp_User) ) $user = new Warecorp_User('id',$user);

        return $user->getGroupRole($this->getGroup()) === 'cohost';
    }
    
    /**
     * Set code host code for member
     * @param int|Warecorp_User $user
     * @return string
     * @author Artem Sukharev
     */
    public function setHostCode($user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;
        
        list($usec, $sec) = explode(" ", microtime());
        $code = md5(((float)$usec + (float)$sec).$userId.$this->getGroupId());
        $data = array('access_code' => $code);
        $where = $this->_db->quoteInto('user_id = ?', (NULL === $userId) ? new Zend_Db_Expr('NULL') : $userId, 'INTEGER').
                 $this->_db->quoteInto(' AND group_id = ?', $this->getGroupId());
        $this->_db->update('zanby_groups__members', $data, $where);
        return $code;
    }
    
    /**
     * Check host code
     * @param int|Warecorp_User $user_id
     * @param string $access_code
     * @return bool
     * @author Artem Sukharev
     */
    public function checkHostCode($user, $access_code)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;
        
        $query = $this->_db->select();
        $query->from('zanby_groups__members', 'access_code')
               ->where('user_id =? ', (NULL === $userId) ? new Zend_Db_Expr('NULL') : $userId, 'INTEGER')
               ->where('group_id =? ', $this->getGroupId())
               ->where('access_code IS NOT NULL')
               ->where('BINARY access_code  =? ', $access_code);
        $res = $this->_db->fetchOne($query);
        return (bool) $res;

    }
    
    /**
     * Clean host code for all users
     * @param int|Warecorp_User $user_id
     * @return void
     * @author Artem Sukharev
     */
    public function clearHostCodes()
    {
        $data = array('access_code' => new Zend_Db_Expr('NULL'));
        $where = $this->_db->quoteInto('group_id = ?', $this->getGroupId());
        $this->_db->update('zanby_groups__members', $data, $where);
    }
    
    /**
     * Resign member as host
     * @param int|Warecorp_User $user
     * @return void
     * @author Artem Sukharev
     */
    public function resignAsHost($user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;
        
        $data = array('status' => Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER);
        $where = $this->_db->quoteInto('user_id = ?', (NULL === $userId) ? new Zend_Db_Expr('NULL') : $userId, 'INTEGER').
                 $this->_db->quoteInto(' AND group_id = ?', $this->getGroupId());
        $this->_db->update('zanby_groups__members', $data, $where);
    }
    /**
     * Set Member as host
     * @param int|Warecorp_User $user
     * @return void
     * @author Artem Sukharev
     */
    public function setAsHost($user)
    {	
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;
        $privileges = new Warecorp_Group_Privileges($this->getGroupId());
        $privileges->deleteUserFromAllTools($user);
    }
    
     /**
     * Set Member as cohost
     * @param int|Warecorp_User $user
     * @return void
     * @author Halauniou Yauhen
     */
    public function setAsCohost($user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;
        
        $data = array('status' => Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST);
        $where = $this->_db->quoteInto('user_id = ?', $userId).
                 $this->_db->quoteInto(' AND group_id = ?', $this->getGroupId());
        $this->_db->update('zanby_groups__members', $data, $where);
        $privileges = new Warecorp_Group_Privileges($this->getGroupId());
        $privileges->deleteUserFromAllTools($user);        
    }

     /**
     * Set Member as member
     * @param int|Warecorp_User $user
     * @return void
     * @author Halauniou Yauhen
     */
    public function setAsMember($user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;
    	
        $data = array('status' => Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER);
        $where = $this->_db->quoteInto('user_id = ?', $userId).
                 $this->_db->quoteInto(' AND group_id = ?', $this->getGroupId());
        $this->_db->update('zanby_groups__members', $data, $where);
    }
    
    /**
     * Remove Member from Group
     * @param int|Warecorp_User $user
     * @return void
     * @author Artem Sukharev
     * @todo удалить все связи пользователя к групповым артифактам: документам, галереям и т.д.
     * @todo посмотреть, какие еще есть связи, которые надо удалять
     */
    public function removeMember($user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else {
            $userId = $user;
            $user = new Warecorp_User('id', $userId);
        }
        
        //@todo удалить все связи пользователя к групповым артифактам: галереям и т.д. Сделано только для документов
		//dump($this->_db->quoteInto('user_id = ?', $userId).$this->_db->quoteInto('AND group_id = ?', $this->getGroupId()));
		//exit;
        
        /* Delete subscriptions */
        $subscription = Warecorp_DiscussionServer_GroupSubscription::findByGroupAndUserId($this->getGroupId(), $user->getId());
        if(null!==$subscription->getId()) {
            $subscription->delete();
        }
        
        
        $user->getArtifacts()->unshareAllArtifactsFromGroup($this->getGroupId());

        /**
         * Unshare all user's shares to Family
         */
        $exec = $this->_db->prepare("CALL delete_users_shares_from_families({$this->getGroupId()}, {$user->getId()})");
        $exec->execute();

               
        $rows_affected = $this->_db->delete(
            'zanby_groups__members',
            $this->_db->quoteInto('user_id = ?', $userId).
            $this->_db->quoteInto(' AND group_id = ?', $this->getGroupId())
        );
       
        $group = $this->getGroup();
        if ($group instanceof Warecorp_Group_Simple) {
            /**
             * найти все Family в которой находится данная группа
             * если есть Family, искать GroupSubscription нашего чела в этих фемили 
             * если нашлись GroupSubscription, проверить, не находится 
             * ли чел в этой фемили через ещё какую-нибудь группу
             * если да, то не отписывать от этих GroupSubscription
             * иначе отписывать :)
             */ 
            
            if ($group->getFamilyGroups()->getCount()) {
                foreach($group->getFamilyGroups()->getList() as $familyGroup) {
                    $subscription = Warecorp_DiscussionServer_GroupSubscription::findByGroupAndUserId($familyGroup->getId(), $user->getId());
                    if(null!==$subscription->getId()) {
                        if (!array_key_exists($user->getId(),$familyGroup->getMembers()->returnAsAssoc()->getList())) {
                            $subscription->delete();
                        }
                    }
                }
            }
       
        }        
       
    }
    
    /**
     * check if user is host of any group
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Artem Sukharev
     * @author Pavel Shutin
     */
    public static function isHostAnyGroup($user, $includeCoHost = false) 
    {
        if ( !($user instanceof Warecorp_User) ) $user = new Warecorp_User($user);

        foreach ($user->getGroupsMembership() as $status) {
            if ($status == 'host') return true;
            if ($includeCoHost && $status == 'cohost') return true;
        }

        return false;

//    	if ($includeCoHost === false) $statuses = array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST);
//           else $statuses = array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST);
//    	$db = Zend_Registry::get('DB');
//    	if ( $user instanceof Warecorp_User ) $userId = $user->getId();
//        else $userId = $user;
//
//        $query = $db->select();
//    	$query->from(array('zgm' => 'zanby_groups__members'), new Zend_Db_Expr('COUNT(zgm.user_id)'));
//    	$query->where('zgm.user_id = ?', ( NULL === $userId) ? new Zend_Db_Expr('NULL') : $userId, 'INTEGER');
//    	$query->where('zgm.is_approved = ?', Warecorp_Group_Enum_MemberStatus::translate(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED));
//    	$query->where('zgm.status in (?)', $statuses);
//    	return (boolean) $db->fetchOne($query);
    }
    
    /**
     * возвращает список всех пользователей всех групп где указанный пользователь имеет определенную роль
     * @param int|Warecorp_User $user
     * @param array|string|string_delimiter_by_; $userRole
     * @return array of user id
     * @author Artem Sukharev
     */
    public static function getAllGroupMembersByUserAndRole($user, $userRole = 'host;cohost;member')
    {
        $db = Zend_Registry::get('DB');
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else {
            $userId = $user;
            $user = new Warecorp_User('id', $user);
        }
        /*
         * get groups for user
         */
        $itemsFamilyUsers = array();
        $itemsSimpleGroup = array();
        $familyGroups = $user->getGroups()->setTypes('family')->setMembersRole($userRole)->returnAsAssoc()->getList();
        if (!empty($familyGroups)) {
            $familyGroups = array_keys($familyGroups);
            $query = $db->select();
            $query->from(array('vfu' => 'view_family__users'), array(new Zend_Db_Expr('DISTINCT vfu.user_id'), 'vfu.user_id'));
            if ( sizeof($familyGroups) != 0 ) {
                $query->where('vfu.family_id IN (?)', $familyGroups);
            }
            $itemsFamilyUsers = $db->fetchPairs($query);
        }
        
        $simpleGroups = $user->getGroups()->setTypes('simple')->setMembersRole($userRole)->returnAsAssoc()->getList();
        if (!empty($simpleGroups)) {
            $simpleGroups = array_keys($simpleGroups);
            $query = $db->select();
            $query->from(array('zgm' => 'zanby_groups__members'), array(new Zend_Db_Expr('DISTINCT zgm.user_id'), 'zgm.user_id'));
            if ( sizeof($simpleGroups) != 0 ) {
                $query->where('zgm.group_id IN (?)', $simpleGroups);
            }
            $itemsSimpleGroup = $db->fetchPairs($query);
        }

        $items = $itemsFamilyUsers + $itemsSimpleGroup;
        return $items;
    }

    /**
     * check if user is host/coHost of any owner's groups
     * @param int|Warecorp_User $owner, int|Warecorp_User $user
     * @return boolean
     * @author Yury Zolotarsky
     */    
    static public function isUserGroupsOrganizer($owner, $user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
          else $userId = $user;
        if ( $owner instanceof Warecorp_User ) $ownerId = $owner->getId();
          else $ownerId = $owner;
    	$db = Zend_Registry::get("DB");
		$sql = $db->select() 
		          ->from(array('zgm1' => 'zanby_groups__members'), array())
                  ->join(array('zgm2' => 'zanby_groups__members'), 'zgm1.group_id = zgm2.group_id', array('zgm2.user_id'))
		          ->where('zgm2.user_id = ?', (NULL === $userId) ? new Zend_Db_Expr('NULL') : $userId, 'INTEGER')
		          ->where('zgm2.status in (?)', array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST))
		          ->where('zgm2.is_approved = ?', Warecorp_Group_Enum_MemberStatus::translate(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED))		          
		          ->where('zgm1.user_id = ?', (NULL === $ownerId) ? new Zend_Db_Expr('NULL') : $ownerId, 'INTEGER');
		$result = (boolean)$db->fetchOne($sql); 
		return $result;  
    }

    /**
     * check if user is a member of any owner's groups
     * @param int|Warecorp_User $owner, int|Warecorp_User $user
     * @return boolean
     * @author Yury Zolotarsky
     */        
    static public function isUserGroupsMember($owner, $user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;
        if ( $owner instanceof Warecorp_User ) $ownerId = $owner->getId();
        else $ownerId = $owner;
    	$db = Zend_Registry::get("DB");
		$sql = $db->select() 
		          ->from(array('zgm1' => 'zanby_groups__members'), array())
                  ->join(array('zgm2' => 'zanby_groups__members'), 'zgm1.group_id = zgm2.group_id', array('zgm2.user_id'))
		          ->where('zgm2.user_id = ?', (NULL === $userId) ? new Zend_Db_Expr('NULL') : $userId, 'INTEGER')
		          ->where('zgm2.is_approved = ?', Warecorp_Group_Enum_MemberStatus::translate(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED))		          
		          ->where('zgm1.user_id = ?', $ownerId);   
		$result = (boolean)$db->fetchOne($sql);   
		$sql = $db->select() 
		          ->from(array('vfu1' => 'view_family__users'), array())
                  ->join(array('vfu2' => 'view_family__users'), 'vfu1.family_id = vfu2.family_id', array('vfu2.user_id'))
		          ->where('vfu2.user_id = ?', (NULL === $userId) ? new Zend_Db_Expr('NULL') : $userId, 'INTEGER')
		          ->where('vfu2.is_approved = ?', Warecorp_Group_Enum_MemberStatus::translate(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED))		          
		          ->where('vfu1.user_id = ?', $ownerId);   
		return ($result || (boolean)$db->fetchOne($sql));    
    } 
    
    /**
     * get user-group link id
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Yury Zolotarsky
     */        
    public function getId($user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
          else $userId = $user;
    	$query = $this->_db->select() 
		          ->from(array('zgm' => 'zanby_groups__members'), 'zgm.id')
		          ->where('zgm.user_id = ?', (NULL === $userId) ? new Zend_Db_Expr('NULL') : $userId, 'INTEGER')
		          ->where('zgm.group_id = ?', $this->getGroupId());
		return $this->_db->fetchOne($query);    	
    }
    
    /**
     * 
     * @param $memberId
     * @return unknown_type
     */
    public static function getMemberData($memberId)
    {
    	$db = Zend_Registry::get("DB");
    	$query = $db->select() 
		          ->from(array('zgm' => 'zanby_groups__members'), '*')
		          ->where('zgm.id = ?', $memberId);
        return $db->fetchRow($query);    	    	
    }
}
