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
 * User activity tracker class
 *
 */
class BaseWarecorp_Util_UserActivity_Tracker implements Warecorp_Controller_IRequestHandler {
    const USER_TYPE_HOST = 1;
    const USER_TYPE_COHOST = 2;
    const USER_TYPE_MEMBER = 4;
    const USER_TYPE_USER = 8;
    
    private $db;
    private $userTypesTracking = 0;
    private $enable = true;
    
    public function __construct() {
        $this->setDb(Zend_Registry::get('DB'));
    }
    
    /**
     * Process request
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function processRequest(Zend_Controller_Request_Abstract $request) {
        if ($this->getEnable() && $request instanceof Zend_Controller_Request_Http) {
            $user = Zend_Registry::get('User');
            $primaryDb = Zend_Registry::get('DB');
            if ($user->isExist) {
                $needTrack = false;
                $userTypesTracking = $this->getUserTypesTracking();
                
                if ($userTypesTracking & self::USER_TYPE_USER) {
                    $needTrack = true;
                } else {
                    $sql = $primaryDb->select()->distinct()->from('zanby_groups__members', 'status')->where('user_id=?', $user->getId())->where("is_approved=1");
                    $roles = $primaryDb->fetchCol($sql);
                    foreach ($roles as $role) {
                        switch($role) {
                            case Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST:
                                if ($userTypesTracking & self::USER_TYPE_HOST) $needTrack = true;
                                break;
                            case Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST:
                                if ($userTypesTracking & self::USER_TYPE_COHOST) $needTrack = true;
                                break;
                            case Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER:
                                if ($userTypesTracking & self::USER_TYPE_MEMBER) $needTrack = true;
                                break;
                        }
                        if ($needTrack) break;
                    }
                }
                
                if ($needTrack) {
                    $this->performTrack($request, $user);
                }
            }
        }
    }
    
    protected function performTrack(Zend_Controller_Request_Http $request, Warecorp_User $user) {
        $sql = "INSERT INTO zanby_users__activity_tracking(user_id,login,tracking_time,request_uri) VALUES (?,?,NOW(),?)";
        $this->getDb()->query($sql, array($user->getId(), $user->getLogin(), $request->getRequestUri()));
    }
    
    /**
     * Return db for tracker
     *
     * @return Zend_Db_Adapter_Abstract database
     */
    public function getDb() {
        return $this->db;
    }
    
    /**
     * Set db for tracker
     *
     * @param Zend_Db_Adapter_Abstract $db
     */
    public function setDb(Zend_Db_Adapter_Abstract $db) {
        $this->db = $db;
    }
    
    /**
     * Return types of user for whitch activity tracking perform
     *
     * @return int
     */
    public function getUserTypesTracking() {
        return $this->userTypesTracking;
    }
    
    /**
     * Set types of user for whitch activity tracking perform
     *
     * @param int $userTypesTracking
     */
    public function setUserTypesTracking($userTypesTracking) {
        $this->userTypesTracking = $userTypesTracking;
    }
    
    /**
     * Return true if tracking is enabled, otherwise false
     *
     * @return bool
     */
    public function getEnable() {
        return $this->enable;
    }
    
    /**
     * Enable process request
     *
     * @param bool $enable
     */
    public function setEnable($enable) {
        $this->enable = $enable;
    }
}
