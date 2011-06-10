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
 * @author Yury Zolotarsky
 */
class BaseWarecorp_User_Addressbook_GroupMember extends Warecorp_User_Addressbook_Abstract
{
    private $_groupId;
    private $_userId;

    function __construct($user_id, $group_id)
    {
        $this->_db = & Zend_Registry::get("DB");
        $this->setClassName(Warecorp_User_Addressbook_eType::GROUP_MEMBER);
        $this->_groupId = $group_id;
        $this->_userId = $user_id;
        $this->_contactId = $group_id.'_'.$user_id.'_'.Warecorp_User_Addressbook_eType::GROUP_MEMBER;
    }

    /**
     *
     *
     */
    public function getContactOwnerId()
    {
        return $this->getGroup()->getHost()->getId();
    }

    /**
     *
     *
     */
    public function getContactOwner()
    {
		return $this->getGroup()->getHost();
    }

    /**
     * registered group object - Warecorp_Group_Base
     */

    public function getDisplayName()
    {
        $user = $this->getUser();
        return $user->getFirstname() . ' ' . $user->getLastname();
    }

    public function getGroup()
    {
    	return Warecorp_Group_Factory::loadById($this->getGroupId());
    }

    public function getUserId()
    {
        if ($this->_userId === null) throw new Warecorp_Exception('Field \'_userId\' does not set.');
    	return $this->_userId;
    }

    public function getUser()
    {
    	return new Warecorp_User('id', $this->_userId);
    }

    /**
     * registered group id
     */
    public function getGroupId()
    {
        if ($this->_groupId === null) throw new Warecorp_Exception('Field \'_groupId\' does not set.');
    	return $this->_groupId;
    }

    public function getEmails()
    {
        $user = $this->getUser();
        return $user->getEmail();
    }

    public function getEmail()
    {
        $user = $this->getUser();
        return $user->getEmail();
    }

    public function getEmailsAsString()
    {
    	return $this->getEmails();
    }

    public function getParentContactLists()
    {
        $query = $this->_db->select();
        $query->from(array('zgm1' => 'zanby_groups__members'), 'zgm1.group_id')
        	->join(array('zgm2' => 'zanby_groups__members'), 'zgm1.group_id = zgm2.group_id')
        	->join(array('zua' => 'zanby_users__accounts'), 'zgm2.user_id = zua.id')
        	->where('zgm1.user_id = ?', $this->getContactOwnerId())
            ->where('zgm1.status = ?', 'host')
            ->where('zgm2.user_id = ?', $this->getUserId())
            ->where('zua.status = ?', 'active');
        $contactLists = $this->_db->fetchCol($query);
        foreach ($contactLists as &$contactList) {
            $contactList = new Warecorp_User_Addressbook_Group($contactList);
        }
        return $contactLists;
    }

    public function save()
    {
        throw new Warecorp_Exception('Cannot use save for GroupMember addressbook');
    }

    public static function loadByEntityId($entityId, $ownerId)
    {
		throw new Warecorp_Exception('Cannot use loadByEntityId for GroupMember addressbook');
    	/*        $db = & Zend_Registry::get("DB");
        $query = $db->select();
        $query->from('zanby_addressbook__contacts', 'id')
           ->where('owner_id = ?', $ownerId)
           ->where('entity_id = ?', $entityId)
           ->where('classname = ?', Warecorp_User_Addressbook_eType::GROUP);
        $contactId = $db->fetchOne($query);
        $group = new Warecorp_User_Addressbook_Group('id', $contactId);
        if (!$group->isExist) return false;
        else return $group;*/
    }
}
