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

class BaseWarecorp_SOAP_Type_Recipient {
    private $email;
    private $name;
    private $locale;
    private $params;
    
	/**
     * @return the $email
     */
    public function getEmail()
    {
        return $this->email;
    }

	/**
     * @param $email the $email to set
     */
    public function setEmail( $email )
    {
        $this->email = $email;
    }

	/**
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

	/**
     * @param $name the $name to set
     */
    public function setName( $name )
    {
        $this->name = $name;
    }

	/**
     * @return the $locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

	/**
     * @param $locale the $locale to set
     */
    public function setLocale( $locale )
    {
        $this->locale = $locale;
    }

	/**
     * @return the $params
     */
    public function getParams()
    {
        return $this->params;
    }

	/**
     * @param $params the $params to set
     */
    public function setParams( $params )
    {
        $this->params = $params;
    }

    /**
     * add new param for recipient
     * @param string $key
     * @param string $value
     * @return Warecorp_SOAP_Type_Recipient
     */
    public function addParam( $key, $value )
    {
        $this->params[] = new Warecorp_SOAP_Type_Param( $key, $value );
        return $this;
    }  
}
