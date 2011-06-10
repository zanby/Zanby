<?php

class xajaxPopup
{
    public $response;
    public $div_id;
    public $title_position;
    public $position_x;
    public $position_y;
    public $width;
    public $height;
    public $style_title;
    public $style_title2;
    public $style_body;
    public $block_layer = false;
    public $title;
    public $body;
    public $_page;
    public $nameMainTemplate = 'main_popup.tpl';

    public function __construct($div_id)
    {
        $this->div_id   = $div_id;
        $this->_page    = Zend_Registry::get("Page");
        $this->_db      = Zend_Registry::get("DB");
        $this->response = new xajaxResponse();
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setStyleTitle($style)
    {
        $this->style_title = $style;
    }

    public function setStyleTitle2($style)
    {
        $this->style_title2 = $style;
    }


    public function setStyleBody($style)
    {
        $this->style_body = $style;
    }

    public function setTemplate($file)
    {
        $this->_page->Template->assign('popup_content', 'title');
        $this->title = $this->_page->Template->getContents($file);
        $this->_page->Template->assign('popup_content', 'body');
        $this->body = $this->_page->Template->getContents($file);
        return true;
    }

    public function setPositionTitle($position)
    {
        $this->title_position = $position;
    }

    public function setPosition($x, $y)
    {
        $this->position_x = $x;
        $this->position_y = $y;
    }

    public function setSize($width, $height)
    {
        $this->width  = $width;
        $this->height = $height;
    }

    public function addBlockLayer()
    {
        $this->block_layer = true;
    }

    public function getClose()
    {
        $this->response->addClear($this->div_id, "innerHTML");
        return $this->response;
    }

    public function getOpen()
    {
        $this->_page->Template->assign('div_id', $this->div_id);
        $this->_page->Template->assign('pos_top', $this->position_y);
        $this->_page->Template->assign('pos_left', $this->position_x);
        $this->_page->Template->assign('width', $this->width.'px');
        $this->_page->Template->assign('height', $this->height.'px');
        $this->_page->Template->assign('style_title', $this->style_title);
        $this->_page->Template->assign('style_title2', $this->style_title);
        $this->_page->Template->assign('style_body', $this->style_body);
        $this->_page->Template->assign('block_layer', $this->block_layer);
        $this->_page->Template->assign('title', $this->title);
        $this->_page->Template->assign('body', $this->body);
        $this->_page->Template->assign('title_position', $this->title_position);

        $js_code = $this->_page->Template->getContents('_design/ajax_popup/create_div.js');

        $html_code = $this->_page->Template->getContents('_design/ajax_popup/'.$this->nameMainTemplate);

        $this->response->addScript($js_code);
        
        
        $this->response->addAssign($this->div_id, 'innerHTML', $html_code);
        if ($this->block_layer)
        {
            $js_block_layer_code = $this->_page->Template->getContents('_design/ajax_popup/block_layer.js');
            $this->response->addScript($js_block_layer_code);
        }
        $this->response->addScript("getPopupCoordinats($this->position_x, $this->position_y, 'main_popup_{$this->div_id}', $this->height, $this->width);");
        return $this->response;
    }

}