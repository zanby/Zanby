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
 * @depricated
 * @author DK
 */

/**
class BaseWarecorp_Controller_RequestHandlerFactory {
    private $handlers;
    
    public function __construct($configFile = 'cfg.requestHandlers.xml') {
        $this->handlers = array();
        
        if (file_exists(CONFIG_DIR. $configFile)) {
            $cache = Warecorp_Cache::getFileCache();
            
            $cacheId = str_replace('.', '_', $configFile);
            $cfg = $cache->load($cacheId);
            if ($cfg === false) {
                $xmlCfg = simplexml_load_file(CONFIG_DIR. $configFile);
                
                if ($xmlCfg !== false) {
                    $cfg = array();
                    
                    if (isset($xmlCfg->handler))
                    foreach ($xmlCfg->handler as $handler) {
                        $className = trim((string)$handler->className);
                        if (!$className) continue;
                        
                        $constructParams = array();
                        if (isset($handler->constructParam)) {
                            foreach ($handler->constructParam as $param) {
                                $constructParams[] = (string)$param;
                            }
                        }
                        $cfg[] = array('className' => $className, 'constructParams' => $constructParams);
                    }
                    $cache->save($cfg, $cacheId, array(), 2592000);
                }
            }
            foreach ($cfg as $handlerCfg) {
                try {
                    $class = new ReflectionClass($handlerCfg['className']);
                    $obj = $class->newInstanceArgs($handlerCfg['constructParams']);
                    if ($obj instanceof Warecorp_Controller_IRequestHandler) {
                        $this->handlers[] = $obj;
                    } else throw new Exception('Request handler object must be instance of Warecorp_Controller_IRequestHandler');
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }
        }
    }
    
    public function processRequest($request) {
        if ($request instanceOf Zend_Controller_Request_Http) {
            foreach ($this->handlers as $handler) {
                $handler->processRequest($request);
            }
        }
    }
}
 */
