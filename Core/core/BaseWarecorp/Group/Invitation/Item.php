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
 * Класс приглашение. Приглашения для групп.
 * @author Andrew Peresalyak
 * @version 1.0
 * @created 31-Aug-2007 14:45:13
 */
class BaseWarecorp_Group_Invitation_Item
{
    /**
	 * Database adapter
	 */
	private $_db;
	/**
	 * Identificator of the invitation
	 */
	private $_id;
	/**
	 * Name of invitation
	 */
	private $_name;
	/**
	 * Subject of invitation
	 */
	private $_subject;
	/**
	 * Body of invitation
	 */
	private $_body;
	/**
	 * Date of invitation creation
	 */
	private $_creationDate;
	/**
	 * Owner of invitation
	 */
	private $_ownerId;
	/**
	 * Folder of invitation
	 */
	private $_folder;

	function __construct($var = null)
	{
	    $this->_db = Zend_Registry::get("DB");
	    if ( $var !== null ) $this->load($var);
	}

	/**
	 * Initialize object by id
	 *
	 * @param var
	 */
	private function load($var)
	{
	    if (!is_numeric($var)) 
            throw new Warecorp_Exception('Function \'load()\': wrong type of parametr');
	    $query = $this->_db->select();
	    $query->from('zanby_groups__invitations', '*')
	          ->where('id = ?', $var);
	    $invitation = $this->_db->fetchRow($query);
	    if ( $invitation ) {
	       $this->setId($var);
	       $this->setOwnerId($invitation['group_owner_id']);
	       $this->setName($invitation['name']);
	       $this->setSubject($invitation['subject']);
	       $this->setBody($invitation['body']);
	       $this->setCreationDate($invitation['creation_date']);
	       $this->setFolder($invitation['folder']);
	    }
	}

	/**
	 * Save object
	 */
	public function save()
	{
	    $data = array();
	    $data['group_owner_id']        = $this->_ownerId;
	    $data['name']                  = empty($this->_name)?' ':$this->_name;
	    $data['subject']               = $this->_subject;
	    $data['body']                  = $this->_body;
        $data['creation_date']         = new Zend_Db_Expr('NOW()');
	    $data['folder']                = $this->_folder;   
	    $rows_affected = $this->_db->insert('zanby_groups__invitations', $data);
        $this->setId($this->_db->lastInsertId());
	}
	/**
	 * Update object
	 */

	public function update()
	{
	    $data = array();
	    $data['group_owner_id']        = $this->_ownerId;
	    $data['name']                  = $this->_name;
	    $data['subject']               = $this->_subject;
	    $data['body']                  = $this->_body;
        $data['creation_date']         = new Zend_Db_Expr('NOW()');
        $data['folder']                = $this->_folder;
	    $where = $this->_db->quoteInto('id = ?', $this->_id);
        $rows_affected = $this->_db->update('zanby_groups__invitations', $data, $where);
	}

	/**
	 * Delete object
	 */
	public function delete()
	{
	    //  remove invitation from table
        $where = $this->_db->quoteInto('id = ?', $this->_id);
        $rows_affected = $this->_db->delete('zanby_groups__invitations', $where);
        if($rows_affected == 1) return true;
        else return false;
	}
	/**
	 * Get groups of current invitation
	 */

	public function getGroups()
	{
		return new Warecorp_Group_Invitation_GroupList($this->getOwnerId(), $this->getId());
	}
	
	public function getId()
	{
		return $this->_id;
	}

	private function setId($newVal)
	{
	    if ($newVal !== $this->_id) $this->_id = $newVal;
		return $this;
	}

	public function getOwnerId()
	{
        return $this->_ownerId;
	}

	public function setOwnerId($newVal)
	{
	    if ($newVal !== $this->_ownerId) $this->_ownerId = $newVal;
		return $this;
	}

	public function getOwner()
	{
	    $sender = Warecorp_Group_Factory::loadById($this->_ownerId,Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
	    return $sender;
	}
	
	public function getName()
	{
		return $this->_name;
	}

	public function setName($newVal)
	{
		if ($newVal !== $this->_name) $this->_name = $newVal;
		return $this;
	}
	
	public function getSubject()
	{
		return $this->_subject;
	}

	public function setSubject($newVal)
	{
		if ($newVal !== $this->_subject) $this->_subject = $newVal;
		return $this;
	}

	public function getBody()
	{
		return $this->_body;
	}

	public function setBody($newVal)
	{
		if ($newVal !== $this->_body) $this->_body = $newVal;
		return $this;
	}

	public function getCreationDate()
	{
		return $this->_creationDate;
	}

	public function setCreationDate($newVal)
	{
	    if ($newVal !== $this->_creationDate) $this->_creationDate = $newVal;
	    return $this;
	}

	public function getFolder()
	{
		return $this->_folder;
	}

	public function setFolder($newVal)
	{
	    if ($newVal !== $this->_folder) $this->_folder = $newVal;
	    return $this;
	}
	
	public function send()
	{
		$groups = $this->getGroups()->setOnlyGroups()->returnAsAssoc()->getList();
		$groups = array_keys($groups);			
		$this->getOwner()->getInvitationList()->setFolder(Warecorp_Group_Invitation_eFolders::DRAFT)->getGroups()->deleteGroups($groups);
	}	
}
