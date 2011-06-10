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
 * @package    Warecorp_List
 * @copyright  Copyright (c) 2006
 */

/**
 *
 *
 */
class BaseWarecorp_List_Item extends Warecorp_Data_Entity implements Warecorp_Global_iSearchFields
{
    protected $_id;
    protected $_listType;
    protected $_title;
    protected $_description;
    protected $_ownerType;
    protected $_ownerId;
    protected $_creatorId;
    protected $_creationDate;
    protected $_updateDate;
    protected $_isPrivate;
    protected $_ranking;
    protected $_adding;
    protected $_systemWhoWillFor;

    private $_isWatched = null;
    private $_isShared  = null;
    private $_viewDate  = null;

    private $_listTypeName;

    private $_owner = null;
    private $_creator = null;

    private $_sharedGroups = null;
    private $_sharedUsers = null;
    private $_recordsCount = null;
    private $_volunteersCount = null;

    public $keepDates = NULL;

    public function getId()
    {
        return $this->_id;
    }
    public function getListType()
    {
        return $this->_listType;
    }
    public function getTitle()
    {
        return $this->_title;
    }
    public function getDescription()
    {
        return $this->_description;
    }
    public function getOwnerType()
    {
        return $this->_ownerType;
    }
    public function getOwnerId()
    {
        return $this->_ownerId;
    }
    public function getCreatorId()
    {
        return $this->_creatorId;
    }
    public function getCreationDate()
    {
        return $this->_creationDate;
    }
    public function getUpdateDate()
    {
        return $this->_updateDate;
    }
    public function getIsPrivate()
    {
        return $this->_isPrivate;
    }
    public function getRanking()
    {
        return $this->_ranking;
    }
    public function getAdding()
    {
        return $this->_adding;
    }
    public function getIsWatched()
    {
        return $this->_isWatched;
    }
    public function getIsShared()
    {
		return $this->_isShared;
    }
    public function getViewDate()
    {
        return $this->_viewDate;
    }
    public function setId($newVal)
    {
        $this->_id = $newVal;
        return $this;
    }
    public function setListType($newVal)
    {
        $this->_listType = $newVal;
        return $this;
    }
    public function setTitle($newVal)
    {
        $this->_title = $newVal;
        return $this;
    }
    public function setDescription($newVal)
    {
        $this->_description = $newVal;
        return $this;
    }
    public function setOwnerType($newVal)
    {
        $this->_ownerType = $newVal;
        return $this;
    }
    public function setOwnerId($newVal)
    {
        $this->_ownerId = $newVal;
        return $this;
    }
    public function setCreatorId($newVal)
    {
        $this->_creatorId = $newVal;
        return $this;
    }
    public function setCreationDate($newVal)
    {
        $this->_creationDate = $newVal;
        return $this;
    }
    public function setUpdateDate($newVal)
    {
        $this->_updateDate = $newVal;
        return $this;
    }
    public function setIsPrivate($newVal)
    {
        $this->_isPrivate = $newVal;
        return $this;
    }
    public function setRanking($newVal)
    {
        $this->_ranking = $newVal;
        return $this;
    }
    public function setAdding($newVal)
    {
        $this->_adding = $newVal;
        return $this;
    }
    public function setIsWatched($newVal)
    {
        $this->_isWatched = $newVal;
        return $this;
    }
    public function setIsShared($newVal)
    {
        $this->_isShared = $newVal;
        return $this;
    }
    public function setViewDate($newVal)
    {
        $this->_viewDate = $newVal;
        return $this;
    }
    /**
     * return true if list is system to store app events as who will list
     * @param string $http_context
     * @return boolean
     * @author Artem Sukharev
     */
    public function isSystemWhoWillFor($http_context)
    {
        return (boolean) $this->_systemWhoWillFor == $http_context;
    }

    public function setSystemWhoWillFor($http_context)
    {
        $this->_systemWhoWillFor = $http_context;
    }

    /**
     * Constructor.
     *
     */
	public function __construct($id = null)
	{

	    parent::__construct('zanby_lists__items', array(
	       'id' => '_id',
	       'list_type_id' => '_listType',
	       'title' => '_title',
	       'description' => '_description',
	       'owner_type' => '_ownerType',
	       'owner_id' => '_ownerId',
	       'creator_id' => '_creatorId',
	       'creation_date' => '_creationDate',
	       'update_date' => '_updateDate',
	       'private' => '_isPrivate',
	       'ranking' => '_ranking',
	       'adding' => '_adding',
           'system_who_will_for' => '_systemWhoWillFor'
	    ));

	    if ($id !== null){
	       $this->pkColName = 'id';
		   $this->loadByPk($id);
	    }
	}


	/**
	 * get list path
	 * @return void
	 * @author Komarovski
	 */
	public function getListPath()
    {
        if ($this->_ownerType == 'user')
        {
            $list_path = $this->getOwner()->getUserPath('listsview/listid/'.$this->_id);
        }
        else
        {
            $list_path = $this->getOwner()->getGroupPath('listsview/listid/'.$this->_id);
        }
	    return $list_path;
    }


	/**
	 * set owner
	 * @return void
	 */
    public function setOwner()
    {
	    $this->_owner = ( $this->_ownerType == 'user' )
	       ? new Warecorp_User('id', $this->_ownerId)
	       : Warecorp_Group_Factory::loadById($this->_ownerId);
    }
	/**
	 * return owner
	 * @return Warecorp_User or Warecorp_Group_Simple object
	 */
    public function getOwner()
    {
        if ( $this->_owner === null ) {
            $this->setOwner();
        }
        return $this->_owner;
    }
	/**
	 * set Creator for list
	 * @return void
	 */
    public function setCreator()
    {
	    $this->_creator = new Warecorp_User('id', $this->_creatorId);
    }
	/**
	 * return Creator
	 * @return Warecorp_User or Warecorp_Group_Simple object
	 */
    public function getCreator()
    {
        if ( $this->_creator === null ) {
            $this->setCreator();
        }
        return $this->_creator;
    }

    /**
     * return list type name
     * @param $type int
     * @return string
     */
    public function getListTypeName()
    {
        $select = $this->_db->select()
            ->from('zanby_lists__type', 'title')
            ->where('id = ?', ($this->_listType === NULL) ? new Zend_Db_Expr('NULL') : $this->_listType);

        return $this->_db->fetchOne($select);
    }

    /**
     * return list-type record name
     * @return string
     */
    public function getListRecordName()
    {
        $select = $this->_db->select()
            ->from('zanby_lists__type', 'record_name')
            ->where('id = ?', ($this->_listType === NULL) ? new Zend_Db_Expr('NULL') : $this->_listType);

        return $this->_db->fetchOne($select);
    }

    /**
     * return XSL form
     * @return string
     */
    public function getXslTitleExtra()
    {
        $select = $this->_db->select()
            ->from('zanby_lists__type', 'xsl_extra_title')
            ->where('id = ?', ($this->_listType === NULL) ? new Zend_Db_Expr('NULL') : $this->_listType);
        return DOMDocument::loadXML($this->_db->fetchOne($select));
    }

    /**
     * return XSL form
     * @return string
     */
    public function getXslForm()
    {
        $select = $this->_db->select()
            ->from('zanby_lists__type', 'xsl_form')
            ->where('id = ?', ($this->_listType === NULL) ? new Zend_Db_Expr('NULL') : $this->_listType);
        return DOMDocument::loadXML($this->_db->fetchOne($select));
    }
    /**
     * return XSL template for type
     * @return DOMDocument
     */
    public function getXslView()
    {
        $select = $this->_db->select()
            ->from('zanby_lists__type', 'xsl_view')
            ->where('id = ?', ($this->_listType === NULL) ? new Zend_Db_Expr('NULL') : $this->_listType);
        return DOMDocument::loadXML($this->_db->fetchOne($select));
    }
    public static function wordwrap30($str = "")
    {
    	return wordwrap($str, 30, "\n", true);
    }
    /**
     * Return "empty" XML for type "list"
     * @return DOMDocument
     */
    public function getXmlEmpty()
    {
        $select = $this->_db->select()
            ->from('zanby_lists__type', 'xml')
            ->where('id = ?', ($this->_listType === NULL) ? new Zend_Db_Expr('NULL') : $this->_listType);
        return DOMDocument::loadXML($this->_db->fetchOne($select));
    }
    /**
     * array to XML
     * @param $data array
     * @return DOMDocument
     */
    public function arrayToXml($data)
    {
        $result  = '<?xml version="1.0" encoding="utf-8"?>'."\n";
        $result .= "<fields>\n";

        foreach ($data as $key=>&$value) {
            $result .= "<{$key} value = \"".htmlspecialchars($value)."\" />\n";
        }
        $result .= "</fields>";

        return DOMDocument::loadXML($result);
    }
    /**
     * XML to array
     * @param $data string
     * @return array
     */
    public function xmlToArray($data)
    {
        $result = array();
        $xml = simplexml_load_string($data);

        foreach ($xml as $field) {
            $result[$field->getName()] = (string)$field['value'];
        }

        return $result;
    }
    /**
     * return sort config data
     *
     */
    public function getXmlSortConfig()
    {
        $select = $this->_db->select()
            ->from('zanby_lists__type', 'xml_sort_config')
            ->where('id = ?', ($this->_listType === NULL) ? new Zend_Db_Expr('NULL') : $this->_listType);
        $xml = $this->_db->fetchOne($select);
        $orders = array();
        if ($xml) {
            $xml = simplexml_load_string($xml);
            foreach ($xml->children() as $order) {
                $_key = (string)$order['key'];
                if (count($order->xmlfield) == 1) {
                    $orders[$_key]['xmlfields'] = array((string)$order->xmlfield['value']);
                } else {
                    foreach ($order->xmlfield as $xmlfield) {
                        $orders[$_key]['xmlfields'][] = (string)$xmlfield['value'];
                    }
                }
                $orders[$_key]['title'] = (string)$order->title['value'];
                $orders[$_key]['direction'] = (string)$order->direction['value'];
                //$orders[$_key] = $_order;
            }
        }
        return $orders;
    }
    /**
     * return sort config data for select
     *
     */
    public function getXmlSortConfigAssoc()
    {
        $orders = $this->getXmlSortConfig();
        if (count($orders)) {
            foreach ($orders as &$order) {
                $order = $order['title'];
            }
        }

        return $orders;
    }
    /**
	 * Check list exist
	 * @param int $id
	 * @return bool
	 */
    public static function isListExists($id)
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('zanby_lists__items', 'id')
            ->where('id = ?', $id);
        $result = $db->fetchOne($select);
        return (bool) $result;
    }
    /**
	 * Return all records of list, saved in DB.
	 * @param int $id
	 * @return bool
	 */
    public function getRecordsListAssoc()
    {
        $select = $this->_db->select();
        $select->from('zanby_lists__records', array('id', 'title'))
            ->where('list_id = ?', ( $this->_id === NULL ) ? new Zend_Db_Expr('NULL') : $this->_id);
        $result = $this->_db->fetchPairs($select);
        return $result;
    }
    /**
     * Return list of titles types
     *
     * @param string|array|null $nameFilter
     * @return array
     */
    public static function getListTypesListAssoc( $nameFilter = null )
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select()
            ->from('zanby_lists__type', array('id','title'));
        if ( !empty($nameFilter) )
            $select->where('title IN (?)', $nameFilter);
        return $db->fetchPairs($select);
    }

    /**
     * need or not fields Entry, Tag
     * @return int
     */
    public function needExtraFields()
    {
        $select = $this->_db->select();
        $select->from('zanby_lists__type', array('extra_fields'))
            ->where('id = ?', ($this->_listType === NULL) ? new Zend_Db_Expr('NULL') : $this->_listType);

        $result = $this->_db->fetchOne($select);
        return $result;
    }
    /**
     * Special view or not (for 'Who will...' lists )
     *
     * @return int
     */
    public function isSpecialView()
    {
        $select = $this->_db->select();
        $select->from('zanby_lists__type', array('special_view'))
            ->where('id = ?', ($this->_listType === NULL) ? new Zend_Db_Expr('NULL') : $this->_listType);
        $result = $this->_db->fetchOne($select);
        return $result;
    }

    /**
     * Return records list.
     *
     * @return array of Warecorp_List_Record
     */
    public function getRecordsList($order=null)
    {
        $record = new Warecorp_List_Record();
        $select = $this->_db->select();
        $select->from(array('zlr' => 'zanby_lists__records'), 'zlr.id')
            ->where('list_id = ?', ( $this->_id === NULL ) ? new Zend_Db_Expr('NULL') : $this->_id);
        if ($order !== null) {
            switch ($order) {
                case 'createdesc' :
                    $select->order('creation_date DESC');
                    break;
                case 'createasc' :
                    $select->order('creation_date ASC');
                    break;
                case 'commentsdesc' :
                    $select->joinLeft(array('zuc' => 'zanby_users__comments'), $this->_db->quoteInto('zuc.entity_id = zlr.id AND zuc.entity_type_id = ?', $record->EntityTypeId))
                           ->group('zlr.id')
                           ->order('COUNT(zuc.id) DESC');
                    break;
                case 'commentsasc' :
                    $select->joinLeft(array('zuc' => 'zanby_users__comments'), $this->_db->quoteInto('zuc.entity_id = zlr.id AND zuc.entity_type_id = ?', $record->EntityTypeId))
                           ->group('zlr.id')
                           ->order('COUNT(zuc.id) ASC');
                    break;
                case 'rankasc' :
                    $select->joinLeft(array('ver' => 'view_entity__ranks'), $this->_db->quoteInto('ver.entity_id = zlr.id AND ver.entity_type_id = ?', $record->EntityTypeId))
                           ->group('zlr.id')
                           ->order(array('ver.rank ASC', 'zlr.id'));
                    break;
                case 'rankdesc' :
                    $select->joinLeft(array('ver' => 'view_entity__ranks'), $this->_db->quoteInto('ver.entity_id = zlr.id AND ver.entity_type_id = ?', $record->EntityTypeId))
                           ->group('zlr.id')
                           ->order(array('ver.rank DESC', 'zlr.id'));
                    break;
                default:
                    $records = $this->_db->fetchCol($select);
                    $xmlSort = $this->getXmlSortConfig();
                    if (isset($xmlSort[$order]) && count($records)) {
                        foreach ($records as &$record){
                            $record = new Warecorp_List_Record($record);
                            $fields = $this->xmlToArray($record->getXml());
                            $sortvalue = "";

                            foreach ($xmlSort[$order]['xmlfields'] as $field) {
                                $sortvalue .= isset($fields[$field]) ? $fields[$field] : "";
                            }
                            $record->sortvalue = $sortvalue;
                        }
                        if ($xmlSort[$order]['direction'] == "asc") {
                            usort($records,'Warecorp_List_Item::recordsCmpAsc');
                        } else {
                            usort($records,'Warecorp_List_Item::recordsCmpDesc');
                        }
                        $order = "xmlfield";
                    }
                    break;
            }
        }
        if ($order !=="xmlfield") {
            $records = $this->_db->fetchCol($select);
            foreach ($records as &$record){
                $record = new Warecorp_List_Record($record);
            }
        }
        return $records;
    }

    /**
     * return Warecorp_List_Record related to event
     * @param integer $event_id
     * @return Warecorp_List_Record
     */
    public function getRecordByRelatedEvent($event_id)
    {
        $record = new Warecorp_List_Record();

        $select = $this->_db->select();
        $select->from('zanby_lists__records', 'id')
               ->where('related_event_id = ?', $event_id);

        $recordID = $this->_db->fetchOne($select);
        if (!$recordID) return NULL;

        return new Warecorp_List_Record($recordID);
    }

    /**
     * return ids of events
     * @return array of int
     */
    public function getEventIDFromRecordsWithEvent()
    {
        $select = $this->_db->select();
        $select->from('zanby_lists__records', 'related_event_id')
               ->where('related_event_id IS NOT NULL');

        return $this->_db->fetchCol($select);
    }

    public static function recordsCmpAsc($record1, $record2)
    {
        return strcasecmp($record1->sortvalue, $record2->sortvalue);
    }
    public static function recordsCmpDesc($record1, $record2)
    {
        return strcasecmp($record2->sortvalue, $record1->sortvalue);
    }
    /**
     * Validate data
     *
     * @param array $data
     * @return array
     */
	public function getErrors(&$data)
	{
	    $errors = array();
        $xml = simplexml_import_dom($this->getXmlEmpty());

        //
        $names_array = array('title' => "Title", 'artist' => "Artist", 'asin' => "ASIN #", 'itunes' => "iTunes", 'firstname' => "Author First Name", 'lastname' => "Last Name", 'isbn' => "ISBN #", 'item' => "Item");
        foreach ($xml as $key=>$field) {
        	$data['item_fields'][$key] = isset($data['item_fields'][$key]) ? trim($data['item_fields'][$key]) : null;
            if ($field->attributes()->required == 1 && empty($data['item_fields'][$key])) {
                $errors[$key] = 'Please fill in "'.$field->attributes()->field_name.'" field. ';
            }
            else {
                switch ($field->attributes()->type) {
                    case 'url' :
                        if (!preg_match('/^(http:\/\/|https:\/\/|ftp:\/\/)?([a-zA-Z0-9\-]{2,}\.)+[a-zA-Z0-9]{2,}(.*)$/', $data['item_fields'][$key], $matches)) {
                            $errors[$key] = 'Please provide a correct "'.$field->attributes()->field_name.'".';
                        } elseif (empty($matches[1])) {
                            $data['item_fields'][$key] = "http://".$data['item_fields'][$key];
                        }
                    break;
                }
            }

            if (array_key_exists($field->getName(), $names_array) && !empty($data['item_fields'][$key]) && strlen($data['item_fields'][$key]) > 200) {
                $errors[$key] = '"' . $names_array[$field->getName()] . '" field too long (max 200)';
            }
        }
	    return $errors;
	}
    /**
     * Delete list with records, and import history
     *
     */
    public function delete()
    {
        foreach ($this->getRecordsListAssoc() as $id=>$record) {
        	$editRecord = new Warecorp_List_Record($id);
        	$editRecord->delete();
        }
        $this->_db->delete('zanby_lists__imported',
        $this->_db->quoteInto('source_list_id=?', $this->_id).
        $this->_db->quoteInto('OR target_list_id=?', $this->_id));

        $this->_db->delete('zanby_lists__sharing',
        $this->_db->quoteInto('list_id=?', $this->_id));

        parent::delete();
    }
    /**
     * Take off Watch List
     *
     */
    public function offWatch()
    {
        $user = Zend_Registry::get('User');

        $this->_db->delete('zanby_lists__imported',
        $this->_db->quoteInto('source_list_id=?', $this->getId()).
        $this->_db->quoteInto('AND target_list_id=?', $this->getId()).
        $this->_db->quoteInto('AND import_type=?', 'watch').
        $this->_db->quoteInto('AND user_id=?', $user->getId()));
    }
    /**
     * save list
     *
     * @param int $source_list_id
     * @param string $import_type 'merge'|'new'|'watch'
     * @param int $user_id need only for watch type
     */
    public function save($source_list_id = null, $import_type = null, $user_id = null) {

        if ( $this->keepDates == NULL) {
            $this->_updateDate = new Zend_Db_Expr('NOW()');
        }
        parent::save();
        if ($source_list_id !== null && $import_type !== null) {
            $this->_db->insert('zanby_lists__imported',
                array(
                    'source_list_id' => $source_list_id,
                    'target_list_id' => $this->_id,
                    'user_id'        => $user_id,
                    'import_type'    => $import_type,
                    'import_date'    => new Zend_Db_Expr('NOW()'),
                    'view_date'      => new Zend_Db_Expr('NOW()'),
                )
            );
        }
    }

    /**
     * Check if list already shared to group or user
     * @param int $list_id
     * @param string $owner_type - 'user' | 'group'
     * @param int $owner_id
     * @return bool
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public static function isListShared($list_id, $owner_type, $owner_id) {
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from("view_lists__list", new Zend_Db_Expr("count(*)"))
        ->where('owner_type = ?', $owner_type)
        ->where('owner_id = ?', $owner_id)
        ->where('share = ?', 1)
        ->where('id = ?', $list_id);
        $res = $db->fetchOne($select);
        return (bool) $res;
    }
    /**
     * Share list to group or user
     * @param string $owner_type - 'user' | 'group'
     * @param int $owner_id
     * @param boolean $shareWithFamily
     * @return void
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function shareList($owner_type, $owner_id, $shareWithFamily = false)
    {
        if ( $shareWithFamily ) {
            if ( !Warecorp_Share_Entity::isShareExists($owner_id, $this->getId(), $this->EntityTypeId) ) {
                return Warecorp_Share_Entity::addShare($owner_id, $this->getId(), $this->EntityTypeId);
            }
        }
        elseif ( !$shareWithFamily && !Warecorp_List_Item::isListShared($this->_id, $owner_type, $owner_id) ) {
            $this->_db->insert('zanby_lists__sharing',
                array(
                    'owner_type' => $owner_type,
                    'owner_id' => $owner_id,
                    'list_id' => $this->_id
                )
            );
        }
    }
    /**
     * Unshare list from group or user
     * @param string $owner_type - 'user' | 'group'
     * @param int $owner_id
     * @return void
     * @author Artem Sukharev, Vitaly Targonsky
     * @todo replace this select with correct command from future versions of ZF
     */
    public function unshareList($owner_type, $owner_id, $shareWithFamily = false)
    {
        if ( $shareWithFamily ) {
            if ( Warecorp_Share_Entity::isShareExists($owner_id, $this->getId(), $this->EntityTypeId) ) {
                return Warecorp_Share_Entity::removeShare($owner_id, $this->getId(), $this->EntityTypeId, true);
            }
        } else {
                
            $this->_db->delete('zanby_lists__sharing',
                $this->_db->quoteInto('list_id = ? ', array($this->_id)).
                $this->_db->quoteInto('AND owner_id = ? ', array($owner_id)).
                $this->_db->quoteInto('AND owner_type = ? ', array($owner_type))
            );
            }
    }
    /**
     * Unshare list from all group and user
     * @return void
     */
    public function unshareAllFromList()
    {
        $this->_db->delete('zanby_lists__sharing',
        $this->_db->quoteInto('list_id = ?', $this->_id));
    }
    /**
     * Set shared groups for list
     * @return void
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function setSharedGroups()
    {
        $select = $this->_db->select();
        $select->from(array('zls' => 'zanby_lists__sharing'), array())
               ->join(array('zgi' => 'zanby_groups__items'), 'zls.owner_id = zgi.id', array('zgi.id', 'zgi.type'))
               ->where('zls.list_id = ?', $this->getId())
               ->where('zls.owner_type = ?', 'group');
        $groups = $this->_db->fetchPairs($select);
        foreach ( $groups as $groupId=>$groupType) $groups[$groupId] = Warecorp_Group_Factory::loadById($groupId, $groupType);
        $this->_sharedGroups = $groups;
    }
    /**
     * Get shared groups for list
     * @return array of Warecorp_Group_Simple
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function getSharedGroups()
    {
        if ( $this->_sharedGroups === null ) {
            $this->setSharedGroups();
        }
        return $this->_sharedGroups;
    }
    /**
     * Set shared users for list
     * @return void
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function setSharedUsers()
    {
        $select = $this->_db->select();
        $select->from('zanby_lists__sharing', 'owner_id')
        ->where('list_id = ?', $this->_id)
        ->where('owner_type = ?', 'user');
        $users = $this->_db->fetchCol($select);
        foreach ( $users as &$user) $user = new Warecorp_User('id', $user);
        $this->_sharedUsers = $users;
    }
    /**
     * Get shared users for list
     * @return array of Warecorp_Group_Simple
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function getSharedUsers()
    {
        if ( $this->_sharedUsers === null ) {
            $this->setSharedUsers();
        }
        return $this->_sharedUsers;
    }

    /**
     * Set records count
     * @return void
     * @author Vitaly Targonsky
     */
    public function setRecordsCount()
    {
        $select = $this->_db->select();
        $select->from('zanby_lists__records', new Zend_Db_Expr('COUNT(id)'))
               ->where('list_id = ?', ( $this->_id === NULL ) ? new Zend_Db_Expr('NULL') : $this->_id);

        $this->_recordsCount = $this->_db->fetchOne($select);
    }
    /**
     * Get shared users for list
     * @return int
     * @author Vitaly Targonsky
     */
    public function getRecordsCount()
    {
        if ( $this->_recordsCount === null ) {
            $this->setRecordsCount();
        }
        return $this->_recordsCount;

    }
    /**
     *
     */
    public function getVolunteersCount()
    {
        if ( $this->_volunteersCount === null ) {
            $this->setVolunteersCount();
        }
        return $this->_volunteersCount;
    }
    /**
     *
     */
    public function setVolunteersCount()
    {
        $select = $this->_db->select();
        $select->from(array('zli' => 'zanby_lists__items'), new Zend_Db_Expr('COUNT(zlv.id)'))
               ->join(array('zlr' => 'zanby_lists__records'), 'zlr.list_id=zli.id')
               ->join(array('zlv' => 'zanby_lists__volunteers'), 'zlv.record_id=zlr.id')
               ->where('list_id = ?', ( $this->_id === NULL ) ? new Zend_Db_Expr('NULL') : $this->_id);
        $this->_volunteersCount = $this->_db->fetchOne($select);
    }
    /**
     *
     */
    public function getLastImportTargetData()
    {
    	$user = Zend_Registry::get('User');
        $select = $this->_db->select()
                 ->from(array('zli' => 'zanby_lists__imported'), array('target_list_id', 'import_type', 'import_date', 'view_date'))
                 ->where('source_list_id = ?', ( $this->_id === NULL ) ? new Zend_Db_Expr('NULL') : $this->_id)
                 ->where('user_id = ?', $user->getId())
                 ->order('import_date DESC')
                 ->limit(1);
        return $this->_db->fetchRow($select);

    }
    /**
     *
     */
    public function updateViewDate()
    {
        $user = Zend_Registry::get('User');

        $result = $this->_db->update('zanby_lists__imported', array('view_date' => new Zend_Db_Expr('NOW()')),
                                  $this->_db->quoteInto('user_id = ?', $user->getId())." AND ".
                                  $this->_db->quoteInto('target_list_id = ?', $this->_id)." AND ".
                                  $this->_db->quoteInto('source_list_id = ?', $this->_id)." AND ".
                                  $this->_db->quoteInto('import_type = ?', 'watch')
                                 );
        return $result;
    }

    /**
    * return object
    * @return void object
    */
    public function entityObject()
    {
        return $this;
    }

    /**
    * return object id
    * @return int
    */
    public function entityObjectId()
    {
        return $this->getId();
    }

    /**
    * return object type. possible values: simple, family, blank string or null
    * @return string
    */
    public function entityObjectType()
    {
        return null;
    }

    /**
    * return owner type
    * possible values: group, user
    * @return string
    */
    public function entityOwnerType()
    {
        return $this->getOwnerType();
    }

    /**
    * return title for entity (like group name, username, photo or gallery title)
    * @return string
    */
    public function entityTitle()
    {
        return $this->getTitle();
    }

    /**
    * return headline for entity (like group headline, members first and last name, photo or gallery title,etc).
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityHeadline()
    {
        return $this->getTitle();
    }

    /**
    * return description for entity (group description, user intro, gallery or photo description, etc.).
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityDescription()
    {
        return $this->getDescription();
    }

    /**
    * return username of owner
    * @return string
    */
    public function entityAuthor()
    {
        return $this->getCreator()->getLogin();
    }

    /**
    * return user_id of entity owner
    * @return string
    */
    public function entityAuthorId()
    {
        return $this->getCreator()->getId();
    }

    /**
    * return picture URL (avatar, group picture, trumbnails, etc.)
    * @return int
    */
    public function entityPicture()
    {
        return null;
    }

    /**
    * return creation date for all elements
    * @return string
    */
    public function entityCreationDate()
    {
        return $this->getCreationDate();
    }

    /**
    * return update date for all elements
    * @return string
    */
    public function entityUpdateDate()
    {
        return $this->getCreationDate();
    }

    /**
    * items count (members, posts, child groups, etc.)
    * @return int
    */
    public function entityItemsCount()
    {
        return $this->getRecordsCount();
    }

    /**
    * get category for entity (event type, list type, group category, etc)
    * possible values: string
    * @return int
    */
    public function entityCategory()
    {
        return $this->getListTypeName();
    }

    /**
    * get category_id for entity (event type, list type, group category, etc)
    * possible values: int , null
    * @return int
    */
    public function entityCategoryId()
    {
        return null;
    }

    /**
    * get country for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCountry()
    {
        return "";
    }

    /**
    * get country_int for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCountryId()
    {
        return null;
    }


    /**
    * get city for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCity()
    {
        return "";
    }

    /**
    * get city_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCityId()
    {
        return null;
    }

    /**
    * get zip for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityZIP()
    {
        return "";
    }

    /**
    * get state for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityState()
    {
        return "";
    }

    /**
    * get state_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityStateId()
    {
        return null;
    }

    /**
    * path to video(video galleries)
    * possible values: string
    * @return int
    */
    public function entityVideo()
    {
        return "";
    }

    /**
    * comments count for entity
    * possible values: int
    * @return int
    */
    public function entityCommentsCount()
    {
        return $this->getCommentsCount();
    }

    public function entityURL()
    {
        return $this->getCreator()->getGlobalPath('listsview').'listid/'.$this->getId().'/';
    }
}
