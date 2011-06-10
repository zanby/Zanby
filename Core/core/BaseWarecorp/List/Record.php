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
class BaseWarecorp_List_Record extends Warecorp_Data_Entity
{
    protected $_id;
    protected $_listId;
    protected $_title;
    protected $_xml;
    protected $_entry;
    protected $_creatorId;
    protected $_creationDate;
    protected $_list;
    protected $_relatedEventId;
    

//    s $Owner = null;
    private $_creator = null;
    private $_volunteersCount = null;
    private $_isVolunteered = null;
    private $_titleExtraStr = null;
    private $_recordName = null;

    public $keepDates = null;
    /**
     * Constructor.
     *
     */
	public function __construct($id = null)
	{
	    parent::__construct('zanby_lists__records');

	    $this->addField('id', '_id');
	    $this->addField('list_id', '_listId');
	    $this->addField('title', '_title');
	    $this->addField('xml', '_xml');
	    $this->addField('entry', '_entry');
	    $this->addField('creator_id', '_creatorId');
	    $this->addField('creation_date', '_creationDate');
        $this->addField('related_event_id', '_relatedEventId');


	    if ($id !== null){
	       $this->pkColName = 'id';
		   $this->loadByPk($id);
	    }
	}
	
	public function getId()
    {
        return $this->_id;
    }
    
    public function getListId()
    {
        return $this->_listId;
    }

    public function getList()
    {
    	if ($this->_list === null ) $this->_list = new Warecorp_List_Item($this->getListId()); 
    	return $this->_list;  
    }
    
    public function getTitle()
    {
        /**
         * block is used for special who will list on zccf
         * @author Artem Sukharev
         */
        if ( $this->getRelatedEventId() ) {
            $objUser = Zend_Registry::get('User');
            $currentTimezone = ( null !== $objUser->getId() && null !== $objUser->getTimezone() ) ? $objUser->getTimezone() : 'UTC';

            $objEvent = new Warecorp_ICal_Event($this->getRelatedEventId());
            if ( $objEvent && $objEvent->getId() ) {
                $city_ = $objEvent->getEventVenue() ? ' in '.$objEvent->getEventVenue()->getCity()->name : '';
                $date_ = $objEvent->displayDate('email.invitation.date', $objUser, $currentTimezone);
                $time_ = $objEvent->displayDate('email.invitation.time', $objUser, $currentTimezone);
                return 'Facilitate event'.$city_.' on '.$date_.' at '.$time_.'?';                
            }
        }
        return $this->_title;
    }
    
    public function getXml()
    {
        return $this->_xml;
    }
    
    public function getEntry()
    {
        return $this->_entry;
    }
    
    public function getCreatorId()
    {
        return $this->_creatorId;
    }
    
    public function getCreationDate()
    {
        return $this->_creationDate;
    }
    
    
	public function setId($newVal)
    {
        $this->_id = $newVal;
        return $this;
    }
    
    public function setListId($newVal)
    {
        $this->_listId = $newVal;
        return $this;
    }
    
    public function setTitle($newVal)
    {
        $this->_title = $newVal;
        return $this;
    }
    
    public function setXml($newVal)
    {
        $this->_xml = $newVal;
        return $this;
    }
    
    public function setEntry($newVal)
    {
        $this->_entry = $newVal;
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
    
    public function getRelatedEventId()
    {
        return $this->_relatedEventId;
    }
    
    public function setRelatedEventId($event_id)
    {
        $this->_relatedEventId = $event_id;
        return $this;
    }

    /**
	 * Set record Creator
	 * @return void
	 */
    public function setCreator()
    {
	    $this->_creator = new Warecorp_User('id', $this->_creatorId);
    }
	/**
	 * Return record Creator
	 * @return Warecorp_User object
	 */
    public function getCreator()
    {
        if ( $this->_creator === null ) {
            $this->setCreator();
        }
        return $this->_creator;
    }
    /**
     * Save object in DB
     */
    public function save() 
    {
        parent::save();
        
        $debug = true;
        $list = new Warecorp_List_Item($this->_listId);
        if ( $this->keepDates == NULL) {
            $list->save();  // set new update date
        }
        $_oldEncoding = mb_regex_encoding();
        $_regExEncoding = mb_regex_encoding();
        $_mbInEncoding  = mb_internal_encoding();
        mb_regex_encoding('UTF-8');
        mb_internal_encoding('UTF-8');
                
        if (!empty($this->weight->xml_fields)) {
            $weight = $this->weight->xml_fields;
            if (!$debug) {
            foreach ($list->xmlToArray($this->_xml) as $value) {
                $tagsString = trim($value);
            
                $fullTagsArray  = preg_split('/[\s,]{1,}/mi', $tagsString);
                $tagsString     = preg_replace('/"(?:[^"]){1,}"/mi', '', $tagsString);
                $tagsString     = trim(mb_ereg_replace("[^[\w]]", ' ', $tagsString));
                $tagsArray      = preg_split('/\s{1,}/mi', $tagsString);
                $tagsArray      = array_merge($tagsArray, $fullTagsArray);
                $tagsArray      = array_unique(array_map('mb_strtolower',$tagsArray));
                
                if ( sizeof($tagsArray) != 0 ) {
                    foreach ( $tagsArray as &$tag ) {
                        $tag = trim(mb_ereg_replace("[^[\w]]", '', $tag));
                        $tag = trim(preg_replace('/\s{1,}/', ' ', $tag));
                        if ( $tag != "" && strlen($tag) >= 2) {
                            if (strlen($tag) > 100) $tag = substr($tag, 0, 100);
                            
                            if ( !($tag_id = Warecorp_Data_Tag::isTagExists('name', $tag)) ) {
                                $TagObj = new Warecorp_Data_Tag();
                                $TagObj->name = $tag;
                                $TagObj->save();
                                $tag_id = $TagObj->getPKPropertyValue();
                            }
                            
                            $RelObj = new Warecorp_Data_TagRelation();
                            $RelObj->tagId          = $tag_id;
                            $RelObj->entityTypeId   = $this->EntityTypeId;
                            $RelObj->entityId       = $this->getPKPropertyValue();
                            $RelObj->weightGroup    = $weight->group;
                            $RelObj->weightUser     = $weight->user;
                            $RelObj->status         = 'system';
                            
                            $RelObj->save();
                        }
                    }
                }
            }
        }
        } else {
            $i = 1; $arrayOfNewTags = array(); $namesOfTags = array();
            $tagsString = implode(' ', $list->xmlToArray($this->_xml));
            $tagsString = trim($tagsString);
        
            $fullTagsArray  = preg_split('/[\s,]{1,}/mi', $tagsString);
            $tagsString     = preg_replace('/"(?:[^"]){1,}"/mi', '', $tagsString);
            $tagsString     = trim(mb_ereg_replace("[^[\w]]", ' ', $tagsString));
            $tagsArray      = preg_split('/\s{1,}/mi', $tagsString);
            $tagsArray      = array_merge($tagsArray, $fullTagsArray);
            $tagsArray      = array_unique(array_map('mb_strtolower',$tagsArray));

            if ( sizeof($tagsArray) != 0 ) {
                foreach ( $tagsArray as &$tag ) {
                    $tag = trim(mb_ereg_replace("[^[\w]]", '', $tag));
                    $tag = trim(preg_replace('/\s{1,}/', ' ', $tag));
                    if ( $tag != "" && Warecorp_Common_Utf8::getStrlen($tag) >= 2) {
                        if (Warecorp_Common_Utf8::getStrlen($tag) > 100) $tag = Warecorp_Common_Utf8::getSubstr($tag, 0, 100);
                        $arrayOfNewTags[$i]['name'] = $tag;
                        $arrayOfNewTags[$i]['weight'] = $weight;
                        $namesOfTags[$i] = $tag;
                        $i++;
                    }
                }
                if (empty($arrayOfNewTags)) {
                    mb_regex_encoding($_regExEncoding);
                    mb_internal_encoding($_mbInEncoding);
                    return;
                }
                
                $existedTagsNames = Warecorp_Data_Tag::getTagsByNamesAsAssoc($namesOfTags);
                $tagsToInsert = array_diff($namesOfTags, array_values($existedTagsNames));
                Warecorp_Data_Tag::insertTags($tagsToInsert);
                $existedTagsNames = Warecorp_Data_Tag::getTagsByNamesAsAssoc($namesOfTags);
                $flipedExistedTagsNames = array_flip($existedTagsNames);
                foreach ($arrayOfNewTags as $key=>&$value) {
                    if (!empty($flipedExistedTagsNames[$value['name']]))
                        $value['id'] = $flipedExistedTagsNames[$value['name']];
                }
                Warecorp_Data_TagRelation::insertTagsForEntity($arrayOfNewTags, $this->EntityTypeId, $this->getPKPropertyValue(), 'system');
            }
        }
        mb_regex_encoding($_regExEncoding);
        mb_internal_encoding($_mbInEncoding);
    }
    /**
     * add user comment for entity
     *
     */
    public function addComment($message)
    {
        parent::addComment($message);
        $list = new Warecorp_List_Item($this->_listId);
        $list->save(); // set new update date
    }
    /**
     * delete record from db
     *
     */
    public function delete()
    {
        $list = new Warecorp_List_Item($this->_listId);
        $list->save(); // set new update date
        parent::delete(); 
    }
    
    /**
     * Return value xml field
     *
     * @param string $field_id - xml-field
     * @return string 
     */
    public function getXmlFieldValue($field_id = null)
    {
        $list = new Warecorp_List_Item($this->_listId);
        $_fields = $list->xmlToArray($this->_xml);
        $value = "";
        if (isset($_fields[$field_id])) $value = $_fields[$field_id];
        return $value; 
    }
    
    /**
     * Add Volunteer to current record
     *
     * @param string $comment 
     */
    public function addVolunteer($comment)
    {
        $user = Zend_Registry::get('User');
        
        $result = $this->_db->insert('zanby_lists__volunteers', 
            array('user_id'     => $user->getId(),
                  'record_id'   => $this->_id, 
                  'comment'     => $comment,
                 )
        );
        return $this->_db->lastInsertId();
    }
    /**
     * Delete Volunteer from current record
     *
     * @param int $id 
     * @param string $comment
     */
    public function deleteVolunteer($id)
    {
        $this->_db->delete('zanby_lists__volunteers', $this->_db->quoteInto('id=?', $id));
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
        $select->from('zanby_lists__volunteers', new Zend_Db_Expr('COUNT(id)'))
               ->where('record_id = ?', $this->_id);
        $this->_volunteersCount = $this->_db->fetchOne($select);
    }
    /**
     * 
     */
    public function getVolunteerUserId($id)
    {
        $select = $this->_db->select();
        $select->from('zanby_lists__volunteers', 'user_id')
               ->where('record_id = ?', $this->getId())
               ->where('id = ?', $id);
        return $this->_db->fetchOne($select);
    }
    /**
     *  is current user already volunteered
     */
    public function isUserVolunteer($user = null)
    {
    	if ($user === null) {
    		$user = Zend_Registry::get('User');
    	}
        
        if ($this->_isVolunteered === null) {
            $select = $this->_db->select();
            $select->from('zanby_lists__volunteers', new Zend_Db_Expr('COUNT(id)'))
                   ->where('record_id = ?', $this->_id)
                   ->where('user_id = ?', $user->getId());
            $this->_isVolunteered = (boolean)$this->_db->fetchOne($select);
        }
        return $this->_isVolunteered;
    }
    /**
     * 
     * @return array of Warecorp_User
     */
    public function getVolunteersList()
    {
        $select = $this->_db->select();
        $select->from('zanby_lists__volunteers', array('id', 'user_id', 'comment'))
               ->where('record_id = ?', $this->_id);
        $users = $this->_volunteersCount = $this->_db->fetchAll($select);
        
        foreach ($users as &$user) {
            $_comment=$user['comment'];
            $_volunteer_id=$user['id'];
            $user = new Warecorp_User('id', $user['user_id']);
            $user->setComment($_comment);
            $user->volunteerId = $_volunteer_id;
        }

        return $users;      
    }
    /**
     * 
     */
    public function getExtraTitleStr($listType='', $data =array())
    {
    	if ($this->getId() && $this->getListId()) {
            if ($this->_titleExtraStr === null) {
                $list = new Warecorp_List_Item($this->getListId());             
                $domXml = DOMDocument::loadXML($this->getXml());
                $XSLTProcessor = new XSLTProcessor();
                $XSLTProcessor->registerPHPFunctions();
                $XSLTProcessor->importStyleSheet($list->getXslTitleExtra());
                $this->_titleExtraStr = $XSLTProcessor->transformToXml($domXml);
            }
    	} elseif ($listType && $data) {
    		$this->_titleExtraStr = '';
            $list = new Warecorp_List_Item();
            $list->setListType($listType);
            if ($list->getListTypeName()) {
                $domXml = $list->arrayToXml($data);
                $XSLTProcessor = new XSLTProcessor();
                $XSLTProcessor->registerPHPFunctions();
                $XSLTProcessor->importStyleSheet($list->getXslTitleExtra());
                $this->_titleExtraStr = $XSLTProcessor->transformToXml($domXml);
            }
    	}
    	
        return $this->_titleExtraStr;
    }
    /**
     * return record name 
     *
     * @param int $listType
     * @return string
     */
    public function getRecordName($listType='')
    {
    	if ($this->_recordName === null) {
    		if ($this->getId() && $this->getListId()) {
    			$list = new Warecorp_List_Item($this->getListId());
    		} else {
    			$list = new Warecorp_List_Item();
    			$list->setListType($listType);
    		}
    		$this->_recordName = (string)$list->getListRecordName();
    	}
    	return $this->_recordName;
    }    
}
