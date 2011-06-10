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

class BaseWarecorp_Document_AccessManager_ESA extends Warecorp_Document_AccessManager
{
    /**
     * Return instance of Access Manager
     * @return Warecorp_Document_AccessManager
     */
    static public function getInstance()
    {
        if ( !self::$instance ) {
            self::$instance = new Warecorp_Document_AccessManager_ESA();
        }
        return self::$instance;
    }
	
    public static function canAnonymousViewDocuments($objContext)
    {
        return false;
    }

    public static function canViewDocument(Warecorp_Document_Item $document, $context, $user) {
        if ( $context instanceof Warecorp_Group_Base && (!$user || $user instanceof  Warecorp_User && !$user->getId()) )
            return false;
        return parent::canViewDocument($document, $context, $user);
    }

    public static function canViewPublicDocuments($context, $owner, $user_id) {
        if ( $owner instanceof Warecorp_Group_Base && !$user_id )
            return false;
        return parent::canViewPublicDocuments($context, $owner, $user_id);
    }

    public static function canViewFamilySharedDocuments($group, $family, $user) {
        if ( !$user || $user instanceof  Warecorp_User && !$user->getId() )
            return false;
        parent::canViewFamilySharedDocuments($group, $family, $user);
    }
}
