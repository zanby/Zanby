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

class BaseWarecorp_SOAP_Callback
{
    /**
     * @param string $campaignUID First string
     * @param array $params Second array
     * @return boolean
     */
    public function callbackAddPMBMessage( $campaignUID, $params )
    {
        $objUser = new Warecorp_User('email', $params['mailsrv:recipient_email']);
        if ( !$objUser || null === $objUser->getId() ) return true;
        
        $message = new Warecorp_Message_Standard();
        
        $message->setSenderId( $params['sender_id'] );
        $message->setSenderType( $params['sender_type'] == 'user' ? 1 : 2 );                
        $message->setRecipientsListFromStringId( $params['recipients'] );        
        
        $message->setSubject( isset($params['mailsrv:pmb_subject']) ? $params['mailsrv:pmb_subject'] : 'Subject' );
        $message->setBody( isset($params['mailsrv:pmb_message']) ? $params['mailsrv:pmb_message'] : 'Message' );
        
        $message->setOwnerId($objUser->getId());                    
        $message->setFolder(Warecorp_Message_eFolders::INBOX);
        $message->setIsRead(0);
        $message->save(false);

        if ( isset($params['onSend_addJoinFamilyRequest']) ) {
            $onSend_addJoinFamilyRequest = Zend_Json::decode($params['onSend_addJoinFamilyRequest']);
            if ( $onSend_addJoinFamilyRequest && is_array($onSend_addJoinFamilyRequest) && isset($onSend_addJoinFamilyRequest['group']) && isset($onSend_addJoinFamilyRequest['related_groups']) ) {
                $objGroup = Warecorp_Group_Factory::loadById( $onSend_addJoinFamilyRequest['group'] );
                if ( $objGroup && $objGroup->getId() ) {
                    $related_groups = $onSend_addJoinFamilyRequest['related_groups'];
                    if ( $related_groups && is_array($related_groups) ) {
                        foreach ( $related_groups as $id ) {
                            $objTmpGroup = Warecorp_Group_Factory::loadById( $id );
                            if ( $objTmpGroup && $objTmpGroup->getId() ) {
                                $objGroup->setRequestRelation($message->getId(), $objTmpGroup);
                            }
                        }
                    }
                }
            }
            
        }
        
        if ( isset($params['onSend_addJoinRequest']) ) {
            $onSend_addJoinRequest = Zend_Json::decode($params['onSend_addJoinRequest']);
            if ( $onSend_addJoinRequest && is_array($onSend_addJoinRequest) && isset($onSend_addJoinRequest['group']) && isset($onSend_addJoinRequest['user']) ) {
                $objGroup = Warecorp_Group_Factory::loadById( $onSend_addJoinRequest['group'] );
                if ( $objGroup && $objGroup->getId() ) {
                    $user = $onSend_addJoinRequest['user'];
                    if ( $user ) {
                        $objUser = new Warecorp_User( 'id', $user );
                        if ( $objUser && $objUser->getId() ) {
                            $objGroup->setRequestRelation($message->getId(), $objUser);
                        }
                    }
                }
            }
        }
        
        if ( isset($params['onSend_addFriendRequest']) ) {
            $onSend_addFriendRequest = Zend_Json::decode($params['onSend_addFriendRequest']);
            if ( $onSend_addFriendRequest && is_array($onSend_addFriendRequest) && isset($onSend_addFriendRequest['request']) ) {
                $request = new Warecorp_User_Friend_Request_Item( $onSend_addFriendRequest['request'] );
                if ( $request && $request->getId() ) {
                    $request->addRelation($message->getId());
                }
            }
        }
        
        return true;
    }
    
}
