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
class BaseWarecorp_User_Addressbook_CustomUser extends Warecorp_User_Addressbook_Abstract
{
    /**
     * city
     */
    private $_city;
    /**
     * country
     */
    private $_country;
    /**
     * country
     */
    private $_street;
    /**
     * email address
     */
    private $_email;
    /**
     * secondary email address
     */
    private $_emailSecondary;
    /**
     * first name
     */
    private $_firstName;
    /**
     * id
     */
    private $_customUserId;
    /**
     * last name
     */
    private $_lastName;
    /**
     * contact notes
     */
    private $_notes;
    /**
     * business phone number
     */
    private $_phoneBusiness;
    /**
     * home phone number
     */
    private $_phoneHome;
    /**
     * mobile phone number
     */
    private $_phoneMobile;
    /**
     * state
     */
    private $_state;
    /**
     * zip
     */
    private $_zipCode;

    function __construct($key = null, $val = null)
    {
        parent::__construct($key, $val);
        
        if ($key !== null && $val !== null && $this->isExist) {
            if ($this->getClassName() != Warecorp_User_Addressbook_eType::CUSTOM_USER) 
                throw new Warecorp_Exception('Incorrect contact type');
            $query = $this->_db->select();
    	    $query->from('zanby_addressbook__customusers', '*')
    	          ->where("$key = ?", $this->getEntityId());
    	    $customUser = $this->_db->fetchRow($query);
    	    if ( $customUser ) {
    	       $this->setCustomUserId($customUser['id']);
    	       $this->setFirstName($customUser['firstname']);
    	       $this->setLastName($customUser['lastname']);
    	       $this->setEmail($customUser['email']);
    	       $this->setEmailSecondary($customUser['email2']);
    	       $this->setPhoneBusiness($customUser['phone_business']);
    	       $this->setPhoneHome($customUser['phone_home']);
    	       $this->setPhoneMobile($customUser['phone_mobile']);
    	       $this->setCity($customUser['city']);
    	       $this->setState($customUser['state']);
    	       $this->setStreet($customUser['street']);
    	       $this->setZipCode($customUser['zipcode']);
    	       $this->setCountry($customUser['country']);
    	       $this->setNotes($customUser['notes']);
    	    }
        } else {
            $this->_className = Warecorp_User_Addressbook_eType::CUSTOM_USER;
        }
    }

    /**
     * city
     */
    public function getCity()
    {
    	return $this->_city;
    }

    /**
     * country
     */
    public function getCountry()
    {
    	return $this->_country;
    }

    /**
     * email address
     */
    public function getEmail()
    {
    	return $this->_email;
    }
    
    /**
     * secondary email address
     */
    public function getEmailSecondary()
    {
    	return $this->_emailSecondary;
    }

    /**
     * first name
     */
    public function getFirstName()
    {
    	return $this->_firstName;
    }

    public function getCustomUserId()
    {
    	return $this->_customUserId;
    }
    
/*    public function getEntityId()
    {
    	return $this->getCustomUserId();
    }    */

    /**
     * last name
     */
    public function getLastName()
    {
    	return $this->_lastName;
    }

    /**
     * contact notes
     */
    public function getNotes()
    {
    	return $this->_notes;
    }

    /**
     * business phone number
     */
    public function getPhoneBusiness()
    {
    	return $this->_phoneBusiness;
    }

    /**
     * home phone number
     */
    public function getPhoneHome()
    {
    	return $this->_phoneHome;
    }

    /**
     * mobile phone number
     */
    public function getPhoneMobile()
    {
    	return $this->_phoneMobile;
    }

    /**
     * state
     */
    public function getState()
    {
    	return $this->_state;
    }
    
    /**
     * Street
     */
    public function getStreet()
    {
    	return $this->_street;
    }

    /**
     * zip
     */
    public function getZipCode()
    {
    	return $this->_zipCode;
    }

    /**
     * city
     * 
     * @param newVal
     */
    public function setCity($newVal)
    {
        if ($newVal !== $this->_city) $this->_city = $newVal;
    	return $this;
    }

    /**
     * country
     * 
     * @param newVal
     */
    public function setCountry($newVal)
    {
        if ($newVal !== $this->_country) $this->_country = $newVal;
    	return $this;
    }

    /**
     * email address
     * 
     * @param newVal
     */
    public function setEmail($newVal)
    {
        if ($newVal !== $this->_email) $this->_email = $newVal;
    	return $this;
    }

    /**
     * secondary email address
     * 
     * @param newVal
     */
    public function setEmailSecondary($newVal)
    {
        if ($newVal !== $this->_emailSecondary) $this->_emailSecondary = $newVal;
    	return $this;
    }

    /**
     * first name
     * 
     * @param newVal
     */
    public function setFirstName($newVal)
    {
        if ($newVal !== $this->_firstName) $this->_firstName = $newVal;
    	return $this;
    }

    /**
     * 
     * @param newVal
     */
    private function setCustomUserId($newVal)
    {
        if ($newVal !== $this->_customUserId) $this->_customUserId = $newVal;
    	return $this;
    }

    /**
     * last name
     * 
     * @param newVal
     */
    public function setLastName($newVal)
    {
        if ($newVal !== $this->_lastName) $this->_lastName = $newVal;
    	return $this;
    }

    /**
     * contact notes
     * 
     * @param newVal
     */
    public function setNotes($newVal)
    {
        if ($newVal !== $this->_notes) $this->_notes = $newVal;
    	return $this;
    }

    /**
     * business phone number
     * 
     * @param newVal
     */
    public function setPhoneBusiness($newVal)
    {
        if ($newVal !== $this->_phoneBusiness) $this->_phoneBusiness = $newVal;
    	return $this;
    }

    /**
     * home phone number
     * 
     * @param newVal
     */
    public function setPhoneHome($newVal)
    {
        if ($newVal !== $this->_phoneHome) $this->_phoneHome = $newVal;
    	return $this;
    }

    /**
     * mobile phone number
     * 
     * @param newVal
     */
    public function setPhoneMobile($newVal)
    {
        if ($newVal !== $this->_phoneMobile) $this->_phoneMobile = $newVal;
    	return $this;
    }

    /**
     * state
     * 
     * @param newVal
     */
    public function setState($newVal)
    {
        if ($newVal !== $this->_state) $this->_state = $newVal;
    	return $this;
    }
    
    /**
     * street
     * 
     * @param newVal
     */
    public function setStreet($newVal)
    {
        if ($newVal !== $this->_street) $this->_street = $newVal;
    	return $this;
    }

    /**
     * zip
     * 
     * @param newVal
     */
    public function setZipCode($newVal)
    {
        if ($newVal !== $this->_zipCode) $this->_zipCode = $newVal;
    	return $this;
    }
    /**
     * save customUser object
     */
    public function save()
    {
        $data = array();
	    $data['firstname']      = $this->getFirstName();
	    $data['lastname']       = $this->getLastName();
	    $data['email']          = $this->getEmail();
	    $data['email2']         = $this->getEmailSecondary();
	    $data['phone_business'] = $this->getPhoneBusiness();
	    $data['phone_home']     = $this->getPhoneHome();
	    $data['phone_mobile']   = $this->getPhoneMobile();
	    $data['city']           = $this->getCity();
	    $data['state']          = $this->getState();
	    $data['street']         = $this->getStreet();
	    $data['zipcode']        = $this->getZipCode();
	    $data['country']        = $this->getCountry();
	    $data['notes']          = $this->getNotes();
	    if ($this->getCustomUserId() !== null) {
	        $where = $this->_db->quoteInto('id = ?', $this->getCustomUserId());
            $rows_affected = $this->_db->update('zanby_addressbook__customusers', $data, $where);
	    } else {
    	    $rows_affected = $this->_db->insert('zanby_addressbook__customusers', $data);
            $this->setCustomUserId($this->_db->lastInsertId());
            $this->setEntityId($this->getCustomUserId());
            $this->setClassName(Warecorp_User_Addressbook_eType::CUSTOM_USER);
            $this->setEmail($data['email']);
//            if ($this->_contactName === null) $this->setContactName($this->getFirstName() . ' ' . $this->getLastName());
	    }
        parent::save();
    }
    
    /**
	 * delete customUser object
	 */
	public function delete()
	{
            $where = $this->_db->quoteInto('id = ?', $this->getCustomUserId());
            $rows_affected = $this->_db->delete('zanby_addressbook__customusers', $where);
            parent::delete();
	}
	
	public function getDisplayName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
    
    public function getEmails()
    {
        return array($this->getEmail(), $this->getEmailSecondary());
    }

    public function getEmailsAsString()
    {
        return implode('; ', $this->getEmails());
    }
    
    public static function loadByEntityId($entityId, $ownerId)
    {
        $db = & Zend_Registry::get("DB");
        $query = $db->select();
        $query->from('zanby_addressbook__contacts', 'id')
           ->where('owner_id = ?', $ownerId) 
           ->where('entity_id = ?', $entityId)
           ->where('classname = ?', Warecorp_User_Addressbook_eType::CUSTOM_USER);
        $contactId = $db->fetchOne($query);
        $customUser = new Warecorp_User_Addressbook_CustomUser('id', $contactId);
        if (!$customUser->isExist) return false;
        else return $customUser;
    }
    
}
