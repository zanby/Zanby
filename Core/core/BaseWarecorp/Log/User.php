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
 * @package Warecorp_Log
 * @author Pavel Shutin
 */
class BaseWarecorp_Log_User
{
	protected $filters = array();

    static protected $tableName = 'zanby_users__log';

    protected $db = null;

    const SUCCESS = 1;

    const FAILURE = 2;


    public function __counstruct()
    {
        $this->db = null;
    }

    /**
     *
     * @param array $filters
     * @return Warecorp_Log_User
     */
    public function setFilters(array $filters) {
        $this->filters = $filters;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getFilters() {
        return $this->filters;
    }

    /**
     *
     * @param array $filters
     * @return Warecorp_Log_User
     */
    public function addFilters(array $filters) {
        if (!is_array($filters)) throw new Exception();

        $this->filters = array_merge($this->filters,$filters);

        return $this;
    }

    /**
     *
     * @return Zend_Db_Adapter_Abstract
     */
    protected function getDB() {
        if ($this->db === null)  $this->db = Zend_Registry::get("DB");
        return $this->db;
    }

    /**
     *
     * @param Zend_Db_Select $query
     * @return Zend_Db_Select
     */
    protected function applyFilters(&$query) {

        if (!empty($this->filters['order'])) $query->order($this->filters['order']);

        if (!empty($this->filters['page']) || !empty($this->filters['size'])) {
            if (empty($this->filters['size'])) $this->filters['size'] = 20;
            if (empty($this->filters['page'])) $this->filters['page'] = 1;
            $query->limitPage($this->filters['page'],$this->filters['size']);
        }

        if (!empty($this->filters['date_start'])) {
            $query->where('created_at >= ? ',$this->filters['date_start']);
        }

        if (!empty($this->filters['date_end'])) {
            $query->where('created_at <= ? ',$this->filters['date_end']);
        }

        if (!empty($this->filters['action'])) {
            $query->where('action = ?',$this->filters['action']);
        }

        if (!empty($this->filters['status'])){
            $query->where('status = ?',$this->filters['status']);
        }

        return $query;
    }

    /**
     * Retuns log list
     * @return array
     */
    public function getList() {
        $db = $this->getDB();

        $query = $db->select()->from(array('ul' => self::$tableName))->order('created_at DESC');

        $this->applyFilters($query);

        return $db->fetchAll($query);
    }

	public static function getTotalCount()
	{
        $db = Zend_Registry::get('DB');

        $query = $db->select()->from(array('ul' => self::$tableName),'count(*)');
        return $db->fetchOne($query);
	}
    
    public function getCount()
    {
        $db = $this->getDB();
        $query = $db->select()->from(array('ul' => self::$tableName),'count(*) as cnt');
        $this->applyFilters($query);
        return $db->fetchOne($query);
    }


    /**
     * Export log list to CSV
     * @return void
     */
    public function exportToCSV() {
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        srand(floor(time()/13));
        $path = DOC_ROOT.'/upload/documents/log_export_'.rand().'csv';
        $file = fopen($path,'w');

        $db = $this->getDB();

        $query = $db->select()->from(array('ul' => self::$tableName))->order('created_at DESC');

        $this->applyFilters($query);

        $query->join(array('zua'=>'zanby_users__accounts'),'zua.id = ul.user_id','login');
        
        fwrite ($file,Warecorp::t('Date/Time, Username, Action')."\n");
        

        $result = $db->fetchAll($query);
        foreach ($result as $row) {
            

            $date = new Zend_Date($row['created_at'],'Y-M-d H:m:s');

            $string = $date->toString('M/d/Y h:m:s a').','.$row['login'].',';
            if ($row['action'] == 'login') {
                $string.=Warecorp::t('Login').' '.Warecorp::t($row['status'] == self::SUCCESS ? 'Success':'Failure');
            }elseif ($row['action'] == 'password_restore') {
                $string.=Warecorp::t('Password request');
            }

            fwrite($file,$string."\n");
        }
        
        fclose($file);

        /* give file for downloading */
        header("Content-Type: text/csv");
        header("Content-Length: ". filesize($path));
        header("Content-Disposition: attachment; filename=\"users_log.csv\"");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate");
        header("Cache-Control: no-cache");

        readfile($path);
        unlink($path);
        date_default_timezone_set($defaultTimezone);
    }

    /**
     * Writes new log entry
     * @param string $action
     * @param int $status
     * @param array $params
     */
    public static function addEntry($action,$status = Warecorp_Log_User::SUCCESS,$params = null) {
        $now = new Zend_Date();
        $now->setTimezone('UTC');
        $data = array('action'=>$action,'status'=>$status,'created_at'=>$now->toString('Y-M-d H:m:s'));
        if (!empty($params)) {
            $data = array_merge($data,$params);
        }
        $db = Zend_Registry::get('DB');
        $db->insert(self::$tableName,$data);
    }
}
