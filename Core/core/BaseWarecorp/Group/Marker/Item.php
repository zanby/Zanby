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

class BaseWarecorp_Group_Marker_Item
{
    /**
     *  @var Warecorp_Group_Base
     */
    private $_group;
    private $AppTheme;

    public function __construct(Warecorp_Group_Base $group)
    {
        $this->_group = $group;
        $this->AppTheme = Zend :: Registry('AppTheme');
    }
    public function getHash()
    {
        return $this->_group->getMapMarkerHash();
    }
    public function setHash($hash)
    {
        $this->_group->setMapMarkerHash($hash);
        return $this;
    }
    public function save()
    {
        $this->_group->save();
        return $this;
    }
    /**
     *  @return Warecorp_Group_Base
     */
    public function getGroup()
    {
        return $this->_group;
    }
    public function getPath( ) {
        return UPLOAD_BASE_PATH.'upload/group_marker/'.$this->_group->getMapMarkerHash();
    }
    public function getSrc( ) {
        return BASE_URL.'/upload/group_marker/'.$this->_group->getMapMarkerHash();
    }
    public function getPathImg( ) {
        return UPLOAD_BASE_PATH.'upload/group_marker/'.$this->_group->getMapMarkerHash().'_orig.png';
    }
    public function getSrcImg( ) {
        if ($this->_group->isCongressionalDistrict()) {
            return $this->AppTheme->common->images.'/map/dot.png';
        }
        return BASE_URL.'/upload/group_marker/'.$this->_group->getMapMarkerHash().'_orig.png';
    }
}
