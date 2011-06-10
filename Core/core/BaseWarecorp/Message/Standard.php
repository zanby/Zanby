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
 * Класс сообщения. Сообщение может отноститься к пользовательской системе
 * сообщений либо к групповой.
 * @author Artem Sukharev
 * @author Ivan Khmurchik
 * @author Eugene Kirdzei
 * @version 1.0
 * @created 24-Jul-2007 13:48:27
 */
class BaseWarecorp_Message_Standard
{
    /**
     * Адаптер базы данных
     */
    private $_db;
    /**
     * Идентификатор сообщения
     */
    private $_id;
    /**
     * Идентификатор получателя (пользователей)
     */
    private $_recipientsList = array();
    /**
     * Идентификатор отправителя
     */
    private $_senderId;
    /**
     * Идентификатор отправителя
     */
    private $_senderType = 1;
    /**
     * Subject of messge
     */
    private $_subject;
    /**
     * Body of message
     */
    private $_body;
    /**
     * Date of message creation
     */
    private $_createDate;
    /**
     * Флаг, показывающий, было ли прочтено сообщение
     */
    private $_isRead;
    /**
     * Папка, в которой лежит данное сообщение. См.Warecorp_Message_eFolders
     */
    private $_folder;
    /**
     * Флаг, показывающий, было ли удалено сообщение
     */
    private $_folderRecovery = null;
    /**
     * Owner of message
     */
    private $_ownerId;

    /*
     * Is this message request
     */
    private $_isRequest;
    
    /**
     * Class constructor
     * @param $var
     * @return unknown_type
     */
    function __construct($var = null)
    {
        $this->_db = Zend_Registry::get("DB");
        if ( $var !== null ) $this->load($var);
    }

    /**
     * Идентификатор сообщения
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Идентификатор сообщения
     *
     * @param newVal
     */
    public function setId($newVal)
    {
        if ($newVal !== $this->_id) $this->_id = $newVal;
        return $this;
    }

    /**
     * дентификатор получателя (пользователей)
     */
    public function getRecipientsListId()
    {
        return $this->_recipientsList;
    }

    /**
     * ??дентификаторы получателей (пользователей)
     *
     * @param newVal - string
     */
    public function setRecipientsListFromStringId($newVal)
    {
        $this->_recipientsList = $this->stringToArray($newVal);
        return $this;
    }

    /**
     * set recipients from array of Warecorp_User objects
     *
     * @param $users - array
     * @author Saharchuk Timofei
     */
    public function setRecipientsListFromArrayOfUsers($users){
        $this->_recipientsList = array();
        if ( is_array($users) ) {
            //set current recipients list to empty value
            foreach($users AS $user) {
                if ($user instanceof Warecorp_User){
                    $this->_recipientsList[] = $user->getId();
                }
            }
        } 
        elseif ( $users instanceof Warecorp_User ) {
            $this->_recipientsList[] = $users->getId();
        } else {
            Warecorp_Exception('Error useing setRecipientsListFromArrayOfUsers method: invalid parameter!');
        }
        $this->_recipientsList = array_unique($this->_recipientsList);
        return $this;
    }

    /**
     * add recipients from array of emails of not registered users
     *
     * @param $emails - array|string emails
     * @author Saharchuk Timofei
     */
    public function addRecipientsEmailsLike($emails)
    {
        if ( is_array($emails) ){
            $this->_recipientsList = array_merge($this->_recipientsList, $emails);
        } else {
            $this->_recipientsList[] = $emails;
        }
        $this->_recipientsList = array_unique($this->_recipientsList);
        return $this;
    }

   /**
    * Remove all recipients from list
    * @author Roman Gabrusenok
    */
    public function cleanRecipientsList()
    {
        $this->_recipientsList = array();
        return $this;
    }

    /**
     * 
     * @param $array
     * @param $wrong_nicks
     * @return unknown_type
     */
    public function setRecipientsListFromArray($array, &$wrong_nicks = null)
    {
        if ( is_array($array) ) {
        $nick_array = $array;
        $id_array = array();
        $wrong_nicks = array();
        foreach ($nick_array as $item){
            $user = new Warecorp_User("login", $item);
            if ($user !== null) {
                $id_array[] = $user->getId();
            }
            else $wrong_nicks[] = $item;
        }
        $this->_recipientsList = $id_array;
        return $this;
        } else new Warecorp_Exception("error using method");

    }

    /**
     * 
     * @param $newVal
     * @param $wrong_nicks
     * @return unknown_type
     */
    public function setRecipientsListFromStringName($newVal, &$wrong_nicks = null)
    {
        if (!isset($newVal)) return $this;
        $nick_array = $this->stringToArray($newVal);
        $id_array = array();
        $wrong_nicks = array();
        foreach ($nick_array as $item){
            $user = new Warecorp_User("login", $item);
            if ($user !== null) {
                $id_array[] = $user->getId();
            }
            else $wrong_nicks[] = $item;
        }
        $this->_recipientsList = $id_array;
        return $this;
    }

    /**
     * Список получателей
     * @return array
     */
    public function getRecipientsList()
    {
        $recipients = array();
        foreach ($this->getRecipientsListId() as $recipientId) {
            if (is_numeric($recipientId)){
                $recipient = new Warecorp_User('id', $recipientId);
            } else {
                $recipient = new Warecorp_User();    // section added by Saharchuk Timofei
                $recipient->setEmail($recipientId);
            }
            $recipients[] = $recipient;
        }
        return $recipients;
    }

    /**
     * строка - список получателей (логины)
     * @return string
     */
    public function getRecipientsStringName()
    {
        $recipients = array();
        foreach ($this->getRecipientsList() as $recipient) {
            if ($recipient->getId()){                                      // condition added by Saharchuk Timofei
                $recipients[] = $recipient->getRecipientDisplayName();
            } else {
                $recipients[] = $recipient->getEmail();
            }
        }
        return join(';', $recipients);
        //return $this->arrayToString($recipients, ";");
    }

    /**
     * Gets recipients emails list in view of To multiple field input
     * @return string string with emails
     * @author Saharchuk Timofei
     */
    public function getRecipientsTargetEmails()
    {
        $recipients = array();
        foreach ($this->getRecipientsList() as $recipient) {
            $recipients[] = '<'.$recipient->getEmail().'>';
        }
        return implode($recipients, ", ");
    }

    /**
     * строка - список получателей (логины)
     * @return string
     */
    public function getRecipientsStringId()
    {
        return $this->arrayToString($this->getRecipientsListId(), ';');
    }

    /**
     * Идентификатор отправителя
     */
    public function getSenderId()
    {
        return $this->_senderId;
    }

    /**
     * 
     * @param Warecorp_User|Warecorp_Group_Standard $objSender
     * @return unknown_type
     */
    public function setSender( $objSender )
    {
        if ( $objSender instanceof Warecorp_User ) {
            $this->setSenderId( $objSender->getId() );
            $this->setSenderType(1); // @see Warecorp_Data_Entity::__construct to view more types
        } elseif ( $objSender instanceof Warecorp_Group_Standard ) {
            $this->setSenderId( $objSender->getId() );
            $this->setSenderType(2); // @see Warecorp_Data_Entity::__construct to view more types
        } else throw new Zend_Exception('Incorrect Sender');        
    }
    
    /**
     * Идентификатор отправителя
     * @param newVal
     */
    public function setSenderId($newVal)
    {
        if ($newVal !== $this->_senderId) $this->_senderId = $newVal;
        return $this;
    }

    /**
     * Идентификатор entity
     */
    public function getSenderType()
    {
        return $this->_senderType;
    }

    /**
     * Идентификатор entity
     * @param newVal
     */
    public function setSenderType($newVal)
    {
        if ($newVal !== $this->_senderType) $this->_senderType = $newVal;
        return $this;
    }

    /**
     * Отправитель
     * @param newVal
     */
    public function getSender()
    {
        if ($this->getSenderType() == 1) {
            $sender = new Warecorp_User('id',$this->getSenderId());
        } elseif ($this->getSenderType() == 2) {
            $sender = Warecorp_Group_Factory::loadById($this->getSenderId());
        }
        return $sender;
    }

    /**
     * Subject of messge
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * Subject of messge
     * @param newVal
     */
    public function setSubject($newVal)
    {
        if ($newVal !== $this->_subject) $this->_subject = $newVal;
        return $this;
    }

    /**
     * Body of message
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Body of message
     * @param newVal
     */
    public function setBody($newVal)
    {
        if ($newVal !== $this->_body) $this->_body = $newVal;
        return $this;
    }

    /**
     * Date of message creation
     */
    public function getCreateDate()
    {
        return $this->_createDate;
    }

    /**
     * Date of message creation
     * @param newVal
     */
    public function setCreateDate($newVal)
    {
        if ($newVal !== $this->_createDate) $this->_createDate = $newVal;
        return $this;
    }

    /**
     * Инициализирует объект по его id
     * @param var
     */
    private function load($var)
    {
        $query = $this->_db->select();
        $query->from('zanby_users__messages', '*')->where('id = ?', $var);
        $message = $this->_db->fetchRow($query);
        if ( $message ) {
           $this->setId($var);
           $this->setRecipientsListFromStringId($message['recipients_list']);
           $this->setSenderId($message['sender_id']);
           $this->setSenderType($message['sender_type']);
           $this->setSubject($message['subject']);
           $this->setBody($message['body']);
           $this->setCreateDate($message['create_date']);
           $this->setIsRead($message['isread']);
           $this->setFolder($message['folder']);
           $this->setFolderRecovery($message['folder_recovery']);
           $this->setOwnerId($message['owner_id']);
        }
    }

    /**
     * Сохранение объекта
     */
    public function save($sendCopyToEmail = true)
    {
        if ($this->getFolder() === Warecorp_Message_eFolders::INBOX && $sendCopyToEmail) {
           $this->sendMail();
        }
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $_localtime = new Zend_Date();
        date_default_timezone_set($defaultTimezone);
        
        $data = array();
        $data['recipients_list'] = $this->getRecipientsStringId();
        $data['sender_id']       = $this->getSenderId();
        $data['sender_type']     = $this->getSenderType();
        $data['subject']         = $this->getSubject();
        $data['body']            = $this->getBody();
        $data['create_date']     = $_localtime->getIso();        
        $data['isread']          = $this->getIsRead();
        $data['folder']          = $this->getFolder();
        $data['folder_recovery'] = $this->getFolderRecovery();
        $data['owner_id']        = $this->getOwnerId();
        $rows_affected = $this->_db->insert('zanby_users__messages', $data);
        $this->setId($this->_db->lastInsertId());
    }

    /**
     * Изменение объекта
     */
    public function update()
    {
        $data = array();
        $data['recipients_list'] = $this->getRecipientsStringId();
        $data['sender_id']       = $this->getSenderId();
        $data['sender_type']     = $this->getSenderType();
        $data['subject']         = $this->getSubject();
        $data['body']            = $this->getBody();
        $data['isread']          = $this->getIsRead();
        $data['folder']          = $this->getFolder();
        $data['folder_recovery'] = $this->getFolderRecovery();
        $data['owner_id']        = $this->getOwnerId();
        $where = $this->_db->quoteInto('id = ?', $this->getId());
        $rows_affected = $this->_db->update('zanby_users__messages', $data, $where);
    }

    /**
     * Удаление сообщения из базы данных
     * @author Eugene Kirdzei
     */
    public function delete()
    {
        //delete message only if it isn't request
        if (!$this->getIsRequest()) {
            //  remove message from table
            if ($this->getFolder() == Warecorp_Message_eFolders::TRASH) {
                $where = $this->_db->quoteInto('id = ?', $this->getId());
                $rows_affected = $this->_db->delete('zanby_users__messages', $where);
                return true;
            }
        } else {
            if ($this->getFolder() == Warecorp_Message_eFolders::TRASH) {
                $data['owner_id'] = new Zend_Db_Expr('null');
                $where = $this->_db->quoteInto('id = ?', $this->getId());
                $rows_affected = $this->_db->update('zanby_users__messages', $data, $where);
                return true;
            }
        }

        return false;
    }

    /**
     * Восстановление сообщения из базы данных
     */
    public function recovery()
    {
        if (in_array($this->getFolderRecovery(), array(Warecorp_Message_eFolders::INBOX, Warecorp_Message_eFolders::SENT, Warecorp_Message_eFolders::DRAFT)) && $this->getFolder() == Warecorp_Message_eFolders::TRASH) {
                $data['folder']          = $this->getFolderRecovery();
                $data['folder_recovery'] = null;
                $where = $this->_db->quoteInto('id = ?', $this->getId());
                $rows_affected = $this->_db->update('zanby_users__messages', $data, $where);
                return true;
        }
        else false;
    }

    /**
     * Перемещение сообщения в мусорку
     * @author Eugene Kirdzei
     */
    public function moveToTrash()
    {
        //trash message only if it isn't request
        //if (!$this->getIsRequest()) {
            if (in_array($this->getFolder(), array(Warecorp_Message_eFolders::INBOX, Warecorp_Message_eFolders::SENT, Warecorp_Message_eFolders::DRAFT))) {
                $data['folder']          = Warecorp_Message_eFolders::TRASH;
                $data['folder_recovery'] = $this->getFolder();
                $where = $this->_db->quoteInto('id = ?', $this->getId());
                $rows_affected = $this->_db->update('zanby_users__messages', $data, $where);
                return true;
           }
        //}
        return false;
    }

    /**
     * Флаг, показывающий, было ли прочтено сообщение
     */
    public function getIsRead()
    {
        return $this->_isRead;
    }

    /**
     * Флаг, показывающий, было ли прочтено сообщение
     * @param newVal
     */
    public function setIsRead($newVal)
    {
        if ($newVal !== $this->_isRead) $this->_isRead = $newVal;
        return $this;
    }

    /**
     * Папка, в которой лежит данное сообщение. Возможные варианты : см.
     * Warecorp_Message_eFolders
     */
    public function getFolder()
    {
        return $this->_folder;
    }

    /**
     * Папка, в которой лежит данное сообщение. Возможные варианты : см. Warecorp_Message_eFolders
     * @param newVal
     */
    public function setFolder($newVal)
    {
        if ($newVal !== $this->_folder) $this->_folder = $newVal;
        return $this;
    }

    /**
     * Поле показывающее, из какой папки было удалено сообщение
     */
    public function getFolderRecovery()
    {
        return $this->_folderRecovery;
    }

    /**
     * Поле показывающее, из какой папки было удалено сообщение
     * @param newVal
     */
    public function setFolderRecovery($newVal)
    {
        if ($newVal !== $this->_folderRecovery) $this->_folderRecovery = $newVal;
        return $this;
    }
    /**
     * Owner id of message
     */
    public function getOwnerId()
    {
        return $this->_ownerId;
    }

    /**
     * Owner id of message
     * @param newVal
     */
    public function setOwnerId($newVal)
    {
        if ($newVal !== $this->_ownerId) $this->_ownerId = $newVal;
        return $this;
    }

    /**
     * 
     * @param $array
     * @param $separator
     * @param $implodeSeparator
     * @return unknown_type
     */
    public function arrayToString($array, $separator = ";", $implodeSeparator = "")
    {
        foreach ($array as &$value){
            if ($value != null) $value .= $separator;
        }
        return implode($implodeSeparator, $array);
    }

	/**
	 * Convert string to array, use separator as delimiter
	 * @param string $string string to convert
	 * @param mixed $separator separator string or array
	 * @return Array
	 */
    public function stringToArray($string, $separator = ";")
    {
		if (is_array($separator)) {
			if (!count($separator)) throw new Warecorp_Exception('stringToArray function: incorrect separator');
			$sepArray = $separator;
			$i = 0;
			$expr = array();
			foreach ($sepArray as $sep) {
				if (!$i) {
					$separator = $sep;
					$i++;
					continue;
				}
				$expr[] = preg_quote($sep);
			}
			if (count($expr)) {
	    		$string = preg_replace('/((['. implode('])|([', $expr). ']))+/', $separator, $string);
			}
		}
        $tempArray = array_map('trim', explode($separator, $string));
        $array = array();
        foreach ($tempArray as $value)
        {
            if ($value != null) $array[] = $value;
        }
        return $array;
    }

    /**
     * parse To field input, conteining multiple emails.
     * @param string string field value
     * @return Array array of emails
     * @author Saharchuk Timofei
     */
    public function parseRecipientString($str)
    {
        $str    = preg_replace('/<|>/', '', $str);
        $result = explode(',', $str);
        $result = array_map('trim', $result);
        return array_filter($result);
    }

    /**
     * 
     * @return unknown_type
     */
    private function sendMail()
    {
        /**
         * collect users ids for all send session to prevent duplicate sent e-mails
         * @author Roman Gabrusenok
         */
        static $RecipientIds = array();
            
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'USER_MESSAGE_INBOX' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   
                foreach ( $this->getRecipientsListId() as $recipientId ) {                    
                    if ( !in_array($recipientId, $RecipientIds) ) {
                        $objRecipient = new Warecorp_User('id', $recipientId);
                        if ( $objRecipient && $objRecipient->getId() ) {
                            $RecipientIds[] = $recipientId;
                            $recipient = new Warecorp_SOAP_Type_Recipient();
                            $recipient->setEmail( $objRecipient->getEmail() );
                            $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                            $recipient->setLocale( null );
                            $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                            $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                            $recipient->addParam( 'url_messagelist', $objRecipient->getUserPath('messagelist') );
                            $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                            $msrvRecipients->addRecipient($recipient);                            
                        }
                    }
                }
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, SITE_NAME_AS_STRING.' Messages' );
                    $request = $client->setTemplate($campaignUID, 'USER_MESSAGE_INBOX', HTTP_CONTEXT); /* USER_MESSAGE_INBOX */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'sender_login', $this->getSender()->getLogin() );
                    $params->addParam( 'message_subject', $this->getSubject() );
                    $params->addParam( 'message_content', $this->getBody() );
                    $params->addParam( 'message_content_plain', $this->getBody() );
                    $params->addParam( 'message_content_html', nl2br(htmlspecialchars($this->getBody())) );
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'USER_MESSAGE_INBOX');
            $mail->setSender($this->getSender());
    
            if ( !in_array($this->getOwnerId(), $RecipientIds) ) {
                $user = new Warecorp_User('id', $this->getOwnerId());
                $mail->addRecipient($user);
                $RecipientIds[] = $user->getId();
            }
    
            $stringTo = "";
            foreach ( $this->getRecipientsListId() as $recipientId ) {
                /* is_numeric checking added to send mail only to registered users - by Saharchuk Timofei */
                if ( !in_array($recipientId, $RecipientIds) && is_numeric($recipientId)) {
                    $RecipientIds[] = $recipientId;
                    $recipient = new Warecorp_User('id', $recipientId);
                    $mail->addRecipient($recipient);
                }
            }
            $mail->addParam('subject', $this->getSubject());
            $mail->addParam('recipient_list', $stringTo);
            $mail->addParam('original_message', $this->getBody());
            $mail->send();
        }
    }

    /**
     * Return true when this message is request
     * @return boolean
     * @author Eugene Kirdzei
     */
    public function getIsRequest ()
    {
        if ( null == $this->_isRequest){
            $this->setIsRequest();
        }

        return $this->_isRequest;
    }

    /**
     * Check: is this message request
     * @author Eugene Kirdzei
     */
    public function setIsRequest ()
    {
        $query = $this->_db->select();

        $query->from('zanby_requests__relations', ('COUNT(*)'))
              ->where('message_id = ?', $this->getId());

        $this->_isRequest = (boolean) $this->_db->fetchOne($query);
    }
}
