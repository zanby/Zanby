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

class BaseWarecorp_List_AccessManager_ESA extends Warecorp_List_AccessManager
{
    /**
     * Return instance of Access Manager
     * @return Warecorp_List_AccessManager
     */
    static public function getInstance() 
    {      
        if ( !self::$instance ) {
            self::$instance = new Warecorp_List_AccessManager_ESA();
        }
        return self::$instance;
    }

    public static function canViewList($list, $context, $user) {
        if ( $context instanceof Warecorp_Group_Base && (!$user || $user instanceof  Warecorp_User && !$user->getId()) )
            return false;
        return parent::canViewList($list, $context, $user);
    }

    public static function canViewLists($context, $user) {
        if ( $context instanceof Warecorp_Group_Base && (!$user || $user instanceof  Warecorp_User && !$user->getId()) )
            return false;
        return parent::canViewLists($context, $user);
    }

    public static function canViewPublicLists($context, $user) {
        if ( $context instanceof Warecorp_Group_Base && (!$user || $user instanceof  Warecorp_User && !$user->getId()) )
            return false;
        return parent::canViewPublicLists($context, $user);
    }

    public static function canViewSharedLists($context, $user) {
        if ( $context instanceof Warecorp_Group_Base && (!$user || $user instanceof  Warecorp_User && !$user->getId()) )
            return false;
        return parent::canViewSharedLists($context, $user);
    }
}
