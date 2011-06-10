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

class BaseWarecorp_Wordpress_SSO
{
    const LIFETIME = 180;
    
    static public function authenticate( $iserID )
    {
        if ( WP_SSO_ENABLED && self::isWordpressSiteEnabled() ) {
            $_SESSION['_WPAuthSSO_'] = $iserID;
            setcookie("_WPAuthSSO_", $iserID, 0, "/",'.'.BASE_HTTP_HOST);
        }
    }
    
    static public function onControllerPostDispatch()
    {
        if ( WP_SSO_ENABLED && self::isWordpressSiteEnabled() ) {
            $urlWPSSO = '';
            if ( isset($_SESSION['_WPAuthSSO_']) && !empty($_SESSION['_WPAuthSSO_']) ) {
                $ssoKey = $_SESSION['_WPAuthSSO_'];
            } elseif ( isset($_COOKIE['_WPAuthSSO_']) && !empty($_COOKIE['_WPAuthSSO_']) ) {
                $ssoKey = $_COOKIE['_WPAuthSSO_'];
            } else $ssoKey = null;

            //  ssoKey is found
            if ( $ssoKey ) {                
                $code = md5(uniqid(mt_rand(), true));
                $cache = Warecorp_Cache::getFileCache();
                $cache->save($ssoKey, 'SSO_'.$code, array(), self::LIFETIME);
                if ( isset($_COOKIE['zanby_username']) && isset($_COOKIE['zanby_password']) ) {
                    $urlWPSSO = WP_SSO_URL.'?zssodoaction=signin&rememberme=1&key='.$code.'&ret=noreturn';
                } else {
                    $urlWPSSO = WP_SSO_URL.'?zssodoaction=signin&key='.$code.'&ret=noreturn';
                }
                unset($_SESSION['_WPAuthSSO_']);
                setcookie("_WPAuthSSO_", '', 0, "/",'.'.BASE_HTTP_HOST);
            }
            return '<iframe style="width:1px; height:1px; visibility:hidden; display:none;" id="WP_ZSSO_IFrame" src="'.$urlWPSSO.'"></iframe>';
        }
        return '';
    }
    
    static public function getJsResponse( $callback = null )
    {
        if ( WP_SSO_ENABLED && self::isWordpressSiteEnabled() ) {
            $urlWPSSO = '';
            $ssoKey = (!empty($_SESSION['_WPAuthSSO_'])) ? $_SESSION['_WPAuthSSO_'] : ((!empty($_COOKIE['_WPAuthSSO_'])) ? $_COOKIE['_WPAuthSSO_'] : null);
            if ( $ssoKey ) {
                $code = md5(uniqid(mt_rand(), true));
                $cache = Warecorp_Cache::getFileCache();
                $cache->save($ssoKey, 'SSO_'.$code, array(), self::LIFETIME);
                if ( isset($_COOKIE['zanby_username']) && isset($_COOKIE['zanby_password']) ) {
                    $urlWPSSO = WP_SSO_URL.'?zssodoaction=signin&rememberme=1&key='.$code.'&ret=noreturn';
                } else {
                    $urlWPSSO = WP_SSO_URL.'?zssodoaction=signin&key='.$code.'&ret=noreturn';
                }
                unset($_SESSION['_WPAuthSSO_']);
                setcookie("_WPAuthSSO_", '', 0, "/",'.'.BASE_HTTP_HOST);                
            }
            return '$("#WP_ZSSO_IFrame").attr("src", "'.$urlWPSSO.'"); $("#WP_ZSSO_IFrame").load(function(){ '.$callback.' });';
        }
        return '';
    }
    
    static public function isWordpressSiteEnabled()
    {
        $url = WP_SSO_URL.'/wp-content/plugins/zanby-sso/zanby-sso-check-configuration.php';
        //$url = 'http://wp.zccf.zanbylab.com'.'/wp-content/plugins/zanby-sso/zanby-sso-check-configuration.php';
        $handle   = curl_init($url);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_TIMEOUT, 1);
        //curl_setopt($handle, CURLOPT_FAILONERROR, 1);  
        curl_setopt($handle, CURLOPT_POST, 1);   
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 0);        
        $connectable = curl_exec($handle);
        $error = curl_error($handle);
        curl_close($handle);
        
        if ( $error || strpos($connectable, '200 OK') === false ) return false;
        else  return true;
    }
}
