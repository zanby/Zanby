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
class BaseWarecorp_User_Addressbook_Friend extends Warecorp_User_Addressbook_Abstract
{
    private $_userId;    

    function __construct($owner_id, $user_id)
    {
        $this->_db = & Zend_Registry::get("DB");
        $this->setClassName(Warecorp_User_Addressbook_eType::FRIEND);        
        $this->_userId = $user_id;
        $this->_contactOwnerId = $owner_id;
        $this->_contactId = $owner_id.'_'.$user_id.'_'.Warecorp_User_Addressbook_eType::FRIEND;
    }
  
    public function getDisplayName()
    {
        $user = $this->getUser();
        return $user->getFirstname() . ' ' . $user->getLastname();
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
    
    public function save()
    {
        throw new Warecorp_Exception('Cannot use save for Friend addressbook');
    }

    public static function loadByEntityId($entityId, $ownerId)
    {
		throw new Warecorp_Exception('Cannot use loadByEntityId for Friend addressbook');
    }	
}
