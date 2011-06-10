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
 * @package    Warecorp_Data
 * @copyright  Copyright (c) 2006, 2008
 */

/**
 * Base class for all table entities.
 *
 */
class BaseWarecorp_Data_Entity
{

    /**
     * Db connection object.
     * @var object
     */
    public $_db;

    /**
     * The name of table which from we get data.
     * @var string
     */
    public $tableName = null;

    /**
     * fale if object was not loaded
     * @var boolean
     */
    public $isExist = false;

    /**
     * primary key name
     * @var string
     */
    public $pkColName = "id";

    /**
     * Array of table's_fields => class_properties.
     * @var array
     */
    protected $record = array();
    protected $init_data = array();

    private $_changesTable = 'zanby_entity__changes';
    
    /**
     * кэш для хранения загруженных объектов в пределах одного обращения к серверу
     * @var unknown_type
     */
    private $cache;
    private $rank = null;
    private $rankCnt = null;
    public $EntityTypeId;
    public $weight;
    private $isChanged = false;

    /**
     * Force usage of SQL-request for tag list retrieval
     * @var boolean
     * @author Konstantin Stepanov
     */
    private $forceDbTags = false;

		/**
		 * Configuration object contains some paths and urls for current
		 * theme.
		 * @author Aleksei Gusev
		 */
    static private $AppTheme = null;
    
    static public function getAppTheme() {
        if (self::$AppTheme === null){
            if ( Zend_Registry::isRegistered( 'AppTheme')) {
								self::$AppTheme = &Zend_Registry::get( 'AppTheme');
            } else {
								self::$AppTheme      				 = new stdClass();
								self::$AppTheme->images      = UPLOAD_BASE_URL;
								self::$AppTheme->images_path = UPLOAD_BASE_PATH;
            }
        }
        return self::$AppTheme;
    }
		
    /**
     * Constructor.
     * @param string $tableName
     * @author Kostikov
     */
     
    public function __construct($tableName = false, $fields = null)
    {
        $this->_db = & Zend_Registry::get("DB");
        if ($tableName) $this->tableName = $tableName;
        if ($fields)    $this->record = $fields;

        //  @todo Why we have two identical chunks of code in two
        //          place? (c) Aleksei Gusev
        //  @see Warecorp_Data_Tag->getTagEntitiesAsObj() там тоже эти типы и идешки

        if ($this instanceof Warecorp_User)                     {  $this->EntityTypeId = 1;    $this->EntityTypeName = 'user';                }
        if ($this instanceof Warecorp_Group_Base)               {  $this->EntityTypeId = 2;    $this->EntityTypeName = 'group';               }
        if ($this instanceof Warecorp_Group_Simple)             {  $this->EntityTypeId = 2;    $this->EntityTypeName = 'group';               }
        if ($this instanceof Warecorp_Group_Family)             {  $this->EntityTypeId = 2;    $this->EntityTypeName = 'group';               }
        if ($this instanceof Warecorp_Photo_Gallery_Abstract)   {  $this->EntityTypeId = 3;    $this->EntityTypeName = 'gallery';             }
        if ($this instanceof Warecorp_Photo_Abstract)           {  $this->EntityTypeId = 4;    $this->EntityTypeName = 'photo';               }
        if ($this instanceof Warecorp_Document_Item)            {  $this->EntityTypeId = 5;    $this->EntityTypeName = 'document';            }
        if ($this instanceof Warecorp_ICal_Event)               {  $this->EntityTypeId = 6;    $this->EntityTypeName = 'event';               }
        if ($this instanceof Warecorp_Data_Comment)             {  $this->EntityTypeId = 9;    $this->EntityTypeName = 'comment';             }
        if ($this instanceof Warecorp_Data_Tag)                 {  $this->EntityTypeId = 17;   $this->EntityTypeName = 'tag';                 }
        if ($this instanceof Warecorp_Mail_Template)            {  $this->EntityTypeId = 15;   $this->EntityTypeName = 'template';            }
        if ($this instanceof Warecorp_List_Item)                {  $this->EntityTypeId = 20;   $this->EntityTypeName = 'list';                }
        if ($this instanceof Warecorp_List_Record)              {  $this->EntityTypeId = 21;   $this->EntityTypeName = 'lists_record';        }
        if ($this instanceof Warecorp_Message)                  {  $this->EntityTypeId = 33;   $this->EntityTypeName = 'account_message';     }
        if ($this instanceof Warecorp_Venue_Item)               {  $this->EntityTypeId = 34;   $this->EntityTypeName = 'venue';               }
        if ($this instanceof Warecorp_Video_Gallery_Abstract)   {  $this->EntityTypeId = 36;   $this->EntityTypeName = 'videogallery';        }
        if ($this instanceof Warecorp_Video_Abstract)           {  $this->EntityTypeId = 37;   $this->EntityTypeName = 'video';               }

        $cfgWeight = Warecorp_Config_Loader::getInstance()->getCoreConfig('cfg.weight.xml');
        if ( isset($this->EntityTypeName) && ($_type = $this->EntityTypeName) && isset($cfgWeight->$_type)) {
            $this->weight = $cfgWeight->$_type;
        }
    }
       
    /**
     * Добавление свойства в класс
     * @param string $colName
     * @param string $propertyName
     * @author Kostikov
     */
    public function addField($colName, $propertyName = false)
    {
        $this->record[$colName] = $propertyName ? $propertyName : $colName;

        if ($propertyName) $this->setProperty($propertyName, null);
        else $this->setProperty($colName, null);
    }

    /**
     * set value for property
     * @param string $propertyName
     * @param string $properyValue
     * @return void
     * @author Artem Sukharev
     */
    private function setProperty($propertyName, $properyValue)
    {
        if ( method_exists($this, 'set' . ucfirst($propertyName)) ) {
    		$method = 'set' . ucfirst($propertyName);
    		$this->$method($properyValue);
    	} else {
    		if ( property_exists($this, $propertyName) ) {
                $this->$propertyName = $properyValue;
    		}
    	}
        if (!array_key_exists($propertyName, $this->init_data))
            $this->init_data[$propertyName] = (string)$this->getProperty($propertyName);
        else $this->isChanged = true;
    }

    /**
     * get value for property
     * @param string $propertyName
     * @return mixed
     * @author Artem Sukharev
     */
    protected function getProperty($propertyName)
    {
        if ( method_exists($this, 'get' . ucfirst($propertyName)) ) {
            $method = 'get' . ucfirst($propertyName);
            if (!empty($this->EntityTypeName) && $this->EntityTypeName == 'user' && $propertyName == 'membershipPlan') {
                return $this->$method(true);
            } else {
                return $this->$method();
            }
        } else {
        	if ( property_exists($this, $propertyName) ) {
                return $this->$propertyName;
        	} else {
                return null;
        	}
        }
    }

    /**
     * return value of primary key for object
     * @return mixed
     * @author Artem Sukharev
     */
    public function getPKPropertyValue()
    {
        $pkPropertyName     = $this->record[$this->pkColName];
        return $this->getProperty($pkPropertyName);
    }

    /**
     * Alias to loadByPk
     * @param int|array $value
     * @author Kostikov
     */
    public function load($value = null)
    {
        if ( is_array($value) && !empty($value) ) {
            foreach ($this->record as $colName => $field) {
                if ( isset($value[$colName]) ) $this->setProperty($field, $value[$colName]);
                else $this->setProperty($field, null);
            }
            $this->isExist = true;
            return true;
        }
        return $this->loadByPk($value);
    }
    
    
    /**
     * load data to object by primary key
     * @param mixed $keyValue
     * @author Kostikov
     */
    public function loadByPk($pkValue)
    {
        if ( null === $pkValue ) return false;


        $memcache = Warecorp_Cache::getMemCache();

        $classname = get_class($this);

        $row = ($this->pkColName == 'id') ? $memcache->load($classname.$pkValue) : null;

        //There is no cache. Load it from DB
        if (!$row) {
            $sql = $this->_db->select()->from($this->tableName, '*')->where($this->pkColName.'=?', $pkValue)->limit(1);
            $row = $this->_db->fetchRow($sql);
            //Save it to memcache
            if ($row && isset($row['id'])) $memcache->save($row, $classname.$row['id'], array(), Warecorp_Cache::LIFETIME_30DAYS);
        }
        if ($row) {
            foreach ($this->record as $colName => $field) {
            	if ( isset($row[$colName]) ) $this->setProperty($field, $row[$colName]);
            	else $this->setProperty($field, null);
            }
            $this->isExist = true;
            return true;
        }
        return false;
    }

    /**
     * load data to object by sql
     * @param string $query
     * @author Kostikov
     */
    public function loadBySql($query)
    {         
        $row = $this->_db->fetchRow($query);
        if ($row) {
            foreach ($this->record as $colName => $field) $this->setProperty($field, $row[$colName]);
            $this->isExist = true;
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
     * logging entity type and entity_id to zenby_entity__changes table
     * @author Pianko
     */
    protected function setLog( $entityType, $entityId, $isDeleted = false )
	{
        if (WITH_SPHINX){
            if ($entityType != null && $entityId != null ) {
				$this->_db->query("insert into {$this->_changesTable} (entity_type, entity_id, is_deleted) values (?, ?, ?) on duplicate key update is_deleted = values(is_deleted)", array( $entityType, $entityId, $isDeleted? 1: 0 ));
			}
        }
	}
    
	protected function getRecordOwnerId()
    {
    	try {
	        if ($this instanceof Warecorp_Photo_Abstract) 			{  return $this->getGallery()->getOwnerId();   }        
	        if ($this instanceof Warecorp_List_Record)              {  return $this->getList()->getOwnerId();      }
	        if ($this instanceof Warecorp_Video_Abstract)           {  return $this->getGallery()->getOwnerId();   }
	    	return $this->getProperty('ownerId');
    	}
    	catch (Exception $ex)
    	{
    		return null;
    	}
    }
	
    protected function getRecordOwnerType()
    {
    	try {
	    	if ($this instanceof Warecorp_Photo_Abstract) 			{  return $this->getGallery()->getOwnerType();   }        
	        if ($this instanceof Warecorp_List_Record)              {  return $this->getList()->getOwnerType();      }
	        if ($this instanceof Warecorp_Video_Abstract)           {  return $this->getGallery()->getOwnerType();   }
	    	return $this->getProperty('ownerType');
    	}
    	catch (Exception $ex){
    		return null;
    	}
    }
    
    /**
     * @desc Clears memcache instance for current object and all related objects.
     * @return void
     */
    public function clearMemcache() {
        $memcache = Warecorp_Cache::getMemcache();
        $classname = get_class($this);
        $memcache->remove($classname.$this->getId());
    }

    
    /**
     * Сохранение объекта в базе данных
     * @todo в массив для апдейта может передать только изменённые поля, сохранив первоначальные в $this->load()
     * @author Kostikov
     */
    public function save()
    {
        $debug = true;
        $prop  = array();

        foreach ( $this->record as $colName => $propertyName ) {
            if ( $propertyName != $this->pkColName ) {
                $prop[$colName] = $this->getProperty($propertyName);
                if ( $prop[$colName] === NULL )
                    $prop[$colName] = new Zend_Db_Expr('NULL');
            }
        }

        $pkPropertyName     = $this->record[$this->pkColName];      

        if ( $this->getPKPropertyValue() ) {
        	// update record 
        	
            $result = $this->_db->update(
                $this->tableName, $prop,
                $this->_db->quoteInto($this->pkColName . ' = ?', $this->getPKPropertyValue())
            );
        } else {
        	// add new record, return last id and set to object
            $result = $this->_db->insert($this->tableName, $prop);
            $this->setProperty($pkPropertyName, $this->_db->lastInsertId());          
        }

        if (WITH_SPHINX){
            //insert into log table entity_type and entity_id of modified record
            $this->setLog($this->EntityTypeId, $this->getPKPropertyValue(), false);
            //insert into log table owner_type and owner_id of modified record
            if ( $this->getRecordOwnerId() != null  &&  $this->getRecordOwnerType() != null )
		    {
			    $this->setLog( (($this->getRecordOwnerType() == 'group')?2:1), $this->getRecordOwnerId(), false);
		    }
        }
        
        $this->clearMemcache();

        if (!$debug) {
            if ( $this->getPKPropertyValue() && !empty($this->EntityTypeId) ) {
                $rels = Warecorp_Data_TagRelation::getRelationsByEntity($this->getPKPropertyValue(), $this->EntityTypeId, 'system');
                if ( sizeof($rels) != 0 ) {
                    foreach ($rels as &$rel) $rel->delete();
                }
            }
        }

		if (!defined("WITH_SPHINX_TAGS") || !WITH_SPHINX_TAGS)
		{
			$result = $this->buildSystemTags($result);
		}

        return $result;
    }

	protected function buildSystemTags($result)
	{
        $debug = true;
        if ( !empty( $this->weight ) )
		{
            $_regExEncoding = mb_regex_encoding();
            $_mbInEncoding  = mb_internal_encoding();
            mb_regex_encoding('UTF-8');
            mb_internal_encoding('UTF-8');

            if (!$debug) {
                foreach( $this->weight as $_key => $weight ) {
                    if( isset($this->record[$_key]) ) {
                        if ( isset($weight->object) && isset($weight->object_property) ) {
                            $object     = $weight->object;
                            $key        = $this->record[$_key];
                            $object     = new $object($this->getProperty($key));
                            $property   = $weight->object_property;
                            $tagsString = trim($object->$property);
                        } else {
                            $key        = $this->record[$_key];
                            $tagsString = trim($this->getProperty($key));
                        }

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
                $i=1; $arrayOfNewTagsAsString = array(); $arrayOfNewTags = array(); $namesOfTags = array();
                foreach( $this->weight as $_key => $weight ) {
                    $tagsString = "";
                    if( isset($this->record[$_key]) ) {
                        if ( isset($weight->object) && isset($weight->object_property) ) {
                            $object     = $weight->object;
                            $key        = $this->record[$_key];
                            $object     = new $object($this->getProperty($key));
                            $property   = $weight->object_property;
                            $tagsString = trim($object->$property); 
                        } else {
                            $key        = $this->record[$_key];
                            $initString = isset($this->init_data[$key])?trim($this->init_data[$key]):'';
                            $tagsString = trim($this->getProperty($key));
                            if (!array_key_exists($key, $this->init_data) || (string)$tagsString != (string)$initString) {
                                $this->isChanged = true;
                            }
                        }

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
                                    if (Warecorp_Common_Utf8::getStrlen($tag) > 100) $tag = Warecorp_Common_Utf8::getSubstr($tag, 0, 100);
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

                if ($this->isChanged === false) {
                    mb_regex_encoding($_regExEncoding);
                    mb_internal_encoding($_mbInEncoding);                
                    return $result;
                }
                
                $arrayOfOldTagsAsString   = Warecorp_Data_TagRelation::getEntitysTagsAsString($this->EntityTypeId, $this->getPKPropertyValue(), 'system');
                $relationsToDelete   = array_keys($this->_array_diff($arrayOfOldTagsAsString, $arrayOfNewTagsAsString));
                $relationsToInsert   = $this->_array_diff($arrayOfNewTagsAsString, $arrayOfOldTagsAsString);
                if ((!empty($relationsToDelete)) || (!empty($relationsToInsert))) {
                    $tagsToInsertNames  = array_intersect_key($namesOfTags, $relationsToInsert);
                    $relationsToInsert   = array_intersect_key($arrayOfNewTags, $relationsToInsert);
                    $existedTagsNames = Warecorp_Data_Tag::getTagsByNamesAsAssoc($tagsToInsertNames);
                    $tagsToInsert = array_diff($tagsToInsertNames, array_values($existedTagsNames));
                    Warecorp_Data_Tag::insertTags($tagsToInsert);
                    $existedTagsNames = Warecorp_Data_Tag::getTagsByNamesAsAssoc($tagsToInsertNames);
                    $flipedExistedTagsNames = array_flip($existedTagsNames);
                    foreach ($relationsToInsert as $key=>&$value) {
                        if (!empty($flipedExistedTagsNames[$value['name']]))
                            $value['id'] = $flipedExistedTagsNames[$value['name']];
                    }
                    Warecorp_Data_TagRelation::deleteTagsRelations($relationsToDelete);                  
                    Warecorp_Data_TagRelation::insertTagsForEntity($relationsToInsert, $this->EntityTypeId, $this->getPKPropertyValue(), 'system');
                }
            }
            mb_regex_encoding($_regExEncoding);
            mb_internal_encoding($_mbInEncoding);

        }
		return $result;
	}

    /**
     * delete record from DB
     */
    public function delete()
    {
        if (WITH_SPHINX){
            //insert into log table entity_type and entity_id of modified record
            $this->setLog($this->EntityTypeId, $this->getPKPropertyValue(), true);
            //insert into log table owner_type and owner_id of modified record
            if ( $this->getRecordOwnerId() != null  &&  $this->getRecordOwnerType() != null )
		    {
			    $this->setLog( ($this->getRecordOwnerType() == 'group') ? 2:1, $this->getRecordOwnerId(), false);
		    }
        }
		
		$pkPropertyName = $this->record[$this->pkColName];
        
        
        $this->_db->delete($this->tableName, $this->_db->quoteInto($this->pkColName.' =? ', $this->getPKPropertyValue()));
        if ( $this->getPKPropertyValue() && !empty($this->EntityTypeId) ) {
            /**
             * delete tag relations if exists
             */
            $rels = Warecorp_Data_TagRelation::getRelationsByEntity($this->getPKPropertyValue(), $this->EntityTypeId);
            if ($rels) {
                foreach ($rels as &$rel) $rel->delete();
            }
            $rels = Warecorp_Data_TagRelation::getRelationsByEntity($this->getPKPropertyValue(), $this->EntityTypeId, 'system');
            if ($rels) {
                foreach ($rels as &$rel) $rel->delete();
            }
            
            /**
             * delete attachments if exists
             */
            $rels = Warecorp_Data_AttachmentRelation::getRelationsByEntity($this->getPKPropertyValue(), $this->EntityTypeId);
            if ($rels) {
                foreach ($rels as &$rel) $rel->delete();
            }

            /**
             * delete comments for entity
             */
            $this->_db->delete('zanby_users__comments',
                $this->_db->quoteInto('entity_type_id =? ', $this->EntityTypeId)." AND ".
                $this->_db->quoteInto('entity_id =? ', $this->getPKPropertyValue())
            );
            
            /**
             *  delete ranks for entity
             */        
            $this->_db->delete('zanby_entity__ranks',
                $this->_db->quoteInto('entity_type_id =? ', $this->EntityTypeId)." AND ".
                $this->_db->quoteInto('entity_id =? ', $this->getPKPropertyValue())
            );

            /**
             * Remove share to all Family's Groups for current Entity
             */
            $reflection = new ReflectionObject($this);
            if ( $reflection->hasMethod('getId') && $this->EntityTypeId ) {
                Warecorp_Share_Entity::removeShare(null, $this->getId(), $this->EntityTypeId, true);
            }
            elseif ( $this->getPKPropertyValue() && is_numeric($this->getPKPropertyValue()) && $this->EntityTypeId ) {
                Warecorp_Share_Entity::removeShare(null, $this->getPKPropertyValue(), $this->EntityTypeId, true);
            }
            unset($reflection);
        }
    }

    /**
     * сохранение текущего объекта в кэш объектов
     */
    public function cacheSave()
    {
        $cache[$this->tableName][$this->getPKPropertyValue()] = $this;
    }

    /**
     * получение из кэша текущего объекта
     */
    public function cacheLoad()
    {
        //return $this = $cache[$this->tableName][$this->getPKPropertyValue()];
    }

    /**
	 * убрать из кэша текущий объект
	 */
    public function cacheDestroy()
    {
        unset($cache[$this->tableName][$this->getPKPropertyValue()]);
    }

    /**
     * object tags features
     * @author Dmitry Kostikov
     */

    /**
     * Set $forceDbTags (sets to true by default)
     */
    public function setForceDbTags($value = true)
    {
	$this->forceDbTags = (bool)$value;
	return $this;
    }

    /**
     * Get tag list for the entity
     * @return array of Warecorp_Data_Tag
     * @author Artem Sukharev
     * FIXME очень долго работает
     */
    public function getTagsList()
    {
		if (!$this->forceDbTags && defined("WITH_SPHINX_TAGS") && WITH_SPHINX_TAGS)
		{
			$taglist = new Warecorp_List_Tags();
			$taglist->addFilter("entity_id", $this->getPKPropertyValue())
					->addFilter("entity_type", $this->EntityTypeId);
			$tags = $taglist->getObjList();
		} else {
			$sql = $this->_db->select()
				->from(array('t' => 'zanby_tags__dictionary'), 't.*')
				->join(array('r' => 'zanby_tags__relations'), 't.id = r.tag_id')
				->where('r.entity_id = ?', $this->getPKPropertyValue())
				->where('r.entity_type_id = ?', $this->EntityTypeId)
				->where('r.status = ?', 'user');
			$tags = $this->_db->fetchAll($sql);
			foreach($tags as &$tag) $tag = new Warecorp_Data_Tag($tag);
		}
        return $tags;
        
    }

    /**
	 * Enter description here...
	 * @param string $tags
	 * @return void
	 * @author Artem Sukharev 
     * @author Vitaly Targonsky 
	 */
    public function addTags($tagsString, $tagsStatus='user')
    {
        if ( !$this->getPKPropertyValue() ) return;

        $_regExEncoding = mb_regex_encoding();
        $_mbInEncoding  = mb_internal_encoding();
        mb_regex_encoding('UTF-8');
        mb_internal_encoding('UTF-8');
        
        $tagsString = trim($tagsString);
        preg_match_all('/"(?:[^"]){1,}"/mi', $tagsString, $tagsMultiArray);
        $tagsString     = preg_replace('/"(?:[^"]){1,}"/mi', '', $tagsString);
        $tagsString     = trim(mb_ereg_replace("[^[\w ]]", '', $tagsString));
        $tagsArray      = preg_split('/\s{1,}/mi', $tagsString);
        $tagsArray      = array_merge($tagsArray, $tagsMultiArray[0]);
        $tagsArray      = array_unique(array_map('mb_strtolower',$tagsArray)); // lowercase unique
        if ( sizeof($tagsArray) != 0 ) {
            foreach ( $tagsArray as $tag ) {
                $tag = trim(mb_ereg_replace("[^[\w ]]", '', $tag));
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
                    $RelObj->entityId       = $this->getPKPropertyValue();
                    if ($this->weight->tag) {
                        $RelObj->weightGroup    = $this->weight->tag->group;
                        $RelObj->weightUser     = $this->weight->tag->user;
                    }
                    $RelObj->status         = $tagsStatus; // user or system
                    $RelObj->save();
                }
            }
        }
        mb_regex_encoding($_regExEncoding);
        mb_internal_encoding($_mbInEncoding);
        
        if (sizeof($tagsMultiArray[0])) {
            $tagsString = implode(' ', $tagsMultiArray[0]);
            $tagsString = str_replace('"',' ', $tagsString);
            $this->addTags($tagsString, 'system');
        }
    }

    /**
     * Добавляет аттачи к ентити
     * @return void
     * @author Vitaly Targonsky
     */
    public function addAttachments()
    {
        foreach ( $_FILES as $_name => $_file ) {
            if ( $_file['tmp_name'] != '' && $_file !=0 ) {
                $file_name = tempnam(ATTACHMENT_DIR, '__');
                if ( Warecorp_File_Item::uploadFile($_file['tmp_name'], $file_name) ) {
                    $newAttach = new Warecorp_Data_AttachmentFile();
                    $newAttach->originalName   = $_file['name'];
                    $newAttach->mimeType       = $_file['type'];
                    $newAttach->save();
                    rename($file_name, ATTACHMENT_DIR.md5($newAttach->getPKPropertyValue()).'.file');
                    $newAttach->insertRelation($this->getPKPropertyValue(), $this->EntityTypeId);
                }
            }
        }
    }
    /**
     * Возвращает массив аттачей
     * @return array of Warecorp_Data_AttachmentFile
     * @author Vitaly Targonsky
     */
    public function getAttachments()
    {
        if ( $this->getPKPropertyValue() && isset($this->EntityTypeId)) {
            return Warecorp_Data_AttachmentFile::getAttachmentFilesByEntity($this->getPKPropertyValue(), $this->EntityTypeId);
        } else {
            return array();
        }
    }

    /**
     * Удаляет тег entity (удаляются только связи tag-entity, сам tag в dictionary остается)
     * @return boolean
     * @author Artem Sukharev
     */
    public function deleteTags()
    {
        if ( !$this->getPKPropertyValue() ) 
            return false;
        $rels = Warecorp_Data_TagRelation::getRelationsByEntity($this->getPKPropertyValue(), $this->EntityTypeId);
        if ( sizeof($rels) != 0 ) {
            foreach ($rels as &$rel) $rel->delete();
        }
        return true;
    }

    /**
     * Удаляет атачменты
     * @return void
     * @author Ivan Meleshko
     */
    public function deleteAttachments()
    {
        if ( !$this->getPKPropertyValue() ) return;
        $rels = Warecorp_Data_AttachmentRelation::getRelationsByEntity($this->getPKPropertyValue(), $this->EntityTypeId);
        if ( sizeof($rels) != 0 ) {
            foreach ($rels as &$rel) $rel->delete();
        }
    }

    /**
     * add user comment for entity
     *
     */
    public function addComment($message)
    {
        $user = Zend_Registry::get('User');
        $comment = new Warecorp_Data_Comment();
        $comment->userId        = $user->getId();
        $comment->entityTypeId  = $this->EntityTypeId;
        $comment->entityId      = $this->getPKPropertyValue();
        $comment->creationDate  = new Zend_Db_Expr('NOW()');
        $comment->content       = $message;
        $comment->save();

    }
    /**
     * add rank to entity, or replace if user already rank it
     */
    public function addRank($rank)
    {
        $user = Zend_Registry::get('User');
        $sql = $this->_db->select()
            ->from(array('zer' => 'zanby_entity__ranks'), new Zend_Db_Expr('COUNT(user_id)'))
            ->where('user_id = ?',  $user->getPKPropertyValue())
            ->where('entity_type_id = ?',  $this->EntityTypeId)
            ->where('entity_id = ?',  $this->getPKPropertyValue());
        if ($this->_db->fetchOne($sql)) {
            $result = $this->_db->update('zanby_entity__ranks', array('rank' => $rank),
            $this->_db->quoteInto('user_id = ?', $user->getPKPropertyValue())." AND ".
            $this->_db->quoteInto('entity_type_id = ?', $this->EntityTypeId)." AND ".
            $this->_db->quoteInto('entity_id = ?', $this->getPKPropertyValue()));
        } else {
            $result = $this->_db->insert('zanby_entity__ranks', array(
            'user_id'       => $user->getPKPropertyValue(),
            'entity_type_id'=> $this->EntityTypeId,
            'entity_id'     => $this->getPKPropertyValue(),
            'rank'          => $rank,
            ));
        }
        $rank = $this->_db->fetchOne($sql);
    }

    /**
     * Return curent rank of entity
     * @return int
     */
    public function getRank()
    {
        if ($this->rank === null) {
            $this->setRank();
        }
        return $this->rank;
    }
    public function getRankCnt()
    {
        if ($this->rankCnt === null) {
            $this->setRank();
        }
        return $this->rankCnt;
    }
    /**
     * Return curent rank of entity
     *
     * @return int
     */
    public function setRank()
    {
        $sql = $this->_db->select()
            ->from(array('ver' => 'view_entity__ranks'), array('ver.rank', 'ver.rank_cnt'))
            ->where("ver.entity_id = ".$this->getPKPropertyValue()." AND ver.entity_type_id = ".$this->EntityTypeId);
        $rank = $this->_db->fetchRow($sql);
        $this->rank = !empty($rank['rank']) ? $rank['rank'] : 0;
        $this->rankCnt = !empty($rank['rank_cnt']) ? floor($rank['rank_cnt']) : 0;
    }

    /**
     * get users comments for any item
     */
    public function getCommentsList()
    {
        $sql = $this->_db->select()
            ->from(array('uc' => 'zanby_users__comments'), array('uc.id', 'ua.login'))
            ->join(array('ua' => 'zanby_users__accounts'), 'uc.user_id=ua.id', array('user_id' => 'ua.id'))
            ->where("uc.entity_id = ".$this->getPKPropertyValue()." AND uc.entity_type_id = ".$this->EntityTypeId);
        $comments = $this->_db->fetchAll($sql);        
        foreach ($comments as &$comment){
            $c = new Warecorp_Data_Comment($comment['id']);            
            $user = new Warecorp_User('id', $comment['user_id']);
            //$c->user = $comment['login'];
            $c->user = $user->getLogin();
            $comment = $c;
        }
        return $comments;
    }

    /**
     * get users comments count for any item
     * @return int
     */
    public function getCommentsCount()
    {
        if (!$this->getPKPropertyValue()) return 0; //Komarovski
        
        $sql = $this->_db->select()
            ->from(array('uc' => 'zanby_users__comments'), array('count' => new Zend_Db_Expr('count(uc.id)')))
            ->join(array('ua' => 'zanby_users__accounts'), 'uc.user_id=ua.id', NULL)
            ->where("uc.entity_id = ".$this->getPKPropertyValue()." AND uc.entity_type_id = ".$this->EntityTypeId);
        return $this->_db->fetchOne($sql);
    }

    public function isPrivate()
    {
        return false;
    }

    public function addEntityAttachment($entity_object)
    {
    	$entityPkPropertyValue = null;
    	if ( $entity_object instanceof Warecorp_Data_Entity ) $entityPkPropertyValue = $entity_object->getPKPropertyValue();
    	elseif ( method_exists($entity_object, 'getId') ) {
            $method = 'getId';
            $entityPkPropertyValue = $entity_object->$method();
    	} elseif ( property_exists($entity_object, 'id') ) $entityPkPropertyValue = $entity_object->id;
    	else throw new Zend_Exception('Undefined Primary Key for object');

        $this->_db->insert('zanby_entity__attachments', array(
        'parent_entity_type'    => $this->EntityTypeId,
        'parent_entity_id'      => $this->getPKPropertyValue(),
        'child_entity_type'     => $entity_object->EntityTypeId,
        'child_entity_id'       => $entityPkPropertyValue
        ));
    }

    public function getEntityAttachments($emptyEntityObj)
    {
        $sql = $this->_db->select();
        $sql->from('zanby_entity__attachments', array('parent_entity_type', 'parent_entity_id', 'child_entity_type', 'child_entity_id'))
        ->where('parent_entity_type = ?', $this->EntityTypeId)
        ->where('parent_entity_id   = ?', $this->getPKPropertyValue())
        ->where('child_entity_type  = ?', $emptyEntityObj->EntityTypeId);

        $entity_attachments = $this->_db->fetchAll($sql);
        $newObj = array();
        foreach ($entity_attachments as $k => &$v) {
            $temp = clone $emptyEntityObj;
            $temp->__construct($v['child_entity_id']);
            if ($temp->getId())
            $newObj[$k] = $temp;
        }
        return $newObj;
    }
    /**
     * проверяет является ли $entityObj аттаччем к текущему ентити
     * @author Vitaly Targonsky    
     */
    public function isEntityAttachment($entityObj)
    {
        $sql = $this->_db->select();
        $sql->from('zanby_entity__attachments', array('parent_entity_type', 'parent_entity_id', 'child_entity_type', 'child_entity_id'))
        ->where('parent_entity_type = ?', $this->EntityTypeId)
        ->where('parent_entity_id   = ?', $this->getPKPropertyValue())
        ->where('child_entity_type  = ?', $entityObj->EntityTypeId)
        ->where('child_entity_id    = ?', $entityObj->getId());   //FIXME: не будет работать, если нет метода getId(); 
        
        return (boolean)$this->_db->fetchOne($sql); 
    }
    
    public function deleteEntityAttachments($emptyEntityObj)
    {
        $this->_db->delete('zanby_entity__attachments',
        $this->_db->quoteInto('parent_entity_type = ?', $this->EntityTypeId).
        $this->_db->quoteInto('AND parent_entity_id = ?', $this->getPKPropertyValue()).
        $this->_db->quoteInto('AND child_entity_type = ?', $emptyEntityObj->EntityTypeId));
    }
}
