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


class BaseWarecorp_Log_List 
{
	public $dateFilter;
	public $nameFilter;
    function __construct()
    {
		$this->dateFilter='';
		$this->nameFilter='';
    }
	public function getLogCount()
	{
		$db = Zend_Registry::get('DB');
		$query = $db->select()->from(array('al' => 'zanby_admin__log'))
					->join(array('u' => 'zanby_users__accounts'), 'al.admin_id = u.id', array('u.login'))
					->order('change_time DESC')
					->where('al.change_time LIKE ?', $this->getDateFilter().'%')
					->where('u.login LIKE ?', '%'.$this->getNameFilter().'%');
					
		$res=$db->fetchAssoc($query);
		return count($res);
	}
	public function getLogPage($page,$items_per_page)
	{
		$db = Zend_Registry::get('DB');
		$query = $db->select()->from(array('al' => 'zanby_admin__log'))
					->join(array('u' => 'zanby_users__accounts'), 'al.admin_id = u.id', array('u.login'))
					->order('change_time DESC')
					->limitPage($page, $items_per_page)
					->where('al.change_time LIKE ?', $this->getDateFilter().'%')
					->where('u.login LIKE ?', '%'.$this->getNameFilter().'%');
					$res=$db->fetchAssoc($query);
		// По именам таблиц ставим 'action' 
		// для формирования url'a перехода к измененному элементу           
		foreach($res as &$item){ 
			$item['actionname']=$item['changed_table'];
			switch ($item['changed_table']){
				case 'billing':$item['actionname']='billingedit'; break;
			}
		}
		return $res;
	}
	public function setDateFilter($newValue)
    {
        $this->dateFilter = $newValue;
    }
	public function getDateFilter()	
	{		
		return $this->dateFilter;
	}
	public function setNameFilter($newValue)
    {
        $this->nameFilter = $newValue;
    }
	public function getNameFilter()	
	{		
		return $this->nameFilter;
	}
	
}
