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
 */

class BaseWarecorp_Common_Utf8
{
    /**
     * return len of utf-8 string
     * @param string $string 
     * @return int 
     */
    static public function getStrlen($string)
    {
        return preg_match_all('/./u', $string, $tmp);
    }
    /**
     * retun substring of string
     */
    function getSubstr($s, $offset, $len = 'all')
    {
        if ($offset < 0) $offset = self::getStrlen($s) + $offset;
        if ( $len != 'all' ) {
           if ( $len < 0 ) $len = self::getStrlen($s) - $offset + $len;
           $xlen = self::getStrlen($s) - $offset;
           $len = ( $len > $xlen ) ? $xlen : $len;
           preg_match('/^.{' . $offset . '}(.{0,'.$len.'})/us', $s, $tmp);
        } else {
           preg_match('/^.{' . $offset . '}(.*)/us', $s, $tmp);
        }
        return (isset($tmp[1])) ? $tmp[1] : false;
    }    
}
