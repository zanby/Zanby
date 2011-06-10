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

class BaseWarecorp_Facebook_Api {
	protected static $_instance;

    protected static $_facebookId;
	
	public static function getInstance()
	{
		if ( null === self::$_instance ) {
			
			if ( !defined('FACEBOOK_API_KEY') || !defined('FACEBOOK_API_SECRET') || !defined('FACEBOOK_APP_ID')) throw new Exception('Incorrect facebook application configuration');
            require_once 'facebook.php'; 
			self::$_instance = new Facebook(array('appId'=>FACEBOOK_APP_ID, 'secret'=>FACEBOOK_API_SECRET, 'cookie'=>true));
		}
		return self::$_instance;
	}
	
	public static function getFacebookId()
	{
        if (self::$_facebookId === null) {
            self::$_facebookId = self::checkConnection();
        }
        return self::$_facebookId;
	}
	
	public static function checkConnection()
	{
        $uid = self::getInstance()->getUser();
        if (!$uid) return false;

        return $uid;
	}

	public static function getFBLoginButton()
	{
		return '<fb:login-button onlogin="facebook_onlogin_ready();"  size="medium" length="long"></fb:login-button>';
	}
}
