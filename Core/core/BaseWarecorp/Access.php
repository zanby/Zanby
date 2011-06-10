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
 *
 * @package    Warecorp_Access
 * @copyright  Copyright (c) 2007
 * @author Halauniou
 */

/**
 *
 *
 */
class BaseWarecorp_Access
{
	private $xml;

	/**
     * Constructor
     *
     */
	public function __construct()
	{
	}

	/**
     * Load XML config
     *
     * @param unknown_type $configPath
     * @return unknown
     * @author Halauniou
     */
	public function loadXmlConfig($configPath)
	{
		if (file_exists($configPath)){
			$this->xml = simplexml_load_file($configPath);
			return true;
		} else {
			return  false;
		}
	}

	/**
     * Check is pair controller:action allowed for user
     *
     * @param unknown_type $controller
     * @param unknown_type $action
     * @return unknown
     */

	public function isAllowed($controller, $action){
        $result = $this->xml->xpath("/config/module/$controller");

        if ($result){

            foreach ($result[0]->action as $act){
        		if (strtolower($act) == strtolower($action)) return true;
			}

            /**
             * Give access to the whole controller for the anonymous users
             * @author Sergey Petkevich
             */
			foreach($result[0]->attributes() as $key => $val) {
                if ( $key == 'allowallactions' && $val == 'true' ) {
                    return true;
                }
            }
		}
		return false;
	}

	/**
	 * Check is RSS enabled for current controller/action pair
	 * @return boolean
	 * @author Kondratiev
	 */

	public static function isRssAllowed($controller, $action){
	    $access = new self();
		/**
		 * Choose configuration file
		 * if file exits in root access folder get it else
		 * get configuration file from ESA|EIA folder
		 */
		if ( file_exists(ACCESS_RIGHTS_DIR.'rss_allowed.xml') ) {
			$cfg_access_file = ACCESS_RIGHTS_DIR.'rss_allowed.xml';
		} elseif ( file_exists(ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/rss_allowed.xml') ) {
			$cfg_access_file = ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/rss_allowed.xml';
		} else {
		   throw new Zend_Exception('Configuration file \'rss_allowed.xml\' was not found.');
		}
	    $access->loadXmlConfig($cfg_access_file);
	    return $access->isAllowed($controller, $action);
	}

	/**
     * redirect to login page
     *
     */

	static function redirectToLogin($path = null)
    {
		if ( $path !== null ) {
            $_SESSION['login_return_page'] = $path;
        } else {
            if ( !preg_match("/[\.jpg|\.jpeg|\.file]$/i", $_SERVER['REQUEST_URI']) ) {
                $_SESSION['login_return_page'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            }
        }
        header('LOCATION: http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        exit;
	}

	/**
     * redirect to login page
     *
     */

	static function redirectToLoginXajax($xajax){

		$objResponse = new xajaxResponse();
		$sContentHeader = "Content-type: text/xml;";
		if ($xajax->sEncoding && strlen(trim($xajax->sEncoding)) > 0) {
			$sContentHeader .= " charset=".$xajax->sEncoding;
		}
		header($sContentHeader);
		$objResponse->addRedirect(BASE_URL."/".LOCALE."/users/login/");
		echo $objResponse->getXML();
		exit;
	}

}
