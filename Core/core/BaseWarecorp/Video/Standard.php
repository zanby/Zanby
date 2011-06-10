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
class BaseWarecorp_Video_Standard extends Warecorp_Video_Abstract
{
	public static function isVideoExists($videoId, $galleryId = null)
	{
		$db = Zend_Registry::get('DB');
		$query = $db->select();
		$query->from(self::$_dbTableName, new Zend_Db_Expr('COUNT(*)'));
		$query->where('id = ?', $videoId);
		if ( $galleryId != null ) $query->where('gallery_id = ?', $galleryId);
        return (boolean) $db->fetchOne($query);
	}

    public function copy($gallery)
    {
    	if ( !($gallery instanceof Warecorp_Video_Gallery_Abstract) ) {
    	   $gallery = Warecorp_Video_Gallery_Factory::loadById($gallery);
    	   if ( $gallery->getId() === null ) return false;
    	}
        
    	$new_video = Warecorp_Video_Factory::createByOwner($gallery->getOwner());
        $new_video->setGalleryId($gallery->getId());
        $new_video->setCreatorId($gallery->getCreatorId());
        $new_video->setCreateDate($this->getCreateDate());
        $new_video->setFilename($this->getFilename());
        $new_video->setSize($this->getSize());
        $new_video->setTitle($this->getTitle());
        $new_video->setDescription($this->getDescription());
        $new_video->setAdditionalInfo($this->getAdditionalInfo());
        if ($this->getCustomSrc()) $new_video->setCustomSrc($this->getCustomSrc());
        if ($this->getCustomSrcImg() && $this->getSource() != "nonvideo") $new_video->setCustomSrcImg($this->getCustomSrcImg());
        $new_video->setSource($this->getSource());
        $new_video->setLength($this->getLength());
        $new_video->setSourceExtension($this->getSourceExtension());
        $new_video->setSourceContentType($this->getSourceContentType());
        $new_video->save();

        $new_video->addTags($this->getVideoTags(), "user");

        if (!$this->getCustomSrc()) {
			if ($this->getSource() != "nonvideo") {
			    if ( file_exists($this->getPath().'_orig.jpg') ) copy($this->getPath().'_orig.jpg', $new_video->getPath().'_orig.jpg');

				$source = $this->getFileCommonPart().'_orig.flv';
	            $s3 = new Warecorp_S3(Warecorp_S3::$__accessKey, Warecorp_S3::$__secretKey);

				$s3->copyObject(S3_BUCKET, $source, null, $new_video->getFileCommonPart().'_orig.flv', Warecorp_S3::ACL_PUBLIC_READ);

	            if ($this->isExistRawVideo()){
	                $orig = $this->getFileCommonPart().'_orig.'.$this->getSourceExtension();
	                $new_orig = $new_video->getFileCommonPart().'_orig.'.$this->getSourceExtension();
					$s3->copyObject(S3_BUCKET, 'sources/'.$orig, null, 'sources/'.$new_orig, Warecorp_S3::ACL_PUBLIC_READ);
	            }
            }else{
				$fileName = basename($this->getCustomSrcImg());
				$r = time();
				copy(UPLOAD_BASE_PATH.'/upload/videogallery_videos/'.$fileName, $new_video->getPath().$r.'_orig.jpg');
				$new_video->setCustomSrcImg($new_video->getCover()->getSrc().$r."_orig.jpg");
				$new_video->save();
			}
        } 
      
        return true;
    }
    
    public function getVideoPath()
    {
    	if ($this->getGallery()->getOwnerType() == 'user') { 
    		return $this->getGallery()->getOwner()->getUserPath("videogalleryView").'id/'.$this->getId().'/';
    	}else {
            return $this->getGallery()->getOwner()->getGroupPath("videogalleryView").'id/'.$this->getId().'/';
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
    		$links .= '<a href="'.$user->getUserPath('videossearch').'preset/tag/id/'.$tag->id.'/"> '.$display_name.'</a> ';
    		if (strlen($s) >= $length && $length != false) {
    			$s = substr($s, 0, $length); 
    			return $links;
    		}    		
    	}
    	return $links;
    }
    
    public function getVideoTags()
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
