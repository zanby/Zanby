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
class BaseWarecorp_Common_PagingProduct
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
     * String for generate additioonal information in paging output
     * (add "for 'search phrase'" in the end of paging block)
     * @var string
     */
    private $_searchPhrase;

    /**
     * page length
     * @var integer
     */
    public $pageLength;

    public function __construct($totPosts, $postPerPage, $link)
    {
        $this->_db = Zend_Registry::get('DB');

        $this->totPosts      = $totPosts;
        $this->postPerPage   = $postPerPage;
        $this->totPages      = ceil($this->totPosts/$this->postPerPage);
        $this->pageLength    = 2;
        $this->link          = $link;
        $this->_searchPhrase = null;
    }

    /**
     * @param string $phrase which used for search
     */
    public function setSearchPhrase( $phrase )
    {
        $this->_searchPhrase = $phrase;
        return $this;
    }

    public function getSearchPhrase()
    {
        return $this->_searchPhrase;
    }

    public function clearSearchPhrase()
    {
        $this->_searchPhrase = null;
        return $this;
    }

    public function makePaging($currPage)
    {
        $i=0;
        if (floor($currPage)>$this->totPages && $this->totPages>0) header("location: ".$this->link."/page/".$this->totPages."/");
        if (floor($currPage)<1) header("location: ".$this->link."/page/1/");

        if ( $this->pageLength > $this->totPages ) {
            $this->pageLength = $this->totPages;
        }

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
        $str = '<span>'.$fromTo.' of '.$this->totPosts.' total'.(($this->_searchPhrase === null) ? '' : " for &quot;{$this->getSearchPhrase()}&quot;").'</span><div class="prBlockPaginator"><ul class="prPaginator">';

        if ($start > 2) {
            $str.= '<li><a href="'.$this->link.'/page/'.($currPage-1).'/">&lt;</a></li><li><a href="'.$this->link.'/page/1/">1</a></li><li><a class="prNoBorder">...</a></li>';
        }else {
            if ($currPage==1) {
                $str.= '<li><a class="prActivePage">1</a></li>';
            } else {
                $str.= '<li><a href="'.$this->link.'/page/'.($currPage-1).'/">&lt;</a></li><li><a href="'.$this->link.'/page/1/">1</a></li>';
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
                        $str .= '<li><a class="prActivePage">'. $i .'</a></li>';
                    }else {
                        $str .= "<li><a href='" . $this->link . "/page/" . $i . "/'>" . $i . "</a></li>";
                    }
                }
            }else {

                $finish = $start+$this->pageLength;
                if ($finish >= $this->totPages) {
                    $finish=$this->totPages-1;
                }
                for ($i=2; $i<=$finish; $i++) {
                    if ($currPage == $i) {
                        $str .= '<li><a class="prActivePage">'. $i .'</a></li>';
                    } else {
                        $str .= "<li><a href='" . $this->link . "/page/" . $i . "/'>" . $i . "</a></li>";
                    }
                }
            }
        }

        if ($i < $this->totPages  && $i>0) {
            $str.= "<li><a class='prNoBorder'>...</a></li><li><a href='".$this->link."/page/".$this->totPages."/'>".$this->totPages.'</a></li><li><a href="'.$this->link.'/page/'.($currPage+1).'/">&gt;</a></li>';
        }else {
            if ($currPage==$this->totPages) {
                $str.='<li><a class="prActivePage">'.$this->totPages.'</a></li>'; // $str.='<a class="znPCurrent">'.$this->totPages.'</a> <a class="znPCurrent">Next &gt;</a>';
            }else {
                $str.= "<li><a href='".$this->link."/page/".$this->totPages."/'>".$this->totPages.'</a></li><li><a href="'.$this->link.'/page/'.($currPage+1).'/">&gt;</a></li>';
            }
        }


        if ($this->totPosts==0) {
            return '';
        }elseif ($this->totPages<2) {
            return '<div class="prBlockPaginator"><span>'.$fromTo.' of '.$this->totPosts.' total'.(($this->_searchPhrase === null) ? '' : " for &quot;{$this->getSearchPhrase()}&quot;").'</span></div>';
        } else {
			$str.="</ul></div>";
            return $str;
        }
    }

    public function makeInfoPaging($currPage)
    {

        $fromTo = (($currPage-1)*$this->postPerPage+1). "-" .($currPage==$this->totPages ? $this->totPosts : $currPage*$this->postPerPage);
        if ($this->totPosts==0) {
            return '&nbsp;';
        }else {
            return $str = '<span>'.$fromTo.' of '.$this->totPosts.' total'.(($this->_searchPhrase === null) ? '' : " for &quot;{$this->getSearchPhrase()}&quot;").'</span>';
        }
    }

    public function makeLinkPaging($currPage, $activeClassName = 'prActivePage')
    {
        $i=0;
        if (floor($currPage)>$this->totPages && $this->totPages>0) header("location: ".$this->link."/page/".$this->totPages."/");
        if (floor($currPage)<1) header("location: ".$this->link."/page/1/");

        if ( $this->pageLength > $this->totPages ) {
            $this->pageLength = $this->totPages;
        }

        $str = '<div class="prBlockPaginator"><ul class="prPaginator">';
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
            $str.= '<li><a href="'.$this->link.'/page/'.($currPage-1).'/">&lt;</a></li><li><a href="'.$this->link.'/page/1/">1</a></li><li><a class="prNoBorder">...</a></li>';
        }else {
            if ($currPage==1) {
                $str.= '<li><a class="'.$activeClassName.'">1</a></li>'; // $str.= '<a class="znPCurrent">&lt; Prev</a><a class="znPCurrent">1</a> ';
            } else {
                $str.= '<li><a href="'.$this->link.'/page/'.($currPage-1).'/">&lt;</a></li><li><a href="'.$this->link.'/page/1/">1</a></li>';
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
                        $str .= '<li><a class="'.$activeClassName.'">' . $i . '</a></li>';
                    }else {
                        $str .= "<li><a href='" . $this->link . "/page/" . $i . "/'>" . $i . "</a><li>";
                    }
                }
            }else {

                $finish = $start+$this->pageLength;
                if ($finish >= $this->totPages) {
                    $finish=$this->totPages-1;
                }
                for ($i=2; $i<=$finish; $i++) {
                    if ($currPage == $i) {
                        $str .= '<li><a class="'.$activeClassName.'">' . $i . "</a></li>";
                    } else {
                        $str .= "<li><a href='" . $this->link . "/page/" . $i ."/'>" . $i . "</a></li>";
                    }
                }
            }
        }

        if ($i < $this->totPages  && $i>0) {
            $str.= "<li><a class='prNoBorder'>...</a></li><li><a href='".$this->link."/page/".$this->totPages."/'>".$this->totPages.'</a></li><li><a href="'.$this->link.'/page/'.($currPage+1).'/">&gt;</a></li>';
        }else {
            if ($currPage==$this->totPages) {
                $str.='<li><a class="'.$activeClassName.'">'.$this->totPages.'</a></li>'; // $str.='<a class="znPCurrent">'.$this->totPages.'</a> <a class="znPCurrent">Next &gt;</a>';
            }else {
                $str.= "<li><a href='".$this->link."/page/".$this->totPages."/'>".$this->totPages.'</a></li><li><a href="'.$this->link.'/page/'.($currPage+1).'/">&gt;</a></li>';
            }
        }

        if ($this->totPages<2) {
            return '';
        } else {
			$str.="</ul></div>";
            return $str;
        }
    }

    public function makeAjaxLinkPaging($currPage, $ajaxPrefix, $ajaxPostfix, $activeClassName = 'prActivePage')
    {
        $i=0;
        if (floor($currPage)>$this->totPages && $this->totPages>0) return ;//header("location: ".$this->link."/page/".$this->totPages."/");
        if (floor($currPage)<1) return ;// header("location: ".$this->link."/page/1/");

        if ( $this->pageLength > $this->totPages ) {
            $this->pageLength = $this->totPages;
        }

        $str = '<div class="prBlockPaginator"><ul class="prPaginator">';
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
            $str.= "<li><a href=\"#null\" onclick=\"".$ajaxPrefix.($currPage-1).$ajaxPostfix."\">&lt;</a></li><li><a href=\"#null\" onclick=\"".$ajaxPrefix.'1'.$ajaxPostfix."\">1</a></li><li><a class='prNoBorder'>...</a></li>";
        }else {
            if ($currPage==1) {
                $str.= '<li><a class="'.$activeClassName.'">1</a></li>'; // $str.= '<a class="znPCurrent">&lt; Prev</a><a class="znPCurrent">1</a> ';
            } else {
                $str.= "<li><a href=\"#null\" onclick=\"".$ajaxPrefix.($currPage-1).$ajaxPostfix."\">&lt;</a></li><li><a href=\"#null\" onclick=\"".$ajaxPrefix.'1'.$ajaxPostfix."\">1</a></li>";
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
                        $str .= '<li><a class="'.$activeClassName.'">' . $i . '</a></li>';
                    }else {
                        $str .= "<li><a href=\"#null\" onclick=\"".$ajaxPrefix.$i.$ajaxPostfix."\">" . $i . "</a></li>";
                    }
                }
            }else {

                $finish = $start+$this->pageLength;
                if ($finish >= $this->totPages) {
                    $finish=$this->totPages-1;
                }
                for ($i=2; $i<=$finish; $i++) {
                    if ($currPage == $i) {
                        $str .= '<li><a class="'.$activeClassName.'">' . $i . "</a></li>";
                    } else {
                        $str .= "<li><a href=\"#null\" onclick=\"".$ajaxPrefix.$i.$ajaxPostfix."\">" . $i . "</a></li>";
                    }
                }
            }
        }

        if ($i < $this->totPages  && $i>0) {
            $str.= "<li><a class='prNoBorder'>...</a></li><li><a href=\"#null\" onclick=\"".$ajaxPrefix.$this->totPages.$ajaxPostfix."\">".$this->totPages."</a></li><li><a href=\"#null\" onclick=\"".$ajaxPrefix.($currPage+1).$ajaxPostfix."\">&gt;</a></li>";
        }else {
            if ($currPage==$this->totPages) {
                $str.='<li><a class="'.$activeClassName.'">'.$this->totPages.'</a></li>'; // $str.='<a class="znPCurrent">'.$this->totPages.'</a> <a class="znPCurrent">Next &gt;</a>';
            }else {
                $str.= "<li><a href=\"#null\" onclick=\"".$ajaxPrefix.$this->totPages.$ajaxPostfix."\">".$this->totPages."</a></li><li><a href=\"#null\" onclick=\"".$ajaxPrefix.($currPage+1).$ajaxPostfix."\">&gt;</a></li>";
            }
        }

        if ($this->totPages<2) {
            return '';
        } else {
			$str.="</ul></div>";
            return $str;
        }
    }
}
