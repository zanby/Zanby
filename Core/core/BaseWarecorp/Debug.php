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
 *
 * @package    Warecorp
 * @copyright  Copyright (c) 2007
 * @author Alexander Komarovski
 */

class BaseWarecorp_Debug
{

    const KYLOBYTE = 1024;
    const MEGABYTE = 1048576;

    static private $_output_file;
    static private $_output_format = "date;host;controller;action;cont-act;\"request URI\";\"page execution time, s\";\"num of queries\";\"longest query time, s\";\"longest query\";\"memory usage, Mb\"\r\n";
    static private $_output_max_size = 10485760; // 10 Mb

    static private function getFile()
    {
        if ( self::$_output_file === NULL ) {
            $cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');
            $file = trim($cfgInstance->measurement->file);
            if ( $file === '' )
                throw new Zend_Exception('Output file isn\'t set in cfg.instance.xml: section <measurement>');
            else if ( substr($file, 0, 1) === '/' ) {   //  Absolute path
                // ok
            } else {  //  revative path
                $file = rtrim(APPLICATION_PATH, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$file;
            }

            if ( file_exists($file) ) {
                if ( !is_writable($file) )
                    throw new Zend_Exception("Output file '{$file}' exists, but it not writeable. Please, check file permissions.");
                else {
                    self::$_output_file = $file;
                    if ( 
                        is_writable(dirname($file))                 &&
                        is_readable($file)                          &&
                        filesize($file) > self::$_output_max_size   &&
                        function_exists('bzopen')
                    ) {
                        //  compress file
                        //  for decompress in Linux console: $ bzip2 -d file.bz2
                        $afile = rtrim(dirname($file), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.date('m.d.Y_H.i').'.csv.bz2';
                        if (FALSE !== ($bz = @bzopen($afile, "w"))) {
                            bzwrite($bz, file_get_contents($file));
                            bzclose($bz);

                            //  set empty csv file
                            $h = fopen($file, "w");
                            fwrite($h, self::$_output_format, strlen(self::$_output_format));
                            fclose($h);
                        }
                    }
                }
            } else {
                $dir = dirname($file);
                if ( !file_exists($dir) ) {
                    if ( FALSE === @mkdir($dir, 0777, true) )
                        throw new Zend_Exception("Please, check permissions for directory '{$dir}', it can't be created.");
                }
                if ( !is_writable($dir) )
                    throw new Zend_Exception("Please, check permissions for directory '{$dir}', file can't into it.");
                if (FALSE === ($h = fopen($file, 'w')))
                    throw new Zend_Exception("Error create file '{$file}'. Please, check directory permissions.");
                $str = trim(self::$_output_format)."\n";
                $len = strlen($str);
                fwrite($h, $str, $len);
                fclose($h);
                @chmod($file, 0666);

                self::$_output_file = $file;
            }
        }

        return self::$_output_file;
    }

    static public function write($fields)
    {
        $cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');
        if ( isset($cfgInstance->measurement) && $cfgInstance->measurement->mode === 'on' ) {
            try {
                $file = self::getFile();
            } catch ( Zend_Exception $e ) {
                echo '<br /><span style="color:red">'.$e->getMessage().'</span><br />';
                return;
            }

            $h = fopen($file, "a");
            fputcsv($h, $fields, ";");
            fclose($h);
            echo "<br />Logging into file: ENABLED. File: {$file}";
        } else {
            echo "<br />Logging into file: DISABLED.";
        }
    }

    /**
     * Recursive function that generate XML structure for value or array
     *
     * @param object $dom
     * @param object $branch
     * @param string $key
     * @param string or array $value
     * @author Alexander Komarovski
     */
    public static function arrToXML(&$dom, &$branch, $key, $value, $prefix = '')
    {
        //integer names of XML elements is incorrect
        if ($key !== $prefix && !empty($prefix)) $key = $prefix.'_'.$key;

        $item = $dom->createElement($key);

        if (is_array($value))
        {
            foreach ($value as $k=>$v)
            {
                Warecorp_Debug::arrToXML($dom, $item, $k, $v, $prefix);
            }
        }
        else
        {
            if (is_object($value)) $value = serialize($value);
            $text = $dom->createTextNode($value);
            $item->appendChild($text);
        }
        $branch->appendChild($item);

        return true;
    }

    /**
     * Adding new records to log(s)
     *
     * @param string $filename
     * @param array $aProfiler
     * @author Alexander Komarovski
     */
    public static function makeXMLLog($filename, &$aProfiler)
    {
        if (empty($filename)) return false;

        $dom = new DOMDocument('1.0');
        $dom->formatOutput = true;

        if( file_exists($filename) ) {
            $dom->load($filename);
            $_tmp = $dom->getElementsByTagName('root');
            foreach ($_tmp as $element) {
                $root = $element;
                break;
            }
        } else {
            $root = $dom->createElement("root");
            $dom->appendChild($root);
        }

        $record = $dom->createElement("record");
        $root->appendChild($record);

        Warecorp_Debug::arrToXML($dom, $record, 'date', date("d.m.Y, H:i:s"));
        Warecorp_Debug::arrToXML($dom, $record, 'timestamp', time());
        Warecorp_Debug::arrToXML($dom, $record, 'url', $_SERVER['QUERY_STRING']);

        Warecorp_Debug::arrToXML($dom, $record, 'sql_section', $aProfiler);

        Warecorp_Debug::arrToXML($dom, $record, 'session', $_SESSION, 'session');
        Warecorp_Debug::arrToXML($dom, $record, 'cookie', $_COOKIE, 'cookie');

        $dom->save($filename);

        return true;
    }


    /**
     * @author Alexander Komarovski
     */
    public static function makeLongQueriesLog($filename, $_time, $_query)
    {
        if (empty($filename)) return false;

        $fp = fopen($filename, 'a');
        $logstring = $_time."[sec] --- [query] ".$_query;
        fwrite($fp, str_replace("\t", " ",(str_replace("\n", " ",str_replace("\r\n", " ",$logstring))))."\n"."\n" );
        fclose($fp);
        
        return true;
    }
    
    
    
    /**
     * main function
     * @param int $time
     * @author Alexander Komarovski
     */
    public static function analyse( $time = 'undefined', $display_stat = true)
    {
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
        $cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');
        
        //----------------------------------------------------------------------------------------------------------------------------------
        if ($cfgInstance->debug_long_queries == "on"){

            $db = Zend_Registry::get("DB");
            $profiler = $db->getProfiler();

            $queries = $profiler->getQueryProfiles();
            
            if (!empty($queries))
            foreach ($queries as $_key => $query) {
                if ($query->getElapsedSecs() >= $cfgInstance->debug_catch_queries_longer) {
                          if ($cfgInstance->debug_long_queries_mode == 'global' || $cfgInstance->debug_long_queries_mode == 'both'){
                              Warecorp_Debug::makeLongQueriesLog(DEBUG_LOG_DIR.'long_queries_global.xml', $query->getElapsedSecs(), $query->getQuery());
                          }
                          if ($cfgInstance->debug_long_queries_mode == 'session' || $cfgInstance->debug_long_queries_mode == 'both'){
                              Warecorp_Debug::makeLongQueriesLog(DEBUG_LOG_DIR.'session/long_queries_'.session_id().'.xml', $query->getElapsedSecs(), $query->getQuery());
                          }
                }  
            }
        }
        //----------------------------------------------------------------------------------------------------------------------------------
        if ($cfgInstance->debug_mode == "silent"){

            if (empty($db)) $db = Zend_Registry::get("DB");
            $aProfiler = array();
            if (empty($profiler)) $profiler = $db->getProfiler();

            $aProfiler['total_time']    = $profiler->getTotalElapsedSecs();
            $aProfiler['query_count']   = $profiler->getTotalNumQueries();
            $aProfiler['longest_time']  = 0;
            $aProfiler['longest_query'] = null;

            if (empty($queries)) $queries = $profiler->getQueryProfiles();
            if (!empty($queries))
            foreach ($queries as $_key => $query) {
                $aProfiler['query_'.$_key] = str_replace("\t", " ",(str_replace("\n", " ",str_replace("\r\n", " ",'['.$query->getElapsedSecs().'] - ' . $query->getQuery()))))."\n";
                if ($query->getElapsedSecs() > $aProfiler['longest_time']) {
                    $aProfiler['longest_time'] = $query->getElapsedSecs();
                    $aProfiler['longest_query'] = $query->getQuery();
                }
            }

            $aProfiler['memory_usage'] = memory_get_usage();

            if ($cfgInstance->debug_silent_mode_type == 'global' || $cfgInstance->debug_silent_mode_type == 'both'){
                Warecorp_Debug::makeXMLLog(DEBUG_LOG_DIR.'global.xml', $aProfiler);
            }
            if ($cfgInstance->debug_silent_mode_type == 'session' || $cfgInstance->debug_silent_mode_type == 'both'){
                Warecorp_Debug::makeXMLLog(DEBUG_LOG_DIR.'session/'.session_id().'.xml', $aProfiler);
            }
        } elseif ($cfgInstance->debug_mode == "on" && $display_stat && !defined('TURN_OFF_DEBUG') ){

            if (empty($db)) $db = Zend_Registry::get("DB");
            if (empty($profiler)) $profiler = $db->getProfiler();

            $totalTime    = $profiler->getTotalElapsedSecs();
            $queryCount   = $profiler->getTotalNumQueries();
            $longestTime  = 0;
            $longestQuery = null;

            $profilerType = Zend_Db_Profiler::QUERY  | Zend_Db_Profiler::INSERT | 
                            Zend_Db_Profiler::UPDATE | Zend_Db_Profiler::DELETE | 
                            Zend_Db_Profiler::SELECT | Zend_Db_Profiler::TRANSACTION;
            if ( $profiler->getQueryProfiles( $profilerType ) ) {
                foreach ($profiler->getQueryProfiles( $profilerType ) as $query) {
                    if ($query->getElapsedSecs() > $longestTime) {
                        $longestTime  = $query->getElapsedSecs();
                        $longestQuery = $query->getQuery();
                    }                                         
                }
            }

            echo '<br />-------------------------------------------------------------------<br />';
            echo 'Executed ' . $queryCount . ' queries in ' . $totalTime . ' seconds' . "\n<br />";
            echo 'Average query length: ' . (( $queryCount ) ? $totalTime / $queryCount : 0) . ' seconds' . "\n<br />";
            echo 'Queries per second: ' . (( $totalTime ) ? $queryCount / $totalTime : 0) . "\n<br />";
            echo 'Longest query length: ' . $longestTime . "\n<br />";
            echo "Longest query: \n" . $longestQuery . "\n<br />";
            echo "Memory usage (peak): " .
                Warecorp_Debug::format_memory_usage( memory_get_usage()) .
                " (" . Warecorp_Debug::format_memory_usage( memory_get_peak_usage()) . ")\n<br />";
            echo "Execution time: ".$time;


            $request = Zend_Controller_Front::getInstance()->getRequest();
            self::write(
                array(
                    date('m/d/Y H:i'),
                    BASE_HTTP_HOST,
                    Warecorp::$controllerName,
                    Warecorp::$actionName,
                    Warecorp::$controllerName.'-'.Warecorp::$actionName,
                    $request->getRequestUri(),
                    sprintf("%5.2f", $totalTime),
                    $queryCount,
                    sprintf("%5.2f", $longestTime),
                    str_replace(array("\r\n", "\n", "\r"), " ", $longestQuery),
                    sprintf("%5.2f", memory_get_peak_usage() / Warecorp_Debug::MEGABYTE)
                )
            );
        }
    }

    /**
     * Format memory usage.
     * @author Aleksei Gusev
     */
    public static function format_memory_usage( $i) {
        $megabytes = $i / Warecorp_Debug::MEGABYTE;
        $kylobytes = $i / Warecorp_Debug::KYLOBYTE;

        if ( $megabytes >= 1) {
            return sprintf( "%.2fMB", $megabytes);
        } else {
            return sprintf( "%.2fkB", $kylobytes);
        }
    }
}
