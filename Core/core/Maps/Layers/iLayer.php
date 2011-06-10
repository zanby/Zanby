<?php

/**
  * @version 1.0
  */
interface Maps_Layers_iLayer
{
    public function getUrl();
    
    public function getWmsLayer();
        
    public function getOpacity();
    
    public function getCopyright();
    
    public function getAsArray();
    
    public function getJSlinks();
}