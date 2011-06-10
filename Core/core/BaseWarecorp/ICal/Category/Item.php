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

class BaseWarecorp_ICal_Category_Item
{
    private $DbConn;
    private $id;
    private $name;
    private $order;
    
    public function setId($newValue)
    {
        $this->id = $newValue;
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setName($newValue)
    {
        $this->name = $newValue;
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setOrder($newValue)
    {
        $this->order = $newValue;
        return $this;
    }
    
    public function getOrder()
    {
        return $this->order;
    }
    
    public function __construct($categoryId = null)
    {
        $this->DbConn = Zend_Registry::get('DB');
        if ( null !== $categoryId ) $this->loadById($categoryId);
    }
    
    public function loadById($categoryId)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_categories', array('*'));
        $query->where('category_id = ?', $categoryId);
        $result = $this->DbConn->fetchRow($query);
        if ( $result ) {
            $this->setId($result['category_id']);
            $this->setName($result['category_name']);
            $this->setOrder($result['category_order']);
        }
    }
}
