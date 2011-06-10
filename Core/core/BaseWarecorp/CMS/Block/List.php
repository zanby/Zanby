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
 * @package Warecorp_CMS
 * @author Serge Rybakov
 */
class BaseWarecorp_CMS_Block_List extends Warecorp_Abstract_List
{
    private $_pageAlias = null;
    private $_items = array();
    private $_invalidate = true;

    /**
     * Construtor
     *
     * @param string $pageAlias Alias of page which blocks have to be bound to
     */
    public function __construct($pageAlias = "")
    {
        parent::__construct();

        $this->setPageAlias($pageAlias);
        $this->invalidate();
    }

    public function getPageAlias()
    {
        return $this->_pageAlias;
    }
    public function setPageAlias($pageAlias)
    {
        if($pageAlias != $this->getPageAlias())
        {
            $this->_pageAlias = $pageAlias;
            $this->invalidate();
        }
    }

    /**
     * Makes the list become outdated
     *
     *  @author Serge Rybakov
     */
    public function invalidate()
    {
        $this->_invalidate = true;
    }

    /**
     *  Return list of all blocks.
     *  If page alias is not set, blocks without binding to pages will be returned
     * 
     *  @return array of Warecorp_CMS_Block_Item
     *  @author Serge Rybakov
     */
    public function getList()
    {
        if($this->_invalidate)
        {
            // reload items from db
            $query = $this->_db->select();
            if ( $this->isAsAssoc() ) {
                $fields = array();
                $fields[] = ( $this->getAssocKey() === null )   ? 'zcb.id' : $this->getAssocKey();
                $fields[] = ( $this->getAssocValue() === null ) ? 'zcb.order' : $this->getAssocValue();
                $query->from(array('zcb' => 'zanby_cms__blocks'), $fields);
            } else {
                $query->from(array('zcb' => 'zanby_cms__blocks'), 'zcb.*');
            }
            $pa = $this->getPageAlias();
            if(!empty($pa))
            {
                $query->join(array('zcp' => 'zanby_cms__pages'), 'zcp.id = zcb.page_id');
                $query->where('zcp.alias = ?', $pa);
            }
            else
            {
                $query->where('zcb.page_id = 0');
            }
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
                foreach ( $items as &$item ) {
                    $item = new Warecorp_CMS_Block_Item($item);
                    $this->_items[$item->getOrder()] = $item;
                }
            }

            $this->_invalidate = false;
        }
        return $this->_items;
    }

    /**
     * return number of all items
     * @return int count
     * @author Serge Rybakov
     */
    public function getCount()
    {
        count($this->getList());
    }
}
