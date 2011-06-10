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

class BaseWarecorp_Facebook_User extends Warecorp_Data_Entity {

    private $id;
    private $userId;
    private $facebookId;
    
    /**
     * Constructor.
     * @param string $key - name of key for user load, range of id|login|email.
     * if null (default) - user data don't loading.
     * @param string $val - key value
     * @return void
     * @author Artem Sukharev
     */
    public function __construct($facebookId = null)
    {
        parent::__construct('zanby_facebook__users', array(
            'id'            => 'id',
            'user_id'       => 'userId',
            'facebook_id'   => 'facebookId',
        ));
        if ( $facebookId !== null && !is_array($facebookId) ){
            $pkColName = $this->pkColName;
            $this->pkColName = 'facebook_id';
            $this->loadByPk($facebookId);
            $this->pkColName = $pkColName;
        } elseif (is_array($facebookId)) {
            $this->load($facebookId);
        }
    }
    
    /**
     * @param $id the $id to set
     */
    public function setId( $id )
    {
        $this->id = $id;
    }

    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param $facebookId the $facebookId to set
     */
    public function setFacebookId( $facebookId )
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return the $facebookId
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param $userId the $userId to set
     */
    public function setUserId( $userId )
    {
        $this->userId = $userId;
    }

    /**
     * @return the $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function save() {
        parent::save();
                
        /**
        * Events : Проверяем, если были attendee с таким fb - делаем их для данного пользователя
        */
        //$user = new Warecorp_User('id', $this->getUserId());
        //Warecorp_ICal_Attendee_List::updateAttendeeForNewFBUser($user, $this->getFacebookId());
        
    }
    
    /**
     * remove FB accociation
     * 
     * @see core_zanby5/core/Warecorp/Data/Warecorp_Data_Entity#delete()
     */
    public function delete( $removeFB = false ) 
    {
        /* TODO: remove allocation from Facebook for user */
        if ( $removeFB ) {
            /* remove application authorization for FB user */
            Warecorp_Facebook_Api::getInstance()->api(array('method'=>'Auth.revokeAuthorization','uid'=>$this->getFacebookId()));
        }
        parent::delete();
    }
        
    /**
     * 
     * @return Warecorp_User or false
     */
    static public function login() {       
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( empty($facebookId) ) return false;
        
        if ( !Warecorp_Facebook_User::isFBAccountConnected($facebookId) ) return false;        
        if ( null === $user = Warecorp_Facebook_User::loadUserByFacebookId($facebookId) ) return false;
        
        return $user;
    }
    
    /**
     * user is loginned if he/she has linked FB account and he/she works as it account
     * @param Warecorp_User $objUser
     * @return unknown_type
     */
    static public function isLogined(Warecorp_User $objUser) {
        $facebookId = Warecorp_Facebook_Api::getFacebookId();
        if ( empty($facebookId) ) return false;
        if ( !Warecorp_Facebook_User::isFBAccountConnected($facebookId) ) return false;
        if ( null === $user = Warecorp_Facebook_User::loadUserByFacebookId($facebookId) ) return false;
        if ( $user->getId() != $objUser->getId() ) return false;
        
        return true;
    }
    
    /**
     * 
     * @return bool
     */
    public function canPublishStream() {
        $response = Warecorp_Facebook_Api::getInstance()->api(array('method'=>'fql.query','query'=> 'select publish_stream from permissions where uid='.$this->getFacebookId()));
        return (isset($response[0]) && isset($response[0]['publish_stream']) && $response[0]['publish_stream']=='1');
    }
    
    /**
     * 
     * @return bool
     */
    public function canEmail() {
        $response = Warecorp_Facebook_Api::getInstance()->api(array('method'=>'fql.query','query'=> 'select email from permissions where uid='.$this->getFacebookId()));
        return (isset($response[0]) && isset($response[0]['email']) && $response[0]['email']=='1');
    }
    
    /**
     * 
     * @return bool
     */
    static public function removePermission($permission) {
        return Warecorp_Facebook_Api::getInstance()->api(array('method'=>'Auth.revokeExtendedPermission','perm'=>$permission));
    }
    
    /**
     * load Warecorp_Facebook_User by linked account id
     * 
     * @param $userId
     * @return Warecorp_Facebook_User
     */
    static public function loadByUserId($userId) {
        $_db = Zend_Registry::get('DB');
        $query = $_db->select('*')->from('zanby_facebook__users')->where('user_id = ?', $userId);
        $res = $_db->fetchRow($query);
        if ( empty($res) ) return null;
        
        return new Warecorp_Facebook_User($res);
    }
    
    /**
     * return Warecorp_User object related to facebook user or NULL
     * 
     * @param int $facebookId 
     * @return Warecorp_User
     */
    static public function loadUserByFacebookId($facebookId) {
        $fUser = new Warecorp_Facebook_User($facebookId);
        if ( !$fUser || !$fUser->getId() ) return null;
        else {
            $objUser = new Warecorp_User('id', $fUser->getUserId());
            if ( empty($objUser) || null === $objUser->getId() ) {
                $fUser->delete(true);
                return null;
            }
            return $objUser;
        }
    }
    
    /**
     * Check if Facebook account is connected to any Zanby account
     * @param $userID - id of Zanby account
     * @return boolean
     */
    static public function isFBAccountConnected($facebookId)
    {
        $_db = Zend_Registry::get('DB');
        $query = $_db->select()->from('zanby_facebook__users', new Zend_Db_Expr('count(*)'))->where('facebook_id = ?', $facebookId === null ? new Zend_Db_Expr('NULL') : $facebookId);
        $res = $_db->fetchOne($query);
        
        return (bool) $res;
    }
    
    /**
     * Check if Zanby account is connected to any Facebook account
     * @param $userId - id of Zanby account
     * @return boolean
     */
    static public function isZAccountConnected($userId)
    {
        $_db = Zend_Registry::get('DB');
        $query = $_db->select()->from('zanby_facebook__users', new Zend_Db_Expr('count(*)'))->where('user_id = ?', $userId === null ? new Zend_Db_Expr('NULL') : $userId);
        $res = $_db->fetchOne($query);
        
        return (bool) $res;
    }
    
    /**
     * 
     * @param $email
     * @return unknown_type
     */    
    static public function hashEmail($email) 
    {
        $email = trim(strtolower($email));
        return Warecorp_Facebook_User::computeUnsignedCRC32($email).'_'.md5($email);
    }
    
    /**
     * 
     * @param $str
     * @return unknown_type
     */
    static public function computeUnsignedCRC32 ( $str ){
        sscanf ( crc32 ( $str ), "%u" , $var );
        return $var ;
    }
    
    /**
     * return user information from Facebook
     * @param $facebookId
     * @param $fields
     * @return unknown_type
     * @TODO: it needs to cache results of function
     */
    static public function getInfo($facebookId, $fields = array())
    {
        if ( empty($fields) ) $fields = 'username,first_name,last_name,current_location,birthday,birthday_date,email_hashes,hometown_location,locale,name,pic,pic_with_logo,pic_big,pic_big_with_logo,pic_small,pic_small_with_logo,pic_square,pic_square_with_logo,sex,timezone,website';
        try {
            $facebookInfo = Warecorp_Facebook_Api::getInstance()->api(array('method'=>'fql.query','query'=>'select '.$fields.' from user where uid = '.$facebookId));
        } catch ( Exception $ex ) { $facebookInfo = null; }
         
        if ( empty($facebookInfo) ) {
            if ( is_array($facebookId) ) return array();
            else return null;                    
        } else {
            return $facebookInfo;
        }
    }
    
    /**
     * create uniq login for registration
     * @param $facebookInfo
     * @return unknown_type
     */
    static function createUniqLogin($facebookInfo) {        
        if ( !empty($facebookInfo['username']) && !Warecorp_User::isUserExists('login', $facebookInfo['username']) ) return $facebookInfo['username'];
        
        $facebookInfo['username'] = strtolower($facebookInfo['first_name']."".$facebookInfo['last_name']);
        if ( !Warecorp_User::isUserExists('login', $facebookInfo['username']) ) return $facebookInfo['username'];
                
        $facebookInfo['username'] = strtolower($facebookInfo['last_name']."".$facebookInfo['first_name']);
        if ( !Warecorp_User::isUserExists('login', $facebookInfo['username']) ) return $facebookInfo['username'];
        
        $i = 1;
        $isValid = false;
        while ( !$isValid ) {
            $facebookInfo['username'] = strtolower($facebookInfo['first_name']."".$facebookInfo['last_name']."".$i);
            if ( !Warecorp_User::isUserExists('login', $facebookInfo['username']) ) $isValid = true;
        }
        return $facebookInfo['username'];
    }
    
}
