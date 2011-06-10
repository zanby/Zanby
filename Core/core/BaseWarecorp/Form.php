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
 * Warecorp Form Framework
 * @package Warecorp_Form
 * @author Dmitry Kostikov
 */
class BaseWarecorp_Form
{
    /**
     * name of the form
     * @var string
     */
    public $name = '';

    /**
     * submit method of the form
     * @var enum (get|post)
     */
    public $method = 'post';

    /**
     * submit action of the form
     * @var string
     */
    public $action = '';

    /**
     * Array of default form values
     * @var  array
     */
    public $_defaults = array();

    /**
     * Array containing the form rules
     * @var  array
     */
    private $_rules = array();

    /**
     * Value for maxfilesize hidden element if form contains file input
     * @var  integer
     */
    public $_maxFileSize = 0;

    private $customErrorMessages = array();

    private $isValid = true;
    /**
     * form constructor
     * @param    string      $name          Form's name.
     * @param    string      $method        (optional)Form's method defaults to 'POST'
     * @param    string      $action        (optional)Form's action
     */
    public function __construct($name='', $method='post', $action='')
    {
        $this->name = $name;
        $this->method = strtolower($method);
        $this->action = ($action == '') ? $_SERVER['REQUEST_URI'] : $action;

        if (preg_match('/^([0-9]+)([a-zA-Z]*)$/', ini_get('upload_max_filesize'), $matches))
        switch (strtoupper($matches['2'])) {
            case 'G': $this->_maxFileSize = $matches['1'] * 1073741824; break;
            case 'M': $this->_maxFileSize = $matches['1'] * 1048576;    break;
            case 'K': $this->_maxFileSize = $matches['1'] * 1024;       break;
            default:  $this->_maxFileSize = $matches['1'];
        }
    }
    /**
     * Return true if form has been submitted
     * @author Artem Sukharev
     */
    public function isPostback()
    {
        return isset($_REQUEST['_wf__' . $this->name]);
    }
    /**
     * Return true if form is valid
     * @author Artem Sukharev
     */
    public function isValid()
    {
        return (bool) $this->isValid;
    }
    /**
     * Set for as valid or invalid
     * @author Artem Sukharev
     */
    public function setValid($status = true)
    {
        $this->isValid = (bool) $status;
    }
    /**
     * Return form rules
     * @author Artem Sukharev
     */
    public function getRules()
    {
        return $this->_rules;
    }
    /**
     * Validate form data
     * @param array $params
     * @return bool
     */
    public function validate($params)
    {
        // form not submitted, abort
        if (!$this->isPostback()) return false;
        $error = false;
        // check element rules
        foreach($this->_rules as $element => &$rules){
            $value = isset($params[$element]) ? $params[$element] : null;

            foreach($rules as &$rule) {
                if ('server' == $rule['mode']) {

                    /**
                     *  Bug with "double" error
                     *  @author Ivan Meleshko
                     **/
                    $stop_check = false;
                    if (count($rules) > 1) {
                        foreach($rules as &$rule_check_error) {
                            if ($rule_check_error['error']) {
                                $stop_check = true;
                                break;
                            }
                        }
                    }
                    if ($stop_check) {
                        continue;
                    }
                    switch ($rule['type']){
                        case 'required':
                        	if (is_string($value)) {
                            	if ('' == trim($value)) $rule['error'] = $error = true;
                        	} else {
                        		if (!$value) $rule['error'] = $error = true;
                        	}
                            break;
                        case 'minlength':
                            $value = strip_tags($value);
                             function_exists("mb_strlen") ? $length = mb_strlen((string)$value, "UTF-8") : $length = strlen((string)$value);
                            if ($length < $rule['options']['min']) $rule['error'] = $error = true;
                            break;
                        case 'maxlength':
                            $value = strip_tags($value);
                            function_exists("mb_strlen") ? $length = mb_strlen((string)$value, "UTF-8") : $length = strlen((string)$value);
                            if ($length > $rule['options']['max']) {
                                $rule['error'] = $error = true;
                                var_dump($length,$value);exit;
                            }
                            break;
                        case 'rangelength':
                            $value = strip_tags($value);
                            function_exists("mb_strlen") ? $length = mb_strlen((string)$value, "UTF-8") : $length = strlen((string)$value);
                            if ($length < $rule['options']['min'] || $length > $rule['options']['max'])
                            $rule['error'] = $error = true;
                            break;
                        case 'email':
                            $regex = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/';
                            if (preg_match($regex, $value)) {
                                if (function_exists('checkdnsrr')) {
                                    $tokens = explode('@', $value);
                                    if (!(checkdnsrr($tokens[1], 'MX') || checkdnsrr($tokens[1], 'A')))
                                    $rule['error'] = $error = true;
                                }
                            } else $rule['error'] = $error = true;
                            break;
                        case 'lettersonly':
                            if (!preg_match('/^[a-zA-Z]+$/', $value)) $rule['error'] = $error = true;
                            break;
                        case 'alphanumeric':
                            if (!preg_match('/^[a-zA-Z0-9]+$/', $value)) $rule['error'] = $error = true;
                            break;
                        case 'numeric':
                            if (!preg_match('/(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/', $value)) $rule['error'] = $error = true;
                            break;
                        case 'positive':
                            if (is_numeric($value)) {
                            	if ($value <= 0) $rule['error'] = $error = true;
                            }
                            break;                                                                                                   
                        case 'phone':
                            if (!preg_match('/^\s*\+?[-.\d\s]{3,}$/', $value) && !preg_match('/^\s*\+?[\d\s]+[-.\d\s]{10,}$/', $value) && !preg_match('/((\(\d{3}\) ?)|(\d{3}[- \.]))?\d{3}[- \.]\d{4}(\s(x\d+)?){0,1}$/', $value)) $rule['error'] = $error = true;
                            break;
                        case 'address':
                            if (!preg_match('/^([a-zA-Z0-9\'\`\s\-]+(,|\.|\s)+[a-zA-Z0-9\'\`\s\-]+(,|\.|\s)?)+$/', $value)) $rule['error'] = $error = true;
                            break;
                        case 'nopunctuation':
                            if (!preg_match('/^[^().\/\*\^\?#!@$%+=,\"\'><~\[\]{}]+$/', $value)) $rule['error'] = $error = true;
                            break;
                        case 'nonzero':
                            if (!preg_match('/^-?[1-9][0-9]*/', $value)) $rule['error'] = $error = true;
                            break;
                        case 'regexp':
                            if (!preg_match($rule['options']['regexp'], $value)) $rule['error'] = $error = true;
                            break;
                        case 'callback':
                            $rule['error'] = call_user_func($rule['options']['func'], $rule['options']['params']);
                            if ($rule['error'] === true) $error = true;
                            break;
                        case 'compare':
                            $compareFn = create_function('$a, $b', 'return $a ' . $rule['options']['rule'] . ' $b;');
                            if (!$compareFn($value, $rule['options']['value'])) $rule['error'] = $error = true;
                            break;
                        case 'uploadedfile':
                            if (!is_uploaded_file($rule['options']['tmp_name'])) $rule['error'] = $error = true;
                            break;
                        case 'maxfilesize':
                            if (!empty($elementValue['error']) &&
                            (UPLOAD_ERR_FORM_SIZE == $elementValue['error'] || UPLOAD_ERR_INI_SIZE == $elementValue['error'])) {
                                return false;
                            }
                            return ($maxSize >= @filesize($elementValue['tmp_name']));
                            break;
                        case 'mimetype':
                            if (is_array($mimeType)) {
                                return in_array($elementValue['type'], $mimeType);
                            }
                            return $elementValue['type'] == $mimeType;
                            break;
                        case 'filename':
                            if (!preg_match($rule['options']['regex'], $value)) $rule['error'] = $error = true;
                            break;
                        case 'validdate':
                        	if ( !$rule['options']['month'] || !$rule['options']['day'] || !$rule['options']['year']) $rule['error'] = $error = true;
                            elseif (!checkdate($rule['options']['month'], $rule['options']['day'], $rule['options']['year'])) $rule['error'] = $error = true;
                            break;
                        case 'notempty':
                            if ('' == trim( (string)$value ) ) $rule['error'] = $error = true;
                            break;
                        case 'cardnumber':
                            $value=preg_replace("/\D|\s/", "", $value);
                            $cardlength=strlen($value);
                            $parity=$cardlength % 2;
                            $sum=0;
                            for ($i=0; $i<$cardlength; $i++) {
                                $digit=$value[$i];
                                if ($i%2==$parity) $digit=$digit*2;
                                if ($digit>9) $digit=$digit-9;
                                $sum=$sum+$digit;
                            }
                            if ($sum%10!=0 || $sum==0) $rule['error'] = $error = true;
                            break;
                        case 'cardtypenumber':
//                            $number=preg_replace("/\D|\s/", "", $rule['options']['number']);
                            $number = trim($rule['options']['number']);
                            switch ($rule['options']['type']) {
                                case 'visa':
                                    if (!preg_match("/^4(\d{12}|\d{15})$/", $number)) $rule['error'] = $error = true;
                                    break;
                                case 'mastercard':
                                    if (!preg_match("/^5[1-5]\d{14}$/", $number)) $rule['error'] = $error = true;
                                    break;
                                case 'discover':
                                    if (!preg_match("/(^(6011)\d{12}$)|(^(65)\d{14}$)/", $number)) $rule['error'] = $error = true;
                                    break;
                                case 'amex':
                                    if (!preg_match("/(^3[47])((\d{11}$)|(\d{13}$))/", $number)) $rule['error'] = $error = true;
                                    break;
                                default:
                                    $rule['error'] = $error = true;
                                    break;
                            }
                            break;
                        case 'cid' :
//                            $value=preg_replace("/\D|\s/", "", $value);
                            $value = trim($value);
                            if ($rule['options'] == 'amex' && !preg_match("/^\d{4}$/", $value)) {
                                $rule['error'] = $error = true;
                            } elseif ($rule['options'] !== 'amex' && !preg_match("/^\d{3}$/", $value)) {
                                $rule['error'] = $error = true;
                            }

                            break;
                        case 'expdate' :
//                            $month = preg_replace("/\D|\s/", "", $rule['options']['month']);
//                            $year  = preg_replace("/\D|\s/", "", $rule['options']['year']);
                            $month = trim($rule['options']['month']);
                            $year  = trim($rule['options']['year']);
                            if(!preg_match("/^\d{2}$/", $month) || !preg_match("/^\d{4}$/", $year)) {
                                $rule['error'] = $error = true;
                            } elseif ($month<1 || $month>12 || $year<date("Y") || $year>date("Y")+100 || ($year == date("Y") && $month<date("m"))) {
                                $rule['error'] = $error = true;
                            }
                            break;
                        case 'datecompare' :
                            if (checkdate($value['date_Month'], $value['date_Day'], $value['date_Year'])) {
                                $date = strtotime($value['date_Year'].'-'.$value['date_Month'].'-'.$value['date_Day']);
                                $strValue = $rule['options']['value'];
                                $valueDate = strtotime($strValue['year'].'-'.$strValue['month'].'-'.$strValue['day']);
                                $compareFn = create_function('$a, $b', 'return $a ' . $rule['options']['rule'] . ' $b;');
                                if (!$compareFn($date, $valueDate)) $rule['error'] = $error = true;
                            }
                            break;
                    }
                }
            }
        }
        //proceed customError
        if($this->isValid == false) $error = true; //if set custom error

        // no rules exeption, process
        $this->setValid(!$error);
        return !$error;
    }
    public function getErrorMessages($errors_summary_id = null, $isField = false)
    {
        if ( $errors_summary_id === null ) $errors_summary_id = '__DEFAULT_ERRORS_SUMMARY__';
        $output_errors = array();
        $output_field_errors = array();
        if ( sizeof($this->_rules) != 0 ) {
            foreach ( $this->_rules as $_field => $_rules ) {
                if ( sizeof($_rules) != 0 ) {
                    foreach ( $_rules as $_rule ) {
                        if ( $_rule['error'] && $_rule['errors_summary_id'] == $errors_summary_id ) {
                            $output_errors[] = $_rule['message'];
                            $output_field_errors[$_field][] = $_rule['message'];
                        }
                    }
                }
            }
        }
        if ( $isField ) return $output_field_errors;
        return $output_errors;
    }
    public function getCustomErrorMessages($errors_summary_id = null)
    {
        if ( $errors_summary_id === null ) $errors_summary_id = '__DEFAULT_ERRORS_SUMMARY__';
        if ( isset($this->customErrorMessages[$errors_summary_id]) ) {
            return $this->customErrorMessages[$errors_summary_id];
        } else {
            return array();
        }
    }
    public function addCustomErrorMessage($message, $errors_summary_id = null)
    {
        if ( $errors_summary_id === null ) $errors_summary_id = '__DEFAULT_ERRORS_SUMMARY__';
        $this->customErrorMessages[$errors_summary_id][] = $message;
        $this->setValid(false);
    }
    public function clearCustomErrorMessages($errors_summary_id = null)
    {
        if ( $errors_summary_id === null ) $errors_summary_id = '__DEFAULT_ERRORS_SUMMARY__';
        $this->customErrorMessages[$errors_summary_id] = array();
        $this->setValid(true);
    }
    /**
     * Sets the value of max file size
     * @param     int    $bytes    Size in bytes
     * @return    void
     */
    public function setMaxFileSize($bytes = 0)
    {
        if ($bytes > 0) $this->_maxFileSize = $bytes;
    }

    /**
     * Returns the value of MAX_FILE_SIZE hidden element
     * @return    int   max file size in bytes
     */
    public function getMaxFileSize()
    {
        return $this->_maxFileSize;
    }

    /**
     * Adds a validation rule for the given field
     * If the element is in fact a group, it will be considered as a whole.
     * @param    string     $element   Form element name
     * @param    string     $type      Rule type
     * @param    string     $message   Message to display for invalid data
     * @param    string     $options   (optional)Required for extra rule data
     * @param    string     $mode      (optional)Where to perform validation: "server", "client"
     * @param    string     $errors_summary_id
     */
    public function addRule($element, $type, $message, $options=null, $mode='server', $errors_summary_id = null)
    {
        if (!isset($this->_rules[$element])) $this->_rules[$element] = array();
        if ( $errors_summary_id === null ) $errors_summary_id = '__DEFAULT_ERRORS_SUMMARY__';
        $this->_rules[$element][] = array(
        'type'    => $type,
        'message' => $message,
        'options' => $options,
        'mode'    => $mode,
        'error'   => false,
        'errors_summary_id' => $errors_summary_id
        );
    }

    /**
     * Initializes default form values
     * @param     array    $values       values used to fill the form
     * @return    void
     */
    public function setDefaults($values)
    {
        foreach ($values as $k=>$v) $this->_defaults[$k] = $v;
    }

    /**
     * set options array for select element
     *
     */
    public function setOptions()
    {

    }

    /**
     * Returns the client side validation script
     * @return    string    Javascript to perform validation, empty string if no 'client' rules were added
     */
    public function getValidationScript()
    {
        foreach ($this->_rules as $element => $rules) {
            foreach ($rules as $rule) {
                if ('client' == $rule['mode']) {
                }
            }
        }
        if (count($test) > 0) {
            return
            "<script type='text/javascript'>function validate_" . $this->name . "() {" .
            "  var value = ''; var errFlag = new Array(); var _qfGroups = {}; _qfMsg = '';" .
            "return true;}</script>";
        }
        return '';
    }

    /**
     * Moves an uploaded file into the destination
     * @param    string  form element name
     * @param    string  Destination directory path
     * @param    string  New file name
     * @return   bool    Whether the file was moved successfully
     */
    public function moveUploadedFile($name, $dest, $fileName = '')
    {
        if ($dest != '' && substr($dest, -1) != '/') $dest .= '/';
        $fileName = ($fileName != '') ? $fileName : $name;
        return move_uploaded_file($name, $dest . $fileName);
    }

    /**
     * trim all elements in input array and return it
     *
     * @param $params - array of params
     *
     * @return $params - trimmed array
     * @author Halauniou
     */
    public function trimData($params){
        foreach ($params as &$value){
            $value = trim($value);
        }
        return $params;
    }
}

