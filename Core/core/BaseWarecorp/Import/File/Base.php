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
 * Base class for import file classes
 *
 * @author Alexey Loshkarev
 */

class BaseWarecorp_Import_File_Base
{
    public $type;
    public $filename;
    public $lastError = "";
    
    var $encoding = SITE_ENCODING;
    
    public function __construct($type, $filename, $encoding = false)
    {
        $this->type = $type;
        $this->filename = $filename;
        
    }
    
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * Retreive contacts from service. Retreived data will be ready for save()
     * 
     * @param integer userId - owner of retreived contacts
     * @return array of Warecorp_User_Addressbook
     * 
     * @author Alexey Loshkarev
     *
     */
    public function getContacts($userId)
    {


    }    
    
}

