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

class BaseWarecorp_SOAP_Type_Recipients
{
    private $recipients = array();
    
	/**
     * @return the $recipients
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * 
     * @return int
     */
    public function getCount()
    {
        return sizeof($this->recipients);
    }
    
    /**
     * unset recipients
     * @return Warecorp_SOAP_Type_Recipients
     */
    public function clean()
    {
        $this->recipients = array();
        return $this;
    }
    
	/**
     * @param $recipients the $recipients to set
     * @return Warecorp_SOAP_Type_Recipients
     */
    public function setRecipients( $recipients )
    {
        $this->recipients = $recipients;
        return $this;
    }

    /**
     * add new recipient
     * @param $objRecipient
     * @return Warecorp_SOAP_Type_Recipients
     */
    public function addRecipient( Warecorp_SOAP_Type_Recipient $objRecipient )
    {
        $this->recipients[] = $objRecipient;
        return $this;
    }
    
}
