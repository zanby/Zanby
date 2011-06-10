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


class BaseWarecorp_Db_Select extends Zend_Db_Select
{
    public function join($name, $cond, $cols = array(), $schema = null)
    {                  
        return $this->joinInner($name, $cond, $cols, $schema);
    }

    public function joinInner($name, $cond, $cols = array(), $schema = null)
    {
        return $this->_join(self::INNER_JOIN, $name, $cond, $cols, $schema);
    }

    public function joinLeft($name, $cond, $cols = array(), $schema = null)
    {
        return $this->_join(self::LEFT_JOIN, $name, $cond, $cols, $schema);
    }

    public function joinRight($name, $cond, $cols = array(), $schema = null)
    {
        return $this->_join(self::RIGHT_JOIN, $name, $cond, $cols, $schema);
    }

    public function joinFull($name, $cond, $cols = array(), $schema = null)
    {
        return $this->_join(self::FULL_JOIN, $name, $cond, $cols, $schema);
    }

    public function joinCross($name, $cols = array(), $schema = null)
    {
        return $this->_join(self::CROSS_JOIN, $name, null, $cols, $schema);
    }

    public function joinNatural($name, $cols = array(), $schema = null)
    {
        return $this->_join(self::NATURAL_JOIN, $name, null, $cols, $schema);
    }
    
    public function where($cond, $value = null, $type = null)
    {
        if ($value === null) $value = new Zend_Db_Expr('NULL');
        parent::where($cond, $value, $type);
        return $this;
    }
    
    public function orWhere($cond, $value = null, $type = null)
    {
        if ($value === null) $value = new Zend_Db_Expr('NULL');
        parent::orWhere($cond, $value, $type);
        return $this;
    }
}
