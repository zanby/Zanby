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
 * @package    Warecorp_Data
 * @copyright  Copyright (c) 2009
 * @author     Aleksei Gusev
 *
 * @todo I should add property noimage and setter/getter methods to
 *       eliminate optional parameter noimage for method getImage().
 */

abstract class BaseWarecorp_Data_Entity_Image extends Warecorp_Data_Entity
{
  private $upload_dirname; // e.g. "/upload/grop_avatars/" for Warecorp_Group_Avatar

  private $width  = 0;
  private $height = 0;
  private $border = 1;

  private $isProportionalInUse = false;
  private $proportional = 0;

  public function __construct( $upload_dirname,
			       $tableName = false,
			       $fields = null,
			       $options = array())
  {
    $defaults = array( 'proportinalInUse' => false);
    $options  = array_merge( $defaults, $options);

    if ( $options['proportinalInUse']) {
      $this->isProportionalInUse = true;
    }
        
    $this->upload_dirname = $upload_dirname.DIRECTORY_SEPARATOR;
    parent::__construct( $tableName, $fields);
  }
    
  // This function deletes original and all generated images.
  public function delete()
  {
    $images = glob($this->getPath().'*');
    if ( sizeof( $images) != 0) {
      foreach ( $images as $i)
	unlink( $i);
    }
    parent::delete();
  }

  public function getPath() {
    return UPLOAD_BASE_PATH.$this->upload_dirname.$this->getBasename();
  }
  public function getPathOrig() {
    return $this->getPath().'_orig.jpg';
  }
  public function getPathFull() {
    if ( $this->isProportionalInUse) {
      return $this->addXYBorderAndProportional( $this->getPath());
    }
    return $this->addXYandBorder( $this->getPath());
  }

  public function getNoImagePath( $noimage = 'noimage'){
    return self::getAppTheme()->images_path
      . $this->upload_dirname
      . $noimage;
  }
  public function getNoImagePathOrig( $noimage = 'noimage'){
    return $this->getNoImagePath( $noimage).'.jpg';
  }
  public function getNoImagePathFull( $noimage = 'noimage'){
    return $this->addXYandBorder( $this->getNoImagePath( $noimage));
  }
    
  public function getSrc() {
    return UPLOAD_BASE_URL.$this->upload_dirname.$this->getBasename();
  }
  public function getSrcFull() {
    if ( $this->isProportionalInUse) {
      return $this->addXYBorderAndProportional( $this->getSrc());
    }             
    return $this->addXYandBorder( $this->getSrc());
  }

  public function getWidth() { return $this->width; }
  public function setWidth( $newValue)
  {
    $this->width = $newValue;
    return $this;
  }

  public function getHeight() { return $this->height; }
  public function setHeight( $newValue)
  {
    $this->height = $newValue;
    return $this;
  }

  public function getBorder() { return $this->border; }
  public function setBorder( $newValue)
  {
    $this->border = $newValue;
    return $this;
  }

  public function getProportional() { return $this->proportional; }
  public function setProportional( $newValue)
  {
    $this->proportional = $newValue;
    return $this;
  }

  public function getUploadDirname(){
    return $this->upload_dirname;
  }
  /**
   * return image url with width and height (if exists) else create
   * it and return image url if width or height not set - return
   * _orig.image
   *
   * @author Aleksei Gusev
   */
  public function getImage( $options = array())
  {
    $defaults = array( 'noimage' => 'noimage');
    $options  = array_merge( $defaults, $options);

    // An avatar is really uploaded. Generate thumbnail if
    // neccessary and return full URL.
    if ( file_exists( $this->getPathOrig())) {
      $this->getOrCreateThumbnail();
      return $this->getSrcFull();
    }
        
    // There is no uploaded avatar. So, we have to return full URL
    // to 'noimage' file. This file different from theme to theme.
    // We also should generate.
    $this->getOrCreateNoimageThumbnail( $options['noimage']);
    return $this->addXYandBorder( self::getAppTheme()->images
				  . $this->upload_dirname
				  . $options['noimage']);
  }
  
  
  public function getNoImage( $options = array())
  {
    $options = array( 'noimage' => 'noimage');
    
    $this->getOrCreateNoimageThumbnail( $options['noimage']);
    return $this->addXYandBorder( self::getAppTheme()->images
                  . $this->upload_dirname
                  . $options['noimage']);
  }
  
  protected function getOrCreateThumbnail(){
    if (! file_exists( $this->getPathFull())) {
      Warecorp_Image_Thumbnail::makeThumbnail( $this->getPathOrig(),
					       $this->getPathFull(),
					       $this->getWidth(),
					       $this->getHeight(),
					       true,
					       $this->getProportional());
    }
    return $this->getPathFull();
  }

  protected function getOrCreateNoimageThumbnail( $noimage = 'noimage'){
    if (! file_exists( $this->getNoImagePathFull( $noimage))) {
      Warecorp_Image_Thumbnail::makeThumbnail( $this->getNoImagePathOrig( $noimage),
					       $this->getNoImagePathFull( $noimage),
					       $this->getWidth(),
					       $this->getHeight(),
					       true);
    }
    return $this->getNoImagePathFull();
  }

  protected function addXYandBorder( $base) {
    return $base
      .'_x'.$this->getWidth()
      .'_y'.$this->getHeight()
      .'_b'.$this->getBorder()
      .'.jpg';
  }

  protected function addXYBorderAndProportional( $base){
    return $base
      .'_x'.$this->getWidth()
      .'_y'.$this->getHeight()
      .'_b'.$this->getBorder()
      .'_p'.$this->getProportional()
      .'.jpg';
  }

  /* This function should be defined in derived classes to provide
     template for detecting basename of file: hash of the filename
     (usually md5(id+something)). */
  abstract protected function getBasename();
}
