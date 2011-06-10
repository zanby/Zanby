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


class BaseWarecorp_Venue_Search extends Warecorp_Search
{
	private $aCriteria = array();
	public $perPage = 10;
	public $page;
	public $count;
	public $countryId;
	public $stateId;
	public $cityId;
	public $whereParts = array();
	private $userObj;
	
	public function __construct( Warecorp_User $userObj = null)
	{
	  parent::__construct();
	  $this->userObj = $userObj; 
	}
	
	public function getSearchResult($aCriteria = array())
	{
		if (!empty($aCriteria)) $this->aCriteria = $aCriteria;

		$searchQuery = $this->getSearchQuery();
		
		$aoResult = array();
		if (sizeof($searchQuery) > 0) {
			foreach ($searchQuery as $v) {
				$aoResult[] = new Warecorp_Venue_Item($v);
			}
		} 
		return $aoResult;
	}
	
	private function getSearchQuery()
	{
		$noresult = false;
        if (WITH_SPHINX) {

            if ($this->aCriteria) {
                $query = "";
                // create object Warecorp_Data_Search
                $cl = new Warecorp_Data_Search();
                // initialization
                $cl->init('venue');
                if (!is_array($this->aCriteria['find_keywords']) && strlen(trim($this->aCriteria['find_keywords'])) > 0){
                    $query = str_replace(',',' ',$this->aCriteria['find_keywords']);
                } 
                     
                if ($this->aCriteria['find_category'] != 0) {
                    $cl->setFilter('category_id', array($this->aCriteria['find_category']) );
                }

                $cl->setFilter('private', array(0) ); 

                if ($this->aCriteria['find_createdBy'] != 'anyone') {
                    switch ($this->aCriteria['find_createdBy']) {
                        case 'groups': 
                            $cl->setFilter('created_by', array(1) ); 
                            break;
                        case 'group_families': 
                            $cl->setFilter('created_by', array(2) ); 
                            break;
                        case 'friends': {
                            $cl->setFilter('created_by', array(4) ); 
                            $cl->setFilter('owner_type', array(1) ); 
                          
			    if (null != $this->userObj) {
				$friends = new Warecorp_User_Friend_List();
				$friends->returnAsAssoc(true)->setUserId($this->userObj->getId());
				$friendsList = $friends->getList(); 

				if ( sizeof($friendsList) > 0 ) {
				    $cl->setFilter('owner_id', $friendsList ); 
				} else {
				    $noresult = true;
				}
			    }
                        } 
                        break;
                    }
                }
                if ($this->cityId !== null) {
                    $cl->setFilter('city_id', array($this->cityId) ); 
                } elseif ($this->stateId !== null) {
                    $cl->setFilter('state_id', array($this->stateId) ); 
                } elseif ($this->countryId !== null) {
                    $cl->setFilter('country_id', array($this->countryId) ); 
                }
				if ($noresult) {
					$result = array();
				} else {
					$cl->Query($query);
					$result = $cl->getResultPairs();
					
					$this->count = count($result ); 
					$result = array_slice ( $cl->getResultPairs(), ($this->aCriteria['find_page']-1)*$this->perPage , $this->perPage, true);
				}
                //print_r($result);
				unset($cl);
            }
        }
        else{
		    $SQL = $this->_db->select()->from(array('vevl' => 'view_events__venue_list'), array('vevl.id'));

		    if ($this->aCriteria){
		      if (!is_array($this->aCriteria['find_keywords']) && strlen(trim($this->aCriteria['find_keywords'])) > 0){
		        $this->aCriteria['find_keywords'] = explode(',',$this->aCriteria['find_keywords']);
                foreach($this->aCriteria['find_keywords'] as &$keyword) {
                    $keyword = substr($keyword, 0, 100);
                }
		      } else {
		        $this->aCriteria['find_keywords'] = array();
		      }
		      
		      if (!is_array($this->aCriteria['find_where']) && strlen(trim($this->aCriteria['find_where'])) > 0){
		        $this->aCriteria['find_where'] = explode(',',$this->aCriteria['find_where']);
		      } else {
		        $this->aCriteria['find_where'] = array();
		      }
		      
		      if (sizeof($this->aCriteria['find_keywords']) > 0){
		        $keywords_count = count($this->aCriteria['find_keywords']);
		        /*$where = '(';
		        $i=0;
		        
		        foreach ($this->aCriteria['find_keywords'] as $k => $v){
		          $i++;
		          $where .= " `name` LIKE '%".trim(addslashes($v))."%' OR `description` LIKE '%".trim(addslashes($v))."%'";
		          if ($i < $keywords_count) $where .= ' OR ';
		        }
		        
		        $SQL->where($where. ")");
		        */
		        $SQL->join(array('vevtu' => 'view_events__venue_tags_used'),'vevl.id=vevtu.id')
		          ->where('vevtu.tag_name IN (?)', $this->aCriteria['find_keywords'])
		          ->group('vevl.id');                
		      }
	    
		      if ($this->aCriteria['find_category'] != 0) {
		        $SQL->where('category_id = ?', $this->aCriteria['find_category']);
		      }
			    
		      if ($this->aCriteria['find_createdBy'] != 'anyone') {
		        switch ($this->aCriteria['find_createdBy']) {
		            case 'groups': {
		                $SQL->where('created_by = ?', 'simple');		    			
		                } break;
		            case 'group_families': {
		                $SQL->where('created_by = ?', 'family');                        
		                } break;
		            case 'friends': {
		                $SQL->where('created_by = ?', 'friend');
		                $SQL->where( 'owner_type = ?', Warecorp_Venue_Enum_OwnerType::USER );
		              
		                if (null != $this->userObj) {
			                $friends = new Warecorp_User_Friend_List();
			                $friends->returnAsAssoc(true)->setUserId($this->userObj->getId());
			                $friendsList = $friends->getList(); 
			            
			                if ( sizeof($friendsList) > 0 ) {
			                    $SQL->where('owner_id IN (?)', $friendsList);
		                    } else {
								$noresult = true;
							}
		                }
		            } 
                    break;
		        }
		      }

		      //		  dump($this);
            if ($this->cityId !== null) {
	          $SQL->where('vevl.city_id = ?', $this->cityId);
	        } elseif ($this->stateId !== null) {
	          $SQL->where('vevl.state_id = ?', $this->stateId);
	        } elseif ($this->countryId !== null) {
	          $SQL->where('vevl.country_id = ?', $this->countryId);
	        }	
 		    }
		    //print $SQL->__toString(); exit;
			if ($noresult) {
				$result = array();
			} else {
				$tmpResult = $this->_db->fetchCol($SQL);
				$this->count = sizeof($tmpResult);    
				
				$SQL->limitPage($this->aCriteria['find_page'], $this->perPage);
				
				$result = $this->_db->fetchCol($SQL);
			}
        }
		return $result;
	}
	

	public function parseParams( $params = array() )
	{

         // parse WHERE
        $params['where'] = isset($params['find_where']) ? trim($params['find_where']) : "";

        if (!empty($params['where'])) {

	  if ( 0 == substr_compare($params['where'],',',-1,1))
	    $params['where'] = substr($params['where'],0,strlen($params['where'])-1);

            $whereParts = preg_split("/\s*,+\s*/", $params['where']); // split by " , "
            $whereParts = array_slice(array_unique($whereParts), 0, 3); // take only 3 unique parts (Country, State, City)
            if (empty($whereParts)) return; // WHERE is empty

	    $query = $this->_db->select();
            $query->from(array('zlc' => 'zanby_location__countries'), array('zlc.id', 'zlc.name', 'zlc.code'))
                  ->orWhere('zlc.name IN (?)', $whereParts)
                  ->orWhere('zlc.code IN (?)', $whereParts)
                  ->limit(1);
	    //print $query->__toString();exit;
            $country = $this->_db->fetchRow($query); // try to get country 
            
            $query = $this->_db->select();
            if (!empty($country['name'])) {
                $this->countryId = $country['id'];
                $this->whereParts[] = $country['name'];
                $whereParts = array_diff($whereParts, $country);  // exclude country name or code
                $query->where('zls.country_id = ?', $country['id']);
            }

            if (empty($whereParts)) return;  // specified only country
            $query->from(array('zls' => 'zanby_location__states'), array('zls.id', 'zls.name', 'zls.code'))
                  ->where('( zls.name IN (?)', $whereParts)
                  ->orWhere('zls.code IN (?) )', $whereParts)
                  ->limit(1);
            //print $query->__toString();exit;
	    $state = $this->_db->fetchRow($query);

            $query = $this->_db->select();
            if (!empty($state['name'])) {
                $this->stateId = $state['id'];
                $this->whereParts[] = $state['name'];
                $whereParts = array_diff($whereParts, $state);  // exclude state name or code
                $query->where('zlci.state_id = ?', $state['id'])
                      ->from(array('zlci' => 'zanby_location__cities'), array('zlci.id', 'zlci.name', 'zlci.state_id'));
            } elseif(!empty($country['name'])) { // there isn't state in specified location, try get state name by city
                $query->join(array('zls' => 'zanby_location__states'), 'zls.id = zlci.state_id', array('state_name' => 'zls.name'))
                      ->join(array('zlc' => 'zanby_location__countries'), 'zls.country_id = zlc.id')
                      ->where('zls.country_id = ?', $country['id'])
                      ->from(array('zlci' => 'zanby_location__cities'), array('zlci.id', 'zlci.name', 'zlci.state_id'));
            } else {
                $query->from(array('zlci' => 'zanby_location__cities'), array('zlci.id', 'zlci.name', 'zlci.state_id'));
            }
            if (empty($whereParts)) return; // specified only country and state
            $query->where('zlci.name IN (?)', $whereParts)
                  ->limit(1);
            $city = $this->_db->fetchRow($query);
            if (!empty($city['name'])) {
                if (empty($state['name']) && empty($country['name'])) { // specified only city name, try get state and country by city
                    $query = $this->_db->select();
                    $query->from(array('zlc' => 'zanby_location__countries'), array('country_id' => 'zlc.id', 'country_name' => 'zlc.name' ))
                          ->join(array('zls' => 'zanby_location__states'), 'zls.country_id = zlc.id', array('state_id' => 'zls.id', 'state_name' => 'zls.name'))
                          ->where('zls.id = ?', $city['state_id'])
                          ->limit(1);
                    $state = $this->_db->fetchRow($query);
                    if (!empty($state['state_name']) && !empty($state['country_name'])) {
                        $this->countryId    = $state['country_id'];
                        $this->whereParts[] = $state['country_name'];
                        $this->stateId      = $state['state_id'];
                        $this->whereParts[] = $state['state_name'];
                    }
                } elseif (empty($state['name']) && !empty($country['name']) && !empty($city['state_name'])) { // specified country name and city name, try get state by city & country
                    $this->stateId      = $city['state_id'];
                    $this->whereParts[] = $city['state_name'];
                }
                
                $this->cityId = $city['id'];
                $this->whereParts[] = $city['name'];
            }
        }
	}
}

?>
