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

class BaseWarecorp_ICal_Event_List_Tag
{
    private $DbConn;
    private $event;
    private $entityIdsFilter;
    
    //private $add;
    
    /**
     * data base field name used as key for returned pairs array
     */
    //protected $_pairsModeKey = 'category_id';
    
    /**
     * data base field name used as value for returned pairs array 
     */
    //protected $_pairsModeValue = 'category_id';
    
    /**
     * data base fields for assoc select
     */
    //protected $_assocFields = array('event_id', 'category_id');
    
    public $EntityTypeId = 6;
    
    public function setEntityIdsFilter($value)
    {
        $this->entityIdsFilter = $value;
    }
    
    public function getEntityIdsFilter()
    {
        if ( null !== $this->entityIdsFilter && (!is_array($this->entityIdsFilter) || sizeof($this->entityIdsFilter) == 0) ) return array(-1);
        return $this->entityIdsFilter;
    }
    
    /**
     * Constructor
     * @param Zend_Db_Table_Abstract $Connection - database connection object
     */
    public function __construct(Warecorp_ICal_Event $objEvent = null)
    {
        //parent::__construct();
        $this->DbConn = Zend_Registry::get('DB');
        if ( null !== $objEvent ) $this->setEvent($objEvent);
    }
    /**
     * 
     */
    public function setEvent(Warecorp_ICal_Event $newVal)
    {
        $this->event = $newVal;
        return $this;
    }
    /**
     * 
     */
    public function getEvent()
    {
        if ( null === $this->event ) throw new Warecorp_ICal_Exception('Event isn\'t set');
        return $this->event;
    }
    /**
     * return number of items
     */
    public function getCount()
    {
        throw new Warecorp_ICal_Exception('This method is not emplement now'); 
    }
    
    /**
     * return list of items    
     */
    public function getList()
    {
        $query = $this->DbConn->select();
        $query->from(array('t' => 'view_tags__dictionary'), 't.id');
        $query->joinLeft(array('r' => 'zanby_tags__relations'), 't.id = r.tag_id');
        $query->where('r.entity_id = ?', $this->getEvent()->getId());
        $query->where('r.entity_type_id = ?', $this->EntityTypeId);
        $query->where('r.status = ?', 'user');
        $tags = $this->DbConn->fetchCol($query);
        foreach($tags as &$tag) $tag = new Warecorp_Data_Tag($tag);
        return $tags;
    }
    
    /**
     * return list of items    
     */
    public function getAsString()
    {
        $strOut = array();
        $query = $this->DbConn->select();
        $query->from(array('t' => 'view_tags__dictionary'));
        $query->joinLeft(array('r' => 'zanby_tags__relations'), 't.id = r.tag_id');
        $query->where('r.entity_id = ?', $this->getEvent()->getId());
        $query->where('r.entity_type_id = ?', $this->EntityTypeId);
        $query->where('r.status = ?', 'user');
        $tags = $this->DbConn->fetchAll($query);
        if ( sizeof($tags) != 0 ) {
            foreach($tags as &$tag) {
                $tag = new Warecorp_Data_Tag($tag);
                $strOut[] = $tag->getPreparedTagName();
            }
        }
        if ( sizeof($strOut) != 0 ) return join(' ', $strOut);        
        return '';
    }
    
    /**
    * @desc 
    */
    public function getAllList()
    {
        $query = $this->DbConn->select();
        $query->from(array('t' => 'view_tags__dictionary'), array('t.id', 'cnt' => 'COUNT(t.id)'));
        $query->joinLeft(array('r' => 'zanby_tags__relations'), 't.id = r.tag_id');
        $query->join(array('ce' => 'calendar_events'), 'ce.event_id = r.entity_id');
        $query->where('r.entity_type_id = ?', $this->EntityTypeId);
        $query->where('r.status = ?', 'user');

        if ( null !== $this->getEntityIdsFilter() ) {
            $query->where('r.entity_id IN (?)', $this->getEntityIdsFilter());
        }

        $query->order('cnt DESC');
        $query->order('t.name ASC');
        $query->group('t.id');
        $tags = $this->DbConn->fetchAll($query);
        foreach($tags as &$tag) {
            $cnt = $tag['cnt'];
            $tag = new Warecorp_Data_Tag($tag['id']);
            $tag->currentCnt = $cnt;
        }
        return $tags;
    }
    
    /**
    * @desc 
    */
    public function addTags($tagsString, $tagsStatus='user')
    {
        if ( null === $this->getEvent()->getId() ) return;
        $debug = true;
        $_regExEncoding = mb_regex_encoding();
        $_mbInEncoding  = mb_internal_encoding();
        mb_regex_encoding('UTF-8');
        mb_internal_encoding('UTF-8');
        
        if (!$debug) {
            $tagsString = trim($tagsString);
            preg_match_all('/"(?:[^"]){1,}"/mi', $tagsString, $tagsMultiArray);
            $tagsString     = preg_replace('/"(?:[^"]){1,}"/mi', '', $tagsString);
            $tagsString     = trim(mb_ereg_replace("[^[\w ]]", '', $tagsString));
            $tagsArray      = preg_split('/\s{1,}/mi', $tagsString);
            $tagsArray      = array_merge($tagsArray, $tagsMultiArray[0]);
            $tagsArray      = array_unique(array_map('mb_strtolower',$tagsArray)); // lowercase unique
            if ( sizeof($tagsArray) != 0 ) {
                foreach ( $tagsArray as $tag ) {
                    $tag = trim(mb_ereg_replace("[^[\w]]", '', $tag));
                    $tag = trim(preg_replace('/\s{1,}/', ' ', $tag));
                    $len = $tagsStatus == 'user' ? 3 : 2;
                    if ( $tag != "" && Warecorp_Common_Utf8::getStrlen($tag) >= $len ) {
                        if ( !($tag_id = Warecorp_Data_Tag::isTagExists('name', $tag)) ) {
                            $TagObj = new Warecorp_Data_Tag();
                            $TagObj->name = $tag;
                            $TagObj->save();
                            $tag_id = $TagObj->getPKPropertyValue();
                        }
                        $RelObj = new Warecorp_Data_TagRelation();
                        $RelObj->tagId          = $tag_id;
                        $RelObj->entityTypeId   = $this->EntityTypeId;
                        $RelObj->entityId       = $this->getEvent()->getId();
                        if ($this->getEvent()->weight->tag) {
                            $RelObj->weightGroup    = $this->getEvent()->weight->tag->group;
                            $RelObj->weightUser     = $this->getEvent()->weight->tag->user;
                        }
                        $RelObj->status         = $tagsStatus; // user or system
                        $RelObj->save();
                    }
                }
            }
        } else {
            $i = 1; $arrayOfNewTags = array(); $namesOfTags = array();
            $weight = $this->getEvent()->weight->tag;
            $tagsString = trim($tagsString);
            preg_match_all('/"(?:[^"]){1,}"/mi', $tagsString, $tagsMultiArray);
            $tagsString     = preg_replace('/"(?:[^"]){1,}"/mi', '', $tagsString);
            $tagsString     = trim(mb_ereg_replace("[^[\w ]]", '', $tagsString));
            $tagsArray      = preg_split('/\s{1,}/mi', $tagsString);
            $tagsArray      = array_merge($tagsArray, $tagsMultiArray[0]);
            $tagsArray      = array_unique(array_map('mb_strtolower',$tagsArray)); // lowercase unique
            if (sizeof($tagsMultiArray[0])) {
                $tagsString     = implode(' ', $tagsMultiArray[0]);
                $tagsString     = str_replace('"',' ', $tagsString);
                $tagsString     = preg_replace('/"(?:[^"]){1,}"/mi', '', $tagsString);
                $tagsString     = trim(mb_ereg_replace("[^[\w ]]", '', $tagsString));
                $multiTagsArray = preg_split('/\s{1,}/mi', $tagsString);            
                $multiTagsArray = array_unique(array_map('mb_strtolower',$multiTagsArray)); // case insensitive unique                        
                foreach ( $multiTagsArray as &$tag ) {
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
                Warecorp_Data_TagRelation::insertTagsForEntity($arrayOfNewTags, $this->EntityTypeId, $this->getEvent()->getId(), 'system');                        
            }        
            if ( sizeof($tagsArray) != 0 ) {
                $arrayOfNewTags = array();
                $namesOfTags = array();
                $i = 1;
                foreach ( $tagsArray as &$tag ) {
                    $tag = trim(mb_ereg_replace("[^[\w\s]]", '', $tag));                
                    $tag = trim(preg_replace('/\s{1,}/', ' ', $tag));
                    if ( $tag != "" && Warecorp_Common_Utf8::getStrlen($tag) >= 3) {
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
                Warecorp_Data_TagRelation::insertTagsForEntity($arrayOfNewTags, $this->EntityTypeId, $this->getEvent()->getId(), 'user');
            }                
        }
        mb_regex_encoding($_regExEncoding);
        mb_internal_encoding($_mbInEncoding);
        
        if (!$debug) {
        if (sizeof($tagsMultiArray[0])) {
            $tagsString = implode(' ', $tagsMultiArray[0]);
            $tagsString = str_replace('"',' ', $tagsString);
            $this->addTags($tagsString, 'system');
        }
        }
    }
    
    private function _array_diff($array1, $array2)
    {
        $result = $array1;
        foreach ($array1 as $key1=>$value) {
            if ($key2 = array_search($value, $array2)) {
               unset($result[$key1]);
               unset($array2[$key2]);
               continue;
            }                
        }
        return $result;            
    }    
   
    /**
    * @desc 
    */
    public function buildSystemTags()
    {
        //$this->buildSystemTagsCheck();
        //return;
        
        $debug = true;
        if (!$debug) {
            $this->deleteSystemTags();
        }
        
        if ( !empty( $this->getEvent()->weight ) ) {
            $_regExEncoding = mb_regex_encoding();
            $_mbInEncoding  = mb_internal_encoding();
            mb_regex_encoding('UTF-8');
            mb_internal_encoding('UTF-8');
            if (!$debug) {
                foreach( $this->getEvent()->weight as $_key => $weight ) {
                    $keyMethod = "get".ucfirst($_key);
                    if( method_exists($this->getEvent(), $keyMethod) ) {
                        $tagsString = $this->getEvent()->$keyMethod();

                        $fullTagsArray  = preg_split('/[\s,]{1,}/mi', $tagsString);
                        $tagsString     = preg_replace('/"(?:[^"]){1,}"/mi', '', $tagsString);
                        $tagsString     = trim(mb_ereg_replace("[^[\w]]", ' ', $tagsString));
                        $tagsArray      = preg_split('/\s{1,}/mi', $tagsString);
                        $tagsArray      = array_merge($tagsArray, $fullTagsArray);
                        $tagsArray      = array_unique($tagsArray);

                        if ( sizeof($tagsArray) != 0 ) {
                            foreach ( $tagsArray as &$tag ) {
                                $tag = trim(preg_replace('/\s{1,}/', ' ', $tag));
                                if ( $tag != "" && Warecorp_Common_Utf8::getStrlen($tag) >= 2) {
                                    if (Warecorp_Common_Utf8::getStrlen($tag) > 100) $tag = Warecorp_Common_Utf8::getSubstr($tag, 0, 100);
                                    if ( !($tag_id = Warecorp_Data_Tag::isTagExists('name', $tag)) ) {
                                        $TagObj = new Warecorp_Data_Tag();
                                        $TagObj->name = $tag;
                                        $TagObj->save();
                                        $tag_id = $TagObj->getPKPropertyValue();
                                    }
                                    $RelObj = new Warecorp_Data_TagRelation();
                                    $RelObj->tagId          = $tag_id;
                                    $RelObj->entityTypeId   = $this->EntityTypeId;
                                    $RelObj->entityId       = $this->getEvent()->getId();
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
                $i = 1; 
                $arrayOfNewTagsAsString = array(); 
                $arrayOfNewTags         = array(); 
                $namesOfTags            = array();
                foreach( $this->getEvent()->weight as $_key => $weight ) {
                    $keyMethod = "get".ucfirst($_key);
                    $tagsString = "";
                    if( method_exists($this->getEvent(), $keyMethod) ) {
                        $tagsString = $this->getEvent()->$keyMethod();
 
                        if (empty($tagsString)) continue;
                        $fullTagsArray  = preg_split('/[\s,]{1,}/mi', $tagsString);
                        $tagsString     = preg_replace('/"(?:[^"]){1,}"/mi', '', $tagsString);
                        $tagsString     = trim(mb_ereg_replace("[^[\w]]", ' ', $tagsString));
                        $tagsArray      = preg_split('/\s{1,}/mi', $tagsString);
                        $tagsArray      = array_merge($tagsArray, $fullTagsArray);
                        $tagsArray      = array_intersect_key($tagsArray,array_unique(array_map('mb_strtolower',$tagsArray))); // case insensitive unique
                        if ( sizeof($tagsArray) != 0 ) {
                            foreach ( $tagsArray as &$tag ) {
                                $tag = trim(preg_replace('/\s{1,}/', ' ', $tag));
                                if ( $tag != "" && Warecorp_Common_Utf8::getStrlen($tag) >= 2) {
                                    if ( Warecorp_Common_Utf8::getStrlen($tag) > 100 ) $tag = Warecorp_Common_Utf8::getSubstr($tag, 0, 100);
                                    $arrayOfNewTagsAsString[$i] = $tag.'_'.$weight->user.'_'.$weight->group;
                                    $arrayOfNewTags[$i]['name'] = $tag;
                                    $arrayOfNewTags[$i]['weight'] = $weight;
                                    $namesOfTags[$i] = $tag;
                                    $i++;
                                }
                            }
                        }
                    }
                }
               
                $arrayOfOldTagsAsString     = Warecorp_Data_TagRelation::getEntitysTagsAsString($this->EntityTypeId, $this->getEvent()->getId(), 'system');
                $relationsToDelete          = array_keys($this->_array_diff($arrayOfOldTagsAsString, $arrayOfNewTagsAsString));
                $relationsToInsert          = $this->_array_diff($arrayOfNewTagsAsString, $arrayOfOldTagsAsString);
                if ( (!empty($relationsToDelete)) || (!empty($relationsToInsert)) ) {
                    $tagsToInsertNames      = array_intersect_key($namesOfTags, $relationsToInsert);
                    $relationsToInsert      = array_intersect_key($arrayOfNewTags, $relationsToInsert);
                    $existedTagsNames       = Warecorp_Data_Tag::getTagsByNamesAsAssoc($tagsToInsertNames);
                    $tagsToInsert           = array_diff($tagsToInsertNames, array_values($existedTagsNames));
                    Warecorp_Data_Tag::insertTags($tagsToInsert);
                    $existedTagsNames       = Warecorp_Data_Tag::getTagsByNamesAsAssoc($tagsToInsertNames);
                    $flipedExistedTagsNames = array_flip($existedTagsNames);
                    foreach ($relationsToInsert as $key=>&$value) {
                        if (!empty($flipedExistedTagsNames[mb_strtolower($value['name'],'UTF-8')]))
                            $value['id'] = $flipedExistedTagsNames[mb_strtolower($value['name'],'UTF-8')];
                    }
                    Warecorp_Data_TagRelation::deleteTagsRelations($relationsToDelete);                  
                    Warecorp_Data_TagRelation::insertTagsForEntity($relationsToInsert, $this->EntityTypeId, $this->getEvent()->getId(), 'system');
                }  
            }
            mb_regex_encoding($_regExEncoding);
            mb_internal_encoding($_mbInEncoding); 
        }
    }
    
    /**
    * @desc 
    */
    public function buildSystemTagsCheck()
    {
        $debug = true;
        if (!$debug) {
            $this->deleteSystemTags();
        }
        
        if ( !file_exists(DEBUG_LOG_DIR.'event_migration_log.txt') ) {
            $handle = fopen(DEBUG_LOG_DIR.'event_migration_log.txt', 'w');
            fclose($handle);
            chmod(DEBUG_LOG_DIR.'event_migration_log.txt', 0777);
        }
        $logMessage = '';
        $logMessage .= "Event\n";
        $logMessage .= "ID : ".$this->getEvent()->getId()."\n";
        $logMessage .= "Title : ".$this->getEvent()->getTitle()."\n";
        $logMessage .= "Descr : ".$this->getEvent()->getDescription()."\n";
        $logMessage .= "-----------------------------------------------\n";
        
        if ( !empty( $this->getEvent()->weight ) ) {
            $_regExEncoding = mb_regex_encoding();
            $_mbInEncoding  = mb_internal_encoding();
            mb_regex_encoding('UTF-8');
            mb_internal_encoding('UTF-8');
            if (!$debug) {
                foreach( $this->getEvent()->weight as $_key => $weight ) {
                    $keyMethod = "get".ucfirst($_key);
                    if( method_exists($this->getEvent(), $keyMethod) ) {
                        $tagsString = $this->getEvent()->$keyMethod();

                        $fullTagsArray  = preg_split('/[\s,]{1,}/mi', $tagsString);
                        $tagsString     = preg_replace('/"(?:[^"]){1,}"/mi', '', $tagsString);
                        $tagsString     = trim(mb_ereg_replace("[^[\w]]", ' ', $tagsString));
                        $tagsArray      = preg_split('/\s{1,}/mi', $tagsString);
                        $tagsArray      = array_merge($tagsArray, $fullTagsArray);
                        $tagsArray      = array_unique($tagsArray);

                        if ( sizeof($tagsArray) != 0 ) {
                            foreach ( $tagsArray as &$tag ) {
                                $tag = trim(preg_replace('/\s{1,}/', ' ', $tag));
                                if ( $tag != "" && Warecorp_Common_Utf8::getStrlen($tag) >= 2) {
                                    if (Warecorp_Common_Utf8::getStrlen($tag) > 100) $tag = Warecorp_Common_Utf8::getSubstr($tag, 0, 100);
                                    if ( !($tag_id = Warecorp_Data_Tag::isTagExists('name', $tag)) ) {
                                        $TagObj = new Warecorp_Data_Tag();
                                        $TagObj->name = $tag;
                                        $TagObj->save();
                                        $tag_id = $TagObj->getPKPropertyValue();
                                    }
                                    $RelObj = new Warecorp_Data_TagRelation();
                                    $RelObj->tagId          = $tag_id;
                                    $RelObj->entityTypeId   = $this->EntityTypeId;
                                    $RelObj->entityId       = $this->getEvent()->getId();
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
                $i = 1; 
                $arrayOfNewTagsAsString = array(); 
                $arrayOfNewTags         = array(); 
                $namesOfTags            = array();
                foreach( $this->getEvent()->weight as $_key => $weight ) {
                    $keyMethod = "get".ucfirst($_key);                
                    $tagsString = "";
                    if( method_exists($this->getEvent(), $keyMethod) ) {
                        $tagsString = $this->getEvent()->$keyMethod();
                        if (empty($tagsString)) continue;
                        $fullTagsArray  = preg_split('/[\s,]{1,}/mi', $tagsString);
                        $tagsString     = preg_replace('/"(?:[^"]){1,}"/mi', '', $tagsString);
                        $tagsString     = trim(mb_ereg_replace("[^[\w]]", ' ', $tagsString));
                        $tagsArray      = preg_split('/\s{1,}/mi', $tagsString);
                        $tagsArray      = array_merge($tagsArray, $fullTagsArray);

                        $logMessage .= "All Tags from '".$_key."' : \n";
                        $logMessage .= join(', ', $tagsArray)."\n";
                        $tagsArray      = array_intersect_key($tagsArray,array_unique(array_map('mb_strtolower',$tagsArray))); // case insensitive unique
                        $logMessage .= "All Unique Tags from '".$_key."' : \n";
                        $logMessage .= join(', ', $tagsArray)."\n";                        

                        if ( sizeof($tagsArray) != 0 ) {
                            foreach ( $tagsArray as &$tag ) {
                                $tag = trim(preg_replace('/\s{1,}/', ' ', $tag));
                                if ( $tag != "" && Warecorp_Common_Utf8::getStrlen($tag) >= 2) {
                                    if ( Warecorp_Common_Utf8::getStrlen($tag) > 100 ) $tag = Warecorp_Common_Utf8::getSubstr($tag, 0, 100);
                                    $arrayOfNewTagsAsString[$i] = $tag.'_'.$weight->user.'_'.$weight->group;
                                    $arrayOfNewTags[$i]['name'] = $tag;
                                    $arrayOfNewTags[$i]['weight'] = $weight;
                                    $namesOfTags[$i] = $tag;
                                    $i++;
                                }
                            }
                        }
                        $logMessage .= "\n";
                    }                     
                }
                
                $logMessage .= "-----------------------------------------------\n";
                $logMessage .= "New Tags As String (\$arrayOfNewTagsAsString) : \n";
                $logMessage .= join(', ', $arrayOfNewTagsAsString)."\n";                        
                $logMessage .= "New Tags As String (\$namesOfTags) : \n";
                $logMessage .= join(', ', $namesOfTags)."\n";                        

               
                $arrayOfOldTagsAsString     = Warecorp_Data_TagRelation::getEntitysTagsAsString($this->EntityTypeId, $this->getEvent()->getId(), 'system');
                $relationsToDelete          = array_keys($this->_array_diff($arrayOfOldTagsAsString, $arrayOfNewTagsAsString));
                $relationsToInsert          = $this->_array_diff($arrayOfNewTagsAsString, $arrayOfOldTagsAsString);
                
                $logMessage .= "Old Tags As String (\$arrayOfOldTagsAsString) : \n";
                $logMessage .= join(', ', $arrayOfOldTagsAsString)."\n";                        
                $logMessage .= "Relations To Delete (\$relationsToDelete) : \n";
                $logMessage .= join(', ', $relationsToDelete)."\n";                        
                $logMessage .= "Relations To Insert (\$relationsToInsert) : \n";
                $logMessage .= join(', ', $relationsToInsert)."\n";
                $logMessage .= "-----------------------------------------------\n";                        
                
                
                if ( (!empty($relationsToDelete)) || (!empty($relationsToInsert)) ) {
                    $tagsToInsertNames      = array_intersect_key($namesOfTags, $relationsToInsert);
                    $relationsToInsert      = array_intersect_key($arrayOfNewTags, $relationsToInsert);
                    $existedTagsNames       = Warecorp_Data_Tag::getTagsByNamesAsAssoc($tagsToInsertNames);
                    $tagsToInsert           = array_diff($tagsToInsertNames, array_values($existedTagsNames));
                    Warecorp_Data_Tag::insertTags($tagsToInsert);
                    $existedTagsNames       = Warecorp_Data_Tag::getTagsByNamesAsAssoc($tagsToInsertNames);
                    $flipedExistedTagsNames = array_flip($existedTagsNames);
                    foreach ($relationsToInsert as $key=>&$value) {
                        if (!empty($flipedExistedTagsNames[$value['name']]))
                            $value['id'] = $flipedExistedTagsNames[$value['name']];
                    }
                    Warecorp_Data_TagRelation::deleteTagsRelations($relationsToDelete);                  
                    $res = Warecorp_Data_TagRelation::insertTagsForEntity($relationsToInsert, $this->EntityTypeId, $this->getEvent()->getId(), 'system');
                    
                    $logMessage .= "Relations To Delete (\$relationsToDelete) : \n";
                    $logMessage .= join(', ', $relationsToDelete)."\n";                        
                    $logMessage .= "Relations To Insert (\$relationsToInsert) : \n";
                    $logMessage .= $res."\n";
                    $logMessage .= "-----------------------------------------------\n";                        
                }  
            }
            mb_regex_encoding($_regExEncoding);
            mb_internal_encoding($_mbInEncoding); 
        }
        
        $logMessage .= "\n\n***********************************************\n\n";
        $handle = fopen(DEBUG_LOG_DIR.'event_migration_log.txt', 'a');
        fwrite($handle, $logMessage);
        fclose($handle);
    }

    /**
    * @desc 
    */
    public function deleteTags()
    {
        if ( null === $this->getEvent()->getId() ) return;
        $rels = Warecorp_Data_TagRelation::getRelationsByEntity($this->getEvent()->getId(), $this->EntityTypeId);
        if ( sizeof($rels) != 0 ) {
            foreach ($rels as &$rel) $rel->delete();
        }
    }
    
    /**
    * @desc 
    */
    public function deleteSystemTags()
    {
        if ( null !== $this->getEvent()->getId() ) {
            $rels = Warecorp_Data_TagRelation::getRelationsByEntity($this->getEvent()->getId(), $this->EntityTypeId, 'system');
            if ( sizeof($rels) != 0 ) {
                foreach ($rels as &$rel) $rel->delete();
            }
        }
    }
    
//    public function add($categoryId)
//    {
//        $this->add[$categoryId] = $categoryId;
//    }
    
    /**
    * Данный метод не должен вызываться нигде, кроме как 
    * в методе Warecorp_ICal_Event::save()
    */
//    public function save()
//    {
//        if ( null != $this->add && sizeof($this->add) != 0 ) {
//            $this->deleteEventsAll();
//            foreach ( $this->add as $key => $value ) {
//                $objEventCategory = new Warecorp_ICal_Category();
//                $objEventCategory->setEventId($this->getEvent()->getId());
//                $objEventCategory->setCategoryId($value);
//                $objEventCategory->save();
//            }
//        }
//    }
//    
//    public function deleteEventsAll()
//    {
//        $where = $this->DbConn->quoteInto('event_id = ?', $this->getEvent()->getId());
//        $this->DbConn->delete('calendar_event_categories', $where);
//    }
}
