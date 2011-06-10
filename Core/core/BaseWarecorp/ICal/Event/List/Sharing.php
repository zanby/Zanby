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

class BaseWarecorp_ICal_Event_List_Sharing extends Warecorp_ICal_List_Abstract 
{
    private $event;
    private $filterOwnerType;
    private $filterOwnerId;

    /**
     * data base field name used as key for returned pairs array
     */
    protected $_pairsModeKey = 'event_owner_id';
    
    /**
     * data base field name used as value for returned pairs array 
     */
    protected $_pairsModeValue = 'event_owner_id';
    
    /**
     * data base fields for assoc select
     */
    protected $_assocFields = array('event_id', 'event_owner_id');
    
    /**
     * Constructor
     * @param Zend_Db_Table_Abstract $Connection - database connection object
     */
    public function __construct(Warecorp_ICal_Event $objEvent = null)
    {
        parent::__construct();
        if ( null !== $objEvent ) $this->setEvent($objEvent);
    }
    /**
     * @param Warecorp_ICal_Event $newVal
     * @return void
     */
    public function setEvent(Warecorp_ICal_Event $newVal)
    {
        $this->event = $newVal;
    }
    /**
     * @return Warecorp_ICal_Event
     */
    public function getEvent()
    {
        if ( null === $this->event ) throw new Warecorp_ICal_Exception('Event isn\'t set');
        return $this->event;
    }
    
    public function setOwnerIdFilter($newValue)
    {
        if ( !is_array($newValue) ) $newValue = array($newValue);
        $this->filterOwnerId = $newValue;
        return $this;
    }

    public function getOwnerIdFilter()
    {
        return $this->filterOwnerId;
    }
    
    public function setOwnerTypeFilter($newValue)
    {
        if ( !is_array($newValue) ) $newValue = array($newValue);
        $this->filterOwnerType = $newValue;
        return $this;
    }

    public function getOwnerTypeFilter()
    {
        return $this->filterOwnerType;
    }
    
    /**
     * return number of items
     */
    public function getCount()
    {
        $query = $this->DbConn->select();
        //$query->from('calendar_event_sharing', array('CNT' => new Zend_Db_Expr('COUNT(*)')));
        $query->from('view_calendar_events__list', array('CNT' => 'COUNT(*)'));
        $query->where('event_id = ?', $this->getEvent()->getId());
        if ( null !== $this->getOwnerTypeFilter() ) {
            $query->where('event_owner_type IN (?)', $this->getOwnerTypeFilter());
        }
        if ( null !== $this->getOwnerIdFilter() ) {
            $query->where('event_owner_id IN (?)', $this->getOwnerIdFilter());
        }
        $query->where('share = ?', 1);
        $result = $this->DbConn->fetchOne($query);
        return $result;
    }
    
    /**
     * return list of items    
     */
    public function getList()
    {
        $query = $this->DbConn->select();
        
        if ( $this->getPage() !== null && $this->getSize() !== null ) {
            $query->limitPage($this->getPage(), $this->getSize());
        }
        
        if ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::OBJECT ) {
            $query->from('calendar_event_sharing', array('event_owner_id', 'event_owner_type'));
            $query->where('event_id = ?', $this->getEvent()->getId());
            if ( null !== $this->getOwnerTypeFilter() ) {
                $query->where('event_owner_type IN (?)', $this->getOwnerTypeFilter());
            }
            if ( null !== $this->getOwnerIdFilter() ) {
                $query->where('event_owner_id IN (?)', $this->getOwnerIdFilter());
            }
            $result = $this->DbConn->fetchAll($query);
            if ( sizeof($result) != 0 ) {
                foreach ( $result as &$item ) {
                    if ( $item['event_owner_type'] == Warecorp_ICal_Enum_OwnerType::USER ) {
                        $item = new Warecorp_User('id', $item['event_owner_id']);
                    } else {
                        $item = Warecorp_Group_Factory::loadById($item['event_owner_id']);
                    }
                }
            }
        } elseif ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::ASSOC ) {
            throw new Warecorp_ICal_Exception('This method is not emplement now');
            /*
            $query->from('calendar_event_categories', $this->getAssocFields());
            $query->where('event_id = ?', $this->getEvent()->getId());
            $result = $this->DbConn->fetchAll($query);
            */
        } elseif ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::PAIRS ) {
            $query->from('calendar_event_sharing', array($this->getPairsModeKey(), $this->getPairsModeValue()));
            $query->where('event_id = ?', $this->getEvent()->getId());
            if ( null !== $this->getOwnerTypeFilter() ) {
                $query->where('event_owner_type IN (?)', $this->getOwnerTypeFilter());
            }
            if ( null !== $this->getOwnerIdFilter() ) {
                $query->where('event_owner_id IN (?)', $this->getOwnerIdFilter());
            }
            $result = $this->DbConn->fetchPairs($query);
        }
        return $result;
    }
    
    /**
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    */
    public function add($objContext, $recur = true, $allFamilyGroups = false)
    {
        if ( $allFamilyGroups && $objContext instanceof Warecorp_Group_Family && !Warecorp_Share_Entity::isShareExists($objContext->getId(), $this->getEvent()->getId(), $this->getEvent()->EntityTypeId) ) {
            Warecorp_Share_Entity::addShare($objContext->getId(), $this->getEvent()->getId(), $this->getEvent()->EntityTypeId);
            
            if ( $recur ) {
                $refs = Warecorp_ICal_Event_List_Standard::getListByRootId($this->getEvent()->getId());
                if ( sizeof($refs) != 0 ) {
                    foreach ( $refs as &$ref ) {
                        if ( !Warecorp_Share_Entity::isShareExists($objContext->getId(), $ref->getId(), $ref->EntityTypeId) ) {
                            Warecorp_Share_Entity::addShare($objContext->getId(), $ref->getId(), $ref->EntityTypeId);
                        }
                    }
                }
            }
        }
        else {
            $data = array();
            $data['event_id']       = $this->getEvent()->getId();
            $data['event_root_id']  = $this->getEvent()->getRootId();
            $data['create_date']    = new Zend_Db_Expr('NOW()');
            if ( $objContext instanceof Warecorp_User ) {
                $data['event_owner_type']   = Warecorp_ICal_Enum_OwnerType::USER;
                $data['event_owner_id']     = $objContext->getId();
            } elseif ( $objContext instanceof Warecorp_Group_Base ) {
                $data['event_owner_type']   = Warecorp_ICal_Enum_OwnerType::GROUP;
                $data['event_owner_id']     = $objContext->getId();
            } else {
                throw new Warecorp_ICal_Exception('Incorrect Content Object');
            }
            $this->DbConn->insert('calendar_event_sharing', $data);
            /**
            * Добавляем шаринг для всех исключение события, если они есть
            */
            if ( $recur ) {
                $refs = Warecorp_ICal_Event_List_Standard::getListByRootId($this->getEvent()->getId());
                if ( sizeof($refs) != 0 ) {
                    foreach ( $refs as &$ref ) {
                        $data['event_id'] = $ref->getId();
                        $this->DbConn->insert('calendar_event_sharing', $data);
                    }
                }
            }
        }
    }

    /**
    * @param int $userId
    */
    public function addUser($userId, $recur = true)
    {
        $data = array();
        $data['event_id']           = $this->getEvent()->getId();
        $data['event_root_id']      = $this->getEvent()->getRootId();
        $data['create_date']        = new Zend_Db_Expr('NOW()');
        $data['event_owner_type']   = Warecorp_ICal_Enum_OwnerType::USER;
        $data['event_owner_id']     = $userId;
        $this->DbConn->insert('calendar_event_sharing', $data);
        /**
        * Добавляем шаринг для всех исключение события, если они есть
        */
        if ( $recur ) {
            $refs = Warecorp_ICal_Event_List_Standard::getListByRootId($this->getEvent()->getId());
            if ( sizeof($refs) != 0 ) {
                foreach ( $refs as &$ref ) {
                    $data['event_id'] = $ref->getId();
                    $this->DbConn->insert('calendar_event_sharing', $data);    
                }
            }
        }
    }
    
    /**
     * @param Warecorp_User|Warecorp_Group_Base $objContext
     * @param boolean $allFamilyGroups
     */
    public function delete($objContext, $allFamilyGroups = false)
    {
        if ( $allFamilyGroups ) {
            Warecorp_Share_Entity::removeShare($objContext->getId(), $this->getEvent()->getId(), $this->getEvent()->EntityTypeId, true);
            $refs = Warecorp_ICal_Event_List_Standard::getListByRootId($this->getEvent()->getId());
            if ( sizeof($refs) != 0 ) {
                foreach ( $refs as &$ref ) {
                    Warecorp_Share_Entity::removeShare($objContext->getId(), $ref->getId(), $ref->EntityTypeId, true);
                }
            }
        }
        else {
            $where = '';
            $where .= $this->DbConn->quoteInto('event_id = ?', $this->getEvent()->getId());
            if ( $objContext instanceof Warecorp_User ) {
                $where .= ' AND '.$this->DbConn->quoteInto('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
                $where .= ' AND '.$this->DbConn->quoteInto('event_owner_id = ?', $objContext->getId());
            } elseif ( $objContext instanceof Warecorp_Group_Base ) {
                $where .= ' AND '.$this->DbConn->quoteInto('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::GROUP);
                $where .= ' AND '.$this->DbConn->quoteInto('event_owner_id = ?', $objContext->getId());
            } else {
                throw new Warecorp_ICal_Exception('Incorrect Content Object');
            }
            $this->DbConn->delete('calendar_event_sharing', $where);
            /**
            * Удаляем шаринг для всех исключение события, если они есть
            */
            $where = '';
            $where .= $this->DbConn->quoteInto('event_root_id = ?', $this->getEvent()->getId());
            if ( $objContext instanceof Warecorp_User ) {
                $where .= ' AND '.$this->DbConn->quoteInto('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
                $where .= ' AND '.$this->DbConn->quoteInto('event_owner_id = ?', $objContext->getId());
            } elseif ( $objContext instanceof Warecorp_Group_Base ) {
                $where .= ' AND '.$this->DbConn->quoteInto('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::GROUP);
                $where .= ' AND '.$this->DbConn->quoteInto('event_owner_id = ?', $objContext->getId());
            } else {
                throw new Warecorp_ICal_Exception('Incorrect Content Object');
            }
            $this->DbConn->delete('calendar_event_sharing', $where);
        }
        $where = '';
        $where .= $this->DbConn->quoteInto('event_id = ?', $this->getEvent()->getId());
        if ( $objContext instanceof Warecorp_User ) {
            $where .= ' AND '.$this->DbConn->quoteInto('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
            $where .= ' AND '.$this->DbConn->quoteInto('event_owner_id = ?', $objContext->getId());        
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            $where .= ' AND '.$this->DbConn->quoteInto('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::GROUP);
            $where .= ' AND '.$this->DbConn->quoteInto('event_owner_id = ?', $objContext->getId());        
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }
        $this->DbConn->delete('calendar_event_sharing', $where);
        /**
        * Удаляем шаринг для всех исключение события, если они есть
        */
        $where = '';
        $where .= $this->DbConn->quoteInto('event_root_id = ?', $this->getEvent()->getId());
        if ( $objContext instanceof Warecorp_User ) {
            $where .= ' AND '.$this->DbConn->quoteInto('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
            $where .= ' AND '.$this->DbConn->quoteInto('event_owner_id = ?', $objContext->getId());        
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            $where .= ' AND '.$this->DbConn->quoteInto('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::GROUP);
            $where .= ' AND '.$this->DbConn->quoteInto('event_owner_id = ?', $objContext->getId());        
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }
        $this->DbConn->delete('calendar_event_sharing', $where);
    }

    /**
     * @param Warecorp_User|Warecorp_Group_Base $objContext
     * @param boolean $allFamilyGroups
     */
    public function deleteUser($userId)
    {
        $where = '';
        $where .= $this->DbConn->quoteInto('event_id = ?', $this->getEvent()->getId());
        $where .= ' AND '.$this->DbConn->quoteInto('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
        $where .= ' AND '.$this->DbConn->quoteInto('event_owner_id = ?', $userId);
        $this->DbConn->delete('calendar_event_sharing', $where);
        /**
        * Удаляем шаринг для всех исключение события, если они есть
        */
        $where = '';
        $where .= $this->DbConn->quoteInto('event_root_id = ?', $this->getEvent()->getId());
        $where .= ' AND '.$this->DbConn->quoteInto('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
        $where .= ' AND '.$this->DbConn->quoteInto('event_owner_id = ?', $userId);
        $this->DbConn->delete('calendar_event_sharing', $where);
    }
    
    public function deleteEventsAll()
    {
        $where = $this->DbConn->quoteInto('event_id = ?', $this->getEvent()->getId());
        $this->DbConn->delete('calendar_event_sharing', $where);
    }
    
    /**
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    */
    public function isShared($objContext)
    {
        $query = $this->DbConn->select();
        //$query->from('calendar_event_sharing', array('CNT' => new Zend_Db_Expr('COUNT(*)')));
        $query->from('view_calendar_events__list', array('CNT' => new Zend_Db_Expr('COUNT(*)')));
        $query->where('share = ?', 1);
        $query->where('event_id = ?', $this->getEvent()->getId());
        if ( $objContext instanceof Warecorp_User ) {
            $query->where('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
            $query->where('event_owner_id = ?', $objContext->getId() === null ? new Zend_Db_Expr('NULL') : $objContext->getId());        
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            $query->where('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::GROUP);
            $query->where('event_owner_id = ?', $objContext->getId() === null ? new Zend_Db_Expr('NULL') : $objContext->getId());        
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }
        $result = $this->DbConn->fetchOne($query);
        return (boolean) $result;
    }
    
    /**
    * @param int $userId
    */
    public function isUserShared($userId)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_sharing', array('COUNT(*) as CNT'));
        $query->where('event_id = ?', $this->getEvent()->getId());
        $query->where('event_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
        $query->where('event_owner_id = ?', $userId);        
        $result = $this->DbConn->fetchOne($query);
        return (boolean) $result;
    }
}
