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
 * @package Warecorp_User_Friend
 * @copyright Copyright (c) 2006
 * @author Eugene Kirdzei
 */

class BaseWarecorp_User_Friend_List extends Warecorp_Abstract_List
{
	private $userId;
	private $countryIds;
	private $stateIds;
	private $cityIds;
	private $joinView;

    /**
     * Set variable $joinView on true if we add filter by any location
     * 
     * @param boolean
     * @author Eugene Kirdzei
     */
	public function setJoinView($newVal) 
	{
		$this->joinView = $newVal;
	}
	
   /**
     * Return true if we add filter by any location
     * 
     * @return boolean
     * @author Eugene Kirdzei
     */
    public function getJoinView() 
    {
        return $this->joinView;
    }
	
	/**
	 * Return user id
	 *
	 * @return int
	 * @author Eugene Kirdzei
	 */
	public function getUserId()
	{
		return $this->userId;
	}

	/**
	 * Set user id
	 * 
	 * @param int
	 * @author Eugene Kirdzei
	 */
	public function setUserId($newVal)
	{
		$this->userId = $newVal;
		return $this; 
	}
	
	/**
	 * Set country id for search
	 *
	 * @param array $newVal
	 * @return self
	 * @author Eugrnr Kirdzei
	 */
	public function setCountryIds($newVal)
	{
		if ( !is_array($newVal) ) {
			$newVal =  array($newVal);
		}
		$this->setJoinView( true );
		$this->countryIds = $newVal;
        return $this;
	}

	/**
	 * Return country id
	 *
	 * @return int
	 * @author Eugrnr Kirdzei
	 */
	public function getCountryIds()
	{
		return $this->countryIds;
	}
	
    /**
     * Set state id for search
     *
     * @param int $newVal
     * @return self
     * @author Eugrnr Kirdzei
     */
    public function setStateIds($newVal)
    {
        if ( !is_array($newVal) ) {
            $newVal =  array($newVal);
        }
    	$this->setJoinView( true );
    	$this->stateIds = $newVal;
        return $this;
    }

    /**
     * Return state id
     *
     * @return int
     * @author Eugrnr Kirdzei
     */
    public function getStateIds()
    {
        return $this->stateIds;
    }
	
    /**
     * Set city id for search
     *
     * @param int $newVal
     * @return self
     * @author Eugrnr Kirdzei
     */
    public function setCityIds($newVal)
    {
        if ( !is_array($newVal) ) {
            $newVal =  array($newVal);
        }
    	$this->setJoinView( true );
    	$this->cityIds = $newVal;
        return $this;
    }

    /**
     * Return city id
     *
     * @return int
     * @author Eugrnr Kirdzei
     */
    public function getCityIds()
    {
        return $this->cityIds;
    }    
    
    public function getOrederByName()
    {
    	return $this->orderByName;
    }
    
	/**
	 * Return list of friends
	 *
	 * @return array
	 * @author Eugene Kirdzei
	 */
	public function getList()
	{
		$query = $this->_db->select();
        $query->from('view_users__friends', array('friend_id', 'created'));

      	$query->join (array('zua' => 'zanby_users__accounts'), "zua.id = friend_id" );
        $query->join (array('zlcit' => 'zanby_location__cities'), "zlcit.id = zua.city_id" );
        $query->join (array('zlst' => 'zanby_location__states'), "zlst.id = zlcit.state_id" );
        $query->join (array('zlc' => 'zanby_location__countries'), "zlc.id = zlst.country_id" );
        	//$query2 = $this->_db->select( );
        	//$query2->from('zanby_users__accounts', 'status');
        	
       	$query->where('zua.`status` = ?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);
        
        $query->where('(user_id = ?)', $this->getUserId());
        
        /*
	    if ( null !== $this->getCountryIds()) {
            $query->where('vul.country_id IN (?)', $this->getCountryIds());
        }
        
        if ( null !== $this->getStateIds()) {
            $query->where('vul.state_id IN (?)', $this->getStateIds());
        }
        
        if ( null !== $this->getCityIds()) {
            $query->where('vul.city_id IN (?)', $this->getCityIds());
        }
        */
        
        if ( null !== $this->getExcludeIds() && sizeof( $this->getExcludeIds() ) > 0 ) {
                $query->where('(friend_id NOT IN ( ? ) )', $this->getExcludeIds());
        }
        
        if ( $this->getWhere() ) $query->where( $this->getWhere() );
        
        if ( null !== $this->getCurrentPage() && null !== $this->getListSize() ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }

        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        //print $query->__toString();exit;
        $items = array();
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchCol($query);
        } else {
        $items = $this->_db->fetchCol($query);
            foreach ( $items as $key => &$item ) {
                   $item = new Warecorp_User_Friend_Item($this->getUserId(),$item);
            }
        }
        
        return $items;          
	}

	/**
	 * Return count of friends
	 *
	 * @return int
	 * @author Eugene Kirdzei
	 */
	public function getCount()
	{
        $query = $this->_db->select();
        $query->from('view_users__friends', new Zend_Db_Expr('COUNT(*)'));

        if ( null !== $this->getJoinView() ) {
            $query->join (array('zua' => 'zanby_users__accounts'), "zua.id = friend_id" );
            
            $query->where('zua.`status` = ?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);
        } else {
            $query->join(array('zua' => 'zanby_users__accounts'), 'zua.id = friend_id');
            $query->where('zua.status = ?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);
        }
        
        $query->where('(user_id = ?)', $this->getUserId());
        /*
	    if ( null !== $this->getCountryIds()) $query->where('vul.country_id IN (?)', $this->getCountryIds());
        if ( null !== $this->getStateIds())   $query->where('vul.state_id IN (?)', $this->getStateIds());
        if ( null !== $this->getCityIds())    $query->where('vul.city_id IN (?)', $this->getCityIds());
*/
        if ( $this->getWhere() ) $query->where( $this->getWhere() );
        return $this->_db->fetchOne($query);
	}

}
?>
