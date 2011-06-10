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
 * @package    Warecorp_Photo
 * @copyright  Copyright (c) 2006
 */

/**
 *
 *
 */
class BaseWarecorp_Venue_Item extends Warecorp_Data_Entity
{
    private $id;
    private $ownerType;
    private $ownerId;
    private $type;
    private $categoryId;
    private $category = null;
    private $name;
    private $cityId;
    private $city;
    private $zipcode;
    private $address1;
    private $address2;
    private $phone;
    private $website;
    private $description;
    private $private;
    private $lat;
    private $lng;
	private $geoCoordinates;
    private $creationDate;
//    private $status;
//    private $savedName;

    /**
     * Constructor.
     *
     */
    public function __construct($id = null)
    {
        parent::__construct('zanby_event__venues');

        $this->addField('id');
        $this->addField('owner_type', 'ownerType');
        $this->addField('owner_id', 'ownerId');
        $this->addField('type', 'type');
        $this->addField('category_id', 'categoryId');
        $this->addField('name', 'name');
        $this->addField('city_id', 'cityId');
        $this->addField('zipcode', 'zipcode');
        $this->addField('address1', 'address1');
        $this->addField('address2', 'address2');
        $this->addField('phone', 'phone');
        $this->addField('website', 'website');
        $this->addField('description', 'description');
        $this->addField('private', 'private');
        $this->addField('lat', 'lat');
        $this->addField('lng', 'lng');
        $this->addField('creation_date', 'creationDate');

        if ($id !== null){
            $this->pkColName = 'id';
            $this->loadByPk($id);
        }
        
        $this->setCategory();
    }
    
    /**
     * set new venue id
     *
     * @param int $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setId($newValue)
    {
        $this->id = $newValue;
        return $this;
    }    
    
    /**
     * return venue id
     *
     * @return int
     * @author Eugene Kirdzei
     */    
    public function getId()
    {
        return $this->id;
    }   

    /**
     * set owner type
     *
     * @param string $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setOwnerType($newValue)
    {
        $this->ownerType = $newValue;
        return $this;
    }    
    
    /**
     * return owner type
     *
     * @return string
     * @author Eugene Kirdzei
     */    
    public function getOwnerType()
    {
        return $this->ownerType;
    }   
    
    /**
     * set owner id
     *
     * @param int $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setOwnerId($newValue)
    {
        $this->ownerId = $newValue;
        return $this;
    }    
    
    /**
     * return owner id
     *
     * @return int
     * @author Eugene Kirdzei
     */    
    public function getOwnerId()
    {
        return $this->ownerId;
    }   
    
    /**
     * set type
     *
     * @param string $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setType($newValue)
    {
        $this->type = $newValue;
        return $this;
    }    
    
    /**
     * return type
     *
     * @return string
     * @author Eugene Kirdzei
     */    
    public function getType()
    {
        return $this->type;
    }   
    
    /**
     * set category id
     *
     * @param int $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setCategoryId($newValue)
    {
        $this->categoryId = $newValue;
        return $this;
    }    
    
    /**
     * return category id
     *
     * @return int
     * @author Eugene Kirdzei
     */    
    public function getCategoryId()
    {
        return $this->categoryId;
    }       
    
    /**
     * set category
     *
     * @return self
     * @author Eugene Kirdzei
     */
    public function setCategory()
    {
        $this->category = new Warecorp_Venue_Category($this->getCategoryId());
        return $this;
    }    
    
    /**
     * return category object
     *
     * @return object
     * @author Eugene Kirdzei
     */    
    public function getCategory()
    {
        if ($this->category instanceof Warecorp_Venue_Category) {
            $vid = $this->category->getId();
        } else $vid = null;       
        if ( empty($vid) && $this->categoryId) $this->setCategory();
    	return $this->category;
    }    

    /**
     * set name
     *
     * @param string $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setName($newValue)
    {
        $this->name = $newValue;
        return $this;
    }    
    
    /**
     * return name
     *
     * @return string
     * @author Eugene Kirdzei
     */    
    public function getName()
    {
        return $this->name;
    }    
    
    /**
     * set city id
     *
     * @param int $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setCityId($newValue)
    {
        $this->cityId = $newValue;
        return $this;
    }    
    
    /**
     * return city id
     *
     * @return int
     * @author Eugene Kirdzei
     */    
    public function getCityId()
    {
        return $this->cityId;
    }    

    
    /**
     * return city object
     *
     * @return object
     * @author Eugene Kirdzei
     */
    public function getCity()
    {
    	if ( null == $this->city){
    		$this->setCity(); 
    	}
    	
    	return $this->city;
    }
    
    /**
     * Set city
     *
     * @return self
     * @author Eugene Kirdzei
     */
    public function setCity()
    {
    	if ( $this->getCityId() ) {
    	   $this->city = Warecorp_Location_City::create( $this->getCityId() );
    	}
    	return $this;	
    }

    /**
     * set zipcode
     *
     * @param string $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setZipcode($newValue)
    {
        $this->zipcode = $newValue;
        return $this;
    }    
    
    /**
     * return zipcode 
     *
     * @return string
     * @author Eugene Kirdzei
     */    
    public function getZipcode()
    {
        return $this->zipcode;
    }    
    
    /**
     * set address 1
     *
     * @param string $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setAddress1($newValue)
    {
        $this->address1 = $newValue;
        return $this;
    }    
    
    /**
     * return address 1
     *
     * @return string
     * @author Eugene Kirdzei
     */    
    public function getAddress1()
    {
        return $this->address1;
    }
    
    /**
     * set address 2
     *
     * @param string $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setAddress2($newValue)
    {
        $this->address2 = $newValue;
        return $this;
    }    
    
    /**
     * return address 2
     *
     * @return string
     * @author Eugene Kirdzei
     */    
    public function getAddress2()
    {
        return $this->address2;
    }

    
    /**
     * set phone
     *
     * @param string $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setPhone($newValue)
    {
        $this->phone = $newValue;
        return $this;
    }    
    
    /**
     * return phone
     *
     * @return string
     * @author Eugene Kirdzei
     */    
    public function getPhone()
    {
        return $this->phone;
    }    
    
    /**
     * set web site
     *
     * @param string $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setWebsite($newValue)
    {
        $this->website = $newValue;
        return $this;
    }    
    
    /**
     * return web site
     *
     * @return string
     * @author Eugene Kirdzei
     */    
    public function getWebsite()
    {
        return $this->website;
    }
        
    /**
     * set description
     *
     * @param string $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setDescription($newValue)
    {
        $this->description = $newValue;
        return $this;
    }    
    
    /**
     * return description
     *
     * @return string
     * @author Eugene Kirdzei
     */    
    public function getDescription()
    {
        return $this->description;
    }
        
    
    /**
     * set privacy
     *
     * @param boolean $newValue
     * @return self
     * @author Eugene Kirdzei
     */
    public function setPrivate($newValue)
    {
        $this->private = $newValue;
        return $this;
    }    
    
    /**
     * return privacy
     *
     * @return boolean
     * @author Eugene Kirdzei
     */    
    public function getPrivate()
    {
        return $this->private;
    }
	/**
     * @param $lng the $lng to set
     */
    public function setLng( $lng )
    {
        $this->lng = $lng;
        return $this;
    }

	/**
     * @return the $lng
     */
    public function getLng()
    {
        return $this->lng;
    }

	/**
     * @param $lat the $lat to set
     */
    public function setLat( $lat )
    {
        $this->lat = $lat;
        return $this;
    }

	/**
     * @return the $lat
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * setCreationDate
     * 
     * @param mixed $date 
     * @access public
     * @return Warecorp_Venue_Item
     */
    public function setCreationDate( $date )
    {
        $this->creationDate = $date;
        return $this;
    }

    /**
     * getCreationDate
     * 
     * @access public
     * @return string
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
   
	/**
	 * Find lat and lng by venue location
     * @return array $geoCoordinates
     */
    public function getGeoCoordinates( $needs_to_update = false )
    {
        if ( $this->getType() == 'simple' ) {
            if ( $this->lat && $this->lng && !$needs_to_update ) {
				$this->geoCoordinates = array('lat' => $this->lat, 'lng' => $this->lng,'east' => null, 'north' => null, 'south' => null, 'west' => null);
				return $this->geoCoordinates;
			}
			if ( null === $this->geoCoordinates ) {
				$objCity = $this->getCity();
				$objState = $objCity->getState();
				$objCountry = $objState->getCountry();
				if ( $objCountry->id == 1 || $objCountry->id == 38 ) { // USA and Canada - location by zipcode
					if ( $this->getZipcode() ) {
//						if ( $objCountry->checkZipcode( $this->getZipcode() ) ) {
//							$objZip = Warecorp_Location_Zipcode::createByZip( $this->getZipcode() );
//							$this->geoCoordinates = array('lat' => $objZip->latitude, 'lng' => $objZip->longitude, 'east' => null, 'north' => null, 'south' => null, 'west' => null);
//						} else {
							$query = $this->getAddress1().', '.$objCity->name.', '.$objState->name.', '.$this->getZipcode();
							$coordinates = Warecorp_Location_Alias_List::coordinatesAliasByQueryString($query, $objCountry->id);
							if ( !empty($coordinates) && sizeof($coordinates) == 1 ) $this->geoCoordinates = $coordinates[0]; 
							elseif ( !empty($coordinates) ) $this->geoCoordinates = $coordinates[0];                      
//						}
					} else {
						$query = $this->getAddress1().', '.$objCity->name.', '.$objState->name;
						$coordinates = Warecorp_Location_Alias_List::coordinatesAliasByQueryString($query, $objCountry->id);
							if ( !empty($coordinates) && sizeof($coordinates) == 1 ) $this->geoCoordinates = $coordinates[0]; 
							elseif ( !empty($coordinates) ) $this->geoCoordinates = $coordinates[0];                      
					}
				} else {
					$query = $this->getAddress1().', '.$objCity->name;
					$coordinates = Warecorp_Location_Alias_List::coordinatesAliasByQueryString($query, $objCountry->id);
					if ( !empty($coordinates) && sizeof($coordinates) == 1 ) $this->geoCoordinates = $coordinates[0]; 
					elseif ( !empty($coordinates) ) $this->geoCoordinates = $coordinates[0];                      
				}
			}
            //  Save coordinates for venue
            if ( null !== $this->geoCoordinates ) {
                $this->saveLatLng( $this->geoCoordinates['lat'], $this->geoCoordinates['lng'] );
            }
        }
        return $this->geoCoordinates;
    }
	
    /**
     * 
     * @return unknown_type
     */
	public function getGoogleQueryLatLng()
	{
		$query = '';
		if ( $this->getType() == 'simple' ) {
			$query = 'http://maps.google.com/maps?q=';
			
            $objCity = $this->getCity();
            $objState = $objCity->getState();
            $objCountry = $objState->getCountry();
            $name = str_replace("(", "[", $this->getName());
            $name = str_replace(")", "]", $name);
			
            $query .= $this->getAddress1().', ';
            $query .= $objCity->name.', ';
            $query .= $objState->name.', ';
            $query .= $objCountry->name;
            if ( $this->getZipcode() ) $query .= ', '.$this->getZipcode();
            $query .= '+('.$name.')';
            
            $query = preg_replace("/\s{1,}/mi", '+', $query);
            
			if ( $this->geoCoordinates = $this->getGeoCoordinates() ) {
				$query .= '@'.$this->geoCoordinates['lat'].','.$this->geoCoordinates['lng'];
			}
			
			$query .= '&z=15';
		}
		return $query;
	}
    
	/**
	 * Save item
	 */
	public function save()
	{
	   parent::save();
	   $this->getGeoCoordinates( true );
	}
	
	/**
	 * update geocoordinate to vanue
	 * @param double $lat
	 * @param double $lng
	 * @throws Exception
	 */
	public function saveLatLng( $lat, $lng )
	{
	    if ( !$this->getId() ) throw new Exception("Save venue first");
	    $data = array();
	    $data['lat'] = $lat;
	    $data['lng'] = $lng;	    
        $where = $this->_db->quoteInto('id = ?', $this->getId());
        $this->_db->update($this->tableName, $data, $where);
	
	}
	
    /**
     * Delete a venue
     *
     * @return boolean
     * @author Eugene Kirdzei
     */
    public function delete(){
        $result = $this->_db->delete('zanby_event__event_venue', $this->_db->quoteInto('venue_id = ?', $this->id));
        parent::delete();
        return true;
    }
}
?>
