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
 * @package Warecorp_Video_Enum
 * @author Yury Zolotarsky
 * @version 1.0
 */
class BaseWarecorp_Video_Enum_ImportActionType
{
    const SAVE_VIDEO            = 'save_video';
    const MERGE_VIDEO           = 'merge_video';
    const SAVE_GALLERY          = 'save_gallery';
    const MERGE_GALLERY         = 'merge_gallery';
    const WATCH_GALLERY         = 'watch_gallery';
    
    /**
     * check is value in enum
     * @param mixed $value
     * @return boolean
     * @author Artem Sukharev
     */
    public static function isIn($value)
    {
        if ( 
            $value != self::SAVE_VIDEO && 
            $value != self::MERGE_VIDEO &&
            $value != self::SAVE_GALLERY &&
            $value != self::MERGE_GALLERY &&
            $value != self::WATCH_GALLERY
        ) {
           return false;
        } 
        return true;
    }
}
?>
