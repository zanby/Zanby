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
 *
 * @package    Warecorp_User
 * @copyright  Copyright (c) 2006
 */

/**
 * Maillist entity class
 *
 * @author Alexey Loshkarev
 */
class BaseWarecorp_User_Maillist extends Warecorp_Data_Entity
{
    
    public $id;
    public $userId;
    public $groupId;
    public $name;
    public $description;
    public $entries = array();
    
    public $user;
    public $group;
    
    /**
     * Constructor.
     *
     * @author Alexey Loshkarev
     */
    public function __construct($id = null)
    {
        parent::__construct('zanby_users__maillists');
        
        $this->addField('id');
        $this->addField('user_id', 'userId');   // owner
        $this->addField('group_id', 'groupId'); // assigned group
        $this->addField('name', 'name');        //list name
        $this->addField('description', 'description');
        if ( $id ) {
           $this->pkColName = 'id';
           $this->loadByPk($id);
           
           $this->user = new Warecorp_User('id', $this->userId);
           
           if ($this->groupId) {
               $this->group = Warecorp_Group_Factory::loadById($this->groupId, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
           }
           $sql = $this->_db->select()->from('zanby_users__maillist_entries', array('id' => 'addressbook_entry_id'))
               ->where('maillist_id = ?', $this->id);
           $this->entries = $this->_db->fetchAll($sql);
        }       
    }
    
    
    /**
     * Add entry/entries to maillist
     *
     * @param mixed $entries - array of addresbook_ids or one entryId
     * @return void
     *
     * @author Alexey Loshkarev
     */
    public function addEntry($entries)
    {
        if (is_numeric($entries)) {
            $entries = array($entries);
        }
        
        if (is_array($entries)) {
            foreach($entries as $entryId) {
                $entry = new Warecorp_User_Addressbook($entryId);
                if ($entry->ownerId == $this->userId) {
                    $this->entries[] = array('id' => $entryId);
                } else {
                    throw new Zend_Exception("You are not owner of this entry!");
                }
            }
        } else {
            throw new Zend_Exception("Incorrect entries format!");
        }
        
    }

    /**
     * Add entry/entries to maillist
     *
     * @param mixed $entries - array of "addresbook.firstName addressbook.lastName" or one string
     * @return void
     *
     * @author Alexey Loshkarev
     */
    public function addEntryByName($entries)
    {
        if (is_string($entries)) {
            $entries = array($entries);
        }
        
        if (is_array($entries)) {
            foreach($entries as $entry) {
                $id = Warecorp_User_Addressbook::get($this->userId, $entry);
                $this->addEntry($id);
            }
        } else {
            throw new Zend_Exception("Incorrect entries format!");
        }
    }
    
    
    /**
     * Delete entry from maillist
     * 
     * @param mixed $delEntries - array of addresbook_ids or one entryId
     * @return void
     * 
     * @author Alexey Loshkarev
     */
    public function deleteEntry($delEntries)
    {
        if (is_numeric($delEntries)) {
            $delEntries = array($delEntries);
        }
        
        if (is_array($delEntries)) {
            $_entries = array();
            foreach($this->entries as $entry) {
                if (!in_array($entry['id'], $delEntries)) {
                    $_entries[] = $entry;
                }
            }
            $this->entries = $_entries;
        } else {
            throw new Zend_Exception("Incorrect entries format!");
        }
        
    }
    
    /**
     * get maillist entries
     *
     * @param enum('first_name', 'last_name', 'email', 'group', 'maillist') orderBy
     * @param boolean dest    - false ('ASC'), true ('DESC')
     * @param integer page - 1..num - addressbook page number
     * @param integer size - page size
     * @return array of Warecorp_User_Addressbook
     *
     *
     * @author Alexey Loshkarev
     */
    public function getEntries($orderBy = 'first_name', $desc = false, $page = 1, $size = 10, $filter = false) 
    {
        
        if ($this->isExist) {
            $entries = array();
            
            $sql = $this->_db->select()->from('zanby_users__maillist_entries', 'addressbook_entry_id')
                ->where('maillist_id = ?', $this->id);
            $entryIds = $this->_db->fetchCol($sql);
            
            if (count($entryIds)) {
                
                $sql = $this->_db->select()->from('view_users__addressbook')
                    ->where('owner_id = ?', $this->userId)
                    ->where('id IN (?)', $entryIds);
                $allowedFields = array('first_name',
                                        'last_name',
                                        'email',
                                        'groups',
                                        'maillists');
        
                $orderBy = (in_array($orderBy, $allowedFields)) ? $orderBy : $allowedFields[0];
        
        
                $sql->order($orderBy .' '. (($desc) ? 'DESC' : 'ASC'));
        
                if ($page) {
                    $sql->limitPage($page, $size);
                }
                if ($filter) {
                	$sql->where('UPPER(SUBSTRING(first_name, 1, 1)) = ?', $filter);
                }
                $entries = $this->_db->fetchAll($sql);
                
                foreach ($entries as &$contact) {
                    switch ($contact['type']) {
                    case 'user':
                        $contact['user'] = new Warecorp_User('id', $contact['item_id']);
                        break;
                    case 'maillist':
                        $contact['maillist'] = new Warecorp_User_Maillist($contact['item_id']);
                        break;
                    default:
                    }
                
                }
            }

            return $entries;
            
            
        } else {
            return false;
        }
        
    }


    /**
     * Save maillist with entries
     *
     * @return boolean result - saved/error
     *
     * @author Alexey Loshkarev
     */
    public function save()
    {
        
        parent::save();

        $result = true;
        $where = $this->_db->quoteInto('maillist_id = ?', $this->id);
        $this->_db->delete('zanby_users__maillist_entries', $where);
        //@todo - change multiple inserts into 1 complex insert. Maybe, future versions of PDO will support this
        foreach($this->entries as $entry) {
            $result = $result && $this->_db->insert('zanby_users__maillist_entries', 
                                                    array('maillist_id' => $this->id,
                                                          'addressbook_entry_id' => $entry['id'])
                                                    );
            if (!$result) {
                throw new Zend_Exception("Can't save maillist entries!");
                break;
            }
        }
        
        return $result;
    }
    /**
    * return contacts count in this mail list 
    *
    * @return int result - count contact in this maillist
    * @author Ivan Khmurchik
    */
    public function getMaillistContactsCount($filter = false)
    {
        $select = $this->_db->select()
        ->from(array('zume' => 'zanby_users__maillist_entries'), new Zend_Db_Expr('COUNT(maillist_id)'))
        ->where('zume.maillist_id = ?', $this->id)
        ->joininner(array('zuae' => 'zanby_users__addressbook_entries'), "zume.addressbook_entry_id = zuae.id");
        if ($filter) {
            $select->where('UPPER(SUBSTRING(zuae.first_name, 1, 1)) = ?', $filter);
        }
        return $this->_db->fetchOne($select);
    }
    
    /**
	 * provides list of active letters, used in maillist first-letter filter
	 *
	 * @return array('A'=> $num1, ..., 'Z'=> $num26). Letters can be skipped
	 *
	 * @todo compatibility with non-ascii and multichars symbols
	 * @author Andrew Peresalyak
	 */
    public function getActiveLetters()
    {

        $select = $this->_db->select();

        $select->from(
            array('zume' => 'zanby_users__maillist_entries'),
            array('letter' => new Zend_Db_Expr('UPPER(SUBSTRING(zuae.first_name, 1, 1))'), 'count' => new Zend_Db_Expr('COUNT(*)'))
        )
        ->joininner(array('zuae' => 'zanby_users__addressbook_entries'), "zume.addressbook_entry_id = zuae.id")
        ->where('maillist_id = ?', $this->id)
        
//        ->where('UPPER(SUBSTRING(zuae.first_name, 1, 1)) = ?', $filter)
        ->where('ORD(UPPER(SUBSTRING(zuae.first_name, 1, 1))) BETWEEN 65 AND 90')
        ->group('letter')
        ->order('letter');

//        dump($select->__toString());

        $result = $this->_db->fetchPairs($select);

//        dump($result);

        return $result;
    }
    
    /**
     * Checks existance of maillist
     *
     * @param integet $ownerId - maillist owner ID
     * @param string $name - maillist name
     * @return boolean result - exists or no
     *
     * @author Alexey Loshkarev
     */
    public static function isMaillistExists($ownerId, $name)
    {
        return (Warecorp_User_Maillist::getId($ownerId, $name) != 0);
    }
    
    public function isMaillistConsistOf($contactId)
    {
        if (is_null($contactId)) return;
        $select = $this->_db->select()
            ->from('zanby_users__maillist_entries', new Zend_Db_Expr('COUNT(maillist_id)'))
            ->where('maillist_id = ?', $this->id)
            ->where('addressbook_entry_id = ?', $contactId);

        return $this->_db->fetchOne($select)>0;
    }
    
    /**
     * Return maillist id by name
     *
     * @param integet $ownerId - maillist owner ID
     * @param string $name - maillist name
     * @return boolean result - exists or no
     *
     * @author Alexey Loshkarev
     */
    public static function getId($ownerId, $name)
    {
        
        $db = Zend_Registry::get("DB");
        $sql = $db->select()
            ->from('zanby_users__maillists', 'id')
            ->where('user_id = ?', $ownerId)
            ->where('name = ?', $name);
        $id = (int)$db->fetchOne($sql);
        
        return $id;
    }
}
