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
 * @author Artem Sukharev
 * @version 1.0
 * @created 21-Jun-2007 14:43:25
 */
class BaseWarecorp_DiscussionServer_Enum_SubscriptionType
{
    const PAUSE            = 0;
	const DAILY            = 1;
	const WEEKLY           = 2;
	const DIGEST25         = 3;
	const DIGEST50         = 4;
	const SINGLE           = 5;

    static public function getAsOptions()
    {
        $options = array();
        $options[self::DAILY ]      = "Daily digest";
        $options[self::WEEKLY ]     = "Weekly digest";
        $options[self::DIGEST25 ]   = "Digest every 25 messages";
        $options[self::DIGEST50 ]   = "Digest every 50 messages";
        $options[self::SINGLE ]     = "Single emails";
        return $options;
    }
    static public function getAsOptionsWithPuse()
    {
        $options = array();
        $options[self::PAUSE]       = "PAUSE delivery";
        $options[self::DAILY]       = "Daily digest";
        $options[self::WEEKLY]      = "Weekly digest";
        $options[self::DIGEST25]    = "Digest every 25 messages";
        $options[self::DIGEST50]    = "Digest every 50 messages";
        $options[self::SINGLE]      = "Single emails";
        return $options;
    }
    static public function isIn($value)
    {
        if (    $value == self::DAILY ||
                $value == self::WEEKLY ||
                $value == self::DIGEST25 ||
                $value == self::DIGEST50 ||
                $value == self::SINGLE
        ) return true;
        else return false;
    }
}
?>
