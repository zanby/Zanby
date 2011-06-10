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
 * @package Warecorp_Video_Gallery
 * @author Yury Zolotarsky
 * @version 1.0
 */
class BaseWarecorp_Video_Gallery_Factory
{

	public static function loadById($galleryId)
	{

		$db = Zend_Registry::get('DB');
		$query = $db->select();
		$query->from(Warecorp_Video_Gallery_Abstract::$_dbTableName, array('owner_id', 'owner_type'));
		$query->where('id = ?', $galleryId);
		$res = $db->fetchRow($query);

		if ( $res ) {
			switch ( $res['owner_type'] ) {
				case 'user'     : {
                    return new Warecorp_Video_Gallery_User($galleryId);   
                    break;
                }
				case 'group'    : {
					$group = Warecorp_Group_Factory::loadById($res['owner_id']);
					if ( $group instanceof Warecorp_Group_Simple ) {
                        return new Warecorp_Video_Gallery_Simple($galleryId);
					} elseif ( $group instanceof Warecorp_Group_Family ) {
                        return new Warecorp_Video_Gallery_Family($galleryId);
					}
				    break;
				}  				
				default : throw new Zend_Exception('Unknown gallery owner');
			}
		}

		return null;
	}


    public static function createByOwner($owner)
    {
    	if ( $owner instanceof Warecorp_User ) {
            return new Warecorp_Video_Gallery_User();
    	} elseif ( $owner instanceof Warecorp_Group_Simple ) {
            return new Warecorp_Video_Gallery_Simple();
        } elseif ( $owner instanceof Warecorp_Group_Family ) {
            return new Warecorp_Video_Gallery_Family();
        } else {
            throw new Zend_Exception('Unknown owner');
        }
    }
}
?>
