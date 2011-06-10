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

class BaseWarecorp_ICal_Attendee_List extends Warecorp_ICal_List_Abstract
{
    private $eventId;
    private $filterAnswer;
    private $filterExcludeAttendeeIds;
    private $filterDate;
    
    public function setEventId($newVal)
    {
        $this->eventId = $newVal;
        return $this;
    }
    public function getEventId()
    {                    
        return $this->eventId;
    }
    public function setAnswerFilter($filterValue)
    {
        if ( !is_array($filterValue) ) {
            $this->filterAnswer = array();
            $split = explode(',', $filterValue);
            foreach ( $split as $_value ) $this->filterAnswer[] = trim($_value);
        } else {
            $this->filterAnswer = $filterValue;
        }
        return $this;        
    }
    public function getAnswerFilter()
    {
        if ( null === $this->filterAnswer ) {
            return array('YES', 'NO', 'MAYBE','NONE');
        }
        return $this->filterAnswer;
        
    }    
    public function setExcludeAttendeeIdsFilter($newValue)
    {
        $this->filterExcludeAttendeeIds = $newValue;
    }    
    public function getExcludeAttendeeIdsFilter()
    {
        return $this->filterExcludeAttendeeIds;
    }
    public function setDateFilter($filterValue)
    {
        $this->filterDate = $filterValue;
        return $this;        
    }
    public function getDateFilter()
    {
        return $this->filterDate;
    }
    
    /**
     * return owner types that can be userd as real attendee
     * e.g. 'group' and 'list' types can not be used as real attendee because they are containers
     * 'user' and 'fbuser' can not be real attendee
     * result of this function will be used for getCount and getList methods 
     * @return array
     */
    private function getAllowedTypesForRealAttendee() {
        $types = array(Warecorp_ICal_Enum_OwnerType::USER);
        if ( defined('FACEBOOK_USED') && FACEBOOK_USED ) $types[] = 'fbuser';
        return $types;
    }

    /**
     *
     * @param Warecorp_ICal_Event|int $event
     */
    public function __construct( $event)
    {
        if ($event instanceof Warecorp_ICal_Event) $event = $event->getId();
        parent::__construct();
        $this->setEventId($event);
    }
    
    /**
     * return number of items
     */
    public function getCount()
    {
        $objEvent = new Warecorp_ICal_Event($this->getEventId());
        
        if ( $objEvent->getId() == $objEvent->getUid() ) {
            $count = 0;
            $where = array();
            if ( null !== $this->getDateFilter() ) {
                $query = $this->DbConn->select();        
                $query->from('calendar_event_attendee', array('attendee_answer', 'attendee_key'));
                $query->where('attendee_event_id = ?', $this->getEventId());
                $query->where('attendee_date = ?', $this->getDateFilter());
                $query->where('attendee_owner_type IN(?)', $this->getAllowedTypesForRealAttendee());
                $result = $this->DbConn->fetchAll($query);
                if ( sizeof($result) != 0 ) {
                    foreach ( $result as &$row ) {
                        if ( in_array($row['attendee_answer'], $this->getAnswerFilter()) ) $count++;
                        $where[] = $row['attendee_key'];
                    }
                }
            }
            $query = $this->DbConn->select();        
            $query->from('calendar_event_attendee', array('CNT' => new Zend_Db_Expr('COUNT(*)')));
            $query->where('attendee_event_id = ?', $this->getEventId());
            $query->where('attendee_answer IN (?)', $this->getAnswerFilter());
            $query->where('attendee_date IS NULL');
            $query->where('attendee_owner_type IN(?)', $this->getAllowedTypesForRealAttendee());
            if ( sizeof($where) != 0 ) $query->where('attendee_key NOT IN (?)', $where);
            $result = $this->DbConn->fetchOne($query);
            return $result + $count;
        } 
        else {
            $query = $this->DbConn->select();        
            $query->from('calendar_event_attendee', array('CNT' => new Zend_Db_Expr('COUNT(*)')));
            $query->where('attendee_event_id = ?', $this->getEventId());
            $query->where('attendee_answer IN (?)', $this->getAnswerFilter());
            $query->where('attendee_owner_type IN(?)', $this->getAllowedTypesForRealAttendee());
            $result = $this->DbConn->fetchOne($query);
            return $result;
        }
    }
    
    /**
     * return list of items    
     */
    public function getList()
    {
        $objEvent = new Warecorp_ICal_Event($this->getEventId());
        
        if ( $objEvent->getId() == $objEvent->getUid() ) {
            $attendee = array();
            $where = array();
            if ( null !== $this->getDateFilter() ) {
                $query = $this->DbConn->select();        
                $query->from('calendar_event_attendee', array('attendee_id', 'attendee_answer', 'attendee_key'));
                $query->where('attendee_event_id = ?', $this->getEventId());
                $query->where('attendee_date = ?', $this->getDateFilter());
                $query->where('attendee_owner_type IN(?)', $this->getAllowedTypesForRealAttendee());
                if ( null != $this->getExcludeAttendeeIdsFilter() ) {
                    $query->where('attendee_id NOT IN (?)', $this->getExcludeAttendeeIdsFilter()); 
                }
                $result = $this->DbConn->fetchAll($query);
                if ( sizeof($result) != 0 ) {
                    foreach ( $result as &$row ) {
                        if ( in_array($row['attendee_answer'], $this->getAnswerFilter()) ) $attendee[] = new Warecorp_ICal_Attendee($row['attendee_id']);
                        $where[] = $row['attendee_key'];
                    }
                }
            }
            $query = $this->DbConn->select();        
            $query->from('calendar_event_attendee', array('attendee_id'));
            $query->where('attendee_event_id = ?', $this->getEventId());
            $query->where('attendee_answer IN (?)', $this->getAnswerFilter());
            $query->where('attendee_date IS NULL');
            $query->where('attendee_owner_type IN(?)', $this->getAllowedTypesForRealAttendee());
            if ( sizeof($where) != 0 ) $query->where('attendee_key NOT IN (?)', $where);
            if ( null != $this->getExcludeAttendeeIdsFilter() ) {
                $query->where('attendee_id NOT IN (?)', $this->getExcludeAttendeeIdsFilter()); 
            }
            $result = $this->DbConn->fetchCol($query);
                        
            if ( sizeof($result) != 0 ) {
                $organizerAttendee = null;
                foreach ( $result as &$_attendee ) {
                    $tmpAttendee = new Warecorp_ICal_Attendee($_attendee);
                    if ( $tmpAttendee->getOwnerType() == 'user' && $tmpAttendee->getOwnerId() !== null ) {
                        if ( $objEvent->getOwnerType() == 'user' ) {
                            if ( $objEvent->getOwnerId() == $tmpAttendee->getOwnerId() ) $organizerAttendee = $tmpAttendee;
                            else $attendee[] = $tmpAttendee;
                        } else {
                            if ( $objEvent->getCreatorId() == $tmpAttendee->getOwnerId() ) $organizerAttendee = $tmpAttendee;
                            else $attendee[] = $tmpAttendee;
                        }
                    } else $attendee[] = $tmpAttendee;
                }
                if ( null !== $organizerAttendee ) {
                    $organizerAttendee->setIsOrganizer(true);
                    array_unshift($attendee, $organizerAttendee);
                }
            }
            return $attendee;
        } else {
            $attendee = array();
            $query = $this->DbConn->select();        
            $query->from('calendar_event_attendee', array('attendee_id'));
            $query->where('attendee_event_id = ?', $this->getEventId());
            $query->where('attendee_answer IN (?)', $this->getAnswerFilter());
            $query->where('(attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
            $query->orWhere('attendee_owner_type = ?)', 'fbuser');
            if ( null != $this->getExcludeAttendeeIdsFilter() ) {
                $query->where('attendee_id NOT IN (?)', $this->getExcludeAttendeeIdsFilter()); 
            }
            $result = $this->DbConn->fetchCol($query);
            if ( sizeof($result) != 0 ) {
                $organizerAttendee = null;
                foreach ( $result as &$_attendee ) {
                    $tmpAttendee = new Warecorp_ICal_Attendee($_attendee);
                    if ( $tmpAttendee->getOwnerType() == 'user' && $tmpAttendee->getOwnerId() !== null ) {
                        if ( $objEvent->getOwnerType() == 'user' ) {
                            if ( $objEvent->getOwnerId() == $tmpAttendee->getOwnerId() ) $organizerAttendee = $tmpAttendee;
                            else $attendee[] = $tmpAttendee;
                        } else {
                            if ( $objEvent->getCreatorId() == $tmpAttendee->getOwnerId() ) $organizerAttendee = $tmpAttendee;
                            else $attendee[] = $tmpAttendee;
                        }
                    } else $attendee[] = $tmpAttendee;
                }
                if ( null !== $organizerAttendee ) {
                    $organizerAttendee->setIsOrganizer(true);
                    array_unshift($attendee, $organizerAttendee);
                }
            }
            return $attendee;        
        }
    }
    
    /**
    * @desc 
    */
    public function getObjectsIdsList($objType)
    {
        $attendee = array();
        $query = $this->DbConn->select();        
        $query->from('calendar_event_attendee', array('attendee_owner_id'));
        $query->where('attendee_event_id = ?', ($this->getEventId() === null) ? new Zend_Db_Expr('NULL') : $this->getEventId());
        $query->where('attendee_owner_type = ?', $objType);
        $result = $this->DbConn->fetchCol($query);
        if ( sizeof($result) != 0 ) {
            return $result;
        }
        return array();        

    }
    
    /**
    * @desc 
    */
    public function findAttendee(Warecorp_User $user) 
    {
        if ( null !== $this->getDateFilter() ) {
            $query = $this->DbConn->select();
            $query->from('calendar_event_attendee', array('attendee_id'));
            $query->where('attendee_answer IN (?)', $this->getAnswerFilter());
            $query->where('attendee_event_id = ?', $this->getEventId());
            $query->where('attendee_date = ?', $this->getDateFilter());
            $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
            if ( null !== $user->getId() ) {
                $query->where('attendee_owner_id = ?', $user->getId());            
                $query->where('attendee_email IS NULL');
            } else {
                $query->where('attendee_email = ?', $user->getEmail());
                $query->where('attendee_owner_id IS NULL');
            }     
			
            $result = $this->DbConn->fetchOne($query);
            if ( $result ) {
                $attendee = new Warecorp_ICal_Attendee($result);
                return $attendee;
            }    
        }

        $query = $this->DbConn->select();
        $query->from('calendar_event_attendee', array('attendee_id'));
        $query->where('attendee_answer IN (?)', $this->getAnswerFilter());
        $query->where('attendee_event_id = ?', $this->getEventId());
        $query->where('attendee_date IS NULL');
        $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
        if ( null !== $user->getId() ) {
            $query->where('attendee_owner_id = ?', (NULL === $user->getId()) ? new Zend_Db_Expr('NULL') : $user->getId(), 'INTEGER'); 
            $query->where('attendee_email IS NULL');
        } else {
            $query->where('attendee_email = ?', (NULL === $user->getEmail()) ? new Zend_Db_Expr('NULL') : $user->getEmail());
            $query->where('attendee_owner_id IS NULL');
        }
        $result = $this->DbConn->fetchOne($query);
        if ( $result ) {
            $attendee = new Warecorp_ICal_Attendee($result);
            return $attendee;
        }
        return null;       
    }
    
    /**
    * @desc 
    */
    public function findAttendeeByEmail($email) 
    {
        if ( null !== $this->getDateFilter() ) {
            $query = $this->DbConn->select();
            $query->from('calendar_event_attendee', array('attendee_id'));
            $query->where('attendee_event_id = ?', $this->getEventId());
            $query->where('attendee_date = ?', $this->getDateFilter());
            $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
            $query->where('attendee_email = ?', $email);
            $query->where('attendee_owner_id IS NULL');
            $result = $this->DbConn->fetchOne($query);
            if ( $result ) {
                $attendee = new Warecorp_ICal_Attendee($result);
                return $attendee;
            }    
        }

        $query = $this->DbConn->select();
        $query->from('calendar_event_attendee', array('attendee_id'));
        $query->where('attendee_event_id = ?', $this->getEventId());
        $query->where('attendee_date IS NULL');
        $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
        $query->where('attendee_email = ?', $email);
        $query->where('attendee_owner_id IS NULL');
        $result = $this->DbConn->fetchOne($query);
        if ( $result ) {
            $attendee = new Warecorp_ICal_Attendee($result);
            return $attendee;
        }
        return null;       
    }
   
    /**
    * @desc 
    */
    public function findObjectsAttendee($objType, $owner_id) 
    {
        if ( null !== $this->getDateFilter() ) {
            $query = $this->DbConn->select();
            $query->from('calendar_event_attendee', array('attendee_id'));
            $query->where('attendee_event_id = ?', $this->getEventId());
            $query->where('attendee_date = ?', $this->getDateFilter());
            $query->where('attendee_owner_type = ?', $objType);
            $query->where('attendee_owner_id = ?', $owner_id);            
            $result = $this->DbConn->fetchOne($query);
            if ( $result ) {
                $attendee = new Warecorp_ICal_Attendee($result);
                return $attendee;
            }    
        }

        $query = $this->DbConn->select();
        $query->from('calendar_event_attendee', array('attendee_id'));
        $query->where('attendee_event_id = ?', $this->getEventId());
        $query->where('attendee_date IS NULL');
        $query->where('attendee_owner_type = ?', $objType);
        $query->where('attendee_owner_id = ?', $owner_id);            
        $result = $this->DbConn->fetchOne($query);
        if ( $result ) {
            $attendee = new Warecorp_ICal_Attendee($result);
            return $attendee;
        }
        return null;       
    }
    
    /**
    * @desc 
    */
    public function findAttendeeByCode($accessCode)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_attendee', array('attendee_id'));
        $query->where('attendee_event_id = ?', $this->getEventId());
        $query->where('attendee_access_code = ?', $accessCode);
        $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
        $result = $this->DbConn->fetchOne($query);
        if ( $result ) {
            $attendee = new Warecorp_ICal_Attendee($result);
            return $attendee;
        }
        return null;
    }
    
    /**
    * @desc 
    */
    public function findObjectsAttendeeByCode($accessCode, $objType = null)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_attendee', array('attendee_id'));
        $query->where('attendee_event_id = ?', $this->getEventId());
        $query->where('attendee_access_code = ?', $accessCode);
        if ( null !== $objType ) $query->where('attendee_owner_type = ?', $objType);
        $result = $this->DbConn->fetchOne($query);
        if ( $result ) {
            $attendee = new Warecorp_ICal_Attendee($result);
            return $attendee;
        }
        return null;
    }    
    
    /**
    * @desc 
    */
    public function delete()
    {
        $where = $this->DbConn->quoteInto('attendee_event_id = ?', $this->getEventId());
        $where .= ' AND '.$this->DbConn->quoteInto('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
        if ( null != $this->getExcludeAttendeeIdsFilter() ) {
            $where .= ' AND '.$this->DbConn->quoteInto('attendee_id NOT IN (?)', $this->getExcludeAttendeeIdsFilter());
        }
        $this->DbConn->delete('calendar_event_attendee', $where);
    }
    
    /**
    * @desc 
    */
    public function deleteByAttendeeIds( $ids )
    {
        $where = $this->DbConn->quoteInto('attendee_event_id = ?', $this->getEventId());
        $where .= ' AND '.$this->DbConn->quoteInto('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
        $where .= ' AND '.$this->DbConn->quoteInto('attendee_id IN (?)', $ids);
        $this->DbConn->delete('calendar_event_attendee', $where);
    }

    public function deleteFBAttendeeByIds( $ids )
    {
        $where = $this->DbConn->quoteInto('attendee_event_id = ?', $this->getEventId());
        $where .= ' AND '.$this->DbConn->quoteInto('attendee_owner_type = ?', 'fbuser');
        $where .= ' AND '.$this->DbConn->quoteInto('attendee_id IN (?)', $ids);
        $this->DbConn->delete('calendar_event_attendee', $where);
    }
    
    /**
    * @desc 
    */
    public function deleteObjects($type, $excludeIds = null)
    {
        $where = $this->DbConn->quoteInto('attendee_event_id = ?', $this->getEventId());
        $where .= ' AND '.$this->DbConn->quoteInto('attendee_owner_type = ?', $type);
        if ( null !== $excludeIds && sizeof($excludeIds) != 0 ) {
            $where .= ' AND '.$this->DbConn->quoteInto('attendee_owner_id NOT IN (?)', $excludeIds);
        }
        $this->DbConn->delete('calendar_event_attendee', $where);
    }
    
    /**
    * @desc 
    */
    public function deleteWhithDate()
    {
        $where = $this->DbConn->quoteInto('attendee_event_id = ?', $this->getEventId());
        $where .= ' AND attendee_date IS NOT NULL ';
        $where .= ' AND '.$this->DbConn->quoteInto('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);
        $this->DbConn->delete('calendar_event_attendee', $where);
    }
 
     /**
     * @desc 
     */
    static public function updateAttendeeForNewUser(Warecorp_User $objUser)
    {
        $DbConn = Zend_Registry::get('DB');
        $data = array();
        $data['attendee_owner_id']  = $objUser->getId();
        $data['attendee_email']     = new Zend_Db_Expr('NULL');
        $data['attendee_key']       = 'user_'.$objUser->getId().'_';
        $where = $DbConn->quoteInto('attendee_email = ?', $objUser->getEmail());
        $DbConn->update('calendar_event_attendee', $data, $where);
    }
    
     /**
     * @desc 
     */
    static public function updateAttendeeForNewFBUser(Warecorp_User $objUser, $facebookId)
    {
        $DbConn = Zend_Registry::get('DB');
        $data = array();
        $data['attendee_owner_id']  = $objUser->getId();
        $data['attendee_owner_type']= 'user';
        $data['attendee_email']     = new Zend_Db_Expr('NULL');
        $data['attendee_key']       = 'user_'.$objUser->getId().'_';
        $where = $DbConn->quoteInto('attendee_owner_id = ?', $facebookId);
        $where .= ' AND '. $DbConn->quoteInto('attendee_owner_type = ?', 'fbuser');
        $DbConn->update('calendar_event_attendee', $data, $where);                
    }
    
    
    /**
    * Вызывается из объекта Warecorp_User_Addressbook_ContactList::delete()
    * Раскрывает контакт лист как логины пользователя или емайл адреса и удаляет связ 
    */
    public function onContactListRemoved(Warecorp_User_Addressbook_ContactList $contactList)
    {
        $DbConn = Zend_Registry::get('DB');
        $query = $DbConn->select();
        $query->from('calendar_event_attendee', array("*"));
        $query->where('attendee_owner_type = ?', 'list');
        $query->where('attendee_owner_id = ?', $contactList->getContactListId());
        $results = $DbConn->fetchAll($query);
        if ( sizeof($results) ) {
            /**
            * @desc 
            */
            $lstContacts = $contactList->getContacts()->getList();
            $strContacts = array();
            if ( sizeof($lstContacts) != 0 ) {
                foreach ( $lstContacts as &$contact ) {
                    if ( $contact instanceof Warecorp_User_Addressbook_User ) {
                        $strContacts[] = $contact->getUser()->getLogin();
                    } elseif ( $contact instanceof Warecorp_User_Addressbook_CustomUser ) {
                        $strContacts[] = $contact->getEmail();
                    }
                }
            }
            if ( sizeof($strContacts) != 0 ) {
                $strContacts = join(', ', $strContacts);
                /**
                 * @see issue #10184
                 */
                $recipients = Warecorp_ICal_Invitation::prepareRecipientsFromString(new Warecorp_User(), $strContacts);
            }
            /**
            * @desc 
            */
            foreach ( $results as $attendeeInfo ) {
                $objEvent = new Warecorp_ICal_Event($attendeeInfo['attendee_event_id']);                
                /**
                 * @see issue #10184
                 */
                $objEvent->getInvite()->mergeRecipients( $recipients );                                                
                
                $where = $DbConn->quoteInto('attendee_id = ?', $attendeeInfo['attendee_id']);
                $DbConn->delete('calendar_event_attendee', $where);
            }

        }
        
    }
}
