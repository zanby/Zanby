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
 * @package Warecorp_Admin
 * @copyright  Copyright (c) 2006-2009
 * @todo проверить на использование статуса deleted для пользователя
 * @author Yury Zolotarsky, Alexander Komarovski
 */

/**
 * Base class for Admin's actions
 *
 */


class BaseWarecorp_Admin {
    private $id;
    private $login;
    private $admin_status;
    private $admin_role;

    private $dateFilter;

    public function __construct() {
        $this->admin_status='user';
        $this->admin_role='none';
        $this->dateFilter='';
        $this->nameFilter='';
    }

    /**
     * Имеет ли пользователь статус админа
     */
    public function isAdmin($login,$password) {
        $db = Zend_Registry::get("DB");
        $select = $db->select()
            ->from(array('u' => 'zanby_users__accounts'), array('u.id', 'u.login') )
            ->joinLeft(array('al' => 'zanby_admin__list'), 'al.user_id = u.id', 'al.role')
            ->where('u.login = ?',$login)
            ->where('u.pass = ?',md5($password));
        $res = $db->fetchRow($select);
        if($res !== false) {
            $this->setId($res['id']);
            $this->setLogin($res['login']);
            if($res['role']!==null) {
                $this->setStatus('admin');
                $this->setRole($res['role']);
                return true;
            }
        }
        return false;
    }

    public function loadById($newid) {
        $this->setId($newid);
        $db = Zend_Registry::get("DB");
        $select = $db->select()
            ->from(array('u' => 'zanby_users__accounts'), 'u.login' )
            ->joinInner(array('al' => 'zanby_admin__list'), 'al.user_id = u.id', 'al.role')
            ->where('u.id = ?',$newid);
        $res = $db->fetchRow($select);
        if($res === false) {
            return false;
        } else {
            $this->setStatus('admin');
            $this->setLogin($res['login']);
            $this->setRole($res['role']);
            return true;
        }
    }

    public function saveRole() {
        $db = Zend_Registry::get("DB");
        if($this->getStatus()=='admin') {
            $select = $db->select()
                ->from('zanby_admin__list', 'role' )
                ->where('user_id = ?',$this->getId());
            $role = $db->fetchOne($select);
            $data=array('user_id' => $this->getId(),'role' => $this->getRole());
            if($this->getRole()!=='none') {
                if($role===false) 	$db->insert('zanby_admin__list',$data);
                else 				$db->update('zanby_admin__list',$data,'user_id = '.$this->getId());
            }
        } else {     // при смене статуса на "user"
            $db->delete('zanby_admin__list','user_id = '.$this->getId());
        }
    }

    /**
     * Аутентификация пользователя
     */
    public function login() {
        $_SESSION['admin_id'] = $this->id;
        $_SESSION['admin'] = true;
    }
    public static function logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin']);
    }
    /**
     * Проверка аутентификации пользователя
     */
    public static function isLogined() {
        return (isset($_SESSION['admin_id']) && isset($_SESSION['admin']) && $_SESSION['admin_id'] !== null ) ? true : false;
    }

    public function getRole() {
        return $this->admin_role;
    }
    public function setRole($newValue) {
        $this->admin_role = $newValue;
    }

    public function getStatus() {
        return $this->admin_status;
    }
    public function setStatus($newValue) {
        $this->admin_status = $newValue;
        if($newValue=='admin' && $this->getRole()=='none') $this->setRole('simpleadmin');
    }

    public function getAdminPath($action) {
        return 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/adminarea/'.$action;
    }

    //@changer komarovski
    public function getAllTemplates() 
    {
        $db = Zend_Registry::get('DB');
        $query = $db->select()
            ->from(array('zmt' => 'zanby_mailtemplates__templates'),array('zmt.id', 'zmt.*'))
            ->where('zmt.is_depricated IS NULL')
            ->where('zmt.is_hidden = 0')
            ->where('(zmt.context="" OR zmt.context="'.HTTP_CONTEXT.'")')
            ->order('template_key ASC')
            ->order('context DESC');
        $result = $db->fetchAssoc($query);

        if (IMPLEMENTATION_TYPE == 'EIA') {
            $_tmpA = array();
            foreach ($result as $_k=>$_v) {
                if ( !isset($_tmpA[$_v['template_key']]) ) { $_tmpA[$_v['template_key']] = 1; } 
                else { unset($result[$_k]); }
            }
        }
        return $result;
    }

    //@author komarovski
    public function checkTemplateCustomization($templateKey) 
    {
        $db = Zend_Registry::get('DB');
        $query = $db->select()
            ->from(array('zmt' => 'zanby_mailtemplates__templates'),array('CNT' => new Zend_Db_Expr('COUNT(zmt.template_key)')))
            ->where('zmt.template_key = ?',$templateKey)
            ->where('(zmt.context="" OR zmt.context="'.HTTP_CONTEXT.'")')
            ->group('zmt.template_key');
        $result = $db->fetchOne($query);
        return ( $result > 1 ) ? true : false;
    }
    

    public function getTemplate($templateId) {
        return new Warecorp_Mail_Template('id', $templateId);
    }

    public function setId($newValue) {
        $this->id = $newValue;
    }
    public function getId() {
        return $this->id;
    }

    public function setLogin($newValue) {
        $this->login = $newValue;
    }

    public function getLogin() {
        return $this->login;
    }

    /**
     *
     * @return array
     * @author Serge Rybakov
     */
    public function getAllStaticPages() {
        $db = Zend_Registry::get('DB');
        $query = $db->select()->from(array('zcp' => 'zanby_cms__pages'),array('zcp.id', 'zcp.*'))->order('alias ASC');
        return $db->fetchAssoc($query);
    }
}
