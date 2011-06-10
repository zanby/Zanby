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

require_once ENGINE_DIR . '/xmlseclibs/xmlseclibs.php';
//require_once ENGINE_DIR . '/Server/WSSecurity/UsernameToken.php';
//require_once ENGINE_DIR . '/Server/Model/Log.php';

class BaseWarecorp_SOAP_Server_WSSecurity
{
    const WSSENS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    const WSSENS_2003 = 'http://schemas.xmlsoap.org/ws/2003/06/secext';
    const WSUNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    const WSSEPFX = 'wsse';
    const WSUPFX = 'wsu';
    private $soapNS, $soapPFX;
    private $soapDoc = NULL;
    private $envelope = NULL;
    private $SOAPXPath = NULL;
    private $secNode = NULL;
    public $signAllHeaders = false;    
    private $usernameToken;

    public function setUsernameToken(Warecorp_SOAP_Server_WSSecurity_UsernameToken $usernameToken)
    {
        $this->usernameToken = $usernameToken;
        return $this;
    }
    
    private function locateSecurityHeader($setActor=NULL) {
        $wsNamespace = NULL;
        if ($this->secNode == NULL) {
            $headers = $this->SOAPXPath->query('//wssoap:Envelope/wssoap:Header');
            if ($header = $headers->item(0)) {
                $secnodes = $this->SOAPXPath->query('./*[local-name()="Security"]', $header);
                $secnode = NULL;
                foreach ($secnodes AS $node) {
                    $nsURI = $node->namespaceURI;
                    if (($nsURI == self::WSSENS) || ($nsURI == self::WSSENS_2003)) {
                        $actor = $node->getAttributeNS($this->soapNS, 'actor');
                        if (empty($actor) || ($actor == $setActor)) {
                            $secnode = $node;
                            $wsNamespace = $nsURI;
                            break;
                        }
                    }
                }
            }
            $this->secNode = $secnode;
        }
        return $wsNamespace;
    }

    public function __construct($doc) { 
        $this->soapDoc = $doc;
        $this->envelope = $doc->documentElement;
        $this->soapNS = $this->envelope->namespaceURI;
        $this->soapPFX = $this->envelope->prefix;
        $this->SOAPXPath = new DOMXPath($doc);
        $this->SOAPXPath->registerNamespace('wssoap', $this->soapNS);
        $this->SOAPXPath->registerNamespace('wswsu', Warecorp_SOAP_Server_WSSecurity::WSUNS);
        $wsNamespace = $this->locateSecurityHeader();       
        if ( !empty($wsNamespace) ) { $this->SOAPXPath->registerNamespace('wswsse', $wsNamespace); }
    }
    
    protected function processSignature($refNode) {
        $objXMLSecDSig = new XMLSecurityDSig();
        $objXMLSecDSig->idKeys[] = 'wswsu:Id';
        $objXMLSecDSig->idNS['wswsu'] = Warecorp_SOAP_Server_WSSecurity::WSUNS;
        $objXMLSecDSig->sigNode = $refNode;

        /* Canonicalize the signed info */
        $objXMLSecDSig->canonicalizeSignedInfo();

        $retVal = $objXMLSecDSig->validateReference();

        if (! $retVal) { throw new Exception("Validation Failed"); }

        $key = NULL;
        $objKey = $objXMLSecDSig->locateKey();

        if ($objKey) {
            if ($objKeyInfo = XMLSecEnc::staticLocateKeyInfo($objKey, $refNode)) {
                /* Handle any additional key processing such as encrypted keys here */
            }
        }

        if (empty($objKey)) {
            throw new Exception("Error loading key to handle Signature");
        }
        do {
            if ( empty($objKey->key) ) {
                $this->SOAPXPath->registerNamespace('xmlsecdsig', XMLSecurityDSig::XMLDSIGNS);
                $query = "./xmlsecdsig:KeyInfo/wswsse:SecurityTokenReference/wswsse:Reference";
                $nodeset = $this->SOAPXPath->query($query, $refNode);
                if ($encmeth = $nodeset->item(0)) {
                    if ($uri = $encmeth->getAttribute("URI")) {
                        $arUrl = parse_url($uri);
                        if (empty($arUrl['path']) && ($identifier = $arUrl['fragment'])) {
                            $query = '//wswsse:BinarySecurityToken[@wswsu:Id="'.$identifier.'"]';
                            $nodeset = $this->SOAPXPath->query($query);
                            if ($encmeth = $nodeset->item(0)) {
                                $x509cert = $encmeth->textContent;
                                $x509cert = str_replace(array("\r", "\n"), "", $x509cert);
                                $x509cert = "-----BEGIN CERTIFICATE-----\n".chunk_split($x509cert, 64, "\n")."-----END CERTIFICATE-----\n";
                                $objKey->loadKey($x509cert);
                                break;
                            }
                        }
                    }
                }
                throw new Exception("Error loading key to handle Signature");
            }
        } while(0);

        if (! $objXMLSecDSig->verify($objKey)) { throw new Exception("Unable to validate Signature"); }

        return TRUE;
    }

    protected function processUsernameToken($refNode)
    {
        if ( null === $this->usernameToken ) return false;
               
        $node = $refNode->firstChild;
        while ($node) {
            $nextNode = $node->nextSibling; 
            switch ($node->localName) {
                case "Username":
                    $this->usernameToken->setUsername($node->textContent);
                    break;
                case "Password" : 
                    $this->usernameToken->setPassword($node->textContent);
                    break;
                case "Nonce" : 
                    $this->usernameToken->setNonce($node->textContent);
                    break;
                case "Created" :
                    $this->usernameToken->setCreated($node->textContent);                    
                    break;
            }
            $node = $nextNode;
        }
        
        try { $isValid = $this->usernameToken->isValid(); }
        catch ( Exception $e ) { throw $e; }        
        if ( !$isValid ) {
            date_default_timezone_set('UTC');                
            //Server_Model_Log::log('['.date('j/M/Y:H:i:s O').'] Authentification failed:'.$this->usernameToken->getUsername().':'.$this->usernameToken->getPassword(), 'authentification.log');
            throw new Exception('Authentification failed');
        }
        
        return true;
    }
    
    protected function processTimestamp($refNode)
    {
        $node = $refNode->firstChild;
        while ($node) {
            $nextNode = $node->nextSibling; 
            switch ($node->localName) {
                case "Created":
                    break;
                case "Expires" : 
                    break;
            }
            $node = $nextNode;
        }        
    }
    
    public function process() 
    {                
        if ( empty($this->secNode) ) { throw new Exception('Authentification failed'); }
               
        $node = $this->secNode->firstChild;
        while ($node) {
            $nextNode = $node->nextSibling;     
            switch ($node->localName) {
                /*
                case "Signature":
                    if ($this->processSignature($node)) {
                        if ($node->parentNode) { $node->parentNode->removeChild($node); }
                    } else { return false; }
                    break;
                */
                case 'UsernameToken' : 
                    $this->processUsernameToken($node);
                    break;
                case 'Timestamp' :
                    break;
            }
            $node = $nextNode;
        }
        
        $this->secNode->parentNode->removeChild($this->secNode);
        $this->secNode = NULL;
        
        /* if there isn't UsernameToken in Security Header - failed  */
        if ( $this->usernameToken == null || !$this->usernameToken->isValid() ) {
            date_default_timezone_set('UTC');                
            //Server_Model_Log::log('['.date('j/M/Y:H:i:s O').'] Authentification failed:No username token', 'authentification.log');            
            throw new Exception('Authentification failed');
        }
        
        return true;
    }
    
    public function saveXML() {
        return $this->soapDoc->saveXML();
    }

    public function save($file) {
        return $this->soapDoc->save($file);
    }
    
    public function failed($message = 'Authentification failed')
    {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
        <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
            <SOAP-ENV:Body>
                <SOAP-ENV:Fault>
                    <faultcode>HTTP</faultcode>
                    <faultstring>'.$message.'</faultstring>
                </SOAP-ENV:Fault>
            </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>';
        Header('Content-type: text/xml');
        echo $body;
        exit();
    }
}