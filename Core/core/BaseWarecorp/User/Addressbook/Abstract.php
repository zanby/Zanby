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
 * @author Dmitry Strupinsky, Andrew Peresalyak, Ivan Khmurchik
 * @version 1.0
 * @created 25-сен-2007 16:21:25
 */
abstract class BaseWarecorp_User_Addressbook_Abstract extends Warecorp_Data_Entity
{
    /**
     * class name for current realization
     */
    protected $_className;
    /**
     * contact id
     */
    protected static $_useCustomNames = false;
    protected $_contactId;
    protected $_creationDate;
    protected $_entityId;
    protected $_contactOwnerId;

    function __construct($key = null, $val = null)
    {
        parent::__construct('zanby_addressbook__contacts', array(
        'id'              => '_contactId',
        'entity_id'       => '_entityId',
        'classname'       => '_className',
        'owner_id'        => '_contactOwnerId',
        'creation_date'   => '_creationDate'));
        
        if ($key !== null){
            $this->pkColName = $key;                        
            $this->loadByPk($val);
        }
    }

    
    public static function setUseCustomNames($value)
    {
        self::$_useCustomNames = $value;
    }
    
    public static function getUseCustomNames()
    {
        return self::$_useCustomNames;
    }

    /**
     * class name for current realization
     */
    public function getClassName()
    {
    	return $this->_className;
    }

    /**
     * contact id
     */
    public function getContactId()
    {
    	return $this->_contactId;
    }

    public function getCreationDate()
    {
    	return $this->_creationDate;
    }
    
    /**
     * return name of contact for display,
     * if object is user - fist and last name
     * if object if mailing list - name of list etc.
     */
    abstract public function getDisplayName();
    /**
     * return array of emails from contact
     * if contact object is user - array with one item, user email
     * if mailing list - array of all emails from list
     */
    abstract public function getEmails();
    
    abstract public function getEmailsAsString();

    public function getEntityId()
    {
    	return $this->_entityId;
    }

    public function getContactOwnerId()
    {
    	return $this->_contactOwnerId;
    }
    
    public function getContactOwner()
    {
        return new Warecorp_User('id', $this->_contactOwnerId);
    }

    /**
     * class name for current realization
     * 
     * @param newVal
     */
    public function setClassName($newVal)
    {
    	$this->_className = $newVal;
    }

    /**
     * 
     * @param newVal
     */
    public function setCreationDate($newVal)
    {
    	$this->_creationDate = $newVal;
    }

    /**
     * 
     * @param newVal
     */
    public function setEntityId($newVal)
    {
    	$this->_entityId = $newVal;
    }

    /**
     * 
     * @param newVal
     */
    public function setContactOwnerId($newVal)
    {
    	$this->_contactOwnerId = $newVal;
    }
    
    public function save()
    {
        if ($this->_creationDate === null) $this->_creationDate = new Zend_Db_Expr('NOW()');
        if ($this->_contactId === null) $add = true;
    	parent::save();
    	
    	$list = new Warecorp_User_Addressbook_List();
    	$mainContactListId = $list->getMainContactListId($this->getContactOwnerId());
    	if (!$mainContactListId) throw new Warecorp_Exception('Main contact list doesn\'t exist.');
    	$mainContactList = new Warecorp_User_Addressbook_ContactList(false, 'id', $mainContactListId);
    	
    	if (isset($add)) $mainContactList->addContact($this);
    }
    
    /**
     * get all contact lists for contact
     */
    public function getParentContactLists()
    {
        $query = $this->_db->select();
        $query->from('view_addressbook__items', 'contactlist_id')
           ->where('owner_id = ?', ($this->_contactOwnerId === NULL) ? new Zend_Db_Expr('NULL') : $this->_contactOwnerId)
           ->where('contact_id = ?', ($this->_contactId === NULL) ? new Zend_Db_Expr('NULL') : $this->_contactId)
           ->where('ismain = ?', 0);
        $contactLists = $this->_db->fetchCol($query);
        foreach ($contactLists as &$contactList) {
            $contactList = Warecorp_User_Addressbook_ContactList::loadByEntityId($contactList, $this->_contactOwnerId);
        }
        return $contactLists;
    }
    
    public function getParentContactListsAsString()
    {
        $contactLists = $this->getParentContactLists();
        foreach ($contactLists as &$contactList)
        {
            $contactList = $contactList->getDisplayName();
        }
        return implode(';', $contactLists);
    }
    
    public static function isUserInAddressBook($owner, $user, $ismain = 1)
    {
        $db = & Zend_Registry::get("DB");
        $query = $db->select();
        $query->from('view_addressbook__items')
           ->where('owner_id = ?', $owner->getId()) 
           ->where('entity_type = ?', Warecorp_User_Addressbook_eType::USER) 
           ->where('entity_id = ?', $user->getId());
        if ($ismain == 1) $query->where('ismain = ?', $ismain); 
        if ($db->fetchOne($query)) return true;
        return false; 	    		
    }
    
    public static function isEqual($contact1, $contact2)
    {
    	if ($contact1->getClassName() == Warecorp_User_Addressbook_eType::USER || $contact1->getClassName() == Warecorp_User_Addressbook_eType::GROUP_MEMBER) {
    		if ($contact2->getClassName() == Warecorp_User_Addressbook_eType::USER || $contact2->getClassName() == Warecorp_User_Addressbook_eType::GROUP_MEMBER) {
    			if ($contact1->getUserId() == $contact2->getUserId()) return true;
    		} else return false;
    	} else {
    		if ($contact2->getClassName() == $contact1->getClassName()) {
    			if ($contact2->getEntityId() == $contact1->getEntityId()) return true;
    		}
    		return false;
    	}
    	return false;
    }
    
    abstract static public function loadByEntityId($entityId, $ownerId);
    
    
}
