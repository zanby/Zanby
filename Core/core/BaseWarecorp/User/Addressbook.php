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
 * Warecorp FRAMEWORK
 * @package    Warecorp_User
 * @copyright  Copyright (c) 2006
 */

/**
 * Addressbook entity class
 *
 * @author Alexey Loshkarev
 */
class BaseWarecorp_User_Addressbook extends Warecorp_Data_Entity
{
    /**
     * Constructor. 
     * @author Alexey Loshkarev
     */
    public function __construct($id = null)
    {
    throw new Warecorp_Exception('Class is deprecated');
        parent::__construct('zanby_users__addressbook_entries');

        $this->addField('id');
        $this->addField('owner_id', 'ownerId');
        $this->addField('ref_user_id', 'userId');  // link to real zanby member
        $this->addField('ref_group_id', 'groupId');  // link to real zanby member
        $this->addField('first_name', 'firstName');
        $this->addField('last_name', 'lastName');
        $this->addField('email', 'email');
        $this->addField('email2', 'email2');
        $this->addField('phone_business', 'phoneBusiness');
        $this->addField('phone_home', 'phoneHome');
        $this->addField('phone_mobile', 'phoneMobile');
        $this->addField('street', 'street');
        $this->addField('city', 'city');
        $this->addField('state', 'state');
        $this->addField('zip', 'zip');
        $this->addField('country', 'country');
        $this->addField('notes', 'notes');
        
        if ( $id ) {
           $this->pkColName = 'id';
           $this->loadByPk($id);
           if ($this->userId) {
               $this->user = new Warecorp_User('id', $this->userId);
               
               // addressbook fields overrides real fields
               $this->realFields = array('country'  =>$this->user->getCountry()->name,
                                         'city'     =>$this->user->getCity()->name,
                                         'state'    =>$this->user->getState()->name,
                                         'zip'      =>$this->user->getZip(),
                                         'firstName'=>$this->user->getFirstname(),
                                         'lastName' =>$this->user->getLastname(),
                                         'email'    =>$this->user->getEmail());
               foreach($this->realFields as $field=>$value) {
                   if (!$this->$field) {
                       $this->$field = $value;
                   }
               }
           }
           if ($this->groupId) {
               $this->group = Warecorp_Group_Factory::loadById($this->groupId, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
           }
        }   
    }
    
    
    /**
     * Initialize fields with array (usefull for storing POST-data)
     * @param integer ownerId - entity ownerId
     * @param array params - fields
     * @author Alexey Loshkarev
     */
    public function init($ownerId, $params)
    {
        if (($this->isExist) && ($ownerId != $this->ownerId)) {
            return false;
        } else {
            foreach($this->record as $field) {
                if ((isset($params[$field])) && ($field != 'id')) {
                    $this->$field = $params[$field];
                }
            }
            $this->ownerId = $ownerId;
            return true;
        }
    }

    /**
     * Get list of addressbook items with "firstname lastname" == $name
     * @param integer $ownerId - owner of addressbook
     * @param string $name - "firstname lastname"
     * @return integer - null or Warecorp_User_Addressbook ID
     */
    static public function get($ownerId, $name)
    {
        $db = Zend_Registry::get("DB");
        $sql = $db->select()
            ->from('view_users__addressbook', 'id')
            ->where('owner_id = ?', $ownerId)
            ->where('CONCAT(first_name, " ", last_name) = ?', $name);
        $id = $db->fetchOne($sql);
        //dump($id);
        return ($id!== false) ? $id : null;
    }
    
    /**
     * Check, wether addressbook entity already exists
     * @type = user/group
     * @todo - comments
     */
    public static function isExists($ownerId, $id)
    {
        //$fields = array('user' => 'ref_user_id',
        //                'group' => 'ref_group_id',
        //                );
        //if (in_array($type, array_keys($fields))) {
        //    $field = $fields[$type];
        
            $db = Zend_Registry::get("DB");
            $sql = $db->select()
                ->from('zanby_users__addressbook_entries', new Zend_Db_Expr('COUNT(*)'))
                ->where('ref_user_id = ?', $id);
        
            $count = $db->fetchOne($sql);
            return ($count > 0);
        //} else {
        
        //    throw new Zend_Exception('Incorrect addressbook item type!');
        
        //}
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
	
	public static function stringToArray($string, $separator = ";")
	{
	    if (!is_string($string)) throw new Warecorp_Exception('stringToArray function: incorrect first parameter');
	    $tempArray = array_map('trim', explode($separator, $string));
	    $array = array();
	    foreach ($tempArray as $value)
	    {
	        if ($value != null) $array[] = $value;
	    }
	    return $array;
	}
    
}
