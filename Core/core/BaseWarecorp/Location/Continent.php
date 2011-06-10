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
* @author Roman Gabrusenok
*/
class BaseWarecorp_Location_Continent
{
    public $id;
    public $code;
    public $name;
    public $latitude;
    public $longitude;

    private $_db;

    public function __construct($field = null, $value = null)
    {
        if ( $field && $value ) {
            $this->load($field, $value);
        }
    }

    private function load($field, $value)
    {
        if ( !$this->_db ) {
            $this->_db = Zend_Registry::get("DB");
        }
        $select = $this->_db->select();
        $select->from('zanby_location__continents', array('*'))
               ->where($field . " = ?", $value)
               ->limit(1,0);
        $result = $this->_db->fetchRow($select);

        if ( $result ) {
            $this->id        = $result['id'];
            $this->code      = $result['code'];
            $this->name      = $result['name'];
            $this->latitude  = $result['latitude'];
            $this->longitude = $result['longitude'];
        }
    }
}
