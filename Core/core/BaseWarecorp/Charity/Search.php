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

class BaseWarecorp_Charity_Search
{
    private $keyword;
    private $category;
    private $geoFilter;
    private $order;
    
    public function getKeyword()
    {
        return $this->keyword;
    }

    public function setKeyword($newVal)
    {
        $this->keyword = $newVal;
        return $this;
    }
        
    public function getCategory()
    {
        return $this->category;
    }
    
    public function setCategory($newVal)
    {
        $this->category = $newVal;
        return $this;
    }
    
    public function getGeoFilter()
    {
        return $this->geoFilter;
    }
    
    public function setGeoFilter($newVal)
    {
        $this->geoFilter = $newVal;
        return $this;
    }
        
    public function getOrder()
    {
        return $this->order;
    }
    
    public function setOrder($newVal)
    {
        $this->order = $newVal;
        return $this;
    }
    
    public function getList()
    {
        $list[] = new Warecorp_Charity_Item();        
        $list[] = new Warecorp_Charity_Item();
        $list[] = new Warecorp_Charity_Item();
        $list[] = new Warecorp_Charity_Item();
        $list[] = new Warecorp_Charity_Item();
        return $list;
     }
     
     public function getCount()
     {
        return 5;   
     }
}
