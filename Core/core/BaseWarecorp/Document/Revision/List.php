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
 * @package    Warecorp_Document
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */

/**
 *
 *
 */
class BaseWarecorp_Document_Revision_List extends Warecorp_Abstract_List
{
    /**
     * @var int
     */
    private $documentId;
    
    /**
     * @return int
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }
    
    /**
     * @param int $documentId
     */
    public function setDocumentId( $documentId )
    {
        $this->documentId = $documentId;
    }

    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Enter description here...
     *
     * @return unknown
     */
    public function getList() {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null )   ? 'dr.revision_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'dr.revision_id' : $this->getAssocValue();
            $query->from(array('dr' => 'zanby_documents__revisions'), $fields);  
        } else {
            $query->from(array('dr' => 'zanby_documents__revisions'), '*');
        }
        
        /* conditions */
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('dr.revision_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('dr.revision_id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getDocumentId() ) $query->where('dr.document_id = ?', $this->getDocumentId());
        
        /* pager */
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) $query->limitPage($this->getCurrentPage(), $this->getListSize());
        
        /* order */
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order('dr.revision_number');

        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchAll($query);
            foreach ( $items as &$item ) { $item = new Warecorp_Document_Revision($item); }
        }

        return $items;
    }
    
    /**
     * Enter description here...
     *
     * @return unknown
     */
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('dr' => 'zanby_documents__revisions'), new Zend_Db_Expr('COUNT(dr.revision_id)'));
        
        /* conditions */
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('dr.revision_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('dr.revision_id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getDocumentId() ) $query->where('dr.document_id = ?', $this->getDocumentId());

        return $this->_db->fetchOne($query);
    }
    
    /**
     * Enter description here...
     *
     * @return unknown
     */
    public function getLastRevision() {
        $query = $this->_db->select();
        $query->from(array('dr' => 'zanby_documents__revisions'), '*');
        
        /* conditions */
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('dr.revision_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('dr.revision_id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getDocumentId() ) $query->where('dr.document_id = ?', $this->getDocumentId());
        
        $query->order('dr.revision_number DESC');
        $query->limitPage(1, 1);

        $item = $this->_db->fetchRow($query);
        if ( $item ) $item = new Warecorp_Document_Revision($item);

        return $item;
    }
}
