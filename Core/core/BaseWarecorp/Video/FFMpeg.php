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
 * @package Warecorp_Video
 * @author Yury Zolotarsky
 * @version 1.0
 */
class BaseWarecorp_Video_FFMpeg
{  
    public static function makeConversionToFLV($sourceFile, $destFile)
    {
        exec("ffmpeg -y -i $sourceFile -ar 22050 -ab 32 -f flv -s 640x480 $destFile"); //|flvtool2 -U stdin $destFile probably need to
 
        if (!file_exists($destFile)) return false; else return true;        
    }
    
    public static function getFrame($sourceFile, $destFile)
    {
        $movie = new ffmpeg_movie($sourceFile, false);

        $s = $movie->getFrame(1);
        $image = $s->toGDImage();                
        return imagejpeg($image, $destFile);
    }
    
    public static function getLength($sourceFile)
    {        
        if (!file_exists($sourceFile)) return 0;
        $movie = new ffmpeg_movie($sourceFile, false);
        if ($movie) {
            return $movie->getDuration();
        }
        return 0;
    }       
}
