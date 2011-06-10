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
 *
 * @package    Warecorp_Group_Simple
 * @copyright  Copyright (c) 2006
 */

/**
 * Class for Image processing
 *
 */
class BaseWarecorp_Common_TagString
{

    /**
     * Create string with links to tags
     *
     * @param array $tagArray       - array of tags
     * @param string $baseHref      - url for tags
     * @param string $delimeter     - delimeter to divide tags in string
     * @param int $limit            - max tags in string
     * @return string
     */

    static function makeTagString($tagArray, $baseHref, $delimeter, $limit = null, $key = false)
    {
        $tagCount       = count($tagArray);
        $outputString   = "";
        if ($limit !== null) $tagCount = floor($limit);

        for ($i=0; $i < $tagCount; $i++){
            $outputString .= '<a href="'.$baseHref.(($key == false)?$tagArray[$i]->name:$tagArray[$i]->id).'">'.$tagArray[$i]->name.'</a>'.$delimeter;
        }
        return $outputString;
    }
}
