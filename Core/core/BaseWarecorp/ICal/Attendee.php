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

class BaseWarecorp_ICal_Attendee
{
    private $DbConn;
    private $id;
    private $eventId;
    private $ownerType;
    private $ownerId;
    private $email;
    private $answer;
    private $answerText;
    private $accessCode;
    private $date;
    private $key;
    private $isOrganizer = false;
    private $phone;
    private $name;
    
    private $event;
    private $owner;
    
	public function setId($newVal)
    {
        $this->id = $newVal;
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setEventId($newVal)
    {
        $this->eventId = $newVal;
        return $this;
    }
    public function getEventId()
    {                    
        return $this->eventId;
    }
    public function setOwnerType($newVal)
    {
        $this->ownerType = $newVal;
        return $this;
    }
    public function getOwnerType()
    {    
        if ( null === $this->ownerType ) throw new Warecorp_ICal_Exception('Owner Type is not set');                   
        return $this->ownerType;
    }
    public function setOwnerId($newVal)
    {
        $this->ownerId = $newVal;
        return $this;
    }
    public function getOwnerId()
    {                       
        return $this->ownerId;
    }
    public function setEmail($newVal)
    {
        $this->email = $newVal;
        return $this;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setAnswer($newVal)
    {
        $this->answer = $newVal;
        return $this;
    }
    public function getAnswer()
    {
        if ( null === $this->answer ) $this->answer = 'NONE';
        return $this->answer;
    }
    public function setAnswerText($newVal)
    {
        $this->answerText = $newVal;
        return $this;
    }
    public function getAnswerText()
    {
        return $this->answerText;
    }
    public function setAccessCode($newVal)
    {
        $this->accessCode = $newVal;
        return $this;
    }
    public function getAccessCode()
    {
        return $this->accessCode;
    }
    public function setDate($newVal)
    {
        $this->date = $newVal;
        return $this;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function setKey($newVal)
    {
        $this->key = $newVal;
        return $this;
    }
    public function getKey()
    {
        return $this->key;
    }
    public function setPhone($newVal)
    {
        $this->phone = $newVal;
        return $this;
    }
    public function getPhone()
    {
        return $this->phone;
    }
    public function setName($newVal)
    {
        $this->name = $newVal;
        return $this;
    }
    public function getName()
    {
        if ( !$this->name && $this->getOwnerType() == 'fbuser' ) {            
            if ( FACEBOOK_USED ) {
                $facebookInfo = Warecorp_Facebook_User::getInfo($this->getOwnerId());
                if ( $facebookInfo ) {
                    $this->setName($facebookInfo['first_name'].' '.$facebookInfo['last_name']);
                    $this->saveName();
                }
            }                     
            if ( !$this->name ) $this->setName('FB user : '.$this->getOwnerId());
        }
        return $this->name;
    }
    
    public function isOrganizer()
    {
        return $this->isOrganizer;
    }
    
    public function setIsOrganizer($value)
    {
        $this->isOrganizer = (boolean) $value;
    }
    
    /**
    * @desc 
    */
    public function __construct($attendeeId = null)
    {
        $this->DbConn = Zend_Registry::get('DB');   
        if ( null !== $attendeeId ) $this->loadById($attendeeId);
    }
    
    /**
    * @desc 
    */
    public function getOwner()
    {
        if ( null === $this->owner ) {
            if ( $this->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) {
                if ( null !== $this->getOwnerId() ) {
                    $this->owner = new Warecorp_User('id', $this->getOwnerId());
                } else {
                    $this->owner = new Warecorp_User();
                    $this->owner->setLogin('Guest');
                    $this->owner->setEmail($this->getEmail());
                }
            } elseif ( $this->getOwnerType() == 'fbuser' ) {
                $this->owner = new stdClass();
                $this->owner->uid = $this->getOwnerId();
            } else {
            
            }
        }
        return $this->owner;    
    }
    
    /**
    * @desc 
    */
    public function generateAccessCode()
    {
        /**
         * @author Artem Sukharev
         */
        return $code = md5(uniqid(mt_rand(), true));
        
        /*
        $flag = false;
        while ( !$flag ) {
            list($usec, $sec) = explode(" ", microtime()); 
            $strCode = ((float)$usec + (float)$sec);
            $strCode = $strCode . $this->getOwnerType() . $this->getOwnerId() . $this->getEmail() . $this->getEventId();
            $code = md5($strCode);
            $query = $this->DbConn->select();
            $query->from('calendar_event_attendee', array(new Zend_Db_Expr('count(*)')));
            $query->where('attendee_access_code = ?', $code);
            $result = $this->DbConn->fetchOne($query);
            if ( !$result ) $flag = true;
        }
        return $code;
        */
    }      

    static public function generateAttendeeAccessCode()
    {
        /**
         * @author Artem Sukharev
         */
        return $code = md5(uniqid(mt_rand(), true));
    }
    
    /**
    * @desc                                     
    */
    public function loadById($attendeeId)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_attendee', array('*'));
        $query->where('attendee_id = ?', $attendeeId);
        $result = $this->DbConn->fetchRow($query);
        if ( $result ) {
            $this->setId($result['attendee_id']);
            $this->setEventId($result['attendee_event_id']);
            $this->setOwnerType($result['attendee_owner_type']);
            $this->setOwnerId($result['attendee_owner_id']);
            $this->setEmail($result['attendee_email']);
            $this->setAnswer($result['attendee_answer']);
            $this->setAnswerText($result['attendee_answer_text']);
            $this->setAccessCode($result['attendee_access_code']);
            $this->setDate($result['attendee_date']);  
            $this->setPhone($result['phone']);      
            $this->setName($result['attendee_name']);    
        }
    }
    
    /**
    * @desc 
    */
    public function save()
    {
        $this->setAccessCode(( $this->getAccessCode() ) ? $this->getAccessCode() : $this->generateAccessCode());
        $data = array();
        $data['attendee_event_id']      = $this->getEventId();
        $data['attendee_owner_type']    = $this->getOwnerType();
        $data['attendee_owner_id']      = ( $this->getOwnerId() ) ? $this->getOwnerId() : new Zend_Db_Expr('NULL');
        $data['attendee_email']         = ( $this->getEmail() ) ? $this->getEmail() : new Zend_Db_Expr('NULL');
        $data['attendee_answer']        = $this->getAnswer();
        $data['attendee_answer_text']   = $this->getAnswerText();
        $data['attendee_access_code']   = $this->getAccessCode(); 
        $data['attendee_date']          = ( $this->getDate() ) ? $this->getDate() : new Zend_Db_Expr('NULL');
        $data['attendee_key']           = $this->getOwnerType().'_'.$this->getOwnerId().'_'.$this->getEmail();
        $data['phone']                  = ( $this->getPhone() ) ? $this->getPhone() : new Zend_Db_Expr('NULL');
        $data['attendee_name']          = ( $this->getName() ) ? $this->getName() : new Zend_Db_Expr('NULL');
        if ( null === $this->getId() ) {
            $this->DbConn->insert('calendar_event_attendee', $data);
            $this->setId($this->DbConn->lastInsertId());
        } else {
            $where = $this->DbConn->quoteInto('attendee_id = ?', $this->getId());
            $this->DbConn->update('calendar_event_attendee', $data, $where);
        }        
    }
    
    public function saveName() {
        $data = array();
        $data['attendee_name']          = ( $this->getName() ) ? $this->getName() : new Zend_Db_Expr('NULL');
        $where = $this->DbConn->quoteInto('attendee_id = ?', $this->getId());
        $this->DbConn->update('calendar_event_attendee', $data, $where);        
    }
    
    public function delete()
    {
        if ( $this->getOwnerType() == 'user' || $this->getOwnerType() == 'group' ) {
            $_owner = $this->getOwner();
            $_objEvent = new Warecorp_ICal_Event($this->getEventId());
            $_objRootEvent = new Warecorp_ICal_Event($_objEvent->getRootId());
            if ( $_objRootEvent->getSharing()->isShared($_owner) ) $_objRootEvent->getSharing()->delete($_owner);
        }
        
        $where = $this->DbConn->quoteInto('attendee_id = ?', $this->getId());
        $this->DbConn->delete('calendar_event_attendee', $where);
    }
}
