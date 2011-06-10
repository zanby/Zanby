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
 *  List of roles, entity actions and subactions, entity types, permissions. 
 *
 */
class BaseWarecorp_System_Administrator extends Warecorp_Data_Entity
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->_db = Zend_Registry::get("DB");
    }


    /**
     * Returns list of all roles
     *
     * @return array of objects
     */
    public function getAllRolesList()
    {
        $select = $this ->_db->select()
        ->from('zanby_users__roles', 'id')
        ->order('role');

        $result = $this->_db->fetchCol($select);

        foreach ($result as &$item) {
            $item = new Warecorp_System_Role($item);
        }

        return $result;
    }
    /**
     * Returns list of all roles
     *
     * @return array of id=>role
     */
    public function getAllRolesListAssoc()
    {
        $select = $this ->_db->select()
        ->from('zanby_users__roles', array('id', 'role'))
        ->order('role');

        $result = $this->_db->fetchPairs($select);

        return $result;
    }





    /**
     * Returns list of all entity types
     *
     * @return array of objects
     */
    public function getAllEtypesList()
    {
        $select = $this ->_db->select()
        ->from('zanby_entity__types', 'id')
        ->order('id');

        $result = $this->_db->fetchCol($select);

        foreach ($result as &$item) {
            $item = new Warecorp_System_Etype($item);
        }

        return $result;
    }
    /**
     * Returns list of all entity types
     *
     * @return array of id=>name
     */
    public function getAllEtypesListAssoc()
    {
        $select = $this ->_db->select()
        ->from('zanby_entity__types', array('id', 'name'))
        ->order('name');

        $result = $this->_db->fetchPairs($select);

        return $result;
    }





    /**
     * Returns list of all subactions
     *
     * @return array of objects
     */
    public function getAllSubactionsList()
    {
        $select = $this ->_db->select()
        ->from('zanby_entity__actions', 'id')
        ->order('id');

        $result = $this->_db->fetchCol($select);

        foreach ($result as &$item) {
            $item = new Warecorp_System_Subaction($item);
        }

        return $result;
    }
    /**
     * Returns list of all subactions
     *
     * @return array of id=>entity_action
     */
    public function getAllSubactionsListAssoc()
    {
        $select = $this ->_db->select()
        ->from('zanby_entity__actions', array('id', 'entity_action'))
        ->order('id');

        $result = $this->_db->fetchPairs($select);

        return $result;
    }





    /**
     * Returns list of all actions
     *
     * @return array of objects
     */
    public function getAllActionsList()
    {
        $select = $this ->_db->select()
        ->from(array('C' => 'zanby_entity__entity_actions'), 'C.id')
        ->joinLeft(array('D' => 'zanby_entity__types'), 'C.pri_entity_type_id = D.id')
        ->joinLeft(array('F' => 'zanby_entity__types'), 'C.sec_entity_type_id = F.id')
        ->joinLeft(array('E' => 'zanby_entity__actions'), 'C.entity_action_id = E.id')
        ->order('D.name')
        ->order('F.name')
        ->order('E.entity_action');

        $result = $this->_db->fetchCol($select);

        foreach ($result as &$item) {
            $item = new Warecorp_System_Action($item);
        }

        return $result;
    }
    /**
     * Returns list of all actions
     *
     * @return array of id=>name
     */
    public function getAllActionsListAssoc()
    {
        $select = $this ->_db->select()
        ->from(array('C' => 'zanby_entity__entity_actions'), array('C.id', new Zend_Db_Expr('CONCAT("_", D.name, F.name, E.entity_action)')))
        ->joinLeft(array('D' => 'zanby_entity__types'), 'C.pri_entity_type_id = D.id', array())
        ->joinLeft(array('F' => 'zanby_entity__types'), 'C.sec_entity_type_id = F.id', array())
        ->joinLeft(array('E' => 'zanby_entity__actions'), 'C.entity_action_id = E.id', array())
        ->order('D.name')
        ->order('F.name')
        ->order('E.entity_action');

        $result = $this->_db->fetchPairs($select);

        foreach ($result as $key => &$item) {

            $item = new Warecorp_System_Action($key);
            $item = $item->priTypeId->name.'_'.$item->secTypeId->name.'_'.$item->subactionId->subaction ;
        }

        return $result;
    }





    /**
     * Returns list of all default permissions
     *
     * @return array of objects
     */
    public function getAllRoleactsList()
    {
        $select = $this ->_db->select()
        ->from(array('A' => 'zanby_users__roles_actions'), 'A.id')
        ->joinLeft(array('B' => 'zanby_users__roles'), 'A.role_id = B.id')
        ->joinLeft(array('C' => 'zanby_entity__entity_actions'), 'A.action_id = C.id')
        ->joinLeft(array('D' => 'zanby_entity__types'), 'C.pri_entity_type_id = D.id')
        ->joinLeft(array('DD' => 'zanby_entity__types'), 'C.sec_entity_type_id = DD.id')
        ->joinLeft(array('E' => 'zanby_entity__actions'), 'C.entity_action_id = E.id')
        ->order('B.role')
        ->order('D.name')
        ->order('DD.name')
        ->order('E.entity_action');

        $result = $this->_db->fetchCol($select);

        foreach ($result as &$item) {
            $item = new Warecorp_System_Roleact($item);
        }

        return $result;
    }






    /**
     * Returns list of all user roles
     *
     * @return array of objects
     */
    public function getAllUserRolesList()
    {
        $select = $this ->_db->select()
        ->from(array('A' => 'zanby_users__roleaccess'), 'A.id')
        ->joinLeft(array('B' => 'zanby_users__accounts'), 'A.user_id = B.id')
        ->joinLeft(array('C' => 'zanby_users__roles'), 'A.role_id = C.id')
        ->order('B.login')
        ->order('C.role');

        $result = $this->_db->fetchCol($select);

        foreach ($result as &$item) {
            $item = new Warecorp_System_Userrole($item);
        }

        return $result;
    }

//@todo @kgam @author Komarovski
    //-------------------------------------------------------------------------------------------------------
    // !!! избавиться !!!
    //-------------------------------------------------------------------------------------------------------
    public function getAllUsersListAssoc()
    {
        $select = $this ->_db->select()
        ->from('zanby_users__accounts', array('id', 'login'))
        ->order('login');

        $result = $this->_db->fetchPairs($select);

        return $result;
    }
    //-------------------------------------------------------------------------------------------------------
}
