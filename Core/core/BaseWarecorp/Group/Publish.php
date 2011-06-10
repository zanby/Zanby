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


class BaseWarecorp_Group_Publish extends Warecorp_Data_Entity
{
    public $id;
    public $groupId;
    public $ftpServer;
    public $ftpMode;
    public $ftpUsername;
    public $ftpPassword;
    public $ftpFolder;
    public $desturl;
    public $filename;
    public $lastPublish;

	public function __construct($key = null, $val = null)
	{
	    parent::__construct('zanby_groups__publish_settings');

	    $this->addField('id');
	    $this->addField('group_id', 'groupId');
	    $this->addField('ftp_server', 'ftpServer');
	    $this->addField('ftp_mode', 'ftpMode');
	    $this->addField('ftp_username', 'ftpUsername');
	    $this->addField('ftp_password', 'ftpPassword');
	    $this->addField('ftp_folder', 'ftpFolder');
	    $this->addField('desturl1', 'desturl');
	    $this->addField('filename', 'filename');
	    $this->addField('last_publish', "lastPublish");
	    if ($key !== null) {
	    	$this->pkColName = $key;
	        $this->loadByPk($val);
	    }
	    $this->pkColName = "id";
	}
	
	public function setGroupId($groupId)
	{
		$this->groupId = $groupId;
		return $this;
	}
	
	public function getGroupId()
	{		
		return $this->groupId;
	}	
	
	public function setFtpServer($ftpServer)
	{
		$this->ftpServer = $ftpServer;
		return $this;
	}
		
	public function getFtpServer()
	{
		return $this->ftpServer;
	}		
	
	public function setFtpMode($ftpMode)
	{
		$this->ftpMode = $ftpMode;
		return $this;
	}	
	
	public function getFtpMode()
	{
		return $this->ftpMode;
	}	

	public function setFtpUsername($ftpUsername)
	{
		$this->ftpUsername = $ftpUsername;
		return $this;
	}	
	
	public function getFtpUsername()
	{
		return $this->ftpUsername;
	}	
		
	public function setFtpPassword($ftpPassword)
	{
		$this->ftpPassword = $ftpPassword;
		return $this;
	}	
	
	public function getFtpPassword()
	{
		return $this->ftpPassword;
	}		

	public function setFtpFolder($ftpFolder)
	{
		$this->ftpFolder = $ftpFolder;
		return $this;
	}	
	
	public function getFtpFolder()
	{
		return $this->ftpFolder;
	}		

	public function setDesturl($desturl)
	{
		$this->desturl = $desturl;
		return $this;
	}	
	
	public function getDesturl()
	{
		return $this->desturl;
	}		

	public function setFilename($filename)
	{
		$this->filename = $filename;
		return $this;
	}	
	
	public function getFilename()
	{
		return $this->filename;
	}	
	
	public function setLastPublish($lastPublish)
	{
		$this->lastPublish = $lastPublish;
		return $this;
	}
	
	public function getLastPublish()
	{
		return $this->lastPublish;
	}	

}
