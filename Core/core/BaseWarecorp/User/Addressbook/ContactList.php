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
 * @created 25-сен-2007 18:39:47
 */
class BaseWarecorp_User_Addressbook_ContactList extends Warecorp_User_Addressbook_Abstract
{
    
    /**
     * id of contact list
     * это id самого листа, если его рассматривать не как контакт,
     * а как лист
     */
    private $_contactListId;
    /**
     * object of Warecorp_User_Addressbook_Contact_List
     */
    private $_contacts;
    /**
     * is contact list main level (addressbook)
     */
    private $_isMain = '0';
    /**
     * name of contact list
     */
    private $_contactListName;
    /**
     * ownerId of contact list
     */
    private $_contactListOwnerId;
    /**
     * is object used as contact or contact list
     */
    private $_isContact;
 

    function __construct($isContact = true, $key = null, $val = null)
    {
        $this->_isContact = $isContact;
        if ($isContact) {
            parent::__construct($key, $val);
            if ($key !== null && $val !== null && $this->isExist) {
                if ($this->getClassName() != Warecorp_User_Addressbook_eType::CONTACT_LIST) 
                    throw new Warecorp_Exception('Incorrect contact type');
                $query = $this->_db->select();
        	    $query->from('zanby_addressbook__contactlists', '*')
        	          ->where('id = ?', $this->getEntityId());        	    
        	    $contactList = $this->_db->fetchRow($query);
        	    if ( $contactList ) {
        	       $this->setContactListId($contactList['id']);
        	       $this->setContactListName($contactList['name']);
        	       $this->setContactListOwnerId($contactList['owner_id']);
        	       $this->setIsMain($contactList['ismain']);
        	    }
            } else {
                $this->_className = Warecorp_User_Addressbook_eType::CONTACT_LIST;
            }
        }
        else {
            $this->_db = & Zend_Registry::get("DB");
            if ($key !== null && $val !== null) {
                $query = $this->_db->select();
        	    $query->from('zanby_addressbook__contactlists', '*');
        	    $query->where("$key = ?", $val);
        	    /**
        	     * @todo добавить проверку на ismain=1
        	     */
        	    $contactList = $this->_db->fetchRow($query);
        	    if ( $contactList ) {
        	       $this->setContactListId($contactList['id']);
        	       $this->setContactListName($contactList['name']);
        	       $this->setContactListOwnerId($contactList['owner_id']);
        	       $this->setIsMain($contactList['ismain']);
        	    }
            }
        }
    }


    /**
     * add new contact to contact list
     * 
     * @param contact
     */
    public function addContact(Warecorp_User_Addressbook_Abstract $contact)
    {
        if ( !$contact->getContactId() ) throw new Warecorp_Exception('Contact is not saved.');
        if ( $this->isContactExist($contact->getContactId()) ) return $this;
        $data = array();
    	$data['contactlist_id'] = $this->getContactListId();
    	$data['contact_id'] = $contact->getContactId();
    	$rows_affected = $this->_db->insert('zanby_addressbook__contactlist_contact_relations', $data);
    	return $this;
    }

    public function isContactExist($contact_id)
    {
         $query = $this->_db->select();
         $query->from('view_addressbook__items', 'contact_id')
               ->where('contactlist_id = ?', $this->getContactListId()) 
               ->where('contact_id = ?', $contact_id);
         $res = $this->_db->fetchOne($query);
         if ($res) return $res;
         return false; 	
    }
    
    /**
     * id of contact list
     * это id самого листа, если его рассматривать не как контакт,
     * а как лист
     */
    public function getContactListId()
    {
        if ($this->_contactListId === null) throw new Warecorp_Exception('field \'contactListId\' not set');
        return $this->_contactListId;
    }

    public function getContacts()
    {
        return new Warecorp_User_Addressbook_List($this->_contactListId, $this->_contactOwnerId);
//        $addressbookList = new Warecorp_User_Addressbook_List($this->_contactListId);
//        $abstract = new Warecorp_User_Addressbook_Abstract();
//        $list = new Warecorp_User_Addressbook_List();
//        $addressbookId = $list->getMainContactListId($this->getContactListOwnerId());
//        return $addressbookList->getList($cond);
    }
    
    public function getEmails() {}
    public function getEmailsAsString() {}
    
    public function getDisplayName() 
    {
        return $this->_contactListName;
    }
    
    /**
     * is contact list creating as contact
     */
    public function getIsContact()
    {
    	return $this->_isContact;
    }

    /**
     * is contact list main level (addressbook)
     */
    public function getIsMain()
    {
    	return $this->_isMain;
    }

    /**
     * name of contact list
     */
//    public function getContactListName()
//    {
//    	return $this->_contactListName;
//    }
    
    /**
     * ownerId of contact list
     */
    public function getContactListOwnerId()
    {
    	return $this->_contactListOwnerId;
    }

    /**
     * remove all contacts from contact list
     */
    public function removeAllContacts()
    {
	    $where = $this->_db->quoteInto('contactlist_id = ?', $this->getContactListId());
        $rows_affected = $this->_db->delete('zanby_addressbook__contactlist_contact_relations', $where);
        if ($this->getIsMain() == '1') {
            foreach ($this->getContacts() as $contact) {
            	$contact->delete();
            }            
        }
    }

    /**
     * remove contact from contact list
     * 
     * @param contact
     */
    public function removeContact(Warecorp_User_Addressbook_Abstract $contact)
    {
        if ($contact->getContactId() === null) throw new Warecorp_Exception("not set contact_id");
        $where = $this->_db->quoteInto('contact_id = ?', $contact->getContactId());
	    $where .= " AND " . $this->_db->quoteInto('contactlist_id = ?', $this->getContactListId());
        $rows_affected = $this->_db->delete('zanby_addressbook__contactlist_contact_relations', $where); 
        if ($this->getIsMain() == '1') {
            $contact->delete();
        }
    }

    /**
     * id of contact list
     * это id самого листа, если его рассматривать не как контакт,
     * а как лист
     * 
     * @param newVal
     */
    private function setContactListId($newVal)
    {
        if ($newVal !== $this->_contactListId) $this->_contactListId = $newVal;
    	return $this;
    }
    
    /**
     * is contact list main level (addressbook)
     * 
     * @param newVal
     */
    public function setIsMain($newVal)
    {
        if ($newVal !== $this->_isMain) $this->_isMain = $newVal;
    	return $this;
    }
    
//    /**
//     * is contact list creating as contact
//     */
//    public function setIsContact($newVal)
//    {
//        $this->_isContact = $newVal;
//    	return $this;
//    }
    
    /**
     * name of contact list
     * 
     * @param newVal
     */
    public function setContactListName($newVal)
    {
        if ($newVal !== $this->_contactListName) $this->_contactListName = $newVal;
    	return $this;
    }
    
    /**
     * name of contact list
     * 
     */
    public function getContactListName()
    {
        return $this->_contactListName;
    }
    
    /**
     * ownerId of contact list
     * 
     * @param newVal
     */
    public function setContactListOwnerId($newVal)
    {
        if ($newVal !== $this->_contactListOwnerId) $this->_contactListOwnerId = $newVal;
        if ($newVal !== $this->_contactOwnerId) $this->_contactOwnerId = $newVal;
    	return $this;
    }
    
    /**
     * save contact list object
     */
    public function save()
    {
        $data = array();
	    $data['owner_id'] = $this->getContactListOwnerId();
	    $data['name']     = $this->getContactListName();
	    if ($this->getIsMain() == '1' && $this->isMainContactListExist()) 
	       throw new Warecorp_Exception('Main contact list is already exist.');
	    $data['ismain']   = $this->getIsMain();
	    if ($this->_contactListId !== null) {
	        $where = $this->_db->quoteInto('id = ?', $this->getContactListId());
            $rows_affected = $this->_db->update('zanby_addressbook__contactlists', $data, $where);
	    } else {
    	    $rows_affected = $this->_db->insert('zanby_addressbook__contactlists', $data);
            $this->setContactListId($this->_db->lastInsertId());
	    }
        if ($this->getIsContact()) {
            $this->setEntityId($this->getContactListId());
            $this->setClassName(Warecorp_User_Addressbook_eType::CONTACT_LIST);
            if ($this->_contactOwnerId === null) $this->setContactOwnerId($this->getContactListOwnerId());
            parent::save();
        }
    }
    
    /**
     * delete contact list object
     *
     */
    public function delete()
    {
        /**
        * Remove Contact List Relation From Invitation objects
        * Expand Contact list as members lagin or email and then
        * remove contact list
        */
        Warecorp_ICal_Attendee_List::onContactListRemoved($this);

        if ($this->getIsMain() == '1') {
            if (count($this->getContacts()) != 0) {
                foreach ($this->getContacts() as $contact) {
            	   $contact->delete();
                }            	
            }
            
        }
        $where = $this->_db->quoteInto('id = ?', $this->getContactListId());
        $rows_affected = $this->_db->delete('zanby_addressbook__contactlists', $where);
        if ($this->getIsContact()) parent::delete();
        
        
        
    }
    
    public static function isContactListExistById($contactListId)
    {
        $db = & Zend_Registry::get("DB");
        $query = $db->select();
        $query->from('zanby_addressbook__contactlists', 'id')
           ->where('id = ?', $contactListId === null ? new Zend_Db_Expr('NULL') : $contactListId) 
           ->where('ismain = ?', '0');
        $res = $db->fetchOne($query);
        if ( $res ) return $res;
        return false;     
    }
    
    public static function isContactListExist($owner, $contactListName)
    {
        $db = & Zend_Registry::get("DB");
        $query = $db->select();
        $query->from('zanby_addressbook__contactlists', 'id')
           ->where('owner_id = ?', $owner) 
           ->where('name = ?', $contactListName) 
           ->where('ismain = ?', '0');
        $res = $db->fetchOne($query);
        if ( $res ) return $res;
        return false; 	
    }
    
    public function isMainContactListExist()
    {
        $query = $this->_db->select();
        $query->from('zanby_addressbook__contactlists', 'id')
           ->where('owner_id = ?', $this->getContactListOwnerId()) 
           ->where('ismain = ?', '1');
        if ($this->_db->fetchCol($query)) return true;
        return false; 	
    }

    public function isContactUserExist($owner, $email)
    {
        $db = & Zend_Registry::get("DB");
        $query = $db->select();
        $query->from('view_addressbook__items', 'contact_id')
           ->where('owner_id = ?', $owner) 
           ->where('classname = ?', Warecorp_User_Addressbook_eType::USER) 
           ->where('email = ?', $email); 
        if ($this->getContactListId()) {
       	    $query->where('contactlist_id = ?', $this->getContactListId());
        }
        if ($db->fetchOne($query)) return true;
        return false; 	
    }
    
    /**
     * using in autocomplete
     * @return Array - Contact list
     * @author Andrew Peresalyak
     */
    public function getContactList($filter = false)
    {
		//require_once("/home/stepanov/work_copy/core_zanby5/core/FirePHPCore/FirePHP.class.php");
		//$fb = FirePHP::getInstance(true);

		$search = new Warecorp_User_Addressbook_Search();
		$search->searchByCriterions(array( "login" => $filter, "owner_id" => $this->_contactListOwnerId ));
		//$fb->fb($search);

		/* Left for reference for a while... - kstep 
		*/

		$contacts = array();
        foreach ($search->resByCriterions as $contact) {
            $contacts[] = new Warecorp_User('id', $contact);
        }
		unset($search);
        return $contacts;
    }
    
    public static function alterForOutput($contacts)
    {
         foreach ($contacts as &$contact)
         {
            if ($contact instanceof Warecorp_User_Addressbook_ContactList) {
                $contact->displayName = $contact->getDisplayName() . " (LIST)";
                $contact->url         = $contact->getContactOwner()->getUserPath('addressbookmaillist/id') . $contact->getContactId() . '/';
            }
            if ($contact instanceof Warecorp_User_Addressbook_User) {
                $contact->displayName = $contact->getDisplayName() . ' (' . $contact->getUser()->getLogin() . ')';
//                $contact->url         = $contact->getUser()->getUserPath('addressbookaddcontact/id') . $contact->getUser()->getId() . '/';
                $contact->url         = $contact->getUser()->getUserPath('profile');
                $contact->profile     = $contact->getUser()->getUserPath('profile');
                $contact->avatar      = $contact->getUser()->getAvatar();
            }
            if ($contact instanceof Warecorp_User_Addressbook_Group) {
                $contact->displayName = $contact->getDisplayName();
                $contact->url         = $contact->getContactOwner()->getUserPath('addressbookgroup/id') . $contact->getGroupId() . '/';
                $contact->profile     = $contact->getGroup()->getGroupPath('summary');
                $contact->avatar      = $contact->getGroup()->getAvatar();
            }
            if ($contact instanceof Warecorp_User_Addressbook_GroupMember) {
                $contact->displayName = $contact->getDisplayName() . ' (' . $contact->getUser()->getLogin() . ')';
                $contact->url         = $contact->getUser()->getUserPath('profile');
                $contact->profile     = $contact->getUser()->getUserPath('profile');
                $contact->avatar      = $contact->getUser()->getAvatar();
            }
            if ($contact instanceof Warecorp_User_Addressbook_Friend) {
                $contact->displayName = $contact->getDisplayName() . ' (' . $contact->getUser()->getLogin() . ')';
                $contact->url         = $contact->getUser()->getUserPath('profile');
                $contact->profile     = $contact->getUser()->getUserPath('profile');
                $contact->avatar      = $contact->getUser()->getAvatar();
            }            
            if ($contact instanceof Warecorp_User_Addressbook_CustomUser) {
            	$contact->displayName = $contact->getDisplayName();
                $contact->url         = $contact->getContactOwner()->getUserPath('addressbookaddcontact/id') . $contact->getContactId() . '/';
            }
        }    
        return $contacts;
    }
    
    public static function getContactLists($ownerId)
    {
    	$addressbookId = Warecorp_User_Addressbook_List::getMainContactListId($ownerId);
        $list = new Warecorp_User_Addressbook_List($addressbookId);
        $contactList = $list->getList(array(Warecorp_User_Addressbook_eType::CONTACT_LIST, Warecorp_User_Addressbook_eType::GROUP));
        
        foreach ($contactList as &$contact)
        {
            $contact->displayName = $contact->getDisplayName();
            if ($contact instanceof Warecorp_User_Addressbook_ContactList) {
            	$contact->url = $contact->getContactOwner()->getUserPath('addressbookmaillist/id') . $contact->getContactId() . '/';
            } else {
                $contact->url = $contact->getContactOwner()->getUserPath('addressbookgroup/id') . $contact->getGroupId() . '/';
            }
        } 
        return $contactList;   
    }
    
    public static function arrayToString($array, $separator = ";", $implodeSeparator = "")
	{
	    if (!is_array($array)) throw new Warecorp_Exception('arrayToString function: incorrect first parameter');
	    foreach ($array as &$value)
	    {
	        if ($value != null) $value .= $separator;
	    }
	    return implode($implodeSeparator, $array);
	}

	/**
	 * Convert string to array, use separator as delimiter
	 *
	 * @param string $string string to convert
	 * @param mixed $separator separator string or array
	 * @return Array
	 */
	public static function stringToArray($string, $separator = ";")
	{
	    if (!is_string($string)) throw new Warecorp_Exception('stringToArray function: incorrect first parameter');
		if (is_array($separator)) {
			if (!count($separator)) throw new Warecorp_Exception('stringToArray function: incorrect separator');
			$sepArray = $separator;
			$i = 0;
			$expr = array();
			foreach ($sepArray as $sep) {
				if (!$i) {
					$separator = $sep;
					$i++;
					continue;
				}
				$expr[] = preg_quote($sep);
			}
			if (count($expr)) {
	    		$string = preg_replace('/((['. implode('])|([', $expr). ']))+/', $separator, $string);
			}
		}
	    $tempArray = array_map('trim', explode($separator, $string));
	    $array = array();
	    foreach ($tempArray as $value)
	    {
	        if ($value != null) $array[] = $value;
	    }
	    return $array;
	}
	
	public function addContacts(array $contacts)
	{
	    foreach ($contacts as $contact)
	    {
	        $this->addContact($contact);
	    }
	}
	
	public static function loadByEntityId($entityId, $ownerId)
    {
        $db = & Zend_Registry::get("DB");
        $query = $db->select();
        $query->from('zanby_addressbook__contacts', 'id')
           ->where('owner_id = ?', $ownerId) 
           ->where('entity_id = ?', $entityId)
           ->where('classname = ?', Warecorp_User_Addressbook_eType::CONTACT_LIST);
        $contactId = $db->fetchOne($query);
        $contactList = new Warecorp_User_Addressbook_ContactList(true, 'id', $contactId);
        if (!$contactList->isExist) return false;
        else return $contactList;
    }
}
