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
class BaseWarecorp_Photo_Standard extends Warecorp_Photo_Abstract
{
	/**
	 * check is photo exists ( exists in gallery)
	 * @param photoId
	 * @param galleryId
	 * @author Artem Sukharev
	 */	
	public static function isPhotoExists($photoId, $galleryId = null)
	{
		$db = Zend_Registry::get('DB');
		$query = $db->select();
		$query->from(self::$_dbTableName, new Zend_Db_Expr('COUNT(*)'));
		$query->where('id = ?', $photoId);
		if ( $galleryId != null ) $query->where('gallery_id = ?', $galleryId);
		return (boolean) $db->fetchOne($query);
	}

	/**
	 * copy photo in new gallery
	 * @param int|Warecorp_Photo_Gallery_Abstract
	 * @return void
	 * @author Artem Sukharev
	 */
	public function copy($gallery)
	{
		if ( !($gallery instanceof Warecorp_Photo_Gallery_Abstract) ) {
			$gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery);
			if ( $gallery->getId() === null ) return false;
		}

		$new_photo = Warecorp_Photo_Factory::createByOwner($gallery->getOwner());
		$new_photo->setGalleryId($gallery->getId());
		$new_photo->setCreatorId($gallery->getCreatorId());
		$new_photo->setCreateDate($this->getCreateDate());
		$new_photo->setTitle($this->getTitle());
		$new_photo->setDescription($this->getDescription());
		$new_photo->setAdditionalInfo($this->getAdditionalInfo());
		$new_photo->save();
		if (file_exists($this->getPathOrig()))
			copy($this->getPathOrig(), $new_photo->getPathOrig());

		$new_photo->addTags($this->getPhotoTags(), "user");

		return true;
	}
    
	public function getPhotoPath()
	{
		if ($this->getGallery()->getOwnerType() == 'user') 
			return $this->getGallery()->getOwner()->getUserPath("galleryView").'id/'.$this->getId().'/';
		else { //group
			return $this->getGallery()->getOwner()->getGroupPath("galleryView").'id/'.$this->getId().'/';		
		}
	}
    
	public function getLinksForTags($length, $user, $trunc = false)
	{
		$tagsList = $this->getTagsList();
		$s = "";$links = "";
		if (empty($tagsList)) return '<br />';
		foreach($tagsList as $tag) {
			$t = ($trunc === false)?$tag->name:(substr($tag->name, 0, $trunc).((strlen($tag->name) > ($trunc - 2))?'...':''));
			if (!is_bool($length))
				$display_name = (strlen($s.' '.$t) >= $length)?substr($t, 0, $length - strlen($s.' ')):$t;
			else $display_name = $t;
			$s .= ' '.$t;    		
			$links .= '<a href="'.$user->getUserPath('photossearch').'preset/tag/id/'.$tag->id.'/"> '.$display_name.'</a> ';
			if (strlen($s) >= $length && $length != false) {
				$s = substr($s, 0, $length); 
				return $links;
			}
		}
		return $links;
	}
    
	public function getPhotoTags()
	{
		$tags = $this->getTagsList();
		$tags_str = array();
		if ( sizeof($tags) != 0 ) {
			foreach ( $tags as $tag ) $tags_str[] = $tag->getPreparedTagName();
		}
		$tags_str = join(' ', $tags_str);
		return $tags_str;
	}
}
