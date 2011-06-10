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

class BaseWarecorp_Export_Excel {

    private $tmpHandle;

    public function  __construct() {
        $this->tmpHandle = tmpfile();
    }

    public function  __destruct() {
        fclose($this->tmpHandle);
    }

    public function xlsBOF() {
        fwrite($this->tmpHandle, pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0));
    }

    public function xlsEOF() {
        fwrite($this->tmpHandle, pack("ss", 0x0A, 0x00));
    }

    public function xlsWriteNumber( $Row, $Col, $Value ) {
        fwrite($this->tmpHandle, pack("sssss", 0x203, 14, $Row, $Col, 0x0));
        fwrite($this->tmpHandle, pack("d", $Value));
    }

    public function xlsWriteLabel( $Row, $Col, $Value ) {
        $L = strlen($Value);
        fwrite($this->tmpHandle, pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L));
        fwrite($this->tmpHandle, $Value);
    }

    public function outputXlsToHttp( $defFileName = 'new_file' )
    {
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');
        header('Content-Disposition: attachment;filename='.$defFileName.'.xls');
        header('Content-Transfer-Encoding: binary');

        rewind($this->tmpHandle);
        while( !feof($this->tmpHandle) ) {
            echo fread($this->tmpHandle, 4096);
        }
        exit(0);
    }
}
