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
 * Base class for import csv-files
 *
 * @author Alexey Loshkarev
 */

class BaseWarecorp_Import_File_Csv extends Warecorp_Import_File_Base
{

    public $fieldEmail = "E-mail Address";
    public $fieldFirstname = "First Name";
    public $fieldLastname = "Last Name";
    public $separator = ',';
    public $enclosure = '"';
    public $data = array();


    public function __construct($type, $filename, $encoding = false)
    {
        parent::__construct($type, $filename, $encoding);
        $this->load();

        if ($encoding) {
            $this->encoding = $encoding;
        }
    }

    /**
     * Loads data from file
     *
     * @return void
     *
     * @author Alexey Loshkarev
     */
    public function load()
    {

        //dump(file_exists($this->filename), $this->filename);
        //dump(iconv("WINDOWS-1251", "UTF-8", file_get_contents($this->filename)));

        //  Check file
        if ( !(is_readable($this->filename) && filesize($this->filename)) ) {
            $this->fields = array();
            return;
        }

        $f = fopen($this->filename, "r");
        $this->separator =(false===strpos(fgets($f, 4096),';'))?',':';';
        rewind($f);

        // we must set some 8-bit locale to make fgetcsv() work with non-ascii data on separator=';'
        $locale = setlocale(LC_ALL,0);
        setlocale(LC_ALL, 'en_US');
        $this->data = array();

        $csv = fgetcsv($f, 0, $this->separator, $this->enclosure);
        $this->fields = $csv;
        if ( is_array($this->fields) && !empty($this->fields) ) {
            $this->_fieldsFlipped = array_flip($csv);
        }
        else {
            $this->fields         = array();
            $this->_fieldsFlipped = array();
            setlocale(LC_ALL, $locale);
            return;
        }

        if (in_array($this->fieldEmail,     $this->fields)   &&
            in_array($this->fieldFirstname, $this->fields)   &&
            in_array($this->fieldLastname,  $this->fields) )
        {
            while($csv = fgetcsv($f, 0, $this->separator, $this->enclosure)) {
                $this->data[] = $csv;
            }
        }

        // rollback locale
        setlocale(LC_ALL, $locale);
    }

    public function value($row, $field, $applyEncoding = false)
    {

        $encoding = ($applyEncoding) ? $applyEncoding : $this->encoding;
        return @iconv($encoding, SITE_ENCODING, $this->data[$row][$this->_fieldsFlipped[$field]]);

    }

    public function rowCount()
    {
        return count($this->data);
    }


}

