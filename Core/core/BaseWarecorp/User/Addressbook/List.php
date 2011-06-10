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
 * @author Dmitry Strupinsky, Andrew Peresalyak, Ivan Khmurchik
 * @version 1.0
 * @created 25-сен-2007 16:23:44
 */
class BaseWarecorp_User_Addressbook_List extends Warecorp_Abstract_List
{

    /**
     * contact list id
     */
    private $_contactListId;
    /**
     * owner of contact list
     */
    private $_ownerId;
    private $_count;
    

    function __construct($listId = null, $ownerId = null)
    {
        $this->_contactListId = $listId;
        $this->_ownerId = $ownerId;
        parent::__construct();
    }

    public function setIncludeCidsCondition($value)
    {
        $iscondexist = false;
        if (is_array($value)) {
            $this->addWhere('((1<>1)');
            foreach($value as $cid) {
                if (!is_numeric($cid) && !empty($cid)) {
                    preg_match('/'.Warecorp_User_Addressbook_eType::CONTACT_LIST.'|'.Warecorp_User_Addressbook_eType::GROUP_MEMBER.
                    '|'.Warecorp_User_Addressbook_eType::FRIEND.'|'.Warecorp_User_Addressbook_eType::GROUP.'/', $cid, $cid_type);
                    if (empty($cid_type[0])) continue;
                    switch ((string)$cid_type[0]) {
                        case (string)Warecorp_User_Addressbook_eType::GROUP:
                            preg_match('/[0-9]+/', $cid, $entity);
                            if (empty($entity[0])) continue;
                            $group_id = $entity[0];
                            $where = "(entity_id = '$group_id')";                            
                            $iscondexist = true;
                            break;
                        case (string)Warecorp_User_Addressbook_eType::GROUP_MEMBER:
                            preg_match_all('/[0-9]+/', $cid, $entity, PREG_PATTERN_ORDER);
                            if (empty($entity[0][0]) || empty($entity[0][1])) continue;
                            $group_id = $entity[0][0];
                            $user_id = $entity[0][1];
                            $where = "(entity_id = '$user_id') and (group_id = '$group_id')";
                            $iscondexist = true;
                            break;
                        case (string)Warecorp_User_Addressbook_eType::FRIEND:
                            preg_match_all('/[0-9]+/', $cid, $entity, PREG_PATTERN_ORDER);                            
                            if (empty($entity[0][0]) || empty($entity[0][1])) continue;
                            $owner_id = $entity[0][0];
                            $user_id = $entity[0][1];
                            $where = "(entity_id = '$user_id')";
                            $iscondexist = true;
                            break;
                        default:                            
                            $where="(1<>1)";
                            break;                            
                    }                    
                    $this->addWhereOr("((vai.classname = '".$cid_type[0]."') and $where)");
                } elseif(is_numeric($cid)) {
                    $iscondexist = true;                    
                    $this->addWhereOr("(contact_id = $cid)");
                }                
            }
            $this->addWhereOr('(2<>2))');
        }
        return $this;
    }
    
    /**
     * contact list id
     */
    public function getContactListId()
    {
    	return $this->_contactListId;
    }
    
    public function getOwnerId()
    {
    	return $this->_ownerId;
    }

    /**
     * return number of all items
     * @return int count
     */
    public function getCount($entityTypes = null)
    {
        $str = '';
    	if (!is_null($entityTypes) && is_array($entityTypes)) {
    		foreach ($entityTypes as $entityType) {
                if (Warecorp_User_Addressbook_eType::isIn($entityType)) {
                    if ($str == '')
            	       $str .= 'vai.classname = \'' . $entityType.'\'';
                    else $str .= 'OR vai.classname = \'' . $entityType.'\'';
                }
    		}
    	}

    	$sql = 'SELECT COUNT(vai2.contactlist_id) as amount FROM 
				(select * from view_addressbook__items as vai
    				WHERE ';
        $sql .= 'vai.contact_type = "member" ';
        if ( $this->getContactListId() ) $sql .= 'and vai.contactlist_id = '.$this->getContactListId().' ';
        if ( $this->getOwnerId() ) $sql .= 'and vai.owner_id = '.$this->getOwnerId().' ';
        if ($str != '') $sql .= 'and (' . $str . ') ';
        if ( $this->getWhere() ) $sql .= 'and '.$this->getWhere().' ';
        $sql .= 'group by vai.entity_id, vai.entity_type) as vai2';
    	$query = $this->_db->query($sql);
        $count = $query->fetch();
    	return $count['amount'];
    }

    /**
     * return list of all items
     * @return array of objects
     */
    public function getList($entityTypes = null)
    {        
    	$query = $this->_db->select();
    	if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'vai.contact_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'vai.contact_name' : $this->getAssocValue();
            $query->from(array('vai' => 'view_addressbook__items'), $fields);
    	} else {
    	    $query->from(array('vai' => 'view_addressbook__items'), array('vai.contact_id', 'vai.entity_id', 'vai.classname', 'vai.group_id'));
    	}
    	if (!is_null($entityTypes) && is_array($entityTypes)) {
    	    $str = '';
    		foreach ($entityTypes as $entityType) {
                if (Warecorp_User_Addressbook_eType::isIn($entityType)) {
                    if ($str == '')
            	       $str .= 'vai.classname = \'' . $entityType.'\'';
                    else $str .= 'OR vai.classname = \'' . $entityType.'\'';
                }
    		}
    		if ($str != '') $query->where('(' . $str . ')');
    	}
    	if ( $this->getWhere() ) $query->where($this->getWhere());
    	if ( $this->getContactListId() ) $query->where('vai.contactlist_id = ?', $this->getContactListId());
        if ( $this->getOwnerId() ) $query->where('vai.owner_id = ?', $this->getOwnerId());

        $query->where('vai.contact_type = ?', 'member');        
		$query->group(array('vai.entity_id', 'vai.entity_type'));
		      
        if ( $this->_order !== null ) {
            $query->order($this->_order);
        }   
		if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
        	$query->limitPage($this->getCurrentPage(), $this->getListSize());
    	}
        if ( $this->isAsAssoc() ) {       	
        	$items = $this->_db->fetchPairs($query);
        } else {
            $_items = $this->_db->fetchAll($query);
            $items = array();
	        foreach ( $_items as $_item ) { 
	        	if ($_item['classname'] == Warecorp_User_Addressbook_eType::GROUP_MEMBER) {
	        		$items[] = new Warecorp_User_Addressbook_GroupMember($_item['entity_id'], $_item['group_id']);
	        	} elseif ($_item['classname'] == Warecorp_User_Addressbook_eType::FRIEND) {
                    $items[] = new Warecorp_User_Addressbook_Friend($this->getOwnerId(), $_item['entity_id']);
	        	}else {
	        		$items[] = Warecorp_User_Addressbook_Factory::loadById($_item['contact_id'],$_item['entity_id']);
	        	}
	        }
        }        
        return $items;
    }

    /**
     * return list of all emails
     * @return array of objects
     */
    public function getEmailsList()
    {        
        $query = $this->_db->select();
        $query->from(array('vai' => 'view_addressbook__items'), array('vai.contact_id', 'vai.email'));
        
        $query->where("(vai.classname = 'user' OR vai.classname = 'custom_user')");
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getContactListId() ) $query->where('vai.contactlist_id = ?', $this->getContactListId());
        if ( $this->getOwnerId() ) $query->where('vai.owner_id = ?', $this->getOwnerId());
        $query->where('vai.contact_type = ?', 'member');  

        if ( $this->_order !== null ) $query->order($this->_order);           
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) $query->limitPage($this->getCurrentPage(), $this->getListSize());

        $items = $this->_db->fetchPairs($query);
        return array_unique( $items );
    }
    
    public function getClassNameById($newVal)
    {
        $query = $this->_db->select();
        $query->from(array('vai' => 'view_addressbook__items'), 'vai.classname');
        $query->where('vai.contact_id = ?', $newVal);
        $result = $this->_db->fetchOne($query);
        return $result;
    }
    /**
     * mailing list id
     * 
     * @param newVal
     */
    public function setContactListId($newVal)
    {
    	$this->_contactListId = $newVal;
    }
    
    public static function getMainContactListId($owner_id)
    {
        $db = & Zend_Registry::get("DB");
        $query = $db->select();
        $query->from(array('zac' => 'zanby_addressbook__contactlists'), 'zac.id');
        $query->where('zac.ismain = ?', '1');
        $query->where('zac.owner_id = ?', $owner_id);
        return $db->fetchOne($query);
    }
    
    public function getAddressbookLetters($ownerId, $forAjax = false)
    {
        $select = $this->_db->select();

        $select->from(array('vai' => 'view_addressbook__items'),
        array('UPPER(SUBSTRING(contact_name, 1, 1)) AS "letter"',
        'COUNT(*) AS "count"'))
        ->where('owner_id = ?', $ownerId)
//        ->where('ismain = ?', 1)
        ->where('ORD(UPPER(SUBSTRING(contact_name, 1, 1))) BETWEEN 65 AND 90')
        ->group('letter')
        ->order('letter');
        if ( $this->getContactListId() ) $select->where('vai.contactlist_id = ?', $this->getContactListId());
        if ($forAjax) $select->where('((classname = \'user\') OR (classname = \'custom_user\') OR (classname = \'groupmember\') OR (classname = \'friend\'))');
        $result = $this->_db->fetchPairs($select);
        return $result;
    }

    public function setOrder($order, $direction = 'asc')
    {
        if( !in_array(strtolower($direction), array('asc', 'desc')) ) $direction = 'asc';
        switch ($order) {
            case "firstname": $orderBy = "contact_firstname"; break;
            case "lastname": $orderBy = "contact_lastname"; break;
            case "email": $orderBy = "email"; break;
            case "creation_date": $orderBy = "creation_date"; break;
            default: $orderBy = "contact_firstname";
        }
        $this->_order = $orderBy . ' ' . $direction;
        return $this;
    }
    
//    public function getOrder()
//    {
//        switch ($this->_order) {
//            case "contact_firstname": $orderBy = "firstname"; break;
//            case "contact_lastname": $orderBy = "lastname"; break;
//            case "email": $orderBy = "email"; break;
//            case "creation_date": $orderBy = "creation_date"; break;
//            default: $orderBy = false;
//        }
//        return $orderBy;
//    }
}
