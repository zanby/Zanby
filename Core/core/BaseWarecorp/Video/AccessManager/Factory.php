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

class BaseWarecorp_Video_AccessManager_Factory {
    /**
     * @return Warecorp_Video_AccessManager
     */
    static public function create($context = null)
    {
        return Warecorp_Video_AccessManager_EIA::getInctance();
        
        
        
        if ( null === $context )
            $context = HTTP_CONTEXT;
        switch ( $context ) {
            case 'zanby'            : return Warecorp_Video_AccessManager_EIA::getInctance();
            case 'zanby-product'    : return Warecorp_Video_AccessManager_EIA::getInctance();
            case 'at'               : return Warecorp_Video_AccessManager_EIA::getInctance();
            case 'cd'               : return Warecorp_Video_AccessManager_EIA::getInctance();
            case 'z1sky'            : return Warecorp_Video_AccessManager_EIA::getInctance();
            case 'theuptake'        : return Warecorp_Video_AccessManager_EIA::getInctance();
            case 'irishfair'        : return Warecorp_Video_AccessManager_EIA::getInctance();
            case 'finnegans'        : return Warecorp_Video_AccessManager_EIA::getInctance();
            case 'zcc'              : return Warecorp_Video_AccessManager_EIA::getInctance();
            case 'zanby-product-ei' : return Warecorp_Video_AccessManager_EIA::getInctance();
            default                 :
                /**
            	 * Look if specific class for implementation exsits
            	 * if yes - load it, if don't exist - look at IMPLEMENTATION_TYPE or load default access manager
            	 */
            	try {
                    $className = 'Warecorp_Video_AccessManager_'.ucfirst(strtolower($context));
            	    @Zend_Loader::loadClass($className);
            	    return Warecorp_Video_AccessManager::getInctance($className);
            	} catch (Exception $ex) {
	                if ( defined('IMPLEMENTATION_TYPE') ) {
	                    if ( 'ESA' == IMPLEMENTATION_TYPE )
                            return Warecorp_Video_AccessManager_EIA::getInctance();
	                    else
                            return Warecorp_Video_AccessManager_EIA::getInctance();
	                } else {
	                   return Warecorp_Video_AccessManager::getInctance();
	                }
            	}
        }
    }
}
