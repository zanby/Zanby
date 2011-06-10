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
 * @package Warecorp_Share_List
 * @author Michael pianko
 * @version 1.0
 */
class BaseWarecorp_Share_List extends Warecorp_Abstract_List 
{
    private $listMode = null;
    private $entityId = null;
    private $entityType = null;
    private $familyId = null;
    private $ownerId = null;
    

    public function setFamilyId($id)
    {
        $this->familyId = $id;
        return $this;
    }

    public function getFamilyId()
    {
        return $this->familyId;
    }

    public function setEntityType($id)
    {
        $this->entityType = $id;
        return $this;
    }

    public function getEntityType()
    {
        return $this->entityType;
    }

    
    public function setEntityId($id)
    {
        $this->entityId = $id;
        return $this;
    }

    public function getEntityId()
    {
        return $this->entityId;
    }
    
    public function setOwnerId($id)
    {
        $this->ownerId = $id;
        return $this;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }
    /**
    * @desc shared / not shared / both 
    */
    public function setListMode($mode)
    {
        $this->listMode = $mode;
        return $this;
    }
    
    public function getListMode()
    {
        return $this->listMode;
    }    
    
    
    public function getList()
    {
        $query = $this->_db->select();
        $query->from(array('zse' => 'zanby_entity__share'), 'zse.entiry_id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
                
        if ( $this->getEntityId()   !== null) $query->where('zse.entity_id IN (?)',     $this->getEntityId()    );  
        if ( $this->getEntityType() !== null) $query->where('zse.entity_type IN (?)',   $this->getEntityType()  );  
        if ( $this->getOwnerId()    !== null) $query->where('zse.owner_id IN (?)',      $this->getOwnerId()     );  
        if ( $this->getFamilyId()   !== null) $query->where('zse.family_id IN (?)',     $this->getFamilyId()    );  
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        
        $items = $this->_db->fetchCol($query);

        return $items;
    }
	
}
