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
* @package Warecorp_Nda
*/
class BaseWarecorp_Nda_List extends Warecorp_Abstract_List
{
    protected $_db;
    private $statusFilter = array();

   /**
    */
    public function __construct()
    {
        if (null === ($this->_db = Zend_Registry::get('DB'))) throw new Warecorp_Exception('Database connection isn`t set');
    }

   /**
    */
    public function setStatusFilter($value, $doClear = true)
    {
        if (is_array($value) && !empty($value)) {
            $this->statusFilter = array();
            foreach ($value as $v) {
                if (Warecorp_Nda_Enum_Status::inEnum($v) && !in_array($v, $this->statusFilter)) {
                    $this->statusFilter[] = $v;
                }
            }
        }
        else {
            if ($doClear) {
                $this->statusFilter = array();
                if (Warecorp_Nda_Enum_Status::inEnum($value))    {
                    $this->statusFilter[] = $value;
                }
            } else {
                if (Warecorp_Nda_Enum_Status::inEnum($value) && !in_array($value, $this->statusFilter)) {
                    $this->statusFilter[] = $value;
                }
            }
        }
        return $this;
    }

   /**
    */
    public function cleanStatusFilter()
    {
        $this->statusFilter = array();
        return $this;
    }

   /**
    */
    public function getStatusFilter(&$query)
    {
        if (sizeof($this->statusFilter) != Warecorp_Nda_Enum_Status::countConsts() && sizeof($this->statusFilter)) {
            foreach ($this->statusFilter as $v) {
                $query->where('ceni.nda_status = ?', $v);
            }
        }
    }

    public function getPreparedKeyword($value)
    {
        if (is_array($value)) $keyword = join(" ", $value);
        else $keyword = $value;
        $keyword = str_replace("\\", "\\\\\\\\", $keyword);
        $keyword = str_replace("'", "\'", $keyword);
        $keyword = str_replace('%', '\%', $keyword);
        $keyword = str_replace('_', '\_', $keyword);
        return "%".$keyword."%";
    }

   /**
    */
    public function getList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey()   === null ) ? 'ceni.nda_id'   : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'ceni.nda_name' : $this->getAssocValue();
            $query->from(array('ceni' => 'calendar_event_nda_items'), $fields);
        } else {
            $query->from(array('ceni' => 'calendar_event_nda_items'), 'ceni.nda_id');
        }
        if ( $this->getWhere() ) $query->where($this->getWhere());

        if ( $this->getIncludeIds() ) $query->where('ceni.nda_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('ceni.nda_id NOT IN (?)', $this->getExcludeIds());

        $this->getStatusFilter($query);

        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = new Warecorp_Nda_Item($item);
        }
        return $items;
    }

   /**
    */
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('ceni' => 'calendar_event_nda_items'), new Zend_Db_Expr('COUNT(ceni.nda_id)'));

        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('ceni.nda_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('ceni.nda_id NOT IN (?)', $this->getExcludeIds());
        $this->getStatusFilter($query);

        return $this->_db->fetchOne($query);
    }
}
