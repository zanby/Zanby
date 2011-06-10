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
 * Warecorp Framework
 *
 * @package    Warecorp_Common
 * @copyright  Copyright (c) 2007
 * @author     Dmitry Kostikov
 */

/**
 * data view paging class
 *
 * @package    Warecorp_Common
 * @copyright  Copyright (c) 2007
 * @author Dmitry Kostikov
 */
class BaseWarecorp_Common_Paging
{
    private $_db;

    /**
     * page length
     * @var integer
     */
    public $postPerPage;

    /**
     * all data length
     * @var integer
     */
    public $totPosts;

    /**
     * count of pages
     * @var integer
     */
    public $totPages;

    /**
     * Uri without paging params.
     * @var string
     */
    public $link;

    /**
     * page length
     * @var integer
     */
    public $pageLength;

    public function __construct($totPosts, $postPerPage, $link)
    {
        $this->_db = Zend_Registry::get('DB');

        $this->totPosts    = $totPosts;
        $this->postPerPage = $postPerPage;
        $this->totPages    = ceil($this->totPosts/$this->postPerPage);
        $this->pageLength  = 2;
        $this->link        = $link;
    }

    public function makePaging($currPage)
    {
        $i=0;
        if (floor($currPage)>$this->totPages && $this->totPages>0) header("location: ".$this->link."/page/".$this->totPages."/");
        if (floor($currPage)<1) header("location: ".$this->link."/page/1/");
        
        if ( $this->pageLength > $this->totPages ) {
            $this->pageLength = $this->totPages;
        }

        $str = '<div class="znWidgetInner20 znClearContainer3">';
        $indent = intval($this->pageLength/2);
        $indent_r = $this->pageLength - $indent;

        if ($currPage + $indent_r >= $this->totPages) {
            $start = $currPage - ($this->pageLength - ($this->totPages - $currPage) + 1);
        } else {
            $start = $currPage - $indent;
        }

        if ($start <= 1 ) {
            $start=2;
        }
        
        $fromTo = (($currPage-1)*$this->postPerPage+1). "-" .($currPage==$this->totPages ? $this->totPosts : $currPage*$this->postPerPage);
        $str.= '<div class="znPaginator znTColor9">'.$fromTo.' of '.$this->totPosts.' total</div><div class="znPagination">';
        
        if ($start > 2) {
            $str.= '<a href="'.$this->link.'/page/'.($currPage-1).'/">&lt; Prev</a>&nbsp;&nbsp;&nbsp; <a href="'.$this->link.'/page/1/">1</a>..';
        }else {
            if ($currPage==1) {
                $str.= '<a class="znPCurrent">1</a> '; // $str.= '<a class="znPCurrent">&lt; Prev</a><a class="znPCurrent">1</a> ';
            } else {
                $str.= '<a href="'.$this->link.'/page/'.($currPage-1).'/">&lt; Prev</a>&nbsp;&nbsp;&nbsp; <a href="'.$this->link.'/page/1/">1</a> ';
            }
        }
        if ($this->totPages > 2) {
            if ($currPage-$indent > 0) {
                $finish = $start+$this->pageLength;
                if ($finish >= $this->totPages) {
                    $finish=$this->totPages-1;
                }
                for ($i=$start; $i<= $finish; $i++) {
                    if ($currPage == $i) {
                        $str .= '<a class="znPCurrent">' . $i . '</a> ';
                    }else {
                        $str .= "<a href='" . $this->link . "/page/" . $i . "/'>" . $i . "</a> ";
                    }
                }
            }else {

                $finish = $start+$this->pageLength;
                if ($finish >= $this->totPages) {
                    $finish=$this->totPages-1;
                }
                for ($i=2; $i<=$finish; $i++) {
                    if ($currPage == $i) {
                        $str .= '<a class="znPCurrent">' . $i . "</a> ";
                    } else {
                        $str .= "<a href='" . $this->link . "/page/" . $i ."/'>" . $i . "</a> ";
                    }
                }
            }
        }

        if ($i < $this->totPages  && $i>0) {
            $str.= " ..<a href='".$this->link."/page/".$this->totPages."/'>".$this->totPages.'</a> &nbsp;&nbsp;&nbsp;<a href="'.$this->link.'/page/'.($currPage+1).'/">Next &gt;</a>';
        }else {
            if ($currPage==$this->totPages) {
                $str.='<a class="znPCurrent">'.$this->totPages.'</a>'; // $str.='<a class="znPCurrent">'.$this->totPages.'</a> <a class="znPCurrent">Next &gt;</a>';
            }else {
                $str.= " <a href='".$this->link."/page/".$this->totPages."/'>".$this->totPages.'</a> &nbsp;&nbsp;&nbsp;<a href="'.$this->link.'/page/'.($currPage+1).'/">Next &gt;</a>';
            }
        }
        

        if ($this->totPosts==0) {
            return '<div class="znWidgetInner20 znClearContainer3"><div class="znPaginator znTColor9">&nbsp;</div><div class="znPagination">&nbsp;</div></div>';
        }elseif ($this->totPages<2) {
            return '<div class="znWidgetInner20 znClearContainer3"><div class="znPaginator znTColor9">'.$fromTo.' of '.$this->totPosts.' total</div><div class="znPagination"></div></div>';
        } else {
			$str.="</div></div>";
            return $str;
        }
    }
    
    public function makeInfoPaging($currPage)
    {
        
        $fromTo = (($currPage-1)*$this->postPerPage+1). "-" .($currPage==$this->totPages ? $this->totPosts : $currPage*$this->postPerPage);
        if ($this->totPosts==0) {
            return '&nbsp;';
        }else {
            return $fromTo.' of '.$this->totPosts.' total';
        }
    }
    
    public function makeLinkPaging($currPage, $activeClassName = 'znPCurrent')
    {
        $i=0;
        if (floor($currPage)>$this->totPages && $this->totPages>0) header("location: ".$this->link."/page/".$this->totPages."/");
        if (floor($currPage)<1) header("location: ".$this->link."/page/1/");
        
        if ( $this->pageLength > $this->totPages ) {
            $this->pageLength = $this->totPages;
        }

        $str = '<div class="znWidgetInner20 znClearContainer3">';
        $indent = intval($this->pageLength/2);
        $indent_r = $this->pageLength - $indent;

        if ($currPage + $indent_r >= $this->totPages) {
            $start = $currPage - ($this->pageLength - ($this->totPages - $currPage) + 1);
        } else {
            $start = $currPage - $indent;
        }

        if ($start <= 1 ) {
            $start=2;
        }
        
        $fromTo = (($currPage-1)*$this->postPerPage+1). "-" .($currPage==$this->totPages ? $this->totPosts : $currPage*$this->postPerPage);
        
        if ($start > 2) {
            $str.= '<a href="'.$this->link.'/page/'.($currPage-1).'/">&lt; Prev</a>&nbsp;&nbsp;&nbsp; <a href="'.$this->link.'/page/1/">1</a>..';
        }else {
            if ($currPage==1) {
                $str.= '<a class="'.$activeClassName.'">1</a> '; // $str.= '<a class="znPCurrent">&lt; Prev</a><a class="znPCurrent">1</a> ';
            } else {
                $str.= '<a href="'.$this->link.'/page/'.($currPage-1).'/">&lt; Prev</a>&nbsp;&nbsp;&nbsp; <a href="'.$this->link.'/page/1/">1</a> ';
            }
        }
        if ($this->totPages > 2) {
            if ($currPage-$indent > 0) {
                $finish = $start+$this->pageLength;
                if ($finish >= $this->totPages) {
                    $finish=$this->totPages-1;
                }
                for ($i=$start; $i<= $finish; $i++) {
                    if ($currPage == $i) {
                        $str .= '<a class="'.$activeClassName.'">' . $i . '</a> ';
                    }else {
                        $str .= "<a href='" . $this->link . "/page/" . $i . "/'>" . $i . "</a> ";
                    }
                }
            }else {

                $finish = $start+$this->pageLength;
                if ($finish >= $this->totPages) {
                    $finish=$this->totPages-1;
                }
                for ($i=2; $i<=$finish; $i++) {
                    if ($currPage == $i) {
                        $str .= '<a class="'.$activeClassName.'">' . $i . "</a> ";
                    } else {
                        $str .= "<a href='" . $this->link . "/page/" . $i ."/'>" . $i . "</a> ";
                    }
                }
            }
        }

        if ($i < $this->totPages  && $i>0) {
            $str.= " ..<a href='".$this->link."/page/".$this->totPages."/'>".$this->totPages.'</a> &nbsp;&nbsp;&nbsp;<a href="'.$this->link.'/page/'.($currPage+1).'/">Next &gt;</a>';
        }else {
            if ($currPage==$this->totPages) {
                $str.='<a class="'.$activeClassName.'">'.$this->totPages.'</a>'; // $str.='<a class="znPCurrent">'.$this->totPages.'</a> <a class="znPCurrent">Next &gt;</a>';
            }else {
                $str.= " <a href='".$this->link."/page/".$this->totPages."/'>".$this->totPages.'</a> &nbsp;&nbsp;&nbsp;<a href="'.$this->link.'/page/'.($currPage+1).'/">Next &gt;</a>';
            }
        }
        
        if ($this->totPages<2) {
            return '';
        } else {
			$str.="</div>";
            return $str;
        }
    }
    
    public function makeAjaxLinkPaging($currPage, $ajaxPrefix, $ajaxPostfix, $activeClassName = 'znPCurrent')
    {
        $i=0;
        if (floor($currPage)>$this->totPages && $this->totPages>0) return ;//header("location: ".$this->link."/page/".$this->totPages."/");
        if (floor($currPage)<1) return ;// header("location: ".$this->link."/page/1/");
        
        if ( $this->pageLength > $this->totPages ) {
            $this->pageLength = $this->totPages;
        }

        $str = '<div class="znWidgetInner20 znClearContainer3">';
        $indent = intval($this->pageLength/2);
        $indent_r = $this->pageLength - $indent;

        if ($currPage + $indent_r >= $this->totPages) {
            $start = $currPage - ($this->pageLength - ($this->totPages - $currPage) + 1);
        } else {
            $start = $currPage - $indent;
        }

        if ($start <= 1 ) {
            $start=2;
        }
        
        $fromTo = (($currPage-1)*$this->postPerPage+1). "-" .($currPage==$this->totPages ? $this->totPosts : $currPage*$this->postPerPage);
        
        if ($start > 2) {
            $str.= "<a href=\"#null\" onclick=\"".$ajaxPrefix.($currPage-1).$ajaxPostfix."\">&lt; Prev</a>&nbsp;&nbsp;&nbsp; <a href=\"#null\" onclick=\"".$ajaxPrefix.'1'.$ajaxPostfix."\">1</a>..";
        }else {
            if ($currPage==1) {
                $str.= '<a class="'.$activeClassName.'">1</a> '; // $str.= '<a class="znPCurrent">&lt; Prev</a><a class="znPCurrent">1</a> ';
            } else {
                $str.= "<a href=\"#null\" onclick=\"".$ajaxPrefix.($currPage-1).$ajaxPostfix."\">&lt; Prev</a>&nbsp;&nbsp;&nbsp; <a href=\"#null\" onclick=\"".$ajaxPrefix.'1'.$ajaxPostfix."\">1</a> ";
            }
        }
        if ($this->totPages > 2) {
            if ($currPage-$indent > 0) {
                $finish = $start+$this->pageLength;
                if ($finish >= $this->totPages) {
                    $finish=$this->totPages-1;
                }
                for ($i=$start; $i<= $finish; $i++) {
                    if ($currPage == $i) {
                        $str .= '<a class="'.$activeClassName.'">' . $i . '</a> ';
                    }else {
                        $str .= "<a href=\"#null\" onclick=\"".$ajaxPrefix.$i.$ajaxPostfix."\">" . $i . "</a> ";
                    }
                }
            }else {

                $finish = $start+$this->pageLength;
                if ($finish >= $this->totPages) {
                    $finish=$this->totPages-1;
                }
                for ($i=2; $i<=$finish; $i++) {
                    if ($currPage == $i) {
                        $str .= '<a class="'.$activeClassName.'">' . $i . "</a> ";
                    } else {
                        $str .= "<a href=\"#null\" onclick=\"".$ajaxPrefix.$i.$ajaxPostfix."\">" . $i . "</a> ";
                    }
                }
            }
        }

        if ($i < $this->totPages  && $i>0) {
            $str.= " ..<a href=\"#null\" onclick=\"".$ajaxPrefix.$this->totPages.$ajaxPostfix."\">".$this->totPages."</a> &nbsp;&nbsp;&nbsp;<a href=\"#null\" onclick=\"".$ajaxPrefix.($currPage+1).$ajaxPostfix."\">Next &gt;</a>";
        }else {
            if ($currPage==$this->totPages) {
                $str.='<a class="'.$activeClassName.'">'.$this->totPages.'</a>'; // $str.='<a class="znPCurrent">'.$this->totPages.'</a> <a class="znPCurrent">Next &gt;</a>';
            }else {
                $str.= " <a href=\"#null\" onclick=\"".$ajaxPrefix.$this->totPages.$ajaxPostfix."\">".$this->totPages."</a> &nbsp;&nbsp;&nbsp;<a href=\"#null\" onclick=\"".$ajaxPrefix.($currPage+1).$ajaxPostfix."\">Next &gt;</a>";
            }
        }
        
        if ($this->totPages<2) {
            return '';
        } else {
			$str.="</div>";
            return $str;
        }
    }
}
