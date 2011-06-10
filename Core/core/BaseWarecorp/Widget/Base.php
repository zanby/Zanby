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
 * @package    Warecorp_Widget_Base
 * @copyright  Copyright (c) 2009
 * @author Alexander Komarovski
 */

abstract class BaseWarecorp_Widget_Base
{
    protected $wtype;
	protected $cloneId;
    protected $containerId;
    protected $params;
    protected $containerType = 'normal';
    protected $width;//=500
    protected $height;//=300
    
    public $baseScriptsToAppendHeader = array();
    public $baseScriptsToAppendApplication = array();
    
    public $paramsAsArray = array();
    
    public function loadParams($params)
    {
    	if (!empty($params['wtype']) ) $this->wtype = $params['wtype'];
        if (!empty($params['wdtype']) && $params['wdtype'] == 'iniframe' ) $this->containerType = 'iniframe';
        if (!empty($params['width']) ) $this->width = intval($params['width']);
        if (!empty($params['height']) ) $this->height = intval($params['height']);
        if (!empty($params['cloneId']) ) $this->cloneId = $params['cloneId'];

        return $this;
    }
    
    public function getParamsAsArray()
    {
        $this->paramsAsArray['wtype'] = $this->wtype;
    	$this->paramsAsArray['cloneId'] = $this->cloneId;
        $this->paramsAsArray['containerId'] = $this->containerId;
        $this->paramsAsArray['containerType'] = $this->containerType;
    	$this->paramsAsArray['width'] = $this->width;
        $this->paramsAsArray['height'] = $this->height;
        $this->paramsAsArray['containerType'] = $this->containerType;
        
        return $this->paramsAsArray;
    }

    abstract function getWriteContainer();
    abstract function getInitFunction();
    
    public function getWType() {
        return $this->wtype;  
    }
    public function getCloneId() {
        return $this->cloneId;  
    }
    public function getContainerId() {
        return $this->containerId;	
    }
    public function getParams() {
        return $this->params;  
    }
    public function getContainerType() {
        return $this->containerType;  
    }
    
    
    public function setType($value) {
        $this->type = $value;  
        return $this;
    }
    public function setCloneId($value) {
        $this->cloneId = $value;  
        return $this; 
    }
    public function setContainerId($value) {
        $this->containerId = $value;  
        return $this; 
    }
    public function setParams($value) {
        $this->params = $value;  
        return $this; 
    }
    public function setContainerType($value) {
        $this->containerType = $value;  
        return $this;  
    }
}
