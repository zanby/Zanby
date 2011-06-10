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
 * @package Warecorp_Video_List
 * @author Yury Zolotarsky
 * @version 1.0
 */
abstract class BaseWarecorp_Video_List_Abstract extends Warecorp_Abstract_List
{

	private $galleryId;
	private $random;
    protected $mostActiveOrder = false;
    protected $mostUppedOrder = false;
    protected $mostRecentOrder = false;
    protected $query;

	function __construct($galleryId = null)
	{
        if ( null !== $galleryId ) $this->setGalleryId($galleryId);
		parent::__construct();
        $this->query = $this->_db->select(); 
	}

	public function getGalleryId()
	{
		return $this->galleryId;
	}

	public function setGalleryId($newVal)
	{
		$this->galleryId = $newVal;
	}

	public function getRandom()
	{
		return $this->random;
	}

	public function setRandom($newVal)
	{
		$this->random = $newVal;
	}
    
    public function getList()
    {
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'tbl.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'tbl.title' : $this->getAssocValue();
            $this->query->from(array('tbl' => Warecorp_Video_Abstract::$_dbTableName), $fields);  
        } else {
            $this->query->from(array('tbl' => Warecorp_Video_Abstract::$_dbTableName), 'tbl.id');
        }
        if ( $this->getWhere() ) $this->query->where($this->getWhere());
        if ($this->mostActiveOrder) {
            $this->query->join(array( 'actView' => new Zend_Db_Expr('
                (select zvv.id as video_id, count(zvvv.user_id) as activity from '.Warecorp_Video_Abstract::$_dbTableName.' zvv left join '.Warecorp_Video_Abstract::$_dbViewsTableName.' zvvv on (zvv.id = zvvv.video_id) group by zvv.id)')), 'tbl.id = actView.video_id', array() );
            $this->query->order('actView.activity desc');           
/*            $this->query->joinLeft('(select zuc.entity_id as video_id, count(zuc.id) as activity from zanby_users__comments zuc where zuc.entity_type_id = 37 group by zuc.entity_id) actView', 'tbl.id = actView.video_id');
            $this->query->order('actView.activity desc');*/
        } elseif($this->mostUppedOrder) {
            $this->query->join(array('rankView' => new Zend_Db_Expr('
                (select zvv.id as video_id, ifnull(sum(zvu.value),0) as rank from '.Warecorp_Video_Abstract::$_dbTableName.' zvv left join '.Warecorp_Video_Abstract::$_dbUpDownTableName.' zvu on (zvv.id = zvu.video_id) group by zvv.id) ')), 'tbl.id = rankView.video_id',array());
            $this->query->order('rankView.rank desc');            
        } elseif($this->mostRecentOrder) {
            //
        } else {
            if ( $this->getOrder() !== null ) {
                $this->query->order($this->getOrder());
            }            
        }
        if (!empty($this->galleryId))
            $this->query->where('tbl.gallery_id IN (?)', $this->getGalleryId());
        if ( $this->getIncludeIds() ) $this->query->where('tbl.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $this->query->where('tbl.id NOT IN (?)', $this->getExcludeIds());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $this->query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        
        //var_dump($this->query->__toString());
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($this->query);
        } else {
            $items = $this->_db->fetchCol($this->query);
            foreach ( $items as &$item ) $item = Warecorp_Video_Factory::loadById($item);
        }        
        $this->query = $this->_db->select();
        return $items;
    }

    public function getCount()
    {
        $this->query->from(array('tbl' => Warecorp_Video_Abstract::$_dbTableName), new Zend_Db_Expr('COUNT(*)'));
        if ( $this->getWhere() ) $this->query->where($this->getWhere());
        if (!empty($this->galleryId)) {
            $this->query->where('tbl.gallery_id IN (?)', $this->getGalleryId());
        }
        if ( $this->getIncludeIds() ) $this->query->where('tbl.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $this->query->where('tbl.id NOT IN (?)', $this->getExcludeIds());    
        $res = $this->_db->fetchOne($this->query);
        $this->query = $this->_db->select();
        return $res;
    }
    
    public function returnInMostActiveOrder($newVal = true)
    {
        $this->mostRecentOrder = false;
        $this->mostUppedOrder = false;
        $this->mostActiveOrder = $newVal;
        return $this;
    }    
    
    public function returnInMostUppedOrder($newVal = true)
    {
        $this->mostRecentOrder = false;
        $this->mostActiveOrder = false;
        $this->mostUppedOrder = $newVal;
        return $this;
    }
    
    public function returnInMostRecentOrder($newVal = true)
    {
        $this->mostActiveOrder = false;
        $this->mostUppedOrder = false;
        $this->mostRecentOrder = $newVal;
        return $this;
    }

    public function isSetInMostActiveOrder()
    {
        return $this->mostActiveOrder;    
    }
    
    public function isSetInMostUppedOrder()
    {
        return $this->mostUppedOrder;    
    }    

    public function isSetInMostRecentOrder()
    {
        return $this->mostRecentOrder;    
    }
        
	abstract public function getLastVideo();
    
    abstract public function getRandomVideo();	
}
