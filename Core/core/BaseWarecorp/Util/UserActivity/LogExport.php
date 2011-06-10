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

class BaseWarecorp_Util_UserActivity_LogExport {
    private $db;
    
    public function __construct() {
        $this->setDb(Zend_Registry::get('DB'));
    }
    
    /**
     * Return db for tracker
     *
     * @return Zend_Db_Adapter_Abstract database
     */
    public function getDb() {
        return $this->db;
    }
    
    /**
     * Set db for tracker
     *
     * @param Zend_Db_Adapter_Abstract $db
     */
    public function setDb(Zend_Db_Adapter_Abstract $db) {
        $this->db = $db;
    }
    
    /**
     * Return logs in assoc array
     *
     * @param Zend_Date $date month for whitch get logs
     * @return array
     */
    public function getLogs(Zend_Date $date) {
        $result = $this->queryLogs($date);
        return $result->fetchAll();
    }
    
    /**
     * Return CSV logs as string
     *
     * @param Zend_Date $date month for whitch get logs
     * @return string CSV logs
     */
    public function getLogsCsv(Zend_Date $date) {
        $output = '';
        $output .= '"User Id","Login","Time","URI"'. "\n";
        $result = $this->queryLogs($date);
        while($data = $result->fetch()) {
            //Request uri to quotes
            $data['request_uri'] = '"'. str_replace('"', '""', $data['request_uri']). '"';
            $data['tracking_time'] = '"'. $data['tracking_time']. '"';
            $output .= $data['user_id']. ','. $data['login']. ','. $data['tracking_time']. ','. $data['request_uri']. "\n";
        }
        return $output;
    }
    
    /**
     * Write logs to CSV file
     *
     * @param Zend_Date $date month for whitch get logs
     * @param unknown_type $filename output filename
     */
    public function writeLogsToCsvFile(Zend_Date $date, $filename) {
        $result = $this->queryLogs($date);
        $file = fopen($filename, 'w');
        if (!$file) throw new Exception("Warecorp_Util_UserActivity_LogExport: Cannot open file $filename for writing");
        fwrite($file, '"User Id","Login","Time","URI"'. "\n");
        while($data = $result->fetch()) {
            //Request uri to quotes
            $data['request_uri'] = '"'. str_replace('"', '""', $data['request_uri']). '"';
            $data['tracking_time'] = '"'. $data['tracking_time']. '"';
            fwrite($file, $data['user_id']. ','. $data['login']. ','. $data['tracking_time']. ','. $data['request_uri']. "\n");
        }
        fclose($file);
    }
    
    private function queryLogs(Zend_Date $date) {
        $startDate = clone $date;
        $startDate->setDay(1);
        $startDate->setHour(0);
        $startDate->setMinute(0);
        $startDate->setSecond(0);
        
        $sqlStartDate = $startDate->toString('YYYY-MM-dd HH:mm:ss');
        $startDate->addMonth(1);
        $sqlEndDate = $startDate->toString('YYYY-MM-dd HH:mm:ss');
        
        $db = $this->getDb();
        $select = $db->select()->from('zanby_users__activity_tracking')
        ->where('tracking_time >= ?', $sqlStartDate)
        ->where('tracking_time < ?', $sqlEndDate)
        ->order('tracking_time');
        return $db->query($select);
    }

    /**
     * Delete logs for month
     *
     * @param Zend_Date $date month for whitch delete logs
     */
    public function deleteLogs(Zend_Date $date) {
        $startDate = clone $date;
        $startDate->setDay(1);
        $startDate->setHour(0);
        $startDate->setMinute(0);
        $startDate->setSecond(0);
        
        $sqlStartDate = $startDate->toString('YYYY-MM-dd HH:mm:ss');
        $startDate->addMonth(1);
        $sqlEndDate = $startDate->toString('YYYY-MM-dd HH:mm:ss');
        
        $db = $this->getDb();
        $where = $db->quoteInto('tracking_time >= ?', $sqlStartDate). ' AND '. $db->quoteInto('tracking_time < ?', $sqlEndDate);
        $db->delete('zanby_users__activity_tracking', $where);
    }
    /**
     * Create Warecorp_Util_UserActivity_LogExport object and configure it with params in config file
     *
     * @param string $configFileName config dile
     * @return Warecorp_Util_UserActivity_LogExport
     */
    public static function createFromConfig($configFileName = 'cfg.userActivityTracker.xml') {
        $logExport = new self();
        
        $xml = Warecorp_Util_UserActivity_ConfigTracker::getXmlConfig($configFileName);
        
        if (isset($xml->useCustomDatabase) && $xml->useCustomDatabase == '1') {
            $cfgDb = $xml->customDatabase;
            $params = array (
        		'host'     => $cfgDb->host,
        		'username' => $cfgDb->username,
        		'password' => $cfgDb->password,
        		'dbname'   => $cfgDb->name
            );
            
            try {
                $db = Zend_Db::factory($cfgDb->type, $params);
                $sql = "SET NAMES utf8";
                $db->query($sql);
                $sql = "SET time_zone = 'UTC';";
                $db->query($sql);
                $logExport->setDb($db);
            } catch (Exception $e) {
                throw new Exception("Cannot connect to user activity tracker custom database: ". $e->getMessage());
            }
        }
        return $logExport;
    }
}
