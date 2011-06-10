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
 * @package    Warecorp_Group_Privileges
 * @copyright  Copyright (c) 2007
 * @author Yury Zolotarsky 
 */

class BaseWarecorp_Group_Resign_Requests extends Warecorp_Data_Entity
{
	protected $id;
    protected $groupId;
    protected $userId;

    /**
	 * Constructor
	 * @param int $value - group id
	 */
	public function __construct($value = null)
	{
	    
        parent::__construct('zanby_groups__resign_requests', array(
    		'id'               		=> 'id',
            'group_id'           	=> 'groupId',
            'user_id'               => 'userId',
    		));
    		
	    if ($value !== null){
	       $this->pkColName = 'id';
		   $this->loadByPk($value);
	    }
	}
	
	public function getId()
	{		
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	public function getGroupId()
	{		
		return $this->groupId;
	}

	public function setGroupId($groupId)
	{
		$this->groupId = $groupId;
		return $this;
	}

	public function getUserId()
	{		
		return $this->userId;
	}

	public function setUserId($userId)
	{
		$this->userId = $userId;
		return $this;
	}
	
	public function deleteAll()
	{		
		$temp = $this->pkColName;
		$this->pkColName = 'group_id';	
		parent::delete();
		$this->pkColName = $temp;
	}	
	
	public static function getIdifExist($groupId, $userId)
	{
		$db = Zend_Registry::get('DB');
		$query = $db->select()
		    ->from(array('zgrr' => 'zanby_groups__resign_requests'), 'id')
            ->where('zgrr.group_id = ?', $groupId)
            ->where('zgrr.user_id = ?', $userId);
        if ($res = $db->fetchOne($query)) return $res; else return false;
	}
    
    public static function getRequestByHash($hash)
    {
        $db = Zend_Registry::get('DB');
        $query = $db->select()
            ->from(array('zgrr' => 'zanby_groups__resign_requests'), 'id')
            ->where('md5(zgrr.id) = ?', $hash);
        if ($res = $db->fetchOne($query)) return new Warecorp_Group_Resign_Requests($res); else return false;
    }    
	
}
