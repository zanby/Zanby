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
class BaseWarecorp_System_Action extends Warecorp_Data_Entity
{
    public $id;
    public $priTypeId;
    public $secTypeId;
    public $subactionId;
    public $description;
    
    /**
     * Constructor.
     *
     */
	public function __construct($id = null)
	{
		parent::__construct('zanby_entity__entity_actions');

		$this->addField('id');
		$this->addField('pri_entity_type_id','priTypeId');
		$this->addField('sec_entity_type_id','secTypeId');
		$this->addField('entity_action_id','subactionId');
		$this->addField('description');

		if ($id !== null){
	        $this->pkColName = 'id';
	        $this->loadByPk($id);
	        
	         $this->priTypeId      = new Warecorp_System_Etype($this->priTypeId); 
	         $this->secTypeId      = new Warecorp_System_Etype($this->secTypeId); 
	         $this->subactionId    = new Warecorp_System_Subaction($this->subactionId); 
		}
	}
	
	
	
	
	
	/**
	 * Checks existence of role with same name. 
	 *
	 * @param integer $entity_type_id
	 * @param string $name
	 * @param integer $entity_action_id
	 * @param integer $id 
	 * @return boolean
	 */
    public static function isActionExists($pri_entity_type_id, $sec_entity_type_id, $entity_action_id, $id=null)
    {
        $db = Zend_Registry::get("DB");

        $select = $db->select();
        $select->from('zanby_entity__entity_actions','id')
               ->where('pri_entity_type_id = ?', $pri_entity_type_id)       
               ->where('sec_entity_type_id = ?', $sec_entity_type_id)       
               ->where('entity_action_id = ?', $entity_action_id);
        if ($id !== null){
            $select->where('id != ?', $id);
        }
                      
        $res = $db->fetchOne($select);
        return (boolean) $res;
    }

}
