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

class BaseWarecorp_SOAP_Service_SSO
{
    /**
     * @return boolean
     */
    public function testService()
    {
        if ( !WP_SSO_ENABLED ) return false;
        return true;
    }
    
    /**
     * @param string $username First string
     * @param string $password Second string
     * @return string
     */
	public function authByUsernameAndPassword( $username, $password )
	{
	    if ( !WP_SSO_ENABLED ) return '';
	    // TODO : fix it
	    $password = strtolower($password);
	    
	    $objUser = new Warecorp_User('login', $username);
        if ( $objUser && null !== $objUser->getId() && Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE == $objUser->getStatus() && $password == $objUser->getPass() ) {
            $code = md5(uniqid(mt_rand(), true));
            $cache = Warecorp_Cache::getFileCache();
            $cache->save($objUser->getId(), 'SSO_'.$code, array(), 30);
            return $code;
        }
        return '';
	}
	
    /**
     * @param string $email First string
     * @param string $password Second string
     * @return string
     */
    public function authByEmailAndPassword( $email, $password )
    {
        if ( !WP_SSO_ENABLED ) return '';
        // TODO : fix it
        $password = strtolower($password);
        
        $objUser = new Warecorp_User('email', $email);
        if ( $objUser && null !== $objUser->getId() && Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE == $objUser->getStatus() && $password == $objUser->getPass() ) {
            $code = md5(uniqid(mt_rand(), true));
            $cache = Warecorp_Cache::getFileCache();
            $cache->save($objUser->getId(), 'SSO_'.$code, array(), 30);
            return $code;
        }
        return '';
        
    }
    
    /**
     * @param string $username First string
     * @return string
     */
    public function restoreByUsername( $username )
    {
        if ( !WP_SSO_ENABLED ) return '';
        $objUser = new Warecorp_User('login', $username);
        if ( $objUser && null !== $objUser->getId() && Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE == $objUser->getStatus() ) {
            $code = md5(uniqid(mt_rand(), true));
            $cache = Warecorp_Cache::getFileCache();
            $cache->save($objUser->getId(), 'SSO_RPASS_'.$code, array(), 30);
            return $code;
        }
        return '';
    }
    
    /**
     * @param string $email First string
     * @return string
     */
    public function restoreByEmail( $email )
    {
        if ( !WP_SSO_ENABLED ) return '';
        $objUser = new Warecorp_User('email', $email);
        if ( $objUser && null !== $objUser->getId() && Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE == $objUser->getStatus() ) {
            $code = md5(uniqid(mt_rand(), true));
            $cache = Warecorp_Cache::getFileCache();
            $cache->save($objUser->getId(), 'SSO_RPASS_'.$code, array(), 30);
            return $code;
        }
        return '';
        
    }
    
    /**
     * @param string $key First string
     * @return array
     */
    public function getUserInfoBySSOKey( $key )
    {
        if ( !WP_SSO_ENABLED ) return array();
        $cache = Warecorp_Cache::getFileCache();
        if ( $userID = $cache->load('SSO_'.$key) ) {
            $objUser = new Warecorp_User('id', $userID);
            if ( $objUser && null !== $objUser->getId() ) {
                if ( Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE == $objUser->getStatus() || Warecorp_User_Enum_UserStatus::USER_STATUS_PENDING == $objUser->getStatus() ) {
                    $cache->save($objUser->getId(), 'SSO_'.$key, array(), Warecorp_Wordpress_SSO::LIFETIME);
                    return array(
                        'user_pass'     => $objUser->getPass,
                        'user_login'    => $objUser->getLogin(),
                        'user_nicename' => $objUser->getFirstname().' '.$objUser->getLastname(),
                        'user_email'    => $objUser->getEmail(),
                        'display_name'  => $objUser->getFirstname().' '.$objUser->getLastname(),
                        'first_name'    => $objUser->getFirstname(),
                        'last_name'     => $objUser->getLastname(),
                    );
                }
            }
        }
        
        return array();
    }
    
    /**
     * @param string $email First string
     * @param int $size Second int
     * @return string
     */
    public function getUserAvatarByEmail( $email, $size )
    {
        if ( !WP_SSO_ENABLED ) return '';
        $objUser = new Warecorp_User('email', $email);
        if ( $objUser && null !== $objUser->getId() && Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE == $objUser->getStatus() ) {
            return "<img src='{$objUser->getAvatar()->setWidth($size)->setHeight($size)->getImage()}' title='' alt='' width='{$size}' height='{$size}' class='avatar avatar-{$size} photo'/>";
        }
        return '';
        
    }
    
    /**
     * @param string $email First string
     * @return string
     */
    public function getProfileUrlByEmail( $email )
    {
        if ( !WP_SSO_ENABLED ) return '';
        $objUser = new Warecorp_User('email', $email);
        if ( $objUser && null !== $objUser->getId() && Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE == $objUser->getStatus() ) {
            defined('LOCALE')
                || define('LOCALE', 'en');
            return $objUser->getUserPath('profile');
        }
        return '';
    }
    
    /**
     * @param string $key First string
     * @return boolean
     */
    public function commit( $key )
    {
        if ( !WP_SSO_ENABLED ) return false;
        $cache = Warecorp_Cache::getFileCache();
        $cache->remove('SSO_'.$key);
        return true;
    }
}