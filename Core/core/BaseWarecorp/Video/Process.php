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
class BaseWarecorp_Video_Process extends Warecorp_Data_Entity
{
    public static $_dbTableName = 'zanby_videogalleries__processes';

    private $startTime;
    private $id;
    private $videoId;
    private $file;
    private $status;
    private $video;    
    
    public static $age_requirement = 16;

    function __construct(&$video)
    {
        parent::__construct(self::$_dbTableName);        
        $this->addField('id', 'id');
        $this->addField('video_id', 'videoId');
        $this->addField('start_time', 'startTime');
        $this->addField('file');
        $this->addField('status');
        
        if (is_numeric($video)) {
            $this->pkColName = 'video_id';
            $this->loadByPk($video);
            $this->video = Warecorp_Video_Factory::loadById($video);
        } else if ($video instanceof Warecorp_Video_Abstract) {        
            $this->video = &$video;
            $this->videoId = $this->video->getId();
            $fileInfo = pathinfo($this->video->getFile('name'));
            $newPath = Warecorp_Video_Process::getProcessingDirectory().'/'.$this->video->getFileCommonPart()."_orig.".$fileInfo['extension'];
            if (copy($this->video->getFile('tmp_name'), $newPath)) {
                $this->file = $newPath;
                $this->status = Warecorp_Video_Enum_ProcessStatus::START;
                $this->startTime = new Zend_Db_Expr('NOW()');
            }        
        }
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
    
    public function setVideoId($newVal)
    {
        $this->videoId = $newVal;
        return $this; 
    }
    
    public function getVideoId()
    {
        return $this->videoId;    
    }
    
    public function setFile($newVal)
    {
        $this->file = $newVal;
        return $this; 
    }
    
    public function getFile()
    {
        return $this->file;    
    }
    
    public function setStartTime($newVal)
    {
        $this->startTime = $newVal;
        return $this; 
    }
    
    public function getStartTime()
    {
        return $this->startTime;    
    }
    
    public function setStatus($newVal)
    {
        $this->status = $newVal;
        return $this; 
    }
    
    public function getStatus()
    {
        return $this->status;    
    }    
        
    public function processVideo()
    {
        $this->status = Warecorp_Video_Enum_ProcessStatus::PROCESSING;
        $this->save();

        $path = $this->video->getPath()."_orig.flv"; 
        $coverPath = $this->video->getPath()."_orig.jpg";
        Warecorp_Video_FFMpeg::makeConversionToFLV($this->file, $path);
        Warecorp_Video_FFMpeg::getFrame($path, $coverPath);
        $this->video->setLength(Warecorp_Video_FFMpeg::getLength($path));
        $this->video->save();
        
        $this->status = Warecorp_Video_Enum_ProcessStatus::UPLOADING;
        $this->save();
        
		Warecorp_S3::putFile($path, null, 'public-read', 'video/x-flv');
        
        if (STORE_ORIGINAL_VIDEO) {
            Warecorp_S3::putFile($this->file, 'sources', 'public-read', $this->video->getSourceContentType());
        }
        
        unlink($path);
        
        $this->status = Warecorp_Video_Enum_ProcessStatus::COMPLETED;
        $this->video->getGallery()->setIsCreated(1)->save();
        $this->save();        
    }
    
    public function delete()
    {
        unlink($this->file);
        parent::delete();      
    }
    
    public static function getProcesses()
    {
        $db = Zend_Registry::get('DB');
        $query = $db->select();
        $query->from(array('zvp' => self::$_dbTableName), 'zvp.video_id');
        $query->order('zvp.start_time asc');
        $query->where('zvp.status = (?)', Warecorp_Video_Enum_ProcessStatus::START);
        $videoList = $db->fetchCol($query);
        if (empty($videoList)) return array();
        foreach($videoList as &$video) {
            $video = new Warecorp_Video_Process($video);    
        }
        return $videoList;
    }
    
    public static function getProcessingDirectory()
    {
        return UPLOAD_BASE_PATH.'/upload/videogallery_videos/processing';   
    }
}
