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
 * @package    Warecorp_Group_Simple
 * @copyright  Copyright (c) 2006
 * @author     Halauniou Yauhen
 */

/**
 * Class for File processing
 *
 */
class BaseWarecorp_File_Item
{
    /**
     * check is uploaded file is image
     * @param $fileName - Uploaded file name
     * @param $filePath - path to uploaded file
     * @param bool true/false
     */
    static function isImage($fileName, $filePath)
    {
        //$valid_extension = array("jpg", "jpeg", "gif", "png");
        $valid_extension = Warecorp_File_Enum_Extensions::getInArrayMode(Warecorp_File_Enum_Extensions::IMAGES);

        if (file_exists($filePath)){
            $parts = explode(".", $fileName);
            $extension = strtolower($parts[count($parts)-1]);

            if (in_array($extension, $valid_extension)){
                @$sourceInfo = getimagesize($filePath);
                $format = strtolower(substr($sourceInfo["mime"], strpos($sourceInfo["mime"], '/')+1));
                if (in_array($format, $valid_extension)){
                    return array($sourceInfo[0], $sourceInfo[1]);
                }
            }
        }
        return false;
    }
    
    /**
     * check is uploaded file is video
     * @param $fileName - Uploaded file name
     * @param $filePath - path to uploaded file
     * @param bool true/false
     */
    static function isVideo($fileName, $filePath)
    {
        $valid_extension = Warecorp_File_Enum_Extensions::getInArrayMode(Warecorp_File_Enum_Extensions::VIDEOS);

        if (file_exists($filePath)){
            $parts = explode(".", $fileName);
            $extension = strtolower($parts[count($parts)-1]);

            if (in_array($extension, $valid_extension)){
                if (new ffmpeg_movie($filePath)) return true;
            }
        }
        return false;
    }
        
    /**
     * Проверяет, существует ли файл (файлы), пришедшие для аплоада
     * @param string $nameRegexp - регуляр-соответствие имени
     * @return bool
     */
    static function filesIssetForUpload($nameRegexp = null)
    {
        $isset = false;
        if ( !isset($_FILES) || sizeof($_FILES) == 0 ) return false;
        foreach ( $_FILES as $_name => $_file ) {
            if ( $nameRegexp !== null ) {
                if ( preg_match($nameRegexp, $_name, $match) ) {
                    if ( $_file['name'] != '' && $_file['tmp_name'] != '' ) $isset = true;
                }
            } else {
                if ( $_file['name'] != '' && $_file['tmp_name'] != '' ) $isset = true;
            }
        }
        return $isset;
    }
    /**
     * Upload file
     * @param string $from - full filepath
     * @param string $to - full filepath
     * @return bool - result
     */
    static function uploadFile($from, $to)
    {
        if ( is_uploaded_file($from) ) {
            if ( @move_uploaded_file($from, $to) ) return true;
        }
        return false;
    }
    /**
     * Return extension of file
     * @param string $filename - name or path of file
     * @return string
     */
    static function getFileExt($filename)
    {
        $filename = basename($filename);
        $ext = explode("\.", $filename);
        return ( sizeof($ext) > 1 ) ? strtolower($ext[sizeof($ext) - 1]) : '';
    }
    
    /**
     * Return max file size in bytes
     */
    static function getMaxFileSize()
    {
        $_maxFileSize = 0;
        if (preg_match('/^([0-9]+)([a-zA-Z]*)$/', ini_get('upload_max_filesize'), $matches)) {
            // see http://www.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
            switch (strtoupper($matches['2'])) {
                case 'G':
                    $_maxFileSize = $matches['1'] * 1073741824;
                    break;
                case 'M':
                    $_maxFileSize = $matches['1'] * 1048576;
                    break;
                case 'K':
                    $_maxFileSize = $matches['1'] * 1024;
                    break;
                default:
                    $_maxFileSize = $matches['1'];
            }
        }
        return $_maxFileSize;
    }
}
