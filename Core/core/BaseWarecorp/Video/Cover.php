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
class BaseWarecorp_Video_Cover
{
    private $video = null;
    private $src = null;
    private $path = null;
    private $commonPart = null;
    
    protected static $_maxPreviewWidth = 800;
    protected static $_maxPreviewHeight = 600;    
    /**
     * current width for image
     */
    private $width  = 0;
    /**
     * current height for image
     */
    private $height = 0;
    /**
     * curent border for image
     */
    private $border = 1;

    /**
     * proportional thumbnail or not
     */

    private $proportional = 0;

    /**
     * original image width
     */
    private $or_width;
    /**
     * original image height
     */
    private $or_height;

    private $coverSrc;
    private $coverPath;

		/**
		 * Configuration object contains some paths and urls for current
		 * theme.
		 * @author Aleksei Gusev
		 */
    static private $AppTheme = null;
    
    static public function getAppTheme() {
        if (self::$AppTheme === null){
            if ( Zend_Registry::isRegistered( 'AppTheme')) {
								self::$AppTheme = &Zend_Registry::get( 'AppTheme');
            } else {
								self::$AppTheme      				 = new stdClass();
								self::$AppTheme->images      = UPLOAD_BASE_URL;
								self::$AppTheme->images_path = UPLOAD_BASE_PATH;
            }
        }
        return self::$AppTheme;
    }

    function __construct(&$video)
    {
        $this->video = &$video;
        $this->src = UPLOAD_BASE_URL.'/upload/videogallery_videos/';
        $this->path = $this->video->getPath();
        $this->commonPart = $this->video->getFileCommonPart();
    }

    /**
     * get width
     * @author Artem Sukharev
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * set width
     * @param int $newValue
     * @return Warecorp_Photo_Abstract
     * @author Artem Sukharev
     */
    public function setWidth($newValue)
    {
        $this->width = $newValue;
        return $this;
    }

    /**
     * get height
     * @author Artem Sukharev
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * set height
     * @param int $newValue
     * @return Warecorp_Photo_Abstract
     * @author Artem Sukharev
     */
    public function setHeight($newValue)
    {
        $this->height = $newValue;
        return $this;
    }

    /**
     * get border
     * @author Artem Sukharev
     */
    public function getBorder()
    {
        return $this->border;
    }

    /**
     * set border
     * @param int $newValue
     * @return Warecorp_Photo_Abstract
     * @author Artem Sukharev
     */
    public function setBorder($newValue)
    {
        $this->border = $newValue;
        return $this;
    }

    /**
     * get is proportional
     * @author Eugene Halauniou
     */
    public function getProportional()
    {
        return $this->proportional;
    }
    /**
     * set is proportional
     * @param int $newValue
     * @author Artem Sukharev
     */
    public function setProportional($newValue)
    {
        $this->proportional = $newValue;
        return $this;
    }

    /**
     * get width of original image
     * @param boolean $prepare
     * @author Artem Sukharev
     */
    public function getOriginalWidth($prepare = false)
    {
        if ( $this->or_width === null || $this->or_height === null ) {
            $orImage = $this->getPath().'_orig.jpg';
            if ( file_exists($orImage) ) $size = getimagesize($orImage);
            else $size = getimagesize(Warecorp_Video_Cover::$coverPath.'noimage.jpg');
            $this->or_width = $size[0];
            $this->or_height = $size[1];
            if ( $prepare ) $this->prepareOriginalImage();
        }
        return $this->or_width;
    }

    /**
     * get height of original image
     * @param boolean $prepare
     * @author Artem Sukharev
     */
    public function getOriginalHeight($prepare = false)
    {
        if ( $this->or_width === null || $this->or_height === null ) {
            $orImage = $this->video->getPath().'_orig.jpg';
            if ( file_exists($orImage) ) $size = getimagesize($orImage);
            else $size = getimagesize(Warecorp_Video_Cover::$coverPath.'noimage.jpg');
            $this->or_width = $size[0];
            $this->or_height = $size[1];
            if ( $prepare ) $this->prepareOriginalImage();
        }
        return $this->or_height;
    }
    
    /**
     * prepare original image size
     * @return void
     * @author Artem Sukharev
     */
    private function prepareOriginalImage()
    {
        if ( $this->or_width > self::$_maxPreviewWidth && $this->or_height > self::$_maxPreviewHeight ) {
            $kw = Warecorp_Video_Cover::$_maxPreviewWidth / $this->or_width;
            $kh = Warecorp_Video_Cover::$_maxPreviewHeight / $this->or_height;
            $k = ($kw < $kh) ? $kw : $kh;
            $this->or_width = $this->or_width * $k;
            $this->or_height = $this->or_height * $k;
        } elseif ( $this->or_width > Warecorp_Video_Cover::$_maxPreviewWidth ) {
            $k = self::$_maxPreviewWidth / $this->or_width;
            $this->or_width = Warecorp_Video_Cover::$_maxPreviewWidth;
            $this->or_height = $this->or_height * $k;
        } elseif ( $this->or_height > Warecorp_Video_Cover::$_maxPreviewHeight ) {
            $k = self::$_maxPreviewHeight / $this->or_height;
            $this->or_width = $this->or_width * $k;
            $this->or_height = Warecorp_Video_Cover::$_maxPreviewHeight;
        }
    }

    public function getVideoId()
    {
        return $this->video->getId();
    }

    public function getPath()
    {
        return $this->path;
    } 

    public function getSrc()
    {
        return $this->src.$this->commonPart;
    }

    public function getImage($user = null)
    {
        if (!empty($user) && $user->getId() === null) $user = null;

        if ( $this->video->getSource() != Warecorp_Video_Enum_VideoSource::OWN) {
            $customSrcImg = $this->video->getCustomSrcImg();
            if (! Warecorp::url_exists($customSrcImg, 'image')) {
								$common_path 					= '/upload/videogallery_videos/' . $this->video->getSource();
								$image_relative_path 	= $common_path.'_x'.$this->width.'_y'.$this->height.'_b'.$this->border.'.jpg';
								$source_relative_path = $common_path.'.jpg';
								$source_path           = self::getAppTheme()->images_path.$source_relative_path;
								$image_path 					= self::getAppTheme()->images_path.$image_relative_path;
                if (! file_exists( $image_path)) {
                    $r0 = Warecorp_Image_Thumbnail::makeThumbnail( $source_path, $image_path,$this->width, $this->height, $this->border);
                }
				return self::getAppTheme()->images.$image_relative_path;
            } else {
                return $this->video->getCustomSrcImg();
            }
        }

        if ($this->getVideoId() !== null && file_exists($this->getPath().'_orig.jpg')){
            if (file_exists($this->getPath().'_x'.$this->width.'_y'.$this->height.'_b'.$this->border.'_p'.$this->proportional.'.jpg')) {
                return $this->getSrc().'_x'.$this->width.'_y'.$this->height.'_b'.$this->border.'_p'.$this->proportional.'.jpg';
            } else {
                $r0 = Warecorp_Image_Thumbnail::makeThumbnail($this->getPath().'_orig.jpg', $this->getPath().'_x'.$this->width.'_y'.$this->height.'_b'.$this->border.'_p'.$this->proportional.'.jpg', $this->width, $this->height, $this->border, $this->proportional);
                return $this->getSrc().'_x'.$this->width.'_y'.$this->height.'_b'.$this->border.'_p'.$this->proportional.'.jpg';
            }
        } else {
            if (! file_exists(self::getAppTheme()->images_path.'/upload/videogallery_videos/noimage_x'.$this->width.'_y'.$this->height.'_b'.$this->border.'.jpg')) {
                $r0 = Warecorp_Image_Thumbnail::makeThumbnail(self::getAppTheme()->images_path.'/upload/videogallery_videos/noimage.jpg', self::getAppTheme()->images_path.'/upload/videogallery_videos/noimage_x'.$this->width.'_y'.$this->height.'_b'.$this->border.'.jpg', $this->width, $this->height, $this->border);
            }
						return self::getAppTheme()->images.'/upload/videogallery_videos/'.'noimage_x'.$this->width.'_y'.$this->height.'_b'.$this->border.'.jpg';
        }
    }
}
