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
 * @created 25-сен-2007 18:39:48
 */
class BaseWarecorp_User_Addressbook_User extends Warecorp_User_Addressbook_Abstract
{
    /**
     * registered user object - Warecorp_User
     */
//    private $_user;
    /**
     * registereg user id
     */
    private $_userId;

    function __construct($key = null, $val = null)
    {
        parent::__construct($key, $val);
        
        if ($key !== null && $val !== null && $this->isExist) {
            if ($this->getClassName() != Warecorp_User_Addressbook_eType::USER) 
                throw new Warecorp_Exception('Incorrect contact type');
            $this->_userId = $this->getEntityId();
        } else {
            $this->_className = Warecorp_User_Addressbook_eType::USER;
        }
    }

    /**
     * registered user object - Warecorp_User
     */
    public function getUser()
    {   
//        if ($this->_user == null) $this->_user = new Warecorp_User('id', $this->getUserId());
    	return new Warecorp_User('id', $this->getUserId());
    }

    /**
     * registereg user id
     */
    public function getUserId()
    {
        if ($this->_userId === null) throw new Warecorp_Exception('Field \'_userId\' does not set.');
    	return $this->_userId;
    }

    /**
     * registereg user id
     * 
     * @param newVal
     */
    public function setUserId($newVal)
    {
        if ($newVal !== $this->_userId) $this->_userId = $newVal;
    }

    public function getDisplayName()
    {
        $user = $this->getUser();
        return $user->getFirstname() . ' ' . $user->getLastname();
    }
    
    public function getEmail()
    {
        $user = $this->getUser();
        return $user->getEmail();
    }

    public function getEmails()
    {
        $user = $this->getUser();
        return array($user->getEmail());
    }
    
    public function getEmailsAsString()
    {
        $user = $this->getUser();
        return $user->getEmail();
    }
   
    public function save()
    {
        $this->setEntityId($this->getUserId());
        $this->setClassName(Warecorp_User_Addressbook_eType::USER);
        parent::save();
    }
    
    public static function loadByEntityId($entityId, $ownerId)
    {
        $db = & Zend_Registry::get("DB");
        $query = $db->select();
        $query->from('zanby_addressbook__contacts', 'id')
           ->where('owner_id = ?', $ownerId) 
           ->where('entity_id = ?', $entityId)
           ->where('classname = ?', Warecorp_User_Addressbook_eType::USER);
        $contactId = $db->fetchOne($query);
        $user = new Warecorp_User_Addressbook_User('id', $contactId);
        if (!$user->isExist) return false;
        else return $user;
    }
}
