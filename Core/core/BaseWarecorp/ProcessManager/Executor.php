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

class BaseWarecorp_ProcessManager_Executor extends PHP_Fork {
    private $id;
    private $cmd;

    private $process;
    private $pipes;
    private $stdoutDone;
    private $stderrDone;

    public function __construct($id, $puid = 0, $guid = 0) {
        parent::PHP_Fork($id, $puid, $guid);
        $this->id = $id;
        //Warecorp_ProcessManager_Manager::getLog()->log("test", Zend_Log::WARN, array('time' => date('M d H:i:s')));
    }

    public function setCmd($cmd) {
        $this->cmd = $cmd;
    }

    public function run() {
        $descriptorspec = array(
        1 => array("pipe", "w"),
        2 => array("pipe", "w")
        );

        $this->process = proc_open($this->cmd, $descriptorspec, $this->pipes, null, array('APPLICATION_ENV' => APPLICATION_ENV));
        if (is_resource($this->process)) {
            $this->stdoutDone = false;
            $this->stderrDone = false;

            while(true) {
                $rx = array();
                if (!$this->stdoutDone) $rx[] = $this->pipes[1];
                if (!$this->stderrDone) $rx[] = $this->pipes[2];

                @stream_select($rx, $tx = null, $ex = null, null, null);
                foreach ($rx as $r) {
                    if ($r == $this->pipes[1]) {
                        $str = trim(fgets($this->pipes[1], 4096));
                        if ($str) {
                            Warecorp_ProcessManager_Manager::getLog()->log($this->id. ' '. $str, Zend_Log::WARN, array('time' => date('M d H:i:s')));
                        }
                        if (feof($this->pipes[1])) { fclose($this->pipes[1]); $this->stdoutDone = true; }
                    } else if ($r == $this->pipes[2]) {
                        $str = trim(fgets($this->pipes[2], 4096));
                        if ($str) {
                            Warecorp_ProcessManager_Manager::getLog()->log($this->id. ' '. $str, Zend_Log::ERR, array('time' => date('M d H:i:s')));
                        }
                        if (feof($this->pipes[2])) { fclose($this->pipes[2]); $this->stderrDone = true; }
                    }
                }
                if (!is_resource($this->process)) break;
                if ($this->stdoutDone && $this->stderrDone) break;
            }
            $returnValue = proc_close($this->process);
        }
    }

    public function terminateProcess() {
        if ($this->_isChild) {
            if (is_resource($this->process)) {
                if (!$this->stdoutDone) {
                    fclose($this->pipes[1]); $this->stdoutDone = true;
                }
                if (!$this->stderrDone) {
                    fclose($this->pipes[2]); $this->stderrDone = true;
                }
                proc_terminate($this->process, SIGKILL);
                proc_close($this->process);
            }
        } else {
            return $this->register_callback_func(null, __FUNCTION__);
        }
    }
}
