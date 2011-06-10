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

abstract class BaseWarecorp_SOAP_Server_WSSecurity_UsernameToken
{
    protected $username;
    protected $password;
    protected $nonce;
    protected $created;
    protected $is_validated = false;
    protected $is_valid = false;
    
    /**
     * @return the $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username the $username to set
     */
    public function setUsername( $username )
    {
        $this->username = $username;
    }

    /**
     * @return the $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password the $password to set
     */
    public function setPassword( $password )
    {
        $this->password = $password;
    }

    /**
     * @return the $nonce
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * @param $nonce the $nonce to set
     */
    public function setNonce( $nonce )
    {
        $this->nonce = $nonce;
    }

    /**
     * @return the $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param $created the $created to set
     */
    public function setCreated( $created )
    {
        $this->created = $created;
    }  

    /**
     * 
     * @return boolean
     */
    public function isValid()
    {
        if ( !$this->is_validated ) $this->is_valid = $this->validate();
        return $this->is_valid;
    }
    
    abstract protected function validate();
}