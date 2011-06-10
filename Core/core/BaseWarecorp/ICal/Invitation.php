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

class BaseWarecorp_ICal_Invitation
{
    protected $DbConn;
    private $id;
    private $eventId;
    private $from;
    private $sendFrom;
    private $to;
    private $subject;
    private $message;
    private $allowGuestToInvite;
    private $displayListToGuest;
    private $receiveNoRsvpEmail;
    private $receivers;
    private $recipients;
    private $event;
    private $isAnybodyJoin = 0;
    private $maxTinyUrlsInArray = 100;
    protected $allowGroups = true;
    protected $allowLists = true;
    protected $sendNotification = true;
    protected $customMessage;
    
    protected $recipientsToInvite;
    protected $recipientsToRemove;
    protected $recipientsToNotify; 

    public function setId($newValue)
    {
        $this->id = $newValue;
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setEventId($newValue)
    {
        $this->eventId = $newValue;
        return $this;
    }
    public function getEventId()
    {
        if ( null === $this->eventId ) throw new Warecorp_ICal_Exception('Event ID is not set');
        return $this->eventId;
    }
    public function setFrom($newValue)
    {
        $this->from = $newValue;
        return $this;
    }
    public function getFrom()
    {
        return $this->from;
    }
    public function setSendFrom($newValue)
    {
        $this->sendFrom = $newValue;
        return $this;
    }
    public function getSendFrom()
    {
        return $this->sendFrom;
    }
    public function setTo($newValue)
    {
        $this->to = $newValue;
        return $this;
    }
    public function getTo()
    {
        return $this->to;
    }
    public function setSubject($newValue)
    {
        $this->subject = $newValue;
        return $this;
    }
    public function getSubject()
    {
        return $this->subject;
    }
    public function setMessage($newValue)
    {
        $this->message = $newValue;
        return $this;
    }
    public function getMessage()
    {
        return $this->message;
    }
    public function setAllowGuestToInvite($newValue)
    {
        $this->allowGuestToInvite = $newValue;
        return $this;
    }
    public function getAllowGuestToInvite()
    {
        return $this->allowGuestToInvite;
    }
    public function setDisplayListToGuest($newValue)
    {
        $this->displayListToGuest = $newValue;
        return $this;
    }
    public function getDisplayListToGuest()
    {
        return $this->displayListToGuest;
    }
    public function setReceiveNoRsvpEmail( $value )
    {
        $this->receiveNoRsvpEmail = $value;
        return $this;
    }    
    public function getReceiveNoRsvpEmail( )
    {
        return $this->receiveNoRsvpEmail;
    }
    public function getSendNotification()
    {
        return $this->sendNotification;
    }
    public function setSendNotification( $value )
    {
        $this->sendNotification = (boolean) $value;
        return $this;
    }   
    public function getCustomMessage()
    {
        return $this->customMessage;
    }
    public function setCustomMessage( $value )
    {
        $this->customMessage = $value;
        return $this;
    } 
    public function setEvent(Warecorp_ICal_Event $objEvent)
    {
        $this->event = $objEvent;
        return $this;
    }
    public function getEvent()
    {
        if ( null === $this->event ) {
            $this->event = new Warecorp_ICal_Event($this->getEventId());
        }
        return $this->event;
    }
    /**
    * Разрешает или запрещает использование в адресах адреса групп
    */
    public function setAllowGroups($newValue)
    {
        $this->allowGroups = (boolean) $newValue;
    }
    /**
    * Разрешает или запрешает использование в адресах названия листов
    */
    public function setAllowLists($newValue)
    {
        $this->allowLists = (boolean) $newValue;
    }    
        
    /**
     * 
     * @param $inviteId
     * @return unknown_type
     */
    public function __construct($inviteId = null)
    {
        $this->DbConn = Zend_Registry::get('DB');
        if ( null !== $inviteId ) $this->loadById($inviteId);
    }
    /**
     * 
     * @param $inviteId
     * @return unknown_type
     */
    public function loadById($inviteId)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_invitations', array('*'));
        $query->where('invite_id = ?', $inviteId);
        $result = $this->DbConn->fetchRow($query);
        if ( $result ) {
            $this->setId($result['invite_id']);
            $this->setEventId($result['invite_event_id']);
            $this->setFrom($result['invite_from']);
            $this->setTo($result['invite_to']);
            $this->setSubject($result['invite_subject']);
            $this->setMessage($result['invite_message']);
            $this->setAllowGuestToInvite($result['invite_allow_guest_to_invite']);
            $this->setDisplayListToGuest($result['invite_display_list_to_guest']);
            $this->setIsAnybodyJoin($result['is_anybody_join']);
            $this->setReceiveNoRsvpEmail($result['invite_receive_no_rsvp_email']);
        }
    }

    /**
     * 
     * @param $eventId
     * @return unknown_type
     */
    public function loadByEventId($eventId)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_invitations', array('invite_id'));
        $query->where('invite_event_id = ?', $eventId);
        $result = $this->DbConn->fetchOne($query);
        $this->loadById($result);
    }

    /**
     * +----------------------------------------------------------------------
     * |
     * |    
     * |
     * +---------------------------------------------------------------------- 
     */
    
    /**
     * 
     * @return unknown_type
     * @deprecated
     */
    public function saveTo()
    {
        throw new Exception('Method is depricated');
    }

    /**
     * 
     * @return unknown_type
     * @deprecated
     */
    public function save()
    {
        throw new Exception('Method is depricated');
    }

    /**
     * save contact list or group objects as attendee for event (not as list of emails)
     * it needs to allow edit these objects
     * @see Warecorp_ICal_Invitation::save()
     * @return void
     * @deprecated
     */
    public function saveInviteObjects()
    {
        throw new Exception('Method is depricated');
    }

    /**
     * update contact list or group objects saved as attendee for event (not as list of emails)
     * it needs to allow edit these objects
     * @see Warecorp_ICal_Invitation::save()
     * @return void
     * @deprecated
     */
    public function updateInviteObjects()
    {
        throw new Exception('Method is depricated');
    }

    /**
     * 
     * @return unknown_type
     * @deprecated
     */
    protected function clearTo()
    {
        throw new Exception('Method is depricated');
    }

    /**
    * @desc
    * @deprecated
    */
    public function mergeTo($newValue)
    {
        throw new Exception('Method is depricated');
    }

    /**
    * @desc
    * @deprecated
    */
    public function equals(Warecorp_ICal_Invitation $objInvite)
    {
        throw new Exception('Method is depricated');
    }

    /**
     * 
     * @param $newValue
     * @param $arrGroups
     * @param $arrLists
     * @param $arrFBUsers
     * @return unknown_type
     * @deprecated
     */
    public function setReceivers($newValue, $arrGroups = null, $arrLists = null, $arrFBUsers = null)
    {
        throw new Exception('Method is depricated');
    }
    /**
     * 
     * @param $newValue
     * @param $arrGroups
     * @param $arrLists
     * @param $arrFBUsers
     * @return unknown_type
     * @deprecated
     */
    public function mergeReceivers($newValue, $arrGroups = null, $arrLists = null, $arrFBUsers = null)
    {
        throw new Exception('Method is depricated');
    }
    /**
     * 
     * @return unknown_type
     * @deprecated
     */
    public function getReceivers()
    {
        throw new Exception('Method is depricated');
    }
    
    
    /**
     * +----------------------------------------------------------------------
     * |
     * |    
     * |
     * +---------------------------------------------------------------------- 
     */
    
    /**
     * add email domain to the string
     * @param string$value
     * @return string
     */
    static public function callback_addDomain( $value )
    {
        return $value.'@'.DOMAIN_FOR_GROUP_EMAIL;
    }
    
    /**
     * 
     * @return void
     */
    public function __save()
    {
        $data = array();
        $data['invite_event_id'] = $this->getEventId();
        $data['invite_from'] = $this->getFrom();
        $data['invite_to'] = $this->__prepareInviteTo();
        $data['invite_subject'] = $this->getSubject();
        $data['invite_message'] = $this->getMessage();
        $data['invite_allow_guest_to_invite'] = $this->getAllowGuestToInvite();
        $data['invite_display_list_to_guest'] = $this->getDisplayListToGuest();
        $data['is_anybody_join'] = $this->getIsAnybodyJoin();
        $data['invite_receive_no_rsvp_email'] = $this->getReceiveNoRsvpEmail() ? 1 : 0;

        if ( null === $this->getId() ) {
            $this->DbConn->insert('calendar_event_invitations', $data);
            $this->setId($this->DbConn->lastInsertId());
            $this->__saveInviteObjects();
        } else {
            $where = $this->DbConn->quoteInto('invite_id = ?', $this->getId());
            $this->DbConn->update('calendar_event_invitations', $data, $where);
            $this->__updateInviteObjects();
        }
    }
    /**
     * save contact list or group objects as attendee for event (not as list of emails)
     * it needs to allow edit these objects
     * @see Warecorp_ICal_Invitation::save()
     * @return void
     */
    protected function __saveInviteObjects()
    {
        $objEvent = $this->getEvent();
        
        $recipients = $this->getRecipients();
        
        /* add groups as attendee of event */
        if ( $this->allowGroups ) { $this->insertAttendeeObjAsGroup( $recipients['valid']['groups'], 'group' ); }
        
        /* add mailing lists as attendee of event */
        if ( $this->allowLists ) { $this->insertAttendeeObjAsGroup( $recipients['valid']['contactLists'], 'list' ); }
        
        /* add Facebook users as attendee of event */
        if ( FACEBOOK_USED ) { $this->insertAttendeeObjAsGroup( $recipients['valid']['fbusers'], 'fbuser' ); }
        
        return true;
    }
    /**
     * update contact list or group objects saved as attendee for event (not as list of emails)
     * it needs to allow edit these objects
     * @see Warecorp_ICal_Invitation::save()
     * @return void
     */
    protected function __updateInviteObjects( $mode = 'UPDATE' )
    {
        $objEvent = new Warecorp_ICal_Event($this->getEventId());
        
        $arrNewLists = array();
        $arrNewGroups = array();
        $arrNewFBUsers = array();
        $recipients = $this->getRecipients();
        
        if ( $this->allowGroups ) {
            $arrGroups = $objEvent->getAttendee()->getObjectsIdsList('group');
            $arrNewGroups = array_diff( $recipients['valid']['groups'], $arrGroups );            
            $this->insertAttendeeObjAsGroup( $arrNewGroups, 'group' );
        }
        
        if ( $this->allowLists ) {
            $arrLists = $objEvent->getAttendee()->getObjectsIdsList('list');
            $arrNewLists = array_diff( $recipients['valid']['contactLists'], $arrLists );            
            $this->insertAttendeeObjAsGroup( $arrNewLists, 'list' );
        }
        if ( FACEBOOK_USED ) {
            $arrFBUsers = $objEvent->getAttendee()->getObjectsIdsList('fbuser');
            $arrNewFBUsers = array_diff( $recipients['valid']['fbusers'], $arrFBUsers );            
            $this->insertAttendeeObjAsGroup( $arrNewFBUsers, 'fbuser' );
        }
        
        if ( $mode == 'UPDATE' ) {
            $objEvent->getAttendee()->deleteObjects('group', $recipients['valid']['groups']);
            $objEvent->getAttendee()->deleteObjects('list', $recipients['valid']['contactLists']);        
            $objEvent->getAttendee()->deleteObjects('fbuser', $recipients['valid']['fbusers']);
        }
    }
    /**
     * create string of logins and emails from user recipients separated by comma
     * used to save as invite_to field
     * @return string
     */
    protected function __prepareInviteTo()
    {
        $recipients = $this->getRecipients();
        $recipients = array_unique( array_merge( $recipients['valid']['users'], $recipients['valid']['guests'] ) );
        return join(', ',$recipients);
        
    }    
    /**
     * Compare two invitations objects 
     * @return boolean
     */
    public function __equals(Warecorp_ICal_Invitation $objInvite)
    {
        if ( $this->getFrom() != $objInvite->getFrom() )                                    return false;
        if ( $this->getSubject() != $objInvite->getSubject() )                              return false;
        if ( $this->getMessage() != $objInvite->getMessage() )                              return false;
        if ( $this->getAllowGuestToInvite() != $objInvite->getAllowGuestToInvite() )        return false;
        if ( $this->getDisplayListToGuest() != $objInvite->getDisplayListToGuest() )        return false;
        if ( $this->isAnybodyJoin() != $objInvite->isAnybodyJoin())                         return false;
        if ( $this->getReceiveNoRsvpEmail() != $objInvite->getReceiveNoRsvpEmail())         return false;
        
        $curRsvp = $this->getEvent()->getMaxRsvp();
        $objRsvp = $objInvite->getEvent()->getMaxRsvp();
        if ( $curRsvp !== NULL && $objRsvp === NULL || $curRsvp !== NULL && $curRsvp < $objRsvp ) return false;
        
        $recipients = $this->getRecipients();
                
        $arrUsers = Warecorp_ICal_Invitation::prepareRecipientsFromString($objInvite->getEvent()->getCreator(), $objInvite->getTo());        
        $arrLists   = $objInvite->getEvent()->getAttendee()->getObjectsIdsList('list');
        $arrGroups  = $objInvite->getEvent()->getAttendee()->getObjectsIdsList(Warecorp_ICal_Enum_OwnerType::GROUP);
        $arrFBUsers = $objInvite->getEvent()->getAttendee()->getObjectsIdsList('fbuser');

        if ( sizeof(array_diff($arrUsers['valid']['users'], $recipients['valid']['users'])) != 0 || sizeof(array_diff($recipients['valid']['users'], $arrUsers['valid']['users'])) != 0 )           return false;
        if ( sizeof(array_diff($arrUsers['valid']['guests'], $recipients['valid']['guests'])) != 0 || sizeof(array_diff($recipients['valid']['guests'], $arrUsers['valid']['guests'])) != 0 )       return false;
        if ( sizeof(array_diff($arrLists, $recipients['valid']['contactLists'])) != 0 || sizeof(array_diff($recipients['valid']['contactLists'], $arrLists)) != 0 )                                 return false;
        if ( sizeof(array_diff($arrGroups, $recipients['valid']['groups'])) != 0 || sizeof(array_diff($recipients['valid']['groups'], $arrGroups)) != 0 )                                           return false;
        if ( sizeof(array_diff($arrFBUsers, $recipients['valid']['fbusers'])) != 0 || sizeof(array_diff($recipients['valid']['fbusers'], $arrFBUsers)) != 0 )                                       return false;

        return true;
    }
    /**
     * Return array of recipients for invitation
     * @return array
     */
    public function getRecipients()
    {
        if ( null === $this->recipients ) {
            $returns = array();
            $returns['isValid'] = true;
            
            $returns['all']['users']                = array();
            $returns['all']['guests']               = array();
            
            $returns['valid']['users']              = array();
            $returns['valid']['guests']             = array();
            $returns['valid']['groups']             = array();
            $returns['valid']['contactLists']       = array();
            $returns['valid']['fbusers']            = array();
    
            $returns['invalid']['userEmails']       = array();
            $returns['invalid']['userNames']        = array();
            $returns['invalid']['groupEmails']      = array();
            $returns['invalid']['groupNames']       = array();
            $returns['invalid']['groupAccess']      = array();
            $returns['invalid']['contactListNames'] = array();
            
            $this->recipients = $returns;
        }
        return $this->recipients;
    }    
    /**
     * 
     * @param array $recipients array returned from self::prepareRecipientsFromString()
     * @param array $arrGroups array of id of groups
     * @param array $arrLists array of id of lists
     * @param $arrFBUsers
     * @return void
     */
    public function setRecipients($recipients, $arrGroups = null, $arrLists = null, $arrFBUsers = null)
    {
        /* add users */        
        $this->recipients = $recipients;
        $this->recipients['all']['users'] = array_keys( $this->recipients['valid']['users'] );
        $this->recipients['all']['guests'] = $this->recipients['valid']['guests'];
        
        /* add creator of event */
        $this->recipients['all']['users'][] = $this->getEvent()->getCreator()->getEmail();
        
        /* add groups and group members */
        if ( null !== $arrGroups && is_array($arrGroups) && sizeof($arrGroups) != 0) {
            $this->recipients['valid']['groups'] = array_unique( array_merge($this->recipients['valid']['groups'], $arrGroups) );
                        
            if ( sizeof($this->recipients['valid']['groups']) != 0 ) {
                foreach ( $this->recipients['valid']['groups'] as $ind => $groupId ) {
                    $objGroup = Warecorp_Group_Factory::loadById( $groupId );
                    if ( !empty($objGroup) && $objGroup->getId() ) {
                        $this->recipients['all']['users'] = array_unique( array_merge( $this->recipients['all']['users'], $objGroup->getMembers()->setMembersStatus('approved')->getEmailsList() ) );
                    }
                }
            }
        }
        
        /* add mailing lists and members */
        if ( null !== $arrLists && is_array($arrLists) && sizeof($arrLists) != 0) {
            $this->recipients['valid']['contactLists'] = array_unique( array_merge($this->recipients['valid']['contactLists'], $arrLists) );
            
            $arrEmails = array();
            if ( sizeof($this->recipients['valid']['contactLists']) != 0 ) {
                foreach ( $this->recipients['valid']['contactLists'] as $ind => $listId ) {
                    $objList = new Warecorp_User_Addressbook_ContactList(false, 'id', $listId);
                    if ( !empty($objList) && $objList->getContactListId() ) {
                        $arrEmails = array_unique( array_merge( $arrEmails, $objList->getContacts()->getEmailsList() ) );
                    }
                }
                $arrEmails = array_diff($arrEmails, $this->recipients['all']['users'], $this->recipients['all']['guests']);
            }
            if ( sizeof($arrEmails) != 0 ) {            
                $DbConn = Zend_Registry::get('DB');
                
                /* find registered users by email */            
                $query = $DbConn->select();
                $query->from('zanby_users__accounts zua', array('zua.email', 'zua.email'));
                $query->where('zua.status != ?', 'deleted');
                $query->where('zua.email IN (?)', $arrEmails);
                
                $arrRegistered = $DbConn->fetchPairs($query);                
                $arrGuests = array_diff( $arrEmails, $arrRegistered );
                
                $this->recipients['all']['users'] = array_unique( array_merge( $this->recipients['all']['users'], $arrRegistered ) );
                $this->recipients['all']['guests'] = array_unique( array_merge( $this->recipients['all']['guests'], $arrGuests ) );
                
                unset($arrEmails, $arrRegistered, $arrGuests);
            }
        
        }
        
        /* add facebook users */
        if ( FACEBOOK_USED ) {
            if ( null !== $arrFBUsers && is_array($arrFBUsers) && sizeof($arrFBUsers) != 0 ) {
                $this->recipients['valid']['fbusers'] = array_unique( array_merge($this->recipients['valid']['fbusers'], $arrFBUsers) );
            }
        }
        
        $this->recipients['all']['users'] = array_unique( $this->recipients['all']['users'] );
        $this->recipients['all']['guests'] = array_unique( $this->recipients['all']['guests'] );
    }     
    /**
     * 
     * @param array $recipients array returned from self::prepareRecipientsFromString()
     * @param array $arrGroups array of id of groups
     * @param array $arrLists array of id of lists
     * @param $arrFBUsers
     * @return void
     */
    public function mergeRecipients($recipients, $arrGroups = null, $arrLists = null, $arrFBUsers = null)
    {
        $this->setRecipients($recipients, $arrGroups, $arrLists, $arrFBUsers);
        
        $strInviteTo = $this->__prepareInviteTo();
        if ( $this->getTo() ) $strInviteTo = $this->getTo().', '.$strInviteTo;
        $strInviteTo = join( ', ', array_unique( explode(', ', $strInviteTo) ) );
        
        $data = array();
        $data['invite_to'] = $strInviteTo;
        if ( null !== $this->getId() ) {
            $where = $this->DbConn->quoteInto('invite_id = ?', $this->getId());
            $this->DbConn->update('calendar_event_invitations', $data, $where);
            //$this->__updateInviteObjects( 'ADD' );
        }  
    }     
    /**
     * 
     * @param $recipients
     * @param $arrGroups
     * @param $arrLists
     * @param $arrFBUsers
     * @return unknown_type
     */ 
    public function diffRecipients($recipients, $arrGroups = null, $arrLists = null, $arrFBUsers = null)
    {
        $rs = explode(', ', $this->getTo());
        $rs = array_diff( $rs, $recipients['valid']['users'], array_keys($recipients['valid']['users']), $recipients['valid']['guests'] );
        $rs = join(', ', $rs);

        $data = array();
        $data['invite_to'] = $rs;
        if ( null !== $this->getId() ) {
            $where = $this->DbConn->quoteInto('invite_id = ?', $this->getId());
            $this->DbConn->update('calendar_event_invitations', $data, $where);
            //$this->__updateInviteObjects( 'ADD' );
        } 
    }
    
    /**
     * 
     * @param string $strEmails
     * @return array
     * @see Warecorp_Mail_Template::validateRecipientsFormString()
     * @author Artem Sukharev
     */
    static public function prepareRecipientsFromString( Warecorp_User $objUser, $strEmails = '' )
    {
        $DbConn = Zend_Registry::get('DB');

        $returns = array();
        $returns['isValid'] = true;
        
        $returns['all']['users']                = array();
        $returns['all']['guests']               = array();
        
        $returns['valid']['users']              = array();
        $returns['valid']['guests']             = array();
        $returns['valid']['groups']             = array();
        $returns['valid']['contactLists']       = array();
        $returns['valid']['fbusers']            = array();

        $returns['invalid']['userEmails']       = array();
        $returns['invalid']['userNames']        = array();
        $returns['invalid']['groupEmails']      = array();
        $returns['invalid']['groupNames']       = array();
        $returns['invalid']['groupAccess']      = array();
        $returns['invalid']['contactListNames'] = array();            
        
        if ( trim($strEmails) == '' ) return $returns;
        
        $groupEmailsToCheck = array();
        $strEmails = str_replace(' ', '', $strEmails);
        $recipients = preg_split("/,|\n/im", $strEmails);
        $recipients = array_map('trim', $recipients);
        foreach ( $recipients as $ind => $rcp ) if ( empty($rcp) ) unset( $recipients[$ind] );
        if ( sizeof($recipients) != 0 ) {
            /* find registered users by email and login */            
            $query = $DbConn->select();
            $query->from('zanby_users__accounts zua', array('zua.email', 'zua.login'));
            $query->where('zua.status != ?', 'deleted');
            $query->where('(zua.login IN (?)', $recipients);
            $query->orWhere('zua.email IN (?))', $recipients);
            $returns['valid']['users'] = $DbConn->fetchPairs($query);

            // Make users search case insensetive
            $func = create_function('&$value,$idx', '$value = strtolower($value);');
            if ( FALSE !== $func ) {
                $recipientsOrig = $recipients;
                array_walk($recipients, $func);
                $recipients = array_combine($recipientsOrig, $recipients);

                $emails = array_keys($returns['valid']['users']);
                $logins = $returns['valid']['users'];
                if ( !empty($emails) ) {
                    $emailsOrig = $emails;
                    array_walk($emails, $func);
                    $emails = array_combine($emailsOrig, $emails);

                    $loginsOrig = $logins;
                    array_walk($logins, $func);
                    $logins = array_combine($loginsOrig, $logins);

                    $recipients = array_unique($recipients);
                    $emails     = array_unique($emails);
                    $logins     = array_unique($logins);
                }
                unset($recipientsOrig, $emailsOrig, $loginsOrig);

                /* find CASE INSENSETIVE guests emails and logins */
                $returns['valid']['guests'] = array_diff(array_values($recipients), array_values($logins), array_values($emails));
            } else {
                /* find guests emails and logins */
                $returns['valid']['guests'] = array_diff($recipients, $returns['valid']['users'], array_keys($returns['valid']['users']));
            }
            if ( sizeof($returns['valid']['guests']) != 0 ) {
                foreach ( $returns['valid']['guests'] as $ind => $guest ) {
                    if ( FALSE !== $func ) {
                        $guest = array_search($guest, $recipients); //  Back guest value to User entered case
                    }
                    if ( false === strpos($guest, "@") ) {
                        /* incorrect user login */
                        $returns['invalid']['userNames'][] = $guest;
                        $returns['isValid'] = false;
                        unset($returns['valid']['guests'][$ind]);                        
                    } elseif ( !Warecorp_Mail_Template::validateEmailAddress($guest) ) {
                        /* incorrect email address */
                        $returns['invalid']['userEmails'][] = $guest;
                        $returns['isValid'] = false;
                        unset($returns['valid']['guests'][$ind]);
                    } elseif ( preg_match('/@'.DOMAIN_FOR_GROUP_EMAIL.'$/i', $guest) ) {
                        /* it's group email address */
                        $groupEmailsToCheck[] = preg_replace('/@'.DOMAIN_FOR_GROUP_EMAIL.'$/mi', '', $guest);
                        unset($returns['valid']['guests'][$ind]);
                    } else {
                        /* it's user email address */
                    }
                }
            }

            if ( sizeof($groupEmailsToCheck) != 0 ) {
                $query = $DbConn->select();
                $query->from('zanby_groups__items as zgi', array('zgi.id', 'zgi.group_path'));
                $query->where('zgi.group_path IN (?)', $groupEmailsToCheck);
                $returns['valid']['groups'] = $DbConn->fetchPairs($query);
                
                $returns['invalid']['groupEmails'] = array_diff($groupEmailsToCheck, $returns['valid']['groups']);
                $returns['invalid']['groupEmails']= array_map(array('Warecorp_ICal_Invitation','callback_addDomain'), $returns['invalid']['groupEmails']);
                if ( sizeof($returns['invalid']['groupEmails']) != 0 ) $returns['isValid'] = false;
                
                if ( sizeof($returns['valid']['groups']) != 0 ) {
                    foreach ( $returns['valid']['groups'] as $groupId => $groupPath ) {
                        $objGroup = Warecorp_Group_Factory::loadByPath( $groupPath );
                        /* check access */
                        if ( !$objGroup || !$objGroup->getId() || !$objGroup->getMembers()->isMemberExistsAndApproved($objUser->getId()) ) {
                            unset($returns['valid']['groups'][$groupId]);
                            $returns['invalid']['groupAccess'][] = $objGroup->getName();
                            $returns['isValid'] = false;
                        }
                    }
                    $returns['valid']['groups'] = array_keys($returns['valid']['groups']);
                }
            }
        }

        return $returns;
    }    
    
    /**
     * find emails to new attendee for registered users from array of emails
     * @param array $arrEmails - array of all emails to check
     * @param int $eventID
     * @return array of emails - emails of registered users to add new attendee
     * @author Artem Sukharev
     */
    static public function prepareListOfNewUserAttendeeEmails( $arrEmails, $eventID )
    {
        if ( sizeof($arrEmails) != 0 ) {                        
            $DbConn = Zend_Registry::get('DB');
            $query = $DbConn->select();
            $query->from('calendar_event_attendee', array('zua.email', 'zua.email'));
            $query->where('attendee_event_id = ?', $eventID);
            $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);            
            $query->where('attendee_email IS NULL');
            $query->joinInner('zanby_users__accounts zua', 'zua.id = attendee_owner_id', array());
            $query->where('zua.email IN (?)', $arrEmails);            
            $results = $DbConn->fetchPairs($query);
            if ( sizeof($results) != 0 ) {
                $arrEmails = array_diff($arrEmails, $results);
            }
        }
        return $arrEmails;
    }
    
    /**
     * find emails to new attendee for UNregistered users from array of emails
     * @param array $arrEmails - array of all emails to check
     * @param int $eventID
     * @return array of emails - emails of UNregistered users to add new attendee
     * @author Artem Sukharev
     */
    static public function prepareListOfNewGuestAttendeeEmails( $arrEmails, $eventID )
    {
        if ( sizeof($arrEmails) != 0 ) {
            $DbConn = Zend_Registry::get('DB');
            $query = $DbConn->select();
            $query->from('calendar_event_attendee', array('attendee_email', 'attendee_email'));
            $query->where('attendee_event_id = ?', $eventID);
            $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);            
            $query->where('attendee_email IN (?)', $arrEmails);
            $query->where('attendee_owner_id IS NULL');            
            $results = $DbConn->fetchPairs($query);
            if ( sizeof($results) != 0 ) { 
                $arrEmails = array_diff($arrEmails, $results); 
            }                                     
        }
        return $arrEmails;
    }
    
    /**
     * find ids to new attendee for objects from array of ids
     * @param $type
     * @param int $eventID
     * @return array of ids - ids of objects to add new attendee
     * @author Artem Sukharev
     */
    static public function prepareListOfNewObjectsAttendee( $type, $arrIds, $eventID )
    {
        if ( sizeof($arrIds) != 0 ) {
            $DbConn = Zend_Registry::get('DB');
            $query = $DbConn->select();
            $query->from('calendar_event_attendee', array('attendee_id', 'attendee_owner_id'));
            $query->where('attendee_event_id = ?', $eventID);
            $query->where('attendee_owner_type = ?', $type);            
            $query->where('attendee_owner_id IN (?)', $arrIds);            
            $results = $DbConn->fetchPairs($query);
            if ( sizeof($results) != 0 ) { 
                $arrIds = array_diff($arrIds, $results); 
            }                                     
        }
        return $arrIds;
    }
    
    /**
     * get array of attendee ids that should be revomed
     * @param $arrOfEmails - exclude these emails
     * @param int $eventID
     * @param Warecorp_User $eventCreator
     * @return unknown_type
     */
    static public function prepareListOfDeleteUserAttendeeByEmails( $arrEmails, $eventID, $eventCreator = null )
    {
        /* don't remove creator of event */
        if ( null !== $eventCreator && $eventCreator instanceof Warecorp_User ) $arrEmails[] = $eventCreator->getEmail();
        
        $DbConn = Zend_Registry::get('DB');
        $query = $DbConn->select();
        $query->from('calendar_event_attendee', array('attendee_id', 'zua.email'));
        $query->where('attendee_event_id = ?', $eventID);
        $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);            
        $query->where('attendee_email IS NULL');
        $query->joinInner('zanby_users__accounts zua', 'zua.id = attendee_owner_id', array());
        if ( sizeof($arrEmails) != 0 ) { $query->where('zua.email NOT IN (?)', $arrEmails); }            
        $results = $DbConn->fetchPairs($query);           
        return $results;    
    }
    
    /**
     * get array of attendee ids that should be revomed
     * @param $arrEmails - exclude these emails
     * @param int $eventID
     * @return unknown_type
     */
    static public function prepareListOfDeleteGuestAttendeeByEmails( $arrEmails, $eventID )
    {
        $DbConn = Zend_Registry::get('DB');
        $query = $DbConn->select();
        $query->from('calendar_event_attendee', array('attendee_id', 'attendee_email'));
        $query->where('attendee_event_id = ?', $eventID);
        $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);            
        $query->where('attendee_email IS NOT NULL');
        if ( sizeof($arrEmails) != 0 ) { $query->where('attendee_email NOT IN (?)', $arrEmails); }
        $query->where('attendee_owner_id IS NULL');            
        $results = $DbConn->fetchPairs($query);
        return $results;    
    }
    
    /**
     * get array of attendee ids that should be revomed
     * @param $arrIDs - attendee to delete
     * @param int $eventID
     * @param Warecorp_User $eventCreator
     * @return unknown_type
     */
    static public function prepareListOfDeleteUserAttendeeByIds( $arrIDs, $eventID, $eventCreator = null )
    {
        /* don't remove creator of event */
        $creatorEmail = null;
        if ( null !== $eventCreator && $eventCreator instanceof Warecorp_User ) $creatorEmail = $eventCreator->getEmail();
        
        $DbConn = Zend_Registry::get('DB');
        $query = $DbConn->select();
        $query->from('calendar_event_attendee', array('attendee_id', 'zua.email'));
        $query->where('attendee_event_id = ?', $eventID);
        $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);            
        $query->where('attendee_email IS NULL');
        $query->where('attendee_id IN (?)', $arrIDs); 
        $query->joinInner('zanby_users__accounts zua', 'zua.id = attendee_owner_id', array());
        if ( $creatorEmail ) { $query->where('zua.email != ?', $creatorEmail); }           
        $results = $DbConn->fetchPairs($query);           
        return $results;    
    }

    /**
     * get array of attendee ids that should be revomed
     * @param $arrIDs - attendee to delete
     * @param int $eventID
     * @param Warecorp_User $eventCreator
     * @return unknown_type
     */
    static public function prepareListOfDeleteFBUserAttendeeByIds( $arrIDs, $eventID, $eventCreator = null )
    {
        $DbConn = Zend_Registry::get('DB');
        $query = $DbConn->select()
            ->from('calendar_event_attendee', array('attendee_id', 'fbuid' => 'attendee_owner_id'))
            ->where('attendee_event_id = ?', $eventID)
            ->where('attendee_owner_type = ?', 'fbuser')
            ->where('attendee_email IS NULL')
            ->where('attendee_id IN (?)', $arrIDs); 

        return $DbConn->fetchPairs($query);
    }
    
    /**
     * get array of attendee ids that should be revomed
     * @param $arrIDs - attendee to delete
     * @param int $eventID
     * @return unknown_type
     */
    static public function prepareListOfDeleteGuestAttendeeByIds( $arrIDs, $eventID )
    {
        $DbConn = Zend_Registry::get('DB');
        $query = $DbConn->select();
        $query->from('calendar_event_attendee', array('attendee_id', 'attendee_email'));
        $query->where('attendee_event_id = ?', $eventID);
        $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);            
        $query->where('attendee_email IS NOT NULL');
        $query->where('attendee_id IN (?)', $arrIDs);
        $query->where('attendee_owner_id IS NULL');            
        $results = $DbConn->fetchPairs($query);
        return $results;    
    }
    
    /**
     * validate recipients and add custom error message to form object
     * @param $recipients
     * @param Warecorp_Form $form
     * @return void
     */
    static public function validateRecipients( $recipients, Warecorp_Form &$form )
    {
        if ( !$recipients['isValid'] ) {
            if ( sizeof($recipients['invalid']['userEmails']) != 0 ) {
                $form->addCustomErrorMessage(
                    ( sizeof( $recipients['invalid']['userEmails'] ) > 1 ) ?
                    Warecorp::t("Sorry, %s are not a valid emails", join( ", ", $recipients['invalid']['userEmails'] )) :
                    Warecorp::t("Sorry, %s is not a valid email", join( ", ", $recipients['invalid']['userEmails'] )));
            }
            if ( sizeof($recipients['invalid']['userNames']) != 0 ) {
                $form->addCustomErrorMessage(
                    ( sizeof( $recipients['invalid']['userNames'] ) > 1 ) ?
                    Warecorp::t("Sorry, %s are not a valid %s usernames", array(join( ", ", $recipients['invalid']['userNames'] ), SITE_NAME_AS_STRING)) :
                    Warecorp::t("Sorry, %s is not a valid %s username", array(join( ", ", $recipients['invalid']['userNames'] ), SITE_NAME_AS_STRING)));
            }
            if ( sizeof($recipients['invalid']['groupEmails']) != 0 ) {
                $form->addCustomErrorMessage(
                    ( sizeof( $recipients['invalid']['groupEmails'] ) > 1 ) ?
                    Warecorp::t("Sorry, %s  are not a valid %s group emails", array(join( ", ", $recipients['invalid']['groupEmails'] ), SITE_NAME_AS_STRING)) :
                    Warecorp::t("Sorry, %s  is not a valid %s group email", array(join( ", ", $recipients['invalid']['groupEmails'] ), SITE_NAME_AS_STRING)));
            }
            if ( sizeof($recipients['invalid']['groupNames']) != 0 ) {
                $form->addCustomErrorMessage(
                    ( sizeof( $recipients['invalid']['groupNames'] ) > 1 ) ?
                    Warecorp::t("Sorry, %s  are not a valid %s group names", array(join( ", ", $recipients['invalid']['groupNames'] ), SITE_NAME_AS_STRING)) :
                    Warecorp::t("Sorry, %s  is not a valid %s group name", array(join( ", ", $recipients['invalid']['groupNames'] ), SITE_NAME_AS_STRING)));
            }
            if ( sizeof($recipients['invalid']['groupAccess']) != 0 ) {
                $form->addCustomErrorMessage(
                    ( sizeof( $recipients['invalid']['groupAccess'] ) > 1 ) ?
                    Warecorp::t("Sorry, you can not intite %s groups", join( ", ", $recipients['invalid']['groupAccess'] )) :
                    Warecorp::t("Sorry, you can not intite %s group", join( ", ", $recipients['invalid']['groupAccess'] )));
            }
            if ( sizeof($recipients['invalid']['contactListNames']) != 0 ) {
                $form->addCustomErrorMessage(
                    ( sizeof( $recipients['invalid']['contactListNames'] ) > 1 ) ?
                    Warecorp::t("Sorry, %s  are not a valid mailing list names", join( ", ", $recipients['invalid']['contactListNames'] )) :
                    Warecorp::t("Sorry, %s  is not a valid mailing list name", join( ", ", $recipients['invalid']['contactListNames'] )));
            }
        }
    }
    
    /**
     * load id,email,firstname and lastname of user by email and return array of data
     * @param $arrOfEmails
     * @return array
     * @author Artem Sukharev
     */
    static public function loadRegisteredRecipientsInfo( $arrOfEmails )
    {
        if ( sizeof($arrOfEmails) != 0 ) {
            $DbConn = Zend_Registry::get('DB');
            $query = $DbConn->select();
            $query->from('zanby_users__accounts zua', array('zua.id','zua.email','zua.firstname','zua.lastname','zua.timezone','zua.path'));
            $query->where('zua.email IN (?)', $arrOfEmails);
            $results = $DbConn->fetchAll($query);
            if ( sizeof($results) != 0 ) { return $results; }                                    
        }
        return array();
    }

    /**
     * load id,email,firstname,lastname and attendee_access_code of registered user by email and return array of data
     * @param $arrOfEmails
     * @param int $eventID
     * @return array
     * @author Artem Sukharev
     */
    static public function loadRegisteredRecipientsAttendeInfo( $arrOfEmails, $eventID )
    {
        if ( sizeof($arrOfEmails) != 0 ) {
            $DbConn = Zend_Registry::get('DB');
            $query = $DbConn->select();
            $query->from('calendar_event_attendee', array('zua.id','zua.email','zua.firstname','zua.lastname','zua.timezone','zua.path','attendee_access_code'));
            $query->where('attendee_event_id = ?', $eventID);
            $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);            
            $query->where('attendee_email IS NULL');
            $query->joinInner('zanby_users__accounts zua', 'zua.id = attendee_owner_id', array());
            $query->where('zua.email IN (?)', $arrOfEmails);            
            $results = $DbConn->fetchAll($query);
            if ( sizeof($results) != 0 ) { return $results; }
        }
        return array();
    }
    
    /**
     * load attendee_access_code of unregistered user by email and return array of data
     * @param $arrOfEmails
     * @param int $eventID
     * @return array
     * @author Artem Sukharev
     */
    static public function loadGuestRecipientsAttendeInfo( $arrOfEmails, $eventID )
    {
        if ( sizeof($arrOfEmails) != 0 ) {
            $DbConn = Zend_Registry::get('DB');            
            $query = $DbConn->select();
            $query->from('calendar_event_attendee', array('attendee_email', 'attendee_access_code'));
            $query->where('attendee_event_id = ?', $eventID);
            $query->where('attendee_owner_type = ?', Warecorp_ICal_Enum_OwnerType::USER);            
            $query->where('attendee_email IN (?)', $arrOfEmails);
            $query->where('attendee_owner_id IS NULL');            
            $results = $DbConn->fetchAll($query);
            if ( sizeof($results) != 0 ) { return $results; }
        }
        return array();
    }
    
    /**
     * insert new attendees for object for event as one query
     * @param $arrObjIds
     * @param $type type of object
     * @return void
     */
    protected function insertAttendeeObjAsGroup( $arrObjIds, $type )
    {
        if ( sizeof($arrObjIds) != 0 ) {
            
            $objEvent = $this->getEvent();
            //$objRootEvent = new Warecorp_ICal_Event($objEvent->getRootId());
            
            $DbConn = Zend_Registry::get('DB');
            $insert = 'INSERT INTO calendar_event_attendee (
                attendee_event_id, attendee_owner_type, attendee_owner_id,
                attendee_answer, attendee_answer_text, attendee_access_code, attendee_key
            ) VALUES ';
                        
            $obj_values = array();
            foreach ( $arrObjIds as &$objId ) {
                $access_code = Warecorp_ICal_Attendee::generateAttendeeAccessCode();
                $values = array();
                $values[] = $DbConn->quoteInto('?', $objEvent->getId());
                $values[] = $DbConn->quoteInto('?', $type);
                $values[] = $DbConn->quoteInto('?', $objId);                
                $values[] = $DbConn->quoteInto('?', 'NONE');
                $values[] = $DbConn->quoteInto('?', '');
                $values[] = $DbConn->quoteInto('?', $access_code);
                $values[] = $DbConn->quoteInto('?', $type.'_'.$objId.'_');
                $obj_values[] = '('. join(',', $values) .')';
                
                if ( sizeof($obj_values) >= 1000 ) {
                    $DbConn->query($insert.join(',', $obj_values));
                    $obj_values = array();
                }
            }
            if ( sizeof($obj_values) != 0 ) $DbConn->query($insert.join(',', $obj_values));     

            if ( FACEBOOK_USED && $type == 'fbuser' ) {
                $event_url = $objEvent->entityURL().'m/fb';
                
                /**
                 * Facebook Notification
                 */
                $notification = "
has invited you to event ".$objEvent->getTitle()." on ".SITE_NAME_AS_STRING.". <br>
<a href='".$event_url."'>Click here</a> to view the event and RSVP.";
                Warecorp_Facebook_Feed::postNotification($arrObjIds, $notification);
                /*
                 * Facebook Email Notification
                 */
                $message_subject = SITE_NAME_AS_STRING." invitation to event";
                $message_body = "
Hello, <br> 
you are invited to event ".$objEvent->getTitle()." on ".SITE_NAME_AS_STRING.". <br>
You can RSVP to the invitation by clicking the link below:  <br>
".$event_url." <br><br>
    
Thanks,<br>
".SITE_NAME_AS_STRING." <br> 
Calendars";
                Warecorp_Facebook_Feed::postEmail($arrObjIds, $message_subject, $message_body, $message_body);            
            }        
        }
    }        
    
    /**
     * insert new attendees for registered for event as one query and send invitation email
     * @param $arrRegRecipientsInfo
     * @param $msrvRecipientsToInvite
     * @return void
     */
    protected function insertAttendeeForRegisteredUser( &$msrvRecipientsToInvite, $client, $campaignUID, $arrRegRecipients )
    {        
        if ( sizeof($arrRegRecipients) != 0 ) {
            
            $objEvent = $this->getEvent();
            $objRootEvent = new Warecorp_ICal_Event($objEvent->getRootId());
            $creatorEmail = $objEvent->getCreator()->getEmail();
            
            $DbConn = Zend_Registry::get('DB');
            $insert = 'INSERT INTO calendar_event_attendee (
                attendee_event_id, attendee_owner_type, attendee_owner_id,
                attendee_answer, attendee_answer_text, attendee_access_code, attendee_key
            ) VALUES ';
            $user_values = array();
            
            $arrRegRecipients = array_chunk( $arrRegRecipients, 100 );
            foreach ( $arrRegRecipients as $arrEmails ) {
                $arrRegRecipientsInfo = self::loadRegisteredRecipientsInfo( $arrEmails );                
                foreach ( $arrRegRecipientsInfo as &$userInfo ) {
                    $access_code = Warecorp_ICal_Attendee::generateAttendeeAccessCode();
                    $values = array();
                    $values[] = $DbConn->quoteInto('?', $objEvent->getId());
                    $values[] = $DbConn->quoteInto('?', Warecorp_ICal_Enum_OwnerType::USER);
                    $values[] = $DbConn->quoteInto('?', $userInfo['id']);                

                    /* Organizer attendee set to YES for ZCCF ONLY! */

                    if ( Warecorp::checkHttpContext('zccf') && $creatorEmail === $userInfo['email']) {
                        $values[] = $DbConn->quoteInto('?', 'YES');
                    } else {
                        $values[] = $DbConn->quoteInto('?', 'NONE');
                    }
                    /* Organizer attendee set to YES for ZCCF ONLY! */

                    $values[] = $DbConn->quoteInto('?', '');
                    $values[] = $DbConn->quoteInto('?', $access_code);
                    $values[] = $DbConn->quoteInto('?', Warecorp_ICal_Enum_OwnerType::USER.'_'.$userInfo['id'].'_');
                    $user_values[] = '('. join(',', $values) .')';
                    
                    /* share event to invited user to user can view it in his celebrations list */
                    if ( !$objRootEvent->getSharing()->isUserShared($userInfo['id']) ) $objRootEvent->getSharing()->addUser($userInfo['id']);
                    
                    if ( $client !== null && $campaignUID !== null ) {
                        /* don't send invitation to event creator */
                        if ( $userInfo['id'] != $objEvent->getCreatorId() ) {
                            $this->__addInvite($msrvRecipientsToInvite, $client, $campaignUID, $userInfo, $access_code);
                        }
                    }
                    
                    if ( sizeof($user_values) >= 1000 ) {
                        $DbConn->query($insert.join(',', $user_values));
                        $user_values = array();
                    }
                    
                    /**/
                    $this->recipientsToInvite[] = $userInfo['id'];
                }
            }
            if ( sizeof($user_values) != 0 ) $DbConn->query($insert.join(',', $user_values));
        }
    }
    
    /**
     * insert new attendees for unregistered for event as one query and send invitation email
     * @param $arrUnregRecipients
     * @param $msrvRecipientsToInvite
     * @return void
     */
    protected function insertAttendeeForUnregisteredUser( &$msrvRecipientsToInvite, $client, $campaignUID, $arrUnregRecipients )
    {        
        if ( sizeof($arrUnregRecipients) != 0 ) {
            
            $objEvent = $this->getEvent();
            
            $DbConn = Zend_Registry::get('DB');
            $insert = 'INSERT INTO calendar_event_attendee (
                attendee_event_id, attendee_owner_type, attendee_email,
                attendee_answer, attendee_answer_text, attendee_access_code, attendee_key
            ) VALUES ';
            $user_values = array();
            foreach ( $arrUnregRecipients as &$userInfo ) {
                $access_code = Warecorp_ICal_Attendee::generateAttendeeAccessCode();
                $values = array();
                $values[] = $DbConn->quoteInto('?', $objEvent->getId());
                $values[] = $DbConn->quoteInto('?', Warecorp_ICal_Enum_OwnerType::USER);
                $values[] = $DbConn->quoteInto('?', $userInfo);                
                $values[] = $DbConn->quoteInto('?', 'NONE');
                $values[] = $DbConn->quoteInto('?', '');
                $values[] = $DbConn->quoteInto('?', $access_code);
                $values[] = $DbConn->quoteInto('?', Warecorp_ICal_Enum_OwnerType::USER.'__'.$userInfo);
                $user_values[] = '('. join(',', $values) .')';
                
                if ( $client !== null && $campaignUID !== null ) {
                    $this->__addInvite($msrvRecipientsToInvite, $client, $campaignUID, array('id' => null, 'email' => $userInfo), $access_code);
                }
            
                if ( sizeof($user_values) >= 1000 ) {
                    $DbConn->query($insert.join(',', $user_values));
                    $user_values = array();
                }
                
                /**/
                $this->recipientsToInvite[] = $userInfo;
            }
            if ( sizeof($user_values) != 0 ) $DbConn->query($insert.join(',', $user_values));
        }
    }

    /**
     * remove attendees for registered user and send notification email
     * @param $arrRegRecipientsInfo
     * @param $msrvRecipientsToInvite
     * @return void
     */
    protected function removeAttendeeForRegisteredUser( &$msrvRecipientsToRemove, $client, $campaignUID, $arrAttendees )
    {        
        if ( sizeof($arrAttendees) != 0 ) {
                        
            $objEvent = $this->getEvent();
            $objRootEvent = new Warecorp_ICal_Event($objEvent->getRootId());
            
            /* delete attendee */
            if ( sizeof($arrAttendees) != 0 ) {
                $objAttendeeList = new Warecorp_ICal_Attendee_List( $objEvent );
                $objAttendeeList->deleteByAttendeeIds( array_keys($arrAttendees) );  
            }
             
            $arrAttendees = array_chunk( $arrAttendees, 100 );
            foreach ( $arrAttendees as $arrEmails ) {
                $arrAttendeesInfo = self::loadRegisteredRecipientsInfo( $arrEmails );                
                foreach ( $arrAttendeesInfo as &$userInfo ) {
                                        
                    /* remove share event to deleted user */
                    if ( $objRootEvent->getSharing()->isUserShared($userInfo['id']) ) $objRootEvent->getSharing()->deleteUser($userInfo['id']);
                    
                    if ( $client !== null && $campaignUID !== null && $this->getSendNotification() ) {
                        $this->__addNotificationToRemovedUser($msrvRecipientsToRemove, $client, $campaignUID, $userInfo);
                    }
                    
                    /**/
                    $this->recipientsToRemove[] = $userInfo['id'];
                }
            }
        }
    }
    
    /**
     * remove attendees for unregistered user and send notification email
     * @param $arrRegRecipientsInfo
     * @param $msrvRecipientsToInvite
     * @return void
     */
    protected function removeAttendeeForUnregisteredUser( &$msrvRecipientsToRemove, $client, $campaignUID, $arrAttendees )
    {        
        if ( sizeof($arrAttendees) != 0 ) {
                        
            $objEvent = $this->getEvent();
            
            /* delete attendee */
            if ( sizeof($arrAttendees) != 0 ) {
                $objAttendeeList = new Warecorp_ICal_Attendee_List( $objEvent );
                $objAttendeeList->deleteByAttendeeIds( array_keys($arrAttendees) );  
            }
             
            foreach ( $arrAttendees as &$userInfo ) {
                if ( $client !== null && $campaignUID !== null && $this->getSendNotification() ) {
                    $this->__addNotificationToRemovedUser($msrvRecipientsToRemove, $client, $campaignUID, array('id' => null, 'email' => $userInfo));
                }
                
                /**/
                $this->recipientsToRemove[] = $userInfo;                
            }
        }
    }

    protected function removeAttendeeForFBUser( $arrAttendees )
    {
        if ( sizeof($arrAttendees) != 0 ) {
            $objAttendeeList = new Warecorp_ICal_Attendee_List( $this->getEvent() );
            $objAttendeeList->deleteFBAttendeeByIds( array_keys($arrAttendees) );  
            $this->__addNotificationToRemovedFBUser( array_values($arrAttendees) );
        }
    }
    
    
    /**
     * +----------------------------------------------------------------------
     * |
     * |    
     * |
     * +---------------------------------------------------------------------- 
     */

    /**
    * При создании основного события
    * @params array $receivers - array from Warecorp_Mail_Template::validateRecipientsFormString
    * Добавление приглашшенных пользователей в событие
    * Отправка приглашений на событие
    * Вызывается при создании события
    * Called when new main event is created
    * @return boolean
    * @see ADD_ONLY
    */
    public function __saveAttendee()
    {
        ini_set("max_execution_time", 1800);
        set_time_limit(1800);
            
        $objEvent = $this->getEvent();
        
        $recipients = $this->getRecipients();
        if ( sizeof($recipients['all']['users']) == 0 && sizeof($recipients['all']['guests']) == 0 ) return true;
        
        /* SOAP: MailSrv */  
        $msrvRecipientsToInvite = new Warecorp_SOAP_Type_Recipients();
        
        if ( $this->getSendNotification() ) list( $client, $campaignUID ) = $this->getMailCampaign( 'INVITE' );
        else $client = $campaignUID = null;
        
        /* registered users */
        if ( sizeof($recipients['all']['users']) != 0 ) {
            $this->insertAttendeeForRegisteredUser($msrvRecipientsToInvite, $client, $campaignUID, $recipients['all']['users'] );
        }
        
        /* guests */
        if ( sizeof($recipients['all']['guests']) != 0 ) {
            $this->insertAttendeeForUnregisteredUser($msrvRecipientsToInvite, $client, $campaignUID, $recipients['all']['guests']);
        }        

        /* SOAP: MailSrv */
        /* add recipients to MAILSRV and start campaign */
        if ( $client !== null && $campaignUID !== null ) {
            if ( $msrvRecipientsToInvite->getCount() > 0 ) $client->addRecipients($campaignUID, $msrvRecipientsToInvite);
            $msrvRecipientsToInvite->clean();
            
            /* add callback to mailsrv campaign to sent PMB message */
            $objCallback = new Warecorp_SOAP_Type_Callback();
            $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
            $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
            $objCallback->setAction( 'callbackAddPMBMessage' );
            $callbackUID = $client->addCallback($campaignUID, $objCallback);

            /* Change PMB Subject by event creator defined. For ZCCF ONLY */
            if ( Warecorp::checkHttpContext('zccf') && $this->getSubject() ) {
                $client->addPMBSubject($campaignUID, $this->getSubject());
            }

            $this->recipientsToInvite = ( null === $this->recipientsToInvite ) ? array() : $this->recipientsToInvite;
            $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
            $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
            $client->addCallbackParam($callbackUID, 'sender_id', $this->getMailSender()->getId());
            $client->addCallbackParam($callbackUID, 'sender_type', ($this->getMailSender() instanceof Warecorp_User) ? 'user' : 'group');
            $client->addCallbackParam($callbackUID, 'recipients', join(';', $this->recipientsToInvite) );
            unset( $this->recipientsToInvite ); 
            
            $request = $client->startCampaign($campaignUID);
        }
        
        return true;
    }
    
    /**
    * При редактировании основного события
    * @param string $mode - UPDATE|ADD
    * Отправляется, если объект приглашения был изменен для события
    * отправляет новые приглашения новым пользователям
    * отправляет уведомления удаленным пользователям
    * Called when main event was changed
    * @see ADD_AND_UPDATE
    */
    public function __saveAttendeeChanges( $mode = 'UPDATE' )
    {
        ini_set("max_execution_time", 1800);
        set_time_limit(1800);
        
        $objEvent = $this->getEvent();
        
        $recipients = $this->getRecipients();
        $arrNewUsers = $this->prepareListOfNewUserAttendeeEmails( $recipients['all']['users'], $objEvent->getId() );
        $arrNewGuests = $this->prepareListOfNewGuestAttendeeEmails( $recipients['all']['guests'], $objEvent->getId() );

        if ( FACEBOOK_USED ) {
            $arrFBUsers = $objEvent->getAttendee()->getObjectsIdsList('fbuser');
            $arrNewFBUsers = array_diff( $recipients['valid']['fbusers'], $arrFBUsers );
            if ( $arrNewFBUsers )
                $this->insertAttendeeObjAsGroup( $arrNewFBUsers, 'fbuser' );
        }

        /* add new attendees */
        if ( sizeof($arrNewUsers) != 0 || sizeof($arrNewGuests) != 0 ) {
            
            /* SOAP: MailSrv */
            $msrvRecipientsToInvite = new Warecorp_SOAP_Type_Recipients();
            
            if ( $this->getSendNotification() ) list( $client, $campaignUID ) = $this->getMailCampaign( 'INVITE' );
            else $client = $campaignUID = null;
            
            /* registered users */
            if ( sizeof($arrNewUsers) != 0 ) {            
                $this->insertAttendeeForRegisteredUser($msrvRecipientsToInvite, $client, $campaignUID, $arrNewUsers );                        
            }
            
            /* guests */
            if ( sizeof($arrNewGuests) != 0 ) {
                $this->insertAttendeeForUnregisteredUser($msrvRecipientsToInvite, $client, $campaignUID, $arrNewGuests );            
            }        
    
            /* SOAP: MailSrv */
            /* add recipients to MAILSRV and start campaign */
            if ( $client !== null && $campaignUID !== null ) {
                if ( $msrvRecipientsToInvite->getCount() > 0 ) $client->addRecipients($campaignUID, $msrvRecipientsToInvite);
                $msrvRecipientsToInvite->clean();
                
                /* add callback to mailsrv campaign to sent PMB message */
                $objCallback = new Warecorp_SOAP_Type_Callback();
                $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                $objCallback->setAction( 'callbackAddPMBMessage' );
                $callbackUID = $client->addCallback($campaignUID, $objCallback);

                /* Change PMB Subject by event creator defined. For ZCCF ONLY */
                if ( Warecorp::checkHttpContext('zccf') && $this->getSubject() ) {
                    $client->addPMBSubject($campaignUID, $this->getSubject());
                }

                $this->recipientsToInvite = ( null === $this->recipientsToInvite ) ? array() : $this->recipientsToInvite;
                $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                $client->addCallbackParam($callbackUID, 'sender_id', $this->getMailSender()->getId());
                $client->addCallbackParam($callbackUID, 'sender_type', ($this->getMailSender() instanceof Warecorp_User) ? 'user' : 'group');
                $client->addCallbackParam($callbackUID, 'recipients', join(';', $this->recipientsToInvite) );
                unset( $this->recipientsToInvite ); 
            
                $request = $client->startCampaign($campaignUID);
            }
        }
        
        /* remove attendee for removed contacts */
        if ( $mode == 'UPDATE' ) {
            $arrDeletedUsers = $this->prepareListOfDeleteUserAttendeeByEmails( $recipients['all']['users'], $objEvent->getId(), $objEvent->getCreator() );
            $arrDeletedGuests = $this->prepareListOfDeleteGuestAttendeeByEmails( $recipients['all']['guests'], $objEvent->getId() );
            
            if ( sizeof($arrDeletedUsers) != 0 || sizeof($arrDeletedGuests) != 0 ) {
                /* SOAP: MailSrv */
                $msrvRecipientsToRemove = new Warecorp_SOAP_Type_Recipients();
                
                if ( $this->getSendNotification() ) list( $client, $campaignUID ) = $this->getMailCampaign( 'REMOVE' );
                else $client = $campaignUID = null;

                if ( sizeof($arrDeletedUsers) != 0 ) {
                    $this->removeAttendeeForRegisteredUser( $msrvRecipientsToRemove, $client, $campaignUID, $arrDeletedUsers );
                }

                if ( sizeof($arrDeletedGuests) != 0 ) {
                    $this->removeAttendeeForUnregisteredUser( $msrvRecipientsToRemove, $client, $campaignUID, $arrDeletedGuests );
                }
                
                /* SOAP: MailSrv */
                /* add recipients to MAILSRV and start campaign */
                if ( $client !== null && $campaignUID !== null ) {
                    if ( $msrvRecipientsToRemove->getCount() > 0 ) $client->addRecipients($campaignUID, $msrvRecipientsToRemove);
                    $msrvRecipientsToRemove->clean();
                    
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);

                    /* Change PMB Subject by event creator defined. For ZCCF ONLY */
                    if ( Warecorp::checkHttpContext('zccf') && $this->getSubject() ) {
                        $client->addPMBSubject($campaignUID, $this->getSubject());
                    }

                    $this->recipientsToRemove = ( null === $this->recipientsToRemove ) ? array() : $this->recipientsToRemove;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $this->getMailSender()->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($this->getMailSender() instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $this->recipientsToRemove) );
                    unset( $this->recipientsToRemove ); 
            
                    $request = $client->startCampaign($campaignUID);
                }
            }
        }
    }
    
    /**
    * При создании исключения события если НЕ изменен список приглашенных
    * Отправляется уведомление, когда создается исключение в повторяющемся событии
    * новые attendee НЕ добавляются
    * Called when new event exception was created and attendee list was not changed
    * @param boolean $useRoot - если для ислючения события создавался свой объект Invitation то надо указать false 
    * @see NOTIFY_ONLY
    */
    public function __sendAttendeeCopyCreated( $useRoot = true )
    {      
        ini_set("max_execution_time", 1800);
        set_time_limit(1800);
        
        /**
        * Т.к. сообщения отправляются от копии, то, чтобы обеспечить
        * что копия будет содержать все смерженные от основного события данные
        * мы заново создаем событие этой копии, при создании происходит автоматическое
        * смерживание события-копии с событием-оригиналом
        * при этом от основного события возьмуться title, description, attendee и другая информация
        */
        $objEvent = new Warecorp_ICal_Event($this->getEventId());
        $this->setEventId($objEvent->getId());
        $this->setEvent($objEvent);
        
        $recipients = $this->getRecipients();
        if ( sizeof($recipients['all']['users']) == 0 && sizeof($recipients['all']['guests']) == 0 ) return true;
        
        /* SOAP: MailSrv */  
        $msrvRecipientsToNotify = new Warecorp_SOAP_Type_Recipients();
        
        if ( $this->getSendNotification() ) list( $client, $campaignUID ) = $this->getMailCampaign( 'NOTIFY' );
        else $client = $campaignUID = null;
        
        /* registered users */
        if ( sizeof($recipients['all']['users']) != 0 ) {    
            $arrRecipients = array_chunk( $recipients['all']['users'], 100 );
            foreach ( $arrRecipients as $arrEmails ) {
                /** 
                 * if the invitation object has not been changed (we don't save different invitation) we use invitation object from root event
                 * if the different invitation object has been created - use it
                 * @author Artem Sukharev
                 */
                $arrRegRecipientsInfo = self::loadRegisteredRecipientsAttendeInfo( $arrEmails, $useRoot ? $objEvent->getRootId() : $objEvent->getId() );                
                foreach ( $arrRegRecipientsInfo as &$userInfo ) {
                    if ( $client !== null && $campaignUID !== null ) {
                        $this->__addNotificationEventIsChanged($msrvRecipientsToNotify, $client, $campaignUID, $userInfo, $userInfo['attendee_access_code']);
                    }                    
                    /**/
                    $this->recipientsToNotify[] = $userInfo['id'];
                }
            }
        }
        
        /* guests */
        if ( sizeof($recipients['all']['guests']) != 0 ) {
            $arrRecipients = array_chunk( $recipients['all']['guests'], 100 );
            foreach ( $arrRecipients as $arrEmails ) { 
                /**
                 * if the invitation object has not been changed (we don't save different invitation) we use invitation object from root event
                 * if the different invitation object has been created - use it
                 * @author Artem Sukharev

                 */                
                $arrGuestRecipientsInfo = self::loadGuestRecipientsAttendeInfo( $arrEmails, $useRoot ? $objEvent->getRootId() : $objEvent->getId() );
                foreach ( $arrGuestRecipientsInfo as &$userInfo ) {
                    if ( $client !== null && $campaignUID !== null ) {
                        $this->__addNotificationEventIsChanged($msrvRecipientsToNotify, $client, $campaignUID, array('id' => null, 'email' => $userInfo['attendee_email']), $userInfo['attendee_access_code']);
                    }                    
                    /**/
                    $this->recipientsToNotify[] = $userInfo['attendee_email'];                    
                }
            }            
        }        

        /* SOAP: MailSrv */
        /* add recipients to MAILSRV and start campaign */
        if ( $client !== null && $campaignUID !== null ) {
            if ( $msrvRecipientsToNotify->getCount() > 0 ) $client->addRecipients($campaignUID, $msrvRecipientsToNotify);
            $msrvRecipientsToNotify->clean();
            
            /* add callback to mailsrv campaign to sent PMB message */
            $objCallback = new Warecorp_SOAP_Type_Callback();
            $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
            $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
            $objCallback->setAction( 'callbackAddPMBMessage' );
            $callbackUID = $client->addCallback($campaignUID, $objCallback);

            /* Change PMB Subject by event creator defined. For ZCCF ONLY */
            if ( Warecorp::checkHttpContext('zccf') && $this->getSubject() ) {
                $client->addPMBSubject($campaignUID, $this->getSubject());
            }

            $this->recipientsToNotify = ( null === $this->recipientsToNotify ) ? array() : $this->recipientsToNotify;
            $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
            $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
            $client->addCallbackParam($callbackUID, 'sender_id', $this->getMailSender()->getId());
            $client->addCallbackParam($callbackUID, 'sender_type', ($this->getMailSender() instanceof Warecorp_User) ? 'user' : 'group');
            $client->addCallbackParam($callbackUID, 'recipients', join(';', $this->recipientsToNotify) );
            unset( $this->recipientsToNotify ); 
            
            $request = $client->startCampaign($campaignUID);
        }
        
        return true;
    }
    
    /**
    * При создании исключения события если изменен список приглашенных
    * Отправляется, когда создается исключение в повторяющемся событии
    * добавляет новые attendee для копии события
    * Caaled when any event exception was created and invitations list was changed
    * @see ADD_ONLY
    * @see self::__saveAttendee();
    */
    public function __saveAttendeeCopyCreated()
    {        
        ini_set("max_execution_time", 1800);
        set_time_limit(1800);
        
        /**
        * Т.к. сообщения отправляются от копии, то, чтобы обеспечить
        * что копия будет содержать все смерженные от основного события данные
        * мы заново создаем событие этой копии, при создании происходит автоматическое
        * смерживание события-копии с событием-оригиналом
        * при этом от основного события возьмуться title, description, attendee и другая информация
        */
        $objEvent = new Warecorp_ICal_Event($this->getEventId());        
        $this->setEventId($objEvent->getId());
        $this->setEvent($objEvent);        
        
        $this->__saveAttendee();
        
        return true;
    }
    
    /**
    * При редактировании исключения события если НЕ изменен список приглашенных
    * Отправляется уведомление, когда редактируется уже созданное исключение новые attendee не добавляются
    * @see NOTIFY_ONLY
    * @see __sendAttendeeCopyCreated
    */
    public function __sendAttendeeCopyChanged()
    {
        ini_set("max_execution_time", 1800);
        set_time_limit(1800);
        
        /**
        * Т.к. сообщения отправляются от копии, то, чтобы обеспечить
        * что копия будет содержать все смерженные от основного события данные
        * мы заново создаем событие этой копии, при создании происходит автоматическое
        * смерживание события-копии с событием-оригиналом
        * при этом от основного события возьмуться title, description, attendee и другая информация
        */
        $objEvent = new Warecorp_ICal_Event($this->getEventId());        
        $this->setEventId($objEvent->getId());
        $this->setEvent($objEvent);
        
        $this->__sendAttendeeCopyCreated( false );
        
        return true;
    }
    
    /**
    * При редактировании исключения события если изменен список приглашенных
    * Called when any event exception was changed and list of attendee was changed
    */
    public function __saveAttendeeCopyChanges()
    {        
        ini_set("max_execution_time", 1800);
        set_time_limit(1800);
        
        /**
        * Т.к. сообщения отправляются от копии, то, чтобы обеспечить
        * что копия будет содержать все смерженные от основного события данные
        * мы заново создаем событие этой копии, при создании происходит автоматическое
        * смерживание события-копии с событием-оригиналом
        * при этом от основного события возьмуться title, description, attendee и другая информация
        */
        $objEvent = new Warecorp_ICal_Event($this->getEventId());
        $this->setEventId($objEvent->getId());
        $this->setEvent($objEvent);
        
        $this->__sendAttendeeCopyCreated( false );
        $this->__saveAttendeeChanges();
        
        return true;
    }
    
    /**
     * Remove attendee from event invitation
     * @param Warecorp_User $objUser user 
     * @param array $attendeeIds array of attendee ids
     * @param string $strCustomMessage string message to send
     * @param boolean $sendNotification send notification to user or not
     * @return unknown_type
     */
    public function __removeAttendees( Warecorp_User $objUser, $attendeeIds )
    {
        ini_set("max_execution_time", 1800);
        set_time_limit(1800);
        
        $attendeeIds = (!is_array($attendeeIds)) ? array($attendeeIds) : $attendeeIds;
        if ( sizeof($attendeeIds) == 0 ) return true;
        
        $objEvent = $this->getEvent();
        
        $arrDeletedUsers = $this->prepareListOfDeleteUserAttendeeByIds( $attendeeIds, $objEvent->getId(), $objEvent->getCreator() );
        $arrDeletedGuests = $this->prepareListOfDeleteGuestAttendeeByIds( $attendeeIds, $objEvent->getId() );
        $arrDeleteFBUsers = array();
        if (FACEBOOK_USED) {
            $arrDeleteFBUsers = $this->prepareListOfDeleteFBUserAttendeeByIds( $attendeeIds, $objEvent->getId() );

            if ( $arrDeleteFBUsers ) {
                $this->removeAttendeeForFBUser( $arrDeleteFBUsers );
            }
        }
        
        if ( sizeof($arrDeletedUsers) != 0 || sizeof($arrDeletedGuests) != 0 ) {
            /* SOAP: MailSrv */
            $msrvRecipientsToRemove = new Warecorp_SOAP_Type_Recipients();
            
            if ( $this->getSendNotification() ) list( $client, $campaignUID ) = $this->getMailCampaign( 'REMOVE' );
            else $client = $campaignUID = null;

            if ( sizeof($arrDeletedUsers) != 0 ) {
                $this->removeAttendeeForRegisteredUser( $msrvRecipientsToRemove, $client, $campaignUID, $arrDeletedUsers );                    
            }

            if ( sizeof($arrDeletedGuests) != 0 ) {
                $this->removeAttendeeForUnregisteredUser( $msrvRecipientsToRemove, $client, $campaignUID, $arrDeletedGuests );                    
            }
            
            /* SOAP: MailSrv */
            /* add recipients to MAILSRV and start campaign */
            if ( $client !== null && $campaignUID !== null ) {
                if ( $msrvRecipientsToRemove->getCount() > 0 ) $client->addRecipients($campaignUID, $msrvRecipientsToRemove);
                $msrvRecipientsToRemove->clean();
                
                /* add callback to mailsrv campaign to sent PMB message */
                $objCallback = new Warecorp_SOAP_Type_Callback();
                $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                $objCallback->setAction( 'callbackAddPMBMessage' );
                $callbackUID = $client->addCallback($campaignUID, $objCallback);

                /* Change PMB Subject by event creator defined. For ZCCF ONLY */
                if ( Warecorp::checkHttpContext('zccf') && $this->getSubject() ) {
                    $client->addPMBSubject($campaignUID, $this->getSubject());
                }

                $this->recipientsToRemove = ( null === $this->recipientsToRemove ) ? array() : $this->recipientsToRemove;
                $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                $client->addCallbackParam($callbackUID, 'sender_id', $this->getMailSender()->getId());
                $client->addCallbackParam($callbackUID, 'sender_type', ($this->getMailSender() instanceof Warecorp_User) ? 'user' : 'group');
                $client->addCallbackParam($callbackUID, 'recipients', join(';', $this->recipientsToRemove) );
                unset( $this->recipientsToRemove ); 
                    
                $request = $client->startCampaign($campaignUID);
            }
        }

        $strEmails = join(', ', array_merge($arrDeletedUsers, $arrDeletedGuests));
        $recipients = $this->prepareRecipientsFromString( $objUser, $strEmails );
        $this->diffRecipients( $recipients, null, null, array_values($arrDeleteFBUsers) );
    }
    /**
     * +----------------------------------------------------------------------
     * |
     * |    
     * |
     * +---------------------------------------------------------------------- 
     */
    
    /**
     * 
     * @param Warecorp_SOAP_Type_Recipients $recipients
     * @param $client
     * @param $campaignUID
     * @param $recipientInfo
     * @param $accessCode
     * @return unknown_type
     */
    protected function __addInvite(Warecorp_SOAP_Type_Recipients &$recipients, $client, $campaignUID, $recipientInfo, $accessCode = null)
    {
        if ( $this->getSendNotification() ) {
            static $tinyUrls = array();
            $objEvent = $this->getEvent();

            $url = $objEvent->entityURL();
            $url = $accessCode ? rtrim($url, '/').'/code' : $url;
            if ( ! isset($tinyUrls[$url]) ) { $tinyUrls[$url] = Warecorp::getTinyUrl($url, HTTP_CONTEXT); }
            
            $recipient = new Warecorp_SOAP_Type_Recipient();
            $recipient->setEmail( $recipientInfo['email'] );
            $recipient->setName( $recipientInfo['id'] ? $recipientInfo['firstname'].' '.$recipientInfo['lastname'] : null );
            $recipient->setLocale( null );
            $recipient->addParam('CCFID', Warecorp::getCCFID($recipientInfo['id']));
            $recipient->addParam( 'event_date', $objEvent->displayDate('email.invitation.date', $recipientInfo['id'], $recipientInfo['id'] ? $recipientInfo['timezone'] : null) );
            $recipient->addParam( 'event_time', $objEvent->displayDate('email.invitation.time', $recipientInfo['id'], $recipientInfo['id'] ? $recipientInfo['timezone'] : null) );
            $recipient->addParam( 'event_url', $accessCode ? rtrim($tinyUrls[$url], ' /').'/'.$accessCode.'/' : $tinyUrls[$url] );
            $recipient->addParam( 'recipient_full_name', $recipientInfo['id'] ? $recipientInfo['firstname'].' '.$recipientInfo['lastname'] : '' );
            $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $recipientInfo['id'] 
                ? 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/user/'.mb_strtolower($recipientInfo['path'], 'utf-8').'/settings/'
                : 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/' );
            $recipients->addRecipient($recipient);
    
            if ( $recipients->getCount() >= 100 ) {
                if ( $client !== null && $campaignUID !== null ) $request = $client->addRecipients($campaignUID, $recipients);
                $recipients->clean();
            }
            
            if ( sizeof($tinyUrls) > $this->maxTinyUrlsInArray ) { $tinyUrls = array(); }
        }
    }
    
    /**
     * 
     * @param Warecorp_SOAP_Type_Recipients $recipients
     * @param $client
     * @param $campaignUID
     * @param $recipientInfo
     * @return unknown_type
     */
    protected function __addNotificationToRemovedUser(Warecorp_SOAP_Type_Recipients &$recipients, $client, $campaignUID, $recipientInfo)
    {
        if ( $this->getSendNotification() ) {
            $objEvent = $this->getEvent();

            $recipient = new Warecorp_SOAP_Type_Recipient();
            $recipient->setEmail( $recipientInfo['email'] );
            $recipient->setName( $recipientInfo['id'] ? $recipientInfo['firstname'].' '.$recipientInfo['lastname'] : null );
            $recipient->setLocale( null );
            $recipient->addParam('CCFID', Warecorp::getCCFID($recipientInfo['id']));
            $recipient->addParam( 'recipient_full_name', $recipientInfo['id'] ? $recipientInfo['firstname'].' '.$recipientInfo['lastname'] : '' );
            $recipient->addParam( 'event_date', $objEvent->displayDate('email.invitation.date', $recipientInfo['id'], $recipientInfo['id'] ? $recipientInfo['timezone'] : null) );
            $recipient->addParam( 'event_time', $objEvent->displayDate('email.invitation.time', $recipientInfo['id'], $recipientInfo['id'] ? $recipientInfo['timezone'] : null) );
            $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $recipientInfo['id'] 
                ? 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/user/'.mb_strtolower($recipientInfo['path'], 'utf-8').'/settings/'
                : 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/' );
            $recipients->addRecipient($recipient);
            
            if ( $recipients->getCount() >= 100 ) {
                if ( $client !== null && $campaignUID !== null ) $request = $client->addRecipients($campaignUID, $recipients);
                $recipients->clean();
            }
        }
    }

    protected function __addNotificationToRemovedFBUser( array $FBUids )
    {
        if ( $this->getSendNotification() && FACEBOOK_USED ) {
            $objEvent = $this->getEvent();
            $objOwner = $objEvent->getOwner();
            $event_url = $objEvent->entityURL().'m/fb';
            
            /**
             * Facebook Notification
             */
            $notification  = "has removed you from event {$objEvent->getTitle()} on ".SITE_NAME_AS_STRING.". <br />";
            if ( $objEvent->getPrivacy() === Warecorp_ICal_Enum_Privacy::PRIVACY_PUBLIC )
                $notification .= "<a href='".$event_url."'>Click here</a> to view the event.";
            Warecorp_Facebook_Feed::postNotification($FBUids, $notification);

            /*
             * Facebook Email Notification
             */
            $message_subject = SITE_NAME_AS_STRING.": You are removed from event";
            $message_body = "
Hello, <br /> 
you are removed from event ".$objEvent->getTitle()." on ".SITE_NAME_AS_STRING.". <br /><br />
    
Thanks,<br />
".SITE_NAME_AS_STRING." <br /> 
Calendars";
            Warecorp_Facebook_Feed::postEmail($FBUids, $message_subject, $message_body, $message_body);            
        }
    }

    /**
     * 
     * @param Warecorp_SOAP_Type_Recipients $recipients
     * @param $client
     * @param $campaignUID
     * @param $recipientInfo
     * @return unknown_type
     */
    protected function __addNotificationEventIsChanged(Warecorp_SOAP_Type_Recipients &$recipients, $client, $campaignUID, $recipientInfo, $accessCode = null)
    {
        if ( $this->getSendNotification() ) {
            static $tinyUrls = array();
            $objEvent = $this->getEvent();
            
            $url = $objEvent->entityURL();
            $url = $accessCode ? rtrim($url, '/').'/code' : $url;
            if ( ! isset($tinyUrls[$url]) ) { $tinyUrls[$url] = Warecorp::getTinyUrl($url, HTTP_CONTEXT); }
            
            $recipient = new Warecorp_SOAP_Type_Recipient();
            $recipient->setEmail( $recipientInfo['email'] );
            $recipient->setName( $recipientInfo['id'] ? $recipientInfo['firstname'].' '.$recipientInfo['lastname'] : null );
            $recipient->setLocale( null );
            $recipient->addParam('CCFID', Warecorp::getCCFID($recipientInfo['id']));
            $recipient->addParam( 'event_date', $objEvent->displayDate('email.invitation.date', $recipientInfo['id'], $recipientInfo['id'] ? $recipientInfo['timezone'] : null) );
            $recipient->addParam( 'event_time', $objEvent->displayDate('email.invitation.time', $recipientInfo['id'], $recipientInfo['id'] ? $recipientInfo['timezone'] : null) );
            $recipient->addParam( 'event_url', $accessCode ? rtrim($tinyUrls[$url], ' /').'/'.$accessCode.'/' : $tinyUrls[$url] );
            $recipient->addParam( 'recipient_full_name', $recipientInfo['id'] ? $recipientInfo['firstname'].' '.$recipientInfo['lastname'] : '' );
            $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $recipientInfo['id'] 
                ? 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/user/'.mb_strtolower($recipientInfo['path'], 'utf-8').'/settings/'
                : 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/' );        
            $recipients->addRecipient($recipient);
            
            if ( $recipients->getCount() >= 100 ) {
                if ( $client !== null && $campaignUID !== null ) $request = $client->addRecipients($campaignUID, $recipients);
                $recipients->clean();
            }        
            
            if ( sizeof($tinyUrls) > $this->maxTinyUrlsInArray ) { $tinyUrls = array(); }
        }
    }



    


    /**
     * +----------------------------------------------------------------------
     * |
     * |    ****
     * |
     * +---------------------------------------------------------------------- 
     */    
    
    /**
    * Отправляет сообщеиен пользователям (зарегестрированным и незарегестрированным), приглашенным в сообщение
    * @param string $mode - value from ALL|YES|YES_MAYBY|YES_MAYBY_NONE
    * @param string $strCustomMessage - custom user message
    * @param array $excludeIds
    * @return void
    */
    public function sendMessageToGuests($mode, $strCustomMessage, $excludeIds = null)
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        
        $lstAttendee = $this->getEvent()->getAttendee()->setFetchMode('object');
        switch ( $mode ) {
            case "ALL" :
                break;
            case "YES" :
                $lstAttendee->setAnswerFilter('YES');
                break;
            case "YES_MAYBY" :
                $lstAttendee->setAnswerFilter(array('YES', 'MAYBE'));
                break;
            case "YES_MAYBY_NONE" :
                $lstAttendee->setAnswerFilter(array('YES', 'MAYBE', 'NONE'));
                break;
        }
        $excludeIds = ( null === $excludeIds ) ? array() : $excludeIds;
        $excludeIds = ( !is_array($excludeIds) ) ? array($excludeIds) : $excludeIds;

        $attendee = $lstAttendee->getList();
        if ( sizeof($attendee) != 0 ) {
            /* SOAP: MailSrv */
            if ( Warecorp::isMailServerUsed() ) {
                if ( $this->getSendNotification() ) list( $client, $campaignUID ) = $this->getMailCampaign( 'MESSAGETOUSERS', array('custom_message' => $strCustomMessage) );
                else $client = $campaignUID = null;
                                
                foreach ( $attendee as &$_attendee ) {
                    if ( $_attendee->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) {
                        if ( null !== $_attendee->getOwnerId() ) {
                            if ( !in_array($_attendee->getOwnerId(),$excludeIds) ) { 
                                $this->_addMessageToUser($msrvRecipients, $_attendee->getOwner());
                                $pmbRecipients[] = $_attendee->getOwner()->getId() ? $_attendee->getOwner()->getId() : $_attendee->getOwner()->getEmail();
                            }
                        } else { 
                            $this->_addMessageToUser($msrvRecipients, $_attendee->getOwner());
                            $pmbRecipients[] = $_attendee->getOwner()->getId() ? $_attendee->getOwner()->getId() : $_attendee->getOwner()->getEmail(); 
                        }
                    }
                } 
                
                if ( $client !== null && $campaignUID !== null ) {
                    try {
                        $client->addRecipients($campaignUID, $msrvRecipients);
                        $msrvRecipients->clean();
                        
                        /* add callback to mailsrv campaign to sent PMB message */
                        $objCallback = new Warecorp_SOAP_Type_Callback();
                        $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                        $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                        $objCallback->setAction( 'callbackAddPMBMessage' );
                        $callbackUID = $client->addCallback($campaignUID, $objCallback);

                        $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                        $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                        $client->addCallbackParam($callbackUID, 'sender_id', $this->getMailSender()->getId());
                        $client->addCallbackParam($callbackUID, 'sender_type', ($this->getMailSender() instanceof Warecorp_User) ? 'user' : 'group');
                        $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                        unset( $pmbRecipients ); 
                        
                        $request = $client->startCampaign($campaignUID);
                    } catch ( Exception $e ) {}
                }
            }
        }
    }
    /**
     * 
     * @param Warecorp_SOAP_Type_Recipients $recipients
     * @param Warecorp_User $objRecipient
     * @return unknown_type
     */

    protected function _addMessageToUser(Warecorp_SOAP_Type_Recipients &$recipients, Warecorp_User $objRecipient)
    {
		$recipient = new Warecorp_SOAP_Type_Recipient();
		$recipient->setEmail( $objRecipient->getEmail() );
		$recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
		$recipient->setLocale( null );
        $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
		$recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
		$recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
		$recipients->addRecipient($recipient);
    }    
    /**
    * Отправляет сообщеиен зарегестрированным пользователям, приглашенным в сообщение
    * @param Warecorp_User $objRecipient
    * @param string $strCustomMessage - custom user message
    * @return void
    * @see  self::sendMessageToGuests
    */
    protected function _sendMessageToUser(Warecorp_User $objRecipient, $strCustomMessage)
    {
        /**
        * Set Event Timezone
        */
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
        if ( empty($cfgSite->use_event_tz_from_venue) || (int)$cfgSite->use_event_tz_from_venue == 0 ) {
            $originalEventTimezone = $this->getEvent()->getTimezone();
            if ( null === $originalEventTimezone || $originalEventTimezone instanceof Zend_Db_Expr ) {
                $this->getEvent()->setTimezone($objRecipient->getTimezone());
            }
        }
        /**
        * Send Message
        */
        $mail = new Warecorp_Mail_Template('template_key', 'CALENDAR_MESSAGE_TO_USER');
        $mail->setSender($this->getMailSender());
        $mail->addRecipient($objRecipient);
        $mail->addParam('objEvent', $this->getEvent());
        $mail->addParam('strCustomMessage', $strCustomMessage);
        $mail->addParam('objInvite', $this);

        $mail->sendToPMB ( true ) ;
        $mail->sendToEmail (true) ;
        $mail->send();

        if ( empty($cfgSite->use_event_tz_from_venue) || (int)$cfgSite->use_event_tz_from_venue == 0 ) {
            $this->getEvent()->setTimezone($originalEventTimezone);
        }
    }

    /**
    * Отправляет сообщеиен зарегестрированным пользователям, приглашенным в сообщение
    * @param Warecorp_User $objRecipient
    * @param string $strCustomMessage - custom user message
    * @return void
    * @see  self::sendMessageToGuests
    */
    protected function _sendMessageToGuest(Warecorp_User $objRecipient, $strCustomMessage = null)
    {
        /**
        * Set Event Timezone
        */
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
        if ( empty($cfgSite->use_event_tz_from_venue) || (int)$cfgSite->use_event_tz_from_venue == 0 ) {
            $originalEventTimezone = $this->getEvent()->getTimezone();
            if ( null === $originalEventTimezone || $originalEventTimezone instanceof Zend_Db_Expr ) {
                $this->getEvent()->setTimezone($this->getEvent()->getCreator()->getTimezone());
            }
        }
        /**
        * Send Message
        */
        $mail = new Warecorp_Mail_Template('template_key', 'CALENDAR_MESSAGE_TO_GUEST');
        $mail->setSender($this->getMailSender());
        $mail->addRecipient($objRecipient);
        $mail->addParam('objEvent', $this->getEvent());
        $mail->addParam('strCustomMessage', $strCustomMessage);
        $mail->addParam('objInvite', $this);

        $mail->sendToEmail (true) ;
        $mail->send();

        if ( empty($cfgSite->use_event_tz_from_venue) || (int)$cfgSite->use_event_tz_from_venue == 0 ) {
            $this->getEvent()->setTimezone($originalEventTimezone);
        }
    }

    /**
    * Отправляет сообщение органайзеру сообщения
    * @param string $strCustomMessage - custom user message
    * @param boolean $pmbSend - send message to PMB if true
    * @param boolean $emailSend - send message to Email if true
    * @return void
    * @version 4.1
    */
    public function sendMessageToOrganizer($strCustomMessage, $pmbSend = true, $emailSend = true)
    {        
        $objEvent = $this->getEvent();
        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
        
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered('CALENDAR_MESSAGE_TO_ORGANIZER') ) {
            $objRecipient = null;
            if ( $this->getEvent()->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) $objRecipient = new Warecorp_User('id', $this->getEvent()->getOwnerId());
            else $objRecipient = $this->getEvent()->getCreator();
            
            $recipient = new Warecorp_SOAP_Type_Recipient();
            $recipient->setEmail( $objRecipient->getEmail() );
            $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
            $recipient->setLocale( null );
            $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
            $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
            $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
            $msrvRecipients->addRecipient($recipient);
            
            $addParams = array();
            $addParams['str_message_plain'] = $strCustomMessage;
            $addParams['str_message_html'] = nl2br(htmlspecialchars($strCustomMessage));     
                   
            if ( $pmbSend ) $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
            
            try { 
                $this->createMailCampaign($msrvRecipients, 'CALENDAR_MESSAGE_TO_ORGANIZER', $objEvent, $pmbRecipients, $addParams); 
                $msrvSended = true;
            } catch ( Exception $e ) { $msrvSended = false; }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $this->_sendMessageToOrganizer($strCustomMessage, $pmbSend, $emailSend);
        }
    }
    
    /**
    * Отправляет сообщение органайзеру сообщения 
    * @param string $strCustomMessage - custom user message
    * @param boolean $pmbSend - send message to PMB if true
    * @param boolean $emailSend - send message to Email if true
    * @return void
    * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
    */
    public function _sendMessageToOrganizer($strCustomMessage, $pmbSend = true, $emailSend = true)
    {
        /**
        * Detect Recipient - Organizeor of Event
        */
        $objRecipient = null;
        if ( $this->getEvent()->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) $objRecipient = new Warecorp_User('id', $this->getEvent()->getOwnerId());
        else $objRecipient = $this->getEvent()->getCreator();
        /**
        * Set Event Timezone
        */
        $originalEventTimezone = $this->getEvent()->getTimezone();
        if ( null === $originalEventTimezone || $originalEventTimezone instanceof Zend_Db_Expr ) {
            $this->getEvent()->setTimezone($objRecipient->getTimezone());
        }
        /**
        * Send Message
        */
        $mail = new Warecorp_Mail_Template('template_key', 'CALENDAR_MESSAGE_TO_ORGANIZER');
        $mail->setSender($this->getMailSender());
        $mail->addRecipient($objRecipient);
        $mail->addParam('objEvent', $this->getEvent());
        $mail->addParam('strCustomMessage', $strCustomMessage);
        $mail->addParam('objInvite', $this);

        $mail->sendToPMB($pmbSend);
        $mail->sendToEmail($emailSend);
        $mail->send();

        $this->getEvent()->setTimezone($originalEventTimezone);
    }
    
    /**
     * 
     * @param $objSender
     * @param $objAttendee
     * @return unknown_type
     */
    public function sentRSVPNotification($objSender, $objAttendee)
    {
        if ( $this->getReceiveNoRsvpEmail() ) return true;
        
        $objEvent = $this->getEvent();
        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
        
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered('CALENDAR_RSVP_NOTIFICATION') ) {
            $objRecipient = null;
            if ( $this->getEvent()->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) $objRecipient = $objEvent->getOwner();
            else $objRecipient = $this->getEvent()->getCreator();
            
            $recipient = new Warecorp_SOAP_Type_Recipient();
            $recipient->setEmail( $objRecipient->getEmail() );
            $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
            $recipient->setLocale( null );
            $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
            $recipient->addParam( 'event_date', $objEvent->displayDate('email.invitation.date', $objRecipient->getId(), $objRecipient->getId() ? $objRecipient->getTimezone() : null) );
            $recipient->addParam( 'event_time', $objEvent->displayDate('email.invitation.time', $objRecipient->getId(), $objRecipient->getId() ? $objRecipient->getTimezone() : null) );
            $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
            $recipient->addParam( 'attendee_answer', $objAttendee->getAnswer());
            $recipient->addParam( 'attendee_answer_text_plain', $objAttendee->getAnswerText());
            $recipient->addParam( 'attendee_answer_text_html', nl2br(htmlspecialchars($objAttendee->getAnswerText())));
            $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );

            $msrvRecipients->addRecipient($recipient);
            
            $addParams = array();
            $addParams['objSender'] = $objSender;
            $addParams['objAttendee'] = $objAttendee;
            
            if ( $objSender->getId() ) {
                if ( $objSender->getId() == $objRecipient->getId() ) $addParams['rsvp_user'] = Warecorp::t('You have');
                else $addParams['rsvp_user'] = Warecorp::t('%s has', $objSender->getLogin());
            } else {
                if ( $objAttendee->getOwnerType() == 'fbuser' ) $addParams['rsvp_user'] = Warecorp::t('%s has', $objAttendee->getName());
                else $addParams['rsvp_user'] = Warecorp::t('%s has', $objSender->getEmail());
            }
                               
            $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
            
            try { 
                $this->createMailCampaign($msrvRecipients, 'CALENDAR_RSVP_NOTIFICATION', $objEvent, $pmbRecipients, $addParams); 
                $msrvSended = true;
            } catch ( Exception $e ) { $msrvSended = false; }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            //  Send message           
            $mail = new Warecorp_Mail_Template('template_key', 'CALENDAR_RSVP_NOTIFICATION');
            $mail->setSender($objSender);
            if ( $objEvent->getOwnerType() == 'user' ) $mail->addRecipient($objEvent->getOwner());
            else $mail->addRecipient($objEvent->getCreator());
            $mail->addParam('objEvent', $objEvent);
            $mail->addParam('objAttendee', $objAttendee);
            $mail->sendToEmail(true);
            $mail->sendToPMB(true);
            $mail->send();
        }
    }
    
    /**
     * 
     * @param $newValue
     * @return unknown_type
     */
    public function setIsAnybodyJoin($newValue = 0)
    {
        $this->isAnybodyJoin = $newValue;
        return $this;
    }
    
    /**
     * 
     * @return unknown_type
     */
    public function getIsAnybodyJoin()
    {
        return $this->isAnybodyJoin;
    }

    /**
     * 
     * @return unknown_type
     */
    public function isAnybodyJoin()
    {
        return $this->isAnybodyJoin;
    }

    /**
    * Определяет отправителя сообщения по полям sendFrom и from
    * @return Warecorp_User $objSender
    */
    private function getMailSender()
    {
        $objSender = null;
        if ( null !== $this->getSendFrom() ) {
            if ( $this->getSendFrom() instanceof Warecorp_User ) {
                $objSender = $this->getSendFrom();
            } elseif ( $this->getSendFrom() instanceof Warecorp_Group_Base ) {
            } elseif ( is_string($this->getSendFrom()) ) {
                $objSender = new Warecorp_User();
                $objSender->setEmail($this->getSendFrom());
            }
        } else {
            $objSender = new Warecorp_User();
            $objSender->setEmail($this->getFrom());
        }
        if ( null === $objSender ) $objSender = $this->getEvent()->getCreator();

        return $objSender;
    }
    
    /**
     * +----------------------------------------------------------------------
     * |
     * |    MAIL SRV CAMPAIGNS
     * |
     * +---------------------------------------------------------------------- 
     */
    
    protected function getMailCampaign($campaign, $addParams = array())
    {
        /* SOAP: MailSrv */       
        try { $client = Warecorp::getMailServerClient(); }
        catch ( Exception $e ) { return array(null, null); }
        if ( empty($client) ) { return array(null, null); }
        
        $objEvent = $this->getEvent();
        
        switch ( $campaign ) {
            case 'INVITE' :
                /* email to invited users */
                try {
                    $campaignUID = $client->createCampaign();
                    $objSender = $this->getMailSender();
                    $request = $client->setSender($campaignUID, $objSender->getEmail(), null);

                    /* Calendar Email subject implementation dependance */
                    if (Warecorp::checkHttpContext('zccf') && $this->getSubject()){
                        $client->setSubject($campaignUID, $this->getSubject());// If event creator set own subject messge, set it as Email subject
                    }
                    /* Calendar Email subject implementation dependance */

                    $request = $client->setTemplate($campaignUID, 'CALENDAR_INVITE_TO_EVENT', HTTP_CONTEXT); /* CALENDAR_INVITE_TO_EVENT */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'event_title', $objEvent->getTitle() );
                    $params->addParam( 'event_owner_login', $objEvent->getOwnerType() == "user" ? $objEvent->getOwner()->getLogin() : $objEvent->getCreator()->getLogin() );
                    $params->addParam( 'event_owner_full_name', $objEvent->getOwnerType() == "user" ? $objEvent->getOwner()->getFirstname().' '.$objEvent->getOwner()->getLastname() : $objEvent->getCreator()->getFirstname().' '.$objEvent->getCreator()->getLastname() );
                    $params->addParam( 'event_venue', $objEvent->getEventVenue() ? $objEvent->getEventVenue()->getName() : '' );
                    $params->addParam( 'event_description', $objEvent->getDescription() );
                    $params->addParam( 'event_sender_login', $objSender->getLogin() ? $objSender->getLogin() : 'member' );
                    $params->addParam( 'event_invite_subject', $this->getMessage() && $this->getSubject() ? $this->getSubject() : '' );
                    $params->addParam( 'event_invite_message', $this->getMessage() ? $this->getMessage() : '' );                        
                    if ( sizeof($addParams) != 0 ) foreach ( $addParams as $key => $value ) $params->addParam( $key, $value );
                    $request = $client->addParams($campaignUID, $params);
    
                    return array($client, $campaignUID);
                } catch ( Exception $e ) { throw $e; }                    
                break;
            case 'REMOVE' :
                /* email to removed users */
                try {
                    $campaignUID = $client->createCampaign();
                    $objSender = $this->getMailSender();
                    $request = $client->setSender($campaignUID, $objSender->getEmail(), null);
                    $request = $client->setTemplate($campaignUID, 'CALENDAR_NOTIFICATION_TO_REMOVED_FROM_EVENT', HTTP_CONTEXT); /* CALENDAR_NOTIFICATION_TO_REMOVED_FROM_EVENT */
                    
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'event_title', $objEvent->getTitle() );
                    $params->addParam( 'event_owner_login', $objEvent->getOwnerType() == "user" ? $objEvent->getOwner()->getLogin() : $objEvent->getCreator()->getLogin() );
                    $params->addParam( 'event_owner_full_name', $objEvent->getOwnerType() == "user" ? $objEvent->getOwner()->getFirstname().' '.$objEvent->getOwner()->getLastname() : $objEvent->getCreator()->getFirstname().' '.$objEvent->getCreator()->getLastname() );
                    $params->addParam( 'message',  $this->getCustomMessage() !== null ? nl2br(htmlspecialchars($this->getCustomMessage())) : '' );
                    if ( sizeof($addParams) != 0 ) foreach ( $addParams as $key => $value ) $params->addParam( $key, $value );
                    $request = $client->addParams($campaignUID, $params);
                    
                    return array($client, $campaignUID);
                } catch ( Exception $e ) { throw $e; }
                break;
            case 'NOTIFY' : 
                /* change notification emails */
                try {
                    $campaignUID = $client->createCampaign();
                    $objSender = $this->getMailSender();
                    $request = $client->setSender($campaignUID, $objSender->getEmail(), null);
                    $request = $client->setTemplate($campaignUID, 'CALENDAR_NOTIFICATION_EVENT_CHANGED', HTTP_CONTEXT); /* CALENDAR_NOTIFICATION_EVENT_CHANGED */
                    
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    /* Params customization depend on implementation */
                    if (Warecorp::checkHttpContext('zccf')){
                        if ( $this->getSubject() ) {    // If event creator set own subject messge, set it as Email subject
                            $client->setSubject($campaignUID, $this->getSubject());
                        }
                        $params->addParam( 'event_sender_login',
                            ( $objSender->getFirstname() && $objSender->getLastname() ) ?
                                $objSender->getFirstname() .' ' . $objSender->getLastname() :
                            ( $objSender->getLogin() ) ?
                                $objSender->getLogin() :
                                'member'
                        );
                    }else{
                        $params->addParam( 'event_sender_login', $objSender->getLogin() ? $objSender->getLogin() : 'member' );
                    }
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'event_title', $this->getEvent()->getTitle() );
                    $params->addParam( 'event_owner_login', $this->getEvent()->getOwnerType() == "user" ? $this->getEvent()->getOwner()->getLogin() : $this->getEvent()->getCreator()->getLogin() );
                    $params->addParam( 'event_owner_full_name', $objEvent->getOwnerType() == "user" ? $objEvent->getOwner()->getFirstname().' '.$objEvent->getOwner()->getLastname() : $objEvent->getCreator()->getFirstname().' '.$objEvent->getCreator()->getLastname() );
                    $params->addParam( 'event_venue', $this->getEvent()->getEventVenue() ? $this->getEvent()->getEventVenue()->getName() : '' );
                    $params->addParam( 'event_description', $this->getEvent()->getDescription() );
                    $params->addParam( 'event_invite_subject', $this->getMessage() && $this->getSubject() ? $this->getSubject() : '' );
                    $params->addParam( 'event_invite_message', $this->getMessage() ? $this->getMessage() : '' );
                    if ( sizeof($addParams) != 0 ) foreach ( $addParams as $key => $value ) $params->addParam( $key, $value );
                    $request = $client->addParams($campaignUID, $params);
                    
                    return array($client, $campaignUID);
                } catch ( Exception $e ) { throw $e; }
                break;
            case 'MESSAGETOUSERS' : 
                /* email to event users */
                try {
                    $campaignUID = $client->createCampaign();
                    $objSender = $this->getMailSender();
                    $request = $client->setSender($campaignUID, $objSender->getEmail(), null);
                    $request = $client->setTemplate($campaignUID, 'CALENDAR_MESSAGE_TO_USER', HTTP_CONTEXT); /* CALENDAR_MESSAGE_TO_USER */
                    
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'event_title', $this->getEvent()->getTitle() );
                    $params->addParam( 'event_owner_login', $this->getEvent()->getOwnerType() == "user" ? $this->getEvent()->getOwner()->getLogin() : $this->getEvent()->getCreator()->getLogin() );
                    $params->addParam( 'event_owner_full_name', $this->getEvent()->getOwnerType() == "user" ? $this->getEvent()->getOwner()->getFirstname().' '.$this->getEvent()->getOwner()->getLastname() : $this->getEvent()->getCreator()->getFirstname().' '.$this->getEvent()->getCreator()->getLastname() );
                    $params->addParam( 'event_sender_login', $objSender->getLogin() ? $objSender->getLogin() : 'member' );
                    if ( sizeof($addParams) != 0 ) foreach ( $addParams as $key => $value ) $params->addParam( $key, $value );
                    $request = $client->addParams($campaignUID, $params);

                    return array($client, $campaignUID);                    
                } catch ( Exception $e ) { throw $e; }
                break;
        }
    }
    
    protected function createMailCampaign(Warecorp_SOAP_Type_Recipients $recipients, $campaign, Warecorp_ICal_Event $objEvent, $pmbRecipients = array(), $addParams = array())
    {
        /* SOAP: MailSrv */       
        try { $client = Warecorp::getMailServerClient(); }
        catch ( Exception $e ) { $client = null; }   

        if ( $client && sizeof($recipients->getRecipients()) != 0 ) {
            switch ( $campaign ) {
                case 'CALENDAR_MESSAGE_TO_ORGANIZER' :
                    /* email to invited users */
                    try {
                        $campaignUID = $client->createCampaign();                        
                        $objSender = $this->getMailSender();
                        $request = $client->setSender($campaignUID, $objSender->getEmail(), $objSender->getFirstname().' '.$objSender->getLastname());
                        $request = $client->setTemplate($campaignUID, 'CALENDAR_MESSAGE_TO_ORGANIZER', HTTP_CONTEXT); /* CALENDAR_MESSAGE_TO_ORGANIZER */
                        
                        /* add params */
                        $params = new Warecorp_SOAP_Type_Params();
                        $params->loadDefaultCampaignParams();
                        $params->addParam( 'sender_login', $objSender->getLogin() ? $objSender->getLogin() : 'member' );
                        if ( sizeof($addParams) != 0 ) foreach ( $addParams as $key => $value ) $params->addParam( $key, $value );
                        $request = $client->addParams($campaignUID, $params);
                        
                        /* add callback to mailsrv campaign to sent PMB message */
                        $objCallback = new Warecorp_SOAP_Type_Callback();
                        $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                        $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                        $objCallback->setAction( 'callbackAddPMBMessage' );
                        $callbackUID = $client->addCallback($campaignUID, $objCallback);

                        $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                        $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                        $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                        $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                        $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                        $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                        unset( $pmbRecipients );
                        
                        $request = $client->addRecipients($campaignUID, $recipients);
                        $request = $client->startCampaign($campaignUID);
                    } catch ( Exception $e ) { throw $e; }                    
                    break;
                case 'CALENDAR_RSVP_NOTIFICATION' :
                    /* email to invited users */
                    try {
                        $objSender = $addParams['objSender']; unset($addParams['objSender']);
                        $objAttendee = $addParams['objAttendee']; unset($addParams['objAttendee']);
                        
                        if ( $objAttendee->getAnswer() == 'YES' ) $addParams['response_subject'] = Warecorp::t('Accepted');
                        elseif ( $objAttendee->getAnswer() == 'MAYBE' ) $addParams['response_subject'] = Warecorp::t('Tentative');
                        else $addParams['response_subject'] = Warecorp::t('Declined');                        
                        
                        $campaignUID = $client->createCampaign();                        
                        $request = $client->setSender($campaignUID, 
                            $objSender->getEmail() ? $objSender->getEmail() : 'calendar@'.SITE_NAME_AS_DOMAIN, 
                            $objSender->getId() ? $objSender->getFirstname().' '.$objSender->getLastname() : ( $objSender->getEmail() ? '' : Warecorp::t('Calendar') ) 
                        );
                        $request = $client->setTemplate($campaignUID, 'CALENDAR_RSVP_NOTIFICATION', HTTP_CONTEXT); /* CALENDAR_RSVP_NOTIFICATION */
                        
                        /* add params */
                        $params = new Warecorp_SOAP_Type_Params();
                        $params->loadDefaultCampaignParams();
                        $params->addParam( 'event_title', $objEvent->getTitle() );
                        if ( sizeof($addParams) != 0 ) foreach ( $addParams as $key => $value ) $params->addParam( $key, $value );
                        $request = $client->addParams($campaignUID, $params);
                        
                        /* add callback to mailsrv campaign to sent PMB message */
                        $objCallback = new Warecorp_SOAP_Type_Callback();
                        $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                        $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                        $objCallback->setAction( 'callbackAddPMBMessage' );
                        $callbackUID = $client->addCallback($campaignUID, $objCallback);
            
                        $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                        $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                        $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                        $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                        $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                        $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                        unset( $pmbRecipients );
                        
                        $request = $client->addRecipients($campaignUID, $recipients);
                        $request = $client->startCampaign($campaignUID);
                    } catch ( Exception $e ) { throw $e; }                    
                    break;
            }
        }        
    }
}
