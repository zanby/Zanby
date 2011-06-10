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

class BaseWarecorp_SOAP_Client extends SoapClient 
{
    private $username;
    private $password;
    
    /**
     * @param $password the $password to set
     */
    public function setPassword( $password )
    {
        $this->password = $password;
    }

    /**
     * @return the $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $username the $username to set
     */
    public function setUsername( $username )
    {
        $this->username = $username;
    }

    /**
     * @return the $username
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    function __doRequest($request, $location, $saction, $version) {
        $doc = new DOMDocument('1.0');
        $doc->loadXML($request);

        require_once ENGINE_DIR.'/SoapWSS/xmlseclibs.php';
        require_once ENGINE_DIR.'/SoapWSS/soap-wsse.php';

        $objWSSE = new WSSESoap($doc);

        /* add UsernameToken */
        $objWSSE->addUserToken($this->getUsername(), md5($this->getPassword()));
        
        /* add Timestamp with no expiration timestamp */
        $objWSSE->addTimestamp();

        /* create new XMLSec Key using RSA SHA-1 and type is private key */
        //$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));

        /* load the private key from file - last arg is bool if key in file (TRUE) or is string (FALSE) */
        //$objKey->loadKey(PRIVATE_KEY, TRUE);

        /* Sign the message - also signs appropraite WS-Security items */
        //$objWSSE->signSoapDoc($objKey);

        /* Add certificate (BinarySecurityToken) to the message and attach pointer to Signature */
        //$token = $objWSSE->addBinaryToken(file_get_contents(CERT_FILE));
        //$objWSSE->attachTokentoSig($token);

        return parent::__doRequest($objWSSE->saveXML(), $location, $saction, $version);
    }
}
