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
 * @package Warecorp_Video_Enum
 * @author Yury Zolotarsky
 * @version 1.0
 */
class BaseWarecorp_Video_Enum_VideoSource
{
    const OWN          = 'own';
    const YOUTUBE      = 'youtube';
    const BLIPTV       = 'bliptv';
    const NONVIDEO     = 'nonvideo';

    static private $instance;
    
    static public function getInstance()
    {
        if ( self::$instance == null ) self::$instance = new Warecorp_Video_Enum_VideoSource();
        return self::$instance;
    }    
    /**
     * check is value in enum
     * @param mixed $value
     * @return boolean
     * @author Yury Zolotarsky
     */
    public static function isIn($value)
    {
        if ( 
            $value != self::OWN && 
            $value != self::YOUTUBE && 
            $value != self::BLIPTV &&
            $value != self::NONVIDEO
        ) {
           return false;
        } 
        return true;
    }
    
    /**
     * translate value to int
     * @return int
     * @author Yury Zolotarsky
     */
    public function translate($value)
    {
        switch ( $value ) {
            case self::OWN        : return 0; break;
            case self::YOUTUBE    : return 1; break;
            case self::BLIPTV     : return 2; break;
            case self::NONVIDEO   : return 3; break;
            default               : throw new Zend_Exception('Incorrect value');
        }
    }
    
    static public function getEnumAsArray($excludeOwn = false, $excludeNonVideo = true)
    {
        $result = array(            
            1 => self::YOUTUBE,
            2 => self::BLIPTV
        );
        
        if (!$excludeOwn) $result[0] = self::OWN;
        if (!$excludeNonVideo) $result[3] = self::NONVIDEO;
        
        return $result;
    }
}
?>
