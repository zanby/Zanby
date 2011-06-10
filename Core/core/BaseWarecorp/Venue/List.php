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
 * @package Warecorp_Venue
 * @copyright  Copyright (c) 2006
 * @author Eugene Kirdzei
 */
class BaseWarecorp_Venue_List extends Warecorp_Abstract_List
{
    /**
     * Venue type for search
     *
     * @var string
     */
    private $_type;
    /**
     * Category id for search
     *
     * @var int
     */
    private $_category;
    /**
     * Letter for search
     *
     * @var string {0}
     */
    private $_letter;
    
    /**
     * User id for search
     *
     * @var int
     */
    private $_ownerId;
    private $ownerType;

    /**
     * sets venue type
     *
     * @param string $newVal
     * @return void
     * @author Eugene Kirdzei
     */
    public function setType ( $newVal )
    {
        if ( !Warecorp_Venue_Enum_VenueType::isIn( $newVal ) ) {
            throw new Zend_Exception( 'Incorrect venue type' );
        }
        $this->_type = $newVal;
    }

    /**
     * return type
     *
     * @return string
     * @author Eugene Kirdzei
     */
    public function getType ()
    {
        if ( null === $this->_type )
            $this->setType( null );
        return $this->_type;
    }

    /**
     * set owner id
     *
     * @param int|object $newVal
     */
    public function setOwnerId ( $newVal )
    { 
        if ( is_object( $newVal ) )
            $newVal = $newVal->id;
        //if ( !is_int( $newVal ) )
        //    $newVal = null;
       
        $this->_ownerId = $newVal;
    }

    /**
     * return owner id
     *
     * @return int
     */
    public function getOwnerId ()
    {
        return $this->_ownerId;
    }

    /**
     * @return unknown
     */
    public function getOwnerType ()
    {
        return $this->ownerType;
    }

    /**
     * @param unknown_type $ownerType
     */
    public function setOwnerType ( $ownerType )
    {
        $this->ownerType = $ownerType;
        return $this;
    }

    /**
     * sets venue category id
     *
     * @param int $newVal
     * @return void
     * @author Eugene Kirdzei
     */
    public function setCategory ( $newVal )
    {
        if ( $newVal == 0 )
            $newVal = null;
        $this->_category = $newVal;
    }

    /**
     * return category id
     *
     * @return int
     * @author Eugene Kirdzei
     */
    public function getCategory ()
    {
        return $this->_category;
    }

    /**
     * sets search letter for venue
     *
     * @param string $newVal
     * @return void
     * @author Eugene Kirdzei
     */
    public function setLetter ( $newVal )
    {
        if ( $newVal == 'all' )
            $newVal = null;
        if ( is_string( $newVal ) )
            $newVal = strtoupper( $newVal );
        
        $this->_letter = $newVal;
    }

    /**
     * return letter for search
     *
     * @return string
     */
    public function getLetter ()
    {
        return $this->_letter;
    }

    /**
     * return venues list
     *
     * @return array
     * @author Eugene Kirdzei
     */
    public function getList ()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'name' : $this->getAssocValue();
            $query->from( 'zanby_event__venues', $fields );
        } else {
            $query->from( 'zanby_event__venues', 'id' );
        }
        if ( $this->getWhere() )
            $query->where( $this->getWhere() );
        
        if ( $this->getOwnerId() ) {
            $query->where( 'owner_id = ?', $this->getOwnerId() );
        }
        if ( null !== $this->getOwnerType() ) {
            $query->where( 'owner_type = ?', $this->getOwnerType() );
        }
        if ( $this->getType() ) {
            $query->where( 'type = ?', $this->getType() );
        }
        if ( $this->getCategory() ) {
            $query->where( 'category_id = ?', $this->getCategory() );
        }
        if ( $this->getLetter() ) {
            $query->where( 'UPPER(SUBSTRING(`name`,1,1)) = ?', $this->getLetter() );
        }
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage( $this->getCurrentPage(), $this->getListSize() );
        }
        if ( $this->getOrder() !== null ) {
            $query->order( $this->getOrder() );
        }
	// print $query->__toString();
        $items = array();
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs( $query );
        } else {
            $items = $this->_db->fetchCol( $query );
            foreach ( $items as &$item )
                $item = new Warecorp_Venue_Item( $item );
        }
        return $items;
    }

    /**
     * return number of all items
     * @return int count
     * @author Eugene Kirdzei
     */
    public function getCount ()
    {
        $query = $this->_db->select();
        $query->from( 'zanby_event__venues', new Zend_Db_Expr('COUNT(id)') );
        if ( $this->getWhere() )
            $query->where( $this->getWhere() );
        if ( $this->getOwnerId() )
            $query->where( 'owner_id = ?', $this->getOwnerId() );
        if ( null !== $this->getOwnerType() ) 
            $query->where( 'owner_type = ?', $this->getOwnerType() );
        if ( $this->getType() )
            $query->where( 'type = ?', $this->getType() );
        if ( $this->getCategory() )
            $query->where( 'category_id = ?', $this->getCategory() );
        if ( $this->getLetter() )
            $query->where( 'UPPER(SUBSTRING(`name`,1,1)) = ?', $this->getLetter() );
        return $this->_db->fetchOne( $query );
    }

    /**
     * return letters array for search
     *
     * @return array
     * @author Eugene Kirdzei
     */
    public function getLettersList ()
    {
        $query = $this->_db->select();
        $query->from( 'zanby_event__venues', array('id', 'letter' => new Zend_Db_Expr('UPPER(SUBSTRING(`name`,1,1))')));
        if ( $this->getWhere() )
            $query->where( $this->getWhere() );
        if ( $this->getType() )
            $query->where( 'type = ?', $this->getType() );
        if ( $this->getOwnerId() )
            $query->where( 'owner_id = ?', $this->getOwnerId() );
        if ( $this->getCategory() )
            $query->where( 'category_id = ?', $this->getCategory() );
        $result = $this->_db->fetchPairs( $query );
        $items = array();
        foreach ( $result as $k => $v ) {
            $items[$v][] = $k;
        }
        return $items;
    }

}

?>
