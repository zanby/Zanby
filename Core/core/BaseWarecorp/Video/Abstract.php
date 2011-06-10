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
 * @package Warecorp_Мшвущ
 * @author Yury Zolotarsky
 * @version 1.0
 */
abstract class BaseWarecorp_Video_Abstract extends Warecorp_Data_Entity implements Warecorp_Global_iSearchFields
{
    public static $_dbTableName = 'zanby_videogalleries__videos';
    public static $_dbUpDownTableName = 'zanby_videogalleries__updowns';
    public static $_dbViewsTableName = 'zanby_videogalleries__videos_views';

    private $id;
    private $galleryId;
    private $gallery;
    private $creatorId;
    private $creator;
    private $title;
    private $description;
    private $additionalInfo;
    private $createDate;
    private $src;
    private $path;
    private $coverPath;
    private $customSrc;
    private $customSrcImg;
    private $source;
    private $filename;
    private $size;
    private $length;
    private $file = null;
    private $sourceContentType;
    private $sourceExtension;
    
    
    public static $age_requirement = 16;

    function __construct($videoId = null)
    {
        parent::__construct(self::$_dbTableName);
        $this->addField('id');
        $this->addField('gallery_id', 'galleryId');
        $this->addField('creator_id', 'creatorId');
        $this->addField('title');
        $this->addField('description');
        $this->addField('additional_info', 'additionalInfo');
        $this->addField('creation_date', 'createDate');
        $this->addField('custom_src', 'customSrc');
        $this->addField('custom_srcimg', 'customSrcImg');
        $this->addField('source', 'source');
        $this->addField('filename', 'filename');
        $this->addField('size', 'size');
        $this->addField('length', 'length');
        $this->addField('source_type', 'sourceContentType');
        $this->addField('source_ext', 'sourceExtension');

        if ( $videoId != null ) {
            $this->pkColName = 'id';
            $this->loadByPk($videoId);
        }
        //if (!empty($this->src)) $this->customSrc = true;
        //if ( $this->id !== null ) $this->src = $this->getSrc();//UPLOAD_BASE_URL.'/upload/videogallery_videos/'.md5($this->id . 'zbvideo');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($newVal)
    {
        $this->id = $newVal;
        return $this;
    }

    public function setSourceContentType($val)
    {
        $this->sourceContentType = $val;
        return $this;
    }
    
    public function getSourceContentType()
    {
        return $this->sourceContentType;
    }
    
    public function setSourceExtension($val)
    {
        $this->sourceExtension = $val;
        return $this;
    }
    
    public function getSourceExtension()
    {
        return $this->sourceExtension;
    }    
    
    public function setSource($val)
    {
        if (Warecorp_Video_Enum_VideoSource::isIn($val)) {
            $this->source = $val;            
        } else {
            $this->source = Warecorp_Video_Enum_VideoSource::OWN;
        }       
    }
    
    public function getSource()
    {
        if (!empty($this->source)) return $this->source;
        return Warecorp_Video_Enum_VideoSource::OWN;
    }    
    
    public function getGalleryId()
    {
        return $this->galleryId;
    }

    public function setFile($val)
    {
        if (!is_array($val)) return $this;
        $this->file = $val;
        return $this;   
    }
    
    public function getFile($attr = null)
    {        
        if (empty($attr)) return $this->file;
        return $this->file[$attr];
    }    
    
    public function setGalleryId($newVal)
    {
        $this->galleryId = $newVal;
        return $this;
    }

    public function getGallery()
    {
        if ( $this->gallery === null ) $this->gallery = Warecorp_Video_Gallery_Factory::loadById($this->getGalleryId());
        return $this->gallery;
    }

    public function getCreatorId()
    {
        return $this->creatorId;
    }

    public function setCreatorId($newVal)
    {
        $this->creatorId = $newVal;
        return $this;
    }

    public function getCreator()
    {
        if ( $this->creator === null ) $this->creator = new Warecorp_User('id', $this->getCreatorId());
        return $this->creator;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($newVal)
    {
        $this->title = $newVal;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($newVal)
    {
        $this->description = $newVal;
        return $this;
    }

    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo($newVal)
    {
        $this->additionalInfo = $newVal;
        return $this;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function setCreateDate($newVal)
    {
        $this->createDate = $newVal;
        return $this;
    }

    public function getViewerSrc()
    {
        if ( !empty($this->customSrc)) {
            return $this->getSrc();               
        } else {
            return UPLOAD_BASE_URL.'/UptakeVideoPlayer/mediaplayer.swf';   
        }           
    }
    
    public function setCustomSrcImg($src)
    {
        $this->customSrcImg = $src;
        return $this;        
    }
    
    public function getCustomSrcImg()
    {
        return $this->customSrcImg;
    }

    public function setCustomSrc($src)
    {
        $this->customSrc = $src;
        return $this;        
    }
    
    public function getCustomSrc()
    {
        return $this->customSrc;
    }    
        
    public function getSrc()
    {       
        if ( empty($this->customSrc)) 
            return 'http://'.S3_BUCKET.'.s3.amazonaws.com/'.$this->getFileCommonPart();
        else 
            return $this->getCustomSrc();
    }
    
    public function isExistRawVideo()
    {
        if (empty($this->customSrc)) {
            $s3 = new Warecorp_S3(Warecorp_S3::$__accessKey, Warecorp_S3::$__secretKey);
            $orig = $this->getFileCommonPart().'_orig.'.$this->getSourceExtension();
            $contents = $s3->getObjectInfo(null, 'sources/'.$orig);
			return is_array($contents);
        }else{
            return false;
        }
    }
    
    public function deleteRawVideo()
    {
        if ( empty($this->customSrc) && $this->getSource() !== 'nonvideo') {
            $s3 = new Warecorp_S3(Warecorp_S3::$__accessKey, Warecorp_S3::$__secretKey);
            $s3->deleteObject(null, 'sources/'.$this->getFileCommonPart().'_orig.'.$this->getSourceExtension());
        }        
    }
    
    public function getRawVideoSrc()
    {
        if (empty($this->customSrc)) {
            return 'http://'.S3_BUCKET.'.s3.amazonaws.com/sources/'.$this->getFileCommonPart().'_orig.'.$this->getSourceExtension();
        }else{
            return '#null';
        }
    }
    
    public function getFileCommonPart()
    {
        return md5($this->id . 'zbvideo');
    }
    
    public function getPath()
    {
        if ( $this->path === null ) $this->path = Warecorp_Video_Abstract::getWorkPath().'/'.$this->getFileCommonPart();
        return $this->path;
    }
    
    public function save()
    {
        if ( $this->getId() === null ) {
            $gallery = $this->getGallery();
            $gallery->setUpdateDate(new Zend_Db_Expr('NOW()'));
            $gallery->save();
        }
        
        if ($this->getFile() === null || !file_exists($this->getFile('tmp_name')) || $this->getId()) {
            parent::save(); 
            return;
        }
        $this->setSourceContentType($this->getFile('type'));
        $filenameInfo = pathinfo(basename($this->getFile('name')));        
        $this->setSourceExtension($filenameInfo['extension']);
        parent::save();
        $video_process = $this->addToProcessing();
        if (!USE_VIDEO_SUSPENDED_PROCESSING) {
            $video_process->processVideo();
            $video_process->delete();
        }
    }
    
    protected function addToProcessing()
    {
        $video_process = new Warecorp_Video_Process($this);
        $video_process->save();       
        return $video_process;
    }

    public function delete()
    {
        $videos = glob($this->getPath().'*.*');
        if ( sizeof($videos) != 0 ) {
            foreach ( $videos as $video ) unlink($video);
        }
        if ( empty($this->customSrc) && $this->getSource() != 'nonvideo') {
            $s3 = new Warecorp_S3(Warecorp_S3::$__accessKey, Warecorp_S3::$__secretKey);
            $s3->deleteObject(null, $this->getFileCommonPart().'_orig.flv');
            $this->deleteRawVideo();
        }
        $where = array();
        $where[] = $this->_db->quoteInto('gallery_id = ?', $this->getGalleryId());
        $where[] = $this->_db->quoteInto('video_id = ?', $this->getId());
        $where = join(' AND ', $where);
        $this->_db->delete(Warecorp_Video_Gallery_Abstract::$_dbImportTableName, $where);

        $where = array();        
        $where[] = $this->_db->quoteInto('video_id = ?', $this->getId());
        $this->_db->delete(Warecorp_Video_Abstract::$_dbUpDownTableName, $where); 
        
        $where = array();        
        $where[] = $this->_db->quoteInto('video_id = ?', $this->getId());
        $this->_db->delete(Warecorp_Video_Abstract::$_dbViewsTableName, $where);
        
        //$where = $this->_db->quoteInto('video_id = ?', $this->getId());
        //$this->_db->delete(Warecorp_Video_Gallery_Abstract::$_dbShareHistoryTableName, $where);

        parent::delete();
    }

    public function getCover($user = null)
    {
        return new Warecorp_Video_Cover($this);
    }

    public function setFilename($newVal)
    {
        $this->filename = $newVal;
        return $this;
    }
    
    public function getFilename()
    {
        return $this->filename;   
    }
    
    public function setLength($newVal)
    {
        $this->length = $newVal;
        return $this;
    }
    
    public function getLength()
    {
        return $this->length;
    }
    
    public function getLengthAsString()
    {
        if (is_numeric($this->length)) {
            $hours = floor($this->length / 60);
            $minutes = $this->length - $hours * 60;
            return sprintf("%d:%02d", $hours, $minutes);
        }
        return "0:00";
    }

    public function setSize($newVal)
    {
        if (is_numeric($newVal))
            $this->size = $newVal;
        else $this->size = null;
    }
    
    public function getSizeAsString()
    {
        $size = $this->getSize();
        if (!$size) return '0b';
        return Warecorp_Debug::format_memory_usage($size);        
    }
    
    public function getSize()
    {
        return $this->size;
    }
    
    public function getViewSrc()
    {
        /*if ( !empty($this->customSrc)) {
            return $this->getViewerSrc().'?video='.$this->getSrc().'&image='.$this->getCover()->getSrc().'_orig.jpg'.'&title='.$this->getTitle();               
        } else {
            return $this->getViewerSrc().'?video='.$this->getSrc().'_orig.flv'.'&image='.$this->getCover()->getSrc().'_orig.jpg'.'&title='.$this->getTitle();   
        }*/
        
        if ( !empty($this->customSrc)) {
            if ($this->getSource() == Warecorp_Video_Enum_VideoSource::BLIPTV){
                preg_match('~file=(http://(?:www\.|(?:[a-z0-9]*?)\.)?blip\.tv/(?:file/|rss/flash/)([0-9]{1,10}))~', $this->customSrc, $array);
                if (isset($array[1])) return $array[1]; else return $this->getViewerSrc();
            } else {
                return '';               
            }
        } else {
            return $this->getSrc().'_orig.flv';   
        }
    }

    abstract public function copy($gallery);


    abstract public static function isVideoExists($videoId, $galleryId = null);

    abstract public function getVideoPath();

    public function deleteThumbnails(){
        $videos = glob($this->getPath().'_x*.*');
        if ( sizeof($videos) != 0 ) {
            foreach ( $videos as $video ) unlink($video);
        }

        return true;
    }
    
    public function setUpDownRank($user, $value = 0)
    {
        if ($user instanceof Warecorp_User) $user_id = $user->getId(); else $user_id = floor($user);
        
        $query = "replace into ".Warecorp_Video_Abstract::$_dbUpDownTableName." (user_id, video_id, value) values (".$user_id.", ".$this->id.", ".$value.")";
        return $this->_db->query($query);
    }
    
    public function getUpDownRank()
    {
        $query = $this->_db->select()
                ->from(Warecorp_Video_Abstract::$_dbUpDownTableName, array('rank' => new Zend_Db_Expr('sum(value)')))
                ->where('video_id = ?', $this->id);        
        $result = $this->_db->fetchOne($query);        
        return ($result == false)?0:$result;
    }
    
    public function getUpDownRankByUser($user)
    {
        if ($user instanceof Warecorp_User) $user_id = $user->getId(); else $user_id = floor($user);
        $query = $this->_db->select()
                ->from(Warecorp_Video_Abstract::$_dbUpDownTableName, array('rank' => 'value'))
                ->where('user_id = ?', (NULL === $user_id) ? new Zend_Db_Expr('NULL') : $user_id, 'INTEGER')
                ->where('video_id = ?', $this->id);
        $result = $this->_db->fetchOne($query);        
        return ($result == false)?0:$result;
    }    
    
    public function addView($user) 
    {
        if ($user instanceof Warecorp_User) $user_id = $user->getId(); else $user_id = floor($user);
        
        $query = "replace into ".Warecorp_Video_Abstract::$_dbViewsTableName." (video_id, user_id) values (".$this->getId().", ".$user_id.")";
        return $this->_db->query($query);            
    }
    
    public function getViewsCount() 
    {
        $query = $this->_db->select()
                ->from(Warecorp_Video_Abstract::$_dbViewsTableName, new Zend_Db_Expr('count(*)'))
                ->where('video_id = ?', $this->id);
        $result = $this->_db->fetchOne($query);        
        return ($result == false)?0:$result;
    }
    
    public static function getEmbedData(&$source, &$customSrc, &$customSrcImg)
    {
        switch ($source) {
            case 1:
                    $pattern['YouTube'] = '~http://(?:(?:www|au|br|ca|es|fr|de|hk|ie|it|jp|mx|nl|nz|pl|ru|tw|uk)\.)?youtube\.com(?:[^"]*?)?(?:\&|\/|\?|\;|\%3F|\%2F)(?:watch|video_id=|v(?:/|=|\%3D|\%2F))([0-9a-z-_]{11})~i';
                    $message['YouTube'] = rawurldecode($customSrc);
                    preg_match_all($pattern['YouTube'], $message['YouTube'], $out['YouTube'], PREG_PATTERN_ORDER);
                    if (empty($out['YouTube'][0][0])) {
                        return "Not YouTube object";
                    } else {
                        $customSrc = $out['YouTube'][0][0];     
                        preg_match_all('~[0-9a-z-_]{11}~i', $customSrc, $outImg, PREG_PATTERN_ORDER);
                        if (!empty($outImg[0][0]))
                            $customSrcImg = (!empty($customSrcImg))?$customSrcImg:'http://i1.ytimg.com/vi/'.$outImg[0][0].'/default.jpg';
                        $source = Warecorp_Video_Enum_VideoSource::YOUTUBE;
                    }     
                    $customSrc = str_replace("watch?v=", "v/", $customSrc);                       
                    break;
            case 2:
                    $pattern['blipTv']  = '~http://(?:www\.|(?:[a-z0-9]*?)\.)?blip\.tv/(?:file/|rss/flash/)([0-9]{1,10})~i';  
                    $message['blipTv'] = rawurldecode($customSrc);
                    preg_match_all($pattern['blipTv'], $message['blipTv'], $out['blipTv'], PREG_PATTERN_ORDER);
                    if (empty($out['blipTv'][0][0])) {
                        $error = "Not Blip.tv object";
                    } else {
                        $customSrc = 'http://blip.tv/scripts/flash/showplayer.swf?file='.$out['blipTv'][0][0];
                        $customSrc = $out['blipTv'][0][0];
                        $source = Warecorp_Video_Enum_VideoSource::BLIPTV;
                        return;
                    }
                    //$pattern['blipTv']  = '~http://blip\.tv/play/([0-9a-z-_]+)~i';
                    $pattern['blipTv']  = '~http://blip\.tv/play/[0-9a-z-_\+]+~i';
                    preg_match_all($pattern['blipTv'], $message['blipTv'], $out['blipTv'], PREG_PATTERN_ORDER);
                    if (empty($out['blipTv'][0][0])) {
                        $error = "Not Blip.tv object";
                    } else {                        
                        $customSrc = $out['blipTv'][0][0];
                        $source = Warecorp_Video_Enum_VideoSource::BLIPTV;
                        return;
                    }       
                    return $error;             
                    break;
            default:
                    return "Select Video Source";
        }    
    }
    
    public static function getSourcesPath()
    {
        return UPLOAD_BASE_PATH.'/upload/videogallery_videos/sources';
    }
        
    public static function getWorkPath()
    {
        return UPLOAD_BASE_PATH.'/upload/videogallery_videos';
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
        return $this->getCover();
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
        return $this->getViewerSrc();
    }
    
    /**
    * comments count for entity
    * possible values: int
    * @return int
    */
    public function entityCommentsCount()
    {
        return null;
    }  
    
    public function entityURL()
    {
        return $this->getCreator()->getGlobalPath('videogalleryView')."id/".$this->getId()."/";
    }
    

}
