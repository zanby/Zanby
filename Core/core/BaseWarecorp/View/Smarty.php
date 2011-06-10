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
 *
 *
 * @copyright  Copyright (c) 2006
 */

require_once SMARTY_DIR.'Smarty.class.php';

class BaseWarecorp_View_Smarty extends Zend_View_Abstract
{
    private $_smarty = false;
    public  $layout = 'main.tpl';
    /**
     * Constructor
     *
     * @param array $data
     * @return void
     */
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->_smarty = new Smarty();
        $this->_smarty->debugging       = false;
    }

    public function setLayout($layout) {
        $this->layout = $layout;
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer
            ->setViewScriptPathSpec($this->layout)
            ->setViewSuffix('tpl');
    }

    public function getSmarty()
    {
        return $this->_smarty;
    }
    /**
     * Assign new variable for smarty
     *
     * @param string|array $var - new variable name
     * @param mixed $value - new variable value
     * @return void
     */
    function assign($var, $value = null)
    {
        if (is_string($var)) {
            $this->_smarty->assign($var, $value);
        } elseif (is_array($var)) {
            foreach ($var as $key => $value) {
                $this->_smarty->assign($key, $value);
            }
        } else {
            throw new Zend_View_Exception('assign() expects a string or array, got '.gettype($var));
        }

    }
    public function __set($var, $val)
    {
        $this->_smarty->assign($var, $val);
    }
    /**
     *  Escapes a value for output in a view script.
     *
     * @param mixed $var - string for escaping
     * @return mixed
     */
    public function escape($var)
    {
        if ( is_string($var) ) {
            return parent::escape($var);
        } elseif ( is_array($var) ) {
            foreach ( $var as $key => $val ) {
                $var[$key] = $this->escape($val);
            }
            return $var;
        } else {
            return $var;
        }
    }
    /**
     *  Processes a view and print the output.
     *
     * @param string $tpl_name - name of template
     * @return void
     */
    public function render($tpl_name)
    {
        $this->_smarty->display($tpl_name);
    }
    /**
     *  Processes a view and returns the output as string.
     *
     * @param string $tpl_name - name of template
     * @return string
     */
    public function getContents($tpl_name)
    {
        return($this->_smarty->fetch($tpl_name));
    }
    /**
     *  Set dirictories for search templates
     *
     * @param string $dir
     * @return void
     */
    public function setTemplatesDir($dir)
    {
        $this->_smarty->template_dir = $dir;
    }
    /**
     * Set dirictories for search complited templates
     *
     * @param string $dir
     */
    public function setCompiledDir($dir)
    {
        $this->_smarty->compile_dir = $dir;
    }
    /**
     * Use to include the view script in a scope that only allows public
     * members.
     *
     * @return mixed
     */
    protected function _run()
    {
    }
}
?>
