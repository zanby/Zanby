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
 *
 * @package    Warecorp_System
 * @copyright  Copyright (c) 2007
 * @author Alexander Komarovski
 */

/**
 *
 *
 */
class BaseWarecorp_System_Userrole extends Warecorp_Data_Entity
{
    public $id;
    public $userId;
    public $roleId;
    public $entityId;
    
    /**
     * Constructor.
     *
     */
	public function __construct($id = null)
	{
		parent::__construct('zanby_users__roleaccess');

		$this->addField('id');
		$this->addField('user_id','userId');
		$this->addField('role_id','roleId');
		$this->addField('entity_id','entityId');

		if ($id !== null){
	        $this->pkColName = 'id';
	        $this->loadByPk($id);
	        
	         $this->roleId = new Warecorp_System_Role($this->roleId); 
	         $this->userId = new Warecorp_User('id',$this->userId);
		}
	}
	
	
	
	
	
	/**
	 * Checks existence of user + role combination. 
	 *
	 * @param integer $user_id
	 * @param integer $role_id
	 * @param integer $id
	 * @return boolean
	 */
    public static function isUserRoleExists($user_id, $role_id, $entity_id = null, $id = null)
    {
        $db = Zend_Registry::get("DB");

        $select = $db->select();
        $select->from('zanby_users__roleaccess','id')
               ->where('user_id = ?', $user_id)       
               ->where('role_id = ?', $role_id);
               if(!empty($entity_id))
               {
                   $select->where('entity_id = ?', $entity_id);
               }
               else 
               {
                   $select->where('entity_id IS NULL');
               }
        if ($id !== null){
            $select->where('id != ?', $id);
        }
                      
        $res = $db->fetchOne($select);
        return (boolean) $res;
    }
    
    
    
    
    
    /**
     * 
	 */
   /* public static function isRole($user_id, $role)
    {
        $db = Zend_Registry::get("DB");

        $select = $db->select();
        $select->from(array('A' => 'zanby_users__roleaccess'),'A.id')
               ->joinLeft(array('B' => 'zanby_users__roles'), 'A.role_id = B.id')
               ->where('A.user_id = ?', $user_id)
               ->where('B.role = ?', $role);
        
        $res = $db->fetchOne($select);
        return (boolean) $res;
    }*/
}
