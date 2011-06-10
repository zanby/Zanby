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
 * @package Warecorp_User
 * Sergey Vaninsky
  */

/**
 * Base class for Import Users
 *
 */


class BaseWarecorp_Import_Groups
{
	private $adminname;
	private $upload_dir;
	private $_filename;
	
	private $_sym;
	private $_infile;
	private $_rejfile;
	private $_errfile;
	private $_warnfile;
	private $_resfile;
	
	private $_error = false;
    private $allstr;
    private $allerr;
    private $allwarn;
    private $warn_row = array();
    private $onestr;
    private $loadstr;
    private $n = 0;
    private $rec_warn = 0;
    private $rec_err = 0;
    
    public 	$hdnames = array( 'group_name'=>true,
						'host_login'=>true,
                        'group_email_prefix'=>true, 
						'category'=>true,
						'members_name'=>true,
						'headline'=>true,
						'tags'=>true,
    					'description'=>true,
    					'join'=>true,
    					'country'=>true,
						'state'=>true,
						'city'=>true,
						'country_id'=>true,
						'city_id'=>true,
                        'category_id'=>true,
                        'is_private'=>true,
    					'zip'=>true);
    
    public $hd = array();
	public $hdn = array();
	public $origin_field = array();

	//
	// Contructor
	//	
	public function __construct() {
		$this->allerr = array();
		$this->allstr = array();
        $this->allwarn = array();
		$this->setUploadDir('/upload/import/'); 
		
	}

	public function getHdn()
	{
		return $this->hdn;
	}
/*
	public function freeLogin()
	{
		$login=$this->getStrField('login');
		$loginbase=$login;
		$num = 0;
		while ($this->isLoginExist($login)) {
			$num++;
			$login=$loginbase.$num;
		}
		$this->setStrField('login',$login);
		return $login;
	}
 */
    public function isGroupExist($value)
    {
        // search in DB
        $db = Zend_Registry::get("DB");
        $select = $db->select()
            ->from(array('g' => 'zanby_groups__items'), 'g.name')
            ->where('g.name = ?',$value);
        $res = $db->fetchOne($select);
        if($res === false) {
            // search in Array
            foreach($this->allstr as $key=>$item) {
                if(!isset($item['name']) || $key==$this->n){
                    continue;
                }elseif($item['name']==$value) {
                    $res = true;
                    break;    
                }
            }
            return $res; 
        } else {
            return true;
        }
    }
	
	public function isLoginExist($value)
	{
		// search in DB
		$db = Zend_Registry::get("DB");
	    $select = $db->select()
	    	->from(array('u' => 'zanby_users__accounts'), 'u.login')
	    	->where('u.login = ?',$value);
	    $res = $db->fetchOne($select);
		if($res === false) {
            return false;
		} else {
			return true;
		}
	}
//  isEmailPrefixExist    
    public function isEmailPrefixExist($value)
    {
        // search in DB
        $db = Zend_Registry::get("DB");
        $select = $db->select()
            ->from(array('g' => 'zanby_groups__items'), 'g.name')
            ->where('g.name = ?',$value);
        $res = $db->fetchOne($select);
        if($res === false) {
            return false;
        } else {
            return true;
        }
    }
// getCategoryId
    public function getCategoryId($catname)
    {
        // search in DB
        $db = Zend_Registry::get("DB");
        $select = $db->select()
            ->from(array('c' => 'zanby_groups__categoties'), 'c.id')
            ->where('c.name = ?',$catname);
        $res = $db->fetchOne($select);
        return $res;
    }

    
 /*   
	public function isEmailExist($value) 
	{
		$res = false;
		foreach($this->allstr as $key=>$item) {
			if(!isset($item['email']) || $key==$this->n){
				continue;
			}elseif($item['email']==$value) {
				$res = true;
				break;	
			}
		}
		return $res; 
	}
	
*/	
	public function getHead() {
        $_line = $this->readNext();
        // write header Reject file
        fwrite($this->_rejfile,$_line);
        // write header Result file
        foreach($this->hdnames as $key => $value) {
//todo: ';' replace with $this->_sym        	
        	fwrite($this->_resfile,$key.';');
        }
        fwrite($this->_resfile,"\n");

        /////
        $_line = trim(strtolower($_line));
        $this->_sym =(strpos($_line,';')===false)?',':';';
        $head_csv = explode($this->_sym,$_line);
        foreach($head_csv as $key => $value){
			$value = trim($value);
			if(isset($this->hdnames[$value]) ){
				$this->hd[$value] = $key;
				$this->hdn[] = $value;
			}
		}
        // strings            
	}
	
	public function getHeadRes() {
        $_line = $this->readNextRes();
        /////
        $_line = trim(strtolower($_line));
        $this->_sym =(strpos($_line,';')===false)?',':';';
        $head_csv = explode($this->_sym,$_line);
        foreach($head_csv as $key => $value){
			$value = trim($value);
			if(isset($this->hdnames[$value]) ){
				$this->hd[$value] = $key;
				$this->hdn[] = $value;
			}
		}
	}
	
	public function parseStr($warn_write = false) {
		$this->origin_field = explode($this->_sym,$this->onestr);
		$this->allstr[$this->n] = array();
		$this->warn_row = array();
		foreach($this->hd as $key => $value){
			if(isset($this->origin_field[$value]) && !empty($this->origin_field[$value])){
				$this->setStrField($key,trim($this->origin_field[$value]));
			} else {
				$this->addWarn($key);
				//todo
			}
		}
		if($warn_write && count($this->warn_row)>0) {
			$warn_str = "Row ".$this->getRowNum().'. Empty fields: '.implode(',',$this->warn_row)."\n";
			fwrite($this->_warnfile,$warn_str);
            $this->allwarn[] = $warn_str;
		}
	}	
	public function getStrField($fieldname)
	{
		if(isset($this->allstr[$this->n][$fieldname])) {
			return $this->allstr[$this->n][$fieldname];
		} else {
			return false;
		}
	}
	public function setStrField($fieldname,$value)
	{
		$this->allstr[$this->n][$fieldname] = $value;
		return;
	}
	
	public function getOneStr()
	{
		return $this->onestr;
	}
	
	public function incRowNum($value=1)
	{
		return $this->n+=$value;
	}
	
	public function isGroup($groupName)
	{
		$db = Zend_Registry::get("DB");
	    $query = $db->select()
	    	->from(array('g' => 'zanby_groups__items'), 'g.name')
	    	->where('g.name = ?',$groupName);
	    $res = $db->fetchOne($query);
	    return ($res===false)?false:true;
	}
	
    public function isFamily($groupName)
    {
        $db = Zend_Registry::get("DB");
        $query = $db->select()
            ->from(array('g' => 'zanby_groups__items'), 'g.name')
            ->where('g.name = ?',$groupName)
            ->where('g.type = ?','family');
        $res = $db->fetchOne($query);
        return ($res===false)?false:true;
    }
	
	public function readNext()
	{
		$this->setErr(false);
		if(feof($this->_infile)) {
			return false;
		} else {
			$this->loadstr = fgets($this->_infile, 4096);
			$this->onestr = trim($this->loadstr);		
			return $this->loadstr;
		}
	}

	public function readNextRes()
	{
		$this->setErr(false);
		if(feof($this->_resfile)) {
			return false;
		} else {
			$this->loadstr = fgets($this->_resfile, 4096);
			$this->onestr = trim($this->loadstr);		
			return $this->loadstr;
		}
	}
	
	public function inEOF()	{
		return feof($this->_infile);
	}
	public function resEOF()	{
		return feof($this->_resfile);
	}
	
	
	public function getRowNum()	{
		return $this->n;
	}

	public function setRowNum($value )
	{
		$this->n = $value; 
	}
	
	public function getAllStr()	{
		return $this->allstr;
	}
	public function getAllErr()	{
		return $this->allerr;
	}
    public function getAllWarn()    {
        return $this->allwarn;
    }

	public function isErr()
	{
		return $this->_error;
	}
	public function setAdminName($value )
	{
		$this->adminname = $value; 
	}
	public function setErr($value = true)
	{
		$this->_error = $value; 
	}
	public function addErr($field,$err=false)
	{
		$this->setErr();
		if($err===false)$err="Incorrect ".$field;
		fwrite($this->_errfile,$this->onestr."; Error: ".$err."\n");
		$this->allerr[$this->n]['row'] = $this->n;
		$this->allerr[$this->n]['err'] = $err;
		$this->allerr[$this->n]['field'] = $field;
		$this->allerr[$this->n]['value'] = $this->getStrField($field);
	}
	
    public function addWarn($fieldname)
    {
        $this->warn_row[] = $fieldname;
        $this->rec_warn++;
    }
    
	public function writeRes()
	{
		if($this->isErr()) {
			fwrite($this->_rejfile,$this->loadstr);
			$this->rec_err++;
		} else {
			
			foreach($this->hdnames as $key => $value) {
				$value = $this->getStrField($key);
				if(!$value)$value='';
//todo: ';' replace with $this->_sym        	
        		fwrite($this->_resfile,$value.';');
        	}
        	fwrite($this->_resfile,"\n");
		}
	}
	
	public function getRecErr()	{
		return $this->rec_err;
	}
	public function getRecWarn()	{
		return $this->rec_warn;
	}
	
	
	public function uploadCSV()
	{
		$this->_filename = $this->getUploadDir().'import_groups_'.$this->adminname;
		
	    $_max_size = DOCUMENTS_SIZE_LIMIT;
	    $_max_size = is_int($_max_size) ? sprintf("%01.1f", $_max_size/1024/1024)."M" : $_max_size;
	    $upload_err = false;
	    
	    if (empty($_FILES["new_file"]) || $_FILES['new_file']['error'] !== 0 ) {
	    	$upload_err = true;
	    	if (!empty($_FILES["new_file"]["name"])) {
	            switch ($_FILES['new_file']['error']) {
	                case UPLOAD_ERR_INI_SIZE:
	                case UPLOAD_ERR_FORM_SIZE:
	                    $errors[] = "File is too big. Max filesize is ".$_max_size;
	                    break;
	                case UPLOAD_ERR_NO_FILE:
	                    $errors[] = "Please select correct file for upload.";
	                    break;
	                default:
	                    $errors[] = "Upload failed";
	                    break;
	            }
	        } else {$errors[] = 'Please select file to upload';}
	 				Zend_Debug::dump($errors);
	    } elseif (filesize($_FILES["new_file"]["tmp_name"]) > DOCUMENTS_SIZE_LIMIT) {
	  		$upload_err = true;
	    } else {
	    	$file_name = tempnam($this->getUploadDir(), '__');
	    	if (!Warecorp_File_Item::uploadFile($_FILES['new_file']['tmp_name'], $file_name) ) {
	  			$upload_err = true;
	    	}
	    }
		if(!$upload_err) {
			rename($file_name, $this->_filename.'_in.csv');
		}
	    return !$upload_err;
	}
	
	public function setUploadDir($value) {
		$this->upload_dir = DOC_ROOT.$value;
		return true;
	}
	public function getUploadDir() {
		return $this->upload_dir;
	}
	
	public function openRes() {
		$this->_filename = $this->getUploadDir().'import_groups_'.$this->adminname;
       	$this->_resfile = fopen($this->_filename.'_res.csv', "r");
	}
	
	public function closeRes(){
	    fclose($this->_resfile);
	}
	
	public function open5(){
		
	    $this->_infile = fopen($this->_filename.'_in.csv', "r");
       	$this->_rejfile = fopen($this->_filename.'_rej.csv', "w");
       	
       	$this->_errfile = fopen($this->_filename.'_err.csv', "w");
       	$this->_warnfile = fopen($this->_filename.'_warn.csv', "w");
       	$this->_resfile = fopen($this->_filename.'_res.csv', "w");
	}
       	
	public function close5(){
	    fclose($this->_infile);
	    fclose($this->_rejfile);
	    fclose($this->_errfile);
	    fclose($this->_warnfile);
	    fclose($this->_resfile);
	}
	    	    
	
	
	
}
