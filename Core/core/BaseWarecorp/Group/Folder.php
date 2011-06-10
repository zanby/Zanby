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


class BaseWarecorp_Group_Folder extends Warecorp_Data_Entity
{
    public $id;
    public $preset;    
    public $name;
    public $parent;
    
	public function __construct($id = null)
	{
	    parent::__construct('zanby_groups__folders');
	    
	    $this->addField('id');
	    $this->addField('preset_id', 'preset');
	    $this->addField('name');
	    $this->addField('parent_id', 'parent');
	    if ($id !== null) {
	        $this->loadByPk($id);
	    }	    
	}

	public function getRelationList()
	{
	    $select = $this->_db->select();
	    $select->from('zanby_groups__folders_relations', 'group_id')
	           ->where('folder_id = ?', $this->id);	           
	    $children = $this->_db->fetchCol($select);
	    foreach ($children as &$value) $value = new Warecorp_Group_BaseGroup('id', $value);
	    return $children;
	}
    
}
