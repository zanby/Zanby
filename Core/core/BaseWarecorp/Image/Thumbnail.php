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
class BaseWarecorp_Image_Thumbnail
{
    /**
     * @param string $sourcePath - source image path
     *
     * @param string $thumbnailPath - thumbnail path
     *
     * @param integer $targetWidth - thumbnail width
     *
     * @param integer $targetHeight - thumbnail height
     *
     * @param boolean $addBorder - draw border and leave initial size of image, if thumbnail size bigger than it.
     *
     * @param boolean $proportional - if set, create thumbnail without cropping of image
     * 
     * @param string $borderColor - string representation of RGB color for border. format R,G,B
     *
     * @param string $result - contain result of convertion. ok - if all right, or error message;
     *
     */

    static function makeThumbnail($sourcePath, $thumbnailPath, $targetWidth = 0, $targetHeight = 0, $addBorder = false, $proportional = 0, $borderColor = "255,255,255" )
    {  
        $targetWidth     = floor($targetWidth);
        $targetHeight    = floor($targetHeight);
        $addBorder       = (bool)$addBorder;
        $borderColor     = $borderColor;
        $proportional	 = (bool)$proportional;
        $result          = "ok";
        /*Get image size */
        $sourceSize = getimagesize($sourcePath);
        if (false === $sourceSize) {
            $result = "cant get image size";
            return $result;
        }

        //Check image type using MIME info
        $format = strtolower(substr($sourceSize['mime'], strpos($sourceSize['mime'], '/')+1));   
        
        if ($format === 'bmp') {
            require_once('bmp.php');
        }
        
        $icfunc = "imagecreatefrom" . $format;
        
        if (!function_exists($icfunc)) { 
            $result = "function ($icfunc) isnt exist";
            return $result;
        }                               
         
        $sourcePic = @$icfunc($sourcePath);
        //print $sourcePic." <br>";
        if ($sourcePic == "") { 
            $result = "cant apply $icfunc function";
            return $result;
        }

        if ($targetHeight == 0 || $targetWidth == 0) $addBorder = false; // if proportional - no border!
        //check zero values, for proportional thumbnails----------------------------\
        if ($targetHeight == 0 && $targetWidth == 0){//return initial image         |
            $width = $sourceSize[0];//                                              |
            $height = $sourceSize[1];//                                             |
        } elseif ($targetHeight == 0) {//                                           |
            $width = $targetWidth;//                                                |
            $height = $sourceSize[1]/($sourceSize[0]/$targetWidth);//               |
        } elseif ($targetWidth == 0){//                                             |
            $width = $sourceSize[0]/($sourceSize[1]/$targetHeight);//               |
            $height = $targetHeight;//                                              |
        } else {//                                                                  |
            $width = $targetWidth;  //thumbnail width                               |
            $height = $targetHeight;//thumbnail height                              |
        }//                                                                         |
        //--------------------------------------------------------------------------/


        $bwidth = $sourceSize[0]; 	  //initial image width
        $bheight = $sourceSize[1];    //initial image height

        //add border to image
        if ($addBorder) {
            $rgb = explode(",", $borderColor);

            if ($bwidth < $width){//add border
                $temp = imagecreatetruecolor($width, $bheight);
                $white = imagecolorallocate($temp, $rgb[0], $rgb[1], $rgb[2]);
                imagefilledrectangle($temp, 0,0,$width, $bheight, $white);
                imagecopyresampled($temp, $sourcePic, ($width - $bwidth)/2, 0, 0, 0, $bwidth, $bheight, $bwidth, $bheight);
                $bwidth = $width;
                $sourcePic = $temp;
            }

            if ($bheight < $height){//add border
                $temp = imagecreatetruecolor($bwidth, $height);
                $white = imagecolorallocate($temp, $rgb[0], $rgb[1], $rgb[2]);
                imagefilledrectangle($temp, 0,0, $bwidth, $height, $white);
                imagecopyresampled($temp, $sourcePic, 0, ($height - $bheight)/2, 0, 0, $bwidth, $bheight, $bwidth, $bheight);
                $bheight = $height;
                $sourcePic = $temp;
            }
        }

        if ($bwidth < $targetWidth && $bheight < $targetHeight) {
            $proportional = false; // if original picture too small, disable proportional
            $width = $sourceSize[0];//
            $height = $sourceSize[1];//
        }

        if ($proportional){ //create proportinal image
            $ratio_orig = $bwidth/$bheight;

            if ($targetWidth/$targetHeight > $ratio_orig) {
                $targetWidth = $targetHeight*$ratio_orig;
            } else {
                $targetHeight = $targetWidth/$ratio_orig;
            }

            // Resample
            $temp = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled($temp, $sourcePic, 0, 0, 0, 0, $targetWidth, $targetHeight, $bwidth, $bheight);

        } else {// create cropped
            //resize
            $scaleX = $bwidth / $width;
            $scaleY = $bheight / $height;

            if ($scaleX > $scaleY){
                $newWidth = $bwidth / $scaleY;
                $newHeight = $height;
            } else {
                $newWidth = $width;
                $newHeight = $bheight / $scaleX;
            }

            $thumbnailPic = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($thumbnailPic,$sourcePic, 0, 0, 0, 0, $newWidth, $newHeight, $bwidth, $bheight);

            $temp = imagecreatetruecolor($width, $height);
            if ($newWidth > $width){
                imagecopyresampled($temp, $thumbnailPic, 0, 0, ($newWidth-$width)/2, 0, $width, $height, $width, $newHeight);
            } else {
                imagecopyresampled($temp, $thumbnailPic, 0, 0, 0, ($newHeight-$height)/2, $width, $height, $newWidth, $height);
            }
            imagedestroy($thumbnailPic);
        }

        //create final image
        imagejpeg($temp, $thumbnailPath);// default quality ~75

        if (!file_exists($thumbnailPath)){
            $result = "cant create file";
            return $result;
        }

        imagedestroy($temp);
        imagedestroy($sourcePic);
        return $result;
    }

    static function imageRotate($imagePath, $direction = 0){

       
        $source = imagecreatefromjpeg(DOC_ROOT.$imagePath);
        $rotate = imagerotate($source, $direction, 0);
        imagejpeg($rotate, DOC_ROOT.$imagePath);
    }
}
