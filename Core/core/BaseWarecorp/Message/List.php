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
 * @author Artem Sukharev, Andrew Peresalyak
 * @version 1.0
 * @created 24-Jul-2007 13:48:29
 */
class BaseWarecorp_Message_List
{
	/**
	 *   
	 */
	private $_db = null;
	/**
	 * current page for select
	 */
	private $_currentPage = null;
	/**
	 *     
	 */
	private $_listSize = null;
	/**
	 *    
	 */
	private $_order = null;
	/**
	 * 
	 * Warecorp_Message_eFolders
	 */
	private $_folder = 1;
//	/**
//	 * 
//	 */
//	private $_folderRecovery = null;

	function __construct()
	{
	    $this->_db = Zend_Registry::get("DB");
	}

	/**
	 * current page for select
	 */
	public function getCurrentPage()
	{
		return $this->_currentPage;
	}

	/**
	 * current page for select
	 *
	 * @param newVal
	 */
	public function setCurrentPage($newVal)
	{
		$this->_currentPage = $newVal;
		return $this;
	}

	/**
	 *     
	 */
	public function getListSize()
	{
		return $this->_listSize;
	}

	/**
	 *     
	 *
	 * @param newVal
	 */
	public function setListSize($newVal)
	{
		$this->_listSize = $newVal;
		return $this;
	}

	/**
	 *    
	 */
	public function getOrder()
	{
		return $this->_order;
	}

	/**
	 *    
	 *
	 * @param newVal
	 */
	public function setOrder($newVal)
	{
	    if ($newVal === null) return;
	    switch ($newVal){
	        case 'from-asc': $var = 'sender.login ASC'; break;
	        case 'to-asc': $var = 'to-asc'; break;
	        case 'title-asc': $var = 'subject ASC'; break;
	        case 'date-asc': $var = 'create_date ASC'; break;
	        case 'from-desc': $var = 'sender.login DESC'; break;
	        case 'to-desc': $var = 'to-desc'; break;
	        case 'title-desc': $var = 'subject DESC'; break;
	        case 'date-desc': $var = 'create_date DESC'; break;
	        default: $var = 'create_date ASC'; break;
	    }
		$this->_order = $var;
		return $this;
	}

	/**
	 * Warecorp_Messages_eFolders
	 */
	public function getFolder()
	{
		return $this->_folder;
	}

	/**
	 * ,    .   : .
	 * Warecorp_Messages_eFolders
	 *
	 * @param newVal
	 */
	public function setFolder($newVal)
	{	   
		if($newVal !== null) $this->_folder = $newVal;
		return $this;
	}

    /**
	 *    Warecorp_Messages_Standard   .
	 *  -   Warecorp_User.    
	 *   : currentPage, listSize, order,
	 * folder
	 *
	 * @param ownerId
	 */
    public function findByOwner($ownerId)
    {
        $query = $this->_db->select();
	    $query->from(array('zum' => 'zanby_users__messages'), 'zum.id')
	          ->joinleft(array('sender' => 'zanby_users__accounts'), 'zum.sender_id = sender.id')
	          ->where('zum.owner_id = ?', $ownerId)
	          ->where('zum.folder = ?', $this->getFolder());

        if ($this->_order == 'to-asc' || $this->_order == 'to-desc') {
	        $cmpMessagesByRecipients = create_function('$message1, $message2',
            '
                return strnatcmp($message1->getRecipientsStringName(), $message2->getRecipientsStringName());
            ');
            
            $messages = $this->_db->fetchCol($query);
    	    foreach ( $messages as &$message ) {
    	       $message = new Warecorp_Message_Standard($message);
    	    }
    	    usort($messages, $cmpMessagesByRecipients);
            if ($this->_order == 'to-desc') $messages = array_reverse($messages);
            $messages = array_slice($messages, $this->getListSize()*($this->getCurrentPage()-1), $this->getListSize());
            return $messages;
	    }    
        
	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	       $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    }
        if ( $this->getOrder() !== null && ($this->_order != 'to-asc' && $this->_order != 'to-desc')) {
            $query->order($this->getOrder());
        }
	    $messages = $this->_db->fetchCol($query);
	    foreach ( $messages as &$message ) {
	       $message = new Warecorp_Message_Standard($message);
	    }

	    return $messages;
	}

	
	public function findAllByOwner($ownerId)
    {
        $query = $this->_db->select();
	    $query->from(array('zum' => 'zanby_users__messages'), 'zum.id')
	          ->joinleft(array('sender' => 'zanby_users__accounts'), 'zum.sender_id = sender.id')
	          ->where('zum.owner_id = ?', $ownerId)
	          ->where('zum.folder = ?', $this->getFolder());
        
	    if ($this->_order == 'to-asc' || $this->_order == 'to-desc') {
	        function cmpMessagesByRecipients($message1, $message2)
            {
                return strnatcmp($message1->getRecipientsStringName(), $message2->getRecipientsStringName());
            }
            
            $messages = $this->_db->fetchCol($query);
    	    foreach ( $messages as &$message ) {
    	       $message = new Warecorp_Message_Standard($message);
    	    }
    	    usort($messages, "cmpMessagesByRecipients");
            if ($this->_order == 'to-desc') $messages = array_reverse($messages);
            $messages = array_slice($messages, $this->getListSize()*($this->getCurrentPage()-1), $this->getListSize());
            return $messages;
	    }    
	    
        if ( $this->getOrder() !== null && ($this->_order != 'to-asc' && $this->_order != 'to-desc')) {
            $query->order($this->getOrder());
        }
	    $messages = $this->_db->fetchCol($query);
	    foreach ( $messages as &$message ) {
	       $message = new Warecorp_Message_Standard($message);
	    }
	    
	    return $messages;
	}
	/**
	 *      . 
	 *      : folder
	 * @param recipientId
	 */
	public function countByOwner($ownerId)
	{
	    $query = $this->_db->select();	    
	    $query->from(array('zum' => 'zanby_users__messages'), new Zend_Db_Expr('COUNT(zum.id)'))
	          ->where('zum.owner_id = ?', $ownerId)
	          ->where('zum.folder = ?', $this->getFolder());
        return $this->_db->fetchOne($query);
	}

	/**
	 *      . 
	 *      : folder
	 * @param recipientId
	 */
	public function countUnreadByOwner($ownerId)
	{
	    $query = $this->_db->select();	    
	    $query->from(array('zum' => 'zanby_users__messages'), new Zend_Db_Expr('COUNT(zum.id)'))
	          ->where('zum.owner_id = ?', $ownerId)
	          ->where('zum.folder = ?', $this->getFolder())
              ->where('isread = ?', 0);
              
        return $this->_db->fetchOne($query);
	}

	/**
	 *  ,       .
	 *
	 * @param userId
	 */
	public function getMessagesFoldersList($userId)
    {
        $folders = array();
        $folders['inbox']['all'] = $this->_db->fetchOne($this->_db->select()
        ->from('zanby_users__messages', new Zend_Db_Expr('COUNT(`id`)'))
        ->where('owner_id = ?', $userId)
        ->where('folder = ?', 1)
        );

        $folders['inbox']['unread'] = $this->_db->fetchOne($this->_db->select()
        ->from('zanby_users__messages', new Zend_Db_Expr('COUNT(`id`)'))
        ->where('owner_id = ?', $userId)
        ->where('folder = ?', 1)
        ->where('isread = ?', 0)
        );

        $folders['sent']['all']  = $this->_db->fetchOne($this->_db->select()
        ->from('zanby_users__messages', new Zend_Db_Expr('COUNT(`id`)'))
        ->where('owner_id = ?', $userId)
        ->where('folder = ?', 2)
        );

        $folders['sent']['unread'] = $this->_db->fetchOne($this->_db->select()
        ->from('zanby_users__messages', new Zend_Db_Expr('COUNT(`id`)'))
        ->where('owner_id = ?', $userId)
        ->where('folder = ?', 2)
        ->where('isread = ?', 0)
        );

        $folders['draft']['all']  = $this->_db->fetchOne($this->_db->select()
        ->from('zanby_users__messages', new Zend_Db_Expr('COUNT(`id`)'))
        ->where('owner_id = ?', $userId)
        ->where('folder = ?', 3)
        );

        $folders['draft']['unread'] = $this->_db->fetchOne($this->_db->select()
        ->from('zanby_users__messages', new Zend_Db_Expr('COUNT(`id`)'))
        ->where('owner_id = ?', $userId)
        ->where('folder = ?', 3)
        ->where('isread = ?', 0)
        );

        $folders['trash']['all']  = $this->_db->fetchOne($this->_db->select()
        ->from('zanby_users__messages', new Zend_Db_Expr('COUNT(`id`)'))
        ->where('folder = ?', 4)
        ->where('owner_id = ?', $userId)
        );

        $folders['trash']['unread'] = $this->_db->fetchOne($this->_db->select()
        ->from('zanby_users__messages', new Zend_Db_Expr('COUNT(`id`)'))
        ->where('isread = ?', 0)
        ->where('folder = ?', 4)
        ->where('owner_id = ?', $userId)
        );

        return $folders;
    }
    
    /**
	 *  id ,   .
	 *
	 * @param userId
	 */
    public function getNextMessageId($message, $ownerId)
    {      
        $ids = $this->findByOwner($ownerId);
	    for($i=0; $i<count($ids); $i++){
	        if($ids[$i]->getId() == $message->getId()) 
	           if($i == count($ids)-1) return null;
	           else return $ids[$i+1]->getId();
	    }
    }
    
    /**
	 *  id ,   .
	 *
	 * @param userId
	 */
    public function getPreviousMessageId($message, $ownerId)
    {
        $ids = $this->findByOwner($ownerId);
	    for($i=0; $i<count($ids); $i++){
	        if($ids[$i]->getId() == $message->getId()) 
	           if($i == 0) return null;
	           else return $ids[$i-1]->getId();
	    }
    }
    
    /**
	 *       .
	 *
	 * @param userId
	 */
    public function getIndexMessageInList($messageId, $ownerId)
    {
        $ids = $this->findAllByOwner($ownerId);
        for ($i=0; $i<count($ids); $i++) {
            if ($ids[$i]->getId() == $messageId) {
                break;
            }
        }
        return $i+1;
    }
}
