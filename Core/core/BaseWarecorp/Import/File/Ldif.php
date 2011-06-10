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
 * Base class for import ldif-files
 *
 * @author Alexey Loshkarev
 */

class BaseWarecorp_Import_File_Ldif extends Warecorp_Import_File_Base
{

    public function __construct($type, $filename, $encoding = false)
    {
        parent::__construct($type, $filename, $encoding);
        $this->load();

    }

    public function getContacts($userId)
    {
        $contacts = array();

        foreach($this->items as $item) {
            $user = new Warecorp_User('email', $item['email']);
            if ($user->isExist) {
                $contactItem = new Warecorp_User_Addressbook_User();
                $contactItem->setUserId($user->getId());
                $contactItem->setContactOwnerId($userId);
            }else {
                $contactItem = new Warecorp_User_Addressbook_CustomUser();
                $contactItem->setFirstName(isset($item['firstName']) ? $item['firstName'] : '');
                $contactItem->setLastName(isset($item['lastName']) ? $item['lastName'] : '');
                $contactItem->setEmail(isset($item['email']) ? $item['email'] : '');
                $contactItem->setContactOwnerId($userId);
            }

            $contacts[] = $contactItem;

        }

        return $contacts;
    }


    /**
     * Load and parse ldif-file into items-values. Fields $this->items = array of array(firstName, lastName, email) will be sets
     *
     * @return void
     */    
    public function load()
    {
        //        function filter($a){
        //            if (substr($a, 0, 3) == "dn:")
        //            return true;
        //            else
        //            return false;
        //        }

        //split into blocks and filter empty lines

        $content = file_get_contents($this->filename);
        str_replace('\r', '', $content);
        $items = array_filter(explode("\n", $content), create_function('$a', 'if (substr($a, 0, 3) == "dn:") return true; else return false;'));
        $out = array();

        foreach ($items as &$item){
            if (substr($item, 0, 5) == "dn:: ") {
                $item = substr_replace($item, '', 0, 5);
                $item = base64_decode($item);
                $item = "dn: ".$item;
            }
            $tmp = explode(",", $item);
            $name = ""; $email = ""; $firstName = ""; $lastName = "";
            foreach ($tmp as $tmp1){

                if (substr($tmp1, 4, 3) == "cn="){
                    $name = substr($tmp1, 7);
                    $parts = explode(" ", $name);
                    if (count($parts) == 2) {
                        $firstName = $parts[0];
                        $lastName = $parts[1];
                    } else $firstName = $parts[0];
                }
                if (substr($tmp1, 0, 5) == "mail="){
                    $email = substr($tmp1, 5);
                }
            }
            if ($email){
                $out[] = array("firstName" => trim($firstName), "lastName"=> trim($lastName),  "email" => trim($email));
            }

        }
        $this->items = $out;
//        print_r($items); exit;
//
//        $this->items = array();
//
//        foreach($items as $item) {
//
//            $values = $this->parse($item);
//            print_r($values);
//            $item2 = array(); $out = array();
//            foreach($values as $value) {
//                switch ($value['name']) {
//                    case 'givenName':
//                        $item2['firstName'] = $value['value'];
//                        break;
//                    case 'sn':
//                        $item2['lastName'] = $value['value'];
//                        break;
//                    case 'mail':
//                        $item2['email'] = $value['value'];
//                        break;
//                    default:
//
//                }
//
//                if ($item2) $out[] = $item2;
//            }
//            //if ($item2) $this->items[] = $item2;
//
//        }
//        print_r($out); exit;
    }


    /**
     * Parse single item
     *
     * @param $item - string (multiline) of ldif item
     * 
     * @return array parsed item (array(firstname, lastname, email))
     *
     * @author Alexey Loshkarev
     */
    public function parse($item)
    {

        preg_match_all('/(\w+)(:{1,2})\ (.*)/i', $item, $matches, PREG_SET_ORDER);

        $values = array();

        foreach($matches as $m) {

            if ($m[2] == ':') {
                $v = $m[3];
            } else {
                $v = base64_decode($m[3]);
            }
            $values[] = array('name' => $m[1],
            'value' => $v);
        }

        return $values;

    }


}

