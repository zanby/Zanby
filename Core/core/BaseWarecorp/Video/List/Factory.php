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
 * Warecorp FRAMEWORK
 * @package Warecorp_Video_List
 * @author Yury Zolotarsky
 * @version 1.0
 */
class BaseWarecorp_Video_List_Factory
{

	public static function load($gallery)
	{
		if ( $gallery instanceof Warecorp_Video_Gallery_User ) {
            $obj = new Warecorp_Video_List_User($gallery->getOwnerId());
            $obj->setGalleryId($gallery->getId());
            return $obj;            
		} elseif ( $gallery instanceof Warecorp_Video_Gallery_Simple ) {
            $obj = new Warecorp_Video_List_Simple($gallery->getOwnerId());
            $obj->setGalleryId($gallery->getId());
            return $obj;
		} elseif ( $gallery instanceof Warecorp_Video_Gallery_Family ) {
            $obj = new Warecorp_Video_List_Simple($gallery->getOwnerId());
            $obj->setGalleryId($gallery->getId());
            return $obj;
        } else {
            throw new Zend_Exception('Unknown gallery type');
        }
	}
    
    public static function loadByOwner($owner)
    {
        if ( $owner instanceof Warecorp_User ) {
            return new Warecorp_Video_List_User($owner->getId());
        } elseif ( $owner instanceof Warecorp_Group_Simple ) {
            return new Warecorp_Video_List_Simple($owner->getId());
        } elseif ( $owner instanceof Warecorp_Group_Family ) {
            return new Warecorp_Video_List_Family($owner->getId());
        } else {
            throw new Zend_Exception('Unknown owner type');
        }
    }
}
