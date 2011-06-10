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
 * Class for import from M$ Outlook
 *
 * @author Alexey Loshkarev
 */

class BaseWarecorp_Import_File_Csv_Outlook extends Warecorp_Import_File_Csv
{
    
    var $fieldEmail = "E-mail Address";
    var $fieldFirstName = "First Name";
    var $fieldLastName = "Last Name";
    var $encoding = "WINDOWS-1251";
    var $separator = ',';
    
    public function getContacts($userId)
    {
        $contacts = array();
        //dump($this->data);
        for($i = 0; $i < $this->rowCount(); $i++) {
            
            $user = new Warecorp_User('email', $this->value($i, $this->fieldEmail));	
            if ($user->isExist) { 
                $contactItem = new Warecorp_User_Addressbook_User();
                $contactItem->setUserId($user->getId());
                $contactItem->setContactOwnerId($userId);
            }else {
                $contactItem = new Warecorp_User_Addressbook_CustomUser();
                $contactItem->setFirstName($this->value($i, $this->fieldFirstName));
                $contactItem->setLastName($this->value($i, $this->fieldLastName));
                $contactItem->setEmail($this->value($i, $this->fieldEmail));
                $contactItem->setContactOwnerId($userId);    
            }
            $contacts[] = $contactItem;
            
            
        }
        
        return $contacts;
        
    }
    
}
