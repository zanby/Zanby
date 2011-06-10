<?php
/**
 * Warecorp FRAMEWORK
 *
 * @package    Maps_Base
 * @copyright  Copyright (c) 2010
 * @author Michael Pianko
 */

abstract class Maps_Base
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
        
    public function appendScripts() {
        foreach ($this->baseScriptsToAppendApplication as $fileUrl) {
            $this->output.="document.write('<script type=\"text/javascript\" src=\"".$fileUrl."\"></script>');";
        }
        foreach ($this->scriptsToAppendApplication as $fileUrl) {
            $this->output .= "document.write('<script type=\"text/javascript\" src=\"".$fileUrl."\"></script>');";
        }        
    }
    
    public function appendHeaderScripts() {
        $output = '';
        foreach ($this->baseScriptsToAppendHeader as $fileUrl) {
            $output.="<script type=\"text/javascript\" src=\"".$fileUrl."\"></script>";
        }
        foreach ($this->scriptsToAppendHeader as $fileUrl) {
            $output.="<script type=\"text/javascript\" src=\"".$fileUrl."\"></script>";
        }
        return $output;
    }
}
