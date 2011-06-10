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
 * @package Warecorp_Photo_List
 * @author Artem Sukharev
 * @version 1.0
 */
class BaseWarecorp_Photo_List_Group extends Warecorp_Photo_List_Abstract
{
    /**
     * return list of all items
     * @return array of objects
     * @author Artem Sukharev
     * @todo getPrivacy
     */
    public function getList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'tbl.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'tbl.title' : $this->getAssocValue();
            $query->from(array('tbl' => Warecorp_Photo_Abstract::$_dbTableName), $fields);  
        } else {
            $query->from(array('tbl' => Warecorp_Photo_Abstract::$_dbTableName), 'tbl.id');
        }
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('tbl.gallery_id IN (?)', ($this->getGalleryId() !== null) ? $this->getGalleryId() : new Zend_Db_Expr('NULL') );
        if ( $this->getIncludeIds() ) $query->where('tbl.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('tbl.id NOT IN (?)', $this->getExcludeIds());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = Warecorp_Photo_Factory::loadById($item);
        }
        return $items;
    }

    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev
     * @todo getPrivacy
     */
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('tbl' => Warecorp_Photo_Abstract::$_dbTableName), new Zend_Db_Expr('COUNT(*)'));
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('tbl.gallery_id IN (?)', ($this->getGalleryId() !== null) ? $this->getGalleryId() : new Zend_Db_Expr('NULL'));
        if ( $this->getIncludeIds() ) $query->where('tbl.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('tbl.id NOT IN (?)', $this->getExcludeIds());
        return $this->_db->fetchOne($query);
    }
    
    /**
     * return last photo for gallery
     * @return Warecorp_Photo_Abstract
     * @author Artem Sukharev
     */
    public function getLastPhoto()
    {
    	$this->setOrder('tbl.creation_date DESC');
    	$this->setCurrentPage(1);
    	$this->setListSize(1);
    	$photo = $this->getList();
    	if ( isset($photo[0]) ) return $photo[0];
    	
    	return new Warecorp_Photo_Standard();
    }
    
     
    /**
     * Get random photo for gallery
     * @return Warecorp_Photo_Abstract
     * @author Komarovski
     */
    public function getRandomPhoto()
    {
        $this->setOrder('RAND()');
        $this->setCurrentPage(1);
        $this->setListSize(1);
        $photo = $this->getList();
        if ( isset($photo[0]) ) return $photo[0];
        
        return new Warecorp_Photo_Standard();
    }
}
?>
