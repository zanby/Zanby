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

class BaseWarecorp_Video_AccessManager_EIA extends Warecorp_Video_AccessManager {
    static protected $instance;
    static protected $eiFamily;

    /**
     * @return Warecorp_Video_AccessManager_EIA
     */
    static public function getInctance()
	{
		if ( self::$instance === null )
            self::$instance = new self();
		return self::$instance;
	}

    static protected function isOwnerEIA($context, $user)
    {
        if ( $context instanceof Warecorp_User )
            return false;

        if ( !defined("IMPLEMENTATION_FAMILY_GROUP_UID") || IMPLEMENTATION_FAMILY_GROUP_UID === '' )
            return false;
        if ( NULL === self::$eiFamily )
            self::$eiFamily = Zend_Registry::get("globalGroup");
        if ( !self::$eiFamily || !(self::$eiFamily instanceof Warecorp_Group_Family) )
            return false;

        if ( !self::$eiFamily->getGroups()->isGroupInFamily($context) )
            return false;
        if ( self::$eiFamily->getMembers()->isHost($user) || self::$eiFamily->getMembers()->isCohost($user) )
            return true;
        return FALSE;
    }

    public static function canViewCommentsGallery($gallery, $context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewCommentsGallery($gallery, $context, $user) || self::isOwnerEIA($context, $user) && $gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId();
        }
        return parent::canViewCommentsGallery($gallery, $context, $user);
    }

    public static function canViewGalleries($context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewGalleries($context, $user) || self::isOwnerEIA($context, $user);
        }
        return parent::canViewGalleries($context, $user);
    }

    public static function canViewGallery($gallery, $context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewGallery($gallery, $context, $user) || self::isOwnerEIA($context, $user) && $gallery->getOwnerType() == 'group';
        }
        return parent::canViewGallery($gallery, $context, $user);
    }

    public static function canViewPrivateGalleries($context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewPrivateGalleries($context, $user) || self::isOwnerEIA($context, $user);
        }
        return parent::canViewPrivateGalleries($context, $user);
    }

    public static function canViewPublicGalleries($context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewPublicGalleries($context, $user) || self::isOwnerEIA($context, $user);
        }
        return parent::canViewPublicGalleries($context, $user);
    }

    public static function canViewShareHistoryGallery($gallery, $context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewShareHistoryGallery($gallery, $context, $user) || self::isOwnerEIA($context, $user) && $gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId();
        }
        return parent::canViewShareHistoryGallery($gallery, $context, $user);
    }
}
