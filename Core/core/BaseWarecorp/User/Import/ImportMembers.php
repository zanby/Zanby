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


class BaseWarecorp_Import_Members
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
    private $_mxfile;
	
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
    private $rec_rej = 0;
    private $rec_join = 0;
    private $rec_added = 0;
    private $is_join_col = false;
    private $is_only_join = false;
    private $join_col = array();
    private $join_matrix = array();
    private $mx_row = array();
    private $tmname;
    public $addeduser;
    
    
    public 	$hdnames = array( 'email'=>true,
						'first_name'=>true,
						'last_name'=>true,
						'login'=>true,
						'password'=>true,
						'gender'=>true,
    					'is_gender_private'=>true,
    					'birthday'=>true,
    					'is_birthday_private'=>true,
    					'country'=>true,
						'state'=>true,
						'city'=>true,
						'country_id'=>true,
						'city_id'=>true,
    					'zip'=>true,
                        'phone'=>true,
                        'congressional_district' => true);
    
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

    public function isOnlyJoin()
    {
        return $this->is_only_join;
    }
    public function setOnlyJoin($value = true)
    {
        $this->is_only_join = $value;
        return;
    }

    public function getJoinCol()
    {
        return $this->join_col;
    }
	public function getHdn()
	{
		return $this->hdn;
	}
    public function isJoinCol()
    {
        return $this->is_join_col;
    }
    public function setJoinCol($value = true)
    {
        $this->is_join_col = $value;
        return;
    }

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
	
	public function isLoginExist($value)
	{
		// search in DB
		$db = Zend_Registry::get("DB");
	    $select = $db->select()
	    	->from(array('u' => 'zanby_users__accounts'), 'u.login')
	    	->where('u.login = ?',$value);
	    $res = $db->fetchOne($select);
		if($res === false) {
			// search in Array
			foreach($this->allstr as $key=>$item) {
				if(!isset($item['login']) || $key==$this->n){
					continue;
				}elseif($item['login']==$value) {
					$res = true;
					break;	
				}
			}
			return $res; 
		} else {
			return true;
		}
	}
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
	
	
	public function getHead() {
        $_line = $this->readNext();
        $this->_sym =(strpos($_line,';')===false)?',':';';
        $orig_line = $_line;
        // write header Reject file
        fwrite($this->_rejfile,$_line);
        // write header Result file
        foreach($this->hdnames as $key => $value) {
        	fwrite($this->_resfile,$key.$this->_sym);
        }
        fwrite($this->_resfile,"\n");

        /////
        $_line = trim(strtolower($_line));
        $head_csv = explode($this->_sym,$_line);
        $orig_head = explode($this->_sym,$orig_line);
        foreach($head_csv as $key => $value){
			$value = trim($value);
			if(isset($this->hdnames[$value]) ){
				$this->hd[$value] = $key;
				$this->hdn[] = $value;
			} elseif($this->isJoinCol()) {
                $orig_head[$key] = trim($orig_head[$key]);
                 if($this->isGroupSimple($orig_head[$key])) {
                // Not field ==> Group name
                    $this->join_col[$key] = $orig_head[$key];
                 } else {
                    fwrite($this->_errfile,$orig_head[$key]." - Incorrect group \n");
                    $this->allerr[$this->rec_err]['row'] = 1;
                    $this->allerr[$this->rec_err]['err'] = "Incorrect group";
                    $this->allerr[$this->rec_err]['field'] = 'group_name';
                    $this->allerr[$this->rec_err]['value'] = $orig_head[$key];
                    $this->rec_err++;
                 }
            }
		}
        if($this->isJoinCol()) {
            fwrite($this->_mxfile,'email'.$this->_sym.implode($this->_sym,$this->join_col)."\n");
        }
        
        // strings            
	}
	
	public function getHeadRes() {
        $_line = $this->readNextRes();
        $this->_sym =(strpos($_line,';')===false)?',':';';
        $orig_line = $_line;
        /////
        $_line = trim(strtolower($_line));
        $head_csv = explode($this->_sym,$_line);
        $orig_head = explode($this->_sym,$orig_line);
        foreach($head_csv as $key => $value){
			$value = trim($value);
			if(isset($this->hdnames[$value]) ){
				$this->hd[$value] = $key;
				$this->hdn[] = $value;
            } elseif($this->isJoinCol()) {
                 if($this->isGroupSimple(trim($orig_head[$key]))) {
                // Not field ==> Group name
                    $this->join_col[$key] = trim($orig_head[$key]);
                 } else {
//                    fwrite($this->_errfile,$orig_head[$key]." - Incorrect group \n");
                 }
            }
		}
	}

    public function getHeadMx() {
        $_line = $this->readNextMx();
        $this->_sym =(strpos($_line,';')===false)?',':';';
        $orig_head = explode($this->_sym,$_line);
        foreach($orig_head as $key => $value){
            if($key>0) {
                $this->join_col[$key] = trim($orig_head[$key]);
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

    public function parseMx($warn_write = false) {
        $this->origin_field = explode($this->_sym,$this->onestr);
        $this->mx_row = array();
        $count_add = 0;
        
        $db = Zend_Registry::get("DB");
        $query = $db->select();
        $query->from(array('u' => 'zanby_users__accounts'), 'u.id');
        $query->where('u.email = ?',$this->origin_field[0]);
        $res = $db->fetchOne($query);
        $this->addeduser = new Warecorp_User('id',$res);
        if($this->addeduser->getId()>0) {
            foreach($this->origin_field as $key => $value){
                if($key>0 && !empty($value)) {
                    $this->joinToGroup($this->join_col[$key]);
                    $count_add++;
                }
            }
        }
        if($count_add>0) {
            $this->rec_added++;
        }
    }    

    public function joinToGroup($gname)
    {
        $group = Warecorp_Group_Factory::loadByName($gname);
        if($this->addeduser->getId()>0 && $group->getId()>0 && !$this->isJoined($this->addeduser->getId(),$group->getId())) {
            $group->getMembers()->addMember($this->addeduser->getId(), 'member', 'approved');  
        }
    }

    public function isJoined($user_id,$group_id)
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select()
            ->from(array('m' => 'zanby_groups__members'), 'm.id')
            ->where('m.user_id = ?',$user_id)
            ->where('m.group_id = ?',$group_id);
        $res = $db->fetchOne($select);
        return ($res===false)?false:true;
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
    public function getTmName()
    {
        return $this->tmname;
    }
    
    public function setTmName($value)
    {
         $this->tmname = $value;
         return;
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

    public function isGroupSimple($groupName)
    {
        $db = Zend_Registry::get("DB");
        $query = $db->select()
            ->from(array('g' => 'zanby_groups__items'), 'g.name')
            ->where('g.name = ?',$groupName)
            ->where('g.type = ?','simple');
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

    public function readNextMx()
    {
        if(feof($this->_mxfile)) {
            return false;
        } else {
            $this->loadstr = fgets($this->_mxfile, 4096);
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
	
    public function mxEOF()    {
        return feof($this->_mxfile);
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
		fwrite($this->_errfile,$this->onestr.$this->_sym."Error: ".$err."\n");
		$this->allerr[$this->rec_err]['row'] = $this->n+1;
		$this->allerr[$this->rec_err]['err'] = $err;
		$this->allerr[$this->rec_err]['field'] = $field;
		$this->allerr[$this->rec_err]['value'] = $this->getStrField($field);
        $this->rec_err++;
	}
	
    public function addWarn($fieldname)
    {
        $this->warn_row[] = $fieldname;
        $this->rec_warn++;
    }
    
	public function writeRes()
	{
		if($this->isErr()) {
            $this->rec_rej++;
			fwrite($this->_rejfile,$this->loadstr);
		} elseif(!$this->isOnlyJoin()) {
			foreach($this->hdnames as $key => $value) {
				$value = $this->getStrField($key);
				if(!$value)$value='';
        		fwrite($this->_resfile,$value.$this->_sym);
        	}
        	fwrite($this->_resfile,"\n");
		} else {
            $this->rec_join++;
        }
	}

    public function writeMx($email)
    {
        if($this->isJoinCol()) {
            fwrite($this->_mxfile,$email);
            foreach($this->join_col as $key => $value) {
                $cell = isset($this->origin_field[$key])?$this->origin_field[$key]:' ';
                fwrite($this->_mxfile,$this->_sym.$cell);
            }
            fwrite($this->_mxfile,"\n");
        }
    }
	
    public function getRecAdded()    {
        return $this->rec_added;
    }
	public function getRecErr()	{
		return $this->rec_err;
	}
	public function getRecWarn()	{
		return $this->rec_warn;
	}
    public function getRecJoin()    {
        return $this->rec_join;
    }
    public function getRecRej()    {
        return $this->rec_rej;
    }
	
	
	public function uploadCSV()
	{
		$this->_filename = $this->getUploadDir().'import_members_'.$this->adminname.$this->tmname;
		
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
//			$this->_filename = $upload_dir.'import_members_'.$this->adminname;
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
		$this->_filename = $this->getUploadDir().'import_members_'.$this->adminname.$this->tmname;
       	$this->_resfile = fopen($this->_filename.'_res.csv', "r");
	}

    public function openMx() {
        $this->_filename = $this->getUploadDir().'import_members_'.$this->adminname.$this->tmname;
        $this->_mxfile = fopen($this->_filename.'_mx.csv', "r");
    }
	
	public function closeRes(){
	    fclose($this->_resfile);
	}

    public function getImportedTransactions(){
        $db = Zend_Registry::get("DB");
        $select = $db->select()
                    ->from(array('u' => 'zanby_users__accounts'), array('u.imported_user', 'users_count' => 'count(1)'))
                    ->group('u.imported_user')
                    ->where('u.imported_user > 0')
                    ->order('u.imported_user DESC')
                    ->limit(50);

//                    
        $res=$db->fetchAll($select);
        return $res;
    }


    public function closeMx(){
        fclose($this->_mxfile);
    }
	
	public function open5(){
		
	    $this->_infile = fopen($this->_filename.'_in.csv', "r");
       	$this->_rejfile = fopen($this->_filename.'_rej.csv', "w");
       	
       	$this->_errfile = fopen($this->_filename.'_err.csv', "w");
       	$this->_warnfile = fopen($this->_filename.'_warn.csv', "w");
       	$this->_resfile = fopen($this->_filename.'_res.csv', "w");

        $this->_mxfile = fopen($this->_filename.'_mx.csv', "w");
    }
       	
	public function close5(){
	    fclose($this->_infile);
	    fclose($this->_rejfile);
	    fclose($this->_errfile);
	    fclose($this->_warnfile);
	    fclose($this->_resfile);

        fclose($this->_mxfile);
    }
	    	    
	
	
	
}
