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
   * @package Warecorp_Photo
   * @author Artem Sukharev
   * @version 1.0
   */
abstract class BaseWarecorp_Photo_Abstract extends Warecorp_Data_Entity_Image implements Warecorp_Global_iSearchFields
{
	/**
	 * table name
	 */
	public static $_dbTableName = 'zanby_galleries__photos';

	protected static $_maxPreviewWidth = 800;
	protected static $_maxPreviewHeight = 600;
	/**
	 * id of photo
	 */
	private $id;
	/**
	 * id of photo gallery
	 */
	private $galleryId;
	/**
	 * reference of gallery object
	 */
	private $gallery;
	/**
	 * id of user created this photo
	 */
	private $creatorId;
	/**
	 * referance of user object created this photo
	 */
	private $creator;
	/**
	 * title of photo
	 */
	private $title;
	/**
	 * description of photo
	 */
	private $description;
	/**
	 * additional information of photo
	 */
	private $additionalInfo;
	/**
	 * date of creation
	 */
	private $createDate;
	/**
	 * original image width
	 */
	private $or_width;
	/**
	 * original image height
	 */
	private $or_height;

	public static $age_requirement = 16;

	function __construct($photoId = null)
	{
		parent::__construct( '/upload/gallery_photos',
												 self::$_dbTableName,
												 null,
												 array( 'proportinalInUse' => true));
		$this->addField('id');
		$this->addField('gallery_id', 'galleryId');
		$this->addField('creator_id', 'creatorId');
		$this->addField('title');
		$this->addField('description');
		$this->addField('additional_info', 'additionalInfo');
		$this->addField('creation_date', 'createDate');

		if ( $photoId != null ) {
			$this->pkColName = 'id';
			$this->loadByPk($photoId);
		}
	}

	protected function getBasename(){
		return md5( $this->getId() . 'zbphoto');
	}

	/**
	 * id of photo
	 * @author Artem Sukharev
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * id of photo
	 * @param newVal
	 * @return Warecorp_Photo_Abstract
	 * @author Artem Sukharev
	 */
	public function setId($newVal)
	{
		$this->id = $newVal;
		return $this;
	}

	/**
	 * id of photo gallery
	 * @author Artem Sukharev
	 */
	public function getGalleryId()
	{
		return $this->galleryId;
	}

	/**
	 * id of photo gallery
	 * @param newVal
	 * @return Warecorp_Photo_Abstract
	 * @author Artem Sukharev
	 */
	public function setGalleryId($newVal)
	{
		$this->galleryId = $newVal;
		return $this;
	}

	/**
	 * reference of gallery object
	 * @author Artem Sukharev
	 */
	public function getGallery()
	{
		if ( $this->gallery === null ) $this->gallery = Warecorp_Photo_Gallery_Factory::loadById($this->getGalleryId());
		return $this->gallery;
	}

	/**
	 * id of user created this photo
	 * @author Artem Sukharev
	 */
	public function getCreatorId()
	{
		return $this->creatorId;
	}

	/**
	 * id of user created this photo
	 * @param newVal
	 * @return Warecorp_Photo_Abstract
	 * @author Artem Sukharev
	 */
	public function setCreatorId($newVal)
	{
		$this->creatorId = $newVal;
		return $this;
	}

	/**
	 * referance of user object created this photo
	 * @author Artem Sukharev
	 */
	public function getCreator()
	{
		if ( $this->creator === null ) $this->creator = new Warecorp_User('id', $this->getCreatorId());
		return $this->creator;
	}

	/**
	 * title of photo
	 * @author Artem Sukharev
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * title of photo
	 * @param newVal
	 * @return Warecorp_Photo_Abstract
	 * @author Artem Sukharev
	 */
	public function setTitle($newVal)
	{
		$this->title = $newVal;
		return $this;
	}

	/**
	 * description of photo
	 * @author Artem Sukharev
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * description of photo
	 * @param newVal
	 * @return Warecorp_Photo_Abstract
	 * @author Artem Sukharev
	 */
	public function setDescription($newVal)
	{
		$this->description = $newVal;
		return $this;
	}

	/**
	 * additional information of photo
	 * @author Artem Sukharev
	 */
	public function getAdditionalInfo()
	{
		return $this->additionalInfo;
	}

	/**
	 * additional information of photo
	 * @param newVal
	 * @return Warecorp_Photo_Abstract
	 * @author Artem Sukharev
	 */
	public function setAdditionalInfo($newVal)
	{
		$this->additionalInfo = $newVal;
		return $this;
	}

	/**
	 * date of creation
	 * @author Artem Sukharev
	 */
	public function getCreateDate()
	{
		return $this->createDate;
	}

	/**
	 * date of creation
	 * @param newVal
	 * @return Warecorp_Photo_Abstract
	 * @author Artem Sukharev
	 */
	public function setCreateDate($newVal)
	{
		$this->createDate = $newVal;
		return $this;
	}

	/**
	 * get width of original image
	 * @param boolean $prepare
	 * @author Artem Sukharev
	 * @author Aleksei Gusev
     * @return int
	 */
	public function getOriginalWidth($prepare = false)
	{
		if ( $this->or_width === null) {
			$this->calculateOriginalWidthAndHeight( $prepare);
		}
		return $this->or_width;
	}

	/**
	 * get height of original image
	 * @param boolean $prepare
	 * @author Artem Sukharev
	 * @author Aleksei Gusev
     * @return int
	 */
	public function getOriginalHeight($prepare = false)
	{
		if ( $this->or_height === null ) {
			$this->calculateOriginalWidthAndHeight( $prepare);
		}
		return $this->or_height;
	}

    /**
     * This function calculates width and height of the original image.
	 * @param boolean $prepare
	 * @author Aleksei Gusev
     * @return void
     */
	private function calculateOriginalWidthAndHeight( $prepare = false){
		$orImage = $this->getPathOrig();
		if ( file_exists( $orImage)) {
			$size = getimagesize( $orImage);
		} else {
			$size = getimagesize( self::getAppTheme()->images_path
														. $this->getUploadDirname()
														. 'noimage.jpg');
		}
		$this->or_width  = $size[0];
		$this->or_height = $size[1];
		if ( $prepare ) $this->prepareOriginalImage();
	}

	/**
	 * prepare original image size
	 * @return void
	 * @author Artem Sukharev
	 */
	private function prepareOriginalImage()
	{
		if ( $this->or_width > self::$_maxPreviewWidth && $this->or_height > self::$_maxPreviewHeight ) {
			$kw = self::$_maxPreviewWidth / $this->or_width;
			$kh = self::$_maxPreviewHeight / $this->or_height;
			$k = ($kw < $kh) ? $kw : $kh;
			$this->or_width = $this->or_width * $k;
			$this->or_height = $this->or_height * $k;
		} elseif ( $this->or_width > self::$_maxPreviewWidth ) {
			$k = self::$_maxPreviewWidth / $this->or_width;
			$this->or_width = self::$_maxPreviewWidth;
			$this->or_height = $this->or_height * $k;
		} elseif ( $this->or_height > self::$_maxPreviewHeight ) {
			$k = self::$_maxPreviewHeight / $this->or_height;
			$this->or_width = $this->or_width * $k;
			$this->or_height = self::$_maxPreviewHeight;
		}
	}

	/**
	 * При добавлении новой фотки надо инкрементировать размер галереи
	 * При редактировании фотки надо редактировать размер галереи
	 * @author Artem Sukharev
	 */
	public function save()
	{
		/**
		 * update last update date for gallery
		 * only for new photos
		 */
		if ( $this->getId() === null ) {
			$gallery = $this->getGallery();
			$gallery->setUpdateDate(new Zend_Db_Expr('NOW()'));
			$gallery->save();
		}

		parent::save();
	}

	/**
	 * delete record from DB
	 * При удалении фотки из галереи надо декрементировать размер галереи
	 * @author Artem Sukharev
	 */
	public function delete()
	{
		/**
		 * remove photo import history
		 */
		$where = array();
		$where[] = $this->_db->quoteInto('gallery_id = ?', $this->getGalleryId());
		$where[] = $this->_db->quoteInto('photo_id = ?', $this->getId());
		$where = join(' AND ', $where);
		$this->_db->delete(Warecorp_Photo_Gallery_Abstract::$_dbImportTableName, $where);
		/**
		 * remove sharing history
		 */
		$where = $this->_db->quoteInto('photo_id = ?', $this->getId());
		$this->_db->delete(Warecorp_Photo_Gallery_Abstract::$_dbShareHistoryTableName, $where);
		/**
		 * @todo если будут шаринги фоток, то надо будет удалить шары
		 */

		parent::delete();
	}

	/**
	 * return image url with width and height (if exists) else create it and return image url
	 * if width or height not set - return _orig.image
	 *
	 * @return unknown
	 * @author Halauniou Eugene
	 * @see Warecorp_User_Avatar
	 * @author Artem Sukharev
	 */
	public function getImage( $user = null, $useWrapper = true)
	{
		if (! empty($user) && $user->getId() === null) $user = null;

		if ( $useWrapper) {
			if (isset($_SESSION['key_'.$this->getId()]['count'])) {
				$_SESSION['key_'.$this->getId()]['count']++;
			}else{
				$_SESSION['key_'.$this->getId()]['count'] = 1;
			}
			if (! isset( $_SESSION['key_'.$this->getId()]['viewType'])) {
				$_SESSION['key_'.$this->getId()]['viewType'] = "";
			}

            if (! file_exists( $this->getPathOrig())) {
				$_SESSION['key_'.$this->getid()]['viewType'] = 'noimage';
				$this->getOrCreateNoimageThumbnail();
			} else {
				$_SESSION['key_'.$this->getid()]['viewType'] = 'show';
				// generating thumbnail if needed.
				$this->getOrCreateThumbnail();
			}


			$url = GET_IMAGE_WRAPPER_PATH.'?id='.$this->getId()
				.'&width='.$this->getWidth()
				.'&height='.$this->getHeight()
				.'&border='.$this->getBorder()
				.'&proportional='.$this->getProportional();
			return $url;
		}

		return parent::getImage();
	}

	/**
	 * return width of thumbnail by params width, height, border, prop
	 * need to get real width for thumbnail, if "proportional" parameter is used
	 * @author Halauniou
	 * @author Aleksei Gusev
	 * @return int width
	 */
	public function getThumbnailWidth()
	{
		$size = getimagesize( $this->generateThumbnail());
		return $size[0];
	}


	/**
	 * return height of thumbnail by params width, height, border, prop
	 * need to get real height for thumbnail, if "proportional" parameter is used
	 * @author Halauniou
	 * @author Aleksei Gusev
	 * @return int height
	 */
	public function getThumbnailHeight()
	{
		$size = getimagesize( $this->generateThumbnail());
		return $size[1];
	}

	private function generateThumbnail(){
		if ( file_exists( $this->getPathOrig())) {
			$thumbnail_path = $this->getOrCreateThumbnail();
		} else {
			$thumbnail_path = $this->getOrCreateNoimageThumbnail();
		}
		return $thumbnail_path;
	}

	/**
	 * size of photo in bytes
	 * @param string $unit - value from Warecorp_Photo_Enum_SizeUnit
	 * @author Artem Sukharev
	 */
	public function getSize($unit = Warecorp_Photo_Enum_SizeUnit::BYTE)
	{
		if ( !Warecorp_Photo_Enum_SizeUnit::isIn($unit) ) throw new Zend_Exception('Incorrect unit size');

		if (file_exists($this->getPathOrig())){
			$size = filesize($this->getPathOrig());
			switch ( $unit ) {
			case Warecorp_Photo_Enum_SizeUnit::BYTE        : return $size;             break;
			case Warecorp_Photo_Enum_SizeUnit::KBYTE       : return $size/1024;        break;
			case Warecorp_Photo_Enum_SizeUnit::MBYTE       : return $size/1024/1024;   break;
			}
		}
		return 0;
	}

	/**
	 * copy photo in new gallery
	 * @param int|Warecorp_Photo_Gallery_Abstract
	 * @return void
	 * @author Artem Sukharev
	 */
	abstract public function copy($gallery);

	/**
	 * check is photo exists ( exists in gallery)
	 * @param photoId
	 * @param galleryId
	 * @author Artem Sukharev
	 */
	abstract public static function isPhotoExists($photoId, $galleryId = null);

	/**
	 * get PhotoPath ( )
	 * @param photoId
	 * @param galleryId
	 * @author Yury Zolotarsky Sukharev
	 */
	abstract public function getPhotoPath();

	/**
	 * delete all thumbnails for this photo, using mask name_x*.*
	 * @return true;
	 */

	public function deleteThumbnails(){
		$photos = glob($this->getPath().'_x*.*');
		if ( sizeof($photos) != 0 ) {
			foreach ( $photos as $photo ) unlink($photo);
		}

		return true;
	}

	/*
	 +-----------------------------------
	 |
	 | iSearchFields Interface
	 |
	 +-----------------------------------
	*/

	/**
	 * return object
	 * @return void object
	 */
	public function entityObject()
	{
		return $this;
	}

	/**
	 * return object id
	 * @return int
	 */
	public function entityObjectId()
	{
		return $this->getId();
	}

	/**
	 * return object type. possible values: simple, family, committies and blank string or null
	 * @return string
	 */
	public function entityObjectType()
	{
		return "";
	}

	/**
	 * return owner type
	 * possible values: group, user
	 * @return string
	 */
	public function entityOwnerType()
	{
		return $this->getGallery()->getOwnerType();
	}

	/**
	 * return title for entity (like group name, username, photo or gallery title)
	 * @return string
	 */
	public function entityTitle()
	{
		return $this->getTitle();
	}

	/**
	 * return headline for entity (like group headline, members first and last name, photo or gallery title,etc).
	 * for entities which didn't have headline will be returned entityTitle
	 * @return string
	 */
	public function entityHeadline()
	{
		return $this->getTitle();;
	}

	/**
	 * return description for entity (group description, user intro, gallery or photo description, etc.).
	 * for entities which didn't have headline will be returned entityTitle
	 * @return string
	 */
	public function entityDescription()
	{
		return $this->getDescription();
	}

	/**
	 * return username of owner
	 * @return string
	 */
	public function entityAuthor()
	{
		return $this->getCreator()->getLogin();
	}

	/**
	 * return user_id of entity owner
	 * @return string
	 */
	public function entityAuthorId()
	{
		return $this->getCreatorId();
	}

	/**
	 * return picture URL (avatar, group picture, trumbnails, etc.)
	 * @return int
	 */
	public function entityPicture()
	{
		return $this;
	}

	/**
	 * return creation date for all elements
	 * @return string
	 */
	public function entityCreationDate()
	{
		return $this->getCreateDate();
	}

	/**
	 * return update date for all elements
	 * @return string
	 */
	public function entityUpdateDate()
	{
		return $this->getCreateDate();
	}

	/**
	 * items count (members, posts, child groups, etc.)
	 * @return int
	 */
	public function entityItemsCount()
	{
		return 1;
	}

	/**
	 * get category for entity (event type, list type, group category, etc)
	 * possible values: string
	 * @return int
	 */
	public function entityCategory()
	{
		return "";
	}

	/**
	 * get category_id for entity (event type, list type, group category, etc)
	 * possible values: int , null
	 * @return int
	 */
	public function entityCategoryId()
	{
		return null;
	}

	/**
	 * get country for entity (users, groups, events)
	 * possible values: string
	 * @return int
	 */
	public function entityCountry()
	{
		return "";
	}

	/**
	 * get country_int for entity (users, groups, events)
	 * possible values: int, null
	 * @return int
	 */
	public function entityCountryId()
	{
		return null;
	}


	/**
	 * get city for entity (users, groups, events)
	 * possible values: string
	 * @return int
	 */
	public function entityCity()
	{
		return "";
	}

	/**
	 * get city_id for entity (users, groups, events)
	 * possible values: int, null
	 * @return int
	 */
	public function entityCityId()
	{
		return null;
	}

	/**
	 * get zip for entity (users, groups, events)
	 * possible values: int, null
	 * @return int
	 */
	public function entityZIP()
	{
		return "";
	}

	/**
	 * get state for entity (users, groups, events)
	 * possible values: int, null
	 * @return int
	 */
	public function entityState()
	{
		return "";
	}

	/**
	 * get state_id for entity (users, groups, events)
	 * possible values: int, null
	 * @return int
	 */
	public function entityStateId()
	{
		return null;
	}

	/**
	 * path to video(video galleries)
	 * possible values: string
	 * @return int
	 */
	public function entityVideo()
	{
		return "";
	}

	/**
	 * comments count for entity
	 * possible values: int
	 * @return int
	 */
	public function entityCommentsCount()
	{
		return 0;
	}

	public function entityURL()
	{
		return $this->getCreator()->getGlobalPath('galleryView')."id/".$this->getId()."/";
	}

}
