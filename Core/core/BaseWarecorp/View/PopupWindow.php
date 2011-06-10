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

class BaseWarecorp_View_PopupWindow
{
    static protected $instance;
    /**
     * @var string
     */
    private $_title;
    private $_width;
    private $_height;
    private $_content;
    private $_target;
    private $_modal;
    private $_close_by_click_around;    //  window close by mouse click near the popup, default in thickbox.js
    private $_close_by_esc;             //  window by Esc, default in thickbox.js
    private $_fixed;                    //  window fixed at display center, default in thickbox.js

    /**
     *
     */
    static public function getInstance()
    {
        if ( null === self::$instance ) self::$instance = new Warecorp_View_PopupWindow();
        return self::$instance;
    }

    /**
     *
     */
    protected function __construct()
    {
    }

    /**
     * @param unknown_type $content
     */
    public function target( $target = null )
    {
        if ( null === $target ) return $this->_target;
        else {
            $this->_target = $target;
            $this->_content = null;
            return $this;
        }
    }

    /**
     * @param unknown_type $content
     */
    public function content( $content = null )
    {
        if ( null === $content ) return $this->_content;
        else {
            $this->_content = $content;
            $this->_target = null;
            return $this;
        }
    }

    /**
     * @param unknown_type $height
     */
    public function height( $height = null )
    {
        if ( null === $height ) return $this->_height;
        else {
            $this->_height = (int) $height;
            return $this;
        }
    }

    /**
     * @param unknown_type $modal
     */
    public function modal( $modal = null )
    {
        if ( null === $modal ) return $this->_modal;
        else {
            $this->_modal = (boolean) $modal;
            return $this;
        }
    }

    /**
     *  @param boolean $bool
     */
    public function closeByClickAround( $bool = null )
    {
        if ( null === $bool ) return $this->_close_by_click_around;
        $this->_close_by_click_around = (boolean)$bool;
        return $this;
    }

    /**
     *  @param boolean $bool
     */
    public function closeByEsc( $bool = null )
    {
        if ( null === $bool ) return $this->_close_by_esc;
        $this->_close_by_esc = (boolean)$bool;
        return $this;
    }

    /**
     *  @param boolean $bool, TRUE - popup fixed at center display, FALSE - popup scrolling with document
     */
    public function fixed( $bool = null )
    {
        if ( null === $bool ) return $this->_fixed;
        $this->_fixed = (boolean)$bool;
        return $this;
    }

    /**
     * @param string $title
     */
    public function title( $title = null )
    {
        if ( null === $title ) return $this->_title;
        else {
            $this->_title = $title;
            return $this;
        }
    }

    /**
     * @param unknown_type $width
     */
    public function width( $width = null )
    {
        if ( null === $width ) return $this->_width;
        else {
            $this->_width = (int) $width;
            return $this;
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $value
     * @return unknown
     */
    private function jsPrepare($value)
    {
        /*
        $value = mb_convert_encoding($value, 'HTML-ENTITIES', 'utf-8');
        $value = str_replace(array("\n", "\r"), '', $value);
        $value = str_replace(array("'"), "\'", $value);
        */
        $value = Zend_Json::encode($value);
        return $value;
    }

    /**
     *
     */
    public function open(&$objResponse)
    {
        $Title = $this->jsPrepare($this->title());
        $objResponse->addScript('popup_window.title('.$Title.');');
        if ( $this->width() ) $objResponse->addScript('popup_window.width('.$this->width().');');
        if ( $this->height() ) $objResponse->addScript('popup_window.height('.$this->height().');');

        if      ( $this->closeByClickAround() === false ) $objResponse->addScript('popup_window.closeByClickAround(false);');
        elseif  ( $this->closeByClickAround() === true )  $objResponse->addScript('popup_window.closeByClickAround(true);');
        if      ( $this->closeByEsc() === false ) $objResponse->addScript('popup_window.closeByEsc(false);');
        elseif  ( $this->closeByEsc() === true  ) $objResponse->addScript('popup_window.closeByEsc(true);');
        if      ( $this->modal() === true )  $objResponse->addScript('popup_window.modal(true);');
        elseif  ( $this->modal() === false ) $objResponse->addScript('popup_window.modal(false);');
        if      ( $this->fixed() === true )  $objResponse->addScript('popup_window.fixed(true);');
        elseif  ( $this->fixed() === false ) $objResponse->addScript('popup_window.fixed(false);');

        if ( $this->content() ) {
            $Content = $this->jsPrepare($this->content());
            $objResponse->addScript('popup_window.content('.$Content.');');
        } elseif( $this->target() ) {
            $objResponse->addScript('popup_window.target("'.$this->target().'");');
        }
        $objResponse->addScript('popup_window.open();');
    }

    /**
     *
     */
    public function reload(&$objResponse)
    {
        $objResponse->addScript('popup_window.isreload(true);');
        $this->open($objResponse);
    }

    /**
     *
     */
    public function close(&$objResponse)
    {
        $objResponse->addScript('popup_window.close();');
    }

}
