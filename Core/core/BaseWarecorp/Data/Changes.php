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
 * Base class for loging changes of entity.
 *
 */

class BaseWarecorp_Data_Changes
{
	/**
	* database object
	* @var object Zend_Db
	* @author Pianko
	*/
	private $_db;
		
	/**
	* path to indexer binary file
	* @var string
	* @author Pianko
	*/
	private $_indexerPath;
	
	/**
	* path to indexer config file
	* @var string
	* @author Pianko
	*/
	private $_configPath;
	
	/**
	* table with ids and types modifed records (log table)
	* @var string
	* @author Pianko
	*/
	private $_tableName 	= 'zanby_entity__changes';
	
	/**
	* static params of indexer
	* @var string
	* @author Pianko
	*/
	private $_staticParams 	= ' --quiet --rotate ';

	/**
	* delta index postfix
	* @var string
	* @author Pianko
	*/
	private $_deltaPostfix 	= 'delta';

	/**
	* temporary index postfix
	* @var string
	* @author Pianko
	*/
	private $_tmpPostfix 	= 'tmp';
	
	
	//array for full version
	private $_entityList 	= 	array (
									1 	=> "user",
									2 	=> "group",
									3 	=> "gallery",
									4 	=> "photo",
									5 	=> "document",
									6 	=> "event",
									20	=> "list",
									34 	=> "venue",
									37	=> "video",
								);
	

	/**
	* array with entity type ids and entity names ( [id] => name)
	* @var array 
	* @author Pianko
	*/
	//private $_entityList 	= 	array ( 2 	=> "group" );
	
    /**
    * additional postfix for index name. (getting value from indexer config file conf->index_postfix)
    * @var string
    * @author Pianko
    */
    private $_resourcePostfix     =     "";

	/**
	* class conctructor
	* @return void
	* @author Pianko
	*/
	public function __construct()
	{
		$this->_db = & Zend_Registry::get("DB");		
        $cfgIndexer = & Warecorp_Config_Loader::getInstance()->getCoreConfig('cfg.indexer.xml');
                
        if (isset($cfgIndexer->conf->config)) 	$this->_configPath 	= $cfgIndexer->conf->config;
		if (isset($cfgIndexer->conf->path))		$this->_indexerPath = $cfgIndexer->conf->path;
	}                               
	
	/**
	* check for existing data in log table 
	* @param int $entityType
	* @author Pianko
	*/
	public function isUpdateExist( $entityType = null )
	{		
		$sql = $this->_db->select()->from($this->_tableName, array('COUNT(*)'));
		if (($entityType !== null) && ($this->isEntityType($entityType))) $sql->where('entity_type = ?', $entityType);
		return ($this->_db->fetchOne($sql) > 0)?true:false;
	}
	
	/**
	* Check entity type
	* @param int $entityType
	* @author Pianko
	*/
	private function isEntityType( $entityType )
	{
		if (is_int($entityType) && in_array( $entityType, array_keys($this->_entityList))){
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	* Get index name by entity type
	* @param int $entityType
	* @author Pianko
	*/
	private function getIndexName( $entityType )
	{
		if ($this->isEntityType( $entityType ))
		{
			return $this->_entityList[$entityType];
		}else{
			die("<p style='font-size:11px;'>Unknown EntityType $entityType </p>");		
		}
		return false;
	}
	
	/**
	* Clear all or selected data from log table
	* @param int $entityType
	* @param int $entityId
	* @author Pianko
	*/
	public function clearLog ($entityType = null, $entityId = null )
	{		
		if (($entityType != null) && ($entityId != null) )
		{
		    $this->_db->delete( $this->_tableName,
                $this->_db->quoteInto('entity_type =? ', $entityType)." AND ".
                $this->_db->quoteInto('entity_id =? ', $entityId )
            );
		}
		elseif ($entityType != null)
		{
		    $this->_db->delete( $this->_tableName, $this->_db->quoteInto('entity_type =? ', $entityType));
		}
		else
		{
			$this->_db->delete( $this->_tableName, "");
		}
	}
	
	/**
	* update sphinx delta index (or all delta indexes if $entityType === null)
	* @param mixed $keyValue
	* @author Pianko
	*/
	public function updateDeltaIndex ( $entityType = null )
	{
		if ($entityType === null)
		{
			foreach ($this->_entityList as $EntityTypeIs => $EntityTypeValue) {
				$this->updateDeltaIndex($EntityTypeIs);
			}
		}
		elseif ($this->isEntityType( $entityType ) && ($this->isUpdateExist($entityType))) 
		{
			$cmd_string = $this->_indexerPath." --config ".$this->_configPath.$this->_staticParams.$this->getIndexName($entityType).$this->_tmpPostfix;
			system($cmd_string);
			$cmd_string =$this->_indexerPath." --config ".$this->_configPath.$this->_staticParams." --merge ".$this->getIndexName($entityType).$this->_deltaPostfix." ".$this->getIndexName($entityType).$this->_tmpPostfix;
			system($cmd_string);
			$this->clearLog($entityType);
		}
	} 


	/**
	* merge delta and full indexes (if $entityType === null merge all indexes of all types)
	* @param int $entityType
	* @return void
	* @author Pianko
	*/
	public function mergeFullIndex( $entityType = null )
	{
		if ($entityType === null)
		{
			foreach ($this->_entityList as $EntityTypeIs => $EntityTypeValue) {
				$this->mergeFullIndex($EntityTypeIs);
			}
		}
		elseif ($this->isEntityType( $entityType ) && ($this->isUpdateExist($entityType))) 
		{
			$cmd_string =$this->_indexerPath." --config ".$this->_configPath.$this->_staticParams." --merge ".$this->getIndexName($entityType)." ".$this->getIndexName($entityType).$this->_deltaPostfix; 
			system($cmd_string);
		}
	}
	
	/**
	* reindexing full index from DB, clear delta and temporary indexes (or all index entity types if $entityType = null)
	* @param int $entityType
	* @return void
	* @author Pianko
	*/
	public function reindexFullIndex( $entityType = null )
	{
		if ( $entityType === null )
		{
			foreach ( $this->_entityList as $EntityTypeIs => $EntityTypeValue ) {
				$this->reindexFullIndex($EntityTypeIs);
			}
		}
		elseif ( $this->isEntityType( $entityType ) ) 
		{
			// @todo insert into log table for fixing sphinx bug while merge clear delta tables
			//$this->_db->insert($this->_changesTable, array( "entity_type" => $entityType, "entity_id" => $entityId ) );
			
			$cmd_string = $this->_indexerPath." --config ".$this->_configPath.$this->_staticParams.$this->getIndexName($entityType)." ".$this->getIndexName($entityType).$this->_deltaPostfix." ".$this->getIndexName($entityType).$this->_tmpPostfix;
			system($cmd_string); 
			$this->clearLog( $entityType );	
		}
	}

}
