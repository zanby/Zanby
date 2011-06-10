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

class BaseWarecorp_SOAP_Type_Params
{
    private $params;
    
	/**
     * @return array $params
     */
    public function getParams()
    {
        return $this->params;
    }

	/**
     * @param array $params
     * @return Warecorp_SOAP_Type_Params
     */
    public function setParams( $params )
    {
        $this->params = $params;
        return $this;
    }
        
    /**
     * add new param
     * @param string $key
     * @param string $value
     * @return Warecorp_SOAP_Type_Params
     */
    public function addParam( $key, $value )
    {
        $this->params[] = new Warecorp_SOAP_Type_Param( $key, $value );
        return $this;
    }
    
    /**
     * load default parameters for campaign
     * @return void
     */
    public function loadDefaultCampaignParams()
    {
        defined('LOCALE') || define('LOCALE', Warecorp::$locale);
        $this->params[] = new Warecorp_SOAP_Type_Param( 'SITE_NAME', SITE_NAME_AS_STRING );
        $this->params[] = new Warecorp_SOAP_Type_Param( 'SITE_URL', SITE_NAME_AS_FULL_DOMAIN );
        $this->params[] = new Warecorp_SOAP_Type_Param( 'SITE_NAME_AS_DOMAIN', SITE_NAME_AS_DOMAIN );
        $this->params[] = new Warecorp_SOAP_Type_Param( 'BASE_HTTP_HOST', BASE_HTTP_HOST );
        $this->params[] = new Warecorp_SOAP_Type_Param( 'SITE_EMAIL_FEEDBACK', 'feedback@'.DOMAIN_FOR_EMAIL );
        $this->params[] = new Warecorp_SOAP_Type_Param( 'SITE_LINK_PRIVACY', BASE_URL.'/'.LOCALE.'/info/privacy/' );
        $this->params[] = new Warecorp_SOAP_Type_Param( 'SITE_LINK_TERMS', BASE_URL.'/'.LOCALE.'/info/terms/' );
        $this->params[] = new Warecorp_SOAP_Type_Param( 'SITE_LINK_REGISTRATION', BASE_URL.'/'.LOCALE.'/registration/index/' );
    }
}
