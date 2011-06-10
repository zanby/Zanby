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
 * Base class for import webservice classes
 *
 * @author Alexey Loshkarev
 */

class BaseWarecorp_Import_Webservice_Base
{
    public $service;
    public $login;
    public $password;
    public $lastError = "";

    public $signedIn = false;
    
    public function __construct($service, $login, $password)
    {
        $this->service = $service;
        $this->login = $login;
        $this->password = $password;
        
    }
    
    
    /**
     * Initiate login session. Returns true on login ok, return false otherwise and this->lastError 
     *   consists of login error
     *
     * @return boolean login result
     * 
     * @author Alexey Loshkarev
     */
    public function login()
    {
        
        $this->lastError = "Login skipped";
        return false;
    }
    
    
    /**
     * Retreive contacts from service. Retreived data will be ready for save()
     * 
     * @param integer userId - owner of retreived contacts
     * @return array of Warecorp_User_Addressbook
     * 
     * @author Alexey Loshkarev
     */
    public function getContacts()
    {
        
        
        return array();
    }
    
}
