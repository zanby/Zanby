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


class BaseWarecorp_Date {
    const DATES = Zend_Date::DATES;
    
    const DATE_FULL = Zend_Date::DATE_FULL;
    const DATE_LONG = Zend_Date::DATE_LONG;
    const DATE_MEDIUM = Zend_Date::DATE_MEDIUM;
    const DATE_SHORT = Zend_Date::DATE_SHORT;

    const TIMES = Zend_Date::TIMES;
    const TIME_FULL = Zend_Date::TIME_FULL;
    const TIME_LONG = Zend_Date::TIME_LONG;
    const TIME_MEDIUM = Zend_Date::TIME_MEDIUM;
    const TIME_SHORT = Zend_Date::TIME_SHORT;

    const DATETIME = Zend_Date::DATETIME;
    const DATETIME_FULL = Zend_Date::DATETIME_FULL;
    const DATETIME_LONG = Zend_Date::DATETIME_LONG;
    const DATETIME_MEDIUM = Zend_Date::DATETIME_MEDIUM;
    const DATETIME_SHORT = Zend_Date::DATETIME_SHORT;
    
    static function getFormat($value) {
        switch ($value){
            case 'DATE_FULL': return self::DATE_FULL;
            case 'DATE_LONG': return self::DATE_LONG;
            case 'DATE_MEDIUM': return self::DATE_MEDIUM;
            case 'DATE_SHORT': return self::DATE_SHORT;
            case 'TIMES': return self::TIMES;
            case 'TIME_FULL': return self::TIME_FULL;
            case 'TIME_LONG': return self::TIME_LONG;
            case 'TIME_MEDIUM': return self::TIME_MEDIUM;
            case 'TIME_SHORT': return self::TIME_SHORT;
            case 'DATETIME': return self::DATETIME;
            case 'DATETIME_FULL': return self::DATETIME_FULL;
            case 'DATETIME_LONG': return self::DATETIME_LONG;
            case 'DATETIME_MEDIUM': return self::DATETIME_MEDIUM;
            case 'DATETIME_SHORT': return self::DATETIME_SHORT;
            default: throw new Exception( "Undefined date format ".$value."\n" );
        }
    }
    
    static function getLocalesListAsArray() {
        $result = array();
        $dom = new DOMDocument();
        $dom->load(CONFIG_DIR.'/cfg.locale.xml');
        $locales = $dom->getElementsByTagName('config')->item(0)->getElementsByTagName('locales')->item(0)->getElementsByTagName('locale');
        if ( 0 != $locales->length ) {
            foreach ( $locales as $_locale ) {
                $result[$_locale->nodeValue] = $_locale->getAttribute('name');
            }
        }
        return $result;
    }
    
}