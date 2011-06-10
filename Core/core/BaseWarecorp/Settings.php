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
   * @package Warecorp
   * @author Pavel Shutin
   * @version 1.0
   */
class BaseWarecorp_Settings extends Warecorp_Data_Entity
{

    public $pkColName = "context";

    protected $tracer_code = null;
    protected $context = null;
    /**
     * constructor
     * @param int $context - settings context
     */
    public function __construct($context = false)
    {
        parent::__construct('zanby_implementations_settings',
			    array(
				'context'   => 'context',
				'tracer_code' => 'tracer_code'
				)
	    );
        parent::loadByPk($context);
    }

    public function setTracerCode($code) {
        $this->tracer_code = $code;
        return $this;
    }

    public function getTracerCode() {
        return $this->tracer_code;
    }
    
    public function getContext() {
        return $this->context;
    }

    /**
     * save tag object
     * @return boolean
     */
    public function save()
    {
        if (isset($this->context)) {
            // изменяем существующую запись
            $result   = $this->_db->update('zanby_implementations_settings', array('tracer_code' => $this->tracer_code), $this->_db->quoteInto($this->pkColName.'=?', $this->context));
        } else {
            // вставляем новую запись, возвращаем получившийся id
            $result   = $this->_db->insert('zanby_implementations_settings', array('context'=>HTTP_CONTEXT,'tracer_code' => $this->tracer_code));
            $this->context = HTTP_CONTEXT;
        }

        //FORCE TEMPLATE TO RECOMPILE
        return true;
    }
}
