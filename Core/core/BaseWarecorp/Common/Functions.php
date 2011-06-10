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

class BaseWarecorp_Common_Functions
{
    //komarovski   
    public function getRandomString($return_as_md5 = false, $length = 10, $characters_set_id = 0)
    {
        $length = intval($length);
        $characters_set_id = intval($characters_set_id);
        
        $allowable_characters = array();
        $allowable_characters[] = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
 
        $len = strlen($allowable_characters[$characters_set_id]);
        mt_srand((double)microtime() * 1000000);
        $code = '';
        for ( $i = 0; $i < $length; $i++ ) {
                $code .= $allowable_characters[$characters_set_id][mt_rand(0, $len - 1)];
        }
        
        if ($return_as_md5) $code = md5($code);
        
        return $code;
    }
    
    //komarovski - returns distance between coordinates
	public static function getPcSphereDistance($lat1, $lon1, $lat2, $lon2, $radius = 6378.135) {
	  $rad = doubleval(M_PI/180.0);
	
	  $lat1 = doubleval($lat1) * $rad;
	  $lon1 = doubleval($lon1) * $rad;
	    $lat2 = doubleval($lat2) * $rad;
	    $lon2 = doubleval($lon2) * $rad;
	
	  $theta = $lon2 - $lon1;
	  $dist = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta));
	  if ($dist < 0) { $dist += M_PI; }
	
	    return $dist = $dist * $radius; // Default is Earth equatorial radius in kilometers
	}
	
	/**
	 * getMinZoomLevelForGMapByAreaCoordinatesAndRealSize
	 * @param $maxlongitude
	 * @param $minlongitude
	 * @param $maxlatitude
	 * @param $minlatitude
	 * @param $width width in pixels of map
	 * @param $height
	 * @return zoom level (integer)
	 * @author Alexander Komarovski 
	 */
	public static function getMinZoomLevelForGMapByAreaCoordinatesAndRealSize($maxlongitude, $minlongitude, $maxlatitude, $minlatitude, $width, $height) {
	    $_distX = array();
	    $_distY = array();
	    $_distY[] = Warecorp_Common_Functions::getPcSphereDistance($minlatitude, $minlongitude, $maxlatitude, $minlongitude);
	    $_distY[] = Warecorp_Common_Functions::getPcSphereDistance($minlatitude, $maxlongitude, $maxlatitude, $maxlongitude);
	    $_distX[] = Warecorp_Common_Functions::getPcSphereDistance($minlatitude, $minlongitude, $minlatitude, $maxlongitude);
	    $_distX[] = Warecorp_Common_Functions::getPcSphereDistance($maxlatitude, $minlongitude, $maxlatitude, $maxlongitude);
	        //$lat1, $lon1, $lat2, $lon2
	    $maxdistanceX = max($_distX);
	    $maxdistanceY = max($_distY);
	        
	    //meters in pixel
	    $minpx = ($maxdistanceX*1000)/$width;
	    $zoomX = 17-log($minpx,2);
	        
	    $minpx = ($maxdistanceY*1000)/$height;
	    $zoomY = 17-log($minpx,2);
	        
	    $zoom = intval(min(array($zoomX, $zoomY)));
	        
	    return $zoom;
	}
    
	
	public static function getCentralCoordinateFrom2Longitudes($coord1, $coord2) {
        //1 - min, 2 - max
		if (  ((abs($coord1) + abs($coord2)) <180 ) || ( $coord1/abs($coord1) == $coord2/abs($coord2))  ) {
            return ($coord2-$coord1)/2+$coord1;
        } else {
            $delta = ( (180-abs($coord1)) + (180-abs($coord2)) )/2;
            return (abs($coord1)+$delta)<=180?(   ($coord1/abs($coord1)) * abs($coord1)+$delta):   ( ($coord2/abs($coord2)) *  abs($coord2)+$delta);       
        }
	}      
}
