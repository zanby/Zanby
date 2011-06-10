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
class BaseWarecorp_System_Roleact extends Warecorp_Data_Entity
{
    public $id;
    public $roleId;
    public $actionId;
    public $allow;
    
    /**
     * Constructor.
     *
     */
	public function __construct($id = null)
	{
		parent::__construct('zanby_users__roles_actions');

		$this->addField('id');
		$this->addField('role_id','roleId');
		$this->addField('action_id','actionId');
		$this->addField('allow');

		if ($id !== null){
	        $this->pkColName = 'id';
	        $this->loadByPk($id);
	        
	         $this->roleId      = new Warecorp_System_Role($this->roleId); 
	         $this->actionId    = new Warecorp_System_Action($this->actionId);
		}
	}
	
	
	
	
	
	/**
	 * Checks existence of role + action combination. 
	 *
	 * @param unknown_type $role_id
	 * @param unknown_type $action_id
	 * @param integer $id
	 * @return boolean
	 */
    public static function isRoleactExists($role_id, $action_id, $id=null)
    {
        $db = Zend_Registry::get("DB");

        $select = $db->select();
        $select->from('zanby_users__roles_actions','id')
               ->where('role_id = ?', $role_id)       
               ->where('action_id = ?', $action_id);
        if ($id !== null){
            $select->where('id != ?', $id);
        }
                      
        $res = $db->fetchOne($select);
        return (boolean) $res;
    }

}
