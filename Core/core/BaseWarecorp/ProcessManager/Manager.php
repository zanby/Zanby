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

require_once ('PHP/Fork.php');

class BaseWarecorp_ProcessManager_Manager {
	private $configFile;
	private $processes = array();
    private $terminateAll = false;
    protected static $log = null;
    
	public function setConfigFile($configFile) {
		$this->configFile = $configFile;
	}
    
    public static function getLog(){
        return self::$log;
    }
	
	private function processConfig() {
        $hostname = trim(exec('hostname'));
        var_dump($hostname);
	    if (empty($hostname)) {
	        echo "Warning: Cannot determinate host name\n";
	    }
	    if (!file_exists($this->configFile)) die("Config file $this->configFile doesn't exist");
        $cfgLoader = Warecorp_Config_Loader::getInstance();

        $xml = $cfgLoader->getAppConfig('cfg.processManager.xml');
		//$xml = simplexml_load_file($this->configFile);
		$processes = $xml->processes->process;
        foreach ($processes as $process) {
            if (isset($process->hosts) && ($process->hosts != "ALL")) {
                if (empty($hostname)) continue;
                $hosts = explode(';', $process->hosts);
                if (!in_array($hostname, $hosts)) continue;
            }
            var_dump($process->id );
            $processDescription = new Warecorp_ProcessManager_ProcessDescription();
            $processDescription->id = (string)$process->id;
            $processDescription->cmd = (string)$process->cmd;
            $processDescription->period = intval($process->period);
            $processDescription->maxLifeTime = intval($process->maxLifeTime);

            $processUser = (string)$process->user;
            if (posix_getuid() != 0) {
                echo "Warning: Process manager should be run by root to run process by different user\n";
            } else {
                $pwnam = posix_getpwnam($processUser);
                if ($pwnam !== false) {
                    $processDescription->user = $processUser;
                    $processDescription->uid = $pwnam['uid'];
                    $processDescription->gid = $pwnam['gid'];
                } else {
                    echo "Warning: User $processUser doesn't exists, run as root\n";
                }
            }

            $this->processes[] = $processDescription;
        }
        $logType = (string)$xml->logging->type;
        if ($logType == 'file') {
            echo (string)$xml->logging->target."\n\n";
            $writer = new Zend_Log_Writer_Stream((string)$xml->logging->target);
            
            $format = '%timestamp% %priorityName% (%priority%): %message%' . PHP_EOL;
            $formatter = new Zend_Log_Formatter_Simple($format);
            
            $filter = new Zend_Log_Filter_Priority(intval($xml->logging->level));
            $writer->addFilter($filter);
            
            $writer->setFormatter($formatter);

            $adapter = new Zend_Log($writer);
            

            //$adapter->log('Process-manager have been configured and started', Zend_Log::ALERT);
            
            //$adapter->setOption('format', '%time% %level% %message%' );
            //Zend_Log::registerLogger($adapter);
        }
        //Zend_Log::setMask(intval($xml->logging->level));
        self::$log = $adapter;
        self::getLog()->log('Process-manager have been configured and started', Zend_Log::INFO);
	}
	
	public function run() {
	    $this->processConfig();
        //self::getLog()->log('Run', Zend_Log::INFO); 

	    
	    while(true) {
	        foreach($this->processes as $processDescription) {
                if ($processDescription->terminated) continue;
	            $process = $processDescription->processExecutor;
	            if (!$process && !$processDescription->processStartTime) {
	                $this->executeProcess($processDescription);
	            } elseif (!$process) {
	                if ($processDescription->period) {
	                    if ($processDescription->processStartTime + $processDescription->period <= time()) {
                            $this->executeProcess($processDescription);	                        
	                    }
	                } else {
	                    $this->executeProcess($processDescription);
	                }
	            } elseif ($process->isActive()) {
	                if ($processDescription->maxLifeTime && (time() - $processDescription->maxLifeTime > $processDescription->processStartTime)) {
                        $process->stop();
           	           self::getLog()->log('Process '. $processDescription->id. ' killed as time limit was exceeded', Zend_Log::WARN, array('time' => date('M d H:i:s')));
	                    $processDescription->processExecutor = null;
	                }
	            } else {
                    $process->stop();
	                $processDescription->processExecutor = null;
           	        self::getLog()->log('Process '. $processDescription->id. ' terminated', Zend_Log::INFO, array('time' => date('M d H:i:s')));
	                if (!$processDescription->period) {
	                    $this->executeProcess($processDescription);
	                }
	            }
	        }
	        sleep(2);
	    }
	}
	
	private function executeProcess(Warecorp_ProcessManager_ProcessDescription $processDescription) {
	    if (!$processDescription->processExecutor) {
            //self::getLog()->log('started', Zend_Log::INFO); 
	        $process = new Warecorp_ProcessManager_Executor($processDescription->id);
            if ($processDescription->user) {
                $process = new Warecorp_ProcessManager_Executor($processDescription->id, $processDescription->uid, $processDescription->gid);
            } else {
                $process = new Warecorp_ProcessManager_Executor($processDescription->id);
            }
	        if ($process->_ipc_is_ok) {
	            $process->setCmd($processDescription->cmd);
	            self::getLog()->log('Executing '. $processDescription->id. ' process', Zend_Log::INFO, array('time' => date('M d H:i:s')));
	            $processDescription->processStartTime = time();
	            $processDescription->processExecutor = $process;
	            $process->start();
	        } else {
	            self::getLog()->log('Unable to create IPC segment for process '. $processDescription->id, Zend_Log::ERR, array('time' => date('M d H:i:s')));
	        }
	    }
	}
	
    public function termAllProcesses() {
        if ($this->terminateAll) return;
        $this->terminateAll = false;
        self::getLog()->log('Terminate all processes', Zend_Log::INFO, array('time' => date('M d H:i:s')));
        foreach($this->processes as $processDescription) {
            $processDescription->terminated = true;
            $process = $processDescription->processExecutor;
            if ($process) {
                self::getLog()->log("Stop process ". $processDescription->id, Zend_Log::INFO, array('time' => date('M d H:i:s')));
                $process->stop();
            }
        }
    }
}
