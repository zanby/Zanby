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

class BaseWarecorp_ICal_Const
{
    public static $weekdaysOptions = array(
        'MO'    => 'Monday',
        'TU'    => 'Tuesday',
        'WE'    => 'Wednesday',
        'TH'    => 'Thursday',
        'FR'    => 'Friday',
        'SA'    => 'Saturday',
        'SU'    => 'Sunday'
    );

    public static $monthsOptions = array(
        '1'     => 'January',
        '2'     => 'February',
        '3'     => 'March',
        '4'     => 'April',
        '5'     => 'May',
        '6'     => 'June',
        '7'     => 'July',
        '8'     => 'August',
        '9'     => 'September',
        '10'    => 'October',
        '11'    => 'November',
        '12'    => 'December'
    );

    public static $setposOptions = array(
        '1'     => '1',
        '2'     => '2',
        '3'     => '3',
        '4'     => '4',
        '-1'    => 'last'
    );

    public static $everyOptions = array(
        '1'     => 'Every',
        '2'     => 'Every other',
        '3'     => 'Every third',
        '4'     => 'Every fourth'
    );

    public static $monthSideOptions = array(
        '+1'     => 'First',
        '-1'     => 'Last'
    );

    public static $minutesOptions = array(
        0 => ':00',
        15 => ':15',
        30 => ':30',
        45 => ':45'
    );

    public static $durMinutesOptions = array(
        0 => '0 mins',
        15 => '15 mins',
        30 => '30 mins',
        45 => '45 mins'
    );

    public static $ReminderOptions1     = array(
        '300'           => '5 minutes',
        '900'           => '15 minutes',
        '1800'          => '30 minutes',
        '3600'          => '1 hour',
        '7200'          => '2 hours',
        '10800'         => '3 hours',
        '21600'         => '6 hours',
        '43200'         => '12 hours',
        '86400'         => '1 day',
        '172800'        => '2 days',
        '259200'        => '3 days',
        '345600'        => '4 days',
        '432000'        => '5 days',
        '518400'        => '6 days',
        '604800'        => '7 days',
        '691200'        => '8 days',
        '777600'        => '9 days',
        '864000'        => '10 days',
        '950400'        => '11 days',
        '1036800'       => '12 days',
        '1123200'       => '13 days',
        '1209600'       => '14 days');

    public static $ReminderOptions2     = array(
        '0'             => '----',
        '300'           => '5 minutes',
        '900'           => '15 minutes',
        '1800'          => '30 minutes',
        '3600'          => '1 hour',
        '7200'          => '2 hours',
        '10800'         => '3 hours',
        '21600'         => '6 hours',
        '43200'         => '12 hours',
        '86400'         => '1 day',
        '172800'        => '2 days',
        '259200'        => '3 days',
        '345600'        => '4 days',
        '432000'        => '5 days',
        '518400'        => '6 days',
        '604800'        => '7 days',
        '691200'        => '8 days',
        '777600'        => '9 days',
        '864000'        => '10 days',
        '950400'        => '11 days',
        '1036800'       => '12 days',
        '1123200'       => '13 days',
        '1209600'       => '14 days');

    public static function getHours()
    {
        $hours = array();
        for ( $i = 0; $i <= 23; $i ++ ) $hours[$i] = date( "h a", mktime( $i, 0, 0, 1, 1, 2000 ) );
        return $hours;
    }

    public static function getDuration()
    {
        $hours = array();
        for ( $i = 1; $i <= 8; $i ++ ){
            $hours[($i-0.5)*60] = $i-1 .":30";
            $hours[$i*60] = $i .":00";
        }
        $hours['all'] = "All Day";
        
        return $hours;
    }

    
    public static function getHoursDur()
    {
        $dur_hours = array();
        for ( $i = 0; $i <= 12; $i ++ ) $dur_hours[$i] = $i . " hr.";
        return $dur_hours;
    }
}
