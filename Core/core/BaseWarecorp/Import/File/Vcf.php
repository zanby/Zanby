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
 * Base class for import vcf-files
 *
 * @author Alexey Loshkarev
 */

class BaseWarecorp_Import_File_Vcf extends Warecorp_Import_File_Base
{
    
    var $lineSeparator = "\r\n";
//    var $vcardSeparator = "\r\n\r\n";
var $vcardSeparator ="END:VCARD";
    var $encoding = false;

    public function __construct($type, $filename, $encoding = false)
    {
        parent::__construct($type, $filename, $encoding);
        $this->load();

    }
    
    
    /**
     * @see this is a copy of ldif->getContacts()
     */
    public function getContacts($userId)
    {
        $contacts = array();
        
        foreach($this->items as $item) {
            if(isset($item['email'])) {
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
        }
        
        return $contacts;
    }


    /**
     * Load and parse vcf-file into items-values. Fields $this->items = array of array(firstName, lastName, email) will be sets
     *
     * @return void
     */    
    public function load()
    {
        
        //split into blocks and filter empty lines
        $items = array_filter(explode($this->vcardSeparator, file_get_contents($this->filename)), create_function('$a', 'return ($a);'));
//Zend_Debug::dump($this->vcardSeparator);
        $this->items = array();

        foreach($items as $item) {
            $values = $this->parse($item);
//Zend_Debug::dump($values);
            $item2 = array();
            foreach($values as $value) {
//Zend_Debug::dump($value);
                switch ($value['name']) {
                case 'N':
                    $item2['lastName']  = $value['value'][0];
                    $item2['firstName'] = $value['value'][1];
                    break;
                case 'EMAIL':
                    $item2['email'] = $value['value'][0];
                    break;
                default:
                    
                }
                
            }
            if ($item2) $this->items[] = $item2;
        }
        
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
        
        // fix for multiline strings
        $item = str_replace($this->lineSeparator." ", "", $item);
        
        $strings = explode($this->lineSeparator, $item);
        
        $values = array();
        // parsing name:value
        foreach($strings as $string) {
            
            //dump($string);
            @list($fieldsRaw, $fieldValue) = explode(":", $string);
            
            // parsing name (like FN;CHARSET=UTF-8;ENCODING=QUOTED-PRINTABLE)
            $fields = explode(";", $fieldsRaw);
            
            $value = array('name' => $fields[0],
                           'params' => array(),
                           );
            
            $fieldParams = array_slice($fields, 1);

            foreach($fieldParams as &$fieldParam) {
                @list($fieldName, $fieldValuesRaw) = explode("=", $fieldParam);
                
                // parsing field value like EMAIL;TYPE=internet,pref:jane_doe@abc.com
                $fieldValues = explode(",", $fieldValuesRaw);
                $value['params'][$fieldName] = $fieldValues;
                
            }
            
            $value['value'] = $this->_decodeValue($fieldValue, $value['params']);
            
            $values[] = $value;
            
        }
        
        //dump($values);
        return $values;
        
    }
    
   
    /**
     * Decode and return value
     *
     * @param string $value - encoded value
     * @param array of array(param=>array(value1, value2)) - params, parsed by this->load()
     * @return array decoded values (exploded by ';')
     *
     * @author Alexey Loshkarev
     */
    public function _decodeValue($value, $params)
    {
        
        //dump($params);
        if (isset($params['ENCODING'])) {
            
            switch ($params['ENCODING'][0]) {
            case "QUOTED-PRINTABLE":
                $value = $this->_decodeQuotedPrintable($value);
                break;
            default:
                
            }
            
        }
        
        if (isset($params['CHARSET'])) {
            $value = iconv($params['CHARSET'][0], SITE_ENCODING, $value);
        }
        
        return explode(";", $value);
    }
    
    
    /**
     * Return decoded by quoted-printable encoding $value
     *
     * @param string $value - encoded value
     * @return string decoded value
     *
     * @author Alexey Loshkarev
     */
    public function _decodeQuotedPrintable($value)
    {
        $result = "";
        
        for($i = 0; $i < strlen($value); $i++) {
            
            if ($value[$i] == '=') {
                $char = $value[$i+1] . $value[$i+2];
                $result .= chr(hexdec($char));
                $i+= 2;
            } else {
                $result .= $value[$i];
            }
            
        }
        
        return $result;
    }
}

