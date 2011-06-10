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
 * @package    Warecorp_User
 * @copyright  Copyright (c) 2008
 * @author Andrey Kondratiev
 */

abstract class BaseWarecorp_User_Profile_Abstract
{
    private   $DbConn;
    private   $id;
    private   $_isNewProfile = true;

    protected $_data = array();
    protected $_fieldsList = array();

    protected $TABLE = '';

    public function __construct($id) {
        $this->initProfile();
        if (empty($this->TABLE))   throw new Exception("Profile Table is not set");
        if (empty($this->_fieldsList)) throw new Exception("Profile fields is not set");

        $this->_fieldsList[] = 'id';
        $this->_data['id'] = $id;
        $this->DbConn = Zend_Registry::get('DB');
        if ( $this->DbConn === null ) throw new Warecorp_Exception('Database connection is not set.');
        $this->loadById($id);
    }

    
    public function __call($name, $arguments) {
        if (strstr($name, "set") ) {
            $varName = str_replace('set','',$name);
            $varName = strtolower($varName);
            if(in_array($varName, $this->_fieldsList)) {
                $this->_data[$varName] = implode('', $arguments);
                return $this;
            } else {
                throw new Exception("Undefined function - ". $name);
            }

        }elseif (strstr($name, "get")) {
            $varName = str_replace('get','',$name);
            $varName = strtolower($varName);
            if(in_array($varName, $this->_fieldsList)) {
                return ( !isset($this->_data[$varName]) ) ? null : $this->_data[$varName];
            } else {
                throw new Exception("Undefined function - ". $name);
            }
        }else {
            throw new Exception("Undefined function: ".$name);
        }
    }


    abstract function initProfile();

    public function loadById($id)
    {
        $this->setId($id);
        $memcache = Warecorp_Cache::getMemCache();

        $classname = get_class($this);

        $data = $memcache->load($classname.$id);
        if (!$data) {
            $query = $this->DbConn->select();
            $query->from($this->TABLE, array('*'));
            $query->where('id = ?', $id);
            $data = $this->DbConn->fetchRow($query);
            if ($data) {
                $memcache->save($data,$classname.$id,array(),Warecorp_Cache::LIFETIME_30DAYS);
            }
        }


        if ( $data ) {
            $this->_data = $data;
            $this->_isNewProfile = false;
        }
        return $this;
    }

    public function setId($val){
        $this->_data['id'] = $val;
        return $this;

    }

    public function getId(){
        return $this->_data['id'];
    }


    public function save()
    {
        if ($this->getId() === null) throw new Exception('UserID must be set.');

        $classname = get_class($this);
        $memcache = Warecorp_Cache::getMemCache();
        $memcache->remove($classname.$this->getId());

        /*
        $data = array(
            'id'                         => (null !== $this->getId())                       ? $this->getId()                        : new Zend_Db_Expr('NULL'),
            'address'                    => (null !== $this->getAddress())                  ? $this->getAddress()                   : new Zend_Db_Expr('NULL'),
            'phone'                      => (null !== $this->getPhone())                    ? $this->getPhone()                     : new Zend_Db_Expr('NULL'),
            'district_state'             => (null !== $this->getDistrictState())            ? $this->getDistrictState()             : new Zend_Db_Expr('NULL'),
            'district_number'            => (null !== $this->getDistrictNumber())           ? $this->getDistrictNumber()            : new Zend_Db_Expr('NULL'),
            'district_state_secondary'   => (null !== $this->getDistrictStateSecondary())   ? $this->getDistrictStateSecondary()    : new Zend_Db_Expr('NULL'),
            'district_number_secondary'  => (null !== $this->getDistrictNumberSecondary())  ? $this->getDistrictNumberSecondary()   : new Zend_Db_Expr('NULL'),
            'zipcode_secondary'          => (null !== $this->getZipcodeSecondary())         ? $this->getZipcodeSecondary()          : new Zend_Db_Expr('NULL'),
            'main_implementation'        => (null !== $this->getMainImplementation())       ? $this->getMainImplementation()        : new Zend_Db_Expr('NULL'),
            'language'                   => (null !== $this->getLanguage())                 ? $this->getLanguage()                  : new Zend_Db_Expr('NULL'),
            
        );
         * 
         */

        if ($this->_isNewProfile){
            $this->DbConn->insert($this->TABLE, $this->_data);
        }
        else{
            $where = $this->DbConn->quoteInto('id = ?', $this->getId());
            $this->DbConn->update($this->TABLE, $this->_data, $where);
        }

    }
    
    public function clearMemcache() {
        $classname = get_class($this);
        $memcache = Warecorp_Cache::getMemCache();
        $memcache->remove($classname.$this->getId());
    }

    public function delete()
    {
        $this->clearMemcache();
        $where = $this->DbConn->quoteInto('id = ?', $this->getId());
        $this->DbConn->delete($this->TABLE, $where);
    }


}
