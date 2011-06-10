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

class BaseWarecorp_ICal_HostRequest
{
    private $DbConn;
    private $id;
    private $eventId;
    private $hostId;
    private $status;
    private $createDate;
    
    private $maxTinyUrlsInArray = 100;

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
        return $this->eventId;
    }

    public function setHostId($newValue)
    {
        $this->hostId = $newValue;
        return $this;
    }

    public function getHostId()
    {
        return $this->hostId;
    }

    public function setStatus($newValue)
    {
        $this->status = $newValue;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateDate($newValue)
    {
        $this->createDate = $newValue;
        return $this;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }
    public function __construct($requestId = null)
    {
        $this->DbConn = Zend_Registry::get('DB');
        if ( null !== $requestId ) $this->loadById($requestId);
    }

    public static function isRequestExists($eventId, $hostId)
    {
        $DbConn = Zend_Registry::get('DB');
        $query = $DbConn->select();
        $query->from('calendar_event_host_requests', array('CNT' => new Zend_Db_Expr('COUNT(*)')));
        $query->where('request_event_root_id = ?', $eventId);
        $query->where('request_new_host_id = ?', $hostId);
        $result = $DbConn->fetchOne($query);
        return (boolean) $result;
    }

    public function loadById($requestId)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_host_requests', array('*'));
        $query->where('request_id = ?', $requestId);
        $data = $this->DbConn->fetchRow($query);
        if ( $data ) {
            $this->setId($data['request_id']);
            $this->setEventId($data['request_event_root_id']);
            $this->setHostId($data['request_new_host_id']);
            $this->setStatus($data['request_status']);
            $this->setCreateDate($data['request_create_date']);
        }
    }

    public function save()
    {
        $data = array();
        $data['request_event_root_id']  = $this->getEventId();
        $data['request_new_host_id']    = $this->getHostId();
        $data['request_status']         = $this->getStatus();
        $data['request_create_date']    = ( null === $this->getCreateDate() ) ? new Zend_Db_Expr('NOW()') : $this->getCreateDate() ;
        if ( null === $this->getId() ) {
            $this->DbConn->insert('calendar_event_host_requests', $data);
            $this->setId($this->DbConn->lastInsertId());
            $objRecipient = new Warecorp_User('id', $this->getHostId());
            $this->sendMessage($objRecipient);
        } else {
            $where = $this->DbConn->quoteInto('request_id = ?', $this->getId());
            $this->DbConn->update('calendar_event_host_requests', $data, $where);
        }
    }

    public function delete()
    {
        $where = $this->DbConn->quoteInto('request_id = ?', $this->getId());
        $this->DbConn->delete('calendar_event_host_requests', $where);
    }

    public function acceptRequest()
    {
        $objHost    = new Warecorp_User('id', $this->getHostId());
        $objEvent   = new Warecorp_ICal_Event($this->getEventId());

        $objRootEvent = $objEvent->getRootEvent();
        if ( $objRootEvent->getSharing()->isShared($objHost) ) {
            $objRootEvent->getSharing()->delete($objHost);
        }
        
        $objOldHost = null;
        if ( $objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) {
            $objOldHost = $objEvent->getOwner();
        }


        if ( $objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ){
            $objEvent->setOwnerId($this->getHostId());
        }
        $objEvent->setCreatorId($this->getHostId());

        if ($objOldHost !== null && !$objEvent->getSharing()->isShared($objOldHost)) {
            $objEvent->getSharing()->add($objOldHost);
        }
        
        $objEvent->save();

        $refsEvents = Warecorp_ICal_Event_List_Standard::getListByRootId($objEvent->getId());
        if ( sizeof($refsEvents) != 0 ) {
            foreach ( $refsEvents as $objRef ) {
                if ( $objRef->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ){
                    $objRef->setOwnerId($this->getHostId());
                }
                $objRef->setCreatorId($this->getHostId());
                $objRef->save();
            }
        }
        $this->sendMessageToMembers($objHost, $objOldHost);
        $this->delete();
    }

    public function declineRequest()
    {
        //FIXME Отправить уведомление об отказе от запроса старому хосту события
        $this->delete();
    }

    
    /**
     * 
     * @param Warecorp_User $objRecipient
     * @return unknown_type
     * @version 4.1
     */
    private function sendMessage(Warecorp_User $objRecipient)
    {
        static $tinyUrls = array();
        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
        
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered('CALENDAR_MESSAGE_CHANGE_HOST') ) {
            $objEvent = new Warecorp_ICal_Event($this->getEventId());

            /**
            * Set Event Timezone
            */
            $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
            $useEventTzFromVenue = empty($cfgSite->use_event_tz_from_venue) || (int)$cfgSite->use_event_tz_from_venue == 0;
            if ( $useEventTzFromVenue ) {
                $originalEventTimezone = $objEvent->getTimezone();
                if ( null === $originalEventTimezone || $originalEventTimezone instanceof Zend_Db_Expr ) {
                    if ( $objRecipient->getTimezone() ) { $objEvent->setTimezone($objRecipient->getTimezone()); } 
                    else { $objEvent->getCreator()->getTimezone(); }
                }
            }
            $eventDtstart = $useEventTzFromVenue 
                ? $objEvent->getDtstart() 
                : $objEvent->convertTZ($objEvent->getDtstart(), $objRecipient->getTimezone());

            $url = $objEvent->entityURL();
            if ( ! isset($tinyUrls[$url]) ) { $tinyUrls[$url] = Warecorp::getTinyUrl($url, HTTP_CONTEXT); }
            $request_url = $objEvent->getOwner()->getOwnerPath('calendar.event.apply.request/id/'.$this->getId());
            if ( ! isset($tinyUrls[$request_url]) ) { $tinyUrls[$request_url] = Warecorp::getTinyUrl($request_url, HTTP_CONTEXT); }
            //break;
            
            $recipient = new Warecorp_SOAP_Type_Recipient();
            $recipient->setEmail( $objRecipient->getEmail() );
            $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
            $recipient->setLocale( null );
            $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
            $recipient->addParam( 'event_date', $eventDtstart->toString("yyyy-MM-dd") );
            $recipient->addParam( 'event_time', $objEvent->isAllDay() ? 'All Day' : $eventDtstart->toString("h:mm").' '.$eventDtstart->get(Zend_Date::MERIDIEM).' '.( $objEvent->isTimezoneExists() ? $eventDtstart->get(Zend_Date::TIMEZONE) : '' ) );
            $recipient->addParam( 'event_url', $tinyUrls[$url] );
            $recipient->addParam( 'request_url', $tinyUrls[$request_url] );
            $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
            $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
            $msrvRecipients->addRecipient($recipient);
            
            /* return event timezone */
            if ( $useEventTzFromVenue ) { $objEvent->setTimezone($originalEventTimezone); }
            if ( sizeof($tinyUrls) > $this->maxTinyUrlsInArray ) { $tinyUrls = array(); }
            
            $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
             
            try { 
                $this->createMailCampaign($msrvRecipients, 'CALENDAR_MESSAGE_CHANGE_HOST', $objEvent, $pmbRecipients); 
                $msrvSended = true;
            } catch ( Exception $e ) { $msrvSended = false; }
        }
        
        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $this->_sendMessage($objRecipient);
        }
    }
    
    /**
     * 
     * @param Warecorp_User $objRecipient
     * @return unknown_type
     * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
     */
    private function _sendMessage(Warecorp_User $objRecipient)
    {
        $objEvent = new Warecorp_ICal_Event($this->getEventId());
        $originalEventTimezone = $objEvent->getTimezone();
        if ( null === $originalEventTimezone || $originalEventTimezone instanceof Zend_Db_Expr ) {
            $objEvent->setTimezone($objRecipient->getTimezone());
        }

        //  Send message
        $mail = new Warecorp_Mail_Template('template_key', 'CALENDAR_MESSAGE_CHANGE_HOST');

        if ( $objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) $objSender = $objEvent->getOwner();
        else $objSender = $objEvent->getCreator();

        $mail->setSender($objSender);
        $mail->addRecipient($objRecipient);
        $mail->addParam('objEvent', $objEvent);
        $mail->addParam('objInvite', $this);
        switch ( $objEvent->getOwnerType() ) {
            case Warecorp_ICal_Enum_OwnerType::GROUP :
                $mail->addParam('ApplyRequestURL', $objEvent->getOwner()->getGroupPath('calendar.event.apply.request/id/'.$this->getId()));
                break;
            case Warecorp_ICal_Enum_OwnerType::USER :
                $mail->addParam('ApplyRequestURL', $objEvent->getOwner()->getUserPath('calendar.event.apply.request/id/'.$this->getId()));
                break;
        }
        $mail->sendToPMB(true) ;
        $mail->sendToEmail(true) ;
        $mail->send();
        //  Send message end
        $objEvent->setTimezone($originalEventTimezone);
    }

    private function sendMessageToMembers(Warecorp_User $objNewHost, $objOldHost)
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
        
        $objEvent = new Warecorp_ICal_Event($this->getEventId());

        $lstAttendee = $objEvent->getAttendee()->setFetchMode('object');
        $excludeIds = array($objNewHost->getId());

        $attendee = $lstAttendee->getList();
        
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() ) { 
            if ( sizeof($attendee) != 0 ) {               
                foreach ( $attendee as &$_attendee ) {
                    if ( $_attendee->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) {
                        if ( null !== $_attendee->getOwnerId() ) {
                            if ( !in_array($_attendee->getOwnerId(),$excludeIds) ) { 
                                $this->_addMessageToMember($msrvRecipients, $_attendee->getOwner(), $objEvent); 
                                $pmbRecipients[] = $_attendee->getOwner()->getId() ? $_attendee->getOwner()->getId() : $_attendee->getOwner()->getEmail();
                            }
                        } else { 
                            $this->_addMessageToMember($msrvRecipients, $_attendee->getOwner(), $objEvent);
                            $pmbRecipients[] = $_attendee->getOwner()->getId() ? $_attendee->getOwner()->getId() : $_attendee->getOwner()->getEmail(); 
                        }
                    }
                } 
            }
            if ( null !== $objOldHost && $objOldHost instanceof Warecorp_User && !$lstAttendee->findAttendee($objOldHost) ) {
                $this->_addMessageToMember($msrvRecipients, $objOldHost, $objEvent);
                $pmbRecipients[] = $objOldHost->getId() ? $objOldHost->getId() : $objOldHost->getEmail();
            }   
            try { 
                $this->createMailCampaign($msrvRecipients, 'MESSAGETOUSERS', $objEvent, $pmbRecipients); 
                $msrvSended = true;
            } catch ( Exception $e ) { $msrvSended = false; }
        }
                
        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            foreach ( $attendee as &$_attendee ) {
                if ( $_attendee->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) {
                    if ( null !== $_attendee->getOwnerId() ) {
                        if ( !in_array($_attendee->getOwnerId(),$excludeIds) ) { $this->_sendMessageToMember($_attendee->getOwner(), $objEvent); }
                    } else { $this->_sendMessageToMember($_attendee->getOwner(), $objEvent); }
                }
            }
            if ( null !== $objOldHost && $objOldHost instanceof Warecorp_User && !$lstAttendee->findAttendee($objOldHost) ) {
                $this->_sendMessageToMember($objOldHost, $objEvent);
            }            
        }
    }

    private function _addMessageToMember(Warecorp_SOAP_Type_Recipients &$recipients, Warecorp_User $objRecipient, Warecorp_ICal_Event $objEvent)
    {
        static $tinyUrls = array();

        /**
        * Set Event Timezone
        */
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
        $useEventTzFromVenue = empty($cfgSite->use_event_tz_from_venue) || (int)$cfgSite->use_event_tz_from_venue == 0;
        if ( $useEventTzFromVenue ) {
            $originalEventTimezone = $objEvent->getTimezone();
            if ( null === $originalEventTimezone || $originalEventTimezone instanceof Zend_Db_Expr ) {
                if ( $objRecipient->getTimezone() ) { $objEvent->setTimezone($objRecipient->getTimezone()); } 
                else { $objEvent->getCreator()->getTimezone(); }
            }
        }
        $eventDtstart = $useEventTzFromVenue 
            ? $objEvent->getDtstart() 
            : $objEvent->convertTZ($objEvent->getDtstart(), $objRecipient->getTimezone());

        $url = $objEvent->entityURL();
        if ( ! isset($tinyUrls[$url]) ) { $tinyUrls[$url] = Warecorp::getTinyUrl($url, HTTP_CONTEXT); }
        
		$recipient = new Warecorp_SOAP_Type_Recipient();
		$recipient->setEmail( $objRecipient->getEmail() );
		$recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
		$recipient->setLocale( null );
        $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
		$recipient->addParam( 'event_date', $eventDtstart->toString("yyyy-MM-dd") );
		$recipient->addParam( 'event_time', $objEvent->isAllDay() ? 'All Day' : $eventDtstart->toString("h:mm").' '.$eventDtstart->get(Zend_Date::MERIDIEM).' '.( $objEvent->isTimezoneExists() ? $eventDtstart->get(Zend_Date::TIMEZONE) : '' ) );
		$recipient->addParam( 'event_url', $tinyUrls[$url] );
		$recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
		$recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
		$recipients->addRecipient($recipient);
        
        /* return event timezone */
        if ( $useEventTzFromVenue ) { $objEvent->setTimezone($originalEventTimezone); }
        if ( sizeof($tinyUrls) > $this->maxTinyUrlsInArray ) { $tinyUrls = array(); }
    }
    
    /**
     * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
     * @param Warecorp_User $objRecipient
     * @param Warecorp_ICal_Event $objEvent
     * @return unknown_type
     */
    private function _sendMessageToMember(Warecorp_User $objRecipient, Warecorp_ICal_Event $objEvent)
    {
        $originalEventTimezone = $objEvent->getTimezone();
        if ( null === $originalEventTimezone || $originalEventTimezone instanceof Zend_Db_Expr ) {
            if ( null !== $objRecipient->getId() ) $objEvent->setTimezone($objRecipient->getTimezone());
            else $objEvent->setTimezone('UTC');
        }

        //  Send message
        $mail = new Warecorp_Mail_Template('template_key', 'CALENDAR_MESSAGE_CHANGE_HOST_NOTIFICATION');

        if ( $objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) $objSender = $objEvent->getOwner();
        else $objSender = $objEvent->getCreator();

        $mail->setSender($objSender);
        $mail->addRecipient($objRecipient);
        $mail->addParam('objEvent', $objEvent);

        if ( null !== $objRecipient->getId() ) $mail->sendToPMB(true);
        else $mail->sendToPMB(false);
        $mail->sendToEmail(true) ;
        $mail->send();
        //  Send message end
        $objEvent->setTimezone($originalEventTimezone);
    }

    /**
     * +----------------------------------------------------------------------
     * |
     * |    MAIL SRV CAMPAIGNS
     * |
     * +---------------------------------------------------------------------- 
     */
    
    protected function createMailCampaign(Warecorp_SOAP_Type_Recipients $recipients, $campaign, Warecorp_ICal_Event $objEvent, $pmbRecipients = array(), $addParams = array())
    {
        /* SOAP: MailSrv */       
        try { $client = Warecorp::getMailServerClient(); }
        catch ( Exception $e ) { $client = null; }   

        if ( $client && sizeof($recipients->getRecipients()) != 0 ) {
            switch ( $campaign ) {
                case 'MESSAGETOUSERS' :
                    /* email to invited users */
                    try {
                        $campaignUID = $client->createCampaign();                        
                        if ( $objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) $objSender = $objEvent->getOwner();
                        else $objSender = $objEvent->getCreator();
                        $request = $client->setSender($campaignUID, $objSender->getEmail(), $objSender->getFirstname().' '.$objSender->getLastname());
                        $request = $client->setTemplate($campaignUID, 'CALENDAR_MESSAGE_CHANGE_HOST_NOTIFICATION', HTTP_CONTEXT); /* CALENDAR_MESSAGE_CHANGE_HOST_NOTIFICATION */
						
						/* add params */
						$params = new Warecorp_SOAP_Type_Params();
						$params->loadDefaultCampaignParams();
						$params->addParam( 'event_title', $objEvent->getTitle() );
						$params->addParam( 'event_owner_login', $objEvent->getOwnerType() == "user" ? $objEvent->getOwner()->getLogin() : $objEvent->getCreator()->getLogin() );
						$params->addParam( 'event_owner_full_name', $objEvent->getOwnerType() == "user" ? $objEvent->getOwner()->getFirstname().' '.$objEvent->getOwner()->getLastname() : $objEvent->getCreator()->getFirstname().' '.$objEvent->getCreator()->getLastname() );
						$params->addParam( 'event_venue', $objEvent->getEventVenue() ? $objEvent->getEventVenue()->getName() : '' );
						$params->addParam( 'event_description', $objEvent->getDescription() );
						$params->addParam( 'event_sender_login', $objSender->getLogin() ? $objSender->getLogin() : 'member' );
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
                case 'CALENDAR_MESSAGE_CHANGE_HOST' :
                    /* email to invited users */
                    try {
                        $campaignUID = $client->createCampaign();                        
                        if ( $objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) $objSender = $objEvent->getOwner();
                        else $objSender = $objEvent->getCreator();
                        $request = $client->setSender($campaignUID, $objSender->getEmail(), $objSender->getFirstname().' '.$objSender->getLastname());
                        $request = $client->setTemplate($campaignUID, 'CALENDAR_MESSAGE_CHANGE_HOST', HTTP_CONTEXT); /* CALENDAR_MESSAGE_CHANGE_HOST */
                        
                        /* add params */
                        $params = new Warecorp_SOAP_Type_Params();
                        $params->loadDefaultCampaignParams();
                        $params->addParam( 'event_title', $objEvent->getTitle() );
                        $params->addParam( 'sender_full_name', $objEvent->getOwnerType() == "user" ? $objEvent->getOwner()->getFirstname().' '.$objEvent->getOwner()->getLastname() : $objEvent->getCreator()->getFirstname().' '.$objEvent->getCreator()->getLastname() );
                        $params->addParam( 'event_venue', $objEvent->getEventVenue() ? $objEvent->getEventVenue()->getName() : '' );
                        $params->addParam( 'event_description', $objEvent->getDescription() );
                        $params->addParam( 'event_sender_login', $objSender->getLogin() ? $objSender->getLogin() : 'member' );
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
