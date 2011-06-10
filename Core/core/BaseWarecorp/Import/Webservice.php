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
 * Webservice import wrapper
 *
 */

require_once('OpenInviter/openinviter.php');

class BaseWarecorp_Import_Webservice
{

    /**
     * Get contact list from web account
     * 
     * @param string $email
     * @param string $pass
     * @throws Warecorp_Import_NoPlugin_Exception
     * @throws Warecorp_Import_WrongAccount_Exception
     * @throws Warecorp_Import_ServiceDown_Exception
     * @return array List of contacts
     */
    public static function fetchContacts($email, $pass) 
    {
        $result = -1;
        
        $oi = new openinviter();

        $oi->getPlugins();
        
        $plName = $oi->getPluginByDomain($email);

        if ( $plName === false || !$oi->startPlugin($plName) ) {
            // cant find web email plagin
            throw new Warecorp_Import_NoPlugin_Exception();
        } else {
        
            if ($oi->login($email, $pass) ) {
                $contacts = $oi->getMyContacts();
                
                //if (is_null($contacts)) $contacts = -1;

                if (is_array($contacts)) {
                    $result = array();
                    foreach ($contacts as $key => $contactName) {
                        
                        if ($email == $key) {
                            // skip yourself
                            continue;
                        }
                        
                        $_parts = preg_split('/[,\s]+/', $contactName, -1, PREG_SPLIT_NO_EMPTY);
                        $result[] = (object)array(
                            'firstName' => isset($_parts[0]) ? $_parts[0] : '',
                            'lastName' => isset($_parts[1]) ? $_parts[1] : '',
                            'email' => $key,
                        );
                    }
                }
            } else {
                if ($oi->getInternalError() == 'Server not responding') {
                    throw new Warecorp_Import_ServiceDown_Exception();
                } else {
                    // No error for wrong account returned.
                    // Assume that if the server responded that the error is only in your account 
                    throw new Warecorp_Import_WrongAccount_Exception();
                }
            }
        }

        return $result;
    }


}
