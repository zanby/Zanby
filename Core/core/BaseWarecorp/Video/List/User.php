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
 * @package Warecorp_Video_List
 * @author Yury Zolotarsky
 * @version 1.0
 */
class BaseWarecorp_Video_List_User extends Warecorp_Video_List_Abstract
{
    private $userId;
    
    function __construct($userId = null)
    {
        if ( null !== $userId ) $this->setUserId($userId);
        parent::__construct();
    }
    
    public function setUserId($newVal)
    {
        $this->userId = $newVal;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     * return list of all items
     * @return array of objects
     * @author Yury Zolotarsky
     * @todo getPrivacy
     */
    public function getList()
    {
        if (!empty($this->userId)) {            
            $this->query->join(array('vvl' => Warecorp_Video_Gallery_Abstract::$_dbViewName), 'vvl.id = tbl.gallery_id', array());
            $this->query->where('vvl.owner_type = ?', 'user');
            $this->query->where('vvl.owner_id = ?', $this->userId);
        }
        return parent::getList();
    }

    public function getCount()
    {
        if (!empty($this->userId)) {
            $this->query->join(array('vvl' => Warecorp_Video_Gallery_Abstract::$_dbViewName), 'vvl.id = tbl.gallery_id', array());
            $this->query->where('vvl.owner_type = ?', 'user');
            $this->query->where('vvl.owner_id = ?', $this->userId);
        }
        return parent::getCount();
    }    

    public function getLastVideo()
    {
        $this->setOrder('tbl.creation_date DESC');
        $this->setCurrentPage(1);
        $this->setListSize(1);
        $video = $this->getList();
        if ( isset($video[0]) ) return $video[0];
        
        return new Warecorp_Video_Standard();
    }
 
    public function getRandomVideo()
    {
        $this->setOrder('RAND()');
        $this->setCurrentPage(1);
        $this->setListSize(1);
        $video = $this->getList();
        if ( isset($video[0]) ) return $video[0];
        
        return new Warecorp_Video_Standard();
    }
    
}
