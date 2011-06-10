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
 * @author Yury Zolotarsky, Dmitry Strupinsky, Andrew Peresalyak, Ivan Khmurchik
 * @version 1.0
 * @created 25-сен-2007 18:39:48
 */
class BaseWarecorp_User_Addressbook_Group extends Warecorp_User_Addressbook_Abstract
{
    /**
     * registered group object - Warecorp_Group_Base
     */
//    private $_group;
    /**
     * registered group id
     */
    private $_groupId;

    function __construct($groupId)
    {
        //parent::__construct($key, $val);
        //if ($key !== null && $val !== null) {
        $this->_db = & Zend_Registry::get("DB");
        $this->setClassName(Warecorp_User_Addressbook_eType::GROUP);
        $this->_groupId = $groupId;
        $this->_contactId = $groupId.'_'.Warecorp_User_Addressbook_eType::GROUP;
    }

    /**
     * 
     * 
     */    
    public function getContactOwnerId()
    {
		$group = $this->getGroup();		
		return $group->getHost()->getId();
    }
    /**
     * 
     * 
     */    
    public function getContactOwner()
    {
		$group = $this->getGroup();		
		return $group->getHost();
    }
    
    /**
     * registered group object - Warecorp_Group_Base
     */
       
    public function getGroup()
    {
    	return Warecorp_Group_Factory::loadById($this->getGroupId());
    }

    /**
     * registered group id
     */
    public function getGroupId()
    {
        if ($this->_groupId === null) throw new Warecorp_Exception('Field \'_groupId\' does not set.');
    	return $this->_groupId;
    }

    public function getEntityId()
    {
    	return $this->getGroupId();
    }
    
    /**
     * registered group id
     * 
     * @param newVal
     */
    public function setGroupId($newVal)
    {
        if ($newVal !== $this->_groupId) $this->_groupId = $newVal;
    }
    
    public function getDisplayName()
    {
        $result = "";
        $group = $this->getGroup();
        //var_dump(Warecorp_User_Addressbook_ContactList::getUseCustomNames());
        if (Warecorp_User_Addressbook_ContactList::getUseCustomNames()){
            if ($group->getGroupType() == 'family') {
                return $group->getName()." (NETWORKS)";
            }
            else{
                return $group->getName()." (GROUP)";
            }    
        }
        else{
                return $group->getName()." (GROUP)";
        }
    }
    
    public function getEmails()
    {
        //get emails
    }
    
    public function getEmailsAsString() {}
    
    public function getContacts()
    {
        return $this->getGroup()->getMembers();
    }
    
    public function save()
    {
        throw new Warecorp_Exception('Cannot use save for Group addressbook');
    }

    public static function loadByEntityId($entityId, $ownerId)
    {
		throw new Warecorp_Exception('Cannot use loadByEntityId for Group addressbook');
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
