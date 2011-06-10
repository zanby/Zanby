<?php 

class Maps_Layers_Item implements Maps_Layers_iLayer {
    
    protected $_url;
    protected $_wmsLayer;
    protected $_opacity;
    protected $_copyright;
    protected $_jsLinks = null;
    
    function _construct()
    {
        
    }
    
    function loadLayerData(array $params)
    {
        if (isset($params['url']) )        { $this->setUrl($params['url']);             } else { throw new Exception('There is no URL!');       }
        if (isset($params['wmsLayer']) )   { $this->setWmsLayer($params['wmsLayer']);   } else { throw new Exception('There is no wmsLayer!');  }
        if (isset($params['opacity']) )    { $this->setOpacity($params['opacity']);     } else { throw new Exception('There is no opacity!');   }
        if (isset($params['copyright']) )  { $this->setCopyright($params['copyright']); } else { throw new Exception('There is no copyright!'); }
    }
    
    public function setUrl($value){
        $this->_url = $value;
        return $this;           
    }
    
    public function getUrl(){
        return $this->_url;
    }
    
    public function setWmsLayer($value){
        $this->_wmsLayer = $value;
        return $this;           
    }
    
    public function getWmsLayer(){
        return $this->_wmsLayer;
    }
    
    public function setOpacity($value){
        $this->_opacity = $value;
        return $this;           
    }
    
    public function getOpacity(){
        return $this->_opacity;
    }

    public function setCopyright($value){
        $this->_copyright = $value;
        return $this;           
    }
    
    public function getCopyright(){
        return $this->_copyright;
    }
    
    public function getAsArray(){
        return array('url' => (string)$this->_url, 'wmsLayer' => (string)$this->_wmsLayer , 'opacity' => (string)$this->_opacity, 'copyright' => (string)$this->_copyright);
    }
    
    public function setJSlinks($value){
        $this->_jsLinks = $value;
        return $this;
    }
    
    public function getJSlinks(){
        return $this->_jsLinks;
    }
}